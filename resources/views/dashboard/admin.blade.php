@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="mb-4">Dashboard Admin</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h6 class="card-title">Total Reservasi</h6>
                    <h3>{{ $stats['total_reservations'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h6 class="card-title">Pending</h6>
                    <h3>{{ $stats['pending_reservations'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h6 class="card-title">Confirmed</h6>
                    <h3>{{ $stats['confirmed_reservations'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-dark mb-3">
                <div class="card-body">
                    <h6 class="card-title">Total Meja</h6>
                    <h3>{{ $stats['total_tables'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Reservasi Terbaru
        </div>
        <div class="card-body">
            @if($recent_reservations->isEmpty())
                <p>Belum ada reservasi.</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Meja</th>
                            <th>Tanggal</th>
                            <th>Jumlah Tamu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name ?? '-' }}</td>
                                <td>Meja {{ $reservation->table->table_number ?? '-' }}</td>
                                <td>{{ $reservation->reservation_date }}</td>
                                <td>{{ $reservation->number_of_guests }}</td>
                                <td>{{ ucfirst($reservation->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
