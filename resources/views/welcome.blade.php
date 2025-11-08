@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Library Management System')
@section('subheader', 'Welcome')


@section('content')
 <div class="mb-6">
  <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg">
   <div class="flex items-center gap-4">
    <img src="{{ asset('storage/background/logo.png') }}" alt="Academia Library" class="w-16 h-16 rounded-xl border-2 border-white/20" />
    <div>
     <h2 class="text-2xl font-bold">Welcome Back, Admin! 👋</h2>
     <p class="mt-1 text-blue-100">Here's what's happening in your library today.</p>
    </div>
   </div>
  </div>
 </div>

 <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Total Books</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600">📚</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $booksCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Members</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600">👥</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $membersCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Active Borrows</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-cyan-50 text-cyan-600">📄</span>
   </div>
   <p class="mt-2 text-3xl font-semibold">{{ $activeLoansCount }}</p>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
   <div class="flex items-center justify-between">
    <p class="text-sm text-slate-500">Overdue</p>
    <span class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-rose-50 text-rose-600">⏰</span>
   </div>
   <p class="mt-2 text-3xl font-semibold text-rose-600">{{ $overdueCount }}</p>
  </div>
 </section>

 <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
    <h3 class="text-sm font-semibold text-slate-700">Quick Actions</h3>
   </div>
   <div class="p-6 space-y-3">
    <a href="{{ route('books.create') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
     <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
     </div>
     <div>
      <div class="text-sm font-medium text-slate-900">Add New Book</div>
      <div class="text-xs text-slate-500">Add books to the library</div>
     </div>
    </a>
    <a href="{{ route('students.create') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
     <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
      </svg>
     </div>
     <div>
      <div class="text-sm font-medium text-slate-900">Add New Student</div>
      <div class="text-xs text-slate-500">Register a new student</div>
     </div>
    </a>
    <a href="{{ route('books.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
     <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
     </div>
     <div>
      <div class="text-sm font-medium text-slate-900">Search Books</div>
      <div class="text-xs text-slate-500">Find and manage books</div>
     </div>
    </a>
    <a href="{{ route('reservations.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
     <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
     </div>
     <div>
      <div class="text-sm font-medium text-slate-900">View Reservations</div>
      <div class="text-xs text-slate-500">Manage book reservations</div>
     </div>
    </a>
   </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
   <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
    <h3 class="text-sm font-semibold text-slate-700">Library Overview</h3>
   </div>
   <div class="p-6 space-y-4">
    <!-- @if($overdueCount > 0)
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-100">
     <div class="flex items-start gap-3">
      <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
       </svg>
      </div>
      <div class="flex-1">
       <div class="text-sm font-semibold text-rose-900">Overdue Books Alert</div>
       <div class="text-xs text-rose-700 mt-1">You have {{ $overdueCount }} overdue {{ $overdueCount === 1 ? 'book' : 'books' }} that need attention.</div>
       <a href="{{ route('books.index') }}" class="text-xs text-rose-600 font-medium hover:underline mt-2 inline-block">View Details →</a>
      </div>
     </div>
    </div>
    @endif -->
    <div class="space-y-3">
     <div class="flex items-center justify-between py-2 border-b border-slate-100">
      <span class="text-sm text-slate-600">Total Collection</span>
      <span class="text-sm font-semibold text-slate-900">{{ $booksCount }} books</span>
     </div>
     <div class="flex items-center justify-between py-2 border-b border-slate-100">
      <span class="text-sm text-slate-600">Registered Students</span>
      <span class="text-sm font-semibold text-slate-900">{{ $membersCount }} members</span>
     </div>
     <div class="flex items-center justify-between py-2 border-b border-slate-100">
      <span class="text-sm text-slate-600">Books in Circulation</span>
      <span class="text-sm font-semibold text-slate-900">{{ $activeLoansCount }} loans</span>
     </div>
     <div class="flex items-center justify-between py-2">
      <span class="text-sm text-slate-600">Available Books</span>
      <span class="text-sm font-semibold text-green-600">{{ $booksCount - $activeLoansCount }} available</span>
     </div>
    </div>
   </div>
  </div>
 </div>
 
@endsection
