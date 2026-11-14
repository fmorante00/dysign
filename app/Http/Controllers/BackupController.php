<?php

namespace App\Http\Controllers;

use App\Models\SystemBackup;
use Illuminate\Http\Request;
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

    public function index(Request $request)
    {
        // Keep filters confined to backup metadata. SQL files are never parsed here.
        $search = trim((string) $request->query('search', ''));
        $search = mb_substr($search, 0, 100);
        $status = (string) $request->query('status', 'all');
        if (!in_array($status, ['all', 'Completed', 'Processing', 'Failed'], true)) {
            $status = 'all';
        }

        $perPage = (int) $request->query('per_page', 10);
        if (!in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $dateFrom = (string) $request->query('from', '');
        $dateTo = (string) $request->query('to', '');
        $dateFrom = $this->validBackupDate($dateFrom) ? $dateFrom : '';
        $dateTo = $this->validBackupDate($dateTo) ? $dateTo : '';

        $query = SystemBackup::query()->with('creator');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', '%' . $search . '%')
                    ->orWhereHas('creator', function ($users) use ($search) {
                        $users->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $backups = $query->orderByDesc('created_at')
            ->orderByDesc('backup_id')
            ->paginate($perPage)
            ->withQueryString();

        // A completed database record does not necessarily mean its SQL file still exists.
        $backups->getCollection()->each(function (SystemBackup $backup) {
            $relative = (string) $backup->file_path;
            $validPath = preg_match('~^backups/[A-Za-z0-9_.-]+\.sql$~D', $relative)
                && !str_contains($relative, '..');
            $backup->file_available = $validPath
                && is_file(storage_path('app/' . $relative));
        });

        $counts = SystemBackup::query()
            ->selectRaw('COUNT(*) AS total')
            ->selectRaw("SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed")
            ->selectRaw("SUM(CASE WHEN status = 'Failed' THEN 1 ELSE 0 END) AS failed")
            ->selectRaw("SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) AS processing")
            ->first();

        $stats = [
            'total' => (int) ($counts->total ?? 0),
            'completed' => (int) ($counts->completed ?? 0),
            'failed' => (int) ($counts->failed ?? 0),
            'processing' => (int) ($counts->processing ?? 0),
        ];

        $lastBackup = SystemBackup::query()
            ->where('status', 'Completed')
            ->latest('created_at')
            ->first();

        $latestRecord = SystemBackup::query()->latest('created_at')->first();
        $storageUsed = $this->formatBytes((int) SystemBackup::query()
            ->where('status', 'Completed')->sum('size_bytes'));

        return view('backup.index', compact(
            'backups', 'lastBackup', 'latestRecord', 'storageUsed',
            'stats', 'search', 'status', 'perPage', 'dateFrom', 'dateTo'
        ));
    }

    private function validBackupDate(string $date): bool
    {
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $date, $parts)) {
            return false;
        }

        return checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
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

        $relativePath = (string) $backup->file_path;
        if (!preg_match('~^backups/[A-Za-z0-9_.-]+\.sql$~D', $relativePath)
            || str_contains($relativePath, '..')) {
            abort(404);
        }

        $fullPath = storage_path('app/' . $relativePath);


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