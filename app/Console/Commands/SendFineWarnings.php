<?php

namespace App\Console\Commands;

use App\Mail\FineWarningMail;
use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFineWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fines:send-warnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email warnings to students whose books are due tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for books due tomorrow...');

        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Find all unreturned issues that are due tomorrow
        $issuesDueTomorrow = Issue::whereNull('return_date')
            ->where('due_date', $tomorrow)
            ->with(['user', 'book']) // Eager load relationships
            ->get();

        if ($issuesDueTomorrow->isEmpty()) {
            $this->info('No books are due tomorrow. No emails sent.');
            return Command::SUCCESS;
        }

        $this->info("Found {$issuesDueTomorrow->count()} book(s) due tomorrow.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($issuesDueTomorrow as $issue) {
            $student = $issue->user;

            // Check if student has a valid email
            if (!$student || !$student->email) {
                $this->warn("Skipping issue #{$issue->id}: Student has no email address.");
                $failedCount++;
                continue;
            }

            // Check if already sent today for this specific issue
            $issueCacheKey = 'fine_warning_issue_' . $issue->id . '_' . now()->toDateString();
            if (\Cache::has($issueCacheKey)) {
                $this->warn("Skipping issue #{$issue->id}: Email already sent today for this book.");
                continue;
            }

            try {
                // Send the email
                Mail::to($student->email)->send(new FineWarningMail($issue));
                
                // Mark this specific issue as notified
                \Cache::put($issueCacheKey, true, now()->endOfDay());

                $this->info("✓ Email sent to {$student->student_name} ({$student->email}) for book {$issue->Accession_Number}");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send email to {$student->email}: {$e->getMessage()}");
                $failedCount++;
            }
        }

        // Summary
        $this->newLine();
        $this->info("=== Summary ===");
        $this->info("Total books due tomorrow: {$issuesDueTomorrow->count()}");
        $this->info("Emails sent successfully: {$sentCount}");
        
        if ($failedCount > 0) {
            $this->warn("Failed to send: {$failedCount}");
        }

        return Command::SUCCESS;
    }
}
