<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model {
    protected $fillable = [
        'reservation_id',
        'amount',
        'paid_at',
        'payment_folio',
        'payment_method',
        'payment_status',
        'external_reference',
    ];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

}
