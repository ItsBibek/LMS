@extends('layouts.student')

@section('title', 'My Profile')
@section('header', 'My Profile')
@section('subheader', 'View your information, search books, and manage reservations')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-500 to-blue-500 rounded-xl p-6 text-white shadow-lg mb-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl font-bold mb-1">Welcome Back, {{ explode(' ', $student->student_name)[0] }}! 👋</h2>
      <p class="text-blue-100 text-sm">Manage your library activities and explore our collection</p>
    </div>
    @if ($student->currentIssues->count() > 0)
      <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-3 text-center">
        <div class="text-3xl font-bold">{{ $student->currentIssues->count() }}</div>
        <div class="text-xs text-blue-100">Books Borrowed</div>
      </div>
    @endif
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- LEFT SIDEBAR -->
  <div class="lg:col-span-1 space-y-6">
    <!-- Profile Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-500 rounded-xl p-6 text-white shadow-lg">
      <div class="flex flex-col items-center text-center">
        @if ($student->photo_path && Storage::disk('public')->exists($student->photo_path))
          <img src="{{ Storage::url($student->photo_path) }}" alt="Photo of {{ $student->student_name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg mb-4" />
        @else
          <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4">
            {{ strtoupper(substr($student->student_name, 0, 1)) }}
          </div>
        @endif
        <h2 class="text-xl font-bold mb-1">{{ $student->student_name }}</h2>
        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-sm font-medium mb-3">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
          </svg>
          {{ $student->batch_no }}
        </div>
      </div>
      <div class="mt-4 pt-4 border-t border-white/20 space-y-2">
        <div class="flex items-center justify-between text-sm">
          <span class="text-blue-100 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Faculty
          </span>
          <span class="font-medium">{{ $student->faculty }}</span>
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="text-blue-100 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Email
          </span>
          <span class="font-medium truncate ml-2">{{ $student->email ?: 'N/A' }}</span>
        </div>
      </div>
    </div>

    <!-- Search Books - Quick Access -->
    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-6 shadow-sm">
      <div class="text-center">
        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-slate-900 mb-2">Search for Books</h3>
        <p class="text-sm text-slate-600 mb-4">Browse our complete library collection and reserve books</p>
        <a href="{{ route('student.books.search') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-all w-full">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          Browse Books
        </a>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT AREA -->
  <div class="lg:col-span-2 space-y-6">
    <!-- Reserved Books -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <h3 class="text-base font-semibold text-slate-900">My Reservations</h3>
        </div>
        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
          {{ $myReservations->count() }} active
        </span>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Expires</th>
              <th class="px-4 py-2"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-200">
            @forelse($myReservations as $r)
              @php($expires = \Carbon\Carbon::parse($r->reserved_at)->addHours(24))
              <tr>
                <td class="px-4 py-2 text-sm">{{ $r->Accession_Number }}</td>
                <td class="px-4 py-2 text-sm">{{ optional($r->book)->Title ?? '-' }}</td>
                <td class="px-4 py-2 text-sm">{{ $expires->toDayDateTimeString() }}</td>
                <td class="px-4 py-2 text-right">
                  <form method="POST" action="{{ route('student.reservations.cancel', $r->id) }}" onsubmit="return confirm('Remove this reservation?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Remove</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500">No active reservations.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Currently Issued Books -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          <h3 class="text-base font-semibold text-slate-900">Currently Borrowed</h3>
        </div>
        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
          {{ $student->currentIssues->count() }} active
        </span>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Issue Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Due Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Fine</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-200">
            @forelse($student->currentIssues as $issue)
              <tr>
                <td class="px-4 py-2 text-sm">{{ $issue->Accession_Number }}</td>
                <td class="px-4 py-2 text-sm">{{ optional($issue->book)->Title ?? '-' }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->issue_date }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->due_date }}</td>
                <td class="px-4 py-2 text-sm">
                  @php($today = \Carbon\Carbon::now())
                  @php($due = \Carbon\Carbon::parse($issue->due_date))
                  @php($lateDays = max(0, $due->diffInDays($today, false)))
                  @php($fineNow = $lateDays * 2)
                  @if($fineNow > 0)
                    <span class="text-rose-600 font-semibold">{{ number_format($fineNow, 2) }}</span>
                  @else
                    <span class="text-slate-600">{{ number_format($fineNow, 2) }}</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-4 text-center text-sm text-slate-500">No books currently issued.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Borrowing History -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-slate-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <h3 class="text-base font-semibold text-slate-900">Borrowing History</h3>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Issued</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Returned</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Fine</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-200">
            @forelse($student->returnedIssues()->latest('return_date')->get() as $issue)
              <tr>
                <td class="px-4 py-2 text-sm">{{ $issue->Accession_Number }}</td>
                <td class="px-4 py-2 text-sm">{{ optional($issue->book)->Title ?? '-' }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->issue_date }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->return_date }}</td>
                <td class="px-4 py-2 text-sm">{{ number_format($issue->fine, 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-4 text-center text-sm text-slate-500">No history yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
