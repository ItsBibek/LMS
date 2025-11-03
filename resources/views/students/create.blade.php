@extends('layouts.app')

@section('title', 'Add Student')
@section('header', 'Add Student')
@section('subheader', 'Create a new student record')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6 max-w-2xl">
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('students.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
    @csrf
   <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700">Student Name</label>
    <input type="text" name="student_name" value="{{ old('student_name') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Batch Number</label>
    <input type="text" name="batch_no" value="{{ old('batch_no') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Faculty</label>
    <input type="text" name="faculty" value="{{ old('faculty') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
   </div>
    <div>
     <label class="block text-sm font-medium text-slate-700">Email</label>
     <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
    </div>
    <div class="md:col-span-2">
     <label class="block text-sm font-medium text-slate-700">Photo (optional)</label>
     <input type="file" name="photo" accept="image/*" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
    </div>
    <div class="md:col-span-2 flex items-center gap-2 pt-2">
     <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Save</button>
     <a href="{{ route('students.manage') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Cancel</a>
    </div>
  </form>
 </div>
@endsection
