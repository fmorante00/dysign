<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendAnnouncementEmail;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\AnnouncementRecipient;
use App\Models\Event;
use App\Services\AnnouncementService;
use App\Support\AnnouncementEmailAddress;
use App\Support\DepartmentAccess;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class AnnouncementApiController extends Controller
{
    public function __construct(
        private AnnouncementService $announcementService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | LIST ANNOUNCEMENTS
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $user = auth()->user();
        abort_unless($user?->department_id, 403, 'No department assigned.');

        $announcements =
            Announcement::with([
                'event',
                'creator',
                'attachments',
                'recipients',
            ])
            ->whereHas('event', fn ($query) => $query
                ->where('department_id', $user->department_id))
            ->latest('created_at')
            ->get();


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Announcements retrieved successfully.',

            'data' =>
                $announcements,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW RECIPIENTS
    |--------------------------------------------------------------------------
    */

    public function previewRecipients(
        Request $request
    ): JsonResponse {

        $validated =
            $request->validate([

                'event_id' => [
                    'required',
                    'integer',
                    'exists:events,event_id',
                ],

                'recipient_type' => [
                    'required',
                    'string',
                    'in:all_students,assigned_personnel,specific_students,college,year,college_year,expected_participants',
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

            ]);


        $event =
            Event::findOrFail(
                $validated['event_id']
            );

        DepartmentAccess::assertCanManageEvent(auth()->user(), $event);


        $recipients =
            $this->announcementService
                ->resolveRecipients(
                    $validated,
                    $event
                );


        return response()->json([

            'success' =>
                true,

            'count' =>
                $recipients->count(),

            'email_ready_count' => $recipients->filter(fn ($recipient) =>
                AnnouncementEmailAddress::isSendable($recipient['email'] ?? null)
            )->count(),

            'in_app_only_count' => $recipients->filter(fn ($recipient) =>
                !AnnouncementEmailAddress::isSendable($recipient['email'] ?? null)
            )->count(),

            'recipients' =>
                $recipients
                    ->take(20)
                    ->values(),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE AND PUBLISH ANNOUNCEMENT
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): JsonResponse {

        $validated =
            $request->validate([

                'event_id' => [
                    'required',
                    'integer',
                    'exists:events,event_id',
                ],

                'recipient_type' => [
                    'required',
                    'string',
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


        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */

        $event =
            Event::findOrFail(
                $validated['event_id']
            );

        DepartmentAccess::assertCanManageEvent(auth()->user(), $event);


        /*
        |--------------------------------------------------------------------------
        | AUTOMATIC RECIPIENT RESOLUTION
        |--------------------------------------------------------------------------
        */

        $recipients =
            $this->announcementService
                ->resolveRecipients(
                    $validated,
                    $event
                );


        if ($recipients->isEmpty()) {

            throw ValidationException::withMessages([

                'recipient_type' =>
                    'No valid recipients were found for the selected audience.',

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | AUTOMATIC SUBJECT
        |--------------------------------------------------------------------------
        */

        $subject =
            trim(
                $validated['subject']
                ?? ''
            );


        if ($subject === '') {

            $subject =
                'DySign Announcement: '
                . $event->event_name;
        }


        $storedFiles = [];

        $recipientIds = [];


        try {

            /*
            |--------------------------------------------------------------------------
            | DATABASE TRANSACTION
            |--------------------------------------------------------------------------
            */

            $announcement =
                DB::transaction(
                    function () use (
                        $request,
                        $validated,
                        $event,
                        $recipients,
                        $subject,
                        &$storedFiles,
                        &$recipientIds
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | CREATE ANNOUNCEMENT
                        |--------------------------------------------------------------------------
                        */

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
                                    $this
                                        ->announcementService
                                        ->mapAudienceType(
                                            $validated[
                                                'recipient_type'
                                            ]
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
                        | SAVE ATTACHMENTS
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $request->file(
                                'attachments',
                                []
                            )
                            as $file
                        ) {

                            $path =
                                $file->store(
                                    'announcement_attachments',
                                    'local'
                                );


                            $storedFiles[] =
                                $path;


                            AnnouncementAttachment::create([

                                'announcement_id' =>
                                    $announcement
                                        ->announcement_id,

                                'file_name' =>
                                    $file
                                        ->getClientOriginalName(),

                                'file_path' =>
                                    $path,

                                'mime_type' =>
                                    $file
                                        ->getMimeType(),

                                'file_size' =>
                                    $file
                                        ->getSize(),

                            ]);
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE RECIPIENT RECORDS
                        |--------------------------------------------------------------------------
                        */

                        foreach (
                            $recipients
                            as $recipient
                        ) {

                            $record =
                                AnnouncementRecipient::create([

                                    'announcement_id' =>
                                        $announcement
                                            ->announcement_id,

                                    'student_id' =>
                                        $recipient[
                                            'student_id'
                                        ]
                                        ?? null,

                                    'user_id' =>
                                        $recipient[
                                            'user_id'
                                        ]
                                        ?? null,

                                    'recipient_type' =>
                                        $recipient[
                                            'recipient_type'
                                        ],

                                    'email' =>
                                        $recipient[
                                            'email'
                                        ],

                                    'status' =>
                                        'Pending',

                                    'sent_at' =>
                                        null,

                                    'error_message' =>
                                        null,

                                ]);


                            $recipientIds[] =
                                $record
                                    ->announcement_recipient_id;
                        }


                        return $announcement;
                    }
                );

        }
        catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | REMOVE FILES IF DB TRANSACTION FAILS
            |--------------------------------------------------------------------------
            */

            foreach (
                $storedFiles
                as $path
            ) {

                Storage::disk('local')
                    ->delete(
                        $path
                    );
            }


            throw $exception;
        }


        /*
        |--------------------------------------------------------------------------
        | QUEUE EMAIL JOBS
        |--------------------------------------------------------------------------
        |
        | This happens AFTER the database transaction succeeds.
        |
        */

        foreach (
            $recipientIds
            as $recipientId
        ) {

            SendAnnouncementEmail::dispatch(
                $recipientId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RESULT
        |--------------------------------------------------------------------------
        */

        // With QUEUE_CONNECTION=sync the jobs may have completed already.
        $announcement->refresh()->load([
            'event',
            'creator',
            'attachments',
            'recipients',
        ]);


        /*
        |--------------------------------------------------------------------------
        | API RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                config('queue.default') === 'sync'
                    ? 'Announcement published. Email sending was attempted; check the recipient statuses.'
                    : 'Announcement published. Email notifications have been queued.',

            'data' => [

                'announcement' =>
                    $announcement,

                'recipient_count' =>
                    count(
                        $recipientIds
                    ),

                'delivery_status' =>
                    $announcement->status,

                'email_counts' => [
                    'sent' => $announcement->recipients->where('status', 'Sent')->count(),
                    'pending' => $announcement->recipients->where('status', 'Pending')->count(),
                    'failed_or_skipped' => $announcement->recipients->where('status', 'Failed')->count(),
                ],

            ],

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ONE ANNOUNCEMENT
    |--------------------------------------------------------------------------
    */

    public function show(
        Announcement $announcement
    ): JsonResponse {

        DepartmentAccess::assertCanManageEvent(auth()->user(), $announcement->event);

        $announcement->load([
            'event',
            'creator',
            'attachments',
            'recipients.student',
            'recipients.user',
        ]);


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Announcement retrieved successfully.',

            'data' =>
                $announcement,

        ]);
    }
}