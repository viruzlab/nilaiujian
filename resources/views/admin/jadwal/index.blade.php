<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penjadwalan Sidang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold flex-1">Daftar Jadwal Sidang</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.jadwal.laporan', ['kelompok' => $selectedKelompok]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors shadow-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Cetak Laporan
                            </a>
                            <a href="{{ route('admin.jadwal.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition-colors shadow-sm font-medium">+ Tambah Jadwal</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        {{-- Filter Kelompok Ujian --}}
                        @if($kelompokList->isNotEmpty())
                        <div class="mb-5 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-gray-600 mr-1">Kelompok Ujian:</span>
                            <a href="{{ route('admin.jadwal.index') }}" 
                               class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ !$selectedKelompok ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                Semua
                            </a>
                            @foreach($kelompokList as $kel)
                            <a href="{{ route('admin.jadwal.index', ['kelompok' => $kel]) }}" 
                               class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ $selectedKelompok == $kel ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                {{ $kel }}
                            </a>
                            @endforeach
                        </div>
                        @endif
                        <table class="min-w-full divide-y divide-gray-200 mt-4 border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">NO</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">NAMA/NIM</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">JUDUL</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">PEMBIMBING SKRIPSI</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">DOSEN PENELAAH/PENGUJI</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">NILAI</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">WAKTU PELAKSANAAN</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">AKSI</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">I</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">II</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">III</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">IV</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">V</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">VI</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">VII</th>
                                    <th class="px-4 py-1 text-center text-xs font-semibold text-gray-500 border">VIII</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($jadwals as $index => $jadwal)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 text-sm text-gray-900 border text-center align-top">{{ $index + 1 }}.</td>
                                    <td class="px-4 py-4 border align-top">
                                        <div class="text-sm font-medium text-gray-900">{{ $jadwal->mahasiswa->nama }}</div>
                                        <div class="text-sm text-gray-500">/{{ $jadwal->mahasiswa->nim }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border align-top max-w-xs whitespace-normal">
                                        {{ $jadwal->mahasiswa->judul_skripsi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border align-top">
                                        @if($jadwal->mahasiswa->pembimbing1)
                                            <div>1. {{ $jadwal->mahasiswa->pembimbing1->nama }}</div>
                                        @endif
                                        @if($jadwal->mahasiswa->pembimbing2)
                                            <div>2. {{ $jadwal->mahasiswa->pembimbing2->nama }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border align-top">
                                        @foreach($jadwal->nilaiSidangs as $nsIndex => $ns)
                                            <div>{{ $nsIndex + 1 }}. {{ $ns->dosen->nama }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border align-top font-mono">
                                        <div class="flex justify-between font-semibold border-b border-gray-200 mb-1 pb-1 text-xs text-gray-600">
                                            <span>No</span>
                                            <span>Nilai</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            @foreach($jadwal->nilaiSidangs as $nsIndex => $ns)
                                                <div class="flex justify-between w-full">
                                                    <span>{{ $nsIndex + 1 }}.</span>
                                                    <span class="font-semibold text-right {{ $ns->nilai !== null ? 'text-green-700' : 'text-gray-400' }}">{{ $ns->nilai !== null ? number_format($ns->nilai, 2) : '-' }}</span>
                                                </div>
                                            @endforeach
                                            @php
                                                $nilaiAkhir = $jadwal->getNilaiSidangAkhir();
                                            @endphp
                                            @if($nilaiAkhir !== null)
                                                <div class="border-t border-gray-300 mt-1 pt-1 flex justify-end w-full">
                                                    <span class="font-bold text-blue-700">{{ number_format($nilaiAkhir, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 border align-top">
                                        <div>{{ \Carbon\Carbon::parse($jadwal->waktu_sidang)->translatedFormat('l, d F Y') }}</div>
                                        <div>Pukul {{ \Carbon\Carbon::parse($jadwal->waktu_sidang)->format('H.i') }}</div>
                                        <div>{{ $jadwal->ruangan ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm border align-top text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.jadwal.nilai.edit', $jadwal->id) }}" class="text-blue-600 hover:underline text-sm font-semibold">Input Nilai</a>
                                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="text-emerald-600 hover:underline text-sm font-semibold">Edit</a>
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm font-semibold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @if($jadwals->isEmpty())
                                <tr><td colspan="8" class="px-6 py-10 text-center text-gray-500 border">Belum ada jadwal sidang.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
