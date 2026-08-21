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
            margin: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            line-height: 1.15;
            padding: 8mm 18mm 8mm 18mm; /* Menghemat 2mm atas, 4mm bawah */
        }

        .page {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* ========== KOP SURAT ========== */
        .kop-surat {
            border-bottom: 4px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px; 
            text-align: center;
        }

        .kop-table {
            margin: 0 auto;
            width: auto;
            border-collapse: collapse;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            padding-right: 2px;
        }

        .kop-logo img {
            height: 3cm;
            width: 3cm;
        }

        .kop-text {
            text-align: center;
            line-height: 1.25;
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
            margin: 6px 0 16px 0; /* Ditambah margin bawah agar tidak menempel */
        }

        .title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 2px;
            margin-bottom: 2px;
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
            margin-bottom: 6px;
        }

        .data-mhs {
            margin: 6px 0 6px 30px;
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

        /* ========== SYARAT LIST ========== */
        .syarat-list {
            margin: 4px 0 6px 30px;
            line-height: 1.15;
            list-style-type: none;
            counter-reset: syarat-counter;
        }

        .syarat-list li {
            position: relative;
            margin-bottom: 2px;
            padding-left: 24px;
        }

        .syarat-list li::before {
            content: "(" counter(syarat-counter) ")";
            counter-increment: syarat-counter;
            position: absolute;
            left: 0;
        }

        .dinyatakan {
            text-indent: 0 !important;
            margin-top: 6px !important;
        }

        /* ========== STATUS BOX ========== */
        .status-box {
            margin: 6px auto; 
            width: 75%;
            border: 2px solid #000;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
        }

        .status-cell {
            width: 50%;
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            padding: 6px 8px;
        }

        .status-cell:first-child {
            border-right: 2px solid #000;
        }

        /* ========== LULUSAN BOX ========== */
        .lulusan-grid {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin: 8px auto;
        }
        
        .lulusan-grid td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }

        .lulusan-grid .title-row td {
            border-bottom: 2px solid #000;
            text-align: center;
            font-size: 14pt;
        }

        .lulusan-grid .label-cell {
            text-align: center;
            font-size: 14pt;
        }

        .lulusan-grid .colon-cell {
            text-align: center;
            font-size: 14pt;
            width: 1%;
            white-space: nowrap;
        }

        .lulusan-grid .value-cell {
            text-align: center;
            font-size: 22pt;
            font-weight: bold;
        }

        /* ========== PENUTUP ========== */
        .penutup {
            margin-top: 8px;
            text-align: justify;
            font-size: 12pt;
            line-height: 1.15;
        }

        .penutup p {
            text-indent: 0;
            margin-bottom: 4px;
        }

        /* ========== FOOTER ========== */
        .ttd-box {
            position: relative;
        }

        .ttd-box .jabatan {
            margin-bottom: 35px; /* Diperkecil lagi */
        }

        /* Untuk DOMPDF kita pakai margin negatif dibanding position absolute */
        .ttd-box .ttd-img img {
            width: 170px;
            height: auto;
            margin-left: -30px;
            margin-top: -20px;
            margin-bottom: -10px;
        }

        .catatan-box {
            font-size: 11pt;
        }

        .catatan-box p {
            text-indent: 0;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- ===== KOP SURAT ===== -->
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo">
                        <img src="{{ public_path('images/logoupi.png') }}" alt="Logo UPI">
                    </td>
                    <td class="kop-text">
                        <div class="line1">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                        <div class="line2">UNIVERSITAS PENDIDIKAN INDONESIA</div>
                        <div class="line3">FAKULTAS PENDIDIKAN EKONOMI DAN BISNIS</div>
                        <div class="line4">PROGRAM STUDI ILMU EKONOMI DAN KEUANGAN ISLAM</div>
                        <div class="line5">Jalan Dr. Setiabudhi No.229 Bandung 40154</div>
                        <div class="line6">Laman http://ieki.upi.edu; surel/e-mail: ieki@upi.edu</div>
                    </td>
                </tr>
            </table>
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
                <li>menyelesaikan seluruh persyaratan administratif;</li>
                <li>menyelesaikan seluruh persyaratan akademik, termasuk telah menyelesaikan semua mata kuliah;</li>
                <li>menyelesaikan tugas akhir skripsi dan atau publikasi dalam jurnal yang direkognisi;</li>
                <li>telah melakukan ujian sidang sarjana.</li>
            </ol>

            <p class="dinyatakan">Dengan ini dinyatakan:</p>
        </div>

        <!-- ===== STATUS LULUS / TIDAK LULUS ===== -->
        <div class="status-box">
            <table class="status-table">
                <tr>
                    <td class="status-cell" style="{{ !$isLulus ? 'text-decoration: line-through;' : '' }}">LULUS</td>
                    <td class="status-cell" style="{{ $isLulus ? 'text-decoration: line-through;' : '' }}">TIDAK LULUS</td>
                </tr>
            </table>
        </div>

        <!-- ===== LULUSAN INFO (GRID) ===== -->
        <table class="lulusan-grid">
            <tr class="title-row">
                <td colspan="6">
                    Lulusan Program Studi Ilmu Ekonomi dan Keuangan Islam Ke - <strong>{{ $lulusanKe }}</strong>
                </td>
            </tr>
            <tr>
                <td class="label-cell" style="width: 15%; text-align: left; padding-left: 12px;">IPK</td>
                <td class="colon-cell">:</td>
                <td class="value-cell" style="width: 30%; border-right: 2px solid #000;">{{ number_format($nilaiAkhirAngka, 2, ',', '.') }}</td>
                <td class="label-cell" style="width: 15%; text-align: left; padding-left: 12px;">Predikat</td>
                <td class="colon-cell">:</td>
                <td class="value-cell" style="width: 30%;">{{ $mutuAkhirPredikat }}</td>
            </tr>
        </table>

        <!-- ===== PENUTUP ===== -->
        <div class="penutup">
            <p>Semoga ilmu yang diperoleh saudara akan bermanfaat agar anda menjadi pribadi yang lebih baik. Mampu
                memberikan kontribusi nyata bagi diri sendiri, keluarga, dan lingkungan baik dalam tataran lokal,
                regional, nasional, maupun global, khususnya dalam bidang ekonomi, bisnis, dan filantropi Islam.</p>
            <p>Semoga Allah SWT senantiasa membimbing dan melindungi kita semua.</p>
        </div>

        <!-- ===== FOOTER (CATATAN & TTD) ===== -->
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 70%;"></td>
                <td style="width: 30%; text-align: right;">
                    <div style="display: inline-block; text-align: left; width: 220px; margin-right: 20px;">
                        <div class="ttd-box">
                            <div class="jabatan">Ketua,</div>
                            <div class="ttd-img">
                                <img src="{{ public_path('images/ttd_ketua.png') }}" alt="Tanda Tangan Ketua">
                            </div>
                            <div class="nama-ttd">Aas Nurasyiah</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; width: 70%;">
                    <div class="catatan-box">
                        <p>Catatan:</p>
                        <p style="white-space: nowrap;">*) Ijazah, transkrip akademik, dan SKPI akan diserahkan pada saat wisuda.</p>
                        <p style="white-space: nowrap;">*) Surat Keterangan Kelulusan akan dikeluarkan oleh fakultas</p>
                    </div>
                </td>
                <td style="vertical-align: top; width: 30%; text-align: right;">
                    <div style="display: inline-block; text-align: left; width: 220px; margin-right: 20px;">
                        <div class="ttd-box">
                            <div class="nip">NIP 198406072014042001</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
