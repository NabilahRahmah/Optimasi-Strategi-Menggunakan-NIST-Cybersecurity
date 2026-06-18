<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'domain_id',
        'kode_kategori',
        'nama_kategori',
        'deskripsi',
        'indeks_0',
        'indeks_1',
        'indeks_2',
        'indeks_3',
        'indeks_4',
        'indeks_5',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'domain_id');
    }

    public function assessmentJawabans(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'kategori_id', 'kategori_id');
    }

    // ── Helper untuk MaturityScoreService ─────────────

    public function getDeskripsiIndeks(int $indeks): ?string
    {
        if ($indeks < 0 || $indeks > 5) return null;

        return $this->{"indeks_{$indeks}"};
    }

    public function getRekomendasiOtomatis(int $indeksDicapai): ?string
    {
        $next = $indeksDicapai + 1;

        if ($next > 5) {
            return 'Sudah mencapai level tertinggi (Optimal). Pertahankan!';
        }

        return $this->{"indeks_{$next}"};
    }

        public function pertanyaans(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'kategori_id', 'kategori_id');
    }
}