<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupBackupsCommand extends Command
{
    protected $signature =
        'backup:cleanup';

    protected $description =
        'Delete old database backups';

    public function handle()
{
    $path = storage_path('app/backups');

    $this->info("Checking directory: {$path}");

    if (!File::exists($path)) {
        $this->error("Backup folder does not exist at: {$path}");
        return;
    }

  
    $files = File::allFiles($path); 

    if (empty($files)) {
        $this->warn("No files found in this folder!");
        return;
    }

    $deletedCount = 0;
    $sevenDaysAgo = now()->subDays(7);

    foreach ($files as $file) {
        $lastModified = Carbon::createFromTimestamp($file->getMTime());
        
        $this->line("File: {$file->getFilename()} | Modified: {$lastModified->toDateTimeString()}");

        if ($lastModified->isBefore($sevenDaysAgo)) {
            try {
               
                if (File::delete($file->getPathname())) {
                    $this->info("--> Successfully deleted: {$file->getFilename()}");
                    $deletedCount++;
                } else {
                    $this->error("--> Failed to delete (Permission issue?): {$file->getFilename()}");
                }
            } catch (\Exception $e) {
                $this->error("--> Error deleting file: " . $e->getMessage());
            }
        } else {
            $this->comment("--> Skipped (Not older than 7 days)");
        }
    }

    $this->info("Finished! Total deleted: {$deletedCount}");
}
}