@extends('layouts.app')

@section('title', 'Student Profile')
@section('header', 'Student Profile')
@section('header_actions')
 <div class="flex items-center gap-3">
  <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
   </svg>
   Back to Search
  </a>
  <a href="{{ route('students.manage') }}" class="inline-flex items-center gap-2 rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
   </svg>
   Manage Students
  </a>
  <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 shadow-sm transition-all">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
   </svg>
   Edit Profile
  </a>
 </div>
@endsection
@section('subheader', 'Complete student information and book management')

@section('content')
 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- LEFT SIDEBAR -->
  <div class="lg:col-span-1 space-y-6">
   <!-- Student Info Card -->
   <div class="bg-gradient-to-br from-blue-500 to-blue-500 rounded-xl p-6 text-white shadow-lg">
    <div class="flex flex-col items-center text-center">
     @if ($student->photo_path && Storage::disk('public')->exists($student->photo_path))
      <img src="{{ Storage::url($student->photo_path) }}" alt="Photo of {{ $student->student_name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg mb-4" />
     @else
      <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4">
       {{ strtoupper(substr($student->student_name, 0, 1)) }}
      </div>
     @endif
     <h2 class="text-xl font-bold mb-1">{{ $student->student_name }}</h2>
     <div class="flex items-center justify-center gap-2 mb-3">
      <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-sm font-medium">
       <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
       </svg>
       {{ $student->batch_no }}
      </div>
      <a href="{{ route('students.barcode-view', $student->batch_no) }}" class="inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-full px-3 py-1 text-xs font-medium transition-all" title="Generate Barcode">
       <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
       </svg>
       Barcode
      </a>
     </div>
    </div>
    <div class="mt-4 pt-4 border-t border-white/20 space-y-2">
     <div class="flex items-center justify-between text-sm">
      <span class="text-emerald-100 flex items-center">
       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
       </svg>
       Faculty
      </span>
      <span class="font-medium">{{ $student->faculty }}</span>
     </div>
     <div class="flex items-center justify-between text-sm">
      <span class="text-emerald-100 flex items-center">
       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
       </svg>
       Email
      </span>
      <span class="font-medium truncate ml-2">{{ $student->email ?: 'N/A' }}</span>
     </div>
    </div>
   </div>

   <!-- Quick Issue Book -->
   <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="flex items-center mb-4">
     <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
      </svg>
     </div>
     <h3 class="text-base font-semibold text-slate-900">Issue Book</h3>
    </div>
    @if ($errors->getBag('issue')->any())
     <div class="mb-3 p-3 bg-rose-50 border border-rose-200 rounded-lg flex items-start">
      <svg class="w-5 h-5 text-rose-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="text-sm text-rose-900">{{ $errors->getBag('issue')->first() }}</span>
     </div>
    @endif
    @if (session('status'))
     <div class="mb-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center">
      <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="text-sm text-emerald-900 font-medium">{{ session('status') }}</span>
     </div>
    @endif
    <form method="POST" action="{{ route('students.issue', $student) }}" class="space-y-4">
     @csrf
     <div>
      <label class="block text-sm font-medium text-slate-700 mb-2">Accession Number</label>
      <div class="relative">
       <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
       </div>
       <input type="text" name="accession" value="{{ old('accession') }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all" placeholder="e.g. ACC-0001" autofocus />
      </div>
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700 mb-2">Issue Date <span class="text-slate-500 text-xs">(optional)</span></label>
      <input type="date" name="issue_date" value="{{ old('issue_date') }}" class="w-full px-4 py-2.5 rounded-lg border-2 border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all" />
     </div>
     <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-3 text-white text-sm font-medium hover:bg-emerald-700 shadow-sm transition-all">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
      </svg>
      Issue Book to Student
     </button>
    </form>
   </div>
  </div>

  <!-- MAIN CONTENT AREA -->
  <div class="lg:col-span-2 space-y-6">
  <!-- Reserved Books -->
  <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
   <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
    <div class="flex items-center">
     <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
     </svg>
     <h3 class="text-base font-semibold text-slate-900">Reserved Books</h3>
    </div>
   </div>
   @php($activeReservations = $student->reservations()->where('status','pending')->latest('reserved_at')->paginate(10))
   <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
     <thead class="bg-slate-50">
      <tr>
       <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
       <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
       <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Reserved</th>
       <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Expires</th>
       <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
       <th class="px-6 py-3"></th>
      </tr>
     </thead>
     <tbody class="bg-white divide-y divide-slate-200">
      @forelse($activeReservations as $r)
       @php($expires = \Carbon\Carbon::parse($r->reserved_at)->addHours(24))
       <tr>
        <td class="px-6 py-3 text-sm">{{ $r->Accession_Number }}</td>
        <td class="px-6 py-3 text-sm">{{ optional($r->book)->Title ?? '-' }}</td>
        <td class="px-6 py-3 text-sm">{{ \Carbon\Carbon::parse($r->reserved_at)->toDayDateTimeString() }}</td>
        <td class="px-6 py-3 text-sm">{{ $expires->toDayDateTimeString() }}</td>
        <td class="px-6 py-3 text-sm">
         @if($r->is_expired)
          <span class="inline-flex items-center rounded-md bg-rose-50 text-rose-700 px-2 py-1 text-xs font-medium">Expired</span>
         @else
          <span class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 px-2 py-1 text-xs font-medium">Pending</span>
         @endif
        </td>
        <td class="px-6 py-3 text-right">
         @if(!$r->is_expired)
          <div class="inline-flex items-center gap-2">
           <form method="POST" action="{{ route('reservations.issue', $r) }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-3 py-2 text-white text-sm font-medium hover:bg-emerald-700">Issue</button>
           </form>
           <form method="POST" action="{{ route('reservations.destroy', $r) }}" class="inline" onsubmit="return confirm('Decline this reservation?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Decline</button>
           </form>
          </div>
         @else
          <form method="POST" action="{{ route('reservations.destroy', $r) }}" class="inline" onsubmit="return confirm('Delete this expired reservation?')">
           @csrf
           @method('DELETE')
           <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Delete</button>
          </form>
         @endif
        </td>
       </tr>
      @empty
       <tr>
        <td colspan="6" class="px-6 py-6 text-center text-sm text-slate-500">No active reservations.</td>
       </tr>
      @endforelse
     </tbody>
    </table>
   </div>
   @if(method_exists($activeReservations, 'links'))
    <div class="px-6 py-3 border-t border-slate-200 flex items-center justify-between">
     <div class="text-sm text-slate-600">{{ number_format($activeReservations->total()) }} results</div>
     {{ $activeReservations->links() }}
    </div>
   @endif
  </div>
   <!-- Currently Issued Books -->
   <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
     <div class="flex items-center">
      <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
      <h3 class="text-base font-semibold text-slate-900">Currently Issued Books</h3>
     </div>
     <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
      {{ $student->currentIssues->count() }} active
     </span>
    </div>
    <div class="overflow-x-auto">
     <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
       <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Issue Date</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Due Date</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Fine</th>
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
        <td class="px-6 py-3 text-sm">
         @php($today = \Carbon\Carbon::now())
         @php($due = \Carbon\Carbon::parse($issue->due_date))
         @php($lateDays = max(0, $due->diffInDays($today, false)))
         @php($fineNow = $lateDays * 2)
         @if($fineNow > 0)
          <span class="text-rose-600 font-semibold">{{ number_format($fineNow, 2) }}</span>
         @else
          <span class="text-slate-600">{{ number_format($fineNow, 2) }}</span>
         @endif
        </td>
         <td class="px-6 py-3 text-right">
         <div class="inline-flex items-center gap-2">
          <form method="POST" action="{{ route('students.return', [$student, $issue->id]) }}" onsubmit="return confirm('Return this book?')">
           @csrf
           <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Return</button>
          </form>
          <button type="button"
                  class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50 js-edit-issue"
                  data-id="{{ $issue->id }}"
                  data-accession="{{ $issue->Accession_Number }}"
                  data-issue-date="{{ $issue->issue_date }}"
                  data-due-date="{{ $issue->due_date }}">
           Edit
          </button>
         </div>
        </td>
        </tr>
       @empty
        <tr>
         <td colspan="6" class="px-6 py-6 text-center text-sm text-slate-500">No books currently issued.</td>
        </tr>
       @endforelse
      </tbody>
     </table>
    </div>
   </div>

   <!-- History -->
   <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
     <div class="flex items-center">
      <svg class="w-5 h-5 text-slate-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <h3 class="text-base font-semibold text-slate-900">Borrowing History</h3>
     </div>
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
<div id="editIssueModal" class="fixed inset-0 z-50 hidden" data-action-prefix="{{ url('/students/'.$student->batch_no.'/issues') }}/">
 <div class="absolute inset-0 bg-slate-900/50"></div>
 <div class="relative mx-auto max-w-lg bg-white rounded-xl shadow-xl p-6 mt-20">
  <div class="flex items-center justify-between mb-4">
   <h3 class="text-sm font-semibold text-slate-700">Edit Issue</h3>
   <button type="button" class="rounded-md px-3 py-1.5 text-sm border border-slate-300 hover:bg-slate-50" onclick="document.getElementById('editIssueModal').classList.add('hidden')">Close</button>
  </div>
  <form id="editIssueForm" method="POST" action="">
   @csrf
   @method('PATCH')
   <input type="hidden" name="issue_id" id="edit-issue-id" value="" />
   @if($errors->any() && old('issue_id'))
    <div class="mb-3 text-sm text-rose-600">
     {{ $errors->first() }}
    </div>
   @endif
   <div class="grid grid-cols-1 gap-3">
    <div>
     <label class="block text-sm font-medium text-slate-700">Accession No.</label>
     <input id="edit-accession" type="text" name="accession" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
     <div>
      <label class="block text-sm font-medium text-slate-700">Issue Date</label>
      <input id="edit-issue-date" type="date" name="issue_date" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700">Due Date</label>
      <input id="edit-due-date" type="date" name="due_date" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
     </div>
    </div>
   </div>
   <div class="mt-4 flex items-center gap-2">
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white text-sm font-medium hover:bg-emerald-700">Save</button>
    <button type="button" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50" onclick="document.getElementById('editIssueModal').classList.add('hidden')">Cancel</button>
   </div>
  </form>
 </div>
</div>
<script>
 (function(){
  var modal = document.getElementById('editIssueModal');
  var form = document.getElementById('editIssueForm');
  var prefix = modal ? modal.getAttribute('data-action-prefix') : '';
  var acc = document.getElementById('edit-accession');
  var issueDate = document.getElementById('edit-issue-date');
  var dueDate = document.getElementById('edit-due-date');
  var issueIdInput = document.getElementById('edit-issue-id');
  document.querySelectorAll('.js-edit-issue').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = this.getAttribute('data-id');
      var a = this.getAttribute('data-accession');
      var i = this.getAttribute('data-issue-date');
      var d = this.getAttribute('data-due-date');
      if (form && prefix) form.setAttribute('action', prefix + id);
      if (issueIdInput) issueIdInput.value = id || '';
      if (acc) acc.value = a || '';
      if (issueDate) issueDate.value = i || '';
      if (dueDate) dueDate.value = d || '';
      if (modal) modal.classList.remove('hidden');
    });
  });
  // Auto-open modal with old input when validation fails
  var oldIssueId = "{{ old('issue_id') }}";
  if (oldIssueId) {
    if (form && prefix) form.setAttribute('action', prefix + oldIssueId);
    if (issueIdInput) issueIdInput.value = oldIssueId;
    var oldAcc = "{{ old('accession') }}";
    var oldIssueDate = "{{ old('issue_date') }}";
    var oldDueDate = "{{ old('due_date') }}";
    if (acc) acc.value = oldAcc || '';
    if (issueDate) issueDate.value = oldIssueDate || '';
    if (dueDate) dueDate.value = oldDueDate || '';
    if (modal) modal.classList.remove('hidden');
  }
 })();
</script>
@endsection
