@extends('layouts.app')

@section('title', 'Students')
@section('header', 'Students')
@section('subheader', 'Search by Batch Number')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6 max-w-xl">
  <form method="GET" action="{{ route('students.index') }}" class="space-y-4">
   <div>
    <label class="block text-sm font-medium text-slate-700">Batch Number</label>
    <input type="text" name="batch" value="{{ old('batch', $batch ?? '') }}" placeholder="Scan or enter batch number" autofocus autocomplete="off" class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
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
@endsection
