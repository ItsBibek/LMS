@extends('layouts.app')

@section('title', 'Add Book')
@section('header', 'Add Book')
@section('subheader', 'Create a new catalog entry')

@section('content')
 <div class="max-w-4xl mx-auto">
  <!-- Option Selection Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
   <!-- Single Entry Card -->
   <div class="bg-white border-2 border-slate-200 rounded-xl p-6 hover:border-emerald-500 transition-all cursor-pointer group" onclick="showSingleEntry()">
    <div class="flex items-start space-x-4">
     <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
      <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
     </div>
     <div class="flex-1">
      <h3 class="text-lg font-semibold text-slate-900 mb-1">Single Entry</h3>
      <p class="text-sm text-slate-600">Add one book at a time with a simple form</p>
     </div>
    </div>
   </div>

   <!-- Bulk Import Card -->
   <div class="bg-white border-2 border-slate-200 rounded-xl p-6 hover:border-emerald-500 transition-all cursor-pointer group" onclick="window.location='{{ route('books.bulk-import') }}'">
    <div class="flex items-start space-x-4">
     <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
      <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
      </svg>
     </div>
     <div class="flex-1">
      <h3 class="text-lg font-semibold text-slate-900 mb-1">Bulk Import</h3>
      <p class="text-sm text-slate-600">Upload Excel file to add multiple books</p>
     </div>
    </div>
   </div>
  </div>

  <!-- Single Entry Form -->
  <div id="singleEntryForm" class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
   <h3 class="text-lg font-semibold text-slate-900 mb-4">Book Details</h3>
   @if ($errors->any())
    <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-lg text-sm text-rose-600">
     {{ $errors->first() }}
    </div>
   @endif
   <form method="POST" action="{{ route('books.store') }}" class="space-y-4">
    @csrf
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-1">Accession Number <span class="text-rose-500">*</span></label>
     <input type="text" name="Accession_Number" value="{{ old('Accession_Number') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" placeholder="e.g. ACC-0001" required />
    </div>
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-1">Title <span class="text-rose-500">*</span></label>
     <input type="text" name="Title" value="{{ old('Title') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" placeholder="Enter book title" required />
    </div>
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-1">Author</label>
     <input type="text" name="Author" value="{{ old('Author') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" placeholder="Enter author name" />
    </div>
    <div class="flex items-center gap-3 pt-4">
     <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
      Save Book
     </button>
     <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
      Cancel
     </a>
    </div>
   </form>
  </div>
 </div>

 <script>
  function showSingleEntry() {
   document.getElementById('singleEntryForm').scrollIntoView({ behavior: 'smooth' });
  }
 </script>
@endsection
