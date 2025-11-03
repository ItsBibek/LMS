@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Library Management System')
@section('subheader', 'Welcome')

@section('header_actions')
 <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add Book</a>
@endsection

@section('content')
 <div class="mb-8">
  <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-r from-indigo-50 via-white to-sky-50">
   <div class="px-6 py-6 sm:px-8 sm:py-8 flex items-start sm:items-center gap-5">
    <img src="{{ asset('images/academia-logo.png') }}" alt="Academia International College" class="w-14 h-14 rounded-xl object-cover ring-1 ring-slate-200" />
    <div class="flex-1">
     <h2 class="text-lg sm:text-xl font-semibold text-slate-800">Welcome to Academia International College Library</h2>
     <p class="mt-1 text-sm text-slate-600">Search books, manage students, and track issues with a clean, fast interface.</p>
     <div class="mt-4 flex flex-wrap gap-2">
      <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-white/60 bg-white">Book Search</a>
      <a href="{{ route('students.index') }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-white/60 bg-white">Student Search</a>
      <a href="{{ route('students.manage') }}" class="inline-flex items-center rounded-md bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700">Manage Students</a>
     </div>
    </div>
   </div>
  </div>
 </div>

 <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Total Books</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-50 text-indigo-600">📚</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $booksCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Members</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-50 text-indigo-600">👥</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $membersCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Active Loans</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-50 text-indigo-600">📄</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $activeLoansCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Overdue</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-indigo-50 text-indigo-600">⏰</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $overdueCount }}</p>
  </div>
 </section>
 
@endsection
