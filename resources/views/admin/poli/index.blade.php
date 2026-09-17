@extends('layouts.app')
@section('title', 'Poliklinik & Kuota - RS Cahya Medika')
@section('page-title', 'Poliklinik & Kuota')

@section('content')
@php 
    $r = auth()->user()->role === 'rekam_medis' ? 'rekam_medis.' : (auth()->user()->role === 'pendaftaran' ? 'pendaftaran.' : 'admin.'); 
@endphp



<div class="grid grid-2" style="gap:20px">
    {{-- DAFTAR POLI --}}
    <div class="card" style="height:fit-content">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-hospital" style="color:#16a34a"></i> Daftar Poliklinik Aktif</div>
        </div>
        <div>
            @forelse($poli ?? [] as $p)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #f9fafb">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px">🏥</div>
                    <div>
                        <div style="font-weight:700;font-size:13px">{{ $p->nama }}</div>
                        <div style="font-size:11px;color:#9ca3af">Kuota: {{ $p->kuota_per_hari ?? '-' }} pasien/hari</div>
                    </div>
                </div>
                <span class="badge badge-{{ $p->is_active ? 'success' : 'danger' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
            @empty
            <div style="padding:36px;text-align:center;color:#9ca3af;font-size:13px">Belum ada data poliklinik</div>
            @endforelse
        </div>
    </div>

    {{-- TAMBAH POLI FORM --}}
    <div class="card" style="height:fit-content">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-plus-circle" style="color:#1d4ed8"></i> Tambah Poliklinik Baru</div>
        </div>
        <div class="card-body">
            @if(in_array(auth()->user()->role, ['admin', 'pendaftaran']))
            <form action="{{ route($r . 'poli.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Poliklinik *</label>
                    <input type="text" name="nama" class="form-control {{ $errors->has('nama')?'is-invalid':'' }}" value="{{ old('nama') }}" placeholder="contoh: Poli Penyakit Dalam" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kuota Per Hari</label>
                    <input type="number" name="kuota_per_hari" class="form-control" value="{{ old('kuota_per_hari', 20) }}" min="1" max="200">
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" placeholder="Keterangan singkat poliklinik..." rows="3">{{ old('keterangan') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%"><i class="fas fa-plus"></i> Tambah Poliklinik</button>
            </form>
            @else
            <div style="padding:20px;text-align:center;color:#64748b;font-size:13px">
                <i class="fas fa-lock" style="font-size:24px;color:#94a3b8;display:block;margin-bottom:10px"></i>
                Mode Read-Only. Petugas Rekam Medis tidak memiliki akses untuk menambah Poliklinik baru.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
