<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ExcuseRequest;
use App\Models\ExcuseRequestDocument;

use App\Services\ExcuseReviewService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DepartmentExcuseRequestApiController extends Controller
{
    public function __construct(
        private ExcuseReviewService $service
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | LIST EXCUSE REQUESTS
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): JsonResponse {

        $user =
            auth()->user();


        if (!$user->department_id) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    'Your account is not assigned to a department.',

            ], 403);
        }


        $query =
            ExcuseRequest::query()
                ->with([
                    'event',
                    'student',
                    'documents',
                    'reviewer',
                ])
                ->whereHas(
                    'event',
                    function ($query) use ($user) {

                        $query->where(
                            'department_id',
                            $user->department_id
                        );

                    }
                );


        /*
        |--------------------------------------------------------------------------
        | Optional Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'status'
            )
        ) {

            $query->where(
                'status',
                $request->status
            );

        }


        $requests =
            $query
                ->orderByRaw(
                    "
                    CASE
                        WHEN status = 'Pending' THEN 1
                        WHEN status = 'Approved' THEN 2
                        WHEN status = 'Rejected' THEN 3
                        ELSE 4
                    END
                    "
                )
                ->orderByDesc(
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
    | VIEW ONE REQUEST
    |--------------------------------------------------------------------------
    */

    public function show(
        ExcuseRequest $excuseRequest
    ): JsonResponse {

        $this->authorizeDepartment(
            $excuseRequest
        );


        $excuseRequest->load([
            'event',
            'student',
            'documents',
            'reviewer',
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
    | REVIEW REQUEST
    |--------------------------------------------------------------------------
    */

    public function review(
        Request $request,
        ExcuseRequest $excuseRequest
    ): JsonResponse {

        $this->authorizeDepartment(
            $excuseRequest
        );


        $validated =
            $request->validate([

                'decision' => [
                    'required',
                    Rule::in([
                        'Approved',
                        'Rejected',
                    ]),
                ],

                'remarks' => [
                    'nullable',
                    'string',
                    'max:2000',
                    'required_if:decision,Rejected',
                ],

            ]);


        $reviewed =
            $this->service->review(

                auth()->user(),

                $excuseRequest,

                $validated

            );


        return response()->json([

            'success' =>
                true,

            'message' =>
                $reviewed->status
                === 'Approved'
                    ? 'Excuse request approved successfully.'
                    : 'Excuse request rejected successfully.',

            'data' =>
                $reviewed,

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

        $this->authorizeDepartment(
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
    | DEPARTMENT AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeDepartment(
        ExcuseRequest $excuseRequest
    ): void {

        $user =
            auth()->user();


        if (!$user->department_id) {

            abort(
                403,
                'Your account is not assigned to a department.'
            );

        }


        $excuseRequest->loadMissing(
            'event'
        );


        if (
            (int) $excuseRequest
                ->event
                ->department_id
            !==
            (int) $user->department_id
        ) {

            abort(
                403,
                'You are not authorized to access excuse requests from another department.'
            );

        }
    }
}