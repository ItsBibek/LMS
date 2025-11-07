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
    @if ($errors->getBag('issue')->any())
     <div class="mt-3 text-sm text-rose-600">
      {{ $errors->getBag('issue')->first() }}
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
    <h3 class="text-sm font-semibold text-slate-700">Reserved</h3>
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
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Issue</button>
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
     <input id="edit-accession" type="text" name="accession" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
     <div>
      <label class="block text-sm font-medium text-slate-700">Issue Date</label>
      <input id="edit-issue-date" type="date" name="issue_date" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
     </div>
     <div>
      <label class="block text-sm font-medium text-slate-700">Due Date</label>
      <input id="edit-due-date" type="date" name="due_date" value="" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
     </div>
    </div>
   </div>
   <div class="mt-4 flex items-center gap-2">
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Save</button>
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
