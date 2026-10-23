<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| DySign Automated Evaluation Publication
|--------------------------------------------------------------------------
*/

Schedule::command(
    'evaluations:auto-publish'
)
->everyMinute()
->withoutOverlapping();
