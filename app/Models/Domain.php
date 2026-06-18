<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DokumenPendukung;


class Domain extends Model
{
    protected $primaryKey = 'domain_id';
    protected $fillable = [
        'framework_id',
        'kode_domain',
        'nama_domain',
        ];

    public function framework()
    {
        return $this->belongsTo(Framework::class, 'framework_id', 'framework_id');
    }

    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'domain_id', 'domain_id');
    }

    public function dokumenPendukungs()
    {
        return $this->hasMany(DokumenPendukung::class, 'domain_id', 'domain_id');
    }

    public function hasils()
    {
        return $this->hasMany(Hasil::class, 'domain_id', 'domain_id');
    }

    public function rekomendasis()
    {
        return $this->hasMany(Rekomendasi::class, 'domain_id', 'domain_id');
    }
}