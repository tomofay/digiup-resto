@extends('layouts.app')

@section('title', 'Ubah Reservasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Ubah Reservasi #{{ $reservation->id }}</h1>
            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">Kembali</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">Periksa kembali input:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📅 Form Ubah Reservasi</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="reservation_date" class="form-label">Tanggal & Jam Reservasi</label>
                        <input type="datetime-local"
                               id="reservation_date"
                               name="reservation_date"
                               class="form-control @error('reservation_date') is-invalid @enderror"
                               value="{{ old('reservation_date', optional($reservation->reservation_date)->format('Y-m-d\\TH:i')) }}"
                               required>
                        @error('reservation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pastikan pilih waktu yang masih tersedia.</div>
                    </div>

                    <div class="mb-3">
                        <label for="number_of_guests" class="form-label">Jumlah Tamu</label>
                        <input type="number"
                               id="number_of_guests"
                               name="number_of_guests"
                               min="1"
                               max="20"
                               class="form-control @error('number_of_guests') is-invalid @enderror"
                               value="{{ old('number_of_guests', $reservation->number_of_guests) }}"
                               required>
                        @error('number_of_guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="special_request" class="form-label">Permintaan Khusus</label>
                        <textarea id="special_request"
                                  name="special_request"
                                  class="form-control @error('special_request') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Contoh: Non-smoking, dekat jendela, dll">{{ old('special_request', $reservation->special_request) }}</textarea>
                        @error('special_request')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <strong>Catatan:</strong> Reservasi hanya bisa diubah maksimal 2 jam sebelum jadwal (sesuai aturan sistem).
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Update Reservasi</button>
                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
