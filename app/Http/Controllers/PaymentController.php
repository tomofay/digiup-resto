<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // form bayar deposit untuk 1 reservasi
    public function create(Reservation $reservation)
    {
        // admin boleh lihat juga, tapi biasanya pembayaran oleh customer
        if (!Auth::user()->isAdmin() && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->payment_status === 'paid') {
            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Reservasi ini sudah dibayar.');
        }

        return view('payments.create', compact('reservation'));
    }

    // simpan pembayaran
    public function store(Request $request, Reservation $reservation)
    {
        if (!Auth::user()->isAdmin() && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // aturan: pembayaran hanya ketika reservasi masih pending
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Pembayaran hanya bisa dilakukan saat reservasi masih pending.');
        }

        if ($reservation->payment_status === 'paid') {
            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Reservasi ini sudah dibayar.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:transfer,cash,ewallet',
            'amount' => 'required|numeric|min:0',
        ]);

        // optional: paksa amount = deposit_amount
        $amount = (float) ($reservation->deposit_amount ?? 0);
        if ($amount <= 0) {
            $amount = (float) $validated['amount'];
        }

        Payment::create([
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => Str::upper(Str::random(12)),
        ]);

        // update reservation payment status
        $reservation->update([
            'payment_status' => 'paid',
            // opsional: auto-confirm setelah bayar
            'status' => 'confirmed',
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Pembayaran berhasil. Reservasi terkonfirmasi.');
    }
}
