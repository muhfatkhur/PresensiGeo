<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geofencing extends Model
{
    protected $table = 'geofencing';

    protected $fillable = [
        'latitude',
        'longitude',
        'radius_meter',
        'batas_tepat_waktu',
        'is_open'
    ];
}