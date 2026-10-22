<?php

namespace App\Http\Controllers;

use App\Jobs\ProvisionStudentAccounts;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
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

        /*
         * Divide students into manageable batches.
         *
         * Each batch is processed by a Laravel queue worker.
         */
        $batches = $eligibleStudentIds->chunk(50);

        /*
         * Dispatch background jobs.
         */
        foreach ($batches as $batch) {
            ProvisionStudentAccounts::dispatch(
                $batch->values()->all()
            );
        }

        /*
         * Return immediately without waiting for
         * account creation or invitation processing.
         */
        return redirect()
            ->route('students.accounts.index')
            ->with(
                'success',
                $eligibleStudentIds->count() .
                ' eligible student records have been queued for account provisioning. ' .
                'Accounts and invitations will be processed in the background.'
            );
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
            ->with('user')
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
            ]);
        }

        return [$summary, $records];
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
