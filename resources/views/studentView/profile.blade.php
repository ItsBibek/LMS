@extends('layouts.student')

@section('title', 'My Profile')
@section('header', 'My Profile')
@section('subheader', $student->student_name . ' · ' . $student->batch_no)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <!-- LEFT COLUMN -->
  <div class="space-y-6">

    <!-- Profile Card -->
    <div class="bg-white border border-slate-200 rounded-lg p-6">
      <div class="flex items-start gap-4">
        @if ($student->photo_path && Storage::disk('public')->exists($student->photo_path))
          <img src="{{ Storage::url($student->photo_path) }}" alt="Photo of {{ $student->student_name }}" class="w-16 h-16 rounded-full object-cover" />
        @else
          <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xl">
            {{ strtoupper(substr($student->student_name, 0, 1)) }}
          </div>
        @endif
        <div>
          <div class="text-base font-semibold">{{ $student->student_name }}</div>
          <div class="text-sm text-slate-500">Batch: {{ $student->batch_no }}</div>
          <div class="text-sm text-slate-500">Faculty: {{ $student->faculty }}</div>
          <div class="text-sm text-slate-500 break-all">{{ $student->email }}</div>
        </div>
      </div>
    </div>

    <!-- Search Books -->
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-700">Search Books by Title</h3>
      </div>
      <div class="p-4">
        <form method="GET" action="{{ route('student.profile') }}" class="grid grid-cols-1 md:grid-cols-3 gap-2">
          <div class="md:col-span-2">
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Type part of the title (e.g. 'numerical')" class="w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
          </div>
          <div class="md:col-span-1 flex gap-2">
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700 w-full">Search</button>
            <a href="{{ route('student.profile') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50 w-full">Reset</a>
          </div>
        </form>

        @if(isset($q) && $q !== '' && isset($matches) && $matches->count() > 0)
          <div class="mt-3 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Author</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Availability</th>
                  <th class="px-4 py-2"></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-slate-200">
                @foreach($matches as $m)
                  @php($st = $statusByAccession[$m->Accession_Number] ?? ['issued'=>false,'reserved'=>false])
                  <tr>
                    <td class="px-4 py-2 text-sm">{{ $m->Accession_Number }}</td>
                    <td class="px-4 py-2 text-sm">{{ $m->Title ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm">{{ $m->Author ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm">
                      @if($st['issued'])
                        <span class="inline-flex items-center rounded-md bg-rose-50 text-rose-700 px-2 py-1 text-xs font-medium">Issued</span>
                      @elseif($st['reserved'])
                        <span class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 px-2 py-1 text-xs font-medium">
                          Reserved by {{ $st['reserved_by'] }} until {{ $st['expires_at'] }}
                        </span>
                      @else
                        <span class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1 text-xs font-medium">Available</span>
                      @endif
                    </td>
                    <td class="px-4 py-2 text-right">
                      @if(!$st['issued'] && $st['reserved'] && isset($st['reserved_by']) && isset($st['reservation_id']) && $st['reserved_by'] === $student->batch_no)
                        <form method="POST" action="{{ route('student.reservations.cancel', $st['reservation_id']) }}">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Remove Reservation</button>
                        </form>
                      @elseif(!$st['issued'] && (!$st['reserved']))
                        <form method="POST" action="{{ route('student.reservations.store') }}">
                          @csrf
                          <input type="hidden" name="accession" value="{{ $m->Accession_Number }}" />
                          <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Reserve</button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @elseif(isset($q) && $q !== '')
          <div class="mt-3 text-sm text-slate-500">No books found for "{{ $q }}".</div>
        @endif
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN -->
  <div class="space-y-6">

    <!-- Reserved -->
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-700">Reserved</h3>
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

    <!-- Currently Issued -->
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-700">Currently Issued</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Accession No.</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Issue Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Due Date</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-200">
            @forelse($student->currentIssues as $issue)
              <tr>
                <td class="px-4 py-2 text-sm">{{ $issue->Accession_Number }}</td>
                <td class="px-4 py-2 text-sm">{{ optional($issue->book)->Title ?? '-' }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->issue_date }}</td>
                <td class="px-4 py-2 text-sm">{{ $issue->due_date }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-4 text-center text-sm text-slate-500">No books currently issued.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- History -->
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-slate-700">History</h3>
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
