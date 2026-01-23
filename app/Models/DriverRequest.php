<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DriverRequest extends Model
{
     protected $fillable = [
        'user_id',
        'license_number',
        'id_card_path',
        'driver_license_path',
        'insurance_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
