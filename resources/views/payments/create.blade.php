@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <h1 class="mb-3">Upload Bukti Pembayaran</h1>

        <p>
            Reservasi #{{ $reservation->id }} - Meja {{ $reservation->table->table_number ?? '-' }}<br>
            Tanggal: {{ $reservation->reservation_date }}<br>
            Deposit: Rp {{ number_format((float)($reservation->deposit_amount ?? 0), 0, ',', '.') }}
        </p>

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

        @if(isset($payment) && $payment && $payment->proof_image)
            <div class="mb-3">
                <div class="text-muted">Bukti yang sudah diupload:</div>
                <img src="{{ asset('storage/'.$payment->proof_image) }}"
                     alt="Bukti transfer"
                     style="max-width: 300px; border-radius: 8px;">
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Upload Bukti Transfer</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store', $reservation) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="transfer" {{ old('payment_method', $payment->payment_method ?? '') === 'transfer' ? 'selected' : '' }}>
                                Transfer Bank
                            </option>
                            <option value="ewallet" {{ old('payment_method', $payment->payment_method ?? '') === 'ewallet' ? 'selected' : '' }}>
                                E-Wallet
                            </option>
                            <option value="cash" {{ old('payment_method', $payment->payment_method ?? '') === 'cash' ? 'selected' : '' }}>
                                Tunai di Tempat
                            </option>
                        </select>
                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah yang Dibayar</label>
                        <input type="number"
                               name="amount"
                               step="0.01"
                               min="0"
                               value="{{ old('amount', $reservation->deposit_amount ?? ($payment->amount ?? '')) }}"
                               class="form-control @error('amount') is-invalid @enderror"
                               required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Bukti Transfer (jpg, png, max 3MB)</label>
                        <input type="file"
                               name="proof_image"
                               accept="image/*"
                               class="form-control @error('proof_image') is-invalid @enderror"
                               required>
                        @error('proof_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="alert alert-info">
                        Setelah upload bukti, admin akan memverifikasi terlebih dahulu sebelum reservasi dikonfirmasi.
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
                    <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">
                        Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
