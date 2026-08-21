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
            width: 100%;
            margin: 0;
            background: white;
            padding: 0;
            box-shadow: none;
        }

        /* ========== KOP SURAT ========== */

    <div class="page">

        <!-- ===== KOP SURAT ===== -->
        <div class="kop-surat">
            <div class="kop-logo">
                <img src="{{ public_path('images/logoupi.png') }}" alt="Logo UPI">
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
