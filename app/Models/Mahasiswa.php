<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $guarded = [];

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
