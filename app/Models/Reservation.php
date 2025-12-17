<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'reservation_date',
        'number_of_guests',
        'special_request',
        'status',
        'deposit_amount',
        'payment_status',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'number_of_guests' => 'integer',
        'deposit_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function canBeCancelled()
    {
        $hoursBefore = $this->reservation_date->diffInHours(now());
        return $hoursBefore > 2 && $this->status !== 'cancelled';
    }

    public function isPast()
    {
        return $this->reservation_date < now();
    }
}
