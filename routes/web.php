<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AccountSetupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RFIDController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MyAssignedEventsController;

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

        return view('dashboard.attendance');

    }


    if ($role === 'Registrar Staff') {

        return view('dashboard.registrar');

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
    | Student Management
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
    | RFID Management
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/rfid',
        [RFIDController::class, 'index']
    )
    ->name('rfid.index');


    Route::get(
        '/rfid/assign',
        [RFIDController::class, 'assign']
    )
    ->name('rfid.assign');



    /*
    |--------------------------------------------------------------------------
    | Event Management
    |--------------------------------------------------------------------------
    */


    Route::resource(
        'events',
        EventController::class
    );


    Route::get(
        '/events/{event}/assign',
        [EventController::class, 'assign']
    )
    ->name('events.assign');


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
        '/attendance',
        function () {
            return view('attendance.index');
        }
    )
    ->name('attendance.index');



    Route::get(
        '/attendance/monitor',
        function () {
            return view('attendance.monitor');
        }
    )
    ->name('attendance.monitor');



    Route::get(
        '/my-events',
        [MyAssignedEventsController::class, 'index']
    )
    ->name('my-events.index');


});





/*
|--------------------------------------------------------------------------
| Shared Pages / System Modules
|--------------------------------------------------------------------------
*/



Route::get(
    '/my-events/{event}',
    [MyAssignedEventsController::class, 'show']
)
->name('my-events.show');





/*
|--------------------------------------------------------------------------
| Role & System Management
|--------------------------------------------------------------------------
*/


Route::get('/roles', [RoleController::class, 'index'])
    ->middleware(['auth'])
    ->name('roles.index');

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
| Participation Management
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
| Reports & Monitoring
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
| Event Announcements
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


Route::middleware('auth')->group(function () {


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



    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )
    ->name('profile.destroy');


});





/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Account Invitation Setup
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