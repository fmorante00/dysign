<?php

namespace App\Support;

use App\Models\Department;
use App\Models\Event;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * DySign department authorization rules.
 *
 * Student records use students.college (text); staff accounts and events use
 * department_id.  This service bridges them without changing historical IDs.
 * SSC may see college students across departments, but event ownership is
 * always checked separately by department_id.
 */
final class DepartmentAccess
{
    /** Explicit, conservative aliases. Add aliases only after verifying imports. */
    private const COLLEGE_ALIASES = [
        'College of Computer Studies' => ['CCS'],
        'College of Business Administration' => ['CBA'],
    ];

    /**
     * Limit a Student query to the students of an event's / account's
     * department. An unassigned department always fails closed.
     */
    public static function scopeStudentsForDepartment(
        Builder $query,
        ?Department $department
    ): Builder {
        abort_unless($department !== null, 403, 'No department is assigned.');

        if ((bool) $department->is_school_wide) {
            return $query;
        }

        $names = self::normalizedCollegeNames($department);
        $placeholders = implode(', ', array_fill(0, count($names), '?'));

        return $query->whereRaw(
            "LOWER(TRIM(college)) IN ({$placeholders})",
            $names
        );
    }

    /** Scope student visibility by role and assigned department. */
    public static function scopeStudentsForUser(Builder $query, User $user): Builder
    {
        $role = $user->role?->role_name;
        if ($role === 'Administrator') {
            return $query;
        }
        abort_unless(
            in_array($role, ['Department Staff', 'Attendance Personnel'], true),
            403,
            'This account cannot view student records.'
        );
        return self::scopeStudentsForDepartment($query, $user->department);
    }

    /** Use this check on direct student lookups, not just dropdowns. */
    public static function canViewStudent(User $user, Student $student): bool
    {
        $role = $user->role?->role_name;
        if ($role === 'Administrator') {
            return true;
        }
        if (!in_array($role, ['Department Staff', 'Attendance Personnel'], true)) {
            return false;
        }
        $department = $user->department;
        if (!$department) {
            return false;
        }
        return self::studentBelongsToDepartment($student, $department);
    }

    public static function studentBelongsToDepartment(Student $student, ?Department $department): bool
    {
        if (!$department) {
            return false;
        }
        if ((bool) $department->is_school_wide) {
            return true;
        }
        return in_array(
            strtolower(trim((string) $student->college)),
            self::normalizedCollegeNames($department),
            true
        );
    }

    /** SSC can manage SSC-owned events only; this never broadens event access. */
    public static function assertCanManageEvent(User $user, Event $event): void
    {
        abort_unless(
            $user->role?->role_name === 'Department Staff'
                && $user->department_id !== null
                && (int) $user->department_id === (int) $event->department_id,
            403,
            'You cannot manage events owned by another department.'
        );
    }

    /** Personnel need both same-department ownership and explicit assignment. */
    public static function assertAssignedPersonnelCanAccess(User $user, Event $event): void
    {
        abort_unless(
            $user->role?->role_name === 'Attendance Personnel'
                && $user->department_id !== null
                && (int) $user->department_id === (int) $event->department_id
                && $user->personnel !== null
                && $event->assignments()
                    ->where('personnel_id', $user->personnel->personnel_id)
                    ->exists(),
            403,
            'This event is not assigned to your department and account.'
        );
    }

    private static function normalizedCollegeNames(Department $department): array
    {
        $name = trim((string) $department->department_name);
        $aliases = self::COLLEGE_ALIASES[$name] ?? [];
        return array_values(array_unique(array_map(
            static fn (string $value): string => strtolower(trim($value)),
            array_merge([$name], $aliases)
        )));
    }
}
