<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenMaster extends Model
{
    protected $primaryKey = 'master_id';

    protected $fillable = [
        'domain_id',
        'kode_kategori',
        'nama_dokumen',
        'deskripsi',
        'is_aktif',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'domain_id');
    }
}