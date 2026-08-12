<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\NilaiSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|integer|min:1|max:100',
        ]);

        $dosen = Auth::user()->dosen;
        $nilaiSidang = NilaiSidang::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        $nilaiSidang->update([
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
