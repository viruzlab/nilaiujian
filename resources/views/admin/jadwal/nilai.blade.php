<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Nilai oleh Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Informasi Mahasiswa</h3>
                        <table class="text-sm w-full">
                            <tr>
                                <td class="py-1 w-1/4 text-gray-600">NIM</td>
                                <td class="py-1">: {{ $jadwal->mahasiswa->nim }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600">Nama</td>
                                <td class="py-1 font-medium">: {{ $jadwal->mahasiswa->nama }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600">Judul Skripsi</td>
                                <td class="py-1">: {{ $jadwal->mahasiswa->judul_skripsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-600">Kelompok Ujian</td>
                                <td class="py-1">: {{ $jadwal->kelompok_ujian ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('admin.jadwal.nilai.update', $jadwal->id) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Form Input Nilai</h3>
                            
                            @if ($errors->any())
                                <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-200">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-4">
                                @foreach($jadwal->nilaiSidangs as $ns)
                                    <div class="border rounded-lg p-4 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $ns->dosen->nama }}</p>
                                            <p class="text-xs text-gray-500">Penguji / Penelaah</p>
                                        </div>
                                        <div class="w-full sm:w-1/3">
                                            <input type="number" 
                                                name="nilai[{{ $ns->id }}]" 
                                                value="{{ old('nilai.' . $ns->id, $ns->nilai) }}" 
                                                class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"
                                                step="0.01" min="0" max="100"
                                                placeholder="Belum ada nilai">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-8">
                            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors shadow-sm">
                                Simpan Nilai
                            </button>
                            <a href="{{ route('admin.jadwal.index') }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
