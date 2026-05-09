@extends('layouts.app')
@section('title', 'Edit Pasien - RS Cahya Medika')
@section('page-title', 'Edit Data Pasien')

@section('content')
<div style="max-width:760px;margin:0 auto">

<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('admin.pasien.index') }}" style="color:#0891b2;text-decoration:none">Data Pasien</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <a href="{{ route('admin.pasien.show', $pasien->id) }}" style="color:#0891b2;text-decoration:none">{{ $pasien->nama_lengkap }}</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Edit</span>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">✏️ Edit Data Pasien: {{ $pasien->nama_lengkap }}</div>
        <span style="font-size:12px;font-weight:700;color:#0891b2;background:#e0f2fe;padding:4px 12px;border-radius:8px">{{ $pasien->no_rm }}</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pasien.update', $pasien->id) }}" method="POST">
            @csrf @method('PUT')

            <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
                <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:14px">📋 Data Diri</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                            value="{{ old('nama_lengkap', $pasien->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK *</label>
                        <input type="text" name="nik" maxlength="16" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                            value="{{ old('nik', $pasien->nik) }}" required>
                        @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <label class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['A','B','AB','O'] as $gd)
                                <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah) == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu'] as $ag)
                                <option value="{{ $ag }}" {{ old('agama', $pasien->agama) == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_pernikahan" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach(['Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'] as $sp)
                                <option value="{{ $sp }}" {{ old('status_pernikahan', $pasien->status_pernikahan) == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. HP *</label>
                        <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                            value="{{ old('no_hp', $pasien->no_hp) }}" required>
                        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $pasien->pekerjaan) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Pasien</label>
                        <select name="status" class="form-select">
                            <option value="aktif" {{ old('status', $pasien->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $pasien->status) == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
                <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:14px">📍 Alamat</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pasien->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pasien->kecamatan) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kabupaten/Kota</label>
                        <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $pasien->kabupaten) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pasien->provinsi) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pasien->kode_pos) }}">
                    </div>
                </div>
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px;margin-top:8px">
                    <div style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:10px">🔗 Kode Wilayah BPS (untuk SatuSehat)</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div class="form-group">
                            <label class="form-label" style="font-size:10px">Kode Provinsi</label>
                            <input type="text" name="kode_provinsi" maxlength="2" class="form-control" style="font-family:monospace"
                                value="{{ old('kode_provinsi', $pasien->kode_provinsi ?? '35') }}" placeholder="35">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:10px">Kode Kabupaten</label>
                            <input type="text" name="kode_kabupaten" maxlength="4" class="form-control" style="font-family:monospace"
                                value="{{ old('kode_kabupaten', $pasien->kode_kabupaten ?? '3511') }}" placeholder="3511">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:10px">Kode Kecamatan</label>
                            <input type="text" name="kode_kecamatan" maxlength="6" class="form-control" style="font-family:monospace"
                                value="{{ old('kode_kecamatan', $pasien->kode_kecamatan ?? '351101') }}" placeholder="351101">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-size:10px">Kode Kelurahan</label>
                            <input type="text" name="kode_kelurahan" maxlength="10" class="form-control" style="font-family:monospace"
                                value="{{ old('kode_kelurahan', $pasien->kode_kelurahan ?? '3511010001') }}" placeholder="3511010001">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.pasien.show', $pasien->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
