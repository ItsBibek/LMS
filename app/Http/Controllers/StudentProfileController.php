<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Issue;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class StudentProfileController extends Controller
{
    public function profile(Request $request)
    {
        $student = $request->user();
        $batch = $student->batch_no;
        $student->load(['issues.book']);

        $q = trim((string) $request->get('q', ''));
        $matches = collect();
        $statusByAccession = [];
        if ($q !== '') {
            $matches = Book::query()
                ->where('Title', 'LIKE', '%'.$q.'%')
                ->orderBy('Title')
                ->limit(50)
                ->get();

            if ($matches->count() > 0) {
                $accessions = $matches->pluck('Accession_Number')->all();
                // Current issues map
                $issues = Issue::whereIn('Accession_Number', $accessions)
                    ->whereNull('return_date')
                    ->get()
                    ->keyBy('Accession_Number');
                // Active reservations map (pending and not expired)
                $reservations = Reservation::whereIn('Accession_Number', $accessions)
                    ->where('status', 'pending')
                    ->orderBy('reserved_at')
                    ->get()
                    ->groupBy('Accession_Number');

                foreach ($accessions as $acc) {
                    $item = [
                        'issued' => false,
                        'reserved' => false,
                        'reserved_by' => null,
                        'expires_at' => null,
                        'reservation_id' => null,
                    ];
                    if (isset($issues[$acc])) {
                        $item['issued'] = true;
                    }
                    $resList = $reservations->get($acc);
                    if ($resList && $resList->count() > 0) {
                        $first = $resList->first();
                        $expires = Carbon::parse($first->reserved_at)->addHours(24);
                        if ($expires->isFuture()) {
                            $item['reserved'] = true;
                            $item['reserved_by'] = $first->user_batch_no;
                            $item['expires_at'] = $expires->toDateTimeString();
                            $item['reservation_id'] = $first->id;
                        }
                    }
                    $statusByAccession[$acc] = $item;
                }
            }
        }

        // Student's own active reservations (pending and not expired)
        $myReservations = Reservation::with('book')
            ->where('user_batch_no', $batch)
            ->where('status', 'pending')
            ->orderBy('reserved_at', 'desc')
            ->get()
            ->filter(function ($r) {
                return Carbon::parse($r->reserved_at)->addHours(24)->isFuture();
            })
            ->values();

        return view('studentView.profile', compact('student', 'myReservations'));
    }

    public function searchBooks(Request $request)
    {
        $student = $request->user();
        $q = trim((string) $request->get('q', ''));
        
        $matches = collect();
        $statusByAccession = [];
        
        if ($q !== '') {
            // Search with pagination
            $matches = Book::query()
                ->where(function($query) use ($q) {
                    $query->where('Title', 'LIKE', '%'.$q.'%')
                          ->orWhere('Author', 'LIKE', '%'.$q.'%')
                          ->orWhere('Accession_Number', 'LIKE', '%'.$q.'%');
                })
                ->orderBy('Title')
                ->paginate(20);

            if ($matches->count() > 0) {
                $accessions = $matches->pluck('Accession_Number')->all();
                
                // Current issues map
                $issues = Issue::whereIn('Accession_Number', $accessions)
                    ->whereNull('return_date')
                    ->get()
                    ->keyBy('Accession_Number');
                    
                // Active reservations map (pending and not expired)
                $reservations = Reservation::whereIn('Accession_Number', $accessions)
                    ->where('status', 'pending')
                    ->orderBy('reserved_at')
                    ->get()
                    ->groupBy('Accession_Number');

                foreach ($accessions as $acc) {
                    $item = [
                        'issued' => false,
                        'reserved' => false,
                        'reserved_by' => null,
                        'expires_at' => null,
                        'reservation_id' => null,
                    ];
                    
                    if (isset($issues[$acc])) {
                        $item['issued'] = true;
                    }
                    
                    $resList = $reservations->get($acc);
                    if ($resList && $resList->count() > 0) {
                        $first = $resList->first();
                        $expires = Carbon::parse($first->reserved_at)->addHours(24);
                        if ($expires->isFuture()) {
                            $item['reserved'] = true;
                            $item['reserved_by'] = $first->user_batch_no;
                            $item['expires_at'] = $expires->format('M d, Y h:i A');
                            $item['reservation_id'] = $first->id;
                        }
                    }
                    $statusByAccession[$acc] = $item;
                }
            }
        }

        return view('studentView.searchBooks', compact('student', 'q', 'matches', 'statusByAccession'));
    }
}
