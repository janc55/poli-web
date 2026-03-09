<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use Illuminate\Support\Facades\Process;

class MakeUpdateZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:make-zip {--all : Include all files, not just modified ones} {--force : Overwrite existing zip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a ZIP file with modified files for deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Detecting modified files...');

        $files = $this->getModifiedFiles();

        if (empty($files)) {
            $this->warn('No modified files detected.');
            return;
        }

        $this->info('Files to include:');
        foreach ($files as $file) {
            $this->line("- $file");
        }

        if (!$this->confirm('Do you want to create the update ZIP with these files?', true)) {
            return;
        }

        $zipName = 'update-' . now()->format('Y-m-d-His') . '.zip';
        $zipPath = base_path($zipName);

        if (file_exists($zipPath) && !$this->option('force')) {
            $this->error("File $zipName already exists. Use --force to overwrite.");
            return;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("Could not create ZIP file at $zipPath");
            return;
        }

        $manifest = [
            'generated_at' => now()->toDateTimeString(),
            'files' => $files,
            'version' => '1.0',
        ];

        foreach ($files as $file) {
            $filePath = base_path($file);
            if (file_exists($filePath) && is_file($filePath)) {
                $zip->addFile($filePath, $file);
            }
        }

        $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
        $zip->close();

        $this->info("Successfully created: $zipName");
        $this->info("Path: $zipPath");
    }

    /**
     * Get list of modified files using Git.
     */
    protected function getModifiedFiles(): array
    {
        if ($this->option('all')) {
            // This is a simplified version for --all, might need more logic for massive projects
            return []; // Not implemented for safety in this version
        }

        $result = Process::run('git status --porcelain');

        if ($result->failed()) {
            $this->error('Git is not available or this is not a git repository.');
            return [];
        }

        $lines = explode("\n", trim($result->output()));
        $files = [];

        foreach ($lines as $line) {
            if (empty($line))
                continue;

            // git status --porcelain output is like " M path/to/file.php" or "?? path/to/new.blade.php"
            $status = substr($line, 0, 2);
            $file = trim(substr($line, 3));

            // Ignore deleted files (handled differently in update)
            if (strpos($status, 'D') !== false) {
                continue;
            }

            // Exclude common files that shouldn't be in an update zip
            if ($this->shouldExclude($file)) {
                continue;
            }

            $files[] = $file;
        }

        return array_unique($files);
    }

    /**
     * Determine if a file should be excluded from the update.
     */
    protected function shouldExclude(string $file): bool
    {
        $excludedPatterns = [
            '.env',
            '.git/',
            'node_modules/',
            'vendor/',
            'storage/',
            'tests/',
            '.editorconfig',
            '.gitattributes',
            '.gitignore',
            'composer.lock',
            'package-lock.json',
            'public/storage',
            'public/hot',
            'update-*.zip',
        ];

        foreach ($excludedPatterns as $pattern) {
            if (str_contains($file, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
