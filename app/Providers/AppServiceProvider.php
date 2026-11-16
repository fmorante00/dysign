<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // In-app notifications for material changes only. All observers execute
        // after commit; reads, failed writes and status polling do not notify.
        $models = [
            \App\Models\Event::class,
            \App\Models\EventAssignment::class,
            \App\Models\EventRegistration::class,
            \App\Models\AttendanceRecord::class,
            \App\Models\AttendanceException::class,
            \App\Models\AttendanceDiscrepancy::class,
            \App\Models\AttendanceDiscrepancyRequest::class,
            \App\Models\ExcuseRequest::class,
            \App\Models\PersonnelDuty::class,
            \App\Models\PersonnelAvailability::class,
            \App\Models\EvaluationForm::class,
            \App\Models\EvaluationSubmission::class,
            \App\Models\Announcement::class,
            \App\Models\AnnouncementRecipient::class,
            \App\Models\RfidCardAssignment::class,
            \App\Models\User::class,
        ];
        foreach ($models as $model) {
            $model::observe(\App\Observers\DySignActivityObserver::class);
        }
    }
}
