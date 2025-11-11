@extends('layouts.app')

@section('title', 'Book Barcode')
@section('header', 'Book Barcode')
@section('subheader', 'Code 39 Barcode for ' . $book->Accession_Number)

@section('content')
<!-- Print-only layout -->
<div class="print-only" style="display: none;">
    <div style="text-align: center; padding: 40px 20px;">
        <img src="{{ route('books.barcode', $book->Accession_Number) }}" 
             alt="Barcode for {{ $book->Accession_Number }}"
             style="max-width: 100%; height: auto; margin: 0 auto;">
        <div style="margin-top: 20px; font-size: 24px; font-weight: bold; font-family: monospace;">
            {{ $book->Accession_Number }}
        </div>
    </div>
</div>

<!-- Screen layout -->
<div class="max-w-3xl mx-auto screen-only">
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <!-- Book Info Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500 mb-1">Accession Number</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $book->Accession_Number }}</div>
                </div>
                <a href="{{ route('books.index', ['q' => $book->Accession_Number]) }}" class="inline-flex items-center rounded-lg border-2 border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Book
                </a>
            </div>
        </div>

        <!-- Book Details -->
        <div class="divide-y divide-slate-200">
            <div class="flex items-start px-6 py-4">
                <div class="w-32 text-sm text-slate-500">Title</div>
                <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Title ?? '-' }}</div>
            </div>
            @if($book->Author)
            <div class="flex items-start px-6 py-4">
                <div class="w-32 text-sm text-slate-500">Author</div>
                <div class="flex-1 text-sm font-medium text-slate-800">{{ $book->Author }}</div>
            </div>
            @endif
        </div>

        <!-- Barcode Display -->
        <div class="px-6 py-8 bg-gradient-to-br from-slate-50 to-white border-t border-slate-200">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Code 39 Barcode</h3>
                <p class="text-sm text-slate-600 mb-6">Scan this barcode to quickly identify the book</p>
                
                <!-- Barcode Image -->
                <div class="inline-block bg-white p-6 rounded-lg border-2 border-slate-200 shadow-sm">
                    <img src="{{ route('books.barcode', $book->Accession_Number) }}" 
                         alt="Barcode for {{ $book->Accession_Number }}"
                         class="mx-auto"
                         style="max-width: 100%; height: auto;">
                    <div class="mt-4 text-lg font-mono font-semibold text-slate-800">{{ $book->Accession_Number }}</div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center justify-center gap-3">
                    <button onclick="downloadBarcodeWithNumber()" 
                            class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Barcode
                    </button>
                    <button onclick="window.print()" 
                            class="inline-flex items-center rounded-lg border-2 border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Barcode
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="px-6 py-4 bg-blue-50 border-t border-blue-100">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">About Code 39 Barcodes</p>
                    <p>Code 39 is a variable-length barcode that can encode alphanumeric characters. It's widely used in inventory management and library systems.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadBarcodeWithNumber() {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.src = '{{ route('books.barcode', $book->Accession_Number) }}';
    
    img.onload = function() {
        // Set canvas size - barcode width + padding, barcode height + text space
        canvas.width = img.width + 40;
        canvas.height = img.height + 80;
        
        // Fill white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Draw barcode centered
        ctx.drawImage(img, 20, 20, img.width, img.height);
        
        // Draw accession number below barcode
        ctx.fillStyle = 'black';
        ctx.font = 'bold 20px monospace';
        ctx.textAlign = 'center';
        ctx.fillText('{{ $book->Accession_Number }}', canvas.width / 2, img.height + 50);
        
        // Download
        canvas.toBlob(function(blob) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'barcode-{{ $book->Accession_Number }}.png';
            a.click();
            URL.revokeObjectURL(url);
        });
    };
}
</script>

<style>
@media print {
    /* Hide everything except print-only content */
    body * {
        visibility: hidden;
    }
    
    .print-only, .print-only * {
        visibility: visible;
        display: block !important;
    }
    
    .screen-only {
        display: none !important;
    }
    
    .print-only {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    /* Remove all backgrounds and borders */
    body {
        background: white !important;
    }
    
    @page {
        margin: 1cm;
    }
}

@media screen {
    .print-only {
        display: none !important;
    }
}
</style>
@endsection
