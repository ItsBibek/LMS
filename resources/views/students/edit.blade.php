@extends('layouts.app')

@section('title', 'Edit Student')
@section('header', 'Edit Student')
@section('subheader', 'Update student details')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6 max-w-2xl">
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('students.update', $student) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
   @csrf
   @method('PATCH')
   <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700">Student Name</label>
    <input type="text" name="student_name" value="{{ old('student_name', $student->student_name) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Batch Number</label>
    <input type="text" name="batch_no" value="{{ old('batch_no', $student->batch_no) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Faculty (optional)</label>
    <input type="text" name="faculty" value="{{ old('faculty', $student->faculty) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Email (optional)</label>
    <input type="email" name="email" value="{{ old('email', $student->email) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
   </div>
   <div class="md:col-span-2 flex items-center gap-2 pt-2">
    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Update</button>
    <a href="{{ route('students.manage') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Cancel</a>
   </div>
  </form>
 </div>
@endsection
