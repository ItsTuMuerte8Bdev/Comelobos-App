<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model {
    protected $fillable = [
        'student_id',
        'shift_id',
        'menu_id',
        'reservation_date',
        'folio',
        'qr_code',
        'status',
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }

    public function shift(): BelongsTo {
        return $this->belongsTo(Shift::class);
    }

    public function menu(): BelongsTo {
        return $this->belongsTo(Menu::class);
    }

    public function payment(): HasOne {
        return $this->hasOne(Payment::class);
    }

    public function consumptionLog(): HasOne {
        return $this->hasOne(ConsumptionLog::class);
    }

}
