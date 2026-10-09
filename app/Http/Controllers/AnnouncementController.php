<?php

namespace App\Http\Controllers;

use App\Mail\StudentAnnouncementMail;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\AnnouncementRecipient;
use App\Models\Event;
use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AnnouncementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Announcement Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $announcements = Announcement::with([
            'event',
            'creator',
            'attachments',
            'recipients',
        ])
        ->latest('created_at')
        ->get();


        $events = Event::whereNotIn('status', [
            'Completed',
            'Cancelled',
        ])
        ->orderBy('event_date')
        ->get();


        $colleges = Student::where('status', 'Active')
            ->whereNotNull('college')
            ->distinct()
            ->orderBy('college')
            ->pluck('college');


        $yearLevels = Student::where('status', 'Active')
            ->distinct()
            ->orderBy('year_level')
            ->pluck('year_level');


        /*
        |--------------------------------------------------------------------------
        | Used for Specific Student selection
        |--------------------------------------------------------------------------
        */

        $students = Student::where('status', 'Active')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get([
                'student_id',
                'student_number',
                'first_name',
                'middle_name',
                'last_name',
                'program_code',
                'year_level',
                'email',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalAnnouncements =
            Announcement::count();


        $sentNotifications =
            AnnouncementRecipient::where(
                'status',
                'Sent'
            )
            ->count();


        $totalRecipients =
            AnnouncementRecipient::count();


        /*
        |--------------------------------------------------------------------------
        | Scheduling is not yet implemented.
        |--------------------------------------------------------------------------
        */

        $upcomingReminders = 0;


        return view(
            'announcements.index',
            compact(
                'announcements',
                'events',
                'colleges',
                'yearLevels',
                'students',
                'totalAnnouncements',
                'sentNotifications',
                'totalRecipients',
                'upcomingReminders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create and Send Announcement
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'event_id' => [
                'required',
                'exists:events,event_id',
            ],

            'recipient_type' => [
                'required',
                'in:all_students,assigned_personnel,specific_students,college,year,college_year,expected_participants',
            ],

            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],

            'college' => [
                'nullable',
                'string',
                'max:255',
            ],

            'year_level' => [
                'nullable',
                'integer',
                'min:1',
                'max:6',
            ],

            'specific_student_ids' => [
                'nullable',
                'array',
            ],

            'specific_student_ids.*' => [
                'integer',
                'exists:students,student_id',
            ],

            'attachments' => [
                'nullable',
                'array',
                'max:5',
            ],

            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

        ]);


        $event = Event::findOrFail(
            $validated['event_id']
        );


        /*
        |--------------------------------------------------------------------------
        | Resolve Recipient List
        |--------------------------------------------------------------------------
        */

        $recipients = $this->resolveRecipients(
            $request,
            $event
        );


        if ($recipients->isEmpty()) {

            throw ValidationException::withMessages([
                'recipient_type' =>
                    'No valid email recipients were found for the selected audience.',
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Generate Subject if Empty
        |--------------------------------------------------------------------------
        */

        $subject = trim(
            $validated['subject'] ?? ''
        );


        if ($subject === '') {

            $subject =
                'DySign Announcement: '
                . $event->event_name;

        }


        $storedFiles = [];


        try {

            /*
            |--------------------------------------------------------------------------
            | Save Announcement and Attachments
            |--------------------------------------------------------------------------
            */

            $announcement = DB::transaction(
                function () use (
                    $request,
                    $validated,
                    $event,
                    $recipients,
                    $subject,
                    &$storedFiles
                ) {

                    $announcement =
                        Announcement::create([

                            'event_id' =>
                                $event->event_id,

                            'created_by' =>
                                auth()->id(),

                            'subject' =>
                                $subject,

                            'message' =>
                                $validated['message'],

                            'audience_type' =>
                                $this->mapAudienceType(
                                    $validated['recipient_type']
                                ),

                            'college' =>
                                $validated['college']
                                ?? null,

                            'year_level' =>
                                $validated['year_level']
                                ?? null,

                            'recipient_count' =>
                                $recipients->count(),

                            'status' =>
                                'Processing',

                            'sent_at' =>
                                null,

                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Store Attachments
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $request->file(
                            'attachments',
                            []
                        )
                        as $file
                    ) {

                        $path = $file->store(
                            'announcement_attachments',
                            'local'
                        );


                        $storedFiles[] = $path;


                        AnnouncementAttachment::create([

                            'announcement_id' =>
                                $announcement
                                    ->announcement_id,

                            'file_name' =>
                                $file->getClientOriginalName(),

                            'file_path' =>
                                $path,

                            'mime_type' =>
                                $file->getMimeType(),

                            'file_size' =>
                                $file->getSize(),

                        ]);

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Save Recipient Records
                    |--------------------------------------------------------------------------
                    */

                    foreach ($recipients as $recipient) {

                        AnnouncementRecipient::create([

                            'announcement_id' =>
                                $announcement
                                    ->announcement_id,

                            'student_id' =>
                                $recipient['student_id']
                                ?? null,

                            'user_id' =>
                                $recipient['user_id']
                                ?? null,

                            'recipient_type' =>
                                $recipient['recipient_type'],

                            'email' =>
                                $recipient['email'],

                            'status' =>
                                'Pending',

                        ]);

                    }


                    return $announcement;

                }
            );

        }
        catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Delete uploaded files if database saving fails
            |--------------------------------------------------------------------------
            */

            foreach ($storedFiles as $path) {

                Storage::disk('local')
                    ->delete($path);

            }

            throw $exception;

        }


        /*
        |--------------------------------------------------------------------------
        | Reload Relationships
        |--------------------------------------------------------------------------
        */

        $announcement->load([
            'event',
            'attachments',
            'recipients',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prepare Attachment Data for Email
        |--------------------------------------------------------------------------
        */

        $mailAttachments =
            $announcement
                ->attachments
                ->map(function ($attachment) {

                    return [

                        'path' =>
                            $attachment->file_path,

                        'name' =>
                            $attachment->file_name,

                        'mime' =>
                            $attachment->mime_type,

                    ];

                })
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Send Emails
        |--------------------------------------------------------------------------
        |
        | For now we send individually so we can record Sent / Failed
        | correctly for every recipient.
        |
        */

        $sentCount = 0;

        $failedCount = 0;


        foreach (
            $announcement->recipients
            as $recipientRecord
        ) {

            try {

                /*
                |--------------------------------------------------------------------------
                | Determine Recipient Name
                |--------------------------------------------------------------------------
                */

                $recipientName =
                    $this->recipientName(
                        $recipientRecord
                    );


                /*
                |--------------------------------------------------------------------------
                | Send Email
                |--------------------------------------------------------------------------
                */

                Mail::to(
                    $recipientRecord->email
                )
                ->send(
                    new StudentAnnouncementMail(
                        $announcement,
                        $recipientName,
                        $mailAttachments
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Mark Successful Recipient
                |--------------------------------------------------------------------------
                */

                $recipientRecord->update([

                    'status' =>
                        'Sent',

                    'sent_at' =>
                        now(),

                    'error_message' =>
                        null,

                ]);


                $sentCount++;

            }
            catch (Throwable $exception) {

                /*
                |--------------------------------------------------------------------------
                | Mark Failed Recipient
                |--------------------------------------------------------------------------
                */

                $recipientRecord->update([

                    'status' =>
                        'Failed',

                    'error_message' =>
                        Str::limit(
                            $exception->getMessage(),
                            1000
                        ),

                ]);


                $failedCount++;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Update Overall Announcement Status
        |--------------------------------------------------------------------------
        */

        if (
            $sentCount > 0 &&
            $failedCount === 0
        ) {

            $announcement->update([

                'status' =>
                    'Sent',

                'sent_at' =>
                    now(),

            ]);

        }
        elseif (
            $sentCount > 0 &&
            $failedCount > 0
        ) {

            $announcement->update([

                'status' =>
                    'Partially Sent',

                'sent_at' =>
                    now(),

            ]);

        }
        else {

            $announcement->update([

                'status' =>
                    'Failed',

                'sent_at' =>
                    null,

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        if ($sentCount > 0) {

            return redirect()
                ->route('announcements.index')
                ->with(
                    'success',
                    "Announcement sent to {$sentCount} recipient(s)."
                );

        }


        return redirect()
            ->route('announcements.index')
            ->with(
                'error',
                'The announcement was saved, but no emails were successfully sent.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Preview Recipients
    |--------------------------------------------------------------------------
    */

    public function previewRecipients(
        Request $request
    ) {

        $request->validate([

            'event_id' => [
                'required',
                'exists:events,event_id',
            ],

            'recipient_type' => [
                'required',
                'in:all_students,assigned_personnel,specific_students,college,year,college_year,expected_participants',
            ],

            'college' => [
                'nullable',
                'string',
            ],

            'year_level' => [
                'nullable',
                'integer',
            ],

            'specific_student_ids' => [
                'nullable',
                'array',
            ],

            'specific_student_ids.*' => [
                'integer',
                'exists:students,student_id',
            ],

        ]);


        $event = Event::findOrFail(
            $request->event_id
        );


        $recipients =
            $this->resolveRecipients(
                $request,
                $event
            );


        return response()->json([

            'count' =>
                $recipients->count(),

            'recipients' =>
                $recipients
                    ->take(10)
                    ->map(function ($recipient) {

                        return [

                            'name' =>
                                $recipient['name'],

                            'email' =>
                                $recipient['email'],

                            'type' =>
                                $recipient[
                                    'recipient_type'
                                ],

                        ];

                    })
                    ->values(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve Recipient Audience
    |--------------------------------------------------------------------------
    */

    private function resolveRecipients(
        Request $request,
        Event $event
    ) {

        $recipientType =
            $request->recipient_type;


        /*
        |--------------------------------------------------------------------------
        | All Active Students
        |--------------------------------------------------------------------------
        */

        if (
            $recipientType ===
            'all_students'
        ) {

            return Student::where(
                    'status',
                    'Active'
                )
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient(
                        $student
                    )
                )
                ->filter(
                    fn ($recipient) =>
                    filter_var(
                        $recipient['email'],
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->values();

        }


        /*
        |--------------------------------------------------------------------------
        | Specific Students
        |--------------------------------------------------------------------------
        */

        if (
            $recipientType ===
            'specific_students'
        ) {

            $studentIds =
                $request->input(
                    'specific_student_ids',
                    []
                );


            if (empty($studentIds)) {

                throw ValidationException::withMessages([
                    'specific_student_ids' =>
                        'Please select at least one student.',
                ]);

            }


            return Student::whereIn(
                    'student_id',
                    $studentIds
                )
                ->where(
                    'status',
                    'Active'
                )
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient(
                        $student
                    )
                )
                ->filter(
                    fn ($recipient) =>
                    filter_var(
                        $recipient['email'],
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->values();

        }


        /*
        |--------------------------------------------------------------------------
        | Students by College
        |--------------------------------------------------------------------------
        */

        if (
            $recipientType ===
            'college'
        ) {

            if (!$request->filled('college')) {

                throw ValidationException::withMessages([
                    'college' =>
                        'Please select a college.',
                ]);

            }


            return Student::where(
                    'status',
                    'Active'
                )
                ->where(
                    'college',
                    $request->college
                )
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient(
                        $student
                    )
                )
                ->filter(
                    fn ($recipient) =>
                    filter_var(
                        $recipient['email'],
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->values();

        }


        /*
        |--------------------------------------------------------------------------
        | Students by Year
        |--------------------------------------------------------------------------
        */

        if (
            $recipientType ===
            'year'
        ) {

            if (!$request->filled('year_level')) {

                throw ValidationException::withMessages([
                    'year_level' =>
                        'Please select a year level.',
                ]);

            }


            return Student::where(
                    'status',
                    'Active'
                )
                ->where(
                    'year_level',
                    $request->year_level
                )
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient(
                        $student
                    )
                )
                ->filter(
                    fn ($recipient) =>
                    filter_var(
                        $recipient['email'],
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->values();

        }


        /*
        |--------------------------------------------------------------------------
        | Students by College + Year
        |--------------------------------------------------------------------------
        */

        if (
            $recipientType ===
            'college_year'
        ) {

            if (!$request->filled('college')) {

                throw ValidationException::withMessages([
                    'college' =>
                        'Please select a college.',
                ]);

            }


            if (!$request->filled('year_level')) {

                throw ValidationException::withMessages([
                    'year_level' =>
                        'Please select a year level.',
                ]);

            }


            return Student::where(
                    'status',
                    'Active'
                )
                ->where(
                    'college',
                    $request->college
                )
                ->where(
                    'year_level',
                    $request->year_level
                )
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get()
                ->map(
                    fn ($student) =>
                    $this->studentRecipient(
                        $student
                    )
                )
                ->filter(
                    fn ($recipient) =>
                    filter_var(
                        $recipient['email'],
                        FILTER_VALIDATE_EMAIL
                    )
                )
                ->values();

        }


        /*
        |--------------------------------------------------------------------------
        | Assigned Personnel
        |--------------------------------------------------------------------------
        |
        | event_assignments
        |      ↓
        | personnel
        |      ↓
        | users.email
        |
        */

        if (
            $recipientType ===
            'assigned_personnel'
        ) {

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
        | Expected Participants
        |--------------------------------------------------------------------------
        |
        | Not implemented yet because DySign currently has no official
        | event eligibility / expected participant table that identifies
        | exactly which students are required to attend an event.
        |
        */

        if (
            $recipientType ===
            'expected_participants'
        ) {

            throw ValidationException::withMessages([

                'recipient_type' =>
                    'Expected Participants cannot be used yet because event eligibility has not been configured.',

            ]);

        }


        return collect();
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Student to Recipient Data
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | Determine Name from Recipient Record
    |--------------------------------------------------------------------------
    */

    private function recipientName(
        AnnouncementRecipient $recipient
    ): string {

        if (
            $recipient->recipient_type ===
            'Student'
        ) {

            $student =
                Student::find(
                    $recipient->student_id
                );


            if ($student) {

                return trim(
                    $student->first_name
                    . ' '
                    . $student->last_name
                );

            }

        }


        if (
            $recipient->recipient_type ===
            'Personnel'
        ) {

            $user =
                DB::table('users')
                    ->where(
                        'user_id',
                        $recipient->user_id
                    )
                    ->first();


            if ($user) {

                return $user->name;

            }

        }


        return 'Recipient';
    }


    /*
    |--------------------------------------------------------------------------
    | Map UI Audience to Existing Database Enum
    |--------------------------------------------------------------------------
    */

    private function mapAudienceType(
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

            /*
            |--------------------------------------------------------------------------
            | Import is currently used for explicitly selected / external
            | recipient sets because it already exists in your database enum.
            |--------------------------------------------------------------------------
            */

            'specific_students',
            'assigned_personnel' =>
                'Import',

            default =>
                'Import',

        };
    }
}