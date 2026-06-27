<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentJawaban extends Model
{
    protected $table = 'assessment_jawabans';
    protected $primaryKey = 'jawaban_id';
    
    public function getRouteKeyName(): string
    {
        return 'jawaban_id';
    }

    protected $fillable = [
        'assessment_id',
        'pertanyaan_id',   
        'indeks_nilai',
        'file_bukti',
        'nama_file_asli',
        'ukuran_file',
        'status_verifikasi',
        'komentar_approver',
        'direvisi_at',
    ];

    protected $casts = [
        'indeks_nilai' => 'integer',
        'ukuran_file' => 'array',
        'file_bukti' => 'array',
        'nama_file_asli' => 'array',
        'direvisi_at'   => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'assessment_id');
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'pertanyaan_id');
    }

    // Shortcut: akses kategori NIST via pertanyaan
    public function getKategoriAttribute()
    {
        return $this->pertanyaan?->kategori;
    }

    public function getRekomendasiOtomatis(): ?string
    {
        if (is_null($this->indeks_nilai))
            return null;
        return $this->kategori?->getRekomendasiOtomatis($this->indeks_nilai);
    }
}