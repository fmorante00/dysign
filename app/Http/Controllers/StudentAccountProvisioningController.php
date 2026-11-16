<?php

namespace App\Http\Controllers;

use App\Jobs\ProvisionStudentAccounts;
use App\Mail\AccountInvitationMail;
use App\Models\AccountInvitation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentAccountProvisioningController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT ACCOUNT PROVISIONING OVERVIEW
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->authorizeAdministrator();

        [$summary, $records] = $this->evaluateStudents();

        return view(
            'students.accounts.index',
            compact('summary', 'records')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOMATED BULK ACCOUNT PROVISIONING
    |--------------------------------------------------------------------------
    */

    public function provision(Request $request)
    {
        $this->authorizeAdministrator();

        /*
         * Require explicit Administrator confirmation.
         */
        $request->validate([
            'confirm_provisioning' => [
                'required',
                'accepted',
            ],
        ]);

        /*
         * Recheck eligibility using current database records.
         * Never trust student IDs submitted by the browser.
         */
        [$summary, $records] = $this->evaluateStudents();

        /*
         * Only process eligible students.
         */
        $eligibleStudentIds = $records
            ->filter(function ($record) {
                return $record['status'] === 'eligible';
            })
            ->pluck('student_id')
            ->values();

        /*
         * No eligible students found.
         */
        if ($eligibleStudentIds->isEmpty()) {
            return redirect()
                ->route('students.accounts.index')
                ->with(
                    'info',
                    'No eligible student accounts were found for provisioning.'
                );
        }

        // Free shared hosting cannot run a permanent queue worker.
        // Process a bounded number of eligible accounts during this request.
        // Rechecking eligibility on every submission prevents duplicate users.
        $selectedIds = $eligibleStudentIds->take(3)->values()->all();

        try {
            $result = (new ProvisionStudentAccounts($selectedIds))->handle();
        } catch (\Throwable $exception) {
            Log::error('Student provisioning request failed.', [
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('students.accounts.index')
                ->with('error', 'Unable to process student accounts. Check the Laravel log; no batch-wide success was assumed.');
        }

        $remaining = max(0, $eligibleStudentIds->count() - count($selectedIds));
        $message = $result['created'] . ' student account(s) created; ' .
            $result['skipped'] . ' skipped; ' .
            $result['failed'] . ' failed. ';

        $message .= $result['emails_accepted'] .
            ' invitation email(s) handed to the configured mail transport. ';
        if ($result['emails_failed'] > 0) {
            $message .= $result['emails_failed'] .
                ' invitation email attempt(s) failed. Accounts were kept; use Pending Invitation Links to retry sending. ';
        }
        if ($remaining > 0) {
            $message .= 'More eligible students remain; run provisioning again after reviewing these results.';
        }

        return redirect()
            ->route('students.accounts.index')
            ->with(($result['failed'] > 0 || $result['emails_failed'] > 0) ? 'warning' : 'success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ACCOUNT ELIGIBILITY EVALUATION
    |--------------------------------------------------------------------------
    */

    private function evaluateStudents(): array
    {
        /*
         * Retrieve imported student records.
         */
        $students = Student::query()
            ->with('user.role')
            ->orderBy('student_number')
            ->get();

        /*
         * Count normalized student email addresses.
         */
        $studentEmailCounts = Student::query()
            ->whereNotNull('email')
            ->pluck('email')
            ->map(function ($email) {
                return Str::lower(
                    trim((string) $email)
                );
            })
            ->filter()
            ->countBy();

        /*
         * Retrieve emails already registered
         * in the users table.
         */
        $existingUserEmails = User::query()
            ->pluck('email')
            ->map(function ($email) {
                return Str::lower(
                    trim((string) $email)
                );
            })
            ->filter()
            ->flip();

        /*
         * Initialize validation counters.
         */
        $summary = [
            'total_students' => $students->count(),
            'already_provisioned' => 0,
            'eligible' => 0,
            'inactive' => 0,
            'missing_email' => 0,
            'invalid_email' => 0,
            'duplicate_email' => 0,
            'email_in_use' => 0,
        ];

        $records = collect();

        /*
         * Automatically classify each student.
         */
        foreach ($students as $student) {

            $email = Str::lower(
                trim((string) ($student->email ?? ''))
            );

            $status = 'eligible';

            /*
             * Check if an account already exists.
             */
            if ($student->user !== null) {

                $status = 'already_provisioned';

            } elseif ($student->status !== 'Active') {

                $status = 'inactive';

            } elseif ($email === '') {

                $status = 'missing_email';

            } elseif (
                strlen($email) > 255 ||
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            ) {

                $status = 'invalid_email';

            } elseif (
                $studentEmailCounts->get($email, 0) > 1
            ) {

                $status = 'duplicate_email';

            } elseif (
                $existingUserEmails->has($email)
            ) {

                $status = 'email_in_use';

            }

            /*
             * Update corresponding counter.
             */
            $summary[$status]++;

            /*
             * Prepare record for the Administrator.
             */
            $records->push([
                'student_id' => $student->student_id,
                'student_number' => $student->student_number,

                'name' => trim(
                    $student->first_name . ' ' .
                    ($student->middle_name ?? '') . ' ' .
                    $student->last_name
                ),

                'email' => $student->email,
                'college' => $student->college,
                'program_code' => $student->program_code,
                'status' => $status,

                // Provide read-only account state for the management controls.
                // The student master record remains unchanged.
                'account_user_id' => $student->user?->user_id,
                'account_is_student_role' => $student->user?->role?->role_name === 'Student Account',
                'account_status' => $student->user?->status,
                'account_setup_pending' => (bool) ($student->user?->must_change_password ?? false),
                'account_deactivated_at' => $student->user?->deactivated_at,
                'student_record_active' => $student->status === 'Active',
            ]);
        }

        return [$summary, $records];
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ACCOUNT STATUS MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Deactivate an existing Student Account.
     */
    /**
     * Administrator-only review and recovery of pending student setup links.
     * Tokens must never be exposed on public routes or general student lists.
     */
    public function invitations()
    {
        $this->authorizeAdministrator();

        $pendingUsers = User::query()
            ->with(['role', 'student'])
            ->where('must_change_password', true)
            ->where('status', 'Inactive')
            ->whereNull('deactivated_at')
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'Student Account');
            })
            ->whereHas('student', function ($query) {
                $query->where('status', 'Active');
            })
            ->orderBy('name')
            ->get();

        $invitationLinks = AccountInvitation::query()
            ->whereIn('user_id', $pendingUsers->pluck('user_id')->all())
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('invitation_id')
            ->get()
            ->unique('user_id')
            ->keyBy('user_id');

        return response()
            ->view('students.accounts.invitations', compact('pendingUsers', 'invitationLinks'))
            ->header('Cache-Control', 'private, no-store, max-age=0')
            ->header('Referrer-Policy', 'no-referrer');
    }

    /**
     * Reissue an expired or missing first-time setup link. Never reactivate
     * a deliberately deactivated account or overwrite a completed password.
     */
    public function reissueInvitation(int $id)
    {
        $this->authorizeAdministrator();

        DB::transaction(function () use ($id) {
            $user = User::query()
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $user->role?->role_name === 'Student Account' &&
                $user->student?->status === 'Active' &&
                $user->status === 'Inactive' &&
                $user->must_change_password &&
                $user->deactivated_at === null,
                422,
                'Only pending, non-deactivated student accounts may receive a setup link.'
            );

            $validInvitationExists = AccountInvitation::query()
                ->where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->exists();

            abort_if($validInvitationExists, 422, 'This student already has a valid setup link.');

            AccountInvitation::query()
                ->where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->update(['used_at' => now()]);

            AccountInvitation::create([
                'user_id' => $user->getKey(),
                'token' => Str::random(64),
                'expires_at' => now()->addDay(),
                'used_at' => null,
            ]);
        }, 3);

        return redirect()
            ->route('students.accounts.invitations')
            ->with('success', 'A new private setup link was generated for the pending student account.');
    }

    /**
     * Deliver or re-send a first-time student invitation to the registered
     * student email. Never reset a completed or deactivated account.
     * A valid invitation is reused rather than replaced.
     */
    public function emailInvitation(int $id)
    {
        $this->authorizeAdministrator();

        $invitation = DB::transaction(function () use ($id) {
            $user = User::query()->whereKey($id)->lockForUpdate()->firstOrFail();
            abort_unless(
                $user->role?->role_name === 'Student Account' &&
                $user->student?->status === 'Active' &&
                $user->status === 'Inactive' &&
                $user->must_change_password &&
                $user->deactivated_at === null,
                422,
                'Only pending, non-deactivated student accounts may be emailed.'
            );

            $email = Str::lower(trim((string) $user->email));
            $studentEmail = Str::lower(trim((string) $user->student->email));
            abort_unless(
                $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) &&
                hash_equals($studentEmail, $email),
                422,
                'The student and account email addresses must match before an invitation can be sent.'
            );

            $existing = AccountInvitation::query()
                ->where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->orderByDesc('invitation_id')
                ->first();
            if ($existing) {
                return $existing;
            }

            AccountInvitation::query()
                ->where('user_id', $user->getKey())
                ->whereNull('used_at')
                ->update(['used_at' => now()]);

            return AccountInvitation::create([
                'user_id' => $user->getKey(),
                'token' => Str::random(64),
                'expires_at' => now()->addDay(),
                'used_at' => null,
            ]);
        }, 3);

        try {
            if (in_array(config('mail.default'), ['log', 'array'], true)) {
                throw new \RuntimeException('The configured mailer does not deliver emails. Configure an external mail provider.');
            }

            Mail::to($invitation->user->email)->send(new AccountInvitationMail($invitation));
            return redirect()->route('students.accounts.invitations')
                ->with('success', 'Invitation handed to the email provider. Inbox delivery is not yet confirmed.');
        } catch (\Throwable $exception) {
            Log::error('Student invitation resend failed.', [
                'user_id' => $id,
                'error' => $exception->getMessage(),
            ]);
            return redirect()->route('students.accounts.invitations')
                ->with('error', 'Invitation email could not be sent. The student account and existing invitation were preserved. Check server mail logs and retry.');
        }
    }
    public function deactivate(int $id)
    {
        $this->authorizeAdministrator();

        DB::transaction(function () use ($id) {
            $user = User::query()
                ->lockForUpdate()
                ->findOrFail($id);

            abort_unless(
                $user->role?->role_name === 'Student Account',
                404
            );

            $user->status = 'Inactive';
            $user->deactivated_at = now();
            $user->save();
        });

        return redirect()
            ->route('students.accounts.index')
            ->with('success', 'Student account deactivated successfully.');
    }

    /**
     * Reactivate a Student Account without skipping first-time setup.
     */
    public function activate(int $id)
    {
        $this->authorizeAdministrator();

        $result = DB::transaction(function () use ($id) {
            $user = User::query()
                ->lockForUpdate()
                ->findOrFail($id);

            abort_unless(
                $user->role?->role_name === 'Student Account',
                404
            );

            abort_unless(
                $user->student && $user->student->status === 'Active',
                422,
                'The linked student record is inactive or missing.'
            );

            // A newly provisioned account is not administratively deactivated.
            // It must complete its invitation setup before becoming Active.
            if (
                $user->deactivated_at === null &&
                $user->status === 'Inactive' &&
                $user->must_change_password
            ) {
                return 'pending';
            }

            if (
                $user->deactivated_at === null &&
                $user->status === 'Active'
            ) {
                return 'already_active';
            }

            $user->deactivated_at = null;
            $user->status = $user->must_change_password
                ? 'Inactive'
                : 'Active';
            $user->save();

            return $user->must_change_password ? 'pending' : 'activated';
        }, 3);

        if ($result === 'pending') {
            return redirect()
                ->route('students.accounts.index')
                ->with('info', 'The student account is awaiting invitation setup.');
        }

        if ($result === 'already_active') {
            return redirect()
                ->route('students.accounts.index')
                ->with('info', 'The student account is already active.');
        }

        return redirect()
            ->route('students.accounts.index')
            ->with('success', 'Student account reactivated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    private function authorizeAdministrator(): void
    {
        abort_unless(
            auth()->user()?->role?->role_name === 'Administrator',
            403
        );
    }
}
