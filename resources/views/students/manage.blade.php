@extends('layouts.app')

@section('title', 'Manage Students')
@section('header', 'Manage Students')
@section('subheader', 'View, edit, and organize your student database')

@section('content')
 <!-- Header Section -->
 <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-6 text-white shadow-lg mb-6">
  <div class="flex items-center justify-between">
   <div>
    <h2 class="text-2xl font-bold mb-2">Student Database</h2>
    <p class="text-emerald-100 text-sm">{{ $totalCount }} total students across all faculties</p>
   </div>
   <a href="{{ route('students.create') }}" class="inline-flex items-center rounded-lg bg-white px-5 py-3 text-emerald-600 text-sm font-medium hover:bg-emerald-50 shadow-lg transition-all">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
    </svg>
    Add Student
   </a>
  </div>
 </div>

 @if (session('status'))
  <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center">
   <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
   </svg>
   <span class="text-sm font-medium text-emerald-900">{{ session('status') }}</span>
  </div>
 @endif

 <!-- Faculty Filter Section -->
 <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm mb-6">
  <div class="flex items-center mb-4">
   <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
   </svg>
   <h3 class="text-base font-semibold text-slate-900">Filter by Faculty</h3>
   @if(!empty($activeFaculty))
    <span class="ml-3 inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
     {{ $students->count() }} student{{ $students->count() === 1 ? '' : 's' }}
    </span>
   @endif
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
   @foreach(($faculties ?? collect()) as $fac)
    @if(($fac ?? '') !== '')
     <a href="{{ route('students.manage', ['faculty' => $fac]) }}" 
        class="group relative block rounded-xl border-2 {{ ($activeFaculty ?? '') === $fac ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white hover:border-emerald-300' }} p-4 transition-all">
      <div class="flex items-center justify-between mb-2">
       <div class="text-sm font-semibold text-slate-900 {{ ($activeFaculty ?? '') === $fac ? 'text-emerald-900' : '' }}">{{ $fac }}</div>
       @if(($activeFaculty ?? '') === $fac)
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
       @endif
      </div>
      <div class="flex items-center text-xs {{ ($activeFaculty ?? '') === $fac ? 'text-emerald-600' : 'text-slate-500' }}">
       <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
       </svg>
       <span class="font-medium">{{ ($counts[$fac] ?? 0) }}</span>
      </div>
     </a>
    @endif
   @endforeach
  </div>
  
  @if(empty($activeFaculty))
   <div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-lg text-center">
    <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <p class="text-sm text-slate-600 font-medium">Select a faculty above to view students</p>
   </div>
  @endif
 </div>

  @if(!empty($activeFaculty))
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
   <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
    <div class="flex items-center justify-between">
     <div class="flex items-center">
      <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <h3 class="text-base font-semibold text-slate-900">{{ $activeFaculty }} Students</h3>
     </div>
     <span class="text-xs text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">{{ $students->count() }} total</span>
    </div>
   </div>
   
   <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200">
     <thead class="bg-slate-50">
      <tr>
       <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
        <div class="flex items-center">
         <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
         </svg>
         Name
        </div>
       </th>
       <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
        <div class="flex items-center">
         <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
         </svg>
         Batch No
        </div>
       </th>
       <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
        <div class="flex items-center">
         <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
         </svg>
         Faculty
        </div>
       </th>
       <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
        <div class="flex items-center">
         <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
         </svg>
         Email
        </div>
       </th>
       <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
      </tr>
     </thead>
     <tbody class="bg-white divide-y divide-slate-100">
      @forelse($students as $student)
       <tr class="hover:bg-slate-50 transition-colors group">
        <td class="px-6 py-4">
         <div class="flex items-center">
          <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center mr-3 flex-shrink-0">
           <span class="text-sm font-medium text-emerald-700">{{ substr($student->student_name, 0, 1) }}</span>
          </div>
          <div class="text-sm font-medium text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $student->student_name }}</div>
         </div>
        </td>
        <td class="px-6 py-4">
         <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-xs font-medium text-slate-700">
          {{ $student->batch_no }}
         </span>
        </td>
        <td class="px-6 py-4 text-sm text-slate-600">{{ $student->faculty }}</td>
        <td class="px-6 py-4">
         @if($student->email)
          <a href="mailto:{{ $student->email }}" class="text-sm text-emerald-600 hover:text-emerald-800 hover:underline">{{ $student->email }}</a>
         @else
          <span class="text-sm text-slate-400 italic">No email</span>
         @endif
        </td>
        <td class="px-6 py-4 text-right">
         <div class="inline-flex items-center gap-2">
          <a href="{{ route('students.show', $student) }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all" title="View Profile">
           <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
           </svg>
          </a>
          <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all" title="Edit">
           <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
           </svg>
          </a>
          <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete {{ $student->student_name }}? This action cannot be undone.')" class="inline">
           @csrf
           @method('DELETE')
           <button type="submit" class="inline-flex items-center rounded-lg border-2 border-rose-200 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50 hover:border-rose-300 transition-all" title="Delete">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
           </button>
          </form>
         </div>
        </td>
       </tr>
      @empty
       <tr>
        <td colspan="5" class="px-6 py-12 text-center">
         <div class="flex flex-col items-center">
          <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
           <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
           </svg>
          </div>
          <p class="text-sm font-medium text-slate-900 mb-1">No students in this faculty yet</p>
          <p class="text-xs text-slate-500">Add your first student to get started</p>
         </div>
        </td>
       </tr>
      @endforelse
     </tbody>
    </table>
   </div>
  </div>
  @endif
@endsection
