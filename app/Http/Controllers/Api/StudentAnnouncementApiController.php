<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Announcement;
use App\Models\AnnouncementAttachment;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class StudentAnnouncementApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT ANNOUNCEMENT LIST
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $user = auth()->user();


        if (!$user->student_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'No student record is linked to this account.',

            ], 403);
        }


        $announcements =
            Announcement::query()
                ->with([
                    'event',
                    'creator',
                    'attachments',
                ])
                ->whereHas(
                    'recipients',
                    function ($query) use ($user) {

                        $query->where(
                            'student_id',
                            $user->student_id
                        );

                    }
                )
                ->latest('created_at')
                ->get();


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Student announcements retrieved successfully.',

            'count' =>
                $announcements->count(),

            'data' =>
                $announcements,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ONE ANNOUNCEMENT
    |--------------------------------------------------------------------------
    */

    public function show(
        Announcement $announcement
    ): JsonResponse {

        $user = auth()->user();


        if (!$user->student_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'No student record is linked to this account.',

            ], 403);
        }


        $this->authorizeRecipient(
            $announcement,
            $user->student_id
        );


        $announcement->load([
            'event',
            'creator',
            'attachments',
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


    /*
    |--------------------------------------------------------------------------
    | VIEW ANNOUNCEMENT ATTACHMENT
    |--------------------------------------------------------------------------
    |
    | Files remain private inside storage/app/private.
    | A student may only open the file if the announcement
    | was actually addressed to that student's account.
    |
    */

    public function attachment(
        Announcement $announcement,
        AnnouncementAttachment $attachment
    ) {

        $user = auth()->user();


        if (!$user->student_id) {

            abort(
                403,
                'No student record is linked to this account.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Make Sure Student Received The Announcement
        |--------------------------------------------------------------------------
        */

        $this->authorizeRecipient(
            $announcement,
            $user->student_id
        );


        /*
        |--------------------------------------------------------------------------
        | Make Sure Attachment Belongs To This Announcement
        |--------------------------------------------------------------------------
        */

        if (
            (int) $attachment->announcement_id
            !==
            (int) $announcement->announcement_id
        ) {

            abort(
                404
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Make Sure File Exists
        |--------------------------------------------------------------------------
        */

        if (
            !Storage::disk('local')
                ->exists(
                    $attachment->file_path
                )
        ) {

            abort(
                404,
                'Attachment file not found.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Return File Inline
        |--------------------------------------------------------------------------
        |
        | Images and PDFs will normally open directly
        | inside the browser.
        |
        */

        return Storage::disk('local')
            ->response(
                $attachment->file_path,
                $attachment->file_name,
                [
                    'Content-Type' =>
                        $attachment->mime_type
                        ?? 'application/octet-stream',
                ],
                'inline'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RECIPIENT AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeRecipient(
        Announcement $announcement,
        int $studentId
    ): void {

        $isRecipient =
            $announcement
                ->recipients()
                ->where(
                    'student_id',
                    $studentId
                )
                ->exists();


        if (!$isRecipient) {

            abort(
                403,
                'You are not authorized to view this announcement.'
            );

        }
    }
}