@extends('layouts.app')

@section('title', 'Books')
@section('header', 'Books')
@section('header_actions')
 <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Book</a>
@endsection
@section('subheader', 'Search by accession number')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-4 md:p-6">
  <form method="GET" action="{{ route('books.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
   <div class="md:col-span-5">
    <input type="text" name="q" value="{{ $q }}" placeholder="Enter accession number"
           autofocus autocomplete="off" inputmode="numeric"
           class="w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
   </div>
   <div class="md:col-span-1 flex gap-2">
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700 w-full">Search</button>
    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50 w-full">Reset</a>
   </div>
  </form>



  @if ($errors->any())
   <div class="mt-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  @if (session('status'))
   <div class="mt-4 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif

  <div class="mt-6">
   @if($q === '')
    <div class="text-center text-slate-500 text-sm py-8">Type the exact accession number and press Search.</div>
   @endif

   @if(isset($book) && $book)
    <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-xl overflow-hidden">
     <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <div>
       <div class="text-xs uppercase tracking-wide text-slate-500">Accession No.</div>
       <div class="text-lg font-semibold text-slate-800">{{ $book->Accession_Number }}</div>
      </div>
      <div class="flex items-center gap-2">
       @if(!empty($isIssued) && $isIssued)
        <span class="inline-flex items-center rounded-md bg-rose-50 text-rose-700 px-2 py-1 text-xs font-medium">Issued</span>
       @else
        <span class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1 text-xs font-medium">Available</span>
       @endif
       <a href="{{ route('books.edit', $book->Accession_Number) }}" class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium hover:bg-slate-50">Edit</a>
       <form method="POST" action="{{ route('books.destroy', $book->Accession_Number) }}" onsubmit="return confirm('Delete this book? This will not remove past issue history. Continue?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1.5 text-white text-xs font-medium hover:bg-rose-700">Delete</button>
       </form>
      </div>
     </div>
     <div class="divide-y divide-slate-200">
      <div class="flex items-start px-6 py-4">
       <div class="w-40 text-sm text-slate-500">Title</div>
       <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Title ?? '-' }}</div>
      </div>
      <div class="flex items-start px-6 py-4">
       <div class="w-40 text-sm text-slate-500">Author</div>
       <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Author ?? '-' }}</div>
      </div>
     </div>

     @if(!empty($isIssued) && $isIssued && isset($currentIssue) && $currentIssue)
      <div class="divide-y divide-slate-200">
       <div class="flex items-start px-6 py-4 bg-amber-50">
        <div class="w-40 text-sm font-semibold text-amber-800">Issued To</div>
        <div class="flex-1 text-sm text-amber-900">{{ $currentIssue->user->student_name ?? '-' }} ({{ $currentIssue->user->batch_no ?? '-' }})</div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Issue Date</div>
        <div class="flex-1">
         <span class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-800 bg-white">{{ $currentIssue->issue_date ?? '-' }}</span>
        </div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Due Date</div>
        <div class="flex-1 text-sm font-medium text-slate-800">{{ $currentIssue->due_date ?? '-' }}</div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Late Days</div>
        <div class="flex-1 text-sm font-medium text-slate-800">{{ $lateDays }}</div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Accrued Fine</div>
        <div class="flex-1 text-sm font-medium text-slate-800">Rs. {{ $accruedFine }}</div>
       </div>
      </div>
     @endif

     @if(empty($isIssued) || !$isIssued)
      <div class="px-6 py-5 border-t border-slate-200">
       <h3 class="text-sm font-semibold text-slate-700">Issue this Book</h3>
       <form method="POST" action="{{ route('books.issue') }}" class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
        @csrf
         <input type="hidden" name="accession" value="{{ $book->Accession_Number }}" />
         <div class="md:col-span-1">
          <label class="block text-sm font-medium text-slate-700">Batch Number</label>
          <input type="text" name="batch_no" value="{{ old('batch_no') }}" placeholder="e.g. BATCH-2025-001" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
         </div>
         <div class="md:col-span-1">
          <label class="block text-sm font-medium text-slate-700">Issue Date (optional)</label>
          <input type="date" name="issue_date" value="{{ old('issue_date') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
         </div>
         <div class="md:col-span-1 flex items-end">
          <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700 w-full">Issue Book</button>
         </div>
        </form>
       </div>
      @endif
    </div>
   @else
    @if($q !== '')
     <div class="text-center text-slate-500 text-sm py-8">No books found for accession "{{ $q }}".</div>
    @endif
   @endif
  </div>

 </div>
@endsection
