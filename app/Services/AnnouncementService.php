<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Student;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AnnouncementService
{
    /**
     * Resolve the recipients of an announcement.
     */
    public function resolveRecipients(
        array $data,
        Event $event
    ): Collection {

        $recipientType =
            $data['recipient_type'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | ALL ACTIVE STUDENTS
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'all_students') {

            return $this->studentQuery()
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | SPECIFIC STUDENTS
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'specific_students') {

            $studentIds =
                $data['specific_student_ids'] ?? [];


            if (empty($studentIds)) {

                throw ValidationException::withMessages([
                    'specific_student_ids' =>
                        'Please select at least one student.',
                ]);
            }


            return $this->studentQuery()
                ->whereIn(
                    'student_id',
                    $studentIds
                )
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENTS BY COLLEGE
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'college') {

            $college =
                $data['college'] ?? null;


            if (!$college) {

                throw ValidationException::withMessages([
                    'college' =>
                        'Please select a college.',
                ]);
            }


            return $this->studentQuery()
                ->where(
                    'college',
                    $college
                )
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENTS BY YEAR LEVEL
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'year') {

            $yearLevel =
                $data['year_level'] ?? null;


            if (!$yearLevel) {

                throw ValidationException::withMessages([
                    'year_level' =>
                        'Please select a year level.',
                ]);
            }


            return $this->studentQuery()
                ->where(
                    'year_level',
                    $yearLevel
                )
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | STUDENTS BY COLLEGE + YEAR LEVEL
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'college_year') {

            $college =
                $data['college'] ?? null;

            $yearLevel =
                $data['year_level'] ?? null;


            if (!$college) {

                throw ValidationException::withMessages([
                    'college' =>
                        'Please select a college.',
                ]);
            }


            if (!$yearLevel) {

                throw ValidationException::withMessages([
                    'year_level' =>
                        'Please select a year level.',
                ]);
            }


            return $this->studentQuery()
                ->where(
                    'college',
                    $college
                )
                ->where(
                    'year_level',
                    $yearLevel
                )
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | EXPECTED EVENT PARTICIPANTS
        |--------------------------------------------------------------------------
        |
        | Uses the official event_eligible_students roster generated by
        | Event Participant Eligibility Management.
        |
        */

        if ($recipientType === 'expected_participants') {

            $students =
                Student::query()
                    ->join(
                        'event_eligible_students as ees',
                        'ees.student_id',
                        '=',
                        'students.student_id'
                    )
                    ->where(
                        'ees.event_id',
                        $event->event_id
                    )
                    ->where(
                        'students.status',
                        'Active'
                    )
                    ->whereNotNull(
                        'students.email'
                    )
                    ->where(
                        'students.email',
                        '!=',
                        ''
                    )
                    ->select(
                        'students.*'
                    )
                    ->distinct()
                    ->get()
                    ->filter(
                        fn ($student) =>
                        filter_var(
                            $student->email,
                            FILTER_VALIDATE_EMAIL
                        )
                    )
                    ->values();


            if ($students->isEmpty()) {

                throw ValidationException::withMessages([
                    'recipient_type' =>
                        'No eligible participants were found for this event.',
                ]);
            }


            return $students
                ->map(
                    fn ($student) =>
                    $this->studentRecipient($student)
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | ASSIGNED ATTENDANCE PERSONNEL
        |--------------------------------------------------------------------------
        */

        if ($recipientType === 'assigned_personnel') {

            return DB::table(
                    'event_assignments as ea'
                )
                ->join(
                    'personnel as p',
                    'p.personnel_id',
                    '=',
                    'ea.personnel_id'
                )
                ->join(
                    'users as u',
                    'u.user_id',
                    '=',
                    'p.user_id'
                )
                ->where(
                    'ea.event_id',
                    $event->event_id
                )
                ->where(
                    'u.status',
                    'Active'
                )
                ->whereNotNull(
                    'u.email'
                )
                ->where(
                    'u.email',
                    '!=',
                    ''
                )
                ->select([
                    'u.user_id',
                    'u.name',
                    'u.email',
                ])
                ->distinct()
                ->get()
                ->filter(
                    fn ($user) =>
                    filter_var(
                        $user->email,
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->map(
                    fn ($user) => [

                        'student_id' =>
                            null,

                        'user_id' =>
                            $user->user_id,

                        'recipient_type' =>
                            'Personnel',

                        'name' =>
                            $user->name,

                        'email' =>
                            $user->email,

                    ]
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | INVALID RECIPIENT TYPE
        |--------------------------------------------------------------------------
        */

        throw ValidationException::withMessages([
            'recipient_type' =>
                'The selected recipient type is invalid.',
        ]);
    }


    /**
     * Base query for students eligible
     * to receive announcement emails.
     */
    private function studentQuery()
    {
        return Student::query()
            ->where(
                'status',
                'Active'
            )
            ->whereNotNull(
                'email'
            )
            ->where(
                'email',
                '!=',
                ''
            );
    }


    /**
     * Convert a student record into
     * announcement recipient data.
     */
    private function studentRecipient(
        Student $student
    ): array {

        $name = trim(

            $student->first_name

            . ' '

            . (
                $student->middle_name
                    ? $student->middle_name . ' '
                    : ''
            )

            . $student->last_name

        );


        return [

            'student_id' =>
                $student->student_id,

            'user_id' =>
                null,

            'recipient_type' =>
                'Student',

            'name' =>
                $name,

            'email' =>
                $student->email,

        ];
    }


    /**
     * Convert frontend recipient type
     * to the existing announcements
     * audience_type database enum.
     */
    public function mapAudienceType(
        string $recipientType
    ): string {

        return match ($recipientType) {

            'all_students' =>
                'All',

            'college' =>
                'College',

            'year' =>
                'Year',

            'college_year' =>
                'CollegeYear',

            'specific_students',
            'assigned_personnel',
            'expected_participants' =>
                'Import',

            default =>
                'Import',

        };
    }
}