<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show all reservations for customer or admin
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $reservations = Reservation::with(['user', 'table'])
                ->orderBy('reservation_date', 'desc')
                ->paginate(10);
        } else {
            $reservations = Auth::user()->reservations()
                ->with('table')
                ->orderBy('reservation_date', 'desc')
                ->paginate(10);
        }

        return view('reservations.index', compact('reservations'));
    }

    // Show create reservation form
    public function create()
    {
        return view('reservations.create');
    }

    // Store reservation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date_format:Y-m-d\TH:i|after:now',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'special_request' => 'nullable|string|max:500',
        ]);

        // Get available table
        $table = Table::getAvailableTables(
            $validated['reservation_date'],
            $validated['number_of_guests']
        )->first();

        if (! $table) {
            return back()
                ->with('error', 'Tidak ada meja tersedia untuk waktu dan jumlah tamu tersebut.');
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'table_id' => $table->id,
            'reservation_date' => $validated['reservation_date'],
            'number_of_guests' => $validated['number_of_guests'],
            'special_request' => $validated['special_request'] ?? null,
            'deposit_amount' => 100000, // Rp 100,000
            'status' => 'pending',
        ]);

        // Send confirmation email
        // Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran deposit.');
    }

    // Show reservation detail
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        return view('reservations.show', compact('reservation'));
    }

    // Show edit form
    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        if (! $reservation->canBeCancelled()) {
            return back()->with('error', 'Reservasi tidak dapat diubah.');
        }

        return view('reservations.edit', compact('reservation'));
    }

    // Update reservation
    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'reservation_date' => 'required|date_format:Y-m-d H:i|after:now',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'special_request' => 'nullable|string|max:500',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil diperbarui!');
    }

    // Cancel reservation
    public function cancel(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        if (! $reservation->canBeCancelled()) {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    // Check availability
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date_format:Y-m-d H:i',
            'number_of_guests' => 'required|integer|min:1',
        ]);

        $available = Table::getAvailableTables(
            $validated['reservation_date'],
            $validated['number_of_guests']
        );

        return response()->json([
            'available' => $available->count() > 0,
            'tables_count' => $available->count(),
        ]);
    }
}
