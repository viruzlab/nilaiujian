<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penguji Sidang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-emerald-600 to-emerald-800 -skew-y-6 origin-top-left -z-10"></div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8" 
         x-data="{
            open: false,
            search: '',
            selectedDosenId: '',
            selectedDosenName: '',
            dosens: [
                @foreach($dosens as $dosen)
                    { id: '{{ $dosen->id }}', nama: '{{ addslashes($dosen->nama) }}' },
                @endforeach
            ],
            get filteredDosens() {
                if (this.search === '') return this.dosens;
                return this.dosens.filter(d => d.nama.toLowerCase().includes(this.search.toLowerCase()));
            },
            selectDosen(dosen) {
                this.selectedDosenId = dosen.id;
                this.selectedDosenName = dosen.nama;
                this.search = dosen.nama;
                this.open = false;
            }
         }">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Sistem Penilaian Sidang</h2>
            <p class="text-gray-500 text-sm mt-2">Silakan pilih nama Anda untuk masuk</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('dosen.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="dosen_id" x-model="selectedDosenId">

            <!-- Searchable Dropdown -->
            <div class="relative">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Dosen</label>
                <div class="relative">
                    <input type="text" 
                        x-model="search" 
                        @focus="open = true" 
                        @click="open = true"
                        @input="open = true; selectedDosenId = ''; selectedDosenName = '';"
                        placeholder="Ketik atau pilih nama dosen..."
                        class="w-full px-4 py-3.5 pr-10 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-gray-800 placeholder-gray-400">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Dropdown List -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="absolute z-30 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto"
                     style="display: none;">
                    <template x-for="dosen in filteredDosens" :key="dosen.id">
                        <button type="button"
                            @click="selectDosen(dosen)"
                            class="w-full text-left px-4 py-3 hover:bg-emerald-50 transition-colors flex items-center justify-between group border-b border-gray-100 last:border-b-0">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-600" x-text="dosen.nama"></span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </template>
                    <div x-show="filteredDosens.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                        Dosen tidak ditemukan.
                    </div>
                </div>
            </div>

            <!-- Selected Indicator -->
            <div x-show="selectedDosenName !== ''" x-transition class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center space-x-3">
                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-emerald-500 font-medium">Login sebagai</p>
                    <p class="text-sm font-bold text-emerald-800" x-text="selectedDosenName"></p>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required
                    placeholder="Masukkan password..."
                    class="w-full px-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all placeholder-gray-400">
            </div>

            <!-- Submit -->
            <button type="submit" 
                :disabled="selectedDosenId === ''"
                :class="selectedDosenId === '' ? 'bg-gray-300 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 hover:shadow-xl'"
                class="w-full px-4 py-3.5 rounded-xl text-white font-semibold transition-all duration-300">
                Masuk
            </button>
        </form>

        @if($dosens->isEmpty())
            <div class="text-center text-gray-400 py-6 mt-4">Belum ada data dosen.</div>
        @endif

        <!-- Link ke Admin Login -->
        <div class="mt-6 pt-5 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-400 mb-2">Bukan dosen?</p>
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Masuk sebagai Admin
            </a>
        </div>
    </div>

</body>
</html>
