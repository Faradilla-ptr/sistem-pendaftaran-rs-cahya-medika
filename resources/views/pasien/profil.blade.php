@extends('layouts.app')
@section('title', 'Profil Saya - RS Cahya Medika')
@section('page-title', 'Profil Saya')

@push('styles')
<style>
.pwd-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; cursor: pointer; font-size: 14px; padding: 4px;
    border: none; background: none; line-height: 1;
}
.pwd-toggle:hover { color: #0891b2; }
.input-pw { padding-right: 40px !important; }
</style>
@endpush

@section('content')
<div style="max-width:900px;margin:0 auto">

@if(session('success'))
<div style="background:#d1fae5;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#065f46;font-size:13px">
    <i class="fas fa-check-circle" style="font-size:16px"></i> {{ session('success') }}
</div>
@endif

<!-- DATA DIRI -->
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-title">👤 Data Diri Pasien</div>
        @if($pasien && $pasien->satusehat_id)
            <span class="badge badge-success"><i class="fas fa-check"></i> Sinkron SatuSehat</span>
        @else
            <span class="badge badge-warning">⚠️ Belum Sinkron</span>
        @endif
    </div>
    <div class="card-body">
    <form action="{{ route('pasien.profil.update') }}" method="POST">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div class="form-group">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                value="{{ old('nama_lengkap', $pasien->nama_lengkap ?? '') }}" required>
            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">NIK (16 digit) *</label>
            <input type="text" name="nik" maxlength="16" class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                value="{{ old('nik', $pasien->nik ?? '') }}" required>
            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Tempat Lahir *</label>
            <input type="text" name="tempat_lahir" class="form-control"
                value="{{ old('tempat_lahir', $pasien->tempat_lahir ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Tanggal Lahir *</label>
            <input type="date" name="tanggal_lahir" class="form-control"
                value="{{ old('tanggal_lahir', optional($pasien->tanggal_lahir)->format('Y-m-d') ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Jenis Kelamin *</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Golongan Darah</label>
            <select name="golongan_darah" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach(['A','B','AB','O'] as $gd)
                    <option value="{{ $gd }}" {{ old('golongan_darah', $pasien->golongan_darah ?? '') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Agama</label>
            <select name="agama" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach(['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu'] as $ag)
                    <option value="{{ $ag }}" {{ old('agama', $pasien->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Status Pernikahan</label>
            <select name="status_pernikahan" class="form-select">
                <option value="">-- Pilih --</option>
                @foreach(['Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'] as $sp)
                    <option value="{{ $sp }}" {{ old('status_pernikahan', $pasien->status_pernikahan ?? '') == $sp ? 'selected' : '' }}>{{ $sp }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control"
                value="{{ old('pekerjaan', $pasien->pekerjaan ?? '') }}" placeholder="Wiraswasta, PNS, dll">
        </div>
        <div class="form-group">
            <label class="form-label">No. HP *</label>
            <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                value="{{ old('no_hp', $pasien->no_hp ?? '') }}" required>
            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', $pasien->email ?? '') }}">
        </div>
    </div>

    <!-- ALAMAT -->
    <div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:16px">
        <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px">📍 Alamat Domisili</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="form-group" style="grid-column:1/-1">
                <label class="form-label">Alamat Lengkap *</label>
                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Kecamatan *</label>
                <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pasien->kecamatan ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kabupaten/Kota *</label>
                <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $pasien->kabupaten ?? 'Bondowoso') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Provinsi *</label>
                <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pasien->provinsi ?? 'Jawa Timur') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Kode Pos</label>
                <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $pasien->kode_pos ?? '') }}">
            </div>
            <div style="grid-column:1/-1;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-top:4px">
                <div style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:8px">
                    🔗 Kode Wilayah BPS — untuk Integrasi SatuSehat
                    <a href="https://sig.bps.go.id/basisdata/index" target="_blank" style="font-weight:400;font-size:10px;margin-left:8px;color:#0891b2">Cari kode BPS →</a>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div>
                        <label class="form-label" style="font-size:10px">Kode Provinsi (2 digit)</label>
                        <input type="text" name="kode_provinsi" maxlength="2" class="form-control" style="font-family:monospace"
                            value="{{ old('kode_provinsi', $pasien->kode_provinsi ?? '35') }}" placeholder="35 = Jawa Timur">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:10px">Kode Kabupaten/Kota (4 digit)</label>
                        <input type="text" name="kode_kabupaten" maxlength="4" class="form-control" style="font-family:monospace"
                            value="{{ old('kode_kabupaten', $pasien->kode_kabupaten ?? '3578') }}" placeholder="3578 = Kota Surabaya">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:10px">Kode Kecamatan (6 digit)</label>
                        <input type="text" name="kode_kecamatan" maxlength="6" class="form-control" style="font-family:monospace"
                            value="{{ old('kode_kecamatan', $pasien->kode_kecamatan ?? '357801') }}" placeholder="357801">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:10px">Kode Kelurahan/Desa (10 digit)</label>
                        <input type="text" name="kode_kelurahan" maxlength="10" class="form-control" style="font-family:monospace"
                            value="{{ old('kode_kelurahan', $pasien->kode_kelurahan ?? '3578011001') }}" placeholder="3578011001">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENANGGUNG JAWAB -->
    <div style="padding:16px;background:#f8fafc;border-radius:12px;margin-bottom:20px">
        <div style="font-size:12px;font-weight:700;color:#0c4a6e;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px">🆘 Penanggung Jawab / Kontak Darurat</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="form-group">
                <label class="form-label">Nama PJ *</label>
                <input type="text" name="nama_pj" class="form-control {{ $errors->has('nama_pj') ? 'is-invalid' : '' }}"
                    value="{{ old('nama_pj', $pasien->nama_pj ?? '') }}" required>
                @error('nama_pj')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Hubungan *</label>
                <select name="hubungan_pj" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach(['Suami','Istri','Orang Tua','Anak','Saudara','Kerabat','Lainnya'] as $hub)
                        <option value="{{ $hub }}" {{ old('hubungan_pj', $pasien->hubungan_pj ?? '') == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">No. HP PJ *</label>
                <input type="text" name="no_hp_pj" class="form-control {{ $errors->has('no_hp_pj') ? 'is-invalid' : '' }}"
                    value="{{ old('no_hp_pj', $pasien->no_hp_pj ?? '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat PJ</label>
                <input type="text" name="alamat_pj" class="form-control" value="{{ old('alamat_pj', $pasien->alamat_pj ?? '') }}">
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;align-items:center">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
        @if($pasien && !$pasien->satusehat_id)
        <div style="padding:10px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:12px;color:#92400e;display:flex;align-items:center;gap:6px">
            <i class="fas fa-info-circle"></i> Data akan otomatis dikirim ke SatuSehat saat disimpan
        </div>
        @endif
    </div>
    </form>
    </div>
</div>

<!-- GANTI PASSWORD -->
<div class="card">
    <div class="card-header">
        <div class="card-title">🔐 Ganti Password</div>
    </div>
    <div class="card-body">
        @if($errors->has('password_lama') || $errors->has('password'))
        <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;display:flex;align-items:center;gap:8px">
            <i class="fas fa-exclamation-circle"></i>
            @error('password_lama'){{ $message }}@enderror
            @error('password'){{ $message }}@enderror
        </div>
        @endif

        <form action="{{ route('pasien.password.update') }}" method="POST">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-bottom:24px">

            <!-- Password Lama -->
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:6px">
                    <i class="fas fa-lock" style="color:#94a3b8;font-size:11px"></i>
                    Password Lama *
                </label>
                <div style="position:relative">
                    <input type="password" name="password_lama" id="pwdLama"
                        class="form-control input-pw {{ $errors->has('password_lama') ? 'is-invalid' : '' }}"
                        placeholder="Password saat ini" required>
                    <button type="button" class="pwd-toggle" onclick="togglePwd('pwdLama', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Password Baru -->
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:6px">
                    <i class="fas fa-key" style="color:#94a3b8;font-size:11px"></i>
                    Password Baru *
                </label>
                <div style="position:relative">
                    <input type="password" name="password" id="pwdBaru"
                        class="form-control input-pw {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 8 karakter" required oninput="checkStr(this.value)">
                    <button type="button" class="pwd-toggle" onclick="togglePwd('pwdBaru', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <!-- Strength bar -->
                <div style="display:flex;gap:3px;margin-top:6px">
                    <div id="sb1" style="flex:1;height:3px;border-radius:3px;background:#e2e8f0;transition:background 0.3s"></div>
                    <div id="sb2" style="flex:1;height:3px;border-radius:3px;background:#e2e8f0;transition:background 0.3s"></div>
                    <div id="sb3" style="flex:1;height:3px;border-radius:3px;background:#e2e8f0;transition:background 0.3s"></div>
                </div>
                <div id="strLabel" style="font-size:10px;color:#94a3b8;margin-top:3px"></div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:6px">
                    <i class="fas fa-check-circle" style="color:#94a3b8;font-size:11px"></i>
                    Konfirmasi Password *
                </label>
                <div style="position:relative">
                    <input type="password" name="password_confirmation" id="pwdKonfirm"
                        class="form-control input-pw"
                        placeholder="Ulangi password baru" required oninput="checkMatch()">
                    <button type="button" class="pwd-toggle" onclick="togglePwd('pwdKonfirm', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="matchMsg" style="font-size:10px;margin-top:3px"></div>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-key"></i> Update Password
            </button>
            <div style="font-size:12px;color:#94a3b8">
                <i class="fas fa-shield-alt" style="margin-right:4px"></i>
                Password harus minimal 8 karakter
            </div>
        </div>
        </form>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
        btn.style.color = '#0891b2';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
        btn.style.color = '#94a3b8';
    }
}

function checkStr(val) {
    const bars   = ['sb1','sb2','sb3'];
    const colors = { weak:'#dc2626', medium:'#f59e0b', strong:'#059669' };
    const label  = document.getElementById('strLabel');
    bars.forEach(b => { document.getElementById(b).style.background = '#e2e8f0'; });

    if (val.length === 0) { label.textContent = ''; return; }

    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength++;
    if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) strength++;

    const map = [
        { bars:1, color: colors.weak,   text:'Lemah',  textColor:'#dc2626' },
        { bars:2, color: colors.medium, text:'Cukup',  textColor:'#d97706' },
        { bars:3, color: colors.strong, text:'Kuat',   textColor:'#059669' },
    ];
    const s = map[Math.min(strength, 2)];
    for (let i = 0; i < s.bars; i++) {
        document.getElementById(bars[i]).style.background = s.color;
    }
    label.textContent  = s.text;
    label.style.color  = s.textColor;
    label.style.fontWeight = '600';
}

function checkMatch() {
    const baru    = document.getElementById('pwdBaru').value;
    const konfirm = document.getElementById('pwdKonfirm').value;
    const msg     = document.getElementById('matchMsg');
    if (!konfirm) { msg.textContent = ''; return; }
    if (baru === konfirm) {
        msg.textContent = '✓ Password cocok';
        msg.style.color = '#059669';
    } else {
        msg.textContent = '✗ Password tidak cocok';
        msg.style.color = '#dc2626';
    }
}
</script>
@endpush
