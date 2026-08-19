<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Ujian Sidang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ieki.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="#" class="flex items-center gap-3">
                            <img src="{{ asset('images/logo-ieki.png') }}" class="h-8 w-auto" alt="Logo IEKI">
                            <h1 class="text-xl font-bold text-emerald-600">Sistem Penilaian Ujian Sidang</h1>
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">Selamat datang, <strong>{{ $dosen->nama }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 flex-grow w-full">
        @if(session('success'))
            <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Mahasiswa Ujian Sidang</h3>
                <p class="text-sm text-gray-500 mt-1 mb-4">Berikut adalah daftar mahasiswa yang dijadwalkan untuk Anda uji.</p>

                <div class="mb-5 bg-emerald-50/80 border-l-4 border-emerald-500 p-4 rounded-r-lg inline-block w-full">
                    <div class="flex items-start mb-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-emerald-800 leading-snug">
                            <strong>Informasi Penilaian:</strong> Skala Penilaian <span class="font-semibold text-emerald-900">1 - 100</span>. Syarat Predikat Cumlaude: Minimal <span class="font-semibold text-emerald-900">82</span>.
                        </p>
                    </div>
                    <div class="overflow-x-auto pl-7">
                        <table class="w-full sm:w-2/3 text-xs text-left text-emerald-900 border-collapse border border-emerald-200 bg-white shadow-sm">
                            <thead class="bg-emerald-100">
                                <tr>
                                    <th class="border border-emerald-200 px-3 py-1.5 font-semibold">Nilai</th>
                                    <th class="border border-emerald-200 px-3 py-1.5 font-semibold text-center">Huruf</th>
                                    <th class="border border-emerald-200 px-3 py-1.5 font-semibold text-center">Angka</th>
                                    <th class="border border-emerald-200 px-3 py-1.5 font-semibold">Derajat Mutu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">&ge; 92</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">A</td><td class="border border-emerald-200 px-3 py-1 text-center">4,0</td><td class="border border-emerald-200 px-3 py-1">Istimewa</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">86 - 91</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">A-</td><td class="border border-emerald-200 px-3 py-1 text-center">3,7</td><td class="border border-emerald-200 px-3 py-1">Hampir Istimewa</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">81 - 85</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">B+</td><td class="border border-emerald-200 px-3 py-1 text-center">3,4</td><td class="border border-emerald-200 px-3 py-1">Baik Sekali</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">76 - 80</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">B</td><td class="border border-emerald-200 px-3 py-1 text-center">3,0</td><td class="border border-emerald-200 px-3 py-1">Baik</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">71 - 75</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">B-</td><td class="border border-emerald-200 px-3 py-1 text-center">2,7</td><td class="border border-emerald-200 px-3 py-1">Cukup Baik</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">66 - 70</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">C+</td><td class="border border-emerald-200 px-3 py-1 text-center">2,4</td><td class="border border-emerald-200 px-3 py-1">Lebih dari Cukup</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">60 - 65</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">C</td><td class="border border-emerald-200 px-3 py-1 text-center">2,0</td><td class="border border-emerald-200 px-3 py-1">Cukup</td></tr>
                                <tr class="hover:bg-emerald-50"><td class="border border-emerald-200 px-3 py-1">55 - 59</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold">D</td><td class="border border-emerald-200 px-3 py-1 text-center">1,0</td><td class="border border-emerald-200 px-3 py-1">Kurang</td></tr>
                                <tr class="hover:bg-red-50"><td class="border border-emerald-200 px-3 py-1">&lt; 55</td><td class="border border-emerald-200 px-3 py-1 text-center font-bold text-red-600">E</td><td class="border border-emerald-200 px-3 py-1 text-center text-red-600">&lt;1,0</td><td class="border border-emerald-200 px-3 py-1 text-red-600">Gagal</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Filter Kelompok Ujian --}}
                @if(isset($kelompokList) && $kelompokList->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-semibold text-gray-600 mr-1">Kelompok Ujian:</span>
                    <a href="{{ route('dosen.dashboard') }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ !$selectedKelompok ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                        Semua
                    </a>
                    @foreach($kelompokList as $kel)
                    <a href="{{ route('dosen.dashboard', ['kelompok' => $kel]) }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium border transition-all {{ $selectedKelompok == $kel ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}">
                        {{ $kel }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu & Ruangan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Nilai</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->jadwalSidang->mahasiswa->nim }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $jadwal->jadwalSidang->mahasiswa->nama }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $jadwal->jadwalSidang->mahasiswa->judul_skripsi }}</div>
                                @php
                                    $mhs = $jadwal->jadwalSidang->mahasiswa;
                                    $role = 'Penguji';
                                    $roleColor = 'bg-blue-100 text-blue-800 border border-blue-200';
                                    if ($dosen->id === $mhs->pembimbing_1_id) {
                                        $role = 'Pembimbing 1';
                                        $roleColor = 'bg-purple-100 text-purple-800 border border-purple-200';
                                    } elseif ($dosen->id === $mhs->pembimbing_2_id) {
                                        $role = 'Pembimbing 2';
                                        $roleColor = 'bg-purple-100 text-purple-800 border border-purple-200';
                                    }
                                @endphp
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $roleColor }}">
                                        Peran: {{ $role }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $jadwal->jadwalSidang->waktu_sidang->format('d M Y, H:i') }}<br>
                                <span class="text-xs text-gray-500">{{ $jadwal->jadwalSidang->ruangan }}</span>
                                @if($jadwal->jadwalSidang->kelompok_ujian)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Kelompok {{ $jadwal->jadwalSidang->kelompok_ujian }}
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" id="status-col-{{ $jadwal->id }}">
                                @php $hasSaved = $jadwal->nilai !== null; @endphp
                                <div id="preview-container-{{ $jadwal->id }}" class="flex flex-col gap-1 {{ $hasSaved ? '' : 'hidden' }}">
                                    <span id="skor-badge-{{ $jadwal->id }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hasSaved ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} w-fit mb-1">
                                        Skor: <span id="skor-val-{{ $jadwal->id }}" class="ml-1">{{ $jadwal->nilai }}</span> 
                                        <span id="preview-label-{{ $jadwal->id }}" class="ml-1 italic {{ $hasSaved ? 'hidden' : '' }}">(Belum Disimpan)</span>
                                    </span>
                                    <div class="text-[11px] text-gray-600 space-y-0.5 bg-gray-50 p-2 rounded border border-gray-100">
                                        <div class="flex justify-between gap-4"><span class="text-gray-500">Huruf:</span> <span id="huruf-val-{{ $jadwal->id }}" class="font-bold text-gray-800">{{ $jadwal->nilai_huruf ?? '-' }}</span></div>
                                        <div class="flex justify-between gap-4"><span class="text-gray-500">Angka:</span> <span id="angka-val-{{ $jadwal->id }}" class="font-bold text-gray-800">{{ $jadwal->nilai_angka ?? '-' }}</span></div>
                                        <div class="flex justify-between gap-4"><span class="text-gray-500">Mutu:</span> <span id="mutu-val-{{ $jadwal->id }}" class="font-semibold text-emerald-700">{{ $jadwal->derajat_mutu ?? '-' }}</span></div>
                                    </div>
                                </div>
                                <div id="default-badge-{{ $jadwal->id }}" class="{{ $hasSaved ? 'hidden' : '' }}">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Dinilai</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('dosen.nilai.store', $jadwal->id) }}" method="POST" class="flex items-center justify-end gap-3">
                                    @csrf
                                    <div class="flex items-center space-x-2">
                                        <input type="number" name="nilai" min="1" max="100" placeholder="0-100" required value="{{ $jadwal->nilai }}" step="0.01"
                                            class="nilai-input w-20 px-3 py-1.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                            data-id="{{ $jadwal->id }}" data-saved="{{ $jadwal->nilai ?? '' }}"
                                            oninput="updateConversion(this)">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg text-sm transition-colors shadow-sm">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($jadwals->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada jadwal sidang untuk Anda saat ini.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            <p class="text-xs">
                @php
                    $commitCount = '2';
                    if(file_exists(base_path('.git/logs/HEAD'))) {
                        $commitCount = count(file(base_path('.git/logs/HEAD')));
                    }
                @endphp
                &copy; {{ date('Y') }} Sistem Penilaian Ujian Sidang. By @ ViruzLab. Version 1.0.{{ $commitCount }}. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        function updateConversion(input) {
            const val = parseFloat(input.value);
            const id = input.getAttribute('data-id');
            const savedVal = input.getAttribute('data-saved');
            
            const previewContainer = document.getElementById('preview-container-' + id);
            const defaultBadge = document.getElementById('default-badge-' + id);
            
            const skorBadge = document.getElementById('skor-badge-' + id);
            const skorVal = document.getElementById('skor-val-' + id);
            const previewLabel = document.getElementById('preview-label-' + id);
            
            const hurufVal = document.getElementById('huruf-val-' + id);
            const angkaVal = document.getElementById('angka-val-' + id);
            const mutuVal = document.getElementById('mutu-val-' + id);
            
            if (isNaN(val)) {
                if (savedVal === '') {
                    previewContainer.classList.add('hidden');
                    defaultBadge.classList.remove('hidden');
                } else {
                    // Revert to saved value if input is cleared
                    input.value = savedVal;
                    updateConversion(input);
                }
                return;
            }
            
            previewContainer.classList.remove('hidden');
            defaultBadge.classList.add('hidden');
            
            let huruf = 'E';
            let angka = '<1,0';
            let mutu = 'Gagal';
            
            if (val >= 92) { huruf = 'A'; angka = '4,0'; mutu = 'Istimewa'; }
            else if (val >= 86) { huruf = 'A-'; angka = '3,7'; mutu = 'Hampir Istimewa'; }
            else if (val >= 81) { huruf = 'B+'; angka = '3,4'; mutu = 'Baik Sekali'; }
            else if (val >= 76) { huruf = 'B'; angka = '3,0'; mutu = 'Baik'; }
            else if (val >= 71) { huruf = 'B-'; angka = '2,7'; mutu = 'Cukup Baik'; }
            else if (val >= 66) { huruf = 'C+'; angka = '2,4'; mutu = 'Lebih dari Cukup'; }
            else if (val >= 60) { huruf = 'C'; angka = '2,0'; mutu = 'Cukup'; }
            else if (val >= 55) { huruf = 'D'; angka = '1,0'; mutu = 'Kurang'; }
            
            skorVal.innerText = val;
            hurufVal.innerText = huruf;
            angkaVal.innerText = angka;
            mutuVal.innerText = mutu;
            
            // Highlight if it's different from saved value
            if (val.toString() !== savedVal) {
                skorBadge.classList.remove('bg-green-100', 'text-green-800');
                skorBadge.classList.add('bg-blue-100', 'text-blue-800');
                previewLabel.classList.remove('hidden');
            } else {
                skorBadge.classList.add('bg-green-100', 'text-green-800');
                skorBadge.classList.remove('bg-blue-100', 'text-blue-800');
                previewLabel.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
