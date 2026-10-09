<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ActivityLogMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Do Not Log Read-Only Requests
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $request->method(),
                [
                    'GET',
                    'HEAD',
                    'OPTIONS',
                ],
                true
            )
        ) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | Avoid Logging The Logger Itself
        |--------------------------------------------------------------------------
        */

        if (
            $request->routeIs(
                'logs.*'
            )
        ) {

            return $next($request);

        }


        try {

            $response = $next(
                $request
            );


            /*
            |--------------------------------------------------------------------------
            | Only Record Authenticated Actions
            |--------------------------------------------------------------------------
            */

            if (auth()->check()) {

                [
                    $module,
                    $action,
                    $description
                ] = $this->resolveActivity(
                    $request
                );


                ActivityLog::record(
                    $action,
                    $module,
                    $description,
                    $response->isSuccessful()
                        || $response->isRedirection()
                            ? 'Success'
                            : 'Failed'
                );

            }


            return $response;

        } catch (Throwable $exception) {


            /*
            |--------------------------------------------------------------------------
            | Record Failed Operation
            |--------------------------------------------------------------------------
            */

            if (auth()->check()) {

                try {

                    [
                        $module,
                        $action,
                        $description
                    ] = $this->resolveActivity(
                        $request
                    );


                    ActivityLog::record(
                        $action,
                        $module,
                        $description,
                        'Failed'
                    );

                } catch (Throwable $ignored) {

                    // Never allow logging failure
                    // to hide the original error.

                }

            }


            throw $exception;

        }
    }


    private function resolveActivity(
        Request $request
    ): array {

        $routeName =
            (string) optional(
                $request->route()
            )->getName();


        /*
        |--------------------------------------------------------------------------
        | Personnel
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'personnel.'
            )
        ) {

            $action = match (
                $routeName
            ) {

                'personnel.store' =>
                    'Created personnel account',

                'personnel.update' =>
                    'Updated personnel account',

                'personnel.destroy' =>
                    'Deactivated personnel account',

                'personnel.activate' =>
                    'Activated personnel account',

                'personnel.resendInvitation' =>
                    'Resent account invitation',

                default =>
                    'Updated personnel information',

            };


            return [
                'Users',
                $action,
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'students.'
            )
        ) {

            $action = match (
                $routeName
            ) {

                'students.process-import' =>
                    'Imported student records',

                'students.update' =>
                    'Updated student information',

                default =>
                    'Modified student records',

            };


            return [
                'Students',
                $action,
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'events.'
            )
        ) {

            $action = match (
                $routeName
            ) {

                'events.store' =>
                    'Created new event',

                'events.update' =>
                    'Updated event',

                'events.destroy' =>
                    'Cancelled or deleted event',

                'events.personnel.update' =>
                    'Updated event personnel assignment',

                default =>
                    'Modified event information',

            };


            return [
                'Events',
                $action,
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | RFID Attendance
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'attendance.'
            )
        ) {

            if (
                $routeName ===
                'attendance.scan'
            ) {

                return [
                    'RFID',
                    'Attendance scan recorded',
                    $this->routeDescription(
                        $request
                    ),
                ];

            }


            return [
                'Attendance',
                'Updated attendance information',
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'announcements.'
            )
        ) {

            return [
                'Announcements',
                'Sent announcement',
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Roles & Permissions
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'roles.'
            )
        ) {

            return [
                'Users',
                'Updated role or permissions',
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Participation
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'participation.'
            )
        ) {

            return [
                'Participation',
                'Re-evaluated participation records',
                $this->routeDescription(
                    $request
                ),
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'profile.'
            )
        ) {

            return [
                'Users',
                'Updated account profile',
                $this->routeDescription(
                    $request
                ),
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Backup Management
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $routeName,
                'backup.'
            )
        ) {

            return [
                'Backup',
                'Created system database backup',
                $this->routeDescription(
                    $request
                ),
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        return [
            'System',
            'Performed system operation',
            $routeName !== ''
                ? "Route: {$routeName}"
                : 'System operation',
        ];
    }


    private function routeDescription(
        Request $request
    ): string {

        $routeName =
            optional(
                $request->route()
            )->getName()
            ?? 'Unknown route';


        return sprintf(
            '%s request through %s',
            $request->method(),
            $routeName
        );
    }
}