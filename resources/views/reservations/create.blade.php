@extends('layouts.app')

@section('title', 'Pesan Meja')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📅 Pesan Meja Restoran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="reservation_date" class="form-label">Tanggal & Jam Reservasi</label>
                        <input type="datetime-local" 
                               id="reservation_date" 
                               name="reservation_date" 
                               class="form-control @error('reservation_date') is-invalid @enderror"
                               required>
                        @error('reservation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="number_of_guests" class="form-label">Jumlah Tamu</label>
                        <input type="number" 
                               id="number_of_guests" 
                               name="number_of_guests" 
                               min="1" 
                               max="20"
                               class="form-control @error('number_of_guests') is-invalid @enderror"
                               required>
                        @error('number_of_guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="special_request" class="form-label">Permintaan Khusus</label>
                        <textarea id="special_request" 
                                  name="special_request" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="Contoh: Non-smoking, dekat jendela, dll"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <strong>💡 Catatan:</strong> Deposit sebesar Rp 100.000 dibutuhkan untuk mengkonfirmasi reservasi.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Pesan Meja</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
