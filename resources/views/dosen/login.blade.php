<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 sm:p-12 relative z-10"
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
         
        <!-- Logo IEKI -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/logo-ieki.png') }}" alt="Logo IEKI" class="h-16 object-contain">
        </div>
        
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Sistem Penilaian Ujian Sidang</h2>
            <p class="text-gray-500 text-sm mt-3 leading-relaxed">Silakan pilih nama Anda untuk masuk</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('dosen.login.submit') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="dosen_id" x-model="selectedDosenId">

            <!-- Searchable Dropdown -->
            <div class="relative">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Dosen</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" 
                        x-model="search" 
                        @focus="open = true" 
                        @click="open = true"
                        @input="open = true; selectedDosenId = ''; selectedDosenName = '';"
                        placeholder="Ketik atau pilih nama dosen..."
                        class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3 pr-10 text-gray-900 shadow-sm focus:border-[#115e41] focus:bg-white focus:ring focus:ring-[#115e41] focus:ring-opacity-20 transition-all duration-200 sm:text-sm">
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
                     class="absolute z-30 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl max-h-60 overflow-y-auto"
                     style="display: none;">
                    <template x-for="dosen in filteredDosens" :key="dosen.id">
                        <button type="button"
                            @click="selectDosen(dosen)"
                            class="w-full text-left px-4 py-3 hover:bg-emerald-50 transition-colors flex items-center justify-between group border-b border-gray-50 last:border-b-0">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-700" x-text="dosen.nama"></span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </template>
                    <div x-show="filteredDosens.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                        Dosen tidak ditemukan.
                    </div>
                </div>
            </div>

            <!-- Selected Indicator -->
            <div x-show="selectedDosenName !== ''" x-transition class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 flex items-center space-x-3 mt-3">
                <div class="w-8 h-8 bg-[#115e41] rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-emerald-600 font-semibold mb-0.5">Login sebagai</p>
                    <p class="text-sm font-bold text-[#115e41]" x-text="selectedDosenName"></p>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" name="password" required autocomplete="current-password" class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3 text-gray-900 shadow-sm focus:border-[#115e41] focus:bg-white focus:ring focus:ring-[#115e41] focus:ring-opacity-20 transition-all duration-200 sm:text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" 
                :disabled="selectedDosenId === ''"
                :class="selectedDosenId === '' ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-[#115e41] hover:bg-[#0d4a33] text-white shadow-lg shadow-[#115e41]/20 hover:shadow-[#115e41]/30 hover:-translate-y-0.5'"
                class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl text-sm font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#115e41] transition-all mt-8">
                Masuk
                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            </button>
        </form>

        @if($dosens->isEmpty())
            <div class="text-center text-gray-400 py-6 mt-4 text-sm font-medium">Belum ada data dosen.</div>
        @endif

        <!-- Link ke Admin Login -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500 mb-2">Bukan dosen?</p>
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-[#115e41] hover:text-[#0d4a33] transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Masuk sebagai Admin
            </a>
        </div>
    </div>
</x-guest-layout>
