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
}
