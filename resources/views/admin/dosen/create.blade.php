<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Dosen</h2>
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

                    <form action="{{ route('admin.dosen.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">NIDN</label>
                            <input type="text" name="nidn" id="nidn" value="{{ old('nidn') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Login <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <div class="bg-blue-50 text-blue-700 p-3 rounded-lg border border-blue-200 text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Password akan di-generate otomatis oleh sistem.
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 pt-2">
                            <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition-colors font-medium">Simpan</button>
                            <a href="{{ route('admin.dosen.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
