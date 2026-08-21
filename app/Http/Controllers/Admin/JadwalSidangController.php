<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\JadwalSidang;
use App\Models\Mahasiswa;
use App\Models\NilaiSidang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Support\Facades\File;

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

        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])
            ->orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc');

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
        $kelompoks = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');
            
        return view('admin.jadwal.create', compact('mahasiswas', 'dosens', 'kelompoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'waktu_sidang' => 'required|date',
            'ruangan' => 'nullable|string|max:255',
            'kelompok_ujian' => 'nullable|string|max:255',
            'dosen_ids' => 'required|array|min:1',
            'dosen_ids.*' => 'exists:dosens,id',
        ]);

        $jadwal = JadwalSidang::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'waktu_sidang' => $request->waktu_sidang,
            'ruangan' => $request->ruangan,
            'kelompok_ujian' => $request->kelompok_ujian,
        ]);

        // Create NilaiSidang record for selected dosen penguji AND pembimbing 1 & 2
        $dosenIds = $request->dosen_ids;
        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($mhs && $mhs->pembimbing_1_id) $dosenIds[] = $mhs->pembimbing_1_id;
        if ($mhs && $mhs->pembimbing_2_id) $dosenIds[] = $mhs->pembimbing_2_id;
        $dosenIds = array_values(array_unique($dosenIds));

        $urutan = 1;
        foreach ($dosenIds as $dosenId) {
            NilaiSidang::updateOrCreate([
                'jadwal_sidang_id' => $jadwal->id,
                'dosen_id' => $dosenId,
            ], [
                'urutan' => $urutan
            ]);
            $urutan++;
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil dibuat!');
    }

    public function edit(JadwalSidang $jadwal)
    {
        $mahasiswas = Mahasiswa::all();
        $dosens = Dosen::all();
        $kelompoks = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');
        
        $mhs = $jadwal->mahasiswa;
        $pembimbingIds = array_filter([$mhs->pembimbing_1_id, $mhs->pembimbing_2_id]);
        
        $selectedPengujiIds = $jadwal->nilaiSidangs()
            ->whereNotIn('dosen_id', $pembimbingIds)
            ->pluck('dosen_id')
            ->toArray();

        return view('admin.jadwal.edit', compact('jadwal', 'mahasiswas', 'dosens', 'selectedPengujiIds', 'kelompoks'));
    }

    public function update(Request $request, JadwalSidang $jadwal)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'waktu_sidang' => 'required|date',
            'ruangan' => 'nullable|string|max:255',
            'kelompok_ujian' => 'nullable|string|max:255',
            'dosen_ids' => 'required|array|min:1',
            'dosen_ids.*' => 'exists:dosens,id',
        ]);

        $jadwal->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'waktu_sidang' => $request->waktu_sidang,
            'ruangan' => $request->ruangan,
            'kelompok_ujian' => $request->kelompok_ujian,
        ]);

        // Sync NilaiSidang
        $dosenIds = $request->dosen_ids;
        $mhs = Mahasiswa::find($request->mahasiswa_id);
        if ($mhs && $mhs->pembimbing_1_id) $dosenIds[] = $mhs->pembimbing_1_id;
        if ($mhs && $mhs->pembimbing_2_id) $dosenIds[] = $mhs->pembimbing_2_id;
        $dosenIds = array_values(array_unique($dosenIds));

        // Delete NilaiSidang records for dosens not in the new list
        $jadwal->nilaiSidangs()->whereNotIn('dosen_id', $dosenIds)->delete();

        // Create new ones if they don't exist and update urutan
        $urutan = 1;
        foreach ($dosenIds as $dosenId) {
            NilaiSidang::updateOrCreate([
                'jadwal_sidang_id' => $jadwal->id,
                'dosen_id' => $dosenId,
            ], [
                'urutan' => $urutan
            ]);
            $urutan++;
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil diperbarui!');
    }

    public function destroy(JadwalSidang $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal sidang berhasil dihapus!');
    }

    public function editNilai(JadwalSidang $jadwal)
    {
        $jadwal->load(['mahasiswa', 'nilaiSidangs.dosen']);
        return view('admin.jadwal.nilai', compact('jadwal'));
    }

    public function updateNilai(Request $request, JadwalSidang $jadwal)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $nilaiSidangId => $nilaiValue) {
            $ns = NilaiSidang::where('id', $nilaiSidangId)
                ->where('jadwal_sidang_id', $jadwal->id)
                ->first();
            if ($ns) {
                // Konversi string kosong menjadi null
                $ns->update(['nilai' => $nilaiValue === '' ? null : $nilaiValue]);
            }
        }

        return redirect()->route('admin.jadwal.index', ['kelompok' => $jadwal->kelompok_ujian])->with('success', 'Nilai sidang berhasil diupdate oleh Admin!');
    }

    public function rekap(Request $request)
    {
        $kelompokList = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');

        $selectedKelompok = $request->query('kelompok');

        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])
            ->orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc');

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

    public function pengumuman(Request $request)
    {
        $kelompokList = JadwalSidang::select('kelompok_ujian')
            ->whereNotNull('kelompok_ujian')
            ->distinct()
            ->orderBy('kelompok_ujian')
            ->pluck('kelompok_ujian');

        $selectedKelompok = $request->query('kelompok');

        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])
            ->orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc');

        if ($selectedKelompok) {
            $query->where('kelompok_ujian', $selectedKelompok);
        }

        $jadwals = $query->get();

        return view('admin.jadwal.pengumuman', compact('jadwals', 'kelompokList', 'selectedKelompok'));
    }

    public function cetakYudisium(JadwalSidang $jadwal)
    {
        $jadwal->load(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen']);

        if (!$jadwal->isNilaiLengkap()) {
            return back()->with('error', 'Gagal mencetak yudisium. Terdapat dosen yang belum memasukkan nilai untuk mahasiswa ini.');
        }

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

        return view('admin.jadwal.cetak-yudisium', compact(
            'jadwal', 'nilaiAkhirAngka', 'mutuAkhirPredikat', 'isLulus', 'nomorSurat', 'lulusanKe'
        ));
    }

    public function cetakYudisiumMassal(Request $request)
    {
        $selectedKelompok = $request->query('kelompok');
        
        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])
            ->orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc');

        if ($selectedKelompok) {
            $query->where('kelompok_ujian', $selectedKelompok);
        }

        $semuaJadwals = $query->get();
        
        $jadwalsSiapCetak = [];
        
        // Cek satu-satu untuk kalkulasi dan status lengkap
        foreach ($semuaJadwals as $jadwal) {
            if ($jadwal->isNilaiLengkap()) {
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
                
                $jadwalsSiapCetak[] = [
                    'jadwal' => $jadwal,
                    'nilaiAkhirAngka' => $nilaiAkhirAngka,
                    'mutuAkhirPredikat' => $mutuAkhirPredikat,
                    'isLulus' => $isLulus,
                    'nomorSurat' => $nomorSurat,
                    'lulusanKe' => $lulusanKe,
                ];
            }
        }
        
        if (empty($jadwalsSiapCetak)) {
            return back()->with('error', 'Gagal mencetak. Tidak ada jadwal dengan nilai lengkap pada pilihan tersebut.');
        }

        return view('admin.jadwal.cetak-yudisium-massal', compact('jadwalsSiapCetak', 'selectedKelompok'));
    }

    public function cetakYudisiumMassalZip(Request $request)
    {
        $selectedKelompok = $request->query('kelompok');
        
        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen'])
            ->orderBy('kelompok_ujian', 'asc')
            ->orderBy('waktu_sidang', 'asc');

        if ($selectedKelompok) {
            $query->where('kelompok_ujian', $selectedKelompok);
        }

        $semuaJadwals = $query->get();
        
        $jadwalsSiapCetak = [];
        
        foreach ($semuaJadwals as $jadwal) {
            if ($jadwal->isNilaiLengkap()) {
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
                
                $jadwalsSiapCetak[] = [
                    'jadwal' => $jadwal,
                    'nilaiAkhirAngka' => $nilaiAkhirAngka,
                    'mutuAkhirPredikat' => $mutuAkhirPredikat,
                    'isLulus' => $isLulus,
                    'nomorSurat' => $nomorSurat,
                    'lulusanKe' => $lulusanKe,
                ];
            }
        }
        
        if (empty($jadwalsSiapCetak)) {
            return back()->with('error', 'Gagal membuat ZIP. Tidak ada jadwal dengan nilai lengkap pada pilihan tersebut.');
        }

        // Tambahan untuk server: bypass batas memory dan waktu eksekusi agar tidak 500 error
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '300');

        // Generate temporary zip file
        $zipFileName = 'Pengumuman_Yudisium_' . ($selectedKelompok ? 'Kelompok_'.$selectedKelompok : 'Semua') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);
        
        try {
            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                
                foreach ($jadwalsSiapCetak as $data) {
                    // Generate PDF in memory
                    $pdf = Pdf::loadView('admin.jadwal.cetak-yudisium-pdf', $data);
                    
                    $mhsNama = preg_replace('/[^A-Za-z0-9\-]/', '_', optional($data['jadwal']->mahasiswa)->nama ?? 'Mahasiswa');
                    $mhsNim = optional($data['jadwal']->mahasiswa)->nim ?? '0000';
                    $pdfFileName = $mhsNim . '_' . $mhsNama . '.pdf';
                    $output = $pdf->output();
                    if (empty($output)) {
                        return back()->with('error', 'Gagal memproses PDF untuk mahasiswa: ' . $mhsNama . '. Modul PDF mungkin bermasalah (coba cek logo/gambar).');
                    }
                    
                    $zip->addFromString($pdfFileName, $output);
                    
                    unset($pdf);
                    unset($output);
                }
                
                $closeResult = $zip->close();
                if (!$closeResult) {
                    return back()->with('error', 'Gagal menyimpan file ZIP di server. Izin folder tidak mencukupi.');
                }
            } else {
                return back()->with('error', 'Gagal membuka file ZIP untuk ditulis. Pastikan ekstensi zip aktif.');
            }

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            return response()->download($zipPath);
        } catch (\Throwable $e) {
            if (isset($zipPath) && file_exists($zipPath)) {
                @unlink($zipPath);
            }
            return back()->with('error', 'Terjadi kesalahan saat memproses PDF/ZIP: ' . $e->getMessage());
        }
    }
}
