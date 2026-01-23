<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    
    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'plate_number',
        'color',
        'seats',
        'is_active',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
