<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadBackupToS3 extends Command
{
    protected $signature = 'backup:upload';
    protected $description = 'Upload the latest SQL backup to AWS S3';

    public function handle()
    {
        $backupPath = storage_path('backups'); 
        $latestFile = $this->getLatestBackupFile($backupPath);

        if ($latestFile) {
            $fileName = basename($latestFile);
            $s3Path = 'backups/' . $fileName;

            Storage::disk('s3')->put($s3Path, file_get_contents($latestFile));

            $this->info("Uploaded: {$fileName} to S3");
        } else {
            $this->warn('No .sql files found in the backups folder.');
        }
    }

    /**
     * Get the latest .sql file from the specified directory.
     *
     * @param string $directory
     * @return string|null
     */
    private function getLatestBackupFile(string $directory): ?string
    {
        $files = glob($directory . '/*.sql');

        if (empty($files)) {
            return null;
        }
        usort($files, function ($a, $b) {
            return filemtime($b) <=> filemtime($a);
        });

        return $files[0];
    }
}
