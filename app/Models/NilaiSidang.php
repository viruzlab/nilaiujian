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

    public function getNilaiAngkaAttribute()
    {
        if ($this->nilai === null) return '-';
        $val = (float)$this->nilai;
        if ($val >= 92) return '4,0';
        if ($val >= 86) return '3,7';
        if ($val >= 81) return '3,4';
        if ($val >= 76) return '3,0';
        if ($val >= 71) return '2,7';
        if ($val >= 66) return '2,4';
        if ($val >= 60) return '2,0';
        if ($val >= 55) return '1,0';
        return '<1,0';
    }

    public function getDerajatMutuAttribute()
    {
        if ($this->nilai === null) return '-';
        $val = (float)$this->nilai;
        if ($val >= 92) return 'Istimewa';
        if ($val >= 86) return 'Hampir Istimewa';
        if ($val >= 81) return 'Baik Sekali';
        if ($val >= 76) return 'Baik';
        if ($val >= 71) return 'Cukup Baik';
        if ($val >= 66) return 'Lebih dari Cukup';
        if ($val >= 60) return 'Cukup';
        if ($val >= 55) return 'Kurang';
        return 'Gagal';
    }
}
