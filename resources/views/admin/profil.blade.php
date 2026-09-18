@extends('layouts.app')
@section('title', 'Profil Akun Petugas - RS Cahya Medika')
@section('page-title', 'Profil & Pengaturan Akun')

@section('content')
@php
    $r = auth()->user()->role === 'rekam_medis' ? 'rekam_medis.' : (auth()->user()->role === 'pendaftaran' ? 'pendaftaran.' : 'admin.');
    $roleTitle = 'Super Admin / Pendaftaran';
    $roleBadgeColor = '#1d4ed8';
    $roleBgColor = '#eff6ff';
    if ($user->role === 'rekam_medis' || request()->is('rekam-medis*')) {
        $roleTitle = 'Petugas Rekam Medis & SATUSEHAT';
        $roleBadgeColor = '#0d9488';
        $roleBgColor = '#f0fdf4';
    }
@endphp

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:20px">
    <i class="fas fa-circle-check" style="font-size:16px;margin-right:8px"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:20px">
    <i class="fas fa-circle-exclamation" style="font-size:16px;margin-right:8px"></i>
    <div>{{ $errors->first() }}</div>
</div>
@endif

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start">
    
    {{-- RINGKASAN PROFIL USER (LEFT SIDEBAR) --}}
    <div class="card">
        <div class="card-body" style="padding:28px 20px;text-align:center">
            <!-- Avatar Circle -->
            <div style="width:84px;height:84px;border-radius:50%;background:linear-gradient(135deg, #0c4a6e, #0891b2);color:#ffffff;font-size:32px;font-weight:900;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 8px 20px rgba(8,145,178,0.25);border:3px solid #ffffff">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            
            <!-- User Info -->
            <h3 style="font-size:17px;font-weight:800;color:#0f172a;margin-bottom:4px;letter-spacing:-0.2px">{{ $user->name }}</h3>
            <p style="font-size:12.5px;color:#64748b;margin-bottom:14px;word-break:break-all">{{ $user->email }}</p>
            
            <!-- Role Badge -->
            <div style="display:inline-flex;align-items:center;gap:6px;background:{{ $roleBgColor }};color:{{ $roleBadgeColor }};padding:6px 14px;border-radius:99px;font-size:11px;font-weight:700;border:1px solid rgba(13,148,136,0.2);margin-bottom:20px">
                <i class="fas fa-user-shield"></i> {{ $roleTitle }}
            </div>

            <div style="border-top:1px solid #f1f5f9;padding-top:16px;text-align:left;font-size:12px;color:#64748b">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <span>Status Akun</span>
                    <span class="badge badge-success" style="font-size:11px"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <span>ID Petugas</span>
                    <code style="font-size:11px;color:#0f172a;font-weight:700;background:#f1f5f9;padding:2px 6px;border-radius:4px">STAFF-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</code>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span>Terdaftar Sejak</span>
                    <span style="font-weight:600;color:#334155">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM EDIT PROFIL & PASSWORD (RIGHT CONTENT) --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        
        <!-- EDIT INFORMASI DIRI -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-user-pen" style="color:#0891b2"></i> Edit Informasi Diri</div>
            </div>
            <div class="card-body" style="padding:20px">
                <form action="{{ route($r . 'profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Nama Lengkap Petugas *</label>
                            <div style="position:relative">
                                <i class="fas fa-user" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px"></i>
                                <input type="text" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" value="{{ old('name', $user->name) }}" required style="padding-left:36px">
                            </div>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="margin:0">
                            <label class="form-label">Alamat Email *</label>
                            <div style="position:relative">
                                <i class="fas fa-envelope" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px"></i>
                                <input type="email" name="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}" value="{{ old('email', $user->email) }}" required style="padding-left:36px">
                            </div>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="text-align:right;margin-top:20px">
                        <button type="submit" class="btn btn-primary" style="padding:9px 20px;font-weight:700">
                            <i class="fas fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- UBAH PASSWORD -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-shield-halved" style="color:#dc2626"></i> Ubah Kata Sandi / Password</div>
            </div>
            <div class="card-body" style="padding:20px">
                <form action="{{ route($r . 'profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group" style="margin-bottom:16px">
                        <label class="form-label">Password Saat Ini</label>
                        <div style="position:relative">
                            <i class="fas fa-lock" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px"></i>
                            <input type="password" name="current_password" class="form-control {{ $errors->has('current_password')?'is-invalid':'' }}" placeholder="Masukkan password saat ini jika ingin mengubah" style="padding-left:36px">
                        </div>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Password Baru</label>
                            <div style="position:relative">
                                <i class="fas fa-key" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px"></i>
                                <input type="password" name="password" class="form-control {{ $errors->has('password')?'is-invalid':'' }}" placeholder="Minimal 6 karakter" style="padding-left:36px">
                            </div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group" style="margin:0">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div style="position:relative">
                                <i class="fas fa-key" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px"></i>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" style="padding-left:36px">
                            </div>
                        </div>
                    </div>

                    <div style="text-align:right;margin-top:20px">
                        <button type="submit" class="btn btn-outline" style="padding:9px 20px;font-weight:700;color:#dc2626;border-color:#dc2626">
                            <i class="fas fa-key me-1"></i> Update Password Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
