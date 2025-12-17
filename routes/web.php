<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
    Route::post('/reservations/check-availability', [ReservationController::class, 'checkAvailability'])
        ->name('reservations.checkAvailability');

    // Tables (Admin only)
    Route::resource('tables', TableController::class);

    // Menus
    Route::resource('menus', MenuController::class);
});
Route::get('/reservations/{reservation}/payment', [PaymentController::class, 'create'])
    ->name('payments.create');

Route::post('/reservations/{reservation}/payment', [PaymentController::class, 'store'])
    ->name('payments.store');