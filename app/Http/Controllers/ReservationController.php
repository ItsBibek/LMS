<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\Issue;
use App\Models\User;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Student creates a reservation
    public function store(Request $request)
    {
        $batch = $request->session()->get('student_batch');
        abort_unless($batch, 403);

        $data = $request->validate([
            'accession' => ['required','string'],
        ]);

        $normalized = preg_replace('/\D+/', '', $data['accession']);
        $book = Book::where('Accession_Number', $normalized)
            ->orWhere('Accession_Number', $data['accession'])
            ->first();
        if (!$book) {
            return back()->withErrors(['accession' => 'Book not found.']);
        }

        // If already issued, cannot reserve
        $alreadyIssued = Issue::where('Accession_Number', $book->Accession_Number)
            ->whereNull('return_date')
            ->exists();
        if ($alreadyIssued) {
            return back()->withErrors(['accession' => 'Book is currently issued.']);
        }

        // Expire old pending reservations
        $pendings = Reservation::where('Accession_Number', $book->Accession_Number)
            ->where('status', 'pending')->get();
        foreach ($pendings as $res) {
            if (Carbon::parse($res->reserved_at)->addHours(24)->isPast()) {
                $res->status = 'expired';
                $res->save();
            }
        }

        // If another active pending reservation exists, block
        $existing = Reservation::with('user')
            ->where('Accession_Number', $book->Accession_Number)
            ->where('status', 'pending')
            ->first();
        if ($existing && $existing->user_batch_no !== $batch) {
            $expiresAt = Carbon::parse($existing->reserved_at)->addHours(24)->toDayDateTimeString();
            $name = optional($existing->user)->student_name;
            $label = $name ? ($name.' ('.$existing->user_batch_no.')') : $existing->user_batch_no;
            return back()->withErrors(['accession' => 'This book is reserved by '.$label.' until '.$expiresAt.'.']);
        }

        // If same student has an active reservation, refresh timestamp
        if ($existing && $existing->user_batch_no === $batch) {
            $existing->reserved_at = now();
            $existing->save();
            return back()->with('status', 'Reservation refreshed for another 24 hours.');
        }

        Reservation::create([
            'user_batch_no' => $batch,
            'Accession_Number' => $book->Accession_Number,
            'reserved_at' => now(),
            'status' => 'pending',
        ]);

        return back()->with('status', 'Book reserved for 24 hours. Show this to the librarian to issue.');
    }

    // Admin list
    public function index()
    {
        // Auto-expire
        $all = Reservation::where('status','pending')->get();
        foreach ($all as $r) {
            if (Carbon::parse($r->reserved_at)->addHours(24)->isPast()) {
                $r->status = 'expired';
                $r->save();
            }
        }

        $reservations = Reservation::with(['user','book'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'issued' THEN 1 WHEN 'declined' THEN 2 ELSE 3 END")
            ->orderByDesc('reserved_at')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    // Admin: issue from reservation
    public function issue(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->withErrors(['reservation' => 'Reservation is not pending.']);
        }
        if (Carbon::parse($reservation->reserved_at)->addHours(24)->isPast()) {
            $reservation->status = 'expired';
            $reservation->save();
            return back()->withErrors(['reservation' => 'Reservation has expired.']);
        }

        $book = $reservation->book;
        if (!$book) {
            return back()->withErrors(['reservation' => 'Book not found.']);
        }
        $already = Issue::where('Accession_Number', $book->Accession_Number)
            ->whereNull('return_date')->exists();
        if ($already) {
            return back()->withErrors(['reservation' => 'Book is already issued.']);
        }

        $issueDate = now();
        $dueDate = $issueDate->copy()->addMonthsNoOverflow(6);

        Issue::create([
            'user_batch_no' => $reservation->user_batch_no,
            'Accession_Number' => $book->Accession_Number,
            'issue_date' => $issueDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'return_date' => null,
            'fine' => 0,
        ]);

        $reservation->status = 'issued';
        $reservation->save();

        return back()->with('status', 'Book issued from reservation.');
    }

    // Admin: delete/decline
    public function destroy(Reservation $reservation)
    {
        if ($reservation->status === 'pending') {
            $reservation->status = 'declined';
            $reservation->save();
        } else {
            $reservation->delete();
        }
        return back()->with('status', 'Reservation removed.');
    }

    // Student: cancel own reservation -> mark declined
    public function cancel(Request $request, Reservation $reservation)
    {
        $batch = $request->session()->get('student_batch');
        abort_unless($batch, 403);
        if ($reservation->user_batch_no !== $batch) {
            abort(403);
        }
        if ($reservation->status === 'pending') {
            $reservation->status = 'declined';
            $reservation->save();
            return back()->with('status', 'Your reservation has been removed.');
        }
        return back()->withErrors(['reservation' => 'Reservation is not pending.']);
    }
}
