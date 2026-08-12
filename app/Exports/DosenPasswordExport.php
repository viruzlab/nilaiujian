<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DosenPasswordExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Dosen::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Dosen',
            'Email',
            'Password Akses',
        ];
    }

    public function map($dosen): array
    {
        return [
            $dosen->nama,
            $dosen->user ? $dosen->user->email : '-',
            $dosen->password_plain ?? 'Belum di-generate',
        ];
    }
}
