<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $primaryKey = 'rekomendasi_id';
    protected $fillable   = [
        'assessment_id',
        'domain_id',
        'deskripsi_perbaikan',
        'prioritas',
        'sumber'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'domain_id');
    }
}