<?php

namespace App\Http\Controllers;

use App\Models\SystemBackup;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Throwable;

class BackupController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Backup Management Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $backups = SystemBackup::query()
            ->with('creator')
            ->latest('created_at')
            ->paginate(10);


        $lastBackup = SystemBackup::query()
            ->with('creator')
            ->where('status', 'Completed')
            ->latest('created_at')
            ->first();


        $latestRecord = SystemBackup::query()
            ->latest('created_at')
            ->first();


        $storageBytes = SystemBackup::query()
            ->where('status', 'Completed')
            ->sum('size_bytes');


        $storageUsed = $this->formatBytes(
            (int) $storageBytes
        );


        return view(
            'backup.index',
            compact(
                'backups',
                'lastBackup',
                'latestRecord',
                'storageUsed'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Database Backup
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        /*
        |--------------------------------------------------------------------------
        | Database Configuration
        |--------------------------------------------------------------------------
        */

        $database = config(
            'database.connections.mysql.database'
        );


        $port = config(
            'database.connections.mysql.port',
            '3306'
        );


        $username = config(
            'database.connections.mysql.username',
            'root'
        );


        $password = config(
            'database.connections.mysql.password',
            ''
        );


        if (!$database) {

            return back()->with(
                'error',
                'Database configuration could not be determined.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Backup Directory
        |--------------------------------------------------------------------------
        */

        $backupDirectory = storage_path(
            'app/backups'
        );


        if (!File::exists($backupDirectory)) {

            File::makeDirectory(
                $backupDirectory,
                0755,
                true
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Backup Filename
        |--------------------------------------------------------------------------
        */

        $filename =
            'dysign_backup_'
            . now()->format('Y-m-d_H-i-s')
            . '.sql';


        $relativePath =
            'backups/'
            . $filename;


        $fullPath =
            storage_path(
                'app/'
                . $relativePath
            );


        /*
        |--------------------------------------------------------------------------
        | Create Backup Record
        |--------------------------------------------------------------------------
        */

        $backup = SystemBackup::create([

            'created_by' =>
                auth()->id(),

            'filename' =>
                $filename,

            'file_path' =>
                $relativePath,

            'size_bytes' =>
                0,

            'status' =>
                'Processing',

            'error_message' =>
                null,

        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Locate mysqldump.exe
            |--------------------------------------------------------------------------
            */

            $mysqldump =
                $this->resolveMysqlDumpBinary();


            /*
            |--------------------------------------------------------------------------
            | Build mysqldump Command
            |--------------------------------------------------------------------------
            |
            | XAMPP on Windows can sometimes fail with:
            |
            | Can't create TCP/IP socket (10106)
            |
            | We explicitly:
            |
            | - use TCP
            | - connect through 127.0.0.1
            | - preserve important Windows environment variables
            | - let mysqldump write directly to the backup file
            |
            */

            $command = [

                $mysqldump,

                '--protocol=TCP',

                '--host=127.0.0.1',

                '--port=' . $port,

                '--user=' . $username,

                '--single-transaction',

                '--skip-lock-tables',

                '--routines',

                '--triggers',

                '--events',

                '--default-character-set=utf8mb4',

                '--result-file=' . $fullPath,

                $database,

            ];


            /*
            |--------------------------------------------------------------------------
            | Windows Environment
            |--------------------------------------------------------------------------
            */

            $environment = [

                'SystemRoot' =>
                    getenv('SystemRoot')
                    ?: 'C:\\Windows',

                'WINDIR' =>
                    getenv('WINDIR')
                    ?: 'C:\\Windows',

                'PATH' =>
                    getenv('PATH')
                    ?: 'C:\\Windows\\System32',

            ];


            /*
            |--------------------------------------------------------------------------
            | MySQL Password
            |--------------------------------------------------------------------------
            |
            | Do not put the password directly inside the command.
            |
            */

            if (
                $password !== null
                && $password !== ''
            ) {

                $environment['MYSQL_PWD'] =
                    (string) $password;

            }


            /*
            |--------------------------------------------------------------------------
            | Execute mysqldump
            |--------------------------------------------------------------------------
            */

            $process = new Process(
                $command,
                base_path(),
                $environment
            );


            $process->setTimeout(
                300
            );


            $process->run();


            /*
            |--------------------------------------------------------------------------
            | Check mysqldump Result
            |--------------------------------------------------------------------------
            */

            if (
                !$process->isSuccessful()
            ) {

                $error =
                    trim(
                        $process->getErrorOutput()
                    );


                if ($error === '') {

                    $error =
                        trim(
                            $process->getOutput()
                        );

                }


                throw new \RuntimeException(
                    $error !== ''
                        ? $error
                        : 'mysqldump failed without returning an error message.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Validate Backup File
            |--------------------------------------------------------------------------
            */

            clearstatcache(
                true,
                $fullPath
            );


            if (
                !File::exists($fullPath)
            ) {

                throw new \RuntimeException(
                    'The backup process completed but the SQL file was not created.'
                );

            }


            $fileSize =
                File::size(
                    $fullPath
                );


            if (
                $fileSize <= 0
            ) {

                throw new \RuntimeException(
                    'The backup file was created but contains no data.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Mark Backup As Completed
            |--------------------------------------------------------------------------
            */

            $backup->update([

                'size_bytes' =>
                    $fileSize,

                'status' =>
                    'Completed',

                'error_message' =>
                    null,

            ]);


            return redirect()
                ->route(
                    'backup.index'
                )
                ->with(
                    'success',
                    'System database backup created successfully.'
                );

        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Delete Incomplete File
            |--------------------------------------------------------------------------
            */

            if (
                File::exists($fullPath)
            ) {

                File::delete(
                    $fullPath
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Mark Backup As Failed
            |--------------------------------------------------------------------------
            */

            $backup->update([

                'size_bytes' =>
                    0,

                'status' =>
                    'Failed',

                'error_message' =>
                    $exception->getMessage(),

            ]);


            return redirect()
                ->route(
                    'backup.index'
                )
                ->with(
                    'error',
                    'Backup failed: '
                    . $exception->getMessage()
                );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Download Backup
    |--------------------------------------------------------------------------
    */

    public function download(
        SystemBackup $backup
    ) {

        /*
        |--------------------------------------------------------------------------
        | Only Completed Backups Can Be Downloaded
        |--------------------------------------------------------------------------
        */

        if (
            $backup->status !==
            'Completed'
        ) {

            return back()->with(
                'error',
                'This backup is not available for download.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Resolve Backup File
        |--------------------------------------------------------------------------
        */

        $fullPath =
            storage_path(
                'app/'
                . $backup->file_path
            );


        if (
            !File::exists($fullPath)
        ) {

            return back()->with(
                'error',
                'The backup file could not be found.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Download SQL File
        |--------------------------------------------------------------------------
        */

        return response()->download(
            $fullPath,
            $backup->filename,
            [
                'Content-Type' =>
                    'application/sql',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Locate mysqldump
    |--------------------------------------------------------------------------
    */

    private function resolveMysqlDumpBinary(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Optional Custom Path
        |--------------------------------------------------------------------------
        */

        $customPath =
            env(
                'MYSQLDUMP_PATH'
            );


        if (
            $customPath
            && File::exists(
                $customPath
            )
        ) {

            return $customPath;

        }


        /*
        |--------------------------------------------------------------------------
        | Default XAMPP Windows Path
        |--------------------------------------------------------------------------
        */

        $xamppPath =
            'C:\\xampp\\mysql\\bin\\mysqldump.exe';


        if (
            File::exists(
                $xamppPath
            )
        ) {

            return $xamppPath;

        }


        /*
        |--------------------------------------------------------------------------
        | Fallback To System PATH
        |--------------------------------------------------------------------------
        */

        return 'mysqldump';
    }


    /*
    |--------------------------------------------------------------------------
    | Human Readable File Size
    |--------------------------------------------------------------------------
    */

    private function formatBytes(
        int $bytes
    ): string {

        if (
            $bytes <= 0
        ) {

            return '0 KB';

        }


        $units = [

            'B',

            'KB',

            'MB',

            'GB',

            'TB',

        ];


        $power =
            (int) min(
                floor(
                    log(
                        $bytes,
                        1024
                    )
                ),
                count($units) - 1
            );


        $value =
            $bytes
            / pow(
                1024,
                $power
            );


        return number_format(
            $value,
            $power === 0
                ? 0
                : 2
        )
        . ' '
        . $units[$power];
    }
}