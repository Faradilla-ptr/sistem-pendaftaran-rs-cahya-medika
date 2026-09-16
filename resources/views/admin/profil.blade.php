@extends('layouts.app')

@php
    $roleTitle = 'Super Admin / Pendaftaran';
    if ($user->role === 'rekam_medis' || request()->is('rekam-medis*')) {
        $roleTitle = 'Petugas Rekam Medis & SATUSEHAT';
    }
@endphp

@section('page-title', 'Profil Akun Petugas - RS Cahya Medika')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase font-weight-bold" style="letter-spacing:1px;">
                <i class="fas fa-user-shield me-1"></i> Profil Akun Petugas
            </span>
            <h2 class="h3 font-weight-bold mt-2 text-dark">Informasi Akun {{ $roleTitle }}</h2>
            <p class="text-muted small mb-0">Kelola informasi data pribadi dan keamanan kata sandi akun Anda.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- USER SUMMARY CARD -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="card-body">
                    <div class="mx-auto rounded-circle bg-primary bg-opacity-10 text-primary font-weight-bold d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 90px; height: 90px; font-size: 36px; border: 3px solid #0284c7;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        @if($user->role === 'pendaftaran' || $user->role === 'admin')
                            <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-id-card me-1"></i> Pendaftaran & Super Admin</span>
                        @elseif($user->role === 'rekam_medis')
                            <span class="badge bg-teal text-white px-3 py-2 rounded-pill" style="background:#0d9488;"><i class="fas fa-file-medical me-1"></i> Rekam Medis & SATUSEHAT</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ strtoupper($user->role) }}</span>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="text-start text-muted small">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Status Akun:</span>
                            <span class="badge bg-success bg-opacity-10 text-success font-weight-bold"><i class="fas fa-check-circle me-1"></i> Aktif / Terverifikasi</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>ID Petugas:</span>
                            <span class="font-weight-bold text-dark">STAFF-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Terdaftar Sejak:</span>
                            <span class="font-weight-bold text-dark">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT FORM TABS -->
        <div class="col-lg-8">
            <!-- EDIT INFORMASI DIRI -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 font-weight-bold text-primary"><i class="fas fa-user-edit me-2"></i>Edit Data Profil</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $user->role === 'rekam_medis' ? route('rekam-medis.profil.update') : route('superadmin.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold small text-muted">NAMA LENGKAP PETUGAS</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold small text-muted">ALAMAT EMAIL</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GANTI KATA SANDI -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 font-weight-bold text-danger"><i class="fas fa-key me-2"></i>Ubah Password Akun</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $user->role === 'rekam_medis' ? route('rekam-medis.password.update') : route('superadmin.password.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold small text-muted">PASSWORD SAAT INI</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold small text-muted">PASSWORD BARU</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold small text-muted">KONFIRMASI PASSWORD BARU</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-danger rounded-pill px-4 font-weight-bold shadow-sm">
                                <i class="fas fa-lock me-1"></i> Update Password Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
