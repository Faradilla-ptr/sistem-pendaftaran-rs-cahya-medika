<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
            }

            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'no_hp'         => 'required|string|max:15',
            'nik'           => 'required|string|size:16|unique:pasien,nik',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agree'         => 'accepted',
        ], [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi',
            'email.required'         => 'Email wajib diisi',
            'email.email'            => 'Format email tidak valid',
            'email.unique'           => 'Email sudah terdaftar',
            'password.required'      => 'Password wajib diisi',
            'password.min'           => 'Password minimal 8 karakter',
            'password.confirmed'     => 'Konfirmasi password tidak cocok',
            'no_hp.required'         => 'No HP wajib diisi',
            'nik.required'           => 'NIK wajib diisi',
            'nik.size'               => 'NIK harus 16 digit',
            'nik.unique'             => 'NIK sudah terdaftar',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'agree.accepted'         => 'Anda harus menyetujui syarat & ketentuan',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Buat akun User
            $user = User::create([
                'name'      => $request->nama_lengkap,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => 'pasien',
                'is_active' => true,
            ]);

            // Buat data Pasien
            Pasien::create([
                'user_id'       => $user->id,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp'         => $request->no_hp,
                'status'        => 'aktif',
            ]);

            DB::commit();

            Auth::login($user);

            return redirect()->route('pasien.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '! Akun Anda berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function logout(Request $request)
    {
        $role = Auth::user() ? Auth::user()->role : null;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (in_array($role, ['admin', 'pendaftaran', 'rekam_medis'])) {
            return redirect()->route('admin.login')->with('success', 'Anda berhasil keluar.');
        }

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    protected function redirectByRole()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pasien.dashboard');
    }
}