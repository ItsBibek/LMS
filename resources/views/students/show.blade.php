@extends('layouts.app')

@section('title', 'Student Profile')
@section('header', 'Student Profile')
@section('subheader', $student->student_name . ' · ' . $student->batch_no)

@section('content')
 <div class="flex items-center justify-end mb-4">
  <form method="GET" action="{{ route('students.index') }}" class="flex items-center gap-2">
   <input type="text" name="batch" placeholder="Search batch no..." class="rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
   <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Search</button>
  </form>
 </div>
 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1 space-y-6">
   <div class="bg-white border border-slate-200 rounded-xl p-6">
    <div class="flex items-start gap-4">
     @if ($student->photo_path && Storage::disk('public')->exists($student->photo_path))
      <img src="{{ Storage::url($student->photo_path) }}" alt="Photo of {{ $student->student_name }}" class="w-16 h-16 rounded-full object-cover" />
     @else
      <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xl">{{ strtoupper(substr($student->student_name, 0, 1)) }}</div>
     @endif
     <div>
      <div class="text-base font-semibold">{{ $student->student_name }}</div>
      <div class="text-sm text-slate-500">Batch: {{ $student->batch_no }}</div>
      <div class="text-sm text-slate-500">Faculty: {{ $student->faculty }}</div>
      <div class="text-sm text-slate-500 break-all">{{ $student->email }}</div>
     </div>
    </div>
   </div>

   <div class="bg-white border border-slate-200 rounded-xl p-6">
    <h3 class="text-sm font-semibold text-slate-700">Issue a Book</h3>
    @if ($errors->any())
     <div class="mt-3 text-sm text-rose-600">
      {{ $errors->first() }}
     </div>
    @endif
    @if (session('status'))
     <div class="mt-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('students.issue', $student) }}" class="mt-4 space-y-3">
     @csrf
     <div>
      <label class="block text-sm font-medium text-slate-700">Accession Number</label>
      <input type="text" name="accession" value="{{ old('accession') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. ACC-0001" />
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700">Issue Date (optional)</label>
      <input type="date" name="issue_date" value="{{ old('issue_date') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
     </div>
     <div>
      <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Issue Book</button>
     </div>
    </form>
   </div>
  </div>

  <div class="lg:col-span-2 space-y-6">
   <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
     <h3 class="text-sm font-semibold text-slate-700">Currently Issued</h3>
    </div>
    <div class="overflow-x-auto">
     <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
       <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Issue Date</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Due Date</th>
        <th class="px-6 py-3"></th>
       </tr>
      </thead>
      <tbody class="bg-white divide-y divide-slate-200">
       @forelse($student->currentIssues as $issue)
        <tr>
         <td class="px-6 py-3 text-sm"><span class="inline-flex items-center rounded-md border border-slate-300 px-2.5 py-1 text-sm bg-white">{{ $issue->Accession_Number }}</span></td>
         <td class="px-6 py-3 text-sm">{{ optional($issue->book)->Title ?? '-' }}</td>
         <td class="px-6 py-3 text-sm"><span class="inline-flex items-center rounded-md border border-slate-300 px-2.5 py-1 text-sm bg-white">{{ $issue->issue_date }}</span></td>
         <td class="px-6 py-3 text-sm">{{ $issue->due_date }}</td>
         <td class="px-6 py-3 text-right">
          <form method="POST" action="{{ route('students.return', [$student, $issue->id]) }}" onsubmit="return confirm('Return this book?')">
           @csrf
           <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Return</button>
          </form>
         </td>
        </tr>
       @empty
        <tr>
         <td colspan="5" class="px-6 py-6 text-center text-sm text-slate-500">No books currently issued.</td>
        </tr>
       @endforelse
      </tbody>
     </table>
    </div>
   </div>

   <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
     <h3 class="text-sm font-semibold text-slate-700">History</h3>
    </div>
    <div class="overflow-x-auto">
     <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
       <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Issued</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Returned</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Fine</th>
       </tr>
      </thead>
      <tbody class="bg-white divide-y divide-slate-200">
       @forelse($student->returnedIssues()->latest('return_date')->get() as $issue)
        <tr>
         <td class="px-6 py-3 text-sm">{{ $issue->Accession_Number }}</td>
         <td class="px-6 py-3 text-sm">{{ optional($issue->book)->Title ?? '-' }}</td>
         <td class="px-6 py-3 text-sm">{{ $issue->issue_date }}</td>
         <td class="px-6 py-3 text-sm">{{ $issue->return_date }}</td>
         <td class="px-6 py-3 text-sm">{{ number_format($issue->fine, 2) }}</td>
        </tr>
       @empty
        <tr>
         <td colspan="5" class="px-6 py-6 text-center text-sm text-slate-500">No history yet.</td>
        </tr>
       @endforelse
      </tbody>
     </table>
    </div>
   </div>
  </div>
 </div>
@endsection
