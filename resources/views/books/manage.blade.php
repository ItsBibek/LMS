@extends('layouts.app')

@section('title', 'Manage Students')
@section('header', 'Manage Students')
@section('subheader', 'View, edit, or add students')

@section('content')
<div class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">

        {{-- Status/Error messages --}}
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-rose-100 border border-rose-200 text-rose-800 px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
            <input type="text" placeholder="Search students..." 
                   class="bg-white px-4 py-2 rounded-lg border-2 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm w-full sm:w-64 transition">
            <div class="text-gray-600 text-sm font-medium">Total Students: <span class="font-semibold">{{ $students->count() }}</span></div>
        </div>

        {{-- Students Table --}}
        <div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $student->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $student->class }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('students.edit', $student->id) }}" class="px-3 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">Edit</a>
                                    <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('Delete this student?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-md bg-rose-600 text-white text-sm font-medium hover:bg-rose-700 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">No students yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Floating Add Student Button --}}
        <a href="{{ route('students.create') }}"
           class="fixed bottom-8 right-8 inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-3 text-white font-bold shadow-xl hover:bg-indigo-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Student
        </a>

    </div>
</div>
@endsection
