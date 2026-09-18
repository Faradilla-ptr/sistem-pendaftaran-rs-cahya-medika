<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Services\SatuSehatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    protected SatuSehatService $satuSehat;

    public function __construct(SatuSehatService $satuSehat)
    {
        $this->satuSehat = $satuSehat;
    }

    public function dashboard()
    {
        /** @var User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.profil')
                ->with('warning', 'Lengkapi profil Anda terlebih dahulu.');
        }

        $pendaftaran_aktif = Pendaftaran::with(['poli', 'dokter'])
            ->where('pasien_id', $pasien->id)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDate('tanggal_kunjungan', '>=', today())
            ->orderBy('tanggal_kunjungan')
            ->get();

        $riwayat = Pendaftaran::with(['poli', 'dokter'])
            ->where('pasien_id', $pasien->id)
            ->where('status', 'selesai')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->limit(5)
            ->get();

        $riwayat_terakhir = $riwayat;

        $stats = [
            'total_pendaftaran' => Pendaftaran::where('pasien_id', $pasien->id)->count(),
            'menunggu'          => Pendaftaran::where('pasien_id', $pasien->id)->whereIn('status', ['menunggu', 'dipanggil'])->count(),
            'selesai'           => Pendaftaran::where('pasien_id', $pasien->id)->where('status', 'selesai')->count(),
        ];

        return view('pasien.dashboard', compact('pasien', 'pendaftaran_aktif', 'riwayat', 'riwayat_terakhir', 'stats'));
    }

    public function profil()
    {
        /** @var User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        return view('pasien.profil', compact('pasien'));
    }

    public function updateProfil(Request $request)
    {
        /** @var User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        $validator = Validator::make($request->all(), [
            'nama_lengkap'     => 'required|string|max:255',
            'nik'              => 'required|size:16|unique:pasien,nik,' . ($pasien->id ?? 0),
            'tanggal_lahir'    => 'required|date',
            'tempat_lahir'     => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'golongan_darah'   => 'nullable|string|max:20',
            'agama'            => 'nullable|string|max:50',
            'status_pernikahan' => 'nullable|string|max:50',
            'pekerjaan'        => 'nullable|string|max:100',
            'no_hp'            => 'required|string|regex:/^08[0-9]{8,11}$/',
            'email'            => 'nullable|email|max:255',
            'alamat'           => 'required|string',
            'kecamatan'        => 'required|string|max:100',
            'kabupaten'        => 'required|string|max:100',
            'provinsi'         => 'required|string|max:100',
            // Penanggung Jawab
            'nama_pj'          => 'required|string|max:255',
            'hubungan_pj'      => 'required|string|max:50',
            'no_hp_pj'         => 'required|string|regex:/^08[0-9]{8,11}$/',
        ], [
            'no_hp.regex'     => 'Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 angka.',
            'no_hp_pj.regex'  => 'Nomor HP Penanggung Jawab harus diawali dengan 08 dan terdiri dari 10-13 angka.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($pasien) {
            $pasien->update($request->except(['_token', '_method']));
        } else {
            $pasien = $user->pasien()->create(
                array_merge($request->except(['_token', '_method']), ['status' => 'aktif'])
            );
        }

        // Sync ke SatuSehat — refresh dari DB supaya data terbaru
        $pasien->refresh();
        if (!$pasien->satusehat_id) {
            // Cari dulu di SatuSehat berdasar NIK, kalau tidak ada baru buat
            $ssResponse = $this->satuSehat->getOrCreatePatient([
                'nik'            => $pasien->nik,
                'nama_lengkap'   => $pasien->nama_lengkap,
                'no_hp'          => $pasien->no_hp,
                'jenis_kelamin'  => $pasien->jenis_kelamin,
                'tanggal_lahir'  => $pasien->tanggal_lahir ? $pasien->tanggal_lahir->format('Y-m-d') : null,
                'alamat'         => $pasien->alamat,
                'kabupaten'      => $pasien->kabupaten,
                'kode_pos'       => $pasien->kode_pos,
                'kode_provinsi'  => $pasien->kode_provinsi  ?? '35',
                'kode_kabupaten' => $pasien->kode_kabupaten ?? '3511',
                'kode_kecamatan' => $pasien->kode_kecamatan ?? '351101',
                'kode_kelurahan' => $pasien->kode_kelurahan ?? '3511010001',
            ]);
            if (!empty($ssResponse['success']) && !empty($ssResponse['data']['id'])) {
                $pasien->update(['satusehat_id' => $ssResponse['data']['id']]);
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function gantiPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required',
            'password'      => 'required|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi',
            'password.required'      => 'Password baru wajib diisi',
            'password.min'           => 'Password minimal 8 karakter',
            'password.confirmed'     => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function riwayat()
    {
        /** @var User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        $riwayat = Pendaftaran::with(['poli', 'dokter'])
            ->where('pasien_id', $pasien->id)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(15);

        return view('pasien.riwayat', compact('riwayat', 'pasien'));
    }

    public function ocrKtp(Request $request, \App\Services\GeminiOcrService $ocrService)
    {
        try {
            $base64 = $request->input('ktp_image');
            if ($request->hasFile('ktp_image')) {
                $file = $request->file('ktp_image');
                $base64 = base64_encode(file_get_contents($file->getRealPath()));
            }

            if (empty($base64)) {
                return response()->json(['success' => false, 'message' => 'Foto KTP wajib diunggah.'], 400);
            }

            $result = $ocrService->parseKtpImage($base64);

            if (empty($result['success'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal membaca KTP menggunakan Gemini API.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data'    => $result['data'] ?? [],
                'message' => 'Data KTP berhasil diekstraksi menggunakan AI Gemini API.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses OCR KTP: ' . $e->getMessage()
            ], 500);
        }
    }
}