<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // form upload bukti tf untuk 1 reservasi
    public function create(Reservation $reservation)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Support both a model method isAdmin() or common attributes like is_admin/role
        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : (!empty($user->is_admin) || (isset($user->role) && $user->role === 'admin'));

        if (!$isAdmin && $reservation->user_id !== $user->id) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Pembayaran hanya untuk reservasi dengan status pending.');
        }

        // ambil payment terakhir (kalau sudah pernah upload)
        $payment = $reservation->payment;

        return view('payments.create', compact('reservation', 'payment'));
    }

    // simpan / update bukti transfer
    public function store(Request $request, Reservation $reservation)
    {
        if (!Auth::user()->isAdmin() && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Pembayaran hanya untuk reservasi dengan status pending.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:transfer,cash,ewallet',
            'amount' => 'required|numeric|min:0',
            'proof_image' => 'required|image|max:3072', // 3MB
        ]);

        // paksa amount = deposit_amount kalau di-set
        $amount = (float) ($reservation->deposit_amount ?? $validated['amount']);

        // jika sudah ada payment, update; kalau belum, buat baru
        $payment = $reservation->payment ?: new Payment(['user_id' => $reservation->user_id]);

        // hapus bukti lama kalau ada
        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $path = $request->file('proof_image')->store('payments', 'public');

        $payment->fill([
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'status' => 'pending', // menunggu verifikasi admin
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $payment->transaction_id ?: Str::upper(Str::random(12)),
            'proof_image' => $path,
        ]);

        $payment->save();

        // CATATAN: TIDAK mengubah reservation->payment_status, tetap 'unpaid'
        // dan reservation->status tetap 'pending'

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.');
    }

    // ADMIN: halaman verifikasi pembayaran
    public function verifyForm(Reservation $reservation)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = $reservation->payment;

        if (!$payment) {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Belum ada data pembayaran untuk reservasi ini.');
        }

        return view('payments.verify', compact('reservation', 'payment'));
    }

    // ADMIN: proses verifikasi (set paid / rejected)
    public function verify(Request $request, Reservation $reservation)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = $reservation->payment;

        if (!$payment) {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Belum ada data pembayaran untuk reservasi ini.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($validated['action'] === 'approve') {
            $payment->update([
                'status' => 'completed',
            ]);

            $reservation->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            $msg = 'Pembayaran disetujui. Reservasi dikonfirmasi.';
        } else {
            $payment->update([
                'status' => 'failed',
            ]);

            // opsional: tetap pending / bisa dibatalkan admin
            $reservation->update([
                'payment_status' => 'unpaid',
            ]);

            $msg = 'Pembayaran ditolak. Silakan hubungi pelanggan.';
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('success', $msg);
    }
}
