<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_number',
        'capacity',
        'location',
        'status',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Check if table available for specific date & time
    public function isAvailable($dateTime, $duration = 120)
    {
        $endTime = \Carbon\Carbon::parse($dateTime)->addMinutes($duration);

        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('reservation_date', [$dateTime, $endTime])
            ->exists();
    }

    public static function getAvailableTables($dateTime, $guests, $duration = 120)
    {
        return self::where('status', 'available')
            ->where('capacity', '>=', $guests)
            ->get()
            ->filter(function ($table) use ($dateTime, $duration) {
                return $table->isAvailable($dateTime, $duration);
            });
    }
}
