<?php

namespace App\Imports;

use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentsImport
{

    public $created = 0;
    public $updated = 0;
    public $failed = 0;


    public function import($file)
    {
        $spreadsheet = IOFactory::load($file);

        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray();


        foreach (array_slice($rows, 1) as $row) {

            try {

                if (empty($row[0]) || empty($row[1]) || empty($row[3])) {
                    $this->failed++;
                    continue;
                }


                $student = Student::where('student_number', $row[0])->first();


                if ($student) {

                    $student->update([
                        'first_name' => $row[1],
                        'middle_name' => $row[2] ?? null,
                        'last_name' => $row[3],
                        'college' => $row[4],
                        'program_name' => $row[5],
                        'program_code' => $row[6],
                        'year_level' => $row[7],
                        'student_status' => $row[8] ?? 'Regular',
                        'rfid_identifier' => $row[9] ?? null,
                        'status' => $row[10] ?? 'Active',
                    ]);

                    $this->updated++;

                } else {

                    Student::create([
                        'student_number' => $row[0],
                        'first_name' => $row[1],
                        'middle_name' => $row[2] ?? null,
                        'last_name' => $row[3],
                        'college' => $row[4],
                        'program_name' => $row[5],
                        'program_code' => $row[6],
                        'year_level' => $row[7],
                        'student_status' => $row[8] ?? 'Regular',
                        'rfid_identifier' => $row[9] ?? null,
                        'status' => $row[10] ?? 'Active',
                    ]);

                    $this->created++;

                }


            } catch (\Exception $e) {

                $this->failed++;

            }

        }

    }

}