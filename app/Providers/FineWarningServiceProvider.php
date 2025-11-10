<?php

namespace App\Providers;

use App\Mail\FineWarningMail;
use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class FineWarningServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run in web requests (not console commands)
        if (!$this->app->runningInConsole()) {
            $this->checkAndSendFineWarnings();
        }
    }

    /**
     * Check for books due tomorrow and send warning emails
     * Uses cache to track individual issues that have been notified
     */
    protected function checkAndSendFineWarnings(): void
    {
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();

        \Log::info("FineWarningService: Checking for issues due on {$tomorrow}");

        // Find all unreturned issues that are due tomorrow
        $issuesDueTomorrow = Issue::whereNull('return_date')
            ->where('due_date', $tomorrow)
            ->with(['user', 'book'])
            ->get();

        \Log::info("FineWarningService: Found {$issuesDueTomorrow->count()} issues due tomorrow");

        if ($issuesDueTomorrow->isEmpty()) {
            return;
        }

        // Send emails, tracking each issue individually
        foreach ($issuesDueTomorrow as $issue) {
            // Check if we've already sent a warning for this specific issue
            $issueCacheKey = 'fine_warning_issue_' . $issue->id . '_' . now()->toDateString();
            
            if (Cache::has($issueCacheKey)) {
                \Log::info("FineWarningService: Skipping issue #{$issue->id} - already notified today");
                continue; // Already sent for this issue today
            }

            $student = $issue->user;

            // Skip if student has no email
            if (!$student || !$student->email) {
                \Log::warning("FineWarningService: Skipping issue #{$issue->id} - student has no email");
                continue;
            }

            try {
                // Send the email
                Mail::to($student->email)->send(new FineWarningMail($issue));
                \Log::info("FineWarningService: Sent email to {$student->email} for issue #{$issue->id}");
                
                // Mark this specific issue as notified (expires at end of day)
                Cache::put($issueCacheKey, true, now()->endOfDay());
            } catch (\Exception $e) {
                // Log error but don't break the application
                \Log::error("FineWarningService: Failed to send fine warning email for issue #{$issue->id}: " . $e->getMessage());
            }
        }
    }
}
