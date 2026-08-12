<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\JadwalSidang;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDosen = Dosen::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalJadwal = JadwalSidang::count();

        // 1. Jumlah mahasiswa yang ujian (Total Jadwal)
        $jumlahUjian = $totalJadwal;

        // 2. Jumlah mahasiswa yang sudah diberi nilai (Semua penguji sudah memberi nilai)
        $jadwalSelesaiDinilai = JadwalSidang::whereHas('nilaiSidangs')
            ->whereDoesntHave('nilaiSidangs', function($query) {
                $query->whereNull('nilai');
            })->count();

        // 3. Jumlah/Presentase pengisian nilai
        $totalTugasMenilai = \App\Models\NilaiSidang::count();
        $tugasSelesai = \App\Models\NilaiSidang::whereNotNull('nilai')->count();
        $persentaseNilai = $totalTugasMenilai > 0 ? round(($tugasSelesai / $totalTugasMenilai) * 100) : 0;

        return view('admin.dashboard', compact(
            'totalDosen', 'totalMahasiswa', 'totalJadwal',
            'jumlahUjian', 'jadwalSelesaiDinilai', 'totalTugasMenilai', 'tugasSelesai', 'persentaseNilai'
        ));
    }

    public function importJadwal(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
            'kelompok_ujian' => 'required|string|max:255',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\JadwalSidangImport($request->kelompok_ujian), $request->file('file'));
            return redirect()->route('admin.jadwal.index', ['kelompok' => $request->kelompok_ujian])->with('success', 'Jadwal Sidang Kelompok "' . $request->kelompok_ujian . '" beserta data Mahasiswa, Pembimbing, dan Penguji berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->withErrors(['file' => 'Gagal mengimpor file: ' . $e->getMessage()]);
        }
    }
}
