@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
    <h1 class="mb-4">Kelola Menu</h1>

    <div class="mb-3">
        <a href="{{ route('menus.create') }}" class="btn btn-primary">+ Tambah Menu</a>
    </div>

    @if($menus->isEmpty())
        <div class="alert alert-info">
            Belum ada menu. Silakan tambah menu terlebih dahulu.
        </div>
    @else
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $menu->name }}</td>
                        <td>{{ $menu->category->name ?? '-' }}</td>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($menu->status) }}</td>
                        <td>
                            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('menus.destroy', $menu) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- kalau pagination tampil --}}
        {{ method_exists($menus, 'links') ? $menus->links() : '' }}
    @endif
@endsection
