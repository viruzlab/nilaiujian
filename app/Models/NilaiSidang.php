<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSidang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function getNilaiHurufAttribute()
    {
        if ($this->nilai === null) return '-';
        $val = (float)$this->nilai;
        if ($val >= 92) return 'A';
        if ($val >= 86) return 'A-';
        if ($val >= 81) return 'B+';
        if ($val >= 76) return 'B';
        if ($val >= 71) return 'B-';
        if ($val >= 66) return 'C+';
        if ($val >= 60) return 'C';
        if ($val >= 55) return 'D';
        return 'E';
    }
}
