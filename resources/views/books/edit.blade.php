@extends('layouts.app')

@section('title', 'Edit Book')
@section('header', 'Edit Book')
@section('subheader', 'Update book details')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6 max-w-2xl">
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  <form method="POST" action="{{ route('books.update', $book->Accession_Number) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
   @csrf
   @method('PATCH')
   <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700">Accession Number</label>
    <input type="text" name="Accession_Number" value="{{ old('Accession_Number', $book->Accession_Number) }}" class="mt-1 w-full rounded-md border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required />
   </div>
   <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700">Title</label>
    <input type="text" name="Title" value="{{ old('Title', $book->Title) }}" class="mt-1 w-full rounded-md border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required />
   </div>
   <div class="md:col-span-2">
    <label class="block text-sm font-medium text-slate-700">Author</label>
    <input type="text" name="Author" value="{{ old('Author', $book->Author) }}" class="mt-1 w-full rounded-md border border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" />
   </div>
   <div class="md:col-span-2 flex items-center gap-2 pt-2">
    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700">Update</button>
    <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Cancel</a>
   </div>
  </form>
 </div>
@endsection
