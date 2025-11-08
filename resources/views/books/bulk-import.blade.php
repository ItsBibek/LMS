@extends('layouts.app')

@section('title', 'Bulk Import Books')
@section('header', 'Bulk Import Books')
@section('subheader', 'Upload Excel file to import multiple books')

@section('content')
 <div class="max-w-4xl mx-auto">
  <!-- Back to Options -->
  <div class="mb-6">
   <a href="{{ route('books.create') }}" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    Back to options
   </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
   <!-- Upload Section -->
   <div class="lg:col-span-2">
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
     <h3 class="text-lg font-semibold text-slate-900 mb-4">Upload Excel File</h3>
     
     @if (session('status'))
      <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
       <div class="flex items-start">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
         <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
         @if (session('imported'))
          <p class="text-xs text-emerald-600 mt-1">Successfully imported {{ session('imported') }} books</p>
         @endif
         @if (session('skipped'))
          <p class="text-xs text-amber-600 mt-1">Skipped {{ session('skipped') }} duplicate entries</p>
         @endif
        </div>
       </div>
      </div>
     @endif

     @if ($errors->any())
      <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-lg">
       <div class="flex items-start">
        <svg class="w-5 h-5 text-rose-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="flex-1">
         <p class="text-sm font-medium text-rose-800 mb-2">There were errors with your upload:</p>
         <ul class="text-xs text-rose-600 space-y-1 list-disc list-inside">
          @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
          @endforeach
         </ul>
        </div>
       </div>
      </div>
     @endif

     <form method="POST" action="{{ route('books.bulk-import.store') }}" enctype="multipart/form-data" class="space-y-4">
      @csrf
      
      <div>
       <label class="block text-sm font-medium text-slate-700 mb-2">Excel File <span class="text-rose-500">*</span></label>
       <div class="relative">
        <input type="file" name="file" accept=".xlsx,.xls" required
         class="w-full px-3 py-2 rounded-lg border-2 border-dashed border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors" />
       </div>
       <p class="mt-2 text-xs text-slate-500">Accepted formats: .xlsx, .xls (Max size: 2MB)</p>
      </div>

      <div class="flex items-center gap-3 pt-4">
       <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Import Books
       </button>
       <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
        Cancel
       </a>
      </div>
     </form>
    </div>
   </div>

   <!-- Instructions Section -->
   <div class="lg:col-span-1">
    <div class="bg-gradient-to-br from-emerald-50 to-blue-50 border border-emerald-100 rounded-xl p-6 shadow-sm sticky top-6">
     <h4 class="text-sm font-semibold text-emerald-900 mb-3">📋 Instructions</h4>
     <ol class="text-xs text-emerald-800 space-y-2">
      <li class="flex items-start">
       <span class="font-semibold mr-2 text-emerald-600">1.</span>
       <span>Download the template file below</span>
      </li>
      <li class="flex items-start">
       <span class="font-semibold mr-2 text-emerald-600">2.</span>
       <span>Fill in the book details in the Excel file</span>
      </li>
      <li class="flex items-start">
       <span class="font-semibold mr-2 text-emerald-600">3.</span>
       <span>Upload the completed file</span>
      </li>
      <li class="flex items-start">
       <span class="font-semibold mr-2 text-emerald-600">4.</span>
       <span>Review the import summary</span>
      </li>
     </ol>

     <div class="mt-4 pt-4 border-t border-emerald-200">
      <a href="{{ route('books.download-template') }}" class="flex items-center justify-center w-full px-4 py-2.5 bg-white border border-emerald-300 text-emerald-700 rounded-lg text-sm font-medium hover:bg-emerald-50 transition-colors">
       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
       </svg>
       Download Template
      </a>
     </div>

     <div class="mt-4 pt-4 border-t border-emerald-200">
      <h5 class="text-xs font-semibold text-emerald-900 mb-2">Required Fields:</h5>
      <ul class="text-xs text-emerald-700 space-y-1">
       <li class="flex items-center">
        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
        Accession Number (unique)
       </li>
       <li class="flex items-center">
        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
        Title
       </li>
      </ul>
      <h5 class="text-xs font-semibold text-emerald-900 mt-3 mb-2">Optional Fields:</h5>
      <ul class="text-xs text-emerald-700 space-y-1">
       <li class="flex items-center">
        <span class="w-1.5 h-1.5 bg-emerald-300 rounded-full mr-2"></span>
        Author
       </li>
      </ul>
     </div>
    </div>
   </div>
  </div>
 </div>
@endsection
