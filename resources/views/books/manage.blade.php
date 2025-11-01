@extends('layouts.app')

@section('title', 'Manage Books')
@section('header', 'Manage Books')
@section('subheader', 'Edit or delete books')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6">
  @if (session('status'))
   <div class="mb-4 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  <div class="flex items-center justify-between mb-4">
   <div class="text-sm text-slate-600">Total: {{ $books->count() }}</div>
   <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Add Book</a>
  </div>
  <div class="overflow-x-auto">
   <table class="min-w-full divide-y divide-slate-200">
    <thead class="bg-slate-50">
     <tr>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession No</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Author</th>
      <th class="px-6 py-3"></th>
     </tr>
    </thead>
    <tbody class="bg-white divide-y divide-slate-200">
     @forelse($books as $book)
      <tr>
       <td class="px-6 py-3 text-sm">{{ $book->Accession_Number }}</td>
       <td class="px-6 py-3 text-sm">{{ $book->Title }}</td>
       <td class="px-6 py-3 text-sm">{{ $book->Author }}</td>
       <td class="px-6 py-3 text-right">
        <div class="inline-flex items-center gap-2">
         <a href="{{ route('books.edit', $book->Accession_Number) }}" class="inline-flex items-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Edit</a>
         <form method="POST" action="{{ route('books.destroy', $book->Accession_Number) }}" onsubmit="return confirm('Delete this book? This will not remove past issue history. Continue?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-2 text-white text-sm font-medium hover:bg-rose-700">Delete</button>
         </form>
        </div>
       </td>
      </tr>
     @empty
      <tr>
       <td colspan="4" class="px-6 py-6 text-center text-sm text-slate-500">No books yet.</td>
      </tr>
     @endforelse
    </tbody>
   </table>
  </div>
 </div>
@endsection
