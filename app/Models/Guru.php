<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'nama',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'guru_id');
    }
}
