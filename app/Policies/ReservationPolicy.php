<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isAdmin() || $reservation->user_id === $user->id;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $reservation->user_id === $user->id
            && $reservation->status === 'pending';
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $reservation->user_id === $user->id
            && $reservation->status === 'pending';
    }
}
