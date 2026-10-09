<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AccountSetupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyAssignedEventsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceMonitoringController;
use App\Http\Controllers\ParticipationRecordsController;
use App\Http\Controllers\ParticipationEvaluationController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\ParticipationReportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BackupController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()
        ->route('login');

});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $role = Auth::user()
        ->role
        ->role_name;


    if ($role === 'Administrator') {

        return app(
            DashboardController::class
        )->index();

    }


    if ($role === 'Attendance Personnel') {

        return app(
            DashboardController::class
        )->attendancePersonnel();

    }


    if ($role === 'Department Staff') {

        return app(
            DashboardController::class
        )->departmentStaff();

    }


    abort(403);

})
->middleware(['auth'])
->name('dashboard');


/*
|--------------------------------------------------------------------------
| Administrator Modules
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'permission:manage_personnel_accounts'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Personnel Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'personnel',
        PersonnelController::class
    );


    Route::post(
        '/personnel/{id}/resend-invitation',
        [
            PersonnelController::class,
            'resendInvitation'
        ]
    )
    ->name(
        'personnel.resendInvitation'
    );


    Route::patch(
        '/personnel/{id}/activate',
        [
            PersonnelController::class,
            'activate'
        ]
    )
    ->name(
        'personnel.activate'
    );


    /*
    |--------------------------------------------------------------------------
    | Student Records
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/students/import',
        [
            StudentController::class,
            'import'
        ]
    )
    ->name(
        'students.import'
    );


    Route::post(
        '/students/import',
        [
            StudentController::class,
            'processImport'
        ]
    )
    ->name(
        'students.process-import'
    );


    Route::get(
        '/students',
        [
            StudentController::class,
            'index'
        ]
    )
    ->name(
        'students.index'
    );


    Route::get(
        '/students/{student}',
        [
            StudentController::class,
            'show'
        ]
    )
    ->whereNumber('student')
    ->name(
        'students.show'
    );


    /*
    |--------------------------------------------------------------------------
    | Role Management
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'roles',
        RoleController::class
    );

});


/*
|--------------------------------------------------------------------------
| Event Management
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Event Personnel Assignment
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/events/{id}/personnel',
        [
            EventController::class,
            'personnel'
        ]
    )
    ->whereNumber('id')
    ->name(
        'events.personnel'
    );


    Route::put(
        '/events/{id}/personnel',
        [
            EventController::class,
            'updatePersonnel'
        ]
    )
    ->whereNumber('id')
    ->name(
        'events.personnel.update'
    );


    /*
    |--------------------------------------------------------------------------
    | Event CRUD
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'events',
        EventController::class
    );

});


/*
|--------------------------------------------------------------------------
| Administrator Attendance Monitoring
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Administrator'
])
->group(function () {


    Route::get(
        '/attendance/monitor',
        [
            AttendanceMonitoringController::class,
            'index'
        ]
    )
    ->name(
        'attendance.monitor'
    );


    Route::get(
        '/attendance/monitor/{event}/feed',
        [
            AttendanceMonitoringController::class,
            'feed'
        ]
    )
    ->whereNumber('event')
    ->name(
        'attendance.monitor.feed'
    );

});


/*
|--------------------------------------------------------------------------
| Attendance Personnel
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:Attendance Personnel'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Assigned Events
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/my-events',
        [
            MyAssignedEventsController::class,
            'index'
        ]
    )
    ->name(
        'my-events.index'
    );


    Route::get(
        '/my-events/{event}',
        [
            MyAssignedEventsController::class,
            'show'
        ]
    )
    ->whereNumber('event')
    ->name(
        'my-events.show'
    );


    /*
    |--------------------------------------------------------------------------
    | RFID Attendance
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/attendance/{event}',
        [
            AttendanceController::class,
            'index'
        ]
    )
    ->whereNumber('event')
    ->name(
        'attendance.index'
    );


    Route::post(
        '/attendance/{event}/scan',
        [
            AttendanceController::class,
            'scan'
        ]
    )
    ->whereNumber('event')
    ->name(
        'attendance.scan'
    );


    Route::get(
        '/attendance/{event}/feed',
        [
            AttendanceController::class,
            'feed'
        ]
    )
    ->whereNumber('event')
    ->name(
        'attendance.feed'
    );

});


/*
|--------------------------------------------------------------------------
| System Management
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Role Permissions
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/roles/{id}/permissions',
        [
            RolePermissionController::class,
            'edit'
        ]
    )
    ->whereNumber('id')
    ->name(
        'roles.permissions'
    );


    Route::put(
        '/roles/{id}/permissions',
        [
            RolePermissionController::class,
            'update'
        ]
    )
    ->whereNumber('id')
    ->name(
        'roles.permissions.update'
    );


    /*
    |--------------------------------------------------------------------------
    | Backup
    |--------------------------------------------------------------------------
    */

Route::middleware([
    'auth',
    'role:Administrator'
])
->group(function () {

    Route::get(
        '/backup',
        [
            BackupController::class,
            'index'
        ]
    )
    ->name(
        'backup.index'
    );


        Route::post(
            '/backup',
            [
                BackupController::class,
                'store'
            ]
        )
        ->name(
            'backup.store'
        );


        Route::get(
            '/backup/{backup}/download',
            [
                BackupController::class,
                'download'
            ]
        )
        ->whereNumber('backup')
        ->name(
            'backup.download'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | Audit Logs
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/logs',
        [
            ActivityLogController::class,
            'index'
        ]
    )
    ->middleware([
        'auth',
        'role:Administrator'
    ])
    ->name(
        'logs.index'
    );

});


/*
|--------------------------------------------------------------------------
| Participation Management
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Participation Records
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/participation/records',
        [
            ParticipationRecordsController::class,
            'index'
        ]
    )
    ->name(
        'participation.records'
    );


    /*
    |--------------------------------------------------------------------------
    | Participation Evaluation
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/participation/evaluation',
        [
            ParticipationEvaluationController::class,
            'index'
        ]
    )
    ->name(
        'participation.evaluation'
    );


    Route::post(
        '/participation/evaluation/re-evaluate',
        [
            ParticipationEvaluationController::class,
            'reevaluate'
        ]
    )
    ->name(
        'participation.evaluation.reevaluate'
    );

});


/*
|--------------------------------------------------------------------------
| Reports & Analytics
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Attendance Reports
    |--------------------------------------------------------------------------
    |
    | Connected to AttendanceReportController.
    |
    */

    Route::get(
        '/reports/attendance',
        [
            AttendanceReportController::class,
            'index'
        ]
    )
    ->name(
        'reports.attendance'
    );


    /*
    |--------------------------------------------------------------------------
    | Participation Reports
    |--------------------------------------------------------------------------
    |
    | Static for now. We can connect this to the database next.
    |
    */

        Route::get(
            '/reports/participation',
            [
                ParticipationReportController::class,
                'index'
            ]
        )
        ->name(
            'reports.participation'
        );


    /*
    |--------------------------------------------------------------------------
    | Attendance Alerts
    |--------------------------------------------------------------------------
    |
    | Static for now.
    |
    */

    Route::get(
        '/attendance/alerts',
        function () {

            return view(
                'alerts.index'
            );

        }
    )
    ->name(
        'attendance.alerts'
    );

});


/*
|--------------------------------------------------------------------------
| Announcements
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    Route::get(
        '/announcements',
        [
            AnnouncementController::class,
            'index'
        ]
    )
    ->name(
        'announcements.index'
    );


    Route::post(
        '/announcements',
        [
            AnnouncementController::class,
            'store'
        ]
    )
    ->name(
        'announcements.store'
    );


    Route::post(
        '/announcements/preview-recipients',
        [
            AnnouncementController::class,
            'previewRecipients'
        ]
    )
    ->name(
        'announcements.preview-recipients'
    );

});


/*
|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth'
])
->group(function () {


    Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit'
        ]
    )
    ->name(
        'profile.edit'
    );


    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update'
        ]
    )
    ->name(
        'profile.update'
    );

});


/*
|--------------------------------------------------------------------------
| Account Setup
|--------------------------------------------------------------------------
*/

Route::get(
    '/account/setup/{token}',
    [
        AccountSetupController::class,
        'show'
    ]
)
->name(
    'account.setup'
);


Route::post(
    '/account/setup/{token}',
    [
        AccountSetupController::class,
        'store'
    ]
)
->name(
    'account.setup.store'
);


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';