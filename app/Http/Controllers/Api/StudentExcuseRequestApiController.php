<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ExcuseRequest;
use App\Models\ExcuseRequestDocument;

use App\Services\ExcuseRequestService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class StudentExcuseRequestApiController extends Controller
{
    public function __construct(
        private ExcuseRequestService $service
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT EXCUSE REQUESTS
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        $user =
            auth()->user();


        if (!$user->student_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'No student record is linked to this account.',

            ], 403);
        }


        $requests =
            ExcuseRequest::query()
                ->with([
                    'event',
                    'documents',
                ])
                ->where(
                    'student_id',
                    $user->student_id
                )
                ->latest(
                    'submitted_at'
                )
                ->get();


        return response()->json([

            'success' =>
                true,

            'count' =>
                $requests->count(),

            'data' =>
                $requests,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT EXCUSE REQUEST
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

                'reason' => [
                    'required',
                    'string',
                    'min:10',
                    'max:3000',
                ],

                /*
                |--------------------------------------------------------------------------
                | Supporting Document Required
                |--------------------------------------------------------------------------
                */

                'documents' => [
                    'required',
                    'array',
                    'min:1',
                    'max:5',
                ],

                'documents.*' => [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:10240',
                ],

            ]);


        $excuseRequest =
            $this->service->submit(

                auth()->user(),

                $validated,

                $request->file(
                    'documents',
                    []
                )

            );


        return response()->json([

            'success' =>
                true,

            'message' =>
                'Your excuse request was submitted successfully and is now pending review.',

            'data' =>
                $excuseRequest,

        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW ONE REQUEST
    |--------------------------------------------------------------------------
    */

    public function show(
        ExcuseRequest $excuseRequest
    ): JsonResponse {

        $this->authorizeStudent(
            $excuseRequest
        );


        $excuseRequest->load([
            'event',
            'documents',
        ]);


        return response()->json([

            'success' =>
                true,

            'data' =>
                $excuseRequest,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW SUPPORTING DOCUMENT
    |--------------------------------------------------------------------------
    */

    public function document(
        ExcuseRequest $excuseRequest,
        ExcuseRequestDocument $document
    ) {

        /*
        |--------------------------------------------------------------------------
        | Student Must Own Request
        |--------------------------------------------------------------------------
        */

        $this->authorizeStudent(
            $excuseRequest
        );


        /*
        |--------------------------------------------------------------------------
        | Document Must Belong To Request
        |--------------------------------------------------------------------------
        */

        if (
            (int) $document->excuse_request_id
            !==
            (int) $excuseRequest->excuse_request_id
        ) {

            abort(
                404
            );

        }


        /*
        |--------------------------------------------------------------------------
        | File Must Exist
        |--------------------------------------------------------------------------
        */

        if (
            !Storage::disk(
                'local'
            )
            ->exists(
                $document->file_path
            )
        ) {

            abort(
                404,
                'Supporting document not found.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Display Image / PDF Securely
        |--------------------------------------------------------------------------
        */

        return Storage::disk(
            'local'
        )
        ->response(
            $document->file_path,
            $document->file_name,
            [
                'Content-Type' =>
                    $document->mime_type
                    ?? 'application/octet-stream',
            ],
            'inline'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STUDENT OWNERSHIP CHECK
    |--------------------------------------------------------------------------
    */

    private function authorizeStudent(
        ExcuseRequest $excuseRequest
    ): void {

        $user =
            auth()->user();


        if (
            !$user->student_id
            ||
            (int) $excuseRequest->student_id
            !==
            (int) $user->student_id
        ) {

            abort(
                403,
                'You are not authorized to access this excuse request.'
            );

        }
    }
}