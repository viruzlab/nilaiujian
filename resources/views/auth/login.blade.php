<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 sm:p-12 relative z-10">
        <!-- Logo IEKI -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/logo-ieki.png') }}" alt="Logo IEKI" class="h-16 object-contain">
        </div>
        
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Sistem Penilaian Ujian Sidang</h2>
            <p class="text-gray-500 text-sm mt-3 leading-relaxed">Silakan masuk untuk melanjutkan ke dasbor akademik Anda.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Username / Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input id="email" type="email" name="email" required autofocus autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3 text-gray-900 shadow-sm focus:border-[#115e41] focus:bg-white focus:ring focus:ring-[#115e41] focus:ring-opacity-20 transition-all duration-200 sm:text-sm" placeholder="Masukkan username atau email">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3 text-gray-900 shadow-sm focus:border-[#115e41] focus:bg-white focus:ring focus:ring-[#115e41] focus:ring-opacity-20 transition-all duration-200 sm:text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between pt-2">
                <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                    <div class="relative flex items-center justify-center">
                        <input id="remember_me" type="checkbox" class="peer h-4 w-4 rounded border-gray-300 text-[#115e41] focus:ring-[#115e41] focus:ring-opacity-50 transition-all" name="remember">
                    </div>
                    <span class="ms-2 text-sm text-gray-500 group-hover:text-gray-700 transition-colors">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-[#115e41] hover:text-[#0a3827] font-semibold transition-colors" href="{{ route('password.request') }}">
                        Lupa Kata Sandi?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-[#115e41]/20 text-sm font-bold text-white bg-[#115e41] hover:bg-[#0d4a33] hover:shadow-[#115e41]/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#115e41] transition-all mt-8">
                Masuk
                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>
</x-guest-layout>
