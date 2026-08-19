<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pengumuman Yudisium - Mahasiswa</title>
    <!-- Add Tailwind CSS via CDN for quick styling since this is a new public page -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        <div>
            <div class="text-center">
                <img class="mx-auto h-24 w-auto" src="{{ asset('images/logoupi.png') }}" alt="Logo UPI">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Pengumuman Yudisium
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Prodi Ilmu Ekonomi dan Keuangan Islam
                </p>
            </div>
        </div>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('mahasiswa.pengumuman.cari') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                    <input id="nim" name="nim" type="text" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1" placeholder="Masukkan NIM Anda">
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Potongan Nama</label>
                    <input id="nama" name="nama" type="text" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm mt-1" placeholder="Masukkan minimal 1 kata nama Anda">
                    <p class="text-xs text-gray-500 mt-1">Contoh: Jika nama "Budi Santoso", ketik "Budi" atau "Santoso".</p>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Cari Pengumuman
                </button>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('dosen.login') }}" class="text-sm text-green-600 hover:text-green-500">Kembali ke Beranda</a>
            </div>
        </form>
    </div>
</body>
</html>
