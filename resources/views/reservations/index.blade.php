@extends('layouts.app')

@section('title', 'Reservations')
@section('header', 'Reservations')
@section('subheader', 'Manage book reservations')

@section('content')
 <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
  <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
   <h3 class="text-sm font-semibold text-slate-700">All Reservations</h3>
   @if (session('status'))
    <div class="text-sm text-emerald-700">{{ session('status') }}</div>
   @endif
   @if ($errors->any())
    <div class="text-sm text-rose-700">{{ $errors->first() }}</div>
   @endif
  </div>
  <div class="overflow-x-auto">
   <table class="min-w-full divide-y divide-slate-200">
    <thead class="bg-slate-50">
     <tr>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Accession</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Title</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Student</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Batch</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Expires</th>
      <th class="px-6 py-3"></th>
     </tr>
    </thead>
    <tbody class="bg-white divide-y divide-slate-200">
     @forelse($reservations as $r)
      @php($expires = \Carbon\Carbon::parse($r->reserved_at)->addHours(24))
      <tr>
       <td class="px-6 py-3 text-sm">{{ $r->Accession_Number }}</td>
       <td class="px-6 py-3 text-sm">{{ optional($r->book)->Title ?? '-' }}</td>
       <td class="px-6 py-3 text-sm"><span class="font-semibold text-slate-800">{{ optional($r->user)->student_name ?? '-' }}</span></td>
       <td class="px-6 py-3 text-sm"><span class="inline-flex items-center rounded-md bg-slate-100 text-slate-700 px-2 py-1 text-xs font-medium">{{ $r->user_batch_no }}</span></td>
       <td class="px-6 py-3 text-sm">
        @if($r->status === 'pending')
         <span class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 px-2 py-1 text-xs font-medium">Pending</span>
        @elseif($r->status === 'issued')
         <span class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1 text-xs font-medium">Issued</span>
        @elseif($r->status === 'declined')
         <span class="inline-flex items-center rounded-md bg-rose-50 text-rose-700 px-2 py-1 text-xs font-medium">Declined</span>
        @else
         <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-700 px-2 py-1 text-xs font-medium">Expired</span>
        @endif
       </td>
       <td class="px-6 py-3 text-sm">{{ $expires->toDayDateTimeString() }}</td>
       <td class="px-6 py-3 text-sm text-right">
        @if($r->status === 'pending' && $expires->isFuture())
         <form method="POST" action="{{ route('reservations.issue', $r) }}" class="inline">
          @csrf
          <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-white text-sm font-medium hover:bg-indigo-700">Issue</button>
         </form>
        @endif
        <form method="POST" action="{{ route('reservations.destroy', $r) }}" class="inline" onsubmit="return confirm('Remove this reservation?')">
         @csrf
         @method('DELETE')
         <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50 ml-2">Delete</button>
        </form>
       </td>
      </tr>
     @empty
      <tr>
       <td colspan="7" class="px-6 py-6 text-center text-sm text-slate-500">No reservations yet.</td>
      </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  <div class="px-6 py-4 border-t border-slate-200">
   {{ $reservations->links() }}
  </div>
 </div>
@endsection
