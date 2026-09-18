<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULIR IDENTITAS PASIEN — {{ $pendaftaran->pasien->no_rm ?? $pendaftaran->kode_booking }} — RS CAHYA MEDIKA</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f1f5f9; color: #000; padding: 20px; font-size: 12px; line-height: 1.4; }
        .page { max-width: 850px; margin: 0 auto; background: white; border: 1.5px solid #000; padding: 20px; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1.5px solid #000; }
        .header-table td { padding: 8px 12px; vertical-align: middle; }
        .h-logo { width: 180px; text-align: left; }
        .h-logo img { height: 45px; width: auto; }
        .h-address { text-align: right; font-size: 11px; line-height: 1.3; }
        .h-title-box { border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; text-align: center; padding: 6px; font-weight: bold; font-size: 15px; letter-spacing: 0.5px; }

        .section-header { background: #d1d5db; border: 1px solid #000; border-bottom: none; font-weight: bold; font-size: 11px; padding: 4px 8px; text-transform: uppercase; }
        .section-sub { font-weight: normal; font-size: 10px; text-transform: none; display: block; }
        
        .form-table { width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 14px; font-size: 11px; }
        .form-table td { padding: 4px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .form-table tr:last-child td { border-bottom: none; }
        .lbl { width: 180px; font-weight: normal; }
        .sep { width: 15px; text-align: center; }
        .val { font-weight: bold; }
        .val-dots { border-bottom: 1px dotted #9ca3af; min-height: 16px; display: inline-block; width: 100%; }

        .checkbox-group { display: inline-flex; gap: 12px; flex-wrap: wrap; }
        .cb { display: inline-flex; align-items: center; gap: 4px; }
        .cb-box { width: 12px; height: 12px; border: 1px solid #000; display: inline-block; text-align: center; line-height: 10px; font-size: 10px; font-weight: bold; }

        .no-print { text-align: center; margin-bottom: 16px; }
        .btn-print { padding: 8px 20px; background: #0284c7; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 13px; cursor: pointer; }

        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .page { border: 1.5px solid #000; padding: 15px; width: 100%; max-width: 100%; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ CETAK FORMULIR IDENTITAS PASIEN (PDF)</button>
    </div>

    <div class="page">
        <!-- HOSPITAL HEADER -->
        <table class="header-table">
            <tr>
                <td class="h-logo">
                    <div style="font-weight:bold;font-size:14px;color:#0369a1">RS CAHYA MEDIKA</div>
                    <div style="font-size:9px;color:#475569">Managed by IHC PT Balai Nusantara Medika</div>
                </td>
                <td class="h-address">
                    Jl. Raya Pakisan No. 24, Bataan<br>
                    Tenggarang - Bondowoso<br>
                    Telp. (0332) 5557554<br>
                    Email : rscahyamedika@gmail.com
                </td>
            </tr>
            <tr>
                <td colspan="2" class="h-title-box">
                    FORMULIR IDENTITAS PASIEN
                </td>
            </tr>
        </table>

        <!-- SECTION 1: DATA UMUM PASIEN -->
        <div class="section-header">DATA UMUM PASIEN</div>
        <table class="form-table">
            <tr>
                <td class="lbl">No. Rekam Medis</td>
                <td class="sep">:</td>
                <td class="val" style="font-size:12px;font-family:monospace">{{ $pendaftaran->pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">No. KTP / SIM</td>
                <td class="sep">:</td>
                <td class="val" style="font-family:monospace">{{ $pendaftaran->pasien->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Tanggal Registrasi</td>
                <td class="sep">:</td>
                <td>
                    <span class="val">{{ $pendaftaran->created_at ? $pendaftaran->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Jam : <span class="val">{{ $pendaftaran->created_at ? $pendaftaran->created_at->format('H:i') : date('H:i') }} WIB</span>
                </td>
            </tr>
            <tr>
                <td class="lbl">Nama Pasien</td>
                <td class="sep">:</td>
                <td class="val">{{ strtoupper($pendaftaran->pasien->nama_lengkap ?? '-') }}</td>
            </tr>
            <tr>
                <td class="lbl">Tempat, Tanggal Lahir</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftaran->pasien->tempat_lahir ?? 'Bondowoso' }}, {{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Pekerjaan</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftaran->pasien->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Jenis Kelamin</td>
                <td class="sep">:</td>
                <td>
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ $pendaftaran->pasien->jenis_kelamin == 'L' ? '✓' : '' }}</span> Laki-laki</span>
                        <span class="cb"><span class="cb-box">{{ $pendaftaran->pasien->jenis_kelamin == 'P' ? '✓' : '' }}</span> Perempuan</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Agama</td>
                <td class="sep">:</td>
                <td>
                    @php $ag = strtolower($pendaftaran->pasien->agama ?? 'islam'); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ str_contains($ag, 'islam') ? '✓' : '' }}</span> Islam</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($ag, 'kristen') ? '✓' : '' }}</span> Kristen</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($ag, 'katolik') ? '✓' : '' }}</span> Katolik</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($ag, 'budha') ? '✓' : '' }}</span> Budha</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($ag, 'hindu') ? '✓' : '' }}</span> Hindu</span>
                        <span class="cb"><span class="cb-box">{{ (!in_array($ag, ['islam','kristen','katolik','budha','hindu']) && $ag != '') ? '✓' : '' }}</span> {{ (!in_array($ag, ['islam','kristen','katolik','budha','hindu']) && $ag != '') ? $pendaftaran->pasien->agama : '________' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Pendidikan Terakhir</td>
                <td class="sep">:</td>
                <td>
                    @php $pd = strtoupper($pendaftaran->pasien->pendidikan ?? ''); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ $pd == 'SD' ? '✓' : '' }}</span> SD</span>
                        <span class="cb"><span class="cb-box">{{ $pd == 'SMP' ? '✓' : '' }}</span> SMP</span>
                        <span class="cb"><span class="cb-box">{{ $pd == 'SMA' || $pd == 'SMK' ? '✓' : '' }}</span> SMA</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($pd, 'DIPLOMA') || str_contains($pd, 'D3') ? '✓' : '' }}</span> Diploma</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($pd, 'SARJANA') || str_contains($pd, 'S1') ? '✓' : '' }}</span> Sarjana</span>
                        <span class="cb"><span class="cb-box">{{ ($pd != '' && !in_array($pd, ['SD','SMP','SMA','SMK','DIPLOMA','D3','SARJANA','S1'])) ? '✓' : '' }}</span> ________</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Status Perkawinan</td>
                <td class="sep">:</td>
                <td>
                    @php $st = strtolower($pendaftaran->pasien->status_pernikahan ?? ''); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ str_contains($st, 'belum') ? '✓' : '' }}</span> Belum Kawin</span>
                        <span class="cb"><span class="cb-box">{{ ($st == 'kawin' || str_contains($st, 'menikah')) ? '✓' : '' }}</span> Kawin</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($st, 'duda') ? '✓' : '' }}</span> Duda</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($st, 'janda') ? '✓' : '' }}</span> Janda</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Alamat</td>
                <td class="sep">:</td>
                <td>
                    <div class="val">{{ $pendaftaran->pasien->alamat ?? '-' }}</div>
                    <div style="margin-top:4px">
                        Desa : <span class="val">{{ $pendaftaran->pasien->kelurahan ?? '-' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Kecamatan : <span class="val">{{ $pendaftaran->pasien->kecamatan ?? '-' }}</span>
                    </div>
                    <div style="margin-top:2px">
                        Kabupaten : <span class="val">{{ $pendaftaran->pasien->kabupaten ?? '-' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Provinsi : <span class="val">{{ $pendaftaran->pasien->provinsi ?? '-' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Warga Negara</td>
                <td class="sep">:</td>
                <td>
                    @php $wn = strtoupper($pendaftaran->pasien->warga_negara ?? 'WNI'); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ $wn == 'WNI' ? '✓' : '' }}</span> WNI</span>
                        <span class="cb"><span class="cb-box">{{ $wn == 'WNA' ? '✓' : '' }}</span> WNA</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Suku</td>
                <td class="sep">:</td>
                <td>
                    @php $sk = strtolower($pendaftaran->pasien->suku ?? 'jawa'); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ str_contains($sk, 'jawa') ? '✓' : '' }}</span> Jawa</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($sk, 'madura') ? '✓' : '' }}</span> Madura</span>
                        <span class="cb"><span class="cb-box">{{ (!in_array($sk, ['jawa','madura']) && $sk != '') ? '✓' : '' }}</span> {{ (!in_array($sk, ['jawa','madura']) && $sk != '') ? $pendaftaran->pasien->suku : '________' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Golongan Darah</td>
                <td class="sep">:</td>
                <td>
                    @php $gd = strtoupper($pendaftaran->pasien->golongan_darah ?? ''); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ $gd == 'A' ? '✓' : '' }}</span> A</span>
                        <span class="cb"><span class="cb-box">{{ $gd == 'B' ? '✓' : '' }}</span> B</span>
                        <span class="cb"><span class="cb-box">{{ $gd == 'AB' ? '✓' : '' }}</span> AB</span>
                        <span class="cb"><span class="cb-box">{{ $gd == 'O' ? '✓' : '' }}</span> O</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Nama Orang Tua</td>
                <td class="sep">:</td>
                <td>
                    Ibu : <span class="val">{{ $pendaftaran->pasien->nama_ibu ?? '-' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Ayah : <span class="val">{{ $pendaftaran->pasien->nama_ayah ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <td class="lbl">No. Telpon / HP</td>
                <td class="sep">:</td>
                <td class="val" style="font-family:monospace">{{ $pendaftaran->pasien->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Riwayat Alergi</td>
                <td class="sep">:</td>
                <td>
                    @php $al = $pendaftaran->pasien->jenis_alergi ?? ''; @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ empty($al) || str_contains(strtolower($al), 'tidak') ? '✓' : '' }}</span> Tidak Ada</span>
                        <span class="cb"><span class="cb-box">{{ (!empty($al) && !str_contains(strtolower($al), 'tidak')) ? '✓' : '' }}</span> Ada</span>
                    </div>
                    @if(!empty($al) && !str_contains(strtolower($al), 'tidak'))
                        <div style="margin-top:2px">Jenis Alergi : <span class="val">{{ $al }}</span></div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- SECTION 2: PENANGGUNG JAWAB / KELUARGA TERDEKAT -->
        <div class="section-header">
            PENANGGUNG JAWAB / KELUARGA TERDEKAT
            <span class="section-sub">*Wajib diisi untuk anak usia &lt; 17 Tahun</span>
        </div>
        <table class="form-table">
            <tr>
                <td class="lbl">Nama Penanggung Jawab</td>
                <td class="sep">:</td>
                <td class="val">{{ strtoupper($pendaftaran->pasien->nama_pj ?? '-') }}</td>
            </tr>
            <tr>
                <td class="lbl">Jenis Kelamin</td>
                <td class="sep">:</td>
                <td>
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ $pendaftaran->pasien->jenis_kelamin_pj == 'L' ? '✓' : '' }}</span> Laki-laki</span>
                        <span class="cb"><span class="cb-box">{{ $pendaftaran->pasien->jenis_kelamin_pj == 'P' ? '✓' : '' }}</span> Perempuan</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Hubungan Dengan Pasien</td>
                <td class="sep">:</td>
                <td>
                    @php $hb = strtolower($pendaftaran->pasien->hubungan_pj ?? ''); @endphp
                    <div class="checkbox-group">
                        <span class="cb"><span class="cb-box">{{ str_contains($hb, 'orang tua') || str_contains($hb, 'ibu') || str_contains($hb, 'ayah') ? '✓' : '' }}</span> Orang Tua</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($hb, 'anak') ? '✓' : '' }}</span> Anak</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($hb, 'suami') ? '✓' : '' }}</span> Suami</span>
                        <span class="cb"><span class="cb-box">{{ str_contains($hb, 'istri') ? '✓' : '' }}</span> Istri</span>
                        <span class="cb"><span class="cb-box">{{ (!in_array($hb, ['orang tua','anak','suami','istri']) && $hb != '') ? '✓' : '' }}</span> {{ $pendaftaran->pasien->hubungan_pj ?? '________' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">Pekerjaan</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftaran->pasien->pekerjaan_pj ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Alamat</td>
                <td class="sep">:</td>
                <td>
                    <div class="val">{{ $pendaftaran->pasien->alamat_pj ?? '-' }}</div>
                    <div style="margin-top:4px">
                        Desa : <span class="val">{{ $pendaftaran->pasien->kelurahan_pj ?? '-' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Kecamatan : <span class="val">{{ $pendaftaran->pasien->kecamatan_pj ?? '-' }}</span>
                    </div>
                    <div style="margin-top:2px">
                        Kabupaten : <span class="val">{{ $pendaftaran->pasien->kabupaten_pj ?? '-' }}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Provinsi : <span class="val">{{ $pendaftaran->pasien->provinsi_pj ?? '-' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="lbl">No. Telpon / HP</td>
                <td class="sep">:</td>
                <td class="val" style="font-family:monospace">{{ $pendaftaran->pasien->no_hp_pj ?? '-' }}</td>
            </tr>
        </table>

        <!-- FOOTER TANDA TANGAN -->
        <table style="width:100%;margin-top:30px;text-align:center;font-size:11px">
            <tr>
                <td style="width:50%">
                    Petugas Loket Pendaftaran,<br><br><br><br>
                    <u>( {{ auth()->user()->name ?? 'Staf Loket RS' }} )</u>
                </td>
                <td style="width:50%">
                    Bondowoso, {{ date('d F Y') }}<br>
                    Pasien / Penanggung Jawab,<br><br><br><br>
                    <u>( {{ $pendaftaran->pasien->nama_lengkap ?? 'Pasien' }} )</u>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
