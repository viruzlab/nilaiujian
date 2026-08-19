<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Yudisium - {{ optional($jadwal->mahasiswa)->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 1cm 18mm 12mm 18mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #e5e7eb;
            line-height: 1.5;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 60px auto 20px;
            background: white;
            padding: 1cm 18mm 12mm 18mm;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        }

        @media print {
            body {
                background: white;
            }

            .page {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
            }

            .no-print {
                display: none !important;
            }
        }

        /* ========== KOP SURAT ========== */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 4px solid #000;
            padding-bottom: 8px;
            margin-bottom: 10px; /* Diperkecil dari 15px */
            gap: 2px;
        }

        .kop-logo {
            flex-shrink: 0;
        }

        .kop-logo img {
            height: 3cm;
            width: 3cm;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            line-height: 1.3;
        }

        .kop-text .line1 {
            font-size: 14pt;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .kop-text .line2 {
            font-size: 14pt;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .kop-text .line3 {
            font-size: 14pt;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .kop-text .line4 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .kop-text .line5 {
            font-size: 12pt;
            margin-top: 1px;
            white-space: nowrap;
        }

        .kop-text .line6 {
            font-size: 12pt;
            white-space: nowrap;
        }

        /* ========== JUDUL ========== */
        .title {
            text-align: center;
            margin: 15px 0 8px 0; /* Diperkecil dari 20px */
        }

        .title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 2px;
            margin-bottom: 3px;
        }

        .title .nomor {
            font-size: 12pt;
        }

        /* ========== KONTEN ========== */
        .body-text {
            text-align: justify;
        }

        .body-text p {
            text-indent: 0;
            margin-bottom: 8px;
        }

        .data-mhs {
            margin: 8px 0 8px 30px;
        }

        .data-mhs table {
            border-collapse: collapse;
        }

        .data-mhs table td {
            padding: 1px 5px;
            vertical-align: top;
            font-size: 12pt;
        }

        .data-mhs table td:first-child {
            width: 120px;
        }

        .data-mhs table td:nth-child(2) {
            width: 12px;
            text-align: center;
        }

        /* ========== DATA MHS ========== */
        .data-mahasiswa {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        /* ========== SYARAT LIST ========== */
        .syarat-list {
            margin: 6px 0 8px 55px;
            line-height: 1.5;
        }

        .syarat-list li {
            margin-bottom: 1px;
            padding-left: 5px;
        }

        .dinyatakan {
            text-indent: 0 !important;
            margin-top: 8px !important;
        }

        /* ========== STATUS BOX ========== */
        .status-box {
            margin: 10px auto;
            width: 75%;
            border: 2px solid #000;
        }

        .status-row {
            display: flex;
        }

        .status-cell {
            flex: 1;
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            padding: 10px 8px;
            position: relative;
        }

        .status-cell:first-child {
            border-right: 2px solid #000;
        }

        /* ========== KELULUSAN BOX ========== */
        .kelulusan-container {
            margin: 0 auto 10px auto;
            max-width: 480px;
        }

        .lulusan-box {
            border: 2px solid #000;
            padding: 10px 15px;
            text-align: center;
        }

        .lulusan-box .lulusan-title {
            font-size: 14pt;
            margin-bottom: 8px;
        }

        .lulusan-box .ipk-row {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 5px;
        }

        .lulusan-box .ipk-item {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .lulusan-box .ipk-label {
            font-size: 14pt;
        }

        .lulusan-box .ipk-colon {
            font-weight: bold;
        }

        .lulusan-box .ipk-value {
            font-size: 14pt;
            font-weight: bold;
        }

        /* ========== PENUTUP ========== */
        .penutup {
            margin-top: 12px;
            text-align: justify;
            font-size: 12pt;
            line-height: 1.5;
        }

        .penutup p {
            text-indent: 0;
            margin-bottom: 4px;
        }

        /* ========== FOOTER ========== */
        .ttd-box {
            float: right;
            text-align: left;
            position: relative;
            margin-right: 80px;
            margin-top: 15px; /* Diperkecil dari 25px */
        }

        .ttd-box .jabatan {
            margin-bottom: 40px; /* Diperkecil dari 50px */
        }

        .ttd-box .ttd-img img {
            position: absolute;
            top: -10px;
            left: -30px;
            width: 170px;
            height: auto;
            z-index: 1;
        }

        .catatan-box {
            clear: both;
            position: relative;
            top: -24px;
            margin-bottom: -24px;
        }

        .catatan-box p {
            text-indent: 0;
            margin-bottom: 4px;
        }

        /* ========== PRINT BAR ========== */
        .print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #059669, #047857);
            padding: 10px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
        }

        .print-bar .info {
            color: white;
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 14px;
        }

        .print-bar .btn-group {
            display: flex;
            gap: 8px;
        }

        .print-bar button {
            border: none;
            padding: 9px 22px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Segoe UI', system-ui, sans-serif;
            transition: all 0.2s;
        }

        .print-bar .btn-print {
            background: white;
            color: #059669;
        }

        .print-bar .btn-print:hover {
            background: #ecfdf5;
            transform: translateY(-1px);
        }

        .print-bar .btn-close {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .print-bar .btn-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>

<body>
    <!-- Print Bar -->
    <div class="print-bar no-print">
        <div class="info">
            <strong>📄 Pengumuman Yudisium</strong> — {{ optional($jadwal->mahasiswa)->nama }}
            ({{ optional($jadwal->mahasiswa)->nim }})
        </div>
        <div class="btn-group">
            <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
            <button class="btn-close" onclick="window.close()">✕ Tutup</button>
        </div>
    </div>

    <div class="page">

        <!-- ===== KOP SURAT ===== -->
        <div class="kop-surat">
            <div class="kop-logo">
                <img src="{{ asset('images/logoupi.png') }}" alt="Logo UPI">
            </div>
            <div class="kop-text">
                <div class="line1">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                <div class="line2">UNIVERSITAS PENDIDIKAN INDONESIA</div>
                <div class="line3">FAKULTAS PENDIDIKAN EKONOMI DAN BISNIS</div>
                <div class="line4">PROGRAM STUDI ILMU EKONOMI DAN KEUANGAN ISLAM</div>
                <div class="line5">Jalan Dr. Setiabudhi No.229 Bandung 40154</div>
                <div class="line6">Laman http://ieki.upi.edu; surel/e-mail: ieki@upi.edu</div>
            </div>
        </div>

        <!-- ===== JUDUL ===== -->
        <div class="title">
            <h2>PENGUMUMAN YUDISIUM</h2>
            <div class="nomor">Nomor: {{ $nomorSurat }}/UN40.A7.5.7/PK.03.06/{{ date('Y') }}</div>
        </div>

        <!-- ===== ISI SURAT ===== -->
        <div class="body-text">
            <p>Berdasarkan data akademik di Prodi Ilmu Ekonomi dan Keuangan Islam, Fakultas Pendidikan Ekonomi dan
                Bisnis Universitas Pendidikan Indonesia, mahasiswa berikut:</p>

            <div class="data-mhs">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><strong>{{ optional($jadwal->mahasiswa)->nama ?? '-' }} /
                                {{ optional($jadwal->mahasiswa)->nim ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>Ilmu Ekonomi dan Keuangan Islam</td>
                    </tr>
                    <tr>
                        <td>Fakultas</td>
                        <td>:</td>
                        <td>Pendidikan Ekonomi dan Bisnis</td>
                    </tr>
                </table>
            </div>

            <p style="text-indent: 0;">Telah memenuhi semua ketentuan akademik pada Program Pendidikan Sarjana di
                Universitas Pendidikan Indonesia, yaitu:</p>

            <ol class="syarat-list">
                <li>menyelesaikan seluruh persyaratan administratif.</li>
                <li>menyelesaikan seluruh persyaratan akademik, termasuk telah menyelesaikan semua mata kuliah;</li>
                <li>menyelesaikan tugas akhir skripsi dan atau publikasi dalam jurnal yang direkognisi;</li>
                <li>telah melakukan ujian sidang sarjana.</li>
            </ol>

            <p class="dinyatakan">Dengan ini dinyatakan:</p>
        </div>

        <!-- ===== STATUS LULUS / TIDAK LULUS ===== -->
        <div class="status-box">
            <div class="status-row">
                <div class="status-cell" style="{{ !$isLulus ? 'text-decoration: line-through;' : '' }}">
                    LULUS
                </div>
                <div class="status-cell" style="{{ $isLulus ? 'text-decoration: line-through;' : '' }}">
                    TIDAK LULUS
                </div>
            </div>
        </div>

        <!-- ===== LULUSAN INFO ===== -->
        <div class="lulusan-box">
            <div class="lulusan-title">Lulusan Program Studi Ilmu Ekonomi dan Keuangan Islam Ke - <strong>{{ $lulusanKe }}</strong></div>
            <div class="ipk-row">
                <div class="ipk-item">
                    <span class="ipk-label">IPK</span>
                    <span class="ipk-colon">:</span>
                    <span class="ipk-value">{{ number_format($nilaiAkhirAngka, 2) }}</span>
                </div>
                <div class="ipk-item">
                    <span class="ipk-label">Predikat</span>
                    <span class="ipk-colon">:</span>
                    <span class="ipk-value">{{ $mutuAkhirPredikat }}</span>
                </div>
            </div>
        </div>

        <!-- ===== PENUTUP ===== -->
        <div class="penutup">
            <p>Semoga ilmu yang diperoleh saudara akan bermanfaat agar anda menjadi pribadi yang lebih baik. Mampu
                memberikan kontribusi nyata bagi diri sendiri, keluarga, dan lingkungan baik dalam tataran lokal,
                regional, nasional, maupun global, khususnya dalam bidang ekonomi, bisnis, dan filantropi Islam.</p>
            <p>Semoga Allah SWT senantiasa membimbing dan melindungi kita semua.</p>
        </div>

        <!-- ===== FOOTER (CATATAN & TTD) ===== -->
        <div class="ttd-box">
            <div class="jabatan">Ketua,</div>
            <div class="ttd-img">
                <img src="{{ asset('images/ttd_ketua.png') }}" alt="Tanda Tangan Ketua">
            </div>
            <div class="nama-ttd">Aas Nurasyiah</div>
            <div class="nip">NIP 198406072014042001</div>
        </div>

        <div class="catatan-box">
            <p>Catatan:</p>
            <p>*) Ijazah, transkrip akademik, dan SKPI akan diserahkan pada saat wisuda.</p>
            <p>*) Surat Keterangan Kelulusan akan dikeluarkan oleh fakultas</p>
        </div>

    </div>

</body>

</html>
