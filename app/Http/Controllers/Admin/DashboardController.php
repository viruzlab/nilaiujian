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

        return view('admin.dashboard', compact('totalDosen', 'totalMahasiswa', 'totalJadwal'));
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
