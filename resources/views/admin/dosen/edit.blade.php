<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Dosen: {{ $dosen->nama }}</h2>
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

                    <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $dosen->nama) }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="nidn" class="block text-sm font-medium text-gray-700 mb-1">NIDN</label>
                            <input type="text" name="nidn" id="nidn" value="{{ old('nidn', $dosen->nidn) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Login <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $dosen->user->email ?? '') }}" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div class="flex items-center space-x-3 pt-2">
                            <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition-colors font-medium">Perbarui</button>
                            <a href="{{ route('admin.dosen.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
