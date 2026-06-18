<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $primaryKey = 'pertanyaan_id';

    protected $fillable = [
        'kategori_id',
        'kode_pertanyaan',
        'judul',
        'deskripsi',
        'indeks_0', 'indeks_1', 'indeks_2',
        'indeks_3', 'indeks_4', 'indeks_5',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function jawabans()
    {
        return $this->hasMany(AssessmentJawaban::class, 'pertanyaan_id', 'pertanyaan_id');
    }
}