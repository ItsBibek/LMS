<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('photos:import {dir?} {--skip=*}', function () {
    $dir = $this->argument('dir') ?: 'C:\\Users\\anush\\Downloads\\fwd2078batchphoto';
    $skip = (array) $this->option('skip');
    $skipNorm = collect($skip)
        ->map(function ($s) { return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$s)); })
        ->filter()
        ->values()
        ->all();
    if (!is_dir($dir)) {
        $this->error("Directory not found: $dir");
        return 1;
    }

    $files = glob($dir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
    if (!$files || count($files) === 0) {
        $this->warn('No image files found in the directory.');
        return 0;
    }

    $updated = 0; $skipped = 0; $missing = 0;

    foreach ($files as $path) {
        $filename = basename($path);
        // Normalize: remove 'AC' prefix, non-alphanumerics, uppercase (e.g., AC-078CSIT23 -> 078CSIT23)
        $nameOnly = pathinfo($filename, PATHINFO_FILENAME);
        $normalized = strtoupper(preg_replace(['/^AC[-_\s]*/i','/[^A-Za-z0-9]/'], ['', ''], $nameOnly));
        if ($normalized === '') { $skipped++; continue; }

        // Respect skip list
        if (!empty($skipNorm) && in_array($normalized, $skipNorm, true)) {
            $this->line("Skipped (per option): $filename");
            $skipped++;
            continue;
        }

        /** @var User|null $user */
        $user = User::where('batch_no', $normalized)
            ->orWhere('batch_no', $nameOnly)
            ->orWhere('batch_no', $filename)
            ->first();

        if (!$user) {
            // Fallback: try matching by student_name (normalized like filename)
            $user = User::get()->first(function ($u) use ($normalized) {
                $normName = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$u->student_name));
                return $normName === $normalized;
            });
        }

        if (!$user) {
            $this->warn("No student matched for file: $filename (normalized: $normalized)");
            $missing++;
            continue;
        }

        // Store into public disk under students/
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $targetName = $user->batch_no . '.' . $ext;
        try {
            $storedPath = Storage::disk('public')->putFileAs('students', new File($path), $targetName);
            if ($storedPath) {
                $user->photo_path = $storedPath; // e.g., students/078CSIT23.jpg
                $user->save();
                $updated++;
                $this->info("Updated: {$user->batch_no} <- $filename");
            } else {
                $this->error("Failed to store: $filename");
            }
        } catch (\Throwable $e) {
            $this->error("Error importing $filename: " . $e->getMessage());
        }
    }

    $this->line("Done. Updated: $updated, Missing: $missing, Skipped: $skipped");
    $this->comment('If images do not appear, ensure storage symlink exists: php artisan storage:link');
    return 0;
})->purpose('Import student photos from a directory and update their profiles by batch number');

Artisan::command('photos:ingest-from-db', function () {
    $users = User::whereNotNull('photo_path')->get();
    if ($users->count() === 0) {
        $this->warn('No users with photo_path found.');
        return 0;
    }

    $updated = 0; $missing = 0; $skipped = 0;
    foreach ($users as $user) {
        $path = trim((string)$user->photo_path);
        if ($path === '') { $skipped++; continue; }

        // If already stored on public disk, ensure exists
        if (Str::startsWith($path, ['students/', 'public/'])) {
            $p = Str::startsWith($path, 'public/') ? Str::after($path, 'public/') : $path;
            if (Storage::disk('public')->exists($p)) {
                continue; // already good
            }
        }

        // If it's a URL, leave it as-is
        if (Str::startsWith($path, ['http://', 'https://'])) {
            continue;
        }

        // If it's an absolute local path, copy into public disk
        if (file_exists($path)) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';
            $targetName = $user->batch_no . '.' . $ext;
            try {
                $storedPath = Storage::disk('public')->putFileAs('students', new \Illuminate\Http\File($path), $targetName);
                if ($storedPath) {
                    $user->photo_path = $storedPath;
                    $user->save();
                    $updated++;
                    $this->info("Ingested: {$user->batch_no} <- $path");
                } else {
                    $this->error("Failed to store: $path");
                }
            } catch (\Throwable $e) {
                $this->error("Error ingesting $path: ".$e->getMessage());
            }
            continue;
        }

        $this->warn("File not found for user {$user->batch_no}: $path");
        $missing++;
    }

    $this->line("Done. Updated: $updated, Missing files: $missing, Skipped: $skipped");
    $this->comment('If images do not appear, ensure storage symlink exists: php artisan storage:link');
    return 0;
})->purpose('Copy photos referenced in users.photo_path into public storage and normalize paths');
