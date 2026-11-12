<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $primaryKey = 'activity_log_id';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'status',
        'ip_address',
        'user_agent',
        'subject_type',
        'subject_id',
        'changes_before',
        'changes_after',
    ];

    protected $casts = [
        'changes_before' => 'array',
        'changes_after' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Compatible with existing ActivityLog::record($action, $module, $description, $status).
     * Optional parameters describe the affected database record and changed fields.
     */
    public static function record(
        string $action,
        string $module,
        ?string $description = null,
        string $status = 'Success',
        ?string $subjectType = null,
        string|int|null $subjectId = null,
        ?array $before = null,
        ?array $after = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'status' => $status,
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
            'user_agent' => app()->runningInConsole() ? null : request()->userAgent(),
            'subject_type' => $subjectType,
            'subject_id' => $subjectId === null ? null : (string) $subjectId,
            'changes_before' => self::redact($before),
            'changes_after' => self::redact($after),
        ]);
    }

    /** Passwords, security tokens, and secrets must never be copied into audit entries. */
    public static function redact(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }
        foreach ($values as $key => $value) {
            if (preg_match('/password|token|secret|api[_-]?key|otp|authorization|cookie/i', (string) $key)) {
                unset($values[$key]);
            } elseif (is_array($value)) {
                $values[$key] = self::redact($value);
            }
        }
        return $values;
    }
}
