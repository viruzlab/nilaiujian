<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Ujian Sidang - IEKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="antialiased text-gray-800 hero-pattern min-h-screen flex flex-col selection:bg-emerald-200 selection:text-emerald-900 overflow-x-hidden relative">
    
    <!-- Decorative Blurs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob -z-10"></div>
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 -z-10"></div>
    <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000 -z-10"></div>

    <!-- Navigation -->
    <nav class="glass-panel sticky top-0 z-50 border-b border-gray-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-emerald-600 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg text-white font-bold text-xl">
                        S
                    </div>
                    <span class="font-bold text-2xl bg-clip-text text-transparent bg-gradient-to-r from-emerald-800 to-emerald-800">
                        IEKI Sidang
                    </span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="flex space-x-4">
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-full bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200 transition-all duration-300">Dashboard Admin</a>
                                @else
                                    <a href="{{ route('dosen.dashboard') }}" class="px-5 py-2.5 rounded-full bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200 transition-all duration-300">Dashboard Dosen</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full glass-panel text-sm font-semibold text-emerald-700 mb-8 border border-emerald-200 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-emerald-600 mr-2 animate-pulse"></span>
                Digitalisasi Penilaian Sidang v1.0
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                Sistem Penilaian <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-emerald-600 to-emerald-600">Ujian Sidang</span>
            </h1>
            
            <p class="mt-4 text-xl text-gray-600 mb-12 max-w-2xl mx-auto leading-relaxed">
                Platform terpadu untuk mengelola jadwal ujian dan penilaian mahasiswa Program Studi IEKI secara efisien, transparan, dan terdigitalisasi.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <!-- Dosen Login Card -->
                <a href="{{ route('dosen.login') }}" class="group relative bg-white rounded-3xl p-8 shadow-xl shadow-emerald-900/5 hover:shadow-2xl hover:shadow-emerald-900/10 border border-gray-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Portal Dosen</h2>
                        <p class="text-gray-500 text-sm text-center mb-6">Masuk untuk melihat jadwal menguji dan memberikan penilaian mahasiswa.</p>
                        <span class="inline-flex items-center font-semibold text-emerald-600 group-hover:text-emerald-700">
                            Masuk sebagai Dosen <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                </a>

                <!-- Admin Login Card -->
                <a href="{{ route('login') }}" class="group relative bg-white rounded-3xl p-8 shadow-xl shadow-emerald-900/5 hover:shadow-2xl hover:shadow-emerald-900/10 border border-gray-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Portal Admin</h2>
                        <p class="text-gray-500 text-sm text-center mb-6">Kelola data dosen, mahasiswa, dan atur jadwal sidang ujian.</p>
                        <span class="inline-flex items-center font-semibold text-emerald-600 group-hover:text-emerald-700">
                            Masuk sebagai Admin <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </main>
    
    <footer class="py-6 text-center text-sm text-gray-500 relative z-10">
        &copy; {{ date('Y') }} Program Studi IEKI. All rights reserved.
    </footer>
</body>
</html>
