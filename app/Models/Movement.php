<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'details',
        'amount'
    ];

    // Aquí abajo seguro ya tienes tu relación con el usuario, déjala tal cual:
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}