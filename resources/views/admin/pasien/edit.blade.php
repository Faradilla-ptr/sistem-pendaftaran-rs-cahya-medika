@extends('layouts.app')
@section('title', 'Edit Pasien - RS Cahya Medika')
@section('page-title', 'Edit Data Pasien')

@section('content')
<div style="max-width:840px;margin:0 auto">

<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('admin.pasien.index') }}" style="color:#0891b2;text-decoration:none">Data Pasien</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route('admin.pasien.show', $pasien->id) }}" style="color:#0891b2;text-decoration:none">{{ $pasien->nama_lengkap }}</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Edit Formulir Identitas Pasien</span>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">✏️ Edit Formulir Identitas Pasien: {{ $pasien->nama_lengkap }}</div>
        <span style="font-size:12px;font-weight:700;color:#0891b2;background:#e0f2fe;padding:4px 12px;border-radius:8px">No. RM: {{ $pasien->no_rm }}</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pasien.update', $pasien->id) }}" method="POST">
            @csrf @method('PUT')

            <!-- 1. DATA UMUM PASIEN -->
            <div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:20px;border:1px solid #e2e8f0">
                <div style="font-size:13px;font-weight:800;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:14px;border-bottom:2px solid #e2e8f0;padding-bottom:6px">
                    📋 DATA UMUM PASIEN
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-group">
                        <label class="form-label">No. Rekam Medis (No RM)</label>
                        <input type="text" name="no_rm" class="form-control" style="font-weight:bold;color:#0891b2"
                            value="{{ old('no_rm', $pasien->no_rm) }}" placeholder="CM2026xxxx">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. KTP / SIM (NIK) *</label>
                        <input type="text" name="nik" maxlength="16" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                            value="{{ old('nik', $pasien->nik) }}" required>
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Pasien *</label>
                        <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                            value="{{ old('nama_lengkap', $pasien->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $pasien->tempat_lahir) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="{{ old('tanggal_lahir', optional($pasien->tanggal_lahir)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $pasien->pekerjaan) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen','Katolik','Budha','Hindu','Lainnya'] as $ag)
                                <option value="{{ $ag }}" {{ old('agama', $pasien->agama) == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['SD','SMP','SMA','Diploma','Sarjana','Lainnya'] as $pd)
                                <option value="{{ $pd }}" {{ old('pendidikan', $pasien->pendidikan) == $pd ? 'selected' : '' }}>{{ $pd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Perkawinan</label>
                        <select name="status_pernikahan" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Belum Kawin','Kawin','Duda','Janda'] as $sp)
                                <option value="{{ $sp }}" {{ old('status_pernikahan', $pasien->status_pernikahan) == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warga Negara</label>
                        <select name="warga_negara" class="form-select">
                            <option value="WNI" {{ old('warga_negara', $pasien->warga_negara ?? 'WNI') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('warga_negara', $pasien->warga_negara) == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Suku</label>
                        <input type="text" name="suku" class="form-control" value="{{ old('suku', $pasien->suku ?? 'Jawa') }}" placeholder="Jawa / Madura / Lainnya">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['A','B','AB','O'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah) == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telpon / HP *</label>
                        <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                            value="{{ old('no_hp', $pasien->no_hp) }}" required>
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua (Ibu)</label>
                        <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $pasien->nama_ibu) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua (Ayah)</label>
                        <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $pasien->nama_ayah) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Riwayat Alergi</label>
                        <select name="riwayat_alergi" class="form-select">
                            <option value="Tidak Ada" {{ old('riwayat_alergi', $pasien->riwayat_alergi ?? 'Tidak Ada') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                            <option value="Ada" {{ old('riwayat_alergi', $pasien->riwayat_alergi) == 'Ada' ? 'selected' : '' }}>Ada</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Alergi (Jika Ada)</label>
                        <input type="text" name="jenis_alergi" class="form-control" value="{{ old('jenis_alergi', $pasien->jenis_alergi) }}" placeholder="Obat / Makanan / Debu">
                    </div>
                </div>

                <!-- ALAMAT DETAIL -->
                <div style="font-size:12px;font-weight:700;color:#0c4a6e;margin-top:16px;margin-bottom:8px">📍 Detail Alamat Pasien</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Alamat Jalan / RT / RW</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pasien->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Desa / Kelurahan</label>
                        <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $pasien->kelurahan) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pasien->kecamatan) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $pasien->kabupaten ?? 'Bondowoso') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pasien->provinsi ?? 'Jawa Timur') }}">
                    </div>
                </div>
            </div>

            <!-- 2. PENANGGUNG JAWAB / KELUARGA TERDEKAT -->
            <div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:20px;border:1px solid #e2e8f0">
                <div style="font-size:13px;font-weight:800;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">
                    👨‍👩‍👧 PENANGGUNG JAWAB / KELUARGA TERDEKAT
                </div>
                <div style="font-size:11px;font-style:italic;color:#64748b;margin-bottom:14px">*Wajib diisi untuk anak usia < 17 Tahun</div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-group">
                        <label class="form-label">Nama Penanggung Jawab</label>
                        <input type="text" name="nama_pj" class="form-control" value="{{ old('nama_pj', $pasien->nama_pj) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin PJ</label>
                        <select name="jenis_kelamin_pj" class="form-select">
                            <option value="L" {{ old('jenis_kelamin_pj', $pasien->jenis_kelamin_pj ?? 'L') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin_pj', $pasien->jenis_kelamin_pj) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hubungan Dengan Pasien</label>
                        <select name="hubungan_pj" class="form-select">
                            <option value="">-- Pilih Hubungan --</option>
                            @foreach(['Orang Tua','Anak','Suami','Istri','Saudara','Lainnya'] as $hb)
                                <option value="{{ $hb }}" {{ old('hubungan_pj', $pasien->hubungan_pj) == $hb ? 'selected' : '' }}>{{ $hb }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan PJ</label>
                        <input type="text" name="pekerjaan_pj" class="form-control" value="{{ old('pekerjaan_pj', $pasien->pekerjaan_pj) }}">
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Alamat Penanggung Jawab</label>
                        <textarea name="alamat_pj" class="form-control" rows="2">{{ old('alamat_pj', $pasien->alamat_pj) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Desa PJ</label>
                        <input type="text" name="kelurahan_pj" class="form-control" value="{{ old('kelurahan_pj', $pasien->kelurahan_pj) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kecamatan PJ</label>
                        <input type="text" name="kecamatan_pj" class="form-control" value="{{ old('kecamatan_pj', $pasien->kecamatan_pj) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten PJ</label>
                        <input type="text" name="kabupaten_pj" class="form-control" value="{{ old('kabupaten_pj', $pasien->kabupaten_pj) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Provinsi PJ</label>
                        <input type="text" name="provinsi_pj" class="form-control" value="{{ old('provinsi_pj', $pasien->provinsi_pj) }}">
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">No. Telpon / HP PJ</label>
                        <input type="text" name="no_hp_pj" class="form-control" value="{{ old('no_hp_pj', $pasien->no_hp_pj) }}">
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:20px">
                <button type="submit" class="btn btn-primary" style="padding:10px 24px">
                    <i class="fas fa-save"></i> Simpan Formulir Identitas
                </button>
                <a href="{{ route('admin.pasien.show', $pasien->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
