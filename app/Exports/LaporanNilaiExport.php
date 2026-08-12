<?php

namespace App\Exports;

use App\Models\JadwalSidang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanNilaiExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithStyles
{
    protected $kelompok;
    protected $rowNumber = 0;

    public function __construct($kelompok = null)
    {
        $this->kelompok = $kelompok;
    }

    public function collection()
    {
        $query = JadwalSidang::with(['mahasiswa', 'nilaiSidangs']);
        if ($this->kelompok) {
            $query->where('kelompok_ujian', $this->kelompok);
        }
        return $query->get();
    }

    public function headings(): array
    {
        return [
            [
                'No',
                'Nama Mahasiswa',
                'Nilai Pembimbing', '',
                'Nilai Penguji', '',
                'Nilai Sidang', '', '',
                'IPK Terakhir', 'Jumlah Mutu', 'Jumlah SKS', 'Mata Kuliah Ulang', 'Semester',
                'Nilai Akhir', 'Mutu Akhir'
            ],
            [
                '', // No
                '', // Nama
                'Nilai', 'Mutu',
                'Nilai', 'Mutu',
                'Nilai', 'Mutu', 'Nilai Mutu',
                '', '', '', '', '', '', ''
            ]
        ];
    }

    public function map($jadwal): array
    {
        $this->rowNumber++;
        $mhs = $jadwal->mahasiswa;

        $npb = $jadwal->getNilaiPembimbing();
        $npbFormat = $npb !== null ? number_format($npb, 2) : '-';
        $npbHuruf = JadwalSidang::konversiHuruf($npb);
        
        $npu = $jadwal->getNilaiPenguji();
        $npuFormat = $npu !== null ? number_format($npu, 2) : '-';
        $npuHuruf = JadwalSidang::konversiHuruf($npu);
        
        $ns = $jadwal->getNilaiSidangAkhir();
        $nsFormat = $ns !== null ? number_format($ns, 2) : '-';
        $nsHuruf = JadwalSidang::konversiHuruf($ns);
        
        $bobot = JadwalSidang::konversiBobot($ns);
        $nilaiMutu = $ns !== null ? number_format($bobot * 6, 2) : '-';

        $jumlahMutuMhs = floatval($mhs->jumlah_mutu ?? 0);
        $jumlahSksMhs = floatval($mhs->jumlah_sks ?? 0);
        $nilaiMutuSidang = $ns !== null ? ($bobot * 6) : 0;
        $nilaiAkhirAngka = 0;
        $mutuAkhirPredikat = '-';
        if ($jumlahSksMhs > 0) {
            $nilaiAkhirAngka = ($nilaiMutuSidang + $jumlahMutuMhs) / $jumlahSksMhs;
            if ($nilaiAkhirAngka > 3.5 && $nilaiAkhirAngka <= 4) {
                $mutuAkhirPredikat = 'Pujian/cum laude';
            } elseif ($nilaiAkhirAngka > 3.0 && $nilaiAkhirAngka <= 3.5) {
                $mutuAkhirPredikat = 'Sangat memuaskan';
            } elseif ($nilaiAkhirAngka > 2.75 && $nilaiAkhirAngka <= 3.0) {
                $mutuAkhirPredikat = 'Memuaskan';
            } elseif ($nilaiAkhirAngka > 2.0 && $nilaiAkhirAngka <= 2.75) {
                $mutuAkhirPredikat = 'Tanpa predikat kelulusan';
            }
        }
        $nilaiAkhirFormat = $jumlahSksMhs > 0 ? number_format($nilaiAkhirAngka, 2) : '-';

        return [
            $this->rowNumber,
            $mhs->nama ?? '-',
            $npbFormat,
            $npbHuruf,
            $npuFormat,
            $npuHuruf,
            $nsFormat,
            $nsHuruf,
            $nilaiMutu,
            $mhs->ipk ?? '-',
            $mhs->jumlah_mutu ?? '-',
            $mhs->jumlah_sks ?? '-',
            $mhs->mata_kuliah_ulang ?? '0',
            $mhs->semester ?? '-',
            $nilaiAkhirFormat,
            $mutuAkhirPredikat
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge Cells
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:D1');
                $sheet->mergeCells('E1:F1');
                $sheet->mergeCells('G1:I1');
                $sheet->mergeCells('J1:J2');
                $sheet->mergeCells('K1:K2');
                $sheet->mergeCells('L1:L2');
                $sheet->mergeCells('M1:M2');
                $sheet->mergeCells('N1:N2');
                $sheet->mergeCells('O1:O2');
                $sheet->mergeCells('P1:P2');
                
                // Header Styling
                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FCD5B4'] 
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $sheet->getStyle('A1:P2')->applyFromArray($headerStyle);

                // Body borders and center alignment
                $highestRow = $sheet->getHighestRow();
                if ($highestRow > 2) {
                    $sheet->getStyle('A3:P' . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $sheet->getStyle('A3:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C3:P' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
