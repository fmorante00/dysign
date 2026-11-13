<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Support\DepartmentAccess;
use Illuminate\Http\Request;

/** Read-only registry for Department Staff and Attendance Personnel. */
class DepartmentStudentDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $user = $request->user();
        $search = trim($validated['search'] ?? '');

        $query = DepartmentAccess::scopeStudentsForUser(Student::query(), $user);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('program_code', 'like', "%{$search}%");
            });
        }

        $students = $query
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(20)
            ->withQueryString();

        return view('department.students.index', compact('students', 'search', 'user'));
    }

    public function show(Request $request, string $student)
    {
        $user = $request->user();
        $studentRecord = DepartmentAccess::scopeStudentsForUser(Student::query(), $user)
            ->where('student_id', $student)
            ->firstOrFail();

        return view('department.students.show', compact('studentRecord', 'user'));
    }
}
