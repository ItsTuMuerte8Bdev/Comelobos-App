<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model {
    protected $fillable = [
        'shift_date',
        'start_time',
        'end_time',
        'max_capacity',
        'available_capacity',
        'status',
    ];

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

    public function consumptionLogs(): HasMany {
        return $this->hasMany(ConsumptionLog::class);
    }

}
