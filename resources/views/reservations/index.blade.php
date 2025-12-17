@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Semua Reservasi' : 'Reservasiku')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">
            {{ auth()->user()->isAdmin() ? 'Semua Reservasi' : 'Reservasiku' }}
        </h1>

        @if(!auth()->user()->isAdmin())
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                + Pesan Meja
            </a>
        @endif
    </div>

    {{-- Filter status (khusus admin) --}}
    @if(auth()->user()->isAdmin())
        <form method="GET" action="{{ route('reservations.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    @foreach(['pending','confirmed','cancelled','completed'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary" type="submit">Filter</button>
                <a class="btn btn-outline-secondary" href="{{ route('reservations.index') }}">Reset</a>
            </div>
        </form>
    @endif

    @if($reservations->isEmpty())
        <div class="alert alert-info">
            {{ auth()->user()->isAdmin() ? 'Belum ada data reservasi.' : 'Kamu belum punya reservasi.' }}
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                @if(auth()->user()->isAdmin())
                                    <th>Pelanggan</th>
                                    <th>Email</th>
                                @endif
                                <th>Meja</th>
                                <th>Tanggal & Jam</th>
                                <th>Jumlah Tamu</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    @if(auth()->user()->isAdmin())
                                        <td>{{ $reservation->user->name ?? '-' }}</td>
                                        <td>{{ $reservation->user->email ?? '-' }}</td>
                                    @endif

                                    <td>Meja {{ $reservation->table->table_number ?? '-' }}</td>
                                    <td>{{ $reservation->reservation_date }}</td>
                                    <td>{{ $reservation->number_of_guests }}</td>

                                    <td>
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
                                    </td>

                                    <td>
                                        <span class="badge text-bg-{{ $reservation->payment_status === 'paid' ? 'success' : 'danger' }}">
                                            {{ strtoupper($reservation->payment_status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="{{ route('reservations.show', $reservation) }}">
                                            Detail
                                        </a>

                                        {{-- Customer: tombol cancel (kalau kamu sudah punya route reservations.cancel) --}}
                                        @if(!auth()->user()->isAdmin())
                                            @if(method_exists($reservation, 'canBeCancelled') && $reservation->canBeCancelled())
                                                <form action="{{ route('reservations.cancel', $reservation) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Batalkan reservasi ini?')">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $reservations->links() }}
        </div>
    @endif
@endsection
