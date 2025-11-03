@extends('layouts.app')

@section('title', 'Add Book')
@section('header', 'Add Book')
@section('subheader', 'Create a new catalog entry')

@section('content')
 <div class="max-w-xl bg-white border border-slate-200 rounded-xl p-6">
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('books.store') }}" class="space-y-4">
   @csrf
   <div>
    <label class="block text-sm font-medium text-slate-700">Accession Number</label>
    <input type="text" name="Accession_Number" value="{{ old('Accession_Number') }}" class="mt-1 w-full rounded-md border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. ACC-0001" />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Title</label>
    <input type="text" name="Title" value="{{ old('Title') }}" class="mt-1 w-full rounded-md border border -slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Author</label>
    <input type="text" name="Author" value="{{ old('Author') }}" class="mt-1 w-full rounded-md border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
   </div>
   <div class="pt-2">
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Save</button>
    <a href="{{ route('books.index') }}" class="ml-2 inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Cancel</a>
   </div>
  </form>
 </div>
@endsection
