<?php

namespace App\Observers;

use App\Services\NotificationRouter;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Database\Eloquent\Model;

/** After-commit only: a failed/rolled-back action must not create notifications. */
class DySignActivityObserver implements ShouldHandleEventsAfterCommit
{
    public function created(Model $model): void { NotificationRouter::recorded($model, 'created'); }
    public function updated(Model $model): void { NotificationRouter::recorded($model, 'updated'); }
    public function deleted(Model $model): void { NotificationRouter::recorded($model, 'deleted'); }
}
