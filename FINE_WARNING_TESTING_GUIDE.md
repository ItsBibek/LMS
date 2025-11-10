# Fine Warning Email Notification - Testing Guide

## 🎯 Feature Overview

This feature **automatically** sends email notifications to students whose books are due tomorrow, warning them that fines (Rs. 2/day) will start accumulating if they don't return the book on time.

**✨ No Scheduler Setup Required!** - The system automatically checks and sends emails when the application loads. Uses cache to ensure emails are only sent once per day.

## 📋 Components Created

1. **Mailable Class**: `app/Mail/FineWarningMail.php`
2. **Email Template**: `resources/views/emails/fine-warning.blade.php`
3. **Service Provider**: `app/Providers/FineWarningServiceProvider.php` (auto-runs on app boot)
4. **Console Command**: `app/Console/Commands/SendFineWarnings.php` (for manual testing)

---

## 🧪 Testing Methods

### Method 1: Automatic Testing (Production Ready - No Setup Required!)

**The emails will be sent automatically when anyone visits your application!**

This is the simplest method and how the system works in production:

#### Step 1: Create Test Data

Set up an issue with a due date of tomorrow (see SQL/Tinker commands below in Method 2).

#### Step 2: Visit Your Application

Simply open your application in a browser:
```
http://127.0.0.1:8000
```

**That's it!** The first time someone visits the app each day, the system will:
- Check for books due tomorrow
- Send warning emails automatically
- Cache the result so it doesn't send duplicate emails

#### How It Works:
- When the Laravel app boots, `FineWarningServiceProvider` runs
- It checks if emails were already sent today (using cache)
- If not, it finds books due tomorrow and sends emails
- Marks them as sent in cache until end of day
- Next day, the cache expires and it checks again

**Note:** After the first visitor of the day triggers the emails, subsequent visitors won't trigger duplicate sends (protected by cache).

**Testing Multiple Times:** If you want to resend emails for the same book on the same day, clear the cache:
```bash
# Clear cache for a specific issue (replace 123 with actual issue ID)
php artisan cache:forget fine_warning_issue_123_2025-11-10

# Or clear all cache:
php artisan cache:clear
```

**Multiple Books for Same Student:** The system automatically sends separate emails for each book due tomorrow, even if they belong to the same student.

---

### Method 2: Manual Command Testing (For Immediate Testing)

This tests the command immediately without waiting for a visitor.

#### Step 1: Create Test Data

In your database, create or update an issue with a due date of **tomorrow**.

**Option A: Via Database Tool (phpMyAdmin/MySQL Workbench)**
```sql
-- Find an existing unreturned issue
SELECT * FROM issues WHERE return_date IS NULL;

-- Update its due_date to tomorrow
UPDATE issues 
SET due_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
WHERE id = 1 AND return_date IS NULL;
```

**Option B: Via Laravel Tinker**
```bash
php artisan tinker
```
```php
// Create a test issue due tomorrow
use App\Models\Issue;
use Carbon\Carbon;

$issue = Issue::whereNull('return_date')->first();
$issue->due_date = Carbon::tomorrow()->toDateString();
$issue->save();

// Verify
echo "Due date set to: " . $issue->due_date;
exit;
```

#### Step 2: Ensure Student Has Email

Make sure the student associated with the issue has a valid email address:

```bash
php artisan tinker
```
```php
use App\Models\User;

$student = User::where('batch_no', 'AC-078CSIT03')->first();
echo "Email: " . $student->email;

// If no email, set one:
$student->email = 'your.test.email@gmail.com';
$student->save();
exit;
```

#### Step 3: Run the Command Manually

```bash
php artisan fines:send-warnings
```

**Expected Output:**
```
Checking for books due tomorrow...
Found 1 book(s) due tomorrow.
✓ Email sent to Student Name (student@email.com)

=== Summary ===
Total books due tomorrow: 1
Emails sent successfully: 1
```

#### Step 4: Check Your Email

Check the email inbox of the student's email address. You should receive a nicely formatted email with:
- Warning that the book is due tomorrow
- Book details (Title, Author, Accession Number)
- Due date information
- Fine rate (Rs. 2/day)
- Call-to-action button

---

### Method 3: Test Email Preview (Without Sending)

Create a test route to preview the email design:

**Add to `routes/web.php`:**
```php
Route::get('/test-email', function () {
    $issue = \App\Models\Issue::whereNull('return_date')
        ->with(['user', 'book'])
        ->first();
    
    if (!$issue) {
        return 'No unreturned issues found for testing.';
    }
    
    return new \App\Mail\FineWarningMail($issue);
});
```

Then visit: `http://127.0.0.1:8000/test-email`

This shows the email design without actually sending it.

---

## 🔍 Verification Checklist

- [ ] Mail configuration is correct in `.env`
- [ ] Student has a valid email address
- [ ] Issue has `return_date = NULL` (unreturned)
- [ ] Issue `due_date` is set to tomorrow's date
- [ ] Gmail "Less secure app access" is enabled OR App Password is used
- [ ] Email arrives in inbox (check spam folder too)
- [ ] Email formatting looks correct
- [ ] All book details are displayed correctly

---

## 📧 Mail Configuration

Your mail is already configured in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=anushashrestha813@gmail.com
MAIL_PASSWORD=cclpzepxgtbvteov
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=anushashrestha813@gmail.com
MAIL_FROM_NAME="Academia Library"
```

### Important: Gmail Security

If emails aren't sending, you may need to:

1. **Use App Password** (Recommended):
   - Go to Google Account → Security → 2-Step Verification → App Passwords
   - Generate an app password for "Mail"
   - Replace `MAIL_PASSWORD` in `.env` with the generated password

2. **Enable Less Secure Apps** (Not Recommended):
   - Only if App Password doesn't work
   - Go to: https://myaccount.google.com/lesssecureapps

---

## 🐛 Troubleshooting

### No emails sent

**Check 1: Are there any books due tomorrow?**
```bash
php artisan tinker
```
```php
use App\Models\Issue;
use Carbon\Carbon;

$tomorrow = Carbon::tomorrow()->toDateString();
$issues = Issue::whereNull('return_date')->where('due_date', $tomorrow)->get();
echo "Books due tomorrow: " . $issues->count();
```

**Check 2: Do students have email addresses?**
```bash
php artisan tinker
```
```php
use App\Models\Issue;
use Carbon\Carbon;

$tomorrow = Carbon::tomorrow()->toDateString();
$issues = Issue::whereNull('return_date')
    ->where('due_date', $tomorrow)
    ->with('user')
    ->get();

foreach ($issues as $issue) {
    echo $issue->user->student_name . " - Email: " . ($issue->user->email ?? 'NO EMAIL') . "\n";
}
```

### Email not arriving

1. Check spam/junk folder
2. Verify Gmail credentials in `.env`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test with a simple mail:
   ```bash
   php artisan tinker
   ```
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('your@email.com')->subject('Test');
   });
   ```

### Command not found

```bash
# Clear config cache
php artisan config:clear

# Re-register commands
php artisan clear-compiled
composer dump-autoload
```

---

## 📊 Monitoring

### Check Scheduled Tasks
```bash
php artisan schedule:list
```

### View Laravel Logs
```bash
# Windows
type storage\logs\laravel.log

# Or view last 50 lines
Get-Content storage\logs\laravel.log -Tail 50
```

### Test Query
```bash
php artisan tinker
```
```php
use App\Models\Issue;
use Carbon\Carbon;

// Check all unreturned books
$unreturned = Issue::whereNull('return_date')
    ->with(['user', 'book'])
    ->get();

foreach ($unreturned as $issue) {
    $dueDate = Carbon::parse($issue->due_date);
    $daysUntilDue = now()->diffInDays($dueDate, false);
    echo "{$issue->user->student_name} - Due: {$issue->due_date} ({$daysUntilDue} days)\n";
}
```

---

## 🚀 Quick Start Testing

**Complete test in 3 commands:**

```bash
# 1. Set up test data
php artisan tinker
# Then run: $issue = Issue::first(); $issue->due_date = \Carbon\Carbon::tomorrow()->toDateString(); $issue->save(); exit;

# 2. Ensure email exists
php artisan tinker
# Then run: $user = User::first(); $user->email = 'yourtest@email.com'; $user->save(); exit;

# 3. Send the warning
php artisan fines:send-warnings
```

---

## 📅 Automatic Execution Details

- **Trigger**: Runs automatically when the application boots (on each web request)
- **Frequency**: Once per issue per day (protected by cache)
- **Cache Key**: `fine_warning_issue_{issue_id}_YYYY-MM-DD`
- **Cache Duration**: Until end of day
- **Target**: Issues with `due_date = tomorrow` AND `return_date = NULL`
- **Provider**: `App\Providers\FineWarningServiceProvider`
- **Per-Student**: If a student has multiple books due tomorrow, they receive separate emails for each book

---

## ✅ Success Criteria

✓ Command runs without errors
✓ Correct number of emails reported as sent
✓ Email arrives in student's inbox
✓ Email contains correct book and student information
✓ Email design is responsive and professional
✓ Links in email work correctly
