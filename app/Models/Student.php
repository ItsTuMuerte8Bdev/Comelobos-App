<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model {
    protected $fillable = [
        'user_id',
        'student_code',
        'first_name',
        'last_name',
        'second_last_name',
        'faculty',
        'semester',
        'alternate_email',
        'academic_status',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }

}
