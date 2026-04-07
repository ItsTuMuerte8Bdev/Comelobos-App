<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'matriculation_number',
        'first_name',
        'last_name',
        'second_last_name',
        'phone',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function validatedConsumptionLogs(): HasMany {
        return $this->hasMany(ConsumptionLog::class, 'validated_by');
    }

    public function getFullNameAttribute(): string {
        return "{$this->first_name} {$this->last_name} {$this->second_last_name}";
    }
}
