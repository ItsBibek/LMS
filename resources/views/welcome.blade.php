@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Library Management System')
@section('subheader', 'Welcome')

@section('header_actions')
 <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Book</a>
@endsection

@section('content')
 <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Total Books</p>
    <span class="text-indigo-600">📚</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $booksCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Members</p>
    <span class="text-indigo-600">👥</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $membersCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Active Loans</p>
    <span class="text-indigo-600">📄</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $activeLoansCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Overdue</p>
    <span class="text-indigo-600">⏰</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $overdueCount }}</p>
  </div>
 </section>
 
@endsection
