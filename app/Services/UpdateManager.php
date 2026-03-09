<?php

namespace App\Services;

use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UpdateManager
{
    /**
     * Process the uploaded update ZIP file.
     *
     * @param string $zipPath Path to the uploaded temporary file
     * @param string $password The password provided by user
     * @return array Result information
     */
    public function processUpdate(string $zipPath, string $password): array
    {
        // 1. Validate password
        $secret = env('UPDATE_PASSWORD');
        if (empty($secret) || $password !== $secret) {
            return ['success' => false, 'message' => 'Invalid update password. Check your .env file.'];
        }

        // 2. Validate ZIP and manifest
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['success' => false, 'message' => 'Could not open ZIP file.'];
        }

        $manifestIndex = $zip->locateName('manifest.json');
        if ($manifestIndex === false) {
            $zip->close();
            return ['success' => false, 'message' => 'Invalid update file: manifest.json missing.'];
        }

        $manifestData = json_decode($zip->getFromIndex($manifestIndex), true);
        if (!$manifestData) {
            $zip->close();
            return ['success' => false, 'message' => 'Invalid manifest.json file.'];
        }

        // 3. Extract files
        $updatedFiles = [];
        $failedFiles = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);

            if ($fileName === 'manifest.json')
                continue;

            $content = $zip->getFromIndex($i);
            $destination = base_path($fileName);

            // Ensure directory exists
            $dir = dirname($destination);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            try {
                File::put($destination, $content);
                $updatedFiles[] = $fileName;
            } catch (\Exception $e) {
                Log::error("Failed to update file $fileName: " . $e->getMessage());
                $failedFiles[] = $fileName;
            }
        }

        $zip->close();

        // 4. Post-update actions
        $migrationRun = false;
        if ($this->hasMigrations($updatedFiles)) {
            try {
                Artisan::call('migrate', ['--force' => true]);
                $migrationRun = true;
            } catch (\Exception $e) {
                Log::error("Post-update migration failed: " . $e->getMessage());
            }
        }

        // Clear cache
        Artisan::call('optimize:clear');

        return [
            'success' => true,
            'message' => 'Update processed successfully.',
            'updated_files' => count($updatedFiles),
            'failed_files' => count($failedFiles),
            'migration_run' => $migrationRun,
        ];
    }

    /**
     * Check if any of the updated files are migrations.
     */
    protected function hasMigrations(array $files): bool
    {
        foreach ($files as $file) {
            if (str_contains($file, 'database/migrations/')) {
                return true;
            }
        }
        return false;
    }
}
