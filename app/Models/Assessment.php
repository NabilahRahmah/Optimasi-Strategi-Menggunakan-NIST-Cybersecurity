<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'assessment_id';

    protected $fillable = [
        'user_id',
        'framework_id',
        'judul_assessment',
        'tgl_pelaksanaan',
        'status',
    ];

    protected $casts = [
        'tgl_pelaksanaan' => 'date',
    ];

    // ── Scopes ──────────────────────────────
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }

    // ── Relasi ──────────────────────────────    

    public function framework(): BelongsTo
    {
        return $this->belongsTo(Framework::class, 'framework_id', 'framework_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'user_id');
    }

    public function jawabans(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'assessment_id', 'assessment_id');
    }

    public function hasils(): HasMany
    {
        return $this->hasMany(Hasil::class, 'assessment_id', 'assessment_id');
    }

    public function rekomendasis(): HasMany
    {
        return $this->hasMany(Rekomendasi::class, 'assessment_id', 'assessment_id');
    }


    // ── Helpers ─────────────────────────────
    public function isComplete(): bool
    {
        $totalKategori = Kategori::count();
        $totalDiisi = $this->jawabans()->whereNotNull('indeks_nilai')->count();
        return $totalDiisi >= $totalKategori;
    }

    public function getPersentaseKelengkapan(): float
    {
        $totalKategori = Kategori::count();
        if ($totalKategori === 0)
            return 0;

        $totalDiisi = $this->jawabans()->whereNotNull('indeks_nilai')->count();
        return round(($totalDiisi / $totalKategori) * 100, 1);
    }
}