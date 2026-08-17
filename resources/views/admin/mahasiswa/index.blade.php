<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Data Mahasiswa</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-[98%] mx-auto sm:px-4 lg:px-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Daftar Mahasiswa</h3>
                        <a href="{{ route('admin.mahasiswa.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors shadow-sm">+ Tambah Mahasiswa</a>
                    </div>
                    
                    @if($errors->has('file'))
                        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200 text-sm">
                            {{ $errors->first('file') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 mt-4">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Skripsi</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">IPK</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Mutu</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml SKS</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">MK Ulang</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mahasiswas as $mhs)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mhs->nim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mhs->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $mhs->judul_skripsi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 text-center">
                                        @php
                                            $kelompok = $mhs->jadwalSidangs->first()->kelompok_ujian ?? '-';
                                        @endphp
                                        @if($kelompok !== '-')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $kelompok }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($mhs->pembimbing1) 1. {{ $mhs->pembimbing1->nama }}<br> @endif
                                        @if($mhs->pembimbing2) 2. {{ $mhs->pembimbing2->nama }} @endif
                                        @if(!$mhs->pembimbing1 && !$mhs->pembimbing2) - @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center {{ !$mhs->ipk ? 'bg-red-50 text-red-500' : '' }}">{{ $mhs->ipk ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center {{ !$mhs->jumlah_mutu ? 'bg-red-50 text-red-500' : '' }}">{{ $mhs->jumlah_mutu ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center {{ !$mhs->jumlah_sks ? 'bg-red-50 text-red-500' : '' }}">{{ $mhs->jumlah_sks ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $mhs->mata_kuliah_ulang ?? '0' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-center {{ !$mhs->semester ? 'bg-red-50 text-red-500' : '' }}">{{ $mhs->semester ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="text-emerald-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($mahasiswas->isEmpty())
                                <tr><td colspan="10" class="px-6 py-10 text-center text-gray-500">Belum ada data mahasiswa.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
