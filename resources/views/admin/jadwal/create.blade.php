<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Jadwal Sidang Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($errors->any())
                        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa <span class="text-red-500">*</span></label>
                            <select name="mahasiswa_id" id="mahasiswa_id" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="waktu_sidang" class="block text-sm font-medium text-gray-700 mb-1">Waktu Sidang <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="waktu_sidang" id="waktu_sidang" value="{{ old('waktu_sidang') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                            <input type="text" name="ruangan" id="ruangan" value="{{ old('ruangan') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                placeholder="Contoh: Ruang 301">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dosen Penguji <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-3">Pilih satu atau lebih dosen penguji untuk sidang ini.</p>
                            <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                @foreach($dosens as $dosen)
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="dosen_ids[]" value="{{ $dosen->id }}"
                                        {{ in_array($dosen->id, old('dosen_ids', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mr-3">
                                    <span class="text-sm text-gray-700">{{ $dosen->nama }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $dosen->nidn ?? '' }}</span>
                                </label>
                                @endforeach
                                @if($dosens->isEmpty())
                                    <p class="text-sm text-gray-400 text-center py-2">Belum ada data dosen. <a href="{{ route('admin.dosen.create') }}" class="text-emerald-600 hover:underline">Tambah dosen</a></p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 pt-2">
                            <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition-colors font-medium">Buat Jadwal</button>
                            <a href="{{ route('admin.jadwal.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
