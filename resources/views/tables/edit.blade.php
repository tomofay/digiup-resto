@extends('layouts.app')

@section('title', 'Edit Meja')

@section('content')
<div class="row">
    <div class="col-md-6">
        <h1 class="mb-4">Edit Meja</h1>

        <form action="{{ route('tables.update', $table) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nomor Meja</label>
                <input type="number" name="table_number"
                       class="form-control @error('table_number') is-invalid @enderror"
                       value="{{ old('table_number', $table->table_number) }}" required>
                @error('table_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas</label>
                <input type="number" name="capacity"
                       class="form-control @error('capacity') is-invalid @enderror"
                       value="{{ old('capacity', $table->capacity) }}" required>
                @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location"
                       class="form-control @error('location') is-invalid @enderror"
                       value="{{ old('location', $table->location) }}">
                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $table->status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="maintenance" {{ $table->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control"
                          rows="3">{{ old('description', $table->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('tables.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
