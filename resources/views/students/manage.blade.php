@extends('layouts.app')

@section('title', 'Manage Students')
@section('header', 'Manage Students')
@section('subheader', 'Add, edit, or delete students')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl p-6">
  @if (session('status'))
   <div class="mb-4 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif
  <div class="flex items-center justify-between mb-4">
   <div class="text-sm text-slate-600">
    @if(!empty($activeFaculty))
     Showing: <span class="font-medium">{{ $activeFaculty }}</span> · {{ $students->count() }}
    @else
     Select a faculty to view students
    @endif
   </div>
   <a href="{{ route('students.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Add Student</a>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-6">
   @foreach(($faculties ?? collect()) as $fac)
    @if(($fac ?? '') !== '')
     <a href="{{ route('students.manage', ['faculty' => $fac]) }}" class="block rounded-lg border {{ ($activeFaculty ?? '') === $fac ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-200' }} p-4 hover:border-slate-300">
      <div class="text-sm font-semibold text-slate-800">{{ $fac }}</div>
      <div class="text-xs text-slate-500">{{ ($counts[$fac] ?? 0) }} students</div>
     </a>
    @endif
   @endforeach
  </div>

  @if(!empty($activeFaculty))
  <div class="overflow-x-auto">
   <table class="min-w-full divide-y divide-slate-200">
    <thead class="bg-slate-50">
     <tr>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Name</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Batch No</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Faculty</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Email</th>
      <th class="px-6 py-3"></th>
     </tr>
    </thead>
    <tbody class="bg-white divide-y divide-slate-200">
     @forelse($students as $student)
      <tr>
       <td class="px-6 py-3 text-sm">{{ $student->student_name }}</td>
       <td class="px-6 py-3 text-sm">{{ $student->batch_no }}</td>
       <td class="px-6 py-3 text-sm">{{ $student->faculty }}</td>
       <td class="px-6 py-3 text-sm">{{ $student->email }}</td>
       <td class="px-6 py-3 text-right">
        <div class="inline-flex items-center gap-2">
         <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Edit</a>
         <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student? This action cannot be undone.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-2 text-white text-sm font-medium hover:bg-rose-700">Delete</button>
         </form>
        </div>
       </td>
      </tr>
     @empty
      <tr>
       <td colspan="5" class="px-6 py-6 text-center text-sm text-slate-500">No students yet.</td>
      </tr>
     @endforelse
    </tbody>
   </table>
   
  </div>
  @endif
 </div>
@endsection
