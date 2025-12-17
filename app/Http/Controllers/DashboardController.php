<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->customerDashboard();
        }
    }


    private function adminDashboard()
    {
        $stats = [
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'total_tables' => Table::count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $recent_reservations = Reservation::with(['user', 'table'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_reservations'));
    }

    private function customerDashboard()
    {
        $reservations = Auth::user()->reservations()
            ->with('table')
            ->orderBy('reservation_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.customer', compact('reservations'));
    }
}
