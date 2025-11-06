@extends('layouts.app')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Search Students')

@section('content')
 <div class="grid gap-6 lg:grid-cols-2">
  <div class="bg-white border border-slate-200 rounded-xl p-6">
   <div class="text-sm font-medium text-slate-700 mb-3">Search by Batch Number</div>
   <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
    <div>
     <label class="block text-sm font-medium text-slate-700">Batch Number</label>
     <input type="text" name="batch" value="{{ old('batch', $batch ?? '') }}" placeholder="Scan or enter batch number" autofocus autocomplete="off" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
    </div>
    <div class="flex items-center gap-2">
     <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Search</button>
     <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Reset</a>
    </div>
   </form>
   @if(($batch ?? '') !== '' && empty($student))
    <div class="mt-4 text-sm text-slate-500">No student found for batch "{{ $batch }}".</div>
   @endif
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-6">
   <div class="text-sm font-medium text-slate-700 mb-3">Search by Name and Faculty</div>
   <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
    <div>
     <label class="block text-sm font-medium text-slate-700">Student Name</label>
     <input type="text" name="name" value="{{ old('name', $name ?? '') }}" placeholder="Enter student name" autocomplete="off" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
    </div>
    <div>
     <label class="block text-sm font-medium text-slate-700">Faculty</label>
     <select name="faculty" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      <option value="">All Faculties</option>
      @foreach(($faculties ?? collect()) as $fac)
       <option value="{{ $fac }}" {{ ($faculty ?? '') === $fac ? 'selected' : '' }}>{{ $fac }}</option>
      @endforeach
     </select>
    </div>
    <div class="flex items-center gap-2">
     <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Search</button>
     <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Reset</a>
    </div>
   </form>

   @if(($name ?? '') !== '')
    @if(($results ?? collect())->count() > 0)
     <div class="mt-4">
      <div class="text-sm text-slate-600 mb-2">Found {{ $results->count() }} student(s):</div>
      <ul class="divide-y divide-slate-200">
       @foreach($results as $s)
        <li class="py-2 flex items-center justify-between">
         <div>
          <div class="text-sm font-medium text-slate-800">{{ $s->student_name }}</div>
          <div class="text-xs text-slate-500">Batch: {{ $s->batch_no }} · Faculty: {{ $s->faculty }}</div>
         </div>
         <a href="{{ route('students.show', $s) }}" class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium hover:bg-slate-50">View Profile</a>
        </li>
       @endforeach
      </ul>
     </div>
    @else
     <div class="mt-4 text-sm text-slate-500">No students found matching "{{ $name }}"{{ ($faculty ?? '') !== '' ? ' in ' . $faculty : '' }}.</div>
    @endif
   @endif
  </div>
 </div>
@endsection
