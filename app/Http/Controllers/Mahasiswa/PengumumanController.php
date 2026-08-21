<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        return view('mahasiswa.pengumuman.index');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'nama' => 'required|string',
        ]);

        $nim = $request->input('nim');
        $nama = $request->input('nama');

        // Cari mahasiswa berdasarkan NIM dan potongan Nama
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('nama', 'like', '%' . $nama . '%')
            ->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan. Pastikan NIM dan Nama sudah benar.');
        }

        // Cek apakah mahasiswa memiliki jadwal sidang
        $jadwal = JadwalSidang::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$jadwal) {
            return back()->with('error', 'Anda belum memiliki jadwal sidang yudisium.');
        }

        if (!$jadwal->isNilaiLengkap()) {
            return back()->with('error', 'Pengumuman belum dapat dicetak. Status nilai Anda saat ini belum lengkap.');
        }

        return redirect()->route('mahasiswa.pengumuman.download', ['id' => $jadwal->id]);
    }

    public function download($id)
    {
        $jadwal = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])->findOrFail($id);

        $ns = $jadwal->getNilaiSidangAkhir();
        $bobot = JadwalSidang::konversiBobot($ns) ?? 0;
        $jumlahMutuMhs = floatval(optional($jadwal->mahasiswa)->jumlah_mutu ?? 0);
        $jumlahSksMhs = floatval(optional($jadwal->mahasiswa)->jumlah_sks ?? 0);
        $nilaiMutuSidang = $ns !== null ? $bobot * 6 : 0;

        $nilaiAkhirAngka = 0;
        $mutuAkhirPredikat = '-';
        $isLulus = false;

        if ($jumlahSksMhs > 0) {
            $nilaiAkhirAngka = ($nilaiMutuSidang + $jumlahMutuMhs) / $jumlahSksMhs;
            if ($nilaiAkhirAngka > 3.5 && $nilaiAkhirAngka <= 4) {
                $mhs = $jadwal->mahasiswa;
                $semester = $mhs ? $mhs->semester : null;
                $mataKuliahUlang = $mhs ? (bool) $mhs->mata_kuliah_ulang : false;
                $nilaiSidangAngka = floatval($ns);

                $syaratSemester = ($semester === null || $semester <= 8);
                
                if ($syaratSemester && !$mataKuliahUlang && $nilaiSidangAngka >= 82) {
                    $mutuAkhirPredikat = 'Pujian/Cum Laude';
                } else {
                    $mutuAkhirPredikat = 'Sangat Memuaskan';
                }
            } elseif ($nilaiAkhirAngka > 3.0 && $nilaiAkhirAngka <= 3.5) {
                $mutuAkhirPredikat = 'Sangat Memuaskan';
            } elseif ($nilaiAkhirAngka > 2.75 && $nilaiAkhirAngka <= 3.0) {
                $mutuAkhirPredikat = 'Memuaskan';
            } elseif ($nilaiAkhirAngka > 2.0 && $nilaiAkhirAngka <= 2.75) {
                $mutuAkhirPredikat = 'Tanpa Predikat Kelulusan';
            }
            $isLulus = $nilaiAkhirAngka >= 2.0;
        }

        $urutan = JadwalSidang::orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc')
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->search($jadwal->id);
            
        $nomorSurat = 355 + ($urutan !== false ? $urutan : 0);
        $lulusanKe = 655 + ($urutan !== false ? $urutan : 0);

        return view('mahasiswa.pengumuman.cetak', compact(
            'jadwal', 'nilaiAkhirAngka', 'mutuAkhirPredikat', 'isLulus', 'nomorSurat', 'lulusanKe'
        ));
    }
}
