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
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" name="password"
                                    class="w-full px-4 py-2.5 pr-10 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
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
