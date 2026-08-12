<?php

namespace App\Exports;

use App\Models\JadwalSidang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanNilaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelompok;

    public function __construct($kelompok = null)
    {
        $this->kelompok = $kelompok;
    }

    public function collection()
    {
        $query = JadwalSidang::with(['mahasiswa.pembimbing1', 'mahasiswa.pembimbing2', 'nilaiSidangs.dosen']);
        if ($this->kelompok) {
            $query->where('kelompok_ujian', $this->kelompok);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kelompok Ujian',
            'NIM',
            'Nama Mahasiswa',
            'Waktu Sidang',
            'Ruangan',
            'Pembimbing 1',
            'Nilai Pembimbing 1',
            'Pembimbing 2',
            'Nilai Pembimbing 2',
            'Penguji 1',
            'Nilai Penguji 1',
            'Penguji 2',
            'Nilai Penguji 2',
            'Penguji 3',
            'Nilai Penguji 3',
            'Penguji 4',
            'Nilai Penguji 4',
            'Rata-rata Nilai Akhir',
        ];
    }

    public function map($jadwal): array
    {
        $mhs = $jadwal->mahasiswa;
        $nilaiSidangs = $jadwal->nilaiSidangs;
        
        // Helper function to get score for a specific dosen_id
        $getNilai = function($dosenId) use ($nilaiSidangs) {
            if (!$dosenId) return '-';
            $ns = $nilaiSidangs->firstWhere('dosen_id', $dosenId);
            return ($ns && $ns->nilai !== null) ? $ns->nilai : '-';
        };

        // Pembimbing 1 & 2
        $p1Nama = $mhs->pembimbing1->nama ?? '-';
        $p1Nilai = $mhs->pembimbing_1_id ? $getNilai($mhs->pembimbing_1_id) : '-';

        $p2Nama = $mhs->pembimbing2->nama ?? '-';
        $p2Nilai = $mhs->pembimbing_2_id ? $getNilai($mhs->pembimbing_2_id) : '-';

        // Penguji lain (selain pembimbing 1 & 2)
        $pengujis = $nilaiSidangs->filter(function($ns) use ($mhs) {
            return $ns->dosen_id != $mhs->pembimbing_1_id && $ns->dosen_id != $mhs->pembimbing_2_id;
        })->values();

        $pengujiCols = [];
        for ($i = 0; $i < 4; $i++) {
            if (isset($pengujis[$i])) {
                $pengujiCols[] = $pengujis[$i]->dosen->nama;
                $pengujiCols[] = $pengujis[$i]->nilai !== null ? $pengujis[$i]->nilai : '-';
            } else {
                $pengujiCols[] = '-';
                $pengujiCols[] = '-';
            }
        }

        // Calculate average of ALL scores submitted for this schedule
        $totalNilai = 0;
        $jumlahPenilai = 0;
        foreach ($nilaiSidangs as $ns) {
            if ($ns->nilai !== null) {
                $totalNilai += $ns->nilai;
                $jumlahPenilai++;
            }
        }
        $rataRata = $jumlahPenilai > 0 ? number_format($totalNilai / $jumlahPenilai, 2) : '-';

        return array_merge([
            $jadwal->kelompok_ujian ?? '-',
            $mhs->nim,
            $mhs->nama,
            $jadwal->waktu_sidang ? $jadwal->waktu_sidang->format('d-m-Y H:i') : '-',
            $jadwal->ruangan ?? '-',
            $p1Nama,
            $p1Nilai,
            $p2Nama,
            $p2Nilai,
        ], $pengujiCols, [$rataRata]);
    }
}
