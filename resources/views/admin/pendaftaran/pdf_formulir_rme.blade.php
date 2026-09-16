<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FORMULIR REKAM MEDIS UMUM - {{ $pendaftaran->pasien->nama_lengkap ?? 'PASIEN' }}</title>
    <style>
        @page {
            /* Format F4 / Folio / HVS: 215.9mm x 330.2mm */
            size: 215.9mm 330.2mm portrait;
            margin: 10mm 12mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif, Helvetica, Arial;
            font-size: 9.5px;
            color: #000000;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        .header-container {
            width: 100%;
            position: relative;
            margin-bottom: 10px;
            text-align: center;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .header-subtitle {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 3px 0 0 0;
        }
        .box-rahasia {
            position: absolute;
            right: 0;
            top: 0;
            border: 1.5px solid #000000;
            padding: 3px 10px;
            font-weight: bold;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            background-color: #ffffff;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
        }
        .form-table td, .form-table th {
            border: 1px solid #000000;
            padding: 5px 8px;
            vertical-align: top;
        }
        .bold {
            font-weight: bold;
        }
        .cb-box {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1.2px solid #000000;
            text-align: center;
            line-height: 10px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 4px;
            vertical-align: middle;
            font-family: 'DejaVu Sans', sans-serif;
            color: #000000;
        }
        .cb-item {
            display: inline-block;
            margin-right: 18px;
            margin-bottom: 3px;
            white-space: nowrap;
            vertical-align: middle;
        }
        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sub-table td {
            border: none;
            padding: 2px 0;
        }
    </style>
</head>
<body>

    @php
        $admisi = $pendaftaran->rme_data['admisi'] ?? [];
        $medis  = $pendaftaran->rme_data['medis'] ?? [];
        $pasien = $pendaftaran->pasien;
        $jk     = strtoupper($pasien->jenis_kelamin ?? '');
        $statusKawin = $pasien->status_pernikahan ?? '';
        $caraKb = $admisi['cara_kb'] ?? '';
        $caraMrs = $admisi['cara_mrs'] ?? '';
        $riwayatAlergi = $medis['riwayat_alergi'] ?? '';
        $keadaanKrs = $medis['keadaan_krs'] ?? '';
        $caraKrs = $medis['cara_krs'] ?? '';
    @endphp

    <!-- HEADER TITLE & BOX RAHASIA -->
    <div class="header-container">
        <div class="box-rahasia">RAHASIA</div>
        <div class="header-title">FORMULIR REKAM MEDIS UMUM</div>
        <div class="header-subtitle">(LEMBAR MASUK & KELUAR)</div>
    </div>

    <!-- MAIN FORMULAR TABLE -->
    <table class="form-table">
        <!-- KAMAR / KELAS / NO.TT -->
        <tr>
            <td colspan="2" style="padding: 6px 8px;">
                <span class="bold">Kamar / Kelas / No.TT :</span>
                <span style="margin-left: 12px;">{{ $admisi['kamar_kelas_tt'] ?? '' }}</span>
            </td>
        </tr>

        <!-- ROW: NO. REKAM MEDIK & DIRAWAT KE -->
        <tr>
            <td style="width: 55%;">
                <span class="bold">No. Rekam Medik :</span>
                <span style="font-size: 11px; font-weight: bold; margin-left: 10px;">{{ $pasien->no_rm ?? '-' }}</span>
            </td>
            <td style="width: 45%;">
                <span class="bold">DIRAWAT KE :</span>
                <span style="margin-left: 8px;">{{ $admisi['dirawat_ke'] ?? '1' }}</span>
            </td>
        </tr>

        <!-- ROW: NAMA PASIEN & SEX -->
        <tr>
            <td>
                <span class="bold">Nama Pasien :</span> {{ $pasien->nama_lengkap ?? '-' }}
            </td>
            <td>
                <span class="bold" style="margin-right: 10px;">Sex :</span>
                <span class="cb-item"><span class="cb-box">{!! $jk == 'L' ? '&#10004;' : '&nbsp;' !!}</span> Laki-laki</span>
                <span class="cb-item"><span class="cb-box">{!! $jk == 'P' ? '&#10004;' : '&nbsp;' !!}</span> Perempuan</span>
            </td>
        </tr>

        <!-- ROW: TGL LAHIR, UMUR, AGAMA -->
        <tr>
            <td>
                <span class="bold">Tanggal lahir :</span> {{ optional($pasien->tanggal_lahir)->format('d-m-Y') ?? '-' }}
                <span class="bold" style="margin-left: 20px;">Umur :</span> {{ $pasien->umur ?? '-' }} th
            </td>
            <td>
                <span class="bold">Agama :</span> {{ $pasien->agama ?? '' }}
            </td>
        </tr>

        <!-- ROW: STATUS PERKAWINAN -->
        <tr>
            <td colspan="2" style="padding: 6px 8px;">
                <span class="bold" style="margin-right: 12px;">Status Perkawinan :</span>
                <span class="cb-item"><span class="cb-box">{!! $statusKawin == 'Kawin' ? '&#10004;' : '&nbsp;' !!}</span> Kawin</span>
                <span class="cb-item"><span class="cb-box">{!! $statusKawin == 'Belum kawin' ? '&#10004;' : '&nbsp;' !!}</span> Belum kawin</span>
                <span class="cb-item"><span class="cb-box">{!! $statusKawin == 'Janda' ? '&#10004;' : '&nbsp;' !!}</span> Janda</span>
                <span class="cb-item"><span class="cb-box">{!! $statusKawin == 'Duda' ? '&#10004;' : '&nbsp;' !!}</span> Duda</span>
                <span class="cb-item"><span class="cb-box">{!! $statusKawin == 'Dibawah umur' ? '&#10004;' : '&nbsp;' !!}</span> Dibawah umur</span>
            </td>
        </tr>

        <!-- ROW: PEKERJAAN & DOKTER MERAWAT (1-6) -->
        <tr>
            <td>
                <span class="bold">Pekerjaan :</span> {{ $pasien->pekerjaan ?? '' }}
            </td>
            <td rowspan="4" style="vertical-align: top; padding: 6px 8px;">
                <div style="font-size: 8.5px; line-height: 1.5;">
                    <div style="margin-bottom: 2px;"><strong>1. Dr.</strong> {{ $pendaftaran->dokter->nama ?? '' }} <strong style="margin-left:8px;">Telp.</strong> {{ $pendaftaran->dokter->no_hp ?? '' }}</div>
                    <div style="margin-bottom: 2px;"><strong>2. Dr.</strong> ........................................................... <strong>Telp.</strong> ....................</div>
                    <div style="margin-bottom: 2px;"><strong>3. Dr.</strong> ........................................................... <strong>Telp.</strong> ....................</div>
                    <div style="margin-bottom: 2px;"><strong>4. Dr.</strong> ........................................................... <strong>Telp.</strong> ....................</div>
                    <div style="margin-bottom: 2px;"><strong>5. Dr.</strong> ........................................................... <strong>Telp.</strong> ....................</div>
                    <div><strong>6. Dr.</strong> ........................................................... <strong>Telp.</strong> ....................</div>
                </div>
            </td>
        </tr>

        <!-- ROW: PENDIDIKAN TERAKHIR -->
        <tr>
            <td>
                <span class="bold">Pendidikan terakhir :</span> {{ $pasien->pendidikan ?? '' }}
            </td>
        </tr>

        <!-- ROW: ALAMAT & TELP -->
        <tr>
            <td>
                <span class="bold">Alamat :</span> {{ $pasien->alamat ?? '' }} {{ $pasien->kabupaten ? ', ' . $pasien->kabupaten : '' }}
                <div style="margin-top: 3px;"><span class="bold">No.Telp/HP :</span> {{ $pasien->no_hp ?? '-' }}</div>
            </td>
        </tr>

        <!-- ROW: CARA KB -->
        <tr>
            <td style="padding: 6px 8px;">
                <span class="bold" style="display: inline-block; margin-bottom: 4px;">Cara KB :</span><br>
                <span class="cb-item"><span class="cb-box">{!! $caraKb == 'IUD' ? '&#10004;' : '&nbsp;' !!}</span> IUD</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKb == 'Pil' ? '&#10004;' : '&nbsp;' !!}</span> Pil</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKb == 'Kondom' ? '&#10004;' : '&nbsp;' !!}</span> Kondom</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKb == 'MOW' ? '&#10004;' : '&nbsp;' !!}</span> MOW</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKb == 'MOP' ? '&#10004;' : '&nbsp;' !!}</span> MOP</span>
                <span class="cb-item"><span class="cb-box">{!! ($caraKb != '' && !in_array($caraKb, ['IUD','Pil','Kondom','MOW','MOP'])) ? '&#10004;' : '&nbsp;' !!}</span> Lain</span>
            </td>
        </tr>

        <!-- ROW: NAMA AYAH/IBU/SUAMI/ISTRI & CARA MRS -->
        <tr>
            <td>
                <span class="bold">Nama Ayah / Ibu / Suami / Istri :</span> {{ $admisi['nama_keluarga_orangtua'] ?? ($pasien->nama_pj ?? '') }}
            </td>
            <td rowspan="4" style="vertical-align: top; padding: 6px 8px;">
                <div style="margin-bottom: 8px;">
                    <span class="bold" style="display: block; margin-bottom: 4px;">Cara MRS :</span>
                    <span class="cb-item"><span class="cb-box">{!! $caraMrs == 'Admission' ? '&#10004;' : '&nbsp;' !!}</span> Admission</span>
                    <span class="cb-item"><span class="cb-box">{!! $caraMrs == 'UGD' ? '&#10004;' : '&nbsp;' !!}</span> UGD</span><br>
                    <span class="cb-item"><span class="cb-box">{!! $caraMrs == 'Klinik Spesialis' ? '&#10004;' : '&nbsp;' !!}</span> Klinik Spesialis</span>
                    <span class="cb-item"><span class="cb-box">{!! $caraMrs == 'RS Lain' ? '&#10004;' : '&nbsp;' !!}</span> RS Lain</span><br>
                    <span class="cb-item"><span class="cb-box">{!! ($caraMrs != '' && !in_array($caraMrs, ['Admission','UGD','Klinik Spesialis','RS Lain'])) ? '&#10004;' : '&nbsp;' !!}</span> Lain - lain :</span>
                </div>
                <div style="margin-bottom: 8px;">
                    <span class="bold">Diagnosa masuk :</span> {{ $medis['diagnosa_masuk'] ?? ($pendaftaran->keluhan ?? '') }}
                </div>
                <div>
                    <span class="bold" style="display: block; margin-bottom: 4px;">Riwayat alergi :</span>
                    <span class="cb-item"><span class="cb-box">{!! ($riwayatAlergi == '' || str_contains($riwayatAlergi, 'Tidak')) ? '&#10004;' : '&nbsp;' !!}</span> Tidak</span>
                    <span class="cb-item"><span class="cb-box">{!! str_contains($riwayatAlergi, 'Ya') ? '&#10004;' : '&nbsp;' !!}</span> Ya : {{ $riwayatAlergi }}</span>
                </div>
            </td>
        </tr>

        <!-- ROW: PENANGGUNG BIAYA -->
        <tr>
            <td>
                <span class="bold">Penanggung Biaya :</span> {{ $admisi['pj_nama_hp'] ?? ($pendaftaran->penjamin ?? 'Umum') }}
                <div><span class="bold">Alamat :</span> {{ $pasien->alamat ?? '' }}</div>
                <div><span class="bold">No.Telp/HP :</span> {{ $pasien->no_hp ?? '-' }}</div>
            </td>
        </tr>

        <!-- ROW: KELUARGA TERDEKAT -->
        <tr>
            <td>
                <span class="bold">Nama Keluarga terdekat :</span> {{ $admisi['keluarga_terdekat_detail'] ?? ($pasien->nama_pj ?? '') }}
                <div><span class="bold">Alamat :</span> {{ $pasien->alamat ?? '' }}</div>
                <div><span class="bold">No.Telp/HP :</span> {{ $pasien->no_hp ?? '-' }}</div>
            </td>
        </tr>

        <!-- ROW: TANGGAL MRS, KRS, LAMA DIRAWAT -->
        <tr>
            <td>
                <table class="sub-table">
                    <tr>
                        <td style="width: 60%;"><span class="bold">Tanggal MRS :</span> {{ $pendaftaran->tanggal_berobat ? \Carbon\Carbon::parse($pendaftaran->tanggal_berobat)->format('d-m-Y') : date('d-m-Y') }}</td>
                        <td style="width: 40%;"><span class="bold">Jam :</span> {{ $admisi['jam_mrs'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td><span class="bold">Tanggal KRS :</span> {{ $medis['tanggal_jam_krs'] ?? date('d-m-Y') }}</td>
                        <td><span class="bold">Jam :</span> {{ $medis['jam_krs'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><span class="bold">Lama dirawat :</span> {{ $medis['lama_dirawat'] ?? '' }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- ROW: DIAGNOSA UTAMA & ICD X -->
        <tr>
            <td style="width: 55%; height: 35px; padding: 6px 8px;">
                <span class="bold">Diagnosa Utama :</span>
                <span style="font-weight: bold; margin-left: 8px;">{{ $medis['diagnosa_utama'] ?? '' }}</span>
            </td>
            <td style="width: 45%; text-align: center; background-color: #f8fafc; padding: 6px 8px;">
                <span class="bold">ICD X</span><br>
                <strong style="font-size: 13px; color: #000;">{{ $medis['icd_code'] ?? '' }}</strong>
            </td>
        </tr>

        <!-- ROW: KOMPLIKASI -->
        <tr>
            <td colspan="2" style="padding: 6px 8px;">
                <span class="bold">Komplikasi :</span> {{ $medis['komplikasi'] ?? '' }}<br>
            </td>
        </tr>

        <!-- ROW: DIAGNOSA SEKUNDER -->
        <tr>
            <td colspan="2" style="padding: 6px 8px;">
                <span class="bold">Diagnosa sekunder :</span> {{ $medis['diagnosa_sekunder'] ?? '' }}<br>
            </td>
        </tr>

        <!-- ROW: PENYEBAB CEDERA & KERACUNAN / MORFOLOGI NEOPLASMA -->
        <tr>
            <td colspan="2" style="padding: 6px 8px;">
                <span class="bold">Penyebab cedera & keracunan / Morfologi Neoplasma :</span> {{ $medis['penyebab_cedera'] ?? '' }}
            </td>
        </tr>

        <!-- ROW: OPERASI / TINDAKAN TABLE -->
        <tr>
            <td colspan="2" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr style="text-align: center; font-weight: bold; background-color: #f1f5f9;">
                        <td style="width: 35%; border-left: none; border-top: none; padding: 5px;">Operasi / Tindakan :</td>
                        <td style="width: 15%; border-top: none; padding: 5px;">Golongan Operasi</td>
                        <td style="width: 15%; border-top: none; padding: 5px;">Tgl. Operasi</td>
                        <td style="width: 18%; border-top: none; padding: 5px;">Jenis Anastesi</td>
                        <td style="width: 17%; border-right: none; border-top: none; padding: 5px;">Kode Operasi</td>
                    </tr>
                    <tr style="height: 28px; text-align: center;">
                        <td style="border-left: none; border-bottom: none; text-align: left; padding: 5px 8px;">{{ $medis['tindakan_nama'] ?? '' }}</td>
                        <td style="border-bottom: none; padding: 5px;">{{ $medis['tindakan_golongan'] ?? '' }}</td>
                        <td style="border-bottom: none; padding: 5px;">{{ $medis['tindakan_tgl'] ?? '' }}</td>
                        <td style="border-bottom: none; padding: 5px;">{{ $medis['tindakan_anastesi'] ?? '' }}</td>
                        <td style="border-right: none; border-bottom: none; padding: 5px;">{{ $medis['tindakan_kode'] ?? '' }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- ROW: INFEKSI NOSOKOMIAL & IMUNISASI -->
        <tr>
            <td style="padding: 6px 8px;">
                <span class="bold">Infeksi Nosokomial :</span>
                <span class="cb-item"><span class="cb-box">&nbsp;</span> Tidak</span>
                <span class="cb-item"><span class="cb-box">&nbsp;</span> Ya, penyebab infeksi : .....</span>
                <div style="margin-top: 6px;">
                    <span class="bold" style="display: block; margin-bottom: 4px;">Imunisasi :</span>
                    <span class="cb-item"><span class="cb-box">&nbsp;</span> BCG</span>
                    <span class="cb-item"><span class="cb-box">&nbsp;</span> DPT</span>
                    <span class="cb-item"><span class="cb-box">&nbsp;</span> Polio</span><br>
                    <span class="cb-item" style="margin-left: 55px;"><span class="cb-box">&nbsp;</span> TFT</span>
                    <span class="cb-item"><span class="cb-box">&nbsp;</span> DT</span>
                    <span class="cb-item"><span class="cb-box">&nbsp;</span> Campak</span><br>
                    <span class="cb-item" style="margin-left: 55px;"><span class="cb-box">&nbsp;</span> Lain-lain :</span>
                </div>
            </td>
            <td style="vertical-align: top; padding: 6px 8px;">
                <div style="text-align: center; font-weight: bold; padding: 6px;">Pengobatan Radioterapi / Kedokteran Nuklir</div>
            </td>
        </tr>

        <!-- ROW: IMUNISASI SELAMA DIRAWAT & TRANSFUSI DARAH -->
        <tr>
            <td style="padding: 6px 8px;">
                <span class="bold">Imunisasi yang diperoleh selama dirawat :</span>
            </td>
            <td style="padding: 6px 8px;">
                <span class="bold">Transfusi darah :</span> {{ $medis['transfusi_darah'] ?? '' }} cc.
            </td>
        </tr>

        <!-- ROW: KEADAAN KRS & CARA KRS -->
        <tr>
            <td style="padding: 6px 8px;">
                <span class="bold" style="display: block; margin-bottom: 4px;">Keadaan KRS :</span>
                <span class="cb-item"><span class="cb-box">{!! $keadaanKrs == 'Sembuh' ? '&#10004;' : '&nbsp;' !!}</span> Sembuh</span>
                <span class="cb-item"><span class="cb-box">{!! str_contains($keadaanKrs, '< 48') ? '&#10004;' : '&nbsp;' !!}</span> Meninggal &lt; 48 jam</span><br>
                <span class="cb-item"><span class="cb-box">{!! $keadaanKrs == 'Membaik' ? '&#10004;' : '&nbsp;' !!}</span> Membaik</span>
                <span class="cb-item"><span class="cb-box">{!! str_contains($keadaanKrs, '> 48') ? '&#10004;' : '&nbsp;' !!}</span> Meninggal &gt; 48 jam</span><br>
                <span class="cb-item"><span class="cb-box">{!! $keadaanKrs == 'Belum sembuh' ? '&#10004;' : '&nbsp;' !!}</span> Belum sembuh</span>
            </td>
            <td style="padding: 6px 8px;">
                <span class="bold" style="display: block; margin-bottom: 4px;">Cara KRS :</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKrs == 'Dipulangkan' ? '&#10004;' : '&nbsp;' !!}</span> Di pulangkan</span>
                <span class="cb-item"><span class="cb-box">{!! $caraKrs == 'Lari' ? '&#10004;' : '&nbsp;' !!}</span> Lari</span><br>
                <span class="cb-item"><span class="cb-box">{!! $caraKrs == 'Pulang paksa' ? '&#10004;' : '&nbsp;' !!}</span> Pulang paksa</span>
                <span class="cb-item"><span class="cb-box">{!! ($caraKrs != '' && !in_array($caraKrs, ['Dipulangkan','Lari','Pulang paksa','Pindah rumah sakit lain'])) ? '&#10004;' : '&nbsp;' !!}</span> Lain-lain :</span><br>
                <span class="cb-item"><span class="cb-box">{!! $caraKrs == 'Pindah rumah sakit lain' ? '&#10004;' : '&nbsp;' !!}</span> Pindah rumah sakit lain</span>
            </td>
        </tr>

        <!-- ROW: DOKTER YANG MERAWAT & TANDA TANGAN -->
        <tr>
            <td style="height: 60px; vertical-align: bottom; padding: 6px 8px;">
                <span class="bold">Dokter yang merawat :</span><br>
                <strong>( {{ $pendaftaran->dokter->nama ?? '' }} )</strong>
            </td>
            <td style="height: 60px; vertical-align: bottom; text-align: center; padding: 6px 8px;">
                <span class="bold">Tanda tangan :</span><br><br><br>
                <span>( ...................................................... )</span>
            </td>
        </tr>
    </table>

</body>
</html>
