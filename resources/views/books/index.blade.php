@extends('layouts.app')

@section('title', 'Books')
@section('header', 'Books')
@section('header_actions')
 <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 shadow-sm transition-all">
  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
  </svg>
  Add Book
 </a>
@endsection
@section('subheader', 'Search and manage your library collection')

@section('content')
 <!-- Search Section -->
 <div class="bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-xl p-6 md:p-8 shadow-sm mb-6">
  <div class="flex items-center mb-4">
   <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
   </div>
   <div>
    <h3 class="text-lg font-semibold text-slate-900">Search Books</h3>
    <p class="text-sm text-slate-600">Find books by accession number or title</p>
   </div>
  </div>
  
  <form method="GET" action="{{ route('books.index') }}" class="space-y-4">
   <div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
     <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
     </svg>
    </div>
    <input type="text" name="q" value="{{ $q }}" placeholder="Search by accession number or book title..."
           autofocus autocomplete="off"
           class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all placeholder-slate-400 text-slate-900" />
   </div>
   <div class="flex gap-3">
    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-white text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm transition-all">
     <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
     </svg>
     Search
    </button>
    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all">
     <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
     </svg>
     Clear
    </a>
   </div>
  </form>



  @if ($errors->any())
   <div class="mt-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  @if (session('status'))
   <div class="mt-4 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif

 </div>

 <!-- Results Section -->
 <div class="mt-6">
   @if($q === '')
    <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-12 text-center">
     <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
     </div>
     <h3 class="text-lg font-semibold text-slate-900 mb-2">Start Searching</h3>
     <p class="text-sm text-slate-600 mb-4">Enter an accession number or book title above to find books</p>
     <div class="flex items-center justify-center gap-4 text-xs text-slate-500">
      <div class="flex items-center">
       <svg class="w-4 h-4 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
       </svg>
       Quick search
      </div>
      <div class="flex items-center">
       <svg class="w-4 h-4 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
       </svg>
       Partial matches
      </div>
     </div>
    </div>
   @endif

   @if(isset($matches) && $matches && $matches->count() > 0)
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
     <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
      <div class="flex items-center">
       <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
       </svg>
       <h3 class="text-sm font-semibold text-slate-900">Found {{ method_exists($matches, 'total') ? $matches->total() : $matches->count() }} result{{ (method_exists($matches, 'total') ? $matches->total() : $matches->count()) === 1 ? '' : 's' }}</h3>
      </div>
      @if(method_exists($matches, 'total'))
       <div class="text-xs text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">Page {{ $matches->currentPage() }} of {{ $matches->lastPage() }}</div>
      @endif
     </div>
     <ul class="divide-y divide-slate-100">
      @foreach($matches as $m)
       <li class="px-6 py-4 hover:bg-slate-50 transition-colors group">
        <div class="flex items-center justify-between">
         <div class="flex-1">
          <div class="flex items-center mb-1">
           <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
           </svg>
           <div class="text-sm font-medium text-slate-900 group-hover:text-blue-600 transition-colors">{{ $m->Title ?? '-' }}</div>
          </div>
          <div class="flex items-center text-xs text-slate-500 space-x-3 ml-6">
           <span class="flex items-center">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            {{ $m->Accession_Number }}
           </span>
           @if($m->Author)
            <span class="flex items-center">
             <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
             </svg>
             {{ $m->Author }}
            </span>
           @endif
          </div>
         </div>
         <div class="flex items-center gap-2">
          <a href="{{ route('books.barcode-view', $m->Accession_Number) }}" class="inline-flex items-center rounded-lg border-2 border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 hover:bg-blue-100 hover:border-blue-300 transition-all" title="Generate Barcode">
           <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
           </svg>
           Barcode
          </a>
          <a href="{{ route('books.index', ['q' => $m->Accession_Number]) }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all">
           <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
           </svg>
           View
          </a>
         </div>
        </div>
       </li>
      @endforeach
     </ul>
     @if(method_exists($matches, 'links'))
      <div class="px-4 md:px-6 py-3 border-t border-slate-200 flex items-center justify-between">
       <div class="text-sm text-slate-600">{{ ($total_matches ?? null) ? number_format($total_matches) : (method_exists($matches, 'total') ? number_format($matches->total()) : $matches->count()) }} results found</div>
       {{ $matches->links() }}
      </div>
     @endif
    </div>
   @endif

   @if(isset($book) && $book)
    <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-xl overflow-hidden">
     <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <div>
       <div class="text-xs uppercase tracking-wide text-slate-500">Accession No.</div>
       <div class="text-lg font-semibold text-slate-800">{{ $book->Accession_Number }}</div>
      </div>
      <div class="flex items-center gap-2">
      @if(!empty($isIssued) && $isIssued)
       <span class="inline-flex items-center rounded-md bg-rose-50 text-rose-700 px-2 py-1 text-xs font-medium">Issued</span>
      @elseif(isset($activeReservation) && $activeReservation)
       @php($expires = \Carbon\Carbon::parse($activeReservation->reserved_at)->addHours(24))
       <span class="inline-flex items-center rounded-md bg-amber-50 text-amber-700 px-2 py-1 text-xs font-medium">Reserved by {{ optional($activeReservation->user)->student_name ?? $activeReservation->user_batch_no }} until {{ $expires->toDayDateTimeString() }}</span>
      @else
       <span class="inline-flex items-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1 text-xs font-medium">Available</span>
      @endif
       <div class="relative">
        <button type="button" id="book-actions-btn" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-2.5 py-1.5 text-xs font-medium hover:bg-slate-50">
         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </button>
        <div id="book-actions-menu" class="hidden absolute right-0 mt-2 w-48 rounded-md border border-slate-200 bg-white shadow-lg z-10">
         <a href="{{ route('books.barcode-view', $book->Accession_Number) }}" class="block px-3 py-2 text-sm hover:bg-blue-50 text-blue-700 border-b border-slate-100">
          <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
          </svg>
          Generate Barcode
         </a>
         <a href="{{ route('books.edit', $book->Accession_Number) }}" class="block px-3 py-2 text-sm hover:bg-slate-50">Edit</a>
         <form method="POST" action="{{ route('books.destroy', $book->Accession_Number) }}" onsubmit="return confirm('Delete this book? This will not remove past issue history. Continue?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="w-full text-left px-3 py-2 text-sm text-rose-700 hover:bg-rose-50">Delete</button>
         </form>
        </div>
       </div>
       <script>
        (function(){
          var btn = document.getElementById('book-actions-btn');
          var menu = document.getElementById('book-actions-menu');
          function hide(){ if(menu) menu.classList.add('hidden'); }
          function toggle(e){ e.stopPropagation(); if(menu) menu.classList.toggle('hidden'); }
          if(btn){ btn.addEventListener('click', toggle); }
          document.addEventListener('click', function(e){ if(!menu || !btn) return; if(!menu.contains(e.target) && e.target!==btn){ hide(); } });
          document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ hide(); } });
        })();
       </script>
      </div>
     </div>
     <div class="divide-y divide-slate-200">
      <div class="flex items-start px-6 py-4">
       <div class="w-40 text-sm text-slate-500">Title</div>
       <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Title ?? '-' }}</div>
      </div>
      <div class="flex items-start px-6 py-4">
       <div class="w-40 text-sm text-slate-500">Author</div>
       <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Author ?? '-' }}</div>
      </div>
     </div>

     @if(!empty($isIssued) && $isIssued && isset($currentIssue) && $currentIssue)
      <div class="divide-y divide-slate-200">
       <div class="flex items-center px-6 py-4 bg-amber-50">
        <div class="w-40 text-sm font-semibold text-amber-800">Issued To</div>
        <div class="flex-1 text-sm text-amber-900">{{ $currentIssue->user->student_name ?? '-' }} ({{ $currentIssue->user->batch_no ?? '-' }})</div>
        <div>
         <form method="POST" action="{{ route('books.return', $currentIssue) }}" onsubmit="return confirm('Mark this book as returned?')">
          @csrf
          <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-white text-sm font-medium hover:bg-emerald-700">Return</button>
         </form>
        </div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Issue Date</div>
        <div class="flex-1">
         <span class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-800 bg-white">{{ $currentIssue->issue_date ?? '-' }}</span>
        </div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Due Date</div>
        <div class="flex-1 text-sm font-medium text-slate-800">{{ $currentIssue->due_date ?? '-' }}</div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Late Days</div>
        <div class="flex-1 text-sm font-medium text-slate-800">{{ $lateDays }}</div>
       </div>
       <div class="flex items-start px-6 py-4">
        <div class="w-40 text-sm text-slate-500">Accrued Fine</div>
        <div class="flex-1 text-sm font-medium text-slate-800">Rs. {{ $accruedFine }}</div>
       </div>
      </div>
     @endif

     @if((empty($isIssued) || !$isIssued))
      <div class="px-6 py-5 border-t border-slate-200">
       <h3 class="text-sm font-semibold text-slate-700">Issue this Book</h3>
       @if(isset($activeReservation) && $activeReservation)
        @php($expires = \Carbon\Carbon::parse($activeReservation->reserved_at)->addHours(24))
        <div class="mt-3 flex flex-col md:flex-row md:items-end md:justify-between gap-3">
         <div class="text-sm text-amber-700">Reserved by <strong>{{ optional($activeReservation->user)->student_name ?? $activeReservation->user_batch_no }}</strong> until {{ $expires->toDayDateTimeString() }}.</div>
         <div class="flex gap-2">
          <form method="POST" action="{{ route('reservations.issue', $activeReservation) }}">
           @csrf
           <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white text-sm font-medium hover:bg-emerald-700">Issue to {{ $activeReservation->user_batch_no }}</button>
          </form>
          <form method="POST" action="{{ route('reservations.destroy', $activeReservation) }}" onsubmit="return confirm('Delete this reservation?')">
           @csrf
           @method('DELETE')
           <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Delete Reservation</button>
          </form>
         </div>
        </div>
       @else
        <form method="POST" action="{{ route('books.issue') }}" class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
         @csrf
          <input type="hidden" name="accession" value="{{ $book->Accession_Number }}" />
          <div class="md:col-span-1">
           <label class="block text-sm font-medium text-slate-700">Batch Number</label>
           <input type="text" name="batch_no" value="{{ old('batch_no') }}" placeholder="e.g. 77A-001" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
          </div>
          <div class="md:col-span-1">
           <label class="block text-sm font-medium text-slate-700">Issue Date (optional)</label>
           <input type="date" name="issue_date" value="{{ old('issue_date') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
          </div>
          <div class="md:col-span-1 flex items-end">
           <button type="submit" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white text-sm font-medium hover:bg-emerald-700 w-full">Issue Book</button>
          </div>
         </form>
       @endif
      </div>
     @endif
    </div>
   @else
    @if($q !== '' && (!isset($matches) || ($matches && $matches->count() === 0)))
     <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
      <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
       <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
       </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-900 mb-2">No Books Found</h3>
      <p class="text-sm text-slate-600 mb-6">We couldn't find any books matching "<strong>{{ $q }}</strong>"</p>
      <div class="flex items-center justify-center gap-3">
       <a href="{{ route('books.index') }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Clear Search
       </a>
       <a href="{{ route('books.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-all">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Book
       </a>
      </div>
     </div>
    @endif
   @endif
  </div>
@endsection
