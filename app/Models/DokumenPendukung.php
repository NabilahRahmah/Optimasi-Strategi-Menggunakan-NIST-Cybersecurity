<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPendukung extends Model
{
    protected $primaryKey = 'dok_id';

    protected $fillable = [
        'user_id',
        'jawaban_id',
        'domain_id',
        'nama_dokumen',
        'jenis_dokumen',
        'deskripsi',
        'file_path',
        'nama_file_asli',
        'ukuran_file',
        'status',
        'sumber', // 'assessment' | 'manual'
    ];

    protected $casts = [
        'ukuran_file' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'domain_id');
    }

    public function jawaban(): BelongsTo
    {
        return $this->belongsTo(AssessmentJawaban::class, 'jawaban_id', 'jawaban_id');
    }

    public function getUkuranFormatAttribute(): string
    {
        if (!$this->ukuran_file)
            return '-';
        $kb = $this->ukuran_file / 1024;
        if ($kb < 1024)
            return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }
}