<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Identitas Pasien - RS Cahya Medika</title>
    <style>
        @page {
            margin: 20px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .header-logo {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header-sublogo {
            font-size: 11px;
            color: #333;
            font-weight: bold;
        }
        .header-right {
            text-align: right;
            font-size: 9px;
            color: #222;
            line-height: 1.2;
        }
        .title-box {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 15px 0;
            letter-spacing: 0.5px;
        }
        .section-header {
            background-color: #a3a8b0;
            color: #000;
            font-weight: bold;
            font-size: 11px;
            padding: 4px 8px;
            margin-top: 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
            border: 1px solid #777;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .form-table td {
            padding: 3px 2px;
            vertical-align: top;
        }
        .label-col {
            width: 160px;
        }
        .colon-col {
            width: 10px;
            text-align: center;
        }
        .value-dots {
            border-bottom: 1px dotted #444;
            min-height: 14px;
            display: inline-block;
            width: 100%;
            font-weight: 500;
        }
        .checkbox-group {
            display: inline-block;
        }
        .checkbox-item {
            display: inline-block;
            margin-right: 12px;
        }
        .box-square {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            text-align: center;
            line-height: 9px;
            font-size: 8px;
            font-weight: bold;
            margin-right: 3px;
        }
        .checked {
            background-color: #111;
            color: #fff;
        }
        .sub-note {
            font-size: 9px;
            font-style: italic;
            margin-top: -5px;
            margin-bottom: 5px;
        }
        .footer-note {
            position: fixed;
            bottom: 15px;
            left: 30px;
            font-size: 9px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- HEADER RUMAH SAKIT -->
    <table class="header-table">
        <tr>
            <td style="width: 60%; vertical-align: middle;">
                <div class="header-logo">RUMAH SAKIT CAHYA MEDIKA BONDOWOSO</div>
                <div class="header-sublogo">IHC | PT Balai Nusantara Medika</div>
            </td>
            <td class="header-right">
                <strong>Jl. Raya Pakisan No. 24, Bataan</strong><br>
                Tenggarang - Bondowoso<br>
                Telp. (0332) 5557554<br>
                Email : rscahyamedika@gmail.com
            </td>
        </tr>
    </table>

    <!-- JUDUL FORMULIR -->
    <div class="title-box">
        FORMULIR IDENTITAS PASIEN
    </div>

    <!-- DATA UMUM PASIEN -->
    <div class="section-header">
        DATA UMUM PASIEN
    </div>

    <table class="form-table">
        <tr>
            <td class="label-col">No. Rekam Medis</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->no_rm ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">No. KTP / SIM</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->nik ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Tanggal Registrasi</td>
            <td class="colon-col">:</td>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding:0;"><span class="value-dots">{{ $pendaftaran->created_at ? $pendaftaran->created_at->format('d/m/Y') : date('d/m/Y') }}</span></td>
                        <td style="width: 15%; text-align: right; padding-right: 5px;">Jam :</td>
                        <td style="width: 35%; padding:0;"><span class="value-dots">{{ $pendaftaran->created_at ? $pendaftaran->created_at->format('H:i') : date('H:i') }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="label-col">Nama Pasien</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->nama_lengkap ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Tempat, Tanggal Lahir</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ ($pasien->tempat_lahir ? $pasien->tempat_lahir . ', ' : '') . ($pasien->tanggal_lahir ? $pasien->tanggal_lahir->format('d-m-Y') : '-') }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Pekerjaan</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->pekerjaan ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>
                <span class="checkbox-item"><span class="box-square {{ ($pasien->jenis_kelamin ?? 'L') === 'L' ? 'checked' : '' }}">{{ ($pasien->jenis_kelamin ?? 'L') === 'L' ? 'v' : '' }}</span> Laki - laki</span>
                <span class="checkbox-item"><span class="box-square {{ ($pasien->jenis_kelamin ?? '') === 'P' ? 'checked' : '' }}">{{ ($pasien->jenis_kelamin ?? '') === 'P' ? 'v' : '' }}</span> Perempuan</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Agama</td>
            <td class="colon-col">:</td>
            <td>
                @php $agama = strtolower($pasien->agama ?? 'islam'); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($agama, 'islam') ? 'checked' : '' }}">{{ str_contains($agama, 'islam') ? 'v' : '' }}</span> Islam</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($agama, 'kristen') ? 'checked' : '' }}">{{ str_contains($agama, 'kristen') ? 'v' : '' }}</span> Kristen</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($agama, 'katolik') ? 'checked' : '' }}">{{ str_contains($agama, 'katolik') ? 'v' : '' }}</span> Katolik</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($agama, 'budha') ? 'checked' : '' }}">{{ str_contains($agama, 'budha') ? 'v' : '' }}</span> Budha</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($agama, 'hindu') ? 'checked' : '' }}">{{ str_contains($agama, 'hindu') ? 'v' : '' }}</span> Hindu</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Pendidikan Terakhir</td>
            <td class="colon-col">:</td>
            <td>
                @php $pend = strtolower($pasien->pendidikan ?? ''); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($pend, 'sd') ? 'checked' : '' }}">{{ str_contains($pend, 'sd') ? 'v' : '' }}</span> SD</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($pend, 'smp') ? 'checked' : '' }}">{{ str_contains($pend, 'smp') ? 'v' : '' }}</span> SMP</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($pend, 'sma') || str_contains($pend, 'slta') ? 'checked' : '' }}">{{ str_contains($pend, 'sma') || str_contains($pend, 'slta') ? 'v' : '' }}</span> SMA</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($pend, 'diploma') || str_contains($pend, 'd3') ? 'checked' : '' }}">{{ str_contains($pend, 'diploma') || str_contains($pend, 'd3') ? 'v' : '' }}</span> Diploma</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($pend, 'sarjana') || str_contains($pend, 's1') || str_contains($pend, 's2') ? 'checked' : '' }}">{{ str_contains($pend, 'sarjana') || str_contains($pend, 's1') || str_contains($pend, 's2') ? 'v' : '' }}</span> Sarjana</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Status Perkawinan</td>
            <td class="colon-col">:</td>
            <td>
                @php $st = strtolower($pasien->status_pernikahan ?? ''); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($st, 'belum') || str_contains($st, 'lajang') ? 'checked' : '' }}">{{ str_contains($st, 'belum') || str_contains($st, 'lajang') ? 'v' : '' }}</span> Belum Kawin</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($st, 'kawin') && !str_contains($st, 'belum') ? 'checked' : '' }}">{{ str_contains($st, 'kawin') && !str_contains($st, 'belum') ? 'v' : '' }}</span> Kawin</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($st, 'duda') ? 'checked' : '' }}">{{ str_contains($st, 'duda') ? 'v' : '' }}</span> Duda</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($st, 'janda') ? 'checked' : '' }}">{{ str_contains($st, 'janda') ? 'v' : '' }}</span> Janda</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Alamat</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->alamat ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col"></td>
            <td class="colon-col"></td>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 12%; padding:0;">Desa</td>
                        <td style="width: 38%; padding:0;"><span class="value-dots">{{ $pasien->kelurahan ?? '-' }}</span></td>
                        <td style="width: 15%; text-align: right; padding-right:5px;">Kecamatan</td>
                        <td style="width: 35%; padding:0;"><span class="value-dots">{{ $pasien->kecamatan ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td style="width: 12%; padding:2px 0 0 0;">Kabupaten</td>
                        <td style="width: 38%; padding:2px 0 0 0;"><span class="value-dots">{{ $pasien->kabupaten ?? 'Bondowoso' }}</span></td>
                        <td style="width: 15%; text-align: right; padding-right:5px;">Provinsi</td>
                        <td style="width: 35%; padding:2px 0 0 0;"><span class="value-dots">{{ $pasien->provinsi ?? 'Jawa Timur' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="label-col">Warga Negara</td>
            <td class="colon-col">:</td>
            <td>
                @php $wn = strtoupper($pasien->warga_negara ?? 'WNI'); @endphp
                <span class="checkbox-item"><span class="box-square {{ $wn === 'WNI' ? 'checked' : '' }}">{{ $wn === 'WNI' ? 'v' : '' }}</span> WNI</span>
                <span class="checkbox-item"><span class="box-square {{ $wn === 'WNA' ? 'checked' : '' }}">{{ $wn === 'WNA' ? 'v' : '' }}</span> WNA</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Suku</td>
            <td class="colon-col">:</td>
            <td>
                @php $suku = strtolower($pasien->suku ?? 'jawa'); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($suku, 'jawa') ? 'checked' : '' }}">{{ str_contains($suku, 'jawa') ? 'v' : '' }}</span> Jawa</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($suku, 'madura') ? 'checked' : '' }}">{{ str_contains($suku, 'madura') ? 'v' : '' }}</span> Madura</span>
                <span class="checkbox-item"><span class="box-square {{ !str_contains($suku, 'jawa') && !str_contains($suku, 'madura') ? 'checked' : '' }}">{{ !str_contains($suku, 'jawa') && !str_contains($suku, 'madura') ? 'v' : '' }}</span> Lainnya: {{ !str_contains($suku, 'jawa') && !str_contains($suku, 'madura') ? $pasien->suku : '' }}</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Golongan Darah</td>
            <td class="colon-col">:</td>
            <td>
                @php $goldar = strtoupper($pasien->golongan_darah ?? ''); @endphp
                <span class="checkbox-item"><span class="box-square {{ $goldar === 'A' ? 'checked' : '' }}">{{ $goldar === 'A' ? 'v' : '' }}</span> A</span>
                <span class="checkbox-item"><span class="box-square {{ $goldar === 'B' ? 'checked' : '' }}">{{ $goldar === 'B' ? 'v' : '' }}</span> B</span>
                <span class="checkbox-item"><span class="box-square {{ $goldar === 'AB' ? 'checked' : '' }}">{{ $goldar === 'AB' ? 'v' : '' }}</span> AB</span>
                <span class="checkbox-item"><span class="box-square {{ $goldar === 'O' ? 'checked' : '' }}">{{ $goldar === 'O' ? 'v' : '' }}</span> O</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Nama Orang Tua</td>
            <td class="colon-col">:</td>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 10%; padding:0;">Ibu</td>
                        <td style="width: 40%; padding:0;"><span class="value-dots">{{ $pasien->nama_ibu ?? '-' }}</span></td>
                        <td style="width: 10%; text-align: right; padding-right: 5px;">Ayah</td>
                        <td style="width: 40%; padding:0;"><span class="value-dots">{{ $pasien->nama_ayah ?? '-' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="label-col">No. Telpon / HP</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->no_hp ?? $pasien->no_telepon ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Riwayat Alergi</td>
            <td class="colon-col">:</td>
            <td>
                @php $alergi = strtolower($pasien->riwayat_alergi ?? 'tidak ada'); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($alergi, 'tidak') ? 'checked' : '' }}">{{ str_contains($alergi, 'tidak') ? 'v' : '' }}</span> Tidak Ada</span>
                <span class="checkbox-item"><span class="box-square {{ !str_contains($alergi, 'tidak') && ($pasien->jenis_alergi || $alergi === 'ada') ? 'checked' : '' }}">{{ !str_contains($alergi, 'tidak') && ($pasien->jenis_alergi || $alergi === 'ada') ? 'v' : '' }}</span> Ada</span>
                @if(!str_contains($alergi, 'tidak') && $pasien->jenis_alergi)
                    <div style="margin-top: 3px;">Jenis Alergi : <span class="value-dots">{{ $pasien->jenis_alergi }}</span></div>
                @endif
            </td>
        </tr>
    </table>

    <!-- PENANGGUNG JAWAB / KELUARGA TERDEKAT -->
    <div class="section-header">
        PENANGGUNG JAWAB / KELUARGA TERDEKAT
    </div>
    <div class="sub-note">*Wajib diisi untuk anak usia &lt; 17 Tahun</div>

    <table class="form-table">
        <tr>
            <td class="label-col">Nama Penanggung Jawab</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->nama_pj ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>
                <span class="checkbox-item"><span class="box-square {{ ($pasien->jenis_kelamin_pj ?? 'L') === 'L' ? 'checked' : '' }}">{{ ($pasien->jenis_kelamin_pj ?? 'L') === 'L' ? 'v' : '' }}</span> Laki - laki</span>
                <span class="checkbox-item"><span class="box-square {{ ($pasien->jenis_kelamin_pj ?? '') === 'P' ? 'checked' : '' }}">{{ ($pasien->jenis_kelamin_pj ?? '') === 'P' ? 'v' : '' }}</span> Perempuan</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Hubungan Dengan Pasien</td>
            <td class="colon-col">:</td>
            <td>
                @php $hub = strtolower($pasien->hubungan_pj ?? ''); @endphp
                <span class="checkbox-item"><span class="box-square {{ str_contains($hub, 'orang tua') || str_contains($hub, 'ayah') || str_contains($hub, 'ibu') ? 'checked' : '' }}">{{ str_contains($hub, 'orang tua') || str_contains($hub, 'ayah') || str_contains($hub, 'ibu') ? 'v' : '' }}</span> Orang Tua</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($hub, 'anak') ? 'checked' : '' }}">{{ str_contains($hub, 'anak') ? 'v' : '' }}</span> Anak</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($hub, 'suami') ? 'checked' : '' }}">{{ str_contains($hub, 'suami') ? 'v' : '' }}</span> Suami</span>
                <span class="checkbox-item"><span class="box-square {{ str_contains($hub, 'istri') ? 'checked' : '' }}">{{ str_contains($hub, 'istri') ? 'v' : '' }}</span> Istri</span>
                <span class="checkbox-item"><span class="box-square {{ $hub && !str_contains($hub, 'orang tua') && !str_contains($hub, 'anak') && !str_contains($hub, 'suami') && !str_contains($hub, 'istri') ? 'checked' : '' }}">{{ $hub && !str_contains($hub, 'orang tua') && !str_contains($hub, 'anak') && !str_contains($hub, 'suami') && !str_contains($hub, 'istri') ? 'v' : '' }}</span> Lainnya</span>
            </td>
        </tr>
        <tr>
            <td class="label-col">Pekerjaan</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->pekerjaan_pj ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col">Alamat</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->alamat_pj ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="label-col"></td>
            <td class="colon-col"></td>
            <td>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 12%; padding:0;">Desa</td>
                        <td style="width: 38%; padding:0;"><span class="value-dots">{{ $pasien->kelurahan_pj ?? '-' }}</span></td>
                        <td style="width: 15%; text-align: right; padding-right:5px;">Kecamatan</td>
                        <td style="width: 35%; padding:0;"><span class="value-dots">{{ $pasien->kecamatan_pj ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td style="width: 12%; padding:2px 0 0 0;">Kabupaten</td>
                        <td style="width: 38%; padding:2px 0 0 0;"><span class="value-dots">{{ $pasien->kabupaten_pj ?? '-' }}</span></td>
                        <td style="width: 15%; text-align: right; padding-right:5px;">Provinsi</td>
                        <td style="width: 35%; padding:2px 0 0 0;"><span class="value-dots">{{ $pasien->provinsi_pj ?? '-' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="label-col">No. Telpon / HP</td>
            <td class="colon-col">:</td>
            <td><span class="value-dots">{{ $pasien->no_hp_pj ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="footer-note">
        * Revisi 1
    </div>

</body>
</html>
