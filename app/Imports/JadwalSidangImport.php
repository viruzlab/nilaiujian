<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\JadwalSidang;
use App\Models\NilaiSidang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalSidangImport implements ToCollection
{
    protected $kelompokUjian;
    
    // Simpan data mentah gabungan
    protected $students = [];

    public function __construct($kelompokUjian = null)
    {
        $this->kelompokUjian = $kelompokUjian;
    }

    public function collection(Collection $rows)
    {
        Log::info("Mulai import Jadwal Sidang (Versi Merged Cells). Total baris dibaca: " . $rows->count());

        $currentNim = null;
        $colOffset = null;

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            $foundNimInRow = false;
            
            // 1. Cek apakah ini baris UTAMA (ada NIM)
            foreach ($row as $colIndex => $cellValue) {
                $cellStr = (string)$cellValue;
                if (Str::contains(strtolower($cellStr), 'nama') || Str::contains(strtolower($cellStr), 'nim') || $cellStr === 'II') {
                    continue;
                }
                
                if (preg_match('/([0-9]{5,10})/', $cellStr, $matches)) {
                    if (strlen(trim($cellStr)) > strlen($matches[1])) {
                        $currentNim = $matches[1];
                        $colOffset = $colIndex; // Ingat letak kolom NIM
                        
                        $namaNimRaw = $cellStr;
                        $nama = trim(str_replace([$currentNim, '/', "\n", "\r"], ' ', $namaNimRaw));
                        $nama = trim(preg_replace('/\s+/', ' ', $nama));
                        
                        // Inisialisasi data array untuk student baru
                        $this->students[$currentNim] = [
                            'nama' => $nama,
                            'judul' => '',
                            'pembimbing' => '',
                            'penguji' => '',
                            'waktu' => ''
                        ];
                        $foundNimInRow = true;
                        break;
                    }
                }
            }

            // 2. Ambil nilai kolom (Entah ini baris utama atau baris lanjutan / merged cell)
            if ($currentNim && $colOffset !== null) {
                $cJudul = isset($row[$colOffset + 1]) ? trim((string)$row[$colOffset + 1]) : '';
                $cPembimbing = isset($row[$colOffset + 2]) ? trim((string)$row[$colOffset + 2]) : '';
                $cPenguji = isset($row[$colOffset + 3]) ? trim((string)$row[$colOffset + 3]) : '';
                $cWaktu = isset($row[$colOffset + 4]) ? trim((string)$row[$colOffset + 4]) : '';

                if ($cJudul) $this->students[$currentNim]['judul'] .= ($this->students[$currentNim]['judul'] ? "\n" : "") . $cJudul;
                if ($cPembimbing) $this->students[$currentNim]['pembimbing'] .= ($this->students[$currentNim]['pembimbing'] ? "\n" : "") . $cPembimbing;
                if ($cPenguji) $this->students[$currentNim]['penguji'] .= ($this->students[$currentNim]['penguji'] ? "\n" : "") . $cPenguji;
                if ($cWaktu) $this->students[$currentNim]['waktu'] .= ($this->students[$currentNim]['waktu'] ? "\n" : "") . $cWaktu;
            }
        }

        // Sekarang kita memiliki array `$this->students` yang teksnya sudah 100% tergabung sempurna (meskipun di excel di-merge beda baris).
        // Jalankan logika parsing string ke database.
        $this->processStudentsData();
    }

    private function processStudentsData()
    {
        // Helper untuk mengekstrak nama berdasarkan nomor urut (1. Nama, 2. Nama)
        $extractNames = function($rawText) {
            $text = str_replace(["\r\n", "\r", "\n"], ' ', $rawText);
            // Pisahkan berdasarkan angka urut 1., 2., dst (spasi opsional)
            $parts = preg_split('/(?=\b\d+\.\s*)/', $text, -1, PREG_SPLIT_NO_EMPTY);
            $names = [];
            foreach ($parts as $part) {
                // Hapus angka urut di awal string
                $clean = trim(preg_replace('/^\d+\.\s*/', '', $part));
                if (!empty($clean)) {
                    $names[] = $clean;
                }
            }
            return $names;
        };

        foreach ($this->students as $nim => $data) {
            Log::info("Memproses Final: NIM={$nim}, Nama={$data['nama']}");
            Log::info("Raw Pembimbing: {$data['pembimbing']}");
            Log::info("Raw Penguji: {$data['penguji']}");
            Log::info("Raw Waktu: {$data['waktu']}");

            // 1. Ekstrak Pembimbing
            $pembimbing1Id = null;
            $pembimbing2Id = null;
            if (!empty($data['pembimbing'])) {
                $pLines = $extractNames($data['pembimbing']);
                if (isset($pLines[0])) $pembimbing1Id = $this->findDosenId($pLines[0]);
                if (isset($pLines[1])) $pembimbing2Id = $this->findDosenId($pLines[1]);
            }

            // 2. Simpan/Update Mahasiswa
            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $nim],
                [
                    'nama' => $data['nama'],
                    'judul_skripsi' => $data['judul'] ?: null,
                    'pembimbing_1_id' => $pembimbing1Id,
                    'pembimbing_2_id' => $pembimbing2Id,
                ]
            );

            // 3. Ekstrak Waktu & Ruangan
            $waktuSidang = null;
            $ruangan = null;
            if (!empty($data['waktu'])) {
                $wLines = explode("\n", str_replace(["\r\n", "\r"], "\n", $data['waktu']));
                $wLines = array_values(array_filter($wLines, fn($line) => trim($line) !== ''));

                if (count($wLines) >= 2) {
                    $tanggalStr = trim($wLines[0]); 
                    $jamStr = trim($wLines[1]); 
                    if (isset($wLines[2])) {
                        $ruangan = trim($wLines[2]);
                    }

                    $waktuSidang = $this->parseIndonesianDateTime($tanggalStr, $jamStr);
                } else if (count($wLines) === 1) {
                    $ruangan = trim($wLines[0]);
                }
            }

            // 4. Simpan/Update Jadwal
            $jadwal = JadwalSidang::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                [
                    'waktu_sidang' => $waktuSidang ?? now(),
                    'ruangan' => $ruangan,
                    'kelompok_ujian' => $this->kelompokUjian,
                ]
            );

            // 5. Ekstrak & Simpan Penilai (Pembimbing 1, Pembimbing 2, dan Penguji)
            $evaluatorDosenIds = [];
            if ($pembimbing1Id) $evaluatorDosenIds[] = $pembimbing1Id;
            if ($pembimbing2Id) $evaluatorDosenIds[] = $pembimbing2Id;

            if (!empty($data['penguji'])) {
                $pengujiLines = $extractNames($data['penguji']);
                foreach ($pengujiLines as $cleanPengujiName) {
                    $pengujiId = $this->findDosenId($cleanPengujiName);
                    if ($pengujiId) {
                        $evaluatorDosenIds[] = $pengujiId;
                    }
                }
            }

            $evaluatorDosenIds = array_values(array_unique($evaluatorDosenIds));

            // Hapus penilai yang sudah tidak lagi masuk di daftar
            NilaiSidang::where('jadwal_sidang_id', $jadwal->id)
                ->whereNotIn('dosen_id', $evaluatorDosenIds)
                ->delete();

            // Tambahkan NilaiSidang untuk semua penilai (Pembimbing & Penguji)
            foreach ($evaluatorDosenIds as $dosenId) {
                NilaiSidang::firstOrCreate([
                    'jadwal_sidang_id' => $jadwal->id,
                    'dosen_id' => $dosenId,
                ]);
            }
        }
    }

    /**
     * Fungsi pencarian dosen yang sangat toleran terhadap perbedaan penulisan gelar.
     * Strategi: hapus semua gelar akademik & tanda baca, lalu cocokkan "nama inti" saja.
     */
    private function findDosenId($name)
    {
        if (empty($name)) return null;

        // Tahap 1: Exact match
        $dosen = Dosen::where('nama', $name)->first();
        if ($dosen) return $dosen->id;

        // Tahap 2: Bandingkan "nama inti" (tanpa gelar) dari input vs semua dosen di DB
        $inputCore = $this->extractCoreName($name);
        Log::info("findDosenId: Input='{$name}' → Core='{$inputCore}'");

        if (empty($inputCore)) return null;

        $allDosens = Dosen::all();
        $bestMatch = null;
        $bestScore = 0;

        foreach ($allDosens as $d) {
            $dbCore = $this->extractCoreName($d->nama);

            // Exact core match
            if (strtolower($inputCore) === strtolower($dbCore)) {
                Log::info("findDosenId: MATCH (exact core) → {$d->nama}");
                return $d->id;
            }

            // Partial: cek apakah semua kata dari salah satu ada di yang lain
            $inputWords = explode(' ', strtolower($inputCore));
            $dbWords = explode(' ', strtolower($dbCore));

            // Hitung kata yang cocok
            $matchCount = count(array_intersect($inputWords, $dbWords));
            $maxWords = max(count($inputWords), count($dbWords));

            if ($maxWords > 0) {
                $score = $matchCount / $maxWords;
                // Minimal 2 kata cocok DAN skor > 50%
                if ($matchCount >= 2 && $score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $d;
                }
                // Atau jika nama inti hanya 1 kata dan cocok persis
                if (count($inputWords) === 1 && count($dbWords) === 1 && $matchCount === 1) {
                    $bestScore = 1;
                    $bestMatch = $d;
                }
            }
        }

        if ($bestMatch && $bestScore >= 0.5) {
            Log::info("findDosenId: MATCH (score={$bestScore}) '{$name}' → {$bestMatch->nama}");
            return $bestMatch->id;
        }

        Log::warning("findDosenId: NOT FOUND → '{$name}' (core: '{$inputCore}')");
        return null;
    }

    /**
     * Menghapus semua gelar akademik, tanda baca, dan menyisakan hanya nama asli.
     * Contoh: "Dr. Neni Sri Wulandari, S.Pd., M.Si." → "Neni Sri Wulandari"
     * Contoh: "Prof. Dr. A. Jajang W. Mahri, M.Si." → "A Jajang W Mahri"
     */
    private function extractCoreName($fullName)
    {
        $name = $fullName;

        // 1. Hapus gelar depan (Prof., Dr., Dra., Ir., dll)
        $name = preg_replace('/\b(Prof|Dr|Dra|Ir|Hj|H)\b\.?\s*/i', '', $name);

        // 2. Hapus gelar belakang setelah koma pertama
        if (strpos($name, ',') !== false) {
            $name = substr($name, 0, strpos($name, ','));
        }

        // 3. Hapus berbagai macam gelar belakang secara eksplisit (meski tanpa koma)
        $gelar = [
            'S\.Pd', 'M\.Pd', 'S\.E', 'M\.E', 'M\.E\.Sy', 'M\.Si', 'B\.B\.A', 'M\.Sc', 'M\.M', 'M\.A', 'Ph\.D', 'S\.T', 'M\.T', 'Sy'
        ];
        foreach ($gelar as $g) {
            $name = preg_replace('/' . str_replace('\.', '\.?\s*', $g) . '\b/i', '', $name);
        }

        // Hapus sisa format X.Yyz yang umum untuk gelar
        $name = preg_replace('/\b[A-Z]\.[A-Za-z]+\.?\b/', '', $name);
        $name = preg_replace('/\b(MM|RIFA|BA|Sy)\b\.?/i', '', $name);

        // 4. Bersihkan spasi dan tanda baca sisa
        $name = str_replace(['.', ',', '-', '(', ')'], ' ', $name);
        $name = trim(preg_replace('/\s+/', ' ', $name));

        return $name;
    }

    private function parseIndonesianDateTime($tanggalStr, $jamStr)
    {
        $tanggalStr = preg_replace('/^[a-zA-Z]+,\s*/', '', $tanggalStr); 

        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $bulanInggris = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        $tanggalStr = str_ireplace($bulanIndo, $bulanInggris, $tanggalStr);

        $jamStr = str_ireplace('pukul', '', strtolower($jamStr));
        $jamMulai = trim(explode('-', $jamStr)[0]);
        $jamMulai = str_replace('.', ':', $jamMulai); 

        try {
            return Carbon::parse($tanggalStr . ' ' . $jamMulai);
        } catch (\Exception $e) {
            Log::error("Failed to parse date: {$tanggalStr} {$jamMulai}");
            return null;
        }
    }
}
