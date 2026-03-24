<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumptionLog extends Model {
    protected $fillable = [
        'reservation_id',
        'student_id',
        'shift_id',
        'validated_by',
        'checked_in_at',
        'notes',
    ];

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }

    public function shift(): BelongsTo {
        return $this->belongsTo(Shift::class);
    }

    public function validator(): BelongsTo {
        return $this->belongsTo(User::class, 'validated_by');
    }

}
