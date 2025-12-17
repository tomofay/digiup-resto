@extends('layouts.app')

@section('title', 'Kelola Meja')

@section('content')
    <h1 class="mb-4">Kelola Meja</h1>

    <div class="mb-3">
        <a href="{{ route('tables.create') }}" class="btn btn-primary">+ Tambah Meja</a>
    </div>

    @if($tables->isEmpty())
        <div class="alert alert-info">
            Belum ada data meja. Silakan tambah meja terlebih dahulu.
        </div>
    @else
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomor Meja</th>
                    <th>Kapasitas</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tables as $table)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $table->table_number }}</td>
                        <td>{{ $table->capacity }}</td>
                        <td>{{ $table->location ?? '-' }}</td>
                        <td>{{ ucfirst($table->status) }}</td>
                        <td>
                            <a href="{{ route('tables.edit', $table) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            <form action="{{ route('tables.destroy', $table) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus meja ini?')">
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

        {{ $tables->links() ?? '' }}
    @endif
@endsection
