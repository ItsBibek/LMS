<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Issue;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $batch = trim((string) $request->get('batch', ''));
        // Normalize scans like "AC-078CSIT04" -> "078CSIT04"
        $normalized = $batch !== ''
            ? preg_replace(['/^AC[-\s]*/i','/[^A-Za-z0-9]/'], ['', ''], $batch)
            : '';
        $student = null;
        if ($batch !== '') {
            $student = User::where('batch_no', $normalized)
                ->orWhere('batch_no', $batch)
                ->first();
            if ($student) {
                return redirect()->route('students.show', $student);
            }
        }
        return view('students.index', [
            'batch' => $batch,
            'student' => $student,
        ]);
    }

    public function show(User $student)
    {
        $student->load([
            'currentIssues.book',
            'returnedIssues.book',
        ]);
        return view('students.show', [
            'student' => $student,
        ]);
    }

    public function issue(Request $request, User $student)
    {
        $data = $request->validate([
            'accession' => ['required','string'],
            'issue_date' => ['nullable','date'],
        ]);

        $accession = preg_replace('/\D+/', '', $data['accession']);
        $issueDate = isset($data['issue_date']) ? Carbon::parse($data['issue_date']) : Carbon::now();
        $dueDate = (clone $issueDate)->addMonthsNoOverflow(6);

        // Ensure book exists
        $book = Book::where('Accession_Number', $accession)->first();
        if (!$book) {
            return back()->withErrors(['accession' => 'Book with this accession number was not found.'])->withInput();
        }

        // Ensure not already issued and unreturned
        $already = Issue::where('Accession_Number', $accession)
            ->whereNull('return_date')
            ->first();
        if ($already) {
            return back()->withErrors(['accession' => 'This book is currently issued and not yet returned.'])->withInput();
        }

        Issue::create([
            'user_batch_no' => $student->batch_no,
            'Accession_Number' => $accession,
            'issue_date' => $issueDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'return_date' => null,
            'fine' => 0,
        ]);

        return redirect()->route('students.show', $student)->with('status', 'Book issued successfully.');
    }

    public function returnBook(Request $request, User $student, Issue $issue)
    {
        if ($issue->user_batch_no !== $student->batch_no) {
            abort(404);
        }

        if ($issue->return_date === null) {
            $today = Carbon::now();
            $due = Carbon::parse($issue->due_date);
            $lateDays = max(0, $due->diffInDays($today, false));
            $finePerDay = 2; // Rs. 2 per day
            $fine = $lateDays > 0 ? $lateDays * $finePerDay : 0;

            $issue->update([
                'return_date' => $today->toDateString(),
                'fine' => $fine,
            ]);
        }

        return redirect()->route('students.show', $student)->with('status', 'Book returned successfully.');
    }

    public function manage()
    {
        $activeFaculty = trim((string) request('faculty', ''));
        $students = User::when($activeFaculty !== '', function ($q) use ($activeFaculty) {
                $q->where('faculty', $activeFaculty);
            })
            ->orderBy('student_name')
            ->get();

        $faculties = User::select('faculty')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty');

        $counts = DB::table('users')
            ->select('faculty', DB::raw('COUNT(*) as total'))
            ->groupBy('faculty')
            ->pluck('total', 'faculty');

        return view('students.manage', [
            'students' => $students,
            'faculties' => $faculties,
            'activeFaculty' => $activeFaculty,
            'counts' => $counts,
            'totalCount' => User::count(),
        ]);
    }

    public function createStudent()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_name' => ['required','string','max:255'],
            'batch_no' => ['required','string','max:255','unique:users,batch_no'],
            'email' => ['nullable','email','max:255'],
            'faculty' => ['nullable','string','max:255'],
        ]);

        $student = User::create($data);

        return redirect()->route('students.manage')->with('status', 'Student added successfully.');
    }

    public function edit(User $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $data = $request->validate([
            'student_name' => ['required','string','max:255'],
            'batch_no' => ['required','string','max:255', Rule::unique('users','batch_no')->ignore($student->batch_no, 'batch_no')],
            'email' => ['nullable','email','max:255'],
            'faculty' => ['nullable','string','max:255'],
        ]);

        $student->update($data);

        return redirect()->route('students.manage')->with('status', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        $student->delete();
        return redirect()->route('students.manage')->with('status', 'Student deleted successfully.');
    }
}
