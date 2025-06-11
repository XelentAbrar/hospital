<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
class BackupsService
{
    const BACKUP_INTERVAL = 1;
    const BACKUP_FILE_PREFIX = 'jinnah_';

    public function takeBackupIfDue()
    {
        $lastBackupTime = cache('last_backup_time');

        if (!$lastBackupTime || now()->diffInMinutes($lastBackupTime) >= self::BACKUP_INTERVAL) {
            $this->takeBackup();
            cache(['last_backup_time' => now()]);
        } else {
            Log::info('Backup not due yet.');
        }
    }

   public function takeBackup()
{
    $filename = self::BACKUP_FILE_PREFIX . now()->format('Y_m_d_H_i_s') . '.sql';
    $backupPath = storage_path("backups/{$filename}");

    Log::info("Backup filename: $filename");
    Log::info("Backup path: $backupPath");

    if (!is_dir(storage_path('app/backup-temp'))) {
        mkdir(storage_path('app/backup-temp'), 0755, true);
        Log::info("Backups directory created at: " . storage_path('backups'));
    } else {
        Log::info("Backups directory already exists.");
    }

    try {
        $this->exportDatabase($backupPath);
        Log::info("Backup created successfully at: $backupPath");
        $this->uploadToS3($backupPath);
    } catch (\Exception $e) {
        Log::error("Backup failed: " . $e->getMessage());
    }
}


private function exportDatabase($backupPath)
{
    $dbName = env('DB_DATABASE');
    $dbUser = decrypt(env('ENCRYPTED_DB_USERNAME'));
    $dbPassword = decrypt(env('ENCRYPTED_DB_PASSWORD'));

    Log::info("DB Username: " . $dbUser);
    Log::info("DB Password: " . $dbPassword);

    // $dumpBinaryPath = 'E:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe';
    $dumpBinaryPath = env('MYSQL_DUMP_PATH', 'path/to/default/mysqldump.exe'); // Fallback to default if not set

    $command = sprintf(
        '%s -u %s -p%s %s > %s 2>&1',
        escapeshellarg($dumpBinaryPath),
        escapeshellarg($dbUser),
        escapeshellarg($dbPassword),
        escapeshellarg($dbName),
        escapeshellarg($backupPath)
    );

    Log::info("Executing mysqldump command: $command");

    $output = [];
    $returnVar = null;
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        Log::error('mysqldump command failed with return code: ' . $returnVar);
        Log::error('mysqldump output: ' . implode("\n", $output));
        throw new \Exception('mysqldump command failed: ' . implode("\n", $output));
    }
}


    private function uploadToS3($path)
    {
        $filename = basename($path);
        $s3Path = "s3://hmsbackup/HMSBACKUP/{$filename}";

        try {
            Log::info("Attempting to upload {$filename} to S3.");
            $command = sprintf('aws s3 cp %s %s', escapeshellarg($path), escapeshellarg($s3Path));

            $output = [];
            $returnVar = null;
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('AWS S3 upload failed: ' . implode("\n", $output));
            }

            Log::info("Successfully uploaded {$filename} to S3.");
        } catch (\Exception $e) {
            Log::error("Failed to upload {$filename} to S3: " . $e->getMessage());
        } finally {
            // Always unlink the backup file after upload attempt
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}

