<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MahasiswaImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        Log::info("Mulai import Excel. Total baris dibaca: " . $rows->count());

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            Log::info("Membaca baris ke-{$index}: " . json_encode($row->toArray()));

            $nim = null;
            $nama = null;
            $judul = null;
            $pembimbingRaw = null;

            // Cari kolom yang mengandung NAMA/NIM (ada teks dan angka 5-10 digit)
            foreach ($row as $colIndex => $cellValue) {
                $cellStr = (string)$cellValue;
                // Skip header strings
                if (Str::contains(strtolower($cellStr), 'nama') || Str::contains(strtolower($cellStr), 'nim') || $cellStr === 'II') {
                    continue;
                }
                
                // Cari angka NIM
                if (preg_match('/([0-9]{5,10})/', $cellStr, $matches)) {
                    // Pastikan ini bukan format tanggal/waktu (biasanya panjang teks NAMA/NIM lebih dari sekadar angka)
                    // Jika sel ini mengandung NAMA dan NIM, panjangnya pasti lebih dari NIM itu sendiri
                    if (strlen(trim($cellStr)) > strlen($matches[1])) {
                        $nim = $matches[1];
                        $namaNimRaw = $cellStr;
                        $nama = trim(str_replace([$nim, '/', "\n", "\r"], ' ', $namaNimRaw));
                        $nama = trim(preg_replace('/\s+/', ' ', $nama));
                        
                        $judul = isset($row[$colIndex + 1]) ? trim((string)$row[$colIndex + 1]) : null;
                        $pembimbingRaw = isset($row[$colIndex + 2]) ? trim((string)$row[$colIndex + 2]) : null;
                        break;
                    }
                }
            }

            if (empty($nim) || empty($nama)) {
                Log::info("Baris {$index} dilewati: NIM atau Nama kosong.");
                continue;
            }

            Log::info("Ditemukan data: NIM={$nim}, Nama={$nama}, Judul={$judul}");

            $pembimbing1Id = null;
            $pembimbing2Id = null;

            if (!empty($pembimbingRaw)) {
                $pLines = explode("\n", str_replace(["\r\n", "\r"], "\n", $pembimbingRaw));
                $pLines = array_values(array_filter($pLines, fn($line) => trim($line) !== ''));
                
                if (isset($pLines[0])) {
                    $p1Name = trim(preg_replace('/^[0-9\.\s]+/', '', $pLines[0])); 
                    $pembimbing1Id = $this->findDosenId($p1Name);
                }
                
                if (isset($pLines[1])) {
                    $p2Name = trim(preg_replace('/^[0-9\.\s]+/', '', $pLines[1])); 
                    $pembimbing2Id = $this->findDosenId($p2Name);
                }
            }

            try {
                Mahasiswa::updateOrCreate(
                    ['nim' => $nim],
                    [
                        'nama' => $nama,
                        'judul_skripsi' => $judul,
                        'pembimbing_1_id' => $pembimbing1Id,
                        'pembimbing_2_id' => $pembimbing2Id,
                    ]
                );
                Log::info("Berhasil menyimpan mahasiswa: {$nim}");
            } catch (\Exception $e) {
                Log::error("Failed to import Mahasiswa: " . $e->getMessage());
            }
        }
    }

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
}
