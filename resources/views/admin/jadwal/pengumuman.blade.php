<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengumuman Sidang Yudisium') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-4 lg:px-6">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h3 class="text-lg font-semibold flex-1 whitespace-nowrap">Daftar Pengumuman Yudisium</h3>
                        <div class="flex gap-2 w-full sm:w-auto items-center">
                            <a href="{{ route('admin.jadwal.cetak-yudisium-massal', ['kelompok' => $selectedKelompok]) }}" target="_blank"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors shadow-sm font-medium flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Cetak PDF (Satu File)
                            </a>
                            <a href="{{ route('admin.jadwal.cetak-yudisium-massal-zip', ['kelompok' => $selectedKelompok]) }}"
                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition-colors shadow-sm font-medium flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download ZIP
                            </a>
                            <input type="text" id="searchInput" placeholder="Cari mahasiswa..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full sm:w-64 transition-shadow">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        {{-- Filter Kelompok Ujian --}}
                        @if ($kelompokList->isNotEmpty())
                            <div class="mb-5 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold text-gray-600 mr-1">Kelompok Ujian:</span>
                                <a href="{{ route('admin.jadwal.pengumuman') }}"
                                    class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ !$selectedKelompok ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                    Semua
                                </a>
                                @foreach ($kelompokList as $kel)
                                    <a href="{{ route('admin.jadwal.pengumuman', ['kelompok' => $kel]) }}"
                                        class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ $selectedKelompok == $kel ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                        {{ $kel }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200 mt-4 border border-gray-200">
                            <thead class="bg-amber-100">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 border">Nama Mahasiswa</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">NIM</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">IPK Akhir</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">Predikat</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">Status</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($jadwals as $index => $jadwal)
                                    @php
                                        $ns = $jadwal->getNilaiSidangAkhir();
                                        $bobot = \App\Models\JadwalSidang::konversiBobot($ns);
                                        $jumlahMutuMhs = floatval(optional($jadwal->mahasiswa)->jumlah_mutu ?? 0);
                                        $jumlahSksMhs = floatval(optional($jadwal->mahasiswa)->jumlah_sks ?? 0);
                                        $nilaiMutuSidang = $ns !== null ? $bobot * 6 : 0;
                                        $nilaiAkhirAngka = 0;
                                        $mutuAkhirPredikat = '-';
                                        $isLulus = false;
                                        if ($jumlahSksMhs > 0) {
                                            $nilaiAkhirAngka = ($nilaiMutuSidang + $jumlahMutuMhs) / $jumlahSksMhs;
                                            if ($nilaiAkhirAngka > 3.5 && $nilaiAkhirAngka <= 4) {
                                                $mutuAkhirPredikat = 'Pujian';
                                            } elseif ($nilaiAkhirAngka > 3.0 && $nilaiAkhirAngka <= 3.5) {
                                                $mutuAkhirPredikat = 'Sangat Memuaskan';
                                            } elseif ($nilaiAkhirAngka > 2.75 && $nilaiAkhirAngka <= 3.0) {
                                                $mutuAkhirPredikat = 'Memuaskan';
                                            } elseif ($nilaiAkhirAngka > 2.0 && $nilaiAkhirAngka <= 2.75) {
                                                $mutuAkhirPredikat = 'Tanpa Predikat';
                                            }
                                            $isLulus = $nilaiAkhirAngka >= 2.0;
                                        }
                                        $nilaiAkhirFormat = $jumlahSksMhs > 0 ? number_format($nilaiAkhirAngka, 2) : '-';

                                        $belumMenilai = $jadwal->nilaiSidangs->filter(fn($n) => $n->nilai === null);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900 border text-center">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 border">
                                            {{ optional($jadwal->mahasiswa)->nama ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 border text-center">{{ optional($jadwal->mahasiswa)->nim ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-900 border text-center">{{ $nilaiAkhirFormat }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold border text-center {{ $mutuAkhirPredikat === 'Pujian' ? 'text-emerald-600' : 'text-gray-700' }}">
                                            {{ $mutuAkhirPredikat }}
                                        </td>
                                        <td class="px-4 py-3 text-sm border text-center">
                                            @if($belumMenilai->count() > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    Nilai Belum Lengkap
                                                </span>
                                            @elseif($isLulus)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800 border border-green-200">
                                                    LULUS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-800 border border-red-200">
                                                    TIDAK LULUS
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm border text-center">
                                            @if($belumMenilai->count() === 0)
                                                <a href="{{ route('admin.jadwal.cetak-yudisium', $jadwal->id) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    Cetak
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($jadwals->isEmpty())
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 border">Belum ada data jadwal sidang.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if(text.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>
