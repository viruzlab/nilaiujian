<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'judul_skripsi',
        'pembimbing_1_id',
        'pembimbing_2_id',
        'jumlah_mutu',
        'jumlah_sks',
        'mata_kuliah_ulang',
        'semester',
        'ipk'
    ];

    public function jadwalSidangs()
    {
        return $this->hasMany(JadwalSidang::class);
    }

    public function pembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_1_id');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_2_id');
    }
}
