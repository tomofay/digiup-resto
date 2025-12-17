@extends('layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="row">
    <div class="col-md-7">
        <h1 class="mb-4">Tambah Menu</h1>

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

        <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number"
                       name="price"
                       step="0.01"
                       min="0"
                       value="{{ old('price') }}"
                       class="form-control @error('price') is-invalid @enderror"
                       required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description"
                          rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Contoh: Pedas, porsi besar, dll">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Menu (opsional)</label>
                <input type="file"
                       name="image"
                       accept="image/*"
                       class="form-control @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih Status --</option>
                    <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>
                        Available
                    </option>
                    <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>
                        Unavailable
                    </option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-secondary" href="{{ route('menus.index') }}">Kembali</a>
        </form>
    </div>
</div>
@endsection
