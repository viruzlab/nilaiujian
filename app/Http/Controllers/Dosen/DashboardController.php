<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NilaiSidang;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->withErrors(['dosen_id' => 'Data dosen tidak ditemukan.']);
        }

        // Get list of unique kelompok ujian that this dosen is assigned to
        $kelompokList = NilaiSidang::where('dosen_id', $dosen->id)
            ->whereHas('jadwalSidang', function($q) {
                $q->whereNotNull('kelompok_ujian');
            })
            ->with('jadwalSidang')
            ->get()
            ->pluck('jadwalSidang.kelompok_ujian')
            ->unique()
            ->sort()
            ->values();

        $selectedKelompok = $request->query('kelompok');

        $query = NilaiSidang::with(['jadwalSidang.mahasiswa'])
            ->where('dosen_id', $dosen->id);

        if ($selectedKelompok) {
            $query->whereHas('jadwalSidang', function($q) use ($selectedKelompok) {
                $q->where('kelompok_ujian', $selectedKelompok);
            });
        }

        $jadwals = $query->get();

        return view('dosen.dashboard', compact('jadwals', 'dosen', 'kelompokList', 'selectedKelompok'));
    }
}
