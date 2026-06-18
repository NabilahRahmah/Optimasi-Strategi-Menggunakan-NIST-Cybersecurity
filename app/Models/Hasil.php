<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $primaryKey = 'hasil_id';
    protected $fillable   = [
        'assessment_id',
        'domain_id',
        'nilai_kematangan',
        'target_nilai',
        'gap',
        'level_kematangan'
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