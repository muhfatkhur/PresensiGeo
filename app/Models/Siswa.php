<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'kelas',
        'no_wa_wali',
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'siswa_id');
    }
}
