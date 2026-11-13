<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Pagination is performed in the database, not on an in-memory collection.
        // Keep page size bounded so large imports cannot overwhelm the page.
        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        // These institutional totals are independent of the current page and filters.
        $totalStudents = Student::count();
        $rfidAssignedCount = Student::whereNotNull('rfid_identifier')->count();
        $missingRfidCount = Student::whereNull('rfid_identifier')->count();
        $activeStudentsCount = Student::where('status', 'Active')->count();

        // Options must come from the full registry, never just the current page.
        $colleges = Student::query()->whereNotNull('college')
            ->where('college', '!=', '')->distinct()->orderBy('college')->pluck('college');
        $programs = Student::query()->whereNotNull('program_code')
            ->where('program_code', '!=', '')->distinct()->orderBy('program_code')->pluck('program_code');

        $query = Student::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('college')) {
            $query->where('college', $request->query('college'));
        }

        if ($request->filled('program_code')) {
            $query->where('program_code', $request->query('program_code'));
        }

        if ($request->filled('year_level')) {
            $query->where('year_level', $request->query('year_level'));
        }

        $students = $query->orderByDesc('student_id')
            ->paginate($perPage)
            ->withQueryString();

        return view('students.index', compact(
            'students',
            'totalStudents',
            'rfidAssignedCount',
            'missingRfidCount',
            'activeStudentsCount',
            'colleges',
            'programs',
            'perPage'
        ));
    }



    public function import()
    {
        return view('students.import');
    }



    public function processImport(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',

                // XLSX/CSV still supported
                // plus ZIP for Excel + photos
                'mimes:xlsx,xls,csv,zip',

                'max:20480',
            ],
        ]);


        try {

            $import =
                new StudentsImport();


            $import->import(
                $request->file('file')
            );


            return redirect()
                ->route('students.index')
                ->with('success', [
                    'created' =>
                        $import->created,

                    'updated' =>
                        $import->updated,

                    'failed' =>
                        $import->failed,
                ]);


        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'file' =>
                        'Import failed: '
                        . $e->getMessage()
                ]);

        }
    }



    public function show(string $id)
    {
        $student =
            Student::findOrFail($id);


        return view(
            'students.show',
            compact('student')
        );
    }
}