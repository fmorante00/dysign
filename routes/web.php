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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect()->route('login');
});



/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $role = Auth::user()->role->role_name;


    if ($role === 'Administrator') {

        return app(\App\Http\Controllers\DashboardController::class)
            ->index();

    }


    if ($role === 'Attendance Personnel') {

        return app(\App\Http\Controllers\DashboardController::class)
            ->attendancePersonnel();

    }


    if ($role === 'Department Staff') {

        return app(\App\Http\Controllers\DashboardController::class)
            ->departmentStaff();

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
        [PersonnelController::class, 'resendInvitation']
    )
    ->name('personnel.resendInvitation');


    Route::patch(
        '/personnel/{id}/activate',
        [PersonnelController::class, 'activate']
    )
    ->name('personnel.activate');



    /*
    |--------------------------------------------------------------------------
    | Student Records
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/students/import',
        [StudentController::class, 'import']
    )
    ->name('students.import');


    Route::post(
        '/students/import',
        [StudentController::class, 'processImport']
    )
    ->name('students.process-import');


    Route::get(
        '/students',
        [StudentController::class, 'index']
    )
    ->name('students.index');


    Route::get(
        '/students/{student}',
        [StudentController::class, 'show']
    )
    ->name('students.show');



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

Route::middleware(['auth'])
->group(function () {

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
        function () {
            return view('attendance.monitor');
        }
    )
    ->name('attendance.monitor');

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


    Route::get(
        '/my-events',
        [MyAssignedEventsController::class, 'index']
    )
    ->name('my-events.index');


    Route::get(
        '/my-events/{event}',
        [MyAssignedEventsController::class, 'show']
    )
    ->name('my-events.show');


    Route::get(
        '/attendance/{event}',
        [AttendanceController::class, 'index']
    )
    ->name('attendance.index');


    Route::post(
        '/attendance/{event}/scan',
        [AttendanceController::class, 'scan']
    )
    ->name('attendance.scan');


    Route::get(
        '/attendance/{event}/feed',
        [AttendanceController::class, 'feed']
    )
    ->name('attendance.feed');

});

/*
|--------------------------------------------------------------------------
| System Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/roles/{id}/permissions',
    [RolePermissionController::class, 'edit']
)
->middleware(['auth'])
->name('roles.permissions');


Route::put(
    '/roles/{id}/permissions',
    [RolePermissionController::class, 'update']
)
->middleware(['auth'])
->name('roles.permissions.update');



Route::get('/backup', function () {
    return view('backup.index');
})
->name('backup.index');



Route::get('/logs', function () {
    return view('logs.index');
})
->name('logs.index');





/*
|--------------------------------------------------------------------------
| Participation
|--------------------------------------------------------------------------
*/

Route::get('/participation/records', function () {
    return view('participation.records');
})
->name('participation.records');


Route::get('/participation/evaluation', function () {
    return view('participation.evaluation');
})
->name('participation.evaluation');





/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

Route::get('/reports/attendance', function () {
    return view('reports.attendance');
})
->name('reports.attendance');


Route::get('/reports/participation', function () {
    return view('reports.participation');
})
->name('reports.participation');



Route::get('/attendance/alerts', function () {
    return view('alerts.index');
})
->name('attendance.alerts');





/*
|--------------------------------------------------------------------------
| Announcements
|--------------------------------------------------------------------------
*/

Route::get('/announcements', function () {
    return view('announcements.index');
})
->name('announcements.index');





/*
|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
->group(function () {


    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )
    ->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )
    ->name('profile.update');





});





/*
|--------------------------------------------------------------------------
| Account Setup
|--------------------------------------------------------------------------
*/

Route::get(
    '/account/setup/{token}',
    [AccountSetupController::class, 'show']
)
->name('account.setup');


Route::post(
    '/account/setup/{token}',
    [AccountSetupController::class, 'store']
)
->name('account.setup.store');



require __DIR__.'/auth.php';