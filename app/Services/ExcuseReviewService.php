<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\ExcuseRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExcuseReviewService
{
    public function review(
        User $reviewer,
        ExcuseRequest $excuseRequest,
        array $data
    ): ExcuseRequest {
        // Review and attendance changes must either both commit or both fail.
        return DB::transaction(function () use ($reviewer, $excuseRequest, $data) {
            // Lock the request to prevent two reviewers from approving it at once.
            $excuseRequest = ExcuseRequest::query()
                ->whereKey($excuseRequest->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($excuseRequest->status !== 'Pending') {
                throw ValidationException::withMessages([
                    'decision' => 'This excuse request has already been reviewed.',
                ]);
            }

            // A department can review only its own event's excuses.
            $event = $excuseRequest->event;

            if (!$reviewer->department_id) {
                abort(403, 'Your account is not assigned to a department.');
            }

            if ((int) $event->department_id !== (int) $reviewer->department_id) {
                abort(403, 'You are not authorized to review excuse requests from another department.');
            }

            $excuseRequest->update([
                'status' => $data['decision'],
                'reviewed_by' => $reviewer->user_id,
                'review_remarks' => $data['remarks'] ?? null,
                'reviewed_at' => now(),
            ]);

            // Approved absence for a completed event becomes official Excused
            // attendance. Never overwrite valid RFID check-ins/check-outs.
            // For events that have not completed yet, attendance is NOT finalized
            // here. Their approvals must be considered by the future finalizer.
            if ($data['decision'] === 'Approved' && $event->status === 'Completed') {
                $record = AttendanceRecord::query()
                    ->where('event_id', $excuseRequest->event_id)
                    ->where('student_id', $excuseRequest->student_id)
                    ->lockForUpdate()
                    ->first();

                if ($record === null) {
                    // Missing scan row: create one official excuse (unique per event/student).
                    AttendanceRecord::create([
                        'event_id' => $excuseRequest->event_id,
                        'student_id' => $excuseRequest->student_id,
                        'status' => 'Excused',
                    ]);
                } elseif (
                    $record->time_in === null
                    && $record->time_out === null
                    && in_array($record->status, ['Absent', 'Incomplete'], true)
                ) {
                    // A scanless Absent/Incomplete record can become Excused.
                    $record->update(['status' => 'Excused']);
                }
            }

            return $excuseRequest->fresh()->load([
                'event',
                'student',
                'documents',
                'reviewer',
            ]);
        });
    }
}
