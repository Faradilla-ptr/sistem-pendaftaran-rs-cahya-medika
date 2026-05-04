@extends('layouts.app')
@section('title', 'Tambah Dokter - RS Cahya Medika')
@section('page-title', 'Tambah Dokter')

@section('content')
<div style="max-width:760px;margin:0 auto">

<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('admin.dokter.index') }}" style="color:#0891b2;text-decoration:none">Data Dokter</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Tambah Dokter</span>
</div>

<div class="card">
    <div class="card-header"><div class="card-title">👨‍⚕️ Form Tambah Dokter</div></div>
    <div class="card-body">
    <form action="{{ route('admin.dokter.store') }}" method="POST">
        @csrf

        <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
            <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;margin-bottom:14px">👤 Identitas Dokter</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group">
                    <label class="form-label">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan','dr.') }}" placeholder="dr.">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Dokter *</label>
                    <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                        value="{{ old('nama') }}" required placeholder="Nama tanpa gelar">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}" placeholder="Sp.PD, M.Kes, dll">
                </div>
                <div class="form-group">
                    <label class="form-label">Spesialisasi *</label>
                    <input type="text" name="spesialisasi" class="form-control {{ $errors->has('spesialisasi') ? 'is-invalid' : '' }}"
                        value="{{ old('spesialisasi') }}" required placeholder="Penyakit Dalam, Anak, dll">
                    @error('spesialisasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Poli *</label>
                    <select name="poli_id" class="form-select {{ $errors->has('poli_id') ? 'is-invalid' : '' }}" required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ old('poli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('poli_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">No. STR</label>
                    <input type="text" name="str_number" class="form-control" value="{{ old('str_number') }}" placeholder="Nomor STR">
                </div>
                <div class="form-group">
                    <label class="form-label">NIK Dokter</label>
                    <input type="text" name="nik" maxlength="16" class="form-control" value="{{ old('nik') }}" placeholder="16 digit NIK">
                </div>
                <div class="form-group">
                    <label class="form-label">SatuSehat Practitioner ID</label>
                    <input type="text" name="satusehat_id" class="form-control" value="{{ old('satusehat_id') }}" placeholder="ID dari SatuSehat">
                </div>
            </div>
        </div>

        <!-- JADWAL -->
        <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
            <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;margin-bottom:14px">📅 Jadwal Praktik</div>
            <div style="display:grid;gap:10px">
                @foreach(['senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu','kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu','minggu'=>'Minggu'] as $key=>$label)
                <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:white;border-radius:10px;border:1px solid #e2e8f0">
                    <input type="checkbox" name="jadwal_{{ $key }}" id="chk_{{ $key }}" style="width:16px;height:16px;accent-color:#0891b2"
                        {{ old("jadwal_{$key}") ? 'checked' : '' }} onchange="toggleJadwal('{{ $key }}', this.checked)">
                    <label for="chk_{{ $key }}" style="font-size:13px;font-weight:600;width:70px;cursor:pointer">{{ $label }}</label>
                    <div id="jadwal_time_{{ $key }}" style="{{ old("jadwal_{$key}") ? '' : 'display:none' }};display:{{ old("jadwal_{$key}") ? 'flex' : 'none' }};align-items:center;gap:8px;flex:1">
                        <label style="font-size:11px;color:#64748b">Mulai</label>
                        <input type="time" name="jam_mulai_{{ $key }}" value="{{ old("jam_mulai_{$key}", '08:00') }}" class="form-control" style="width:110px;padding:6px 10px">
                        <label style="font-size:11px;color:#64748b">Selesai</label>
                        <input type="time" name="jam_selesai_{{ $key }}" value="{{ old("jam_selesai_{$key}", '12:00') }}" class="form-control" style="width:110px;padding:6px 10px">
                    </div>
                    <div id="jadwal_off_{{ $key }}" style="{{ old("jadwal_{$key}") ? 'display:none' : '' }};font-size:12px;color:#94a3b8">
                        Tidak praktik
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Dokter</button>
            <a href="{{ route('admin.dokter.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function toggleJadwal(hari, checked) {
    document.getElementById('jadwal_time_' + hari).style.display = checked ? 'flex' : 'none';
    document.getElementById('jadwal_off_' + hari).style.display = checked ? 'none' : 'block';
}
</script>
@endpush
