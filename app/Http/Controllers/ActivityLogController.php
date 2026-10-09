<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(
        Request $request
    ) {

        $query = ActivityLog::query()
            ->with('user');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'search'
            )
        ) {

            $search =
                trim(
                    $request->search
                );


            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'action',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'module',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas(
                        'user',
                        function ($userQuery) use ($search) {

                            $userQuery->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'username',
                                'like',
                                "%{$search}%"
                            );

                        }
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Module Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'module'
            )
        ) {

            $query->where(
                'module',
                $request->module
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Logs
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Available Modules
        |--------------------------------------------------------------------------
        */

        $modules = ActivityLog::query()
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');


        return view(
            'logs.index',
            compact(
                'logs',
                'modules'
            )
        );
    }
}