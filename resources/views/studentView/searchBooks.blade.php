@extends('layouts.student')

@section('title', 'Search Books')
@section('header', 'Search Books')
@section('subheader', 'Browse and reserve books from our library collection')

@section('content')
<div class="max-w-7xl mx-auto">
  <!-- Search Section -->
  <div class="bg-gradient-to-br from-white to-blue-50 border border-slate-200 rounded-xl p-6 md:p-8 shadow-sm mb-6">
    <div class="flex items-center mb-6">
      <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <div>
        <h2 class="text-xl font-semibold text-slate-900">Search Our Collection</h2>
        <p class="text-sm text-slate-600">Search by title, author, or accession number</p>
      </div>
    </div>
    
    <form method="GET" action="{{ route('student.books.search') }}" class="space-y-4">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <input type="text" name="q" value="{{ $q }}" placeholder="Search for books..." autofocus autocomplete="off"
               class="w-full pl-12 pr-4 py-4 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all placeholder-slate-400 text-slate-900 text-lg" />
      </div>
      <div class="flex gap-3">
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-8 py-3 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-all">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Search
        </button>
        <a href="{{ route('student.books.search') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Clear
        </a>
        <a href="{{ route('student.profile') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Back to Profile
        </a>
      </div>
    </form>
  </div>

  @if($q === '')
    <!-- Empty State -->
    <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
      <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-900 mb-2">Start Searching</h3>
      <p class="text-sm text-slate-600 mb-4">Enter a book title, author name, or accession number above to search our collection</p>
      <p class="text-xs text-slate-500">Tip: You can search by partial matches - just type part of the title!</p>
    </div>
  @elseif($matches && $matches->count() > 0)
    <!-- Results -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <h3 class="text-base font-semibold text-slate-900">Found {{ $matches->total() }} result{{ $matches->total() === 1 ? '' : 's' }} for "{{ $q }}"</h3>
        </div>
        <div class="text-xs text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">
          Page {{ $matches->currentPage() }} of {{ $matches->lastPage() }}
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Accession No.</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Title</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Author</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-slate-100">
            @foreach($matches as $book)
              @php($st = $statusByAccession[$book->Accession_Number] ?? ['issued'=>false,'reserved'=>false])
              <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-xs font-medium text-slate-700">
                    {{ $book->Accession_Number }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <div class="text-sm font-medium text-slate-900">{{ $book->Title }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $book->Author ?: '-' }}</td>
                <td class="px-6 py-4">
                  @if($st['issued'])
                    <span class="inline-flex items-center rounded-full bg-rose-50 text-rose-700 px-3 py-1 text-xs font-medium">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                      </svg>
                      Issued
                    </span>
                  @elseif($st['reserved'])
                    <span class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-3 py-1 text-xs font-medium">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                      </svg>
                      Reserved
                    </span>
                  @else
                    <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-xs font-medium">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      Available
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right">
                  @if(!$st['issued'] && $st['reserved'] && isset($st['reserved_by']) && isset($st['reservation_id']) && $st['reserved_by'] === $student->batch_no)
                    <form method="POST" action="{{ route('student.reservations.cancel', $st['reservation_id']) }}" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="inline-flex items-center rounded-lg border-2 border-rose-200 px-4 py-2 text-xs font-medium text-rose-700 hover:bg-rose-50 transition-all">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                      </button>
                    </form>
                  @elseif(!$st['issued'] && $st['reserved'])
                    <span class="text-xs text-slate-500">Reserved by {{ $st['reserved_by'] }}</span>
                  @elseif(!$st['issued'] && !$st['reserved'])
                    <form method="POST" action="{{ route('student.reservations.store') }}" class="inline">
                      @csrf
                      <input type="hidden" name="accession" value="{{ $book->Accession_Number }}" />
                      <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-xs font-medium text-white hover:bg-emerald-700 shadow-sm transition-all">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Reserve
                      </button>
                    </form>
                  @else
                    <span class="text-xs text-slate-400">Not Available</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($matches->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
          <div class="flex items-center justify-between">
            <div class="text-sm text-slate-600">
              Showing {{ $matches->firstItem() }} to {{ $matches->lastItem() }} of {{ $matches->total() }} results
            </div>
            <div>
              {{ $matches->appends(['q' => $q])->links() }}
            </div>
          </div>
        </div>
      @endif
    </div>
  @else
    <!-- No Results -->
    <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
      <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-slate-900 mb-2">No Books Found</h3>
      <p class="text-sm text-slate-600 mb-4">We couldn't find any books matching "<strong>{{ $q }}</strong>"</p>
      <div class="flex items-center justify-center gap-3">
        <a href="{{ route('student.books.search') }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
          Try Another Search
        </a>
      </div>
    </div>
  @endif
</div>
@endsection
