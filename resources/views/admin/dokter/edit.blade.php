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
                    <div style="display:flex;gap:8px">
                        <input type="text" name="satusehat_id" id="satusehat_id" class="form-control" value="{{ old('satusehat_id', $dokter->satusehat_id) }}" placeholder="Auto-isi dari tombol Cari">
                        <button type="button" onclick="cariPractitioner()" class="btn btn-outline btn-sm" style="flex-shrink:0;white-space:nowrap">
                            <i class="fas fa-search"></i> Cari by NIK
                        </button>
                    </div>
                    <div id="ssResult" style="display:none;margin-top:6px;padding:8px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:12px"></div>
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

async function cariPractitioner() {
    const nik = document.querySelector('[name="nik"]').value.trim();
    if (!nik || nik.length !== 16) {
        alert('Isi NIK Dokter (16 digit) terlebih dahulu');
        return;
    }
    const res = document.getElementById('ssResult');
    res.style.display = 'block';
    res.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari di SatuSehat...';
    try {
        const r = await fetch(`/admin/satusehat/cari-dokter?nik=${nik}`);
        const d = await r.json();
        if (d.ditemukan) {
            const info = d.info_ringkas;
            document.getElementById('satusehat_id').value = info.id;
            res.innerHTML = `✅ <b>${info.nama_lengkap || 'Nama termasked'}</b> ditemukan! ID: <code>${info.id}</code> (sudah diisi)`;
            res.style.cssText += 'background:#f0fdf4;border-color:#bbf7d0';
        } else {
            res.innerHTML = `⚠️ NIK ini belum terdaftar sebagai Practitioner di SatuSehat.`;
            res.style.cssText += 'background:#fffbeb;border-color:#fde68a';
        }
    } catch(e) {
        res.innerHTML = `❌ Error: ${e.message}`;
        res.style.cssText += 'background:#fee2e2;border-color:#fecaca';
    }
}
</script>
@endpush
