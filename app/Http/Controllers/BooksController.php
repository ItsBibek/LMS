<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Issue;
use App\Models\User;
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
        $isIssued = false;
        $currentIssue = null;
        $lateDays = 0;
        $accruedFine = 0;
        if ($q !== '') {
            $book = Book::query()
                ->where('Accession_Number', $normalized)
                ->orWhere('Accession_Number', $q)
                ->first();

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
            }
        }

        return view('books.index', [
            'book' => $book,
            'q' => $q,
            'isIssued' => $isIssued,
            'currentIssue' => $currentIssue,
            'lateDays' => $lateDays,
            'accruedFine' => $accruedFine,
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

        $issueDate = isset($data['issue_date']) ? Carbon::parse($data['issue_date']) : Carbon::now();
        $dueDate = (clone $issueDate)->addMonthsNoOverflow(6);

        Issue::create([
            'user_id' => $student->id,
            'Accession_Number' => $book->Accession_Number,
            'issue_date' => $issueDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'return_date' => null,
            'fine' => 0,
        ]);

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
