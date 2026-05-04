@extends('layouts.app')
@section('title', 'Data Poli - RS Cahya Medika')
@section('page-title', 'Poli / Klinik')

@section('content')
<div class="grid grid-2" style="gap:24px;align-items:start">

    <!-- DAFTAR POLI -->
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">🏥 Daftar Poli</div>
                <span style="font-size:12px;color:#94a3b8">{{ $poli->total() }} poli</span>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($poli as $p)
                <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:14px">
                    <div style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:{{ $p->warna ?? '#e0f2fe' }}22;border:2px solid {{ $p->warna ?? '#e0f2fe' }}">
                        {{ $p->icon ?? '🏥' }}
                    </div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:14px;color:#0c4a6e">{{ $p->nama }}</div>
                        <div style="font-size:11px;color:#94a3b8">
                            Kode: <strong>{{ $p->kode }}</strong> &nbsp;·&nbsp;
                            {{ $p->lantai ?? 'Lantai 1' }} &nbsp;·&nbsp;
                            {{ $p->jam_buka }} - {{ $p->jam_tutup }}
                        </div>
                        <div style="font-size:11px;color:#64748b;margin-top:2px">
                            👨‍⚕️ {{ $p->dokter_count }} dokter &nbsp;·&nbsp; 📋 {{ $p->pendaftaran_count }} kunjungan
                        </div>
                    </div>
                    <div>
                        <span class="badge badge-{{ $p->is_active ? 'success' : 'danger' }}">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="padding:40px;text-align:center;color:#94a3b8">Belum ada poli</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH POLI -->
    <div>
        <div class="card">
            <div class="card-header"><div class="card-title">➕ Tambah Poli Baru</div></div>
            <div class="card-body">
                <form action="{{ route('admin.poli.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Kode Poli *</label>
                        <input type="text" name="kode" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}"
                            value="{{ old('kode') }}" placeholder="UMUM, ANAK, BEDAH..." required style="text-transform:uppercase">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Poli *</label>
                        <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                            value="{{ old('nama') }}" required placeholder="Poli Umum, Poli Anak...">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Keterangan singkat poli ini...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="form-group">
                            <label class="form-label">Lantai</label>
                            <input type="text" name="lantai" class="form-control" value="{{ old('lantai','Lantai 1') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Icon (emoji)</label>
                            <input type="text" name="icon" class="form-control" value="{{ old('icon','🏥') }}" placeholder="🏥">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka','07:30') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup','14:00') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-plus"></i> Tambah Poli
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
