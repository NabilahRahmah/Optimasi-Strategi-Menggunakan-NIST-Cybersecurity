<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $primaryKey = 'verifikasi_id';
    protected $fillable   = [
        'assessment_id',
        'user_id',
        'jenis_verifikasi',
        'komentar',
        'tgl_verif'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}