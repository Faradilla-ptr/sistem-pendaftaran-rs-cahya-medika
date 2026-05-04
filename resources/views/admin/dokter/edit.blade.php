@extends('layouts.app')
@section('title', 'Edit Dokter - RS Cahya Medika')
@section('page-title', 'Edit Dokter')

@section('content')
<div style="max-width:760px;margin:0 auto">

<div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:13px;color:#64748b">
    <a href="{{ route('admin.dokter.index') }}" style="color:#0891b2;text-decoration:none">Data Dokter</a>
    <i class="fas fa-chevron-right" style="font-size:10px"></i>
    <span>Edit: {{ $dokter->nama_lengkap }}</span>
</div>

<div class="card">
    <div class="card-header"><div class="card-title">✏️ Edit Dokter: {{ $dokter->nama_lengkap }}</div></div>
    <div class="card-body">
    <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST">
        @csrf @method('PUT')

        <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
            <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;margin-bottom:14px">👤 Identitas Dokter</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group">
                    <label class="form-label">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan', $dokter->gelar_depan) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Dokter *</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $dokter->nama) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang', $dokter->gelar_belakang) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Spesialisasi *</label>
                    <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi', $dokter->spesialisasi) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Poli *</label>
                    <select name="poli_id" class="form-select" required>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}" {{ old('poli_id', $dokter->poli_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">No. STR</label>
                    <input type="text" name="str_number" class="form-control" value="{{ old('str_number', $dokter->str_number) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SatuSehat ID</label>
                    <input type="text" name="satusehat_id" class="form-control" value="{{ old('satusehat_id', $dokter->satusehat_id) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', $dokter->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $dokter->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- JADWAL -->
        <div style="padding:14px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
            <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;margin-bottom:14px">📅 Jadwal Praktik</div>
            <div style="display:grid;gap:10px">
                @foreach(['senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu','kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu','minggu'=>'Minggu'] as $key=>$label)
                @php
                    $jadwalHari = $dokter->jadwal[$key] ?? null;
                    $isActive = $jadwalHari['aktif'] ?? false;
                @endphp
                <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:white;border-radius:10px;border:1px solid #e2e8f0">
                    <input type="checkbox" name="jadwal_{{ $key }}" id="chk_{{ $key }}" style="width:16px;height:16px;accent-color:#0891b2"
                        {{ $isActive ? 'checked' : '' }} onchange="toggleJadwal('{{ $key }}', this.checked)">
                    <label for="chk_{{ $key }}" style="font-size:13px;font-weight:600;width:70px;cursor:pointer">{{ $label }}</label>
                    <div id="jadwal_time_{{ $key }}" style="display:{{ $isActive ? 'flex' : 'none' }};align-items:center;gap:8px;flex:1">
                        <label style="font-size:11px;color:#64748b">Mulai</label>
                        <input type="time" name="jam_mulai_{{ $key }}" value="{{ $jadwalHari['jam_mulai'] ?? '08:00' }}" class="form-control" style="width:110px;padding:6px 10px">
                        <label style="font-size:11px;color:#64748b">Selesai</label>
                        <input type="time" name="jam_selesai_{{ $key }}" value="{{ $jadwalHari['jam_selesai'] ?? '12:00' }}" class="form-control" style="width:110px;padding:6px 10px">
                    </div>
                    <div id="jadwal_off_{{ $key }}" style="display:{{ $isActive ? 'none' : 'block' }};font-size:12px;color:#94a3b8">
                        Tidak praktik
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
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
