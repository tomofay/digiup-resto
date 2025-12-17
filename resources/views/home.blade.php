@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="text-center py-5">
        <h1 class="mb-3">Selamat datang di Sistem Reservasi Restoran</h1>
        <p class="mb-4">Pesan meja restoran dengan mudah dan cepat secara online.</p>

        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Masuk ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">Register</a>
        @endauth
    </div>
@endsection
