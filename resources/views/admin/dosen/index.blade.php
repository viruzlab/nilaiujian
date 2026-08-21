<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Data Dosen</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-4 bg-blue-50 text-blue-700 p-4 rounded-lg border border-blue-200">
                    {{ session('info') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <h3 class="text-lg font-semibold flex-1 whitespace-nowrap">Daftar Dosen Penguji</h3>
                            <input type="text" id="searchInput" placeholder="Cari dosen..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full sm:w-64 transition-shadow">
                        </div>
                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                            <!-- Removed mass generate password button -->
                            <a href="{{ route('admin.dosen.download-passwords') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors shadow-sm font-medium flex items-center whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Excel
                            </a>
                            <a href="{{ route('admin.dosen.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition-colors shadow-sm font-medium whitespace-nowrap">+ Tambah Dosen</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 mt-4">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIDN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Akun</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dosens as $dosen)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dosen->nidn ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dosen->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dosen->user->email ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($dosen->password_plain)
                                            <span class="font-mono bg-gray-100 px-2 py-1 rounded text-gray-800">{{ $dosen->password_plain }}</span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Diubah mandiri</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <form action="{{ route('admin.dosen.reset-password', $dosen->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mereset password dosen ini menjadi password acak baru?')">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:underline">Reset Pass</button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-emerald-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($dosens->isEmpty())
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada data dosen.</td></tr>
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
