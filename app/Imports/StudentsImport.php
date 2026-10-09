<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;

class StudentsImport
{
    public int $created = 0;
    public int $updated = 0;
    public int $failed = 0;


    private ?string $temporaryDirectory = null;
    private ?string $photosDirectory = null;



    /*
    |--------------------------------------------------------------------------
    | IMPORT ENTRY POINT
    |--------------------------------------------------------------------------
    */

    public function import(UploadedFile $file): void
    {
        try {

            $extension =
                strtolower(
                    $file->getClientOriginalExtension()
                );


            /*
            |--------------------------------------------------------------------------
            | ZIP IMPORT
            |--------------------------------------------------------------------------
            */

            if ($extension === 'zip') {

                $spreadsheetPath =
                    $this->prepareZipImport($file);

            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL EXCEL / CSV IMPORT
            |--------------------------------------------------------------------------
            */

            else {

                $spreadsheetPath =
                    $file->getRealPath();

            }


            $this->importSpreadsheet(
                $spreadsheetPath
            );


        } finally {

            /*
            |--------------------------------------------------------------------------
            | DELETE TEMPORARY EXTRACTION FOLDER
            |--------------------------------------------------------------------------
            */

            if (
                $this->temporaryDirectory
                &&
                File::exists(
                    $this->temporaryDirectory
                )
            ) {

                File::deleteDirectory(
                    $this->temporaryDirectory
                );

            }

        }
    }



    /*
    |--------------------------------------------------------------------------
    | PREPARE ZIP FILE
    |--------------------------------------------------------------------------
    */

    private function prepareZipImport(
        UploadedFile $file
    ): string
    {
        if (!class_exists(ZipArchive::class)) {

            throw new \Exception(
                'PHP ZIP extension is not enabled.'
            );

        }


        $zip =
            new ZipArchive();


        $result =
            $zip->open(
                $file->getRealPath()
            );


        if ($result !== true) {

            throw new \Exception(
                'Unable to open ZIP file.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TEMPORARY DIRECTORY
        |--------------------------------------------------------------------------
        */

        $this->temporaryDirectory =
            storage_path(
                'app/student-import-'
                . Str::uuid()
            );


        File::makeDirectory(
            $this->temporaryDirectory,
            0755,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | SAFE ZIP EXTRACTION
        |--------------------------------------------------------------------------
        */

        for (
            $i = 0;
            $i < $zip->numFiles;
            $i++
        ) {

            $entryName =
                $zip->getNameIndex($i);


            if (!$entryName) {
                continue;
            }


            /*
             * Prevent paths such as:
             *
             * ../../dangerous-file.php
             */

            $normalized =
                str_replace(
                    '\\',
                    '/',
                    $entryName
                );


            if (
                str_contains(
                    $normalized,
                    '../'
                )
                ||
                str_starts_with(
                    $normalized,
                    '/'
                )
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | DIRECTORY
            |--------------------------------------------------------------------------
            */

            if (
                str_ends_with(
                    $normalized,
                    '/'
                )
            ) {

                File::makeDirectory(
                    $this->temporaryDirectory
                    . DIRECTORY_SEPARATOR
                    . $normalized,
                    0755,
                    true
                );

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | FILE
            |--------------------------------------------------------------------------
            */

            $destination =
                $this->temporaryDirectory
                . DIRECTORY_SEPARATOR
                . $normalized;


            File::ensureDirectoryExists(
                dirname($destination)
            );


            $contents =
                $zip->getFromIndex($i);


            if ($contents !== false) {

                file_put_contents(
                    $destination,
                    $contents
                );

            }

        }


        $zip->close();



        /*
        |--------------------------------------------------------------------------
        | FIND STUDENT SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheets =
            collect(
                File::allFiles(
                    $this->temporaryDirectory
                )
            )
            ->filter(function ($file) {

                return in_array(
                    strtolower(
                        $file->getExtension()
                    ),
                    [
                        'xlsx',
                        'xls',
                        'csv'
                    ]
                );

            })
            ->values();


        if ($spreadsheets->isEmpty()) {

            throw new \Exception(
                'No Excel or CSV file was found inside the ZIP.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FIRST SPREADSHEET FOUND
        |--------------------------------------------------------------------------
        */

        $spreadsheetPath =
            $spreadsheets
                ->first()
                ->getRealPath();



        /*
        |--------------------------------------------------------------------------
        | FIND PHOTOS DIRECTORY
        |--------------------------------------------------------------------------
        */

        $possiblePhotoDirectory =
            $this->temporaryDirectory
            . DIRECTORY_SEPARATOR
            . 'photos';


        if (
            File::isDirectory(
                $possiblePhotoDirectory
            )
        ) {

            $this->photosDirectory =
                $possiblePhotoDirectory;

        }


        return $spreadsheetPath;
    }



    /*
    |--------------------------------------------------------------------------
    | READ SPREADSHEET
    |--------------------------------------------------------------------------
    */

    private function importSpreadsheet(
        string $spreadsheetPath
    ): void
    {
        $spreadsheet =
            IOFactory::load(
                $spreadsheetPath
            );


        $sheet =
            $spreadsheet
                ->getActiveSheet();


        /*
         * true = calculate formulas
         * true = format data
         * false = numeric indexed array
         */

        $rows =
            $sheet->toArray(
                null,
                true,
                true,
                false
            );


        if (count($rows) < 2) {

            throw new \Exception(
                'The student file contains no records.'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | READ HEADER
        |--------------------------------------------------------------------------
        */

        $headers =
            array_map(
                function ($header) {

                    return $this->normalizeHeader(
                        $header
                    );

                },
                $rows[0]
            );



        /*
        |--------------------------------------------------------------------------
        | REQUIRED COLUMNS
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'student_number',
            'first_name',
            'last_name',
            'college',
            'program_name',
            'program_code',
            'year_level',
            'rfid_identifier',
        ];


        foreach (
            $requiredColumns
            as $requiredColumn
        ) {

            if (
                !in_array(
                    $requiredColumn,
                    $headers
                )
            ) {

                throw new \Exception(
                    "Required column missing: {$requiredColumn}"
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PROCESS EACH STUDENT
        |--------------------------------------------------------------------------
        */

        foreach (
            array_slice($rows, 1)
            as $row
        ) {

            try {

                $data =
                    $this->combineRow(
                        $headers,
                        $row
                    );


                /*
                |--------------------------------------------------------------------------
                | EMPTY ROW
                |--------------------------------------------------------------------------
                */

                if (
                    empty(
                        trim(
                            (string)
                            ($data['student_number'] ?? '')
                        )
                    )
                ) {

                    continue;

                }



                /*
                |--------------------------------------------------------------------------
                | VALIDATE IMPORTANT FIELDS
                |--------------------------------------------------------------------------
                */

                if (
                    empty($data['first_name'])
                    ||
                    empty($data['last_name'])
                    ||
                    empty($data['college'])
                    ||
                    empty($data['program_name'])
                    ||
                    empty($data['program_code'])
                    ||
                    empty($data['year_level'])
                    ||
                    empty($data['rfid_identifier'])
                ) {

                    $this->failed++;

                    continue;

                }



                /*
                |--------------------------------------------------------------------------
                | NORMALIZE VALUES
                |--------------------------------------------------------------------------
                */

                $studentNumber =
                    trim(
                        (string)
                        $data['student_number']
                    );


                $rfidIdentifier =
                    trim(
                        (string)
                        $data['rfid_identifier']
                    );


                /*
                |--------------------------------------------------------------------------
                | PHOTO
                |--------------------------------------------------------------------------
                */

                $photoPath =
                    null;


                if (
                    !empty(
                        $data['photo'] ?? null
                    )
                ) {

                    $photoPath =
                        $this->saveStudentPhoto(
                            $data['photo'],
                            $studentNumber
                        );

                }



                /*
                |--------------------------------------------------------------------------
                | STUDENT DATA
                |--------------------------------------------------------------------------
                */

                $studentData = [

                    'first_name' =>
                        trim(
                            (string)
                            $data['first_name']
                        ),

                    'middle_name' =>
                        !empty(
                            $data['middle_name']
                        )
                            ? trim(
                                (string)
                                $data['middle_name']
                            )
                            : null,

                    'last_name' =>
                        trim(
                            (string)
                            $data['last_name']
                        ),

                    'college' =>
                        trim(
                            (string)
                            $data['college']
                        ),

                    'program_name' =>
                        trim(
                            (string)
                            $data['program_name']
                        ),

                    'program_code' =>
                        trim(
                            (string)
                            $data['program_code']
                        ),

                    'year_level' =>
                        (int)
                        $data['year_level'],

                    'student_status' =>
                        !empty(
                            $data['student_status']
                        )
                            ? trim(
                                (string)
                                $data['student_status']
                            )
                            : 'Regular',

                    'rfid_identifier' =>
                        $rfidIdentifier,

                    'email' =>
                        !empty(
                            $data['email']
                        )
                            ? trim(
                                (string)
                                $data['email']
                            )
                            : null,

                    'status' =>
                        !empty(
                            $data['status']
                        )
                            ? trim(
                                (string)
                                $data['status']
                            )
                            : 'Active',
                ];



                /*
                |--------------------------------------------------------------------------
                | ONLY REPLACE PHOTO IF NEW PHOTO EXISTS
                |--------------------------------------------------------------------------
                */

                if ($photoPath) {

                    $studentData[
                        'photo_path'
                    ] = $photoPath;

                }



                /*
                |--------------------------------------------------------------------------
                | UPDATE OR CREATE
                |--------------------------------------------------------------------------
                */

                $student =
                    Student::where(
                        'student_number',
                        $studentNumber
                    )
                    ->first();


                if ($student) {

                    $student->update(
                        $studentData
                    );


                    $this->updated++;

                }

                else {

                    Student::create(
                        array_merge(
                            [
                                'student_number' =>
                                    $studentNumber
                            ],
                            $studentData
                        )
                    );


                    $this->created++;

                }


            } catch (\Throwable $e) {

                /*
                 * One bad student will not stop
                 * the entire import.
                 */

                $this->failed++;

            }

        }
    }



    /*
    |--------------------------------------------------------------------------
    | NORMALIZE HEADER
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Student Number
    | Student-Number
    | STUDENT_NUMBER
    |
    | becomes:
    |
    | student_number
    |
    */

    private function normalizeHeader(
        $header
    ): string
    {
        $header =
            strtolower(
                trim(
                    (string)
                    $header
                )
            );


        $header =
            preg_replace(
                '/[^a-z0-9]+/',
                '_',
                $header
            );


        return trim(
            $header,
            '_'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | COMBINE HEADER + ROW
    |--------------------------------------------------------------------------
    */

    private function combineRow(
        array $headers,
        array $row
    ): array
    {
        $row =
            array_pad(
                $row,
                count($headers),
                null
            );


        return array_combine(
            $headers,
            array_slice(
                $row,
                0,
                count($headers)
            )
        );
    }



    /*
    |--------------------------------------------------------------------------
    | SAVE STUDENT PHOTO
    |--------------------------------------------------------------------------
    */

    private function saveStudentPhoto(
        string $photoFilename,
        string $studentNumber
    ): ?string
    {
        if (!$this->photosDirectory) {

            return null;

        }


        /*
        |--------------------------------------------------------------------------
        | SECURITY: USE ONLY FILENAME
        |--------------------------------------------------------------------------
        */

        $photoFilename =
            basename(
                trim(
                    $photoFilename
                )
            );


        $source =
            $this->photosDirectory
            . DIRECTORY_SEPARATOR
            . $photoFilename;


        if (!File::exists($source)) {

            return null;

        }



        /*
        |--------------------------------------------------------------------------
        | VALID IMAGE TYPES
        |--------------------------------------------------------------------------
        */

        $extension =
            strtolower(
                pathinfo(
                    $photoFilename,
                    PATHINFO_EXTENSION
                )
            );


        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        if (
            !in_array(
                $extension,
                $allowedExtensions
            )
        ) {

            return null;

        }



        /*
        |--------------------------------------------------------------------------
        | PUBLIC STUDENT PHOTO DIRECTORY
        |--------------------------------------------------------------------------
        */

        $destinationDirectory =
            public_path(
                'student_photos'
            );


        File::ensureDirectoryExists(
            $destinationDirectory
        );



        /*
        |--------------------------------------------------------------------------
        | STANDARDIZED FILE NAME
        |--------------------------------------------------------------------------
        */

        $safeStudentNumber =
            preg_replace(
                '/[^A-Za-z0-9_-]/',
                '-',
                $studentNumber
            );


        $destinationFilename =
            $safeStudentNumber
            . '.'
            . $extension;


        $destination =
            $destinationDirectory
            . DIRECTORY_SEPARATOR
            . $destinationFilename;



        /*
        |--------------------------------------------------------------------------
        | COPY PHOTO
        |--------------------------------------------------------------------------
        */

        File::copy(
            $source,
            $destination
        );



        /*
        |--------------------------------------------------------------------------
        | DATABASE VALUE
        |--------------------------------------------------------------------------
        */

        return
            'student_photos/'
            . $destinationFilename;
    }
}