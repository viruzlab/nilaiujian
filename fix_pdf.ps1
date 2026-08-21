
$content = @"
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Yudisium - {{ optional(`$jadwal->mahasiswa)->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4; margin: 1cm 18mm 12mm 18mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; background: #fff; line-height: 1.5; }
        .page { width: 100%; margin: 0; padding: 0; }

        /* ========== KOP SURAT ========== */
        .kop-surat { border-bottom: 4px solid #000; padding-bottom: 8px; margin-bottom: 10px; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-logo { width: 15%; text-align: left; vertical-align: middle; }
        .kop-logo img { width: 100px; height: auto; }
        .kop-text { width: 85%; text-align: center; vertical-align: middle; line-height: 1.3; }
        .kop-text .line1 { font-size: 14pt; letter-spacing: 0.3px; }
        .kop-text .line2 { font-size: 14pt; text-transform: uppercase; }
        .kop-text .line3 { font-size: 14pt; text-transform: uppercase; }
        .kop-text .line4 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .line5 { font-size: 12pt; margin-top: 2px; }
        .kop-text .line6 { font-size: 12pt; }

        /* ========== JUDUL ========== */
        .title { text-align: center; margin: 15px 0 10px 0; }
        .title h2 { font-size: 14pt; font-weight: bold; text-decoration: underline; letter-spacing: 1px; margin-bottom: 3px; }
        .title .nomor { font-size: 12pt; }

        /* ========== KONTEN ========== */
        .body-text { text-align: justify; }
        .body-text p { text-indent: 0; margin-bottom: 8px; }
        .data-mhs { margin: 10px 0 10px 30px; }
        .data-mhs table { border-collapse: collapse; }
        .data-mhs table td { padding: 2px 5px; vertical-align: top; font-size: 12pt; }
        .data-mhs table td:first-child { width: 120px; }
        .data-mhs table td:nth-child(2) { width: 15px; text-align: center; }

        /* ========== SYARAT LIST ========== */
        .syarat-list { margin: 10px 0 10px 55px; line-height: 1.5; }
        .syarat-list li { margin-bottom: 2px; padding-left: 5px; }
        .dinyatakan { margin-top: 10px; }

        /* ========== STATUS BOX ========== */
        .status-box { margin: 10px auto; width: 75%; border: 2px solid #000; }
        .status-table { width: 100%; border-collapse: collapse; }
        .status-cell { width: 50%; text-align: center; font-weight: bold; font-size: 14pt; padding: 8px; }
        .status-cell:first-child { border-right: 2px solid #000; }

        /* ========== LULUSAN BOX ========== */
        .lulusan-box { border: 2px solid #000; padding: 10px 15px; text-align: center; margin: 15px auto; width: 75%; }
        .lulusan-title { font-size: 14pt; margin-bottom: 10px; }
        .ipk-table { width: 100%; margin: 0 auto; }
        .ipk-table td { text-align: center; }
        .ipk-label { font-size: 14pt; }
        .ipk-value { font-size: 14pt; font-weight: bold; }

        /* ========== PENUTUP ========== */
        .penutup { margin-top: 15px; text-align: justify; font-size: 12pt; line-height: 1.5; }
        .penutup p { margin-bottom: 5px; }

        /* ========== FOOTER ========== */
        .ttd-box { float: right; text-align: left; width: 250px; margin-top: 15px; }
        .ttd-box .jabatan { margin-bottom: 10px; }
        .ttd-box .ttd-img img { width: 150px; height: auto; margin-left: -20px; }
        .catatan-box { clear: both; margin-top: 30px; font-size: 11pt; }
        .catatan-box p { margin-bottom: 3px; }
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
            <div class="nomor">Nomor: {{ `$nomorSurat }}/UN40.A7.5.7/PK.03.06/{{ date('Y') }}</div>
        </div>

        <!-- ===== ISI SURAT ===== -->
        <div class="body-text">
            <p>Berdasarkan data akademik di Prodi Ilmu Ekonomi dan Keuangan Islam, Fakultas Pendidikan Ekonomi dan Bisnis Universitas Pendidikan Indonesia, mahasiswa berikut:</p>

            <div class="data-mhs">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td><strong>{{ optional(`$jadwal->mahasiswa)->nama ?? '-' }} / {{ optional(`$jadwal->mahasiswa)->nim ?? '-' }}</strong></td>
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

            <p style="text-indent: 0;">Telah memenuhi semua ketentuan akademik pada Program Pendidikan Sarjana di Universitas Pendidikan Indonesia, yaitu:</p>

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
            <table class="status-table">
                <tr>
                    <td class="status-cell" style="{{ !`$isLulus ? 'text-decoration: line-through;' : '' }}">LULUS</td>
                    <td class="status-cell" style="{{ `$isLulus ? 'text-decoration: line-through;' : '' }}">TIDAK LULUS</td>
                </tr>
            </table>
        </div>

        <!-- ===== LULUSAN INFO ===== -->
        <div class="lulusan-box">
            <div class="lulusan-title">Lulusan Program Studi Ilmu Ekonomi dan Keuangan Islam Ke - <strong>{{ `$lulusanKe }}</strong></div>
            <table class="ipk-table">
                <tr>
                    <td>
                        <span class="ipk-label">IPK</span> <strong>:</strong> <span class="ipk-value">{{ number_format(`$nilaiAkhirAngka, 2) }}</span>
                    </td>
                    <td>
                        <span class="ipk-label">Predikat</span> <strong>:</strong> <span class="ipk-value">{{ `$mutuAkhirPredikat }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ===== PENUTUP ===== -->
        <div class="penutup">
            <p>Semoga ilmu yang diperoleh saudara akan bermanfaat agar anda menjadi pribadi yang lebih baik. Mampu memberikan kontribusi nyata bagi diri sendiri, keluarga, dan lingkungan baik dalam tataran lokal, regional, nasional, maupun global, khususnya dalam bidang ekonomi, bisnis, dan filantropi Islam.</p>
            <p>Semoga Allah SWT senantiasa membimbing dan melindungi kita semua.</p>
        </div>

        <!-- ===== FOOTER (CATATAN & TTD) ===== -->
        <div class="ttd-box">
            <div class="jabatan">Ketua,</div>
            <div class="ttd-img">
                <img src="{{ public_path('images/ttd_ketua.png') }}" alt="Tanda Tangan Ketua">
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
"@
Set-Content -Path "resources/views/admin/jadwal/cetak-yudisium-pdf.blade.php" -Value $content -Encoding UTF8

