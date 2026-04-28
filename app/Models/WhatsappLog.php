<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_log';

    protected $fillable = [
        'presensi_id',
        'no_wa',
        'pesan',
        'status',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }
}
