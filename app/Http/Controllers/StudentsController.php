<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Issue;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $batch = trim((string) $request->get('batch', ''));
        $name = trim((string) $request->get('name', ''));
        $faculty = trim((string) $request->get('faculty', ''));

        // Normalize scans like "AC-078CSIT04" -> "078CSIT04"
        $normalized = $batch !== ''
            ? preg_replace(['/^AC[-\s]*/i','/[^A-Za-z0-9]/'], ['', ''], $batch)
            : '';

        $student = null;
        $results = collect();

        // Priority: batch search
        if ($batch !== '') {
            $student = User::where('batch_no', $normalized)
                ->orWhere('batch_no', $batch)
                ->first();
            if ($student) {
                return redirect()->route('students.show', $student);
            }
        } elseif ($name !== '') {
            // Name + optional faculty filter
            $query = User::query()
                ->when($faculty !== '', function ($q) use ($faculty) {
                    $q->where('faculty', $faculty);
                })
                ->where('student_name', 'like', "%{$name}%")
                ->orderBy('student_name');

            $results = $query->get();

            if ($results->count() === 1) {
                return redirect()->route('students.show', $results->first());
            }
        }

        // Faculties for dropdown
        $faculties = User::select('faculty')
            ->whereNotNull('faculty')
            ->where('faculty', '<>', '')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty');

        return view('students.index', [
            'batch' => $batch,
            'student' => $student,
            'name' => $name,
            'faculty' => $faculty,
            'faculties' => $faculties,
            'results' => $results,
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
        $data = $request->validateWithBag('issue', [
            'accession' => ['required','string'],
            'issue_date' => ['nullable','date'],
        ]);

        $accession = preg_replace('/\D+/', '', $data['accession']);
        $issueDate = isset($data['issue_date']) ? Carbon::parse($data['issue_date']) : Carbon::now();
        $dueDate = (clone $issueDate)->addMonthsNoOverflow(6);

        // Ensure book exists
        $book = Book::where('Accession_Number', $accession)->first();
        if (!$book) {
            return back()->withErrors(['accession' => 'Book with this accession number was not found.'], 'issue')->withInput();
        }

        // Ensure not already issued and unreturned
        $already = Issue::where('Accession_Number', $accession)
            ->whereNull('return_date')
            ->first();
        if ($already) {
            return back()->withErrors(['accession' => 'This book is currently issued and not yet returned.'], 'issue')->withInput();
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

    public function updateIssue(Request $request, User $student, Issue $issue)
    {
        if ($issue->user_batch_no !== $student->batch_no) {
            abort(404);
        }

        $data = $request->validate([
            'accession' => ['required','string'],
            'issue_date' => ['required','date'],
            'due_date' => ['required','date','after_or_equal:issue_date'],
        ]);

        $accession = preg_replace('/\D+/', '', $data['accession']);

        // Ensure book exists
        $bookExists = Book::where('Accession_Number', $accession)->exists();
        if (!$bookExists) {
            return back()->withErrors(['accession' => 'Book with this accession number was not found.'])->withInput();
        }

        // Ensure not already issued to someone else (unreturned)
        $already = Issue::where('Accession_Number', $accession)
            ->whereNull('return_date')
            ->where('id', '!=', $issue->id)
            ->first();
        if ($already) {
            return back()->withErrors(['accession' => 'This book is currently issued to another member.'])->withInput();
        }

        $issue->update([
            'Accession_Number' => $accession,
            'issue_date' => Carbon::parse($data['issue_date'])->toDateString(),
            'due_date' => Carbon::parse($data['due_date'])->toDateString(),
        ]);

        return redirect()->route('students.show', $student)->with('status', 'Issue updated successfully.');
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
            'photo' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('students', 'public');
        }

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
            'photo' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if (!empty($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.manage')->with('status', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        $student->delete();
        return redirect()->route('students.manage')->with('status', 'Student deleted successfully.');
    }

    public function showBulkImport()
    {
        return view('students.bulk-import');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'Student_Name');
        $sheet->setCellValue('B1', 'Batch_Number');
        $sheet->setCellValue('C1', 'Faculty');
        $sheet->setCellValue('D1', 'Email');
        
        // Style headers
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E8F0');
        
        // Add sample data
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', '078CSIT01');
        $sheet->setCellValue('C2', 'CSIT');
        $sheet->setCellValue('D2', 'john.doe@example.com');
        
        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'students_import_template.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:2048'],
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Remove header row
            $headers = array_shift($rows);
            
            $imported = 0;
            $skipped = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and Excel is 1-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $studentName = trim($row[0] ?? '');
                $batchNo = trim($row[1] ?? '');
                $faculty = trim($row[2] ?? '');
                $email = trim($row[3] ?? '');
                
                // Validate required fields
                if (empty($studentName) || empty($batchNo)) {
                    $errors[] = "Row {$rowNumber}: Student Name and Batch Number are required";
                    $skipped++;
                    continue;
                }
                
                // Validate email format if provided
                if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row {$rowNumber}: Invalid email format";
                    $skipped++;
                    continue;
                }
                
                // Check if batch number already exists
                if (User::where('batch_no', $batchNo)->exists()) {
                    $errors[] = "Row {$rowNumber}: Batch number '{$batchNo}' already exists";
                    $skipped++;
                    continue;
                }
                
                // Check if email already exists (only if email is provided)
                if (!empty($email) && User::where('email', $email)->exists()) {
                    $errors[] = "Row {$rowNumber}: Email '{$email}' is already in use";
                    $skipped++;
                    continue;
                }
                
                // Create student (no try-catch needed as we've already validated uniqueness)
                User::create([
                    'student_name' => $studentName,
                    'batch_no' => $batchNo,
                    'faculty' => $faculty ?: null,
                    'email' => !empty($email) ? $email : null,
                ]);
                
                $imported++;
            }
            
            // Always show results, even if there are errors
            $message = 'Bulk import completed!';
            if ($imported > 0) {
                $message .= " Successfully imported {$imported} student(s).";
            }
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} row(s) due to errors.";
            }
            
            $redirect = redirect()->route('students.bulk-import')
                ->with('status', $message)
                ->with('imported', $imported)
                ->with('skipped', $skipped);
            
            if (!empty($errors)) {
                $redirect->withErrors($errors);
            }
            
            return $redirect;
                
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }
}
