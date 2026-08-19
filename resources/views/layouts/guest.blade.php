<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Penilaian Ujian Sidang IEKI</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ieki.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans text-gray-900 antialiased overflow-hidden">
    <div class="flex min-h-screen bg-gray-50/50">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-[45%] relative flex-col justify-end p-12 lg:p-16 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/gambar.png') }}" alt="Gedung FPEB" class="w-full h-full object-cover">
                <!-- Premium Green Overlays -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-[#0f5132] via-[#0f5132]/80 to-[#115e41]/40 mix-blend-multiply">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f5132] via-[#0f5132]/50 to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 text-left w-full">
                <div
                    class="inline-block mb-4 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md">
                    <span class="text-emerald-100 text-sm font-semibold tracking-wider uppercase">PROGRAM STUDI ILMU
                        EKONOMI DAN KEUANGAN ISLAM - FPEB UPI</span>
                </div>
                <h1 class="text-[3rem] font-bold text-white mb-6 leading-[1.15] tracking-tight">
                    Transformasi Penilaian<br>Akademik yang Terpercaya
                </h1>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-[55%] flex flex-col justify-center items-center p-8 lg:p-24 relative bg-[#f8fafc]">
            {{ $slot }}

            <div class="absolute bottom-8 text-center text-xs text-gray-400 font-medium tracking-wide">
                <p class="text-xs">
                    @php
                        $commitCount = '2';
                        if(file_exists(base_path('.git/logs/HEAD'))) {
                            $commitCount = count(file(base_path('.git/logs/HEAD')));
                        }
                    @endphp
                    &copy; {{ date('Y') }} Sistem Penilaian Ujian Sidang. By @ ViruzLab. Version 1.0.{{ $commitCount }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
