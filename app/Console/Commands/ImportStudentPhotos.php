<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportStudentPhotos extends Command
{
    protected $signature = 'students:import-photos {--path=students : Path within storage/app/public} {--dry : Preview changes without saving} {--fuzzy : Try fuzzy matching for close name spellings}';

    protected $description = 'Import student photos by matching filenames to student_name and updating users.photo_path';

    public function handle()
    {
        $disk = Storage::disk('public');
        $path = trim((string) $this->option('path')) ?: 'students';
        $dry = (bool) $this->option('dry');

        if (!$disk->exists($path)) {
            $this->error("Path not found on public disk: {$path}");
            return 1;
        }

        // Get all files (non-recursive is fine; switch to allFiles() if you have subfolders)
        $files = $disk->files($path);
        if (empty($files)) {
            $this->warn("No files found in storage/app/public/{$path}");
            return 0;
        }

        $this->info("Scanning ".count($files)." files in public/{$path}...");

        $updated = 0;
        $skippedNoMatch = 0;
        $skippedSame = 0;
        $fuzzyEnabled = (bool) $this->option('fuzzy');

        // Build an index of students by normalized name for faster matching
        $students = User::query()->get(['batch_no','student_name','photo_path']);
        $index = [];
        $nameMap = []; // normalized full -> display name
        foreach ($students as $stu) {
            $variants = $this->nameVariants($stu->student_name);
            foreach ($variants as $v) {
                $index[$v][] = $stu;
            }
            $nameMap[$this->normalizeName($stu->student_name)] = $stu->student_name;
        }

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $norm = $this->normalizeName($filename);

            $matches = $index[$norm] ?? [];
            if (empty($matches) && $fuzzyEnabled) {
                // Try fuzzy matching against all student names (normalized full names)
                [$best, $score] = $this->bestFuzzyMatch($norm, array_keys($nameMap));
                if ($best !== null && $score >= 0.85) {
                    $stuName = $nameMap[$best];
                    $confirm = $dry ? true : $this->confirm("Fuzzy match: '{$filename}' -> '{$stuName}' (score: ".round($score*100)."%). Apply?");
                    if ($confirm) {
                        $matches = $index[$best] ?? [];
                    }
                }
            }

            if (empty($matches)) {
                $this->line("No match for file: {$file}", 'comment');
                $skippedNoMatch++;
                continue;
            }
            if (count($matches) > 1) {
                $this->warn("Ambiguous match (".count($matches).") for '{$filename}', skipping. Names: ".implode(', ', array_map(fn($u) => $u->student_name.'('.$u->batch_no.')', $matches)));
                $skippedNoMatch++;
                continue;
            }

            /** @var User $student */
            $student = $matches[0];
            if ($student->photo_path === $file) {
                $skippedSame++;
                continue;
            }

            if ($dry) {
                $this->line("DRY: would set {$student->student_name} ({$student->batch_no}) -> {$file}");
                continue;
            }

            $student->update(['photo_path' => $file]);
            $updated++;
        }

        $this->newLine();
        $this->info("Done.");
        $this->line("Updated: {$updated}");
        $this->line("Skipped (no match/ambiguous): {$skippedNoMatch}");
        $this->line("Skipped (already same path): {$skippedSame}");

        return 0;
    }

    private function normalizeName(string $name): string
    {
        $s = mb_strtolower($name, 'UTF-8');
        // Replace non-alphanumeric with single space
        $s = preg_replace('/[^a-z0-9]+/u', ' ', $s);
        // Collapse spaces
        $s = trim(preg_replace('/\s+/', ' ', $s));
        return $s;
    }

    private function nameVariants(string $name): array
    {
        $full = $this->normalizeName($name); // e.g., "john doe smith"
        $parts = $full !== '' ? explode(' ', $full) : [];
        $variants = [$full];
        if (count($parts) >= 2) {
            // first + last variant (drop middle names)
            $firstLast = $parts[0] . ' ' . $parts[count($parts)-1];
            $variants[] = $firstLast;
            // concatenated variant without spaces
            $variants[] = str_replace(' ', '', $firstLast);
            $variants[] = str_replace(' ', '', $full);
        } elseif (count($parts) === 1) {
            $variants[] = $parts[0];
        }
        // ensure unique
        return array_values(array_unique($variants));
    }

    private function bestFuzzyMatch(string $needle, array $haystack): array
    {
        $best = null;
        $bestScore = 0.0;
        foreach ($haystack as $candidate) {
            $score = $this->similarity($needle, $candidate);
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $candidate;
            }
        }
        return [$best, $bestScore];
    }

    private function similarity(string $a, string $b): float
    {
        // Use similar_text percentage
        similar_text($a, $b, $percent);
        return ($percent ?? 0) / 100.0;
    }
}
