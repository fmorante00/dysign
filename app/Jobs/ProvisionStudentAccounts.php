<?php

namespace App\Jobs;

use App\Mail\AccountInvitationMail;
use App\Models\AccountInvitation;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProvisionStudentAccounts implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120;

    /**
     * Create a new provisioning job.
     *
     * Student IDs are supplied only after
     * Administrator confirmation.
     */
    public function __construct(
        public array $studentIds
    ) {
    }

    /**
     * Execute automated student account provisioning.
     */
    public function handle(): void
    {
        /*
         * The account, invitation, and queued email must be written to the
         * same database transaction. This job currently supports the
         * database queue on DySign's default database connection.
         *
         * Stop safely if the queue is reconfigured; an outbox / recovery
         * workflow is required before using an external queue connection.
         */
        $queueDatabase = config('queue.connections.database.connection');
        $defaultDatabase = config('database.default');

        if (
            config('queue.default') !== 'database' ||
            ($queueDatabase !== null && $queueDatabase !== $defaultDatabase)
        ) {
            throw new \RuntimeException(
                'Student account provisioning requires the database queue on the default database connection.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENT ACCOUNT ROLE
        |--------------------------------------------------------------------------
        */

        $role = Role::where('role_name', 'Student Account')
            ->where('status', 'Active')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PROCESSING COUNTERS
        |--------------------------------------------------------------------------
        */

        $created = 0;
        $skipped = 0;
        $failed = 0;
        $invitationsQueued = 0;

        /*
        |--------------------------------------------------------------------------
        | PROCESS STUDENT IDS
        |--------------------------------------------------------------------------
        */

        $studentIds = array_unique(
            array_map('intval', $this->studentIds)
        );

        foreach ($studentIds as $studentId) {

            if ($studentId <= 0) {
                $skipped++;
                continue;
            }

            try {

                /*
                |--------------------------------------------------------------
                | CREATE ACCOUNT SAFELY
                |--------------------------------------------------------------
                */

                $result = DB::transaction(
                    function () use ($studentId, $role) {

                        $student = Student::whereKey($studentId)
                            ->lockForUpdate()
                            ->first();

                        if (!$student) {
                            return null;
                        }

                        /*
                         * Only active students can receive accounts.
                         */
                        if ($student->status !== 'Active') {
                            return null;
                        }

                        /*
                         * Prevent duplicate student accounts.
                         */
                        if ($student->user()->exists()) {
                            return null;
                        }

                        /*
                         * Normalize student email.
                         */
                        $email = Str::lower(
                            trim((string) $student->email)
                        );

                        /*
                         * Validate student email.
                         */
                        if (
                            $email === '' ||
                            strlen($email) > 255 ||
                            !filter_var(
                                $email,
                                FILTER_VALIDATE_EMAIL
                            )
                        ) {
                            return null;
                        }

                        /*
                         * Detect another student using this email.
                         */
                        $duplicateStudentEmail = Student::whereRaw(
                            'LOWER(TRIM(email)) = ?',
                            [$email]
                        )
                            ->where(
                                'student_id',
                                '<>',
                                $student->student_id
                            )
                            ->exists();

                        if ($duplicateStudentEmail) {
                            return null;
                        }

                        /*
                         * Prevent conflicts with existing user emails.
                         */
                        $emailExists = User::whereRaw(
                            'LOWER(TRIM(email)) = ?',
                            [$email]
                        )->exists();

                        if ($emailExists) {
                            return null;
                        }

                        /*
                         * Generate a unique username.
                         */
                        $baseUsername = 'student.' .
                            $student->student_id;

                        $username = $baseUsername;
                        $suffix = 2;

                        while (
                            User::where(
                                'username',
                                $username
                            )->exists()
                        ) {
                            $username = $baseUsername .
                                '.' . $suffix;

                            $suffix++;
                        }

                        /*
                         * Prepare full name.
                         */
                        $fullName = trim(
                            $student->first_name . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            $student->last_name
                        );

                        /*
                         * Create student login account.
                         */
                        $user = new User();

                        $user->name = $fullName;
                        $user->username = $username;
                        $user->email = $email;

                        $user->password = Hash::make(
                            Str::random(64)
                        );

                        $user->role_id = $role->role_id;

                        $user->department_id = null;

                        /*
                         * Account activates after setup.
                         */
                        $user->status = 'Inactive';

                        $user->must_change_password = true;

                        /*
                         * Link official student record.
                         */
                        $user->student_id = $student->student_id;

                        $user->save();

                        /*
                         * Generate invitation.
                         */
                        $invitation = AccountInvitation::create([
                            'user_id' => $user->user_id,
                            'token' => Str::random(64),
                            'expires_at' => now()->addDay(),
                            'used_at' => null,
                        ]);

                        /*
                         * Queue the invitation inside the same DB transaction.
                         * For DySign's database queue, this means an exception
                         * while inserting the email job rolls back the user
                         * and invitation too. The worker cannot see the job
                         * until the transaction commits.
                         *
                         * beforeCommit() deliberately keeps this queue insert
                         * in the transaction, even if the queue configuration
                         * is later set to dispatch jobs after commit.
                         */
                        Mail::to($email)->queue(
                            (new AccountInvitationMail($invitation))
                                ->onConnection('database')
                                ->beforeCommit()
                        );

                        return true;

                    },
                    3
                );

                /*
                |--------------------------------------------------------------
                | SKIPPED STUDENT
                |--------------------------------------------------------------
                */

                if ($result === null) {
                    $skipped++;
                    continue;
                }

                // Both the account and invitation email job were committed.
                $created++;
                $invitationsQueued++;

            } catch (\Throwable $exception) {

                $failed++;

                Log::error(
                    'Student account provisioning failed.',
                    [
                        'student_id' => $studentId,
                        'error' => $exception->getMessage(),
                    ]
                );

            }
        }

        /*
        |--------------------------------------------------------------------------
        | PROCESSING SUMMARY
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Bulk student account provisioning completed.',
            [
                'created' => $created,
                'skipped' => $skipped,
                'failed' => $failed,
                'invitations_queued' => $invitationsQueued,
            ]
        );
    }
}
