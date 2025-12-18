@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 class="mb-3">Verifikasi Pembayaran Reservasi #{{ $reservation->id }}</h1>

        <div class="mb-3">
            <h5>Data Reservasi</h5>
            <p>
                Pelanggan: {{ $reservation->user->name ?? '-' }} ({{ $reservation->user->email ?? '-' }})<br>
                Meja: {{ $reservation->table->table_number ?? '-' }}<br>
                Tanggal: {{ $reservation->reservation_date }}<br>
                Status Reservasi: {{ ucfirst($reservation->status) }}<br>
                Status Pembayaran: {{ strtoupper($reservation->payment_status) }}
            </p>
        </div>

        <div class="mb-3">
            <h5>Data Pembayaran</h5>
            <p>
                Metode: {{ strtoupper($payment->payment_method) }}<br>
                Jumlah: Rp {{ number_format((float)$payment->amount, 0, ',', '.') }}<br>
                Status Payment: {{ ucfirst($payment->status) }}<br>
                Kode Transaksi: {{ $payment->transaction_id }}
            </p>
        </div>

        <div class="mb-3">
            <h5>Bukti Transfer</h5>
            @if($payment->proof_image)
                <img src="{{ asset('storage/'.$payment->proof_image) }}"
                     alt="Bukti transfer"
                     style="max-width: 400px; border-radius: 8px;">
            @else
                <p class="text-muted">Belum ada bukti.</p>
            @endif
        </div>

        <form action="{{ route('payments.verify', $reservation) }}" method="POST" class="d-flex gap-2">
            @csrf
            <button type="submit" name="action" value="approve" class="btn btn-success">
                Setujui & Tandai Lunas
            </button>
            <button type="submit" name="action" value="reject" class="btn btn-danger"
                    onclick="return confirm('Tolak pembayaran ini?')">
                Tolak Pembayaran
            </button>
            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection
