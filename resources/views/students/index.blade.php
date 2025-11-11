@extends('layouts.app')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Search and find student information')

@section('content')
 <div class="grid gap-6 lg:grid-cols-2">
  <!-- Batch Number Search -->
  <div class="bg-gradient-to-br from-white to-emerald-50 border border-slate-200 rounded-xl p-6 shadow-sm">
   <div class="flex items-center mb-5">
    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
     <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
     </svg>
    </div>
    <div>
     <h3 class="text-base font-semibold text-slate-900">Quick Search</h3>
     <p class="text-xs text-slate-600">Search by batch number</p>
    </div>
   </div>
   
   <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-2">Batch Number</label>
     <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
       <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
       </svg>
      </div>
      <input type="text" name="batch" value="{{ old('batch', $batch ?? '') }}" placeholder="e.g., 078CSIT01" autofocus autocomplete="off" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all placeholder-slate-400" />
     </div>
     <p class="mt-1.5 text-xs text-slate-500">Scan card or type batch number manually</p>
    </div>
    <div class="flex items-center gap-3">
     <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-all">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      Search
     </button>
     <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-4 py-2.5 text-sm font-medium hover:bg-white transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
     </a>
    </div>
   </form>
   
   @if(($batch ?? '') !== '' && empty($student))
    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start">
     <svg class="w-5 h-5 text-amber-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
     </svg>
     <div>
      <p class="text-sm font-medium text-amber-900">No student found</p>
      <p class="text-xs text-amber-700 mt-0.5">Batch "{{ $batch }}" doesn't exist in the system</p>
     </div>
    </div>
   @endif
  </div>

  <!-- Name & Faculty Search -->
  <div class="bg-gradient-to-br from-white to-emerald-50 border border-slate-200 rounded-xl p-6 shadow-sm">
   <div class="flex items-center mb-5">
    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
     <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
     </svg>
    </div>
    <div>
     <h3 class="text-base font-semibold text-slate-900">Advanced Search</h3>
     <p class="text-xs text-slate-600">Search by name and faculty</p>
    </div>
   </div>
   
   <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-2">Student Name</label>
     <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
       <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
       </svg>
      </div>
      <input type="text" name="name" value="{{ old('name', $name ?? '') }}" placeholder="Enter student name..." autocomplete="off" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all placeholder-slate-400" />
     </div>
    </div>
    <div>
     <label class="block text-sm font-medium text-slate-700 mb-2">Faculty (Optional)</label>
     <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
       <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
       </svg>
      </div>
      <select name="faculty" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all appearance-none bg-white">
       <option value="">All Faculties</option>
       @foreach(($faculties ?? collect()) as $fac)
        <option value="{{ $fac }}" {{ ($faculty ?? '') === $fac ? 'selected' : '' }}>{{ $fac }}</option>
       @endforeach
      </select>
     </div>
    </div>
    <div class="flex items-center gap-3">
     <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-white text-sm font-medium hover:bg-emerald-700 shadow-sm transition-all">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      Search
     </button>
     <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-4 py-2.5 text-sm font-medium hover:bg-white transition-all">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
     </a>
    </div>
   </form>

   @if(($name ?? '') !== '')
    @if(($results ?? collect())->count() > 0)
     <div class="mt-5 bg-white border border-emerald-200 rounded-lg overflow-hidden">
      <div class="px-4 py-2.5 bg-emerald-50 border-b border-emerald-200">
       <div class="flex items-center text-sm font-medium text-emerald-900">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Found {{ $results->count() }} student{{ $results->count() === 1 ? '' : 's' }}
       </div>
      </div>
      <ul class="divide-y divide-slate-100">
       @foreach($results as $s)
        <li class="px-4 py-3 hover:bg-emerald-50 transition-colors group">
         <div class="flex items-center justify-between">
          <div class="flex-1">
           <div class="text-sm font-medium text-slate-900 group-hover:text-emerald-700 transition-colors">{{ $s->student_name }}</div>
           <div class="flex items-center text-xs text-slate-500 space-x-2 mt-1">
            <span class="flex items-center">
             <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
             </svg>
             {{ $s->batch_no }}
            </span>
            <span class="text-slate-300">·</span>
            <span class="flex items-center">
             <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
             </svg>
             {{ $s->faculty }}
            </span>
           </div>
          </div>
          <div class="flex items-center gap-2">
           <a href="{{ route('students.barcode-view', $s->batch_no) }}" class="inline-flex items-center rounded-lg border-2 border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100 hover:border-blue-300 transition-all" title="Generate Barcode">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            Barcode
           </a>
           <a href="{{ route('students.show', $s) }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-3 py-1.5 text-xs font-medium hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            View Profile
           </a>
          </div>
         </div>
        </li>
       @endforeach
      </ul>
     </div>
    @else
     <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start">
      <svg class="w-5 h-5 text-amber-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
      </svg>
      <div>
       <p class="text-sm font-medium text-amber-900">No students found</p>
       <p class="text-xs text-amber-700 mt-0.5">Try different search criteria{{ ($faculty ?? '') !== '' ? ' or remove faculty filter' : '' }}</p>
      </div>
     </div>
    @endif
   @endif
  </div>
 </div>
@endsection
