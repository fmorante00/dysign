<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();


        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'student_number',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'first_name',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'last_name',
                    'like',
                    '%' . $request->search . '%'
                );

            });

        }


        if ($request->college) {

            $query->where(
                'college',
                $request->college
            );

        }


        if ($request->program_code) {

            $query->where(
                'program_code',
                $request->program_code
            );

        }


        if ($request->year_level) {

            $query->where(
                'year_level',
                $request->year_level
            );

        }


        $students = $query
            ->latest()
            ->get();


        return view(
            'students.index',
            compact('students')
        );
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