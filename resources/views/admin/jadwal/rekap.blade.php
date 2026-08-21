<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekapitulasi Nilai Sidang') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-4 lg:px-6">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <h3 class="text-lg font-semibold flex-1 whitespace-nowrap">Rekapitulasi Nilai Mahasiswa</h3>
                            <input type="text" id="searchInput" placeholder="Cari mahasiswa..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full sm:w-64 transition-shadow">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <a href="{{ route('admin.jadwal.laporan', ['kelompok' => $selectedKelompok]) }}"
                                class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition-colors shadow-sm font-medium flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Cetak Laporan (Excel)
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        {{-- Filter Kelompok Ujian --}}
                        @if ($kelompokList->isNotEmpty())
                            <div class="mb-5 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold text-gray-600 mr-1">Kelompok Ujian:</span>
                                <a href="{{ route('admin.jadwal.rekap') }}"
                                    class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ !$selectedKelompok ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                    Semua
                                </a>
                                @foreach ($kelompokList as $kel)
                                    <a href="{{ route('admin.jadwal.rekap', ['kelompok' => $kel]) }}"
                                        class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ $selectedKelompok == $kel ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                                        {{ $kel }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        <table class="min-w-full divide-y divide-gray-200 mt-4 border border-gray-200">
                            <thead class="bg-amber-100">
                                <tr>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">No</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border">Nama
                                        Mahasiswa</th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Nilai Pembimbing</th>
                                    <th colspan="2"
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Nilai Penguji</th>
                                    <th colspan="3"
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-200">
                                        Nilai Sidang Akhir</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        IPK Terakhir</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Jumlah Mutu</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Jumlah SKS</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Mata Kuliah Ulang</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Semester</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-cyan-100">
                                        Nilai Akhir</th>
                                    <th rowspan="2"
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 border bg-cyan-100">
                                        Mutu Akhir</th>
                                </tr>
                                <tr>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Nilai</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Mutu</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Nilai</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-100">
                                        Mutu</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-200">
                                        Nilai</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-200">
                                        Mutu</th>
                                    <th
                                        class="px-4 py-2 text-center text-xs font-bold text-gray-700 border bg-orange-200">
                                        Nilai Mutu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($jadwals as $index => $jadwal)
                                    @php
                                        $npb = $jadwal->getNilaiPembimbing();
                                        $npbFormat = $npb !== null ? number_format($npb, 2) : '-';
                                        $npbHuruf = \App\Models\JadwalSidang::konversiHuruf($npb);

                                        $npu = $jadwal->getNilaiPenguji();
                                        $npuFormat = $npu !== null ? number_format($npu, 2) : '-';
                                        $npuHuruf = \App\Models\JadwalSidang::konversiHuruf($npu);

                                        $ns = $jadwal->getNilaiSidangAkhir();
                                        $nsFormat = $ns !== null ? number_format($ns, 2) : '-';
                                        $nsHuruf = \App\Models\JadwalSidang::konversiHuruf($ns);

                                        $bobot = \App\Models\JadwalSidang::konversiBobot($ns);
                                        $nilaiMutu = $ns !== null ? number_format($bobot * 6, 2) : '-';

                                        $jumlahMutuMhs = floatval(optional($jadwal->mahasiswa)->jumlah_mutu ?? 0);
                                        $jumlahSksMhs = floatval(optional($jadwal->mahasiswa)->jumlah_sks ?? 0);
                                        $nilaiMutuSidang = $ns !== null ? $bobot * 6 : 0;
                                        $nilaiAkhirAngka = 0;
                                        $mutuAkhirPredikat = '-';
                                        if ($jumlahSksMhs > 0) {
                                            $nilaiAkhirAngka = ($nilaiMutuSidang + $jumlahMutuMhs) / $jumlahSksMhs;
                                            if ($nilaiAkhirAngka > 3.5 && $nilaiAkhirAngka <= 4) {
                                                $mutuAkhirPredikat = 'Pujian/Cumlaude';
                                            } elseif ($nilaiAkhirAngka > 3.0 && $nilaiAkhirAngka <= 3.5) {
                                                $mutuAkhirPredikat = 'Sangat memuaskan';
                                            } elseif ($nilaiAkhirAngka > 2.75 && $nilaiAkhirAngka <= 3.0) {
                                                $mutuAkhirPredikat = 'Memuaskan';
                                            } elseif ($nilaiAkhirAngka > 2.0 && $nilaiAkhirAngka <= 2.75) {
                                                $mutuAkhirPredikat = 'Tanpa predikat kelulusan';
                                            }
                                        }
                                        $nilaiAkhirFormat =
                                            $jumlahSksMhs > 0 ? number_format($nilaiAkhirAngka, 2) : '-';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ $index + 1 }}</td>
                                        <td class="px-4 py-4 text-sm font-medium text-gray-900 border align-middle">
                                            <div class="mb-2">{{ optional($jadwal->mahasiswa)->nama ?? '-' }}</div>
                                            
                                            @php
                                                $belumMenilai = $jadwal->nilaiSidangs->filter(function($ns) {
                                                    return $ns->nilai === null;
                                                });
                                            @endphp
                                            
                                            @if($belumMenilai->count() > 0)
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-800 border border-red-200 w-fit">
                                                        Belum Lengkap (Kurang {{ $belumMenilai->count() }})
                                                    </span>
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800 border border-green-200 w-fit">
                                                        Nilai Lengkap
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ $npbFormat }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-gray-900 border text-center font-bold align-middle">
                                            {{ $npbHuruf }}</td>

                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ $npuFormat }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-gray-900 border text-center font-bold align-middle">
                                            {{ $npuHuruf }}</td>

                                        <td
                                            class="px-4 py-4 text-sm text-gray-900 border text-center align-middle bg-green-50 font-semibold">
                                            {{ $nsFormat }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-emerald-600 border text-center font-bold align-middle bg-green-50 text-base">
                                            {{ $nsHuruf }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-gray-900 border text-center font-bold align-middle bg-green-50">
                                            {{ $nilaiMutu }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ optional($jadwal->mahasiswa)->ipk ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ optional($jadwal->mahasiswa)->jumlah_mutu ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ optional($jadwal->mahasiswa)->jumlah_sks ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ optional($jadwal->mahasiswa)->mata_kuliah_ulang ?? '0' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 border text-center align-middle">
                                            {{ optional($jadwal->mahasiswa)->semester ?? '-' }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-blue-700 border text-center font-bold align-middle bg-cyan-50">
                                            {{ $nilaiAkhirFormat }}</td>
                                        <td
                                            class="px-4 py-4 text-sm text-gray-900 border text-center align-middle font-semibold bg-cyan-50 whitespace-nowrap">
                                            {{ $mutuAkhirPredikat }}</td>
                                    </tr>
                                @endforeach
                                @if ($jadwals->isEmpty())
                                    <tr>
                                        <td colspan="16" class="px-6 py-10 text-center text-gray-500 border">Belum ada
                                            data nilai.</td>
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
