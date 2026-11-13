
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Default Laravel Command
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| DySign Automated Evaluation Publication
|--------------------------------------------------------------------------
*/

Schedule::command('evaluations:auto-publish')
    ->everyMinute()
    ->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| DySign Automatic Incomplete Attendance
|--------------------------------------------------------------------------
|
| After the event's checkout deadline (event end + 1 hour),
| attendance records that are still Checked In without Time Out
| will automatically become Incomplete.
|
*/

Schedule::command('attendance:finalize-incomplete')
    ->everyMinute()
    ->withoutOverlapping();


/*
|--------------------------------------------------------------------------
| DySign Automatic Approved Excuse Finalization
|--------------------------------------------------------------------------
*/

Schedule::command('attendance:finalize-excused')
    ->everyMinute()
    ->withoutOverlapping();
