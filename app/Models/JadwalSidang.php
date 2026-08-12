<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSidang extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'kelompok_ujian',
        'waktu_sidang',
        'ruangan'
    ];

    protected $casts = [
        'waktu_sidang' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function nilaiSidangs()
    {
        return $this->hasMany(NilaiSidang::class);
    }
}
