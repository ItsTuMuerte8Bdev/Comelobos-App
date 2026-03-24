<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model {
    protected $fillable = [
        'menu_date',
        'description',
        'price',
        'available_portions',
        'status',
    ];

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

}
