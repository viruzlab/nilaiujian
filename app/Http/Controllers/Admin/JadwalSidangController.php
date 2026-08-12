<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use App\Models\NilaiSidang;
use Illuminate\Http\Request;

class JadwalSidangController extends Controller
{
    public function index(Request $request)
    {
        $kelompokList = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');

        $selectedKelompok = $request->query('kelompok');

        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen']);

        if ($selectedKelompok) {
            $query->where('kelompok_ujian', $selectedKelompok);
        }

        $jadwals = $query->get();

        return view('admin.jadwal.index', compact('jadwals', 'kelompokList', 'selectedKelompok'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::all();
        $dosens = Dosen::all();
        return view('admin.jadwal.create', compact('mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'waktu_sidang' => 'required|date',
            'ruangan' => 'nullable|string|max:255',
            'dosen_ids' => 'required|array|min:1',
            'dosen_ids.*' => 'exists:dosens,id',
        ]);

        $jadwal = JadwalSidang::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'waktu_sidang' => $request->waktu_sidang,
            'ruangan' => $request->ruangan,
        ]);

        // Create NilaiSidang record for selected dosen penguji AND pembimbing 1 & 2
        $dosenIds = $request->dosen_ids;
        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($mhs && $mhs->pembimbing_1_id) $dosenIds[] = $mhs->pembimbing_1_id;
        if ($mhs && $mhs->pembimbing_2_id) $dosenIds[] = $mhs->pembimbing_2_id;
        $dosenIds = array_values(array_unique($dosenIds));

        foreach ($dosenIds as $dosenId) {
            NilaiSidang::firstOrCreate([
                'jadwal_sidang_id' => $jadwal->id,
                'dosen_id' => $dosenId,
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil dibuat!');
    }

    public function edit(JadwalSidang $jadwal)
    {
        $mahasiswas = Mahasiswa::all();
        $dosens = Dosen::all();
        
        $mhs = $jadwal->mahasiswa;
        $pembimbingIds = array_filter([$mhs->pembimbing_1_id, $mhs->pembimbing_2_id]);
        
        $selectedPengujiIds = $jadwal->nilaiSidangs()
            ->whereNotIn('dosen_id', $pembimbingIds)
            ->pluck('dosen_id')
            ->toArray();

        return view('admin.jadwal.edit', compact('jadwal', 'mahasiswas', 'dosens', 'selectedPengujiIds'));
    }

    public function update(Request $request, JadwalSidang $jadwal)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'waktu_sidang' => 'required|date',
            'ruangan' => 'nullable|string|max:255',
            'dosen_ids' => 'required|array|min:1',
            'dosen_ids.*' => 'exists:dosens,id',
        ]);

        $jadwal->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'waktu_sidang' => $request->waktu_sidang,
            'ruangan' => $request->ruangan,
        ]);

        // Sync NilaiSidang
        $dosenIds = $request->dosen_ids;
        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($mhs && $mhs->pembimbing_1_id) $dosenIds[] = $mhs->pembimbing_1_id;
        if ($mhs && $mhs->pembimbing_2_id) $dosenIds[] = $mhs->pembimbing_2_id;
        $dosenIds = array_values(array_unique($dosenIds));

        // Delete NilaiSidang records for dosens not in the new list
        $jadwal->nilaiSidangs()->whereNotIn('dosen_id', $dosenIds)->delete();

        // Create new ones if they don't exist
        foreach ($dosenIds as $dosenId) {
            NilaiSidang::firstOrCreate([
                'jadwal_sidang_id' => $jadwal->id,
                'dosen_id' => $dosenId,
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil diperbarui!');
    }

    public function destroy(JadwalSidang $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil dihapus!');
    }

    public function rekap(Request $request)
    {
        $kelompokList = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');

        $selectedKelompok = $request->query('kelompok');

        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen']);

        if ($selectedKelompok) {
            $query->where('kelompok_ujian', $selectedKelompok);
        }

        $jadwals = $query->get();

        return view('admin.jadwal.rekap', compact('jadwals', 'kelompokList', 'selectedKelompok'));
    }

    public function downloadLaporan(Request $request)
    {
        $kelompok = $request->query('kelompok');
        $fileName = $kelompok ? "Laporan_Nilai_Kelompok_{$kelompok}.xlsx" : "Laporan_Nilai_Semua.xlsx";
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanNilaiExport($kelompok), $fileName);
    }
}
