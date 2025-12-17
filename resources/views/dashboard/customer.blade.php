@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <h1 class="mb-4">Dashboard Pelanggan</h1>

    <div class="card mb-4">
        <div class="card-header">
            Reservasi Terbaru
        </div>
        <div class="card-body">
            @if($reservations->isEmpty())
                <p>Belum ada reservasi. Silakan pesan meja.</p>
                <a href="{{ route('reservations.create') }}" class="btn btn-primary">Pesan Meja</a>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Meja</th>
                            <th>Jumlah Tamu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->reservation_date }}</td>
                                <td>Meja {{ $reservation->table->table_number ?? '-' }}</td>
                                <td>{{ $reservation->number_of_guests }}</td>
                                <td>{{ ucfirst($reservation->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                    Lihat Semua Reservasi
                </a>
            @endif
        </div>
    </div>
@endsection
