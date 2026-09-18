<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonnelController;
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

        return view('dashboard.admin');

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



    // Personnel Management

    Route::resource(
        'personnel',
        PersonnelController::class
    );

    Route::get(
    '/events/{event}/assign',
    [EventController::class, 'assign']
)
->name('events.assign');



    Route::patch(
        '/personnel/{id}/activate',
        [PersonnelController::class, 'activate']
    )
    ->name('personnel.activate');





    // Student Data Management


    Route::get(
        '/students/import',
        [StudentController::class, 'import']
    )
    ->name('students.import');



    Route::resource(
        'students',
        StudentController::class
    );







    // RFID Management


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







    // Event Management


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
        '/my-events',
        [MyAssignedEventsController::class, 'index']
    )
    ->name('my-events.index');



});









/*
|--------------------------------------------------------------------------
| Profile
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


Route::get(
    '/my-events/{event}',
    [MyAssignedEventsController::class, 'show']
)
->name('my-events.show');

Route::get('/roles', function () {
    return view('roles.index');
})
->name('roles.index');

Route::get('/backup', function () {
    return view('backup.index');
})
->name('backup.index');

Route::get('/logs', function () {
    return view('logs.index');
})
->name('logs.index');



require __DIR__.'/auth.php';