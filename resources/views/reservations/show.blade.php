@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Detail Reservasi #{{ $reservation->id }}</h1>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3">
                @if(auth()->user()->isAdmin())
                    <div class="col-md-6">
                        <div class="text-muted">Pelanggan</div>
                        <div class="fw-semibold">{{ $reservation->user->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted">Email</div>
                        <div class="fw-semibold">{{ $reservation->user->email ?? '-' }}</div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="text-muted">Meja</div>
                    <div class="fw-semibold">Meja {{ $reservation->table->table_number ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted">Tanggal & Jam</div>
                    <div class="fw-semibold">{{ $reservation->reservation_date }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Jumlah Tamu</div>
                    <div class="fw-semibold">{{ $reservation->number_of_guests }}</div>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Status Reservasi</div>
                    @php
                        $status = $reservation->status;
                        $badge = match ($status) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'cancelled' => 'secondary',
                            'completed' => 'primary',
                            default => 'light'
                        };
                    @endphp
                    <span class="badge text-bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                </div>

                <div class="col-md-4">
                    <div class="text-muted">Status Pembayaran</div>
                    <span class="badge text-bg-{{ $reservation->payment_status === 'paid' ? 'success' : 'danger' }}">
                        {{ strtoupper($reservation->payment_status) }}
                    </span>
                </div>

                <div class="col-12">
                    <div class="text-muted">Permintaan Khusus</div>
                    <div class="fw-semibold">
                        {{ $reservation->special_request ?: '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted">Deposit</div>
                    <div class="fw-semibold">
                        Rp {{ number_format((float)($reservation->deposit_amount ?? 0), 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted">Dibuat Pada</div>
                    <div class="fw-semibold">{{ $reservation->created_at }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Aksi Customer --}}
    @if(!auth()->user()->isAdmin())
        <div class="d-flex gap-2">
            @if(method_exists($reservation, 'canBeCancelled') && $reservation->canBeCancelled())
                <form action="{{ route('reservations.cancel', $reservation) }}"
                      method="POST"
                      onsubmit="return confirm('Batalkan reservasi ini?')">
                    @csrf
                    <button class="btn btn-outline-danger" type="submit">Cancel Reservasi</button>
                </form>
            @endif

            <a class="btn btn-outline-primary" href="{{ route('reservations.edit', $reservation) }}">
                Ubah Reservasi
            </a>
        </div>
    @endif
@endsection
