<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Mahasiswa: {{ $mahasiswa->nama }}</h2>
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

                    <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="judul_skripsi" class="block text-sm font-medium text-gray-700 mb-1">Judul Skripsi</label>
                            <textarea name="judul_skripsi" id="judul_skripsi" rows="3"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all resize-none">{{ old('judul_skripsi', $mahasiswa->judul_skripsi) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="pembimbing_1_id" class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 1</label>
                                <select name="pembimbing_1_id" id="pembimbing_1_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                    <option value="">-- Pilih Pembimbing 1 --</option>
                                    @foreach($dosens as $dosen)
                                        <option value="{{ $dosen->id }}" {{ old('pembimbing_1_id', $mahasiswa->pembimbing_1_id) == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="pembimbing_2_id" class="block text-sm font-medium text-gray-700 mb-1">Pembimbing 2</label>
                                <select name="pembimbing_2_id" id="pembimbing_2_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                    <option value="">-- Pilih Pembimbing 2 --</option>
                                    @foreach($dosens as $dosen)
                                        <option value="{{ $dosen->id }}" {{ old('pembimbing_2_id', $mahasiswa->pembimbing_2_id) == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr class="border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="jumlah_mutu" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Mutu</label>
                                <input type="number" step="0.01" name="jumlah_mutu" id="jumlah_mutu" value="{{ old('jumlah_mutu', $mahasiswa->jumlah_mutu) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                            <div x-data="{ sks: '{{ old('jumlah_sks', $mahasiswa->jumlah_sks ? $mahasiswa->jumlah_sks - 6 : '') }}' }">
                                <label for="jumlah_sks" class="block text-sm font-medium text-gray-700 mb-1">Jumlah SKS</label>
                                <input type="number" name="jumlah_sks" id="jumlah_sks" 
                                    x-model="sks"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                <p x-show="sks !== '' && sks !== null" x-cloak class="text-xs text-emerald-600 mt-1 font-medium">
                                    Tersimpan: <span x-text="parseInt(sks) + 6"></span> SKS (Otomatis ditambah SKS Skripsi 6 SKS)
                                </p>
                            </div>
                            <div>
                                <label for="ipk" class="block text-sm font-medium text-gray-700 mb-1">IPK (0 - 4.00)</label>
                                <input type="number" step="0.01" max="4.00" name="ipk" id="ipk" value="{{ old('ipk', $mahasiswa->ipk) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                <input type="text" name="semester" id="semester" value="{{ old('semester', $mahasiswa->semester) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                            <div>
                                <label for="mata_kuliah_ulang" class="block text-sm font-medium text-gray-700 mb-1">Kontrak Ulang (Kali)</label>
                                <input type="number" name="mata_kuliah_ulang" id="mata_kuliah_ulang" value="{{ old('mata_kuliah_ulang', $mahasiswa->mata_kuliah_ulang ?? 0) }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 pt-2">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition-colors font-medium">Perbarui</button>
                            <a href="{{ route('admin.mahasiswa.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
