<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:database';

    protected $description =
        'Create automatic database backup';

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | Create Backup Directory
        |--------------------------------------------------------------------------
        */

        $backupPath =
            storage_path('app/backups');

        if (!File::exists($backupPath)) {

            File::makeDirectory(
                $backupPath,
                0755,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Database Credentials
        |--------------------------------------------------------------------------
        */

        $database =
            config('database.connections.mysql.database');

        $username =
            config('database.connections.mysql.username');

        $password =
            config('database.connections.mysql.password');

        $host =
            config('database.connections.mysql.host');

        /*
        |--------------------------------------------------------------------------
        | Backup Filename
        |--------------------------------------------------------------------------
        */

        $filename =
            'backup-' .
            now()->format('Y-m-d_H-i-s') .
            '.sql';

        $path =
            $backupPath .
            DIRECTORY_SEPARATOR .
            $filename;

        /*
        |--------------------------------------------------------------------------
        | mysqldump Path
        |--------------------------------------------------------------------------
        */

        $mysqldump =
            '"C:\\xampp\\mysql\\bin\\mysqldump.exe"';

        /*
        |--------------------------------------------------------------------------
        | Generate Command
        |--------------------------------------------------------------------------
        */

        $command =
            "{$mysqldump} " .
            "--user={$username} " .
            "--password={$password} " .
            "--host={$host} " .
            "{$database} > \"{$path}\"";

        /*
        |--------------------------------------------------------------------------
        | Execute Command
        |--------------------------------------------------------------------------
        */

        exec($command, $output, $result);

        /*
        |--------------------------------------------------------------------------
        | Check Result
        |--------------------------------------------------------------------------
        */

        if ($result === 0) {

            $this->info(
                'Database backup created successfully.'
            );

        } else {

            $this->error(
                'Database backup failed.'
            );
        }
    }
}