<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Issue;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;

class BooksController extends Controller
{
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Title' => ['required','string','max:255'],
            'Author' => ['nullable','string','max:255'],
            'Accession_Number' => ['required','string','max:255','unique:books,Accession_Number'],
        ]);

        Book::create($data);

        return redirect()->route('books.index', ['q' => $data['Accession_Number']])
            ->with('status', 'Book added successfully.');
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $normalized = $q !== '' ? preg_replace('/\D+/', '', $q) : '';

        $book = null;
        $matches = collect();
        $isIssued = false;
        $currentIssue = null;
        $lateDays = 0;
        $accruedFine = 0;

        if ($q !== '') {
            // 1) Try accession number first (supports numeric-only or raw)
            $book = Book::query()
                ->where('Accession_Number', $normalized)
                ->orWhere('Accession_Number', $q)
                ->first();

            // 2) If not found by accession, try partial Title match (case-insensitive)
            if (!$book) {
                $matches = Book::query()
                    ->where('Title', 'LIKE', '%'.$q.'%')
                    ->orderBy('Title')
                    ->limit(50)
                    ->get();

                // If exactly one match, show details directly
                if ($matches->count() === 1) {
                    $book = $matches->first();
                    $matches = collect();
                }
            }

            if ($book) {
                $currentIssue = Issue::with('user')
                    ->where('Accession_Number', $book->Accession_Number)
                    ->whereNull('return_date')
                    ->first();
                $isIssued = (bool) $currentIssue;
                if ($currentIssue) {
                    $due = Carbon::parse($currentIssue->due_date);
                    $today = Carbon::now();
                    $lateDays = max(0, $due->diffInDays($today, false));
                    $accruedFine = $lateDays * 2; // Rs. 2 per day
                }

                // Reservation status (pending and not expired)
                // Auto-decline expired reservations (>24h)
                $pendingRes = Reservation::where('Accession_Number', $book->Accession_Number)
                    ->where('status', 'pending')
                    ->orderBy('reserved_at')
                    ->get();
                foreach ($pendingRes as $res) {
                    if (Carbon::parse($res->reserved_at)->addHours(24)->isPast()) {
                        $res->status = 'expired';
                        $res->save();
                    }
                }
                // After expiring old ones, pick the first active pending reservation (if any)
                $activeReservation = Reservation::where('Accession_Number', $book->Accession_Number)
                    ->where('status', 'pending')
                    ->orderBy('reserved_at')
                    ->first();
            }
        }

        return view('books.index', [
            'book' => $book,
            'matches' => $matches,
            'q' => $q,
            'isIssued' => $isIssued,
            'currentIssue' => $currentIssue,
            'lateDays' => $lateDays,
            'accruedFine' => $accruedFine,
            'activeReservation' => isset($activeReservation) ? $activeReservation : null,
        ]);
    }

    public function issue(Request $request)
    {
        $data = $request->validate([
            'accession' => ['required','string'],
            'batch_no' => ['required','string'],
            'issue_date' => ['nullable','date'],
        ]);

        $normalizedAccession = preg_replace('/\D+/', '', $data['accession']);
        $book = Book::where('Accession_Number', $normalizedAccession)
            ->orWhere('Accession_Number', $data['accession'])
            ->first();
        if (!$book) {
            return back()->withErrors(['accession' => 'Book not found.'])->withInput();
        }

        $student = User::where('batch_no', $data['batch_no'])->first();
        if (!$student) {
            return back()->withErrors(['batch_no' => 'Student with this batch number was not found.'])->withInput();
        }

        $already = Issue::where('Accession_Number', $book->Accession_Number)
            ->whereNull('return_date')
            ->first();
        if ($already) {
            return back()->withErrors(['accession' => 'This book is currently issued.'])->withInput();
        }

        // Respect reservations: if there is a pending, unexpired reservation, only that student can issue
        $activeReservation = Reservation::where('Accession_Number', $book->Accession_Number)
            ->where('status', 'pending')
            ->orderBy('reserved_at')
            ->first();
        if ($activeReservation) {
            // expire if older than 24h
            if (Carbon::parse($activeReservation->reserved_at)->addHours(24)->isPast()) {
                $activeReservation->status = 'expired';
                $activeReservation->save();
            } else if ($activeReservation->user_batch_no !== $student->batch_no) {
                $expiresAt = Carbon::parse($activeReservation->reserved_at)->addHours(24);
                return back()->withErrors(['accession' => 'This book is reserved by '.$activeReservation->user_batch_no.' until '.$expiresAt->toDayDateTimeString().'.'])->withInput();
            }
        }

        $issueDate = isset($data['issue_date']) ? Carbon::parse($data['issue_date']) : Carbon::now();
        $dueDate = (clone $issueDate)->addMonthsNoOverflow(6);

        Issue::create([
            'user_batch_no' => $student->batch_no,
            'Accession_Number' => $book->Accession_Number,
            'issue_date' => $issueDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'return_date' => null,
            'fine' => 0,
        ]);

        // If issuing from an active reservation for this student, mark it issued
        if (isset($activeReservation) && $activeReservation && $activeReservation->status === 'pending' && $activeReservation->user_batch_no === $student->batch_no) {
            $activeReservation->status = 'issued';
            $activeReservation->save();
        }

        return redirect()->route('books.index', ['q' => $book->Accession_Number])
            ->with('status', 'Book issued to '.$student->student_name.' ('.$student->batch_no.').');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'Title' => ['required','string','max:255'],
            'Author' => ['nullable','string','max:255'],
            'Accession_Number' => ['required','string','max:255','unique:books,Accession_Number,'.$book->Accession_Number.',Accession_Number'],
        ]);

        // If accession changes, cascade to issues to keep FK values in sync
        if ($book->Accession_Number !== $data['Accession_Number']) {
            Issue::where('Accession_Number', $book->Accession_Number)
                ->update(['Accession_Number' => $data['Accession_Number']]);
        }

        $book->update($data);

        return redirect()->route('books.index', ['q' => $book->Accession_Number])
            ->with('status', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        // Prevent deleting if currently issued
        $hasOpenIssue = Issue::where('Accession_Number', $book->Accession_Number)
            ->whereNull('return_date')
            ->exists();
        if ($hasOpenIssue) {
            return back()->withErrors(['book' => 'Cannot delete: book is currently issued.']);
        }

        // Delete related issues history first if desired, or keep history. We'll keep history.
        $book->delete();
        return redirect()->route('books.index')->with('status', 'Book deleted successfully.');
    }
}
