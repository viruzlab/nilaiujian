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

    public function getNilaiPembimbing()
    {
        $mhs = $this->mahasiswa;
        if (!$mhs) return null;
        
        $p1 = $mhs->pembimbing_1_id;
        $p2 = $mhs->pembimbing_2_id;
        
        $total = 0;
        $count = 0;
        
        if ($p1) {
            $ns1 = $this->nilaiSidangs->firstWhere('dosen_id', $p1);
            if ($ns1 && $ns1->nilai !== null) {
                $total += $ns1->nilai;
                $count++;
            }
        }
        
        if ($p2) {
            $ns2 = $this->nilaiSidangs->firstWhere('dosen_id', $p2);
            if ($ns2 && $ns2->nilai !== null) {
                $total += $ns2->nilai;
                $count++;
            }
        }
        
        return $count > 0 ? $total / $count : null;
    }

    public function getNilaiPenguji()
    {
        $mhs = $this->mahasiswa;
        if (!$mhs) return null;
        
        $p1 = $mhs->pembimbing_1_id;
        $p2 = $mhs->pembimbing_2_id;
        
        $total = 0;
        $count = 0;
        
        foreach ($this->nilaiSidangs as $ns) {
            if ($ns->dosen_id != $p1 && $ns->dosen_id != $p2 && $ns->nilai !== null) {
                $total += $ns->nilai;
                $count++;
            }
        }
        
        return $count > 0 ? $total / $count : null;
    }

    public function getNilaiSidangAkhir()
    {
        $nPembimbing = $this->getNilaiPembimbing();
        $nPenguji = $this->getNilaiPenguji();
        
        if ($nPembimbing === null && $nPenguji === null) return null;
        
        $npb = $nPembimbing ?? 0;
        $npu = $nPenguji ?? 0;
        
        if ($nPembimbing !== null && $nPenguji !== null) {
            return ($npb + ($npu * 2)) / 3;
        } elseif ($nPembimbing !== null) {
            return $npb;
        } elseif ($nPenguji !== null) {
            return $npu; // Or should we wait until both are filled? Returning partial is fine for preview.
        }
        
        return null;
    }

    public function isNilaiLengkap()
    {
        if ($this->nilaiSidangs->isEmpty()) return false;
        
        foreach ($this->nilaiSidangs as $ns) {
            if ($ns->nilai === null) {
                return false;
            }
        }
        return true;
    }

    public static function konversiHuruf($nilai)
    {
        if ($nilai === null || $nilai === '-') return '-';
        $val = (float)$nilai;
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

    public static function konversiBobot($nilai)
    {
        if ($nilai === null || $nilai === '-') return '-';
        $val = (float)$nilai;
        if ($val >= 92) return 4.0;
        if ($val >= 86) return 3.7;
        if ($val >= 81) return 3.4;
        if ($val >= 76) return 3.0;
        if ($val >= 71) return 2.7;
        if ($val >= 66) return 2.4;
        if ($val >= 60) return 2.0;
        if ($val >= 55) return 1.0;
        return 0.0;
    }
}
