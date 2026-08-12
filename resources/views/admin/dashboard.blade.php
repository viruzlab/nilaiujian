<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Card Dosen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Dosen</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $totalDosen }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-full">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card Mahasiswa -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Mahasiswa</p>
                            <p class="text-3xl font-bold text-green-600">{{ $totalMahasiswa }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card Jadwal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Jadwal Sidang</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ $totalJadwal }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-full">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->has('file'))
                <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                    {{ $errors->first('file') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Import Jadwal Sidang (Excel)</h3>
                        <p class="text-sm text-gray-500 mt-1">Unggah file Excel untuk secara otomatis memasukkan data Mahasiswa, Pembimbing, Penguji, Waktu, dan Ruangan.</p>
                    </div>
                    <form action="{{ route('admin.dashboard.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-200 w-full md:w-auto">
                        @csrf
                        <div class="w-full sm:w-auto">
                            <input type="text" name="kelompok_ujian" placeholder="Kelompok Ujian (misal: 102)" required class="w-full sm:w-48 text-sm border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm px-3 py-2">
                        </div>
                        <div class="w-full sm:w-auto">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-emerald-600 text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm whitespace-nowrap">Import Data</button>
                    </form>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-100">
                    <h3 class="text-lg font-semibold mb-6 text-gray-800">Statistik Penilaian Sidang</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Chart 1: Status Penilaian Mahasiswa -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center">
                            <h4 class="text-sm font-medium text-gray-600 mb-4 text-center">Status Mahasiswa Ujian</h4>
                            <div class="relative w-full max-w-[250px] aspect-square">
                                <canvas id="mahasiswaChart"></canvas>
                            </div>
                        </div>

                        <!-- Chart 2: Progres Dosen Menilai -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center">
                            <h4 class="text-sm font-medium text-gray-600 mb-4 text-center">Progres Dosen Menilai</h4>
                            <div class="relative w-full max-w-[250px] aspect-square">
                                <canvas id="dosenChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Mahasiswa
            const mhsCtx = document.getElementById('mahasiswaChart').getContext('2d');
            const totalUjian = {{ $jumlahUjian }};
            const selesaiDinilai = {{ $jadwalSelesaiDinilai }};
            const belumDinilai = totalUjian - selesaiDinilai;

            new Chart(mhsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Dinilai (' + selesaiDinilai + ')', 'Belum Lengkap (' + belumDinilai + ')'],
                    datasets: [{
                        data: [selesaiDinilai, belumDinilai],
                        backgroundColor: [
                            '#10b981', // emerald-500
                            '#e5e7eb'  // gray-200
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '65%'
                }
            });

            // Chart 2: Dosen
            const dosenCtx = document.getElementById('dosenChart').getContext('2d');
            const targetSelesai = {{ $tugasSelesai }};
            const sisaTugas = {{ $totalTugasMenilai - $tugasSelesai }};

            new Chart(dosenCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Mengisi (' + targetSelesai + ')', 'Belum Mengisi (' + sisaTugas + ')'],
                    datasets: [{
                        data: [targetSelesai, sisaTugas],
                        backgroundColor: [
                            '#3b82f6', // blue-500
                            '#e5e7eb'  // gray-200
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '65%'
                }
            });
        });
    </script>
</x-app-layout>
