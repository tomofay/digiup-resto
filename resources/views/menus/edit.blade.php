@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div class="row">
    <div class="col-md-7">
        <h1 class="mb-4">Edit Menu</h1>

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

        <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $menu->name) }}"
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
                            {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
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
                       value="{{ old('price', $menu->price) }}"
                       class="form-control @error('price') is-invalid @enderror"
                       required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description"
                          rows="4"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Contoh: Pedas, porsi besar, dll">{{ old('description', $menu->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Menu (opsional)</label>

                @if($menu->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$menu->image) }}" alt="Menu Image"
                             style="max-width: 240px; border-radius: 8px;">
                    </div>
                @endif

                <input type="file"
                       name="image"
                       accept="image/*"
                       class="form-control @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if($menu->image)
                    <small class="text-muted">Upload gambar baru untuk mengganti gambar lama.</small>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </form>
    </div>
</div>
@endsection
