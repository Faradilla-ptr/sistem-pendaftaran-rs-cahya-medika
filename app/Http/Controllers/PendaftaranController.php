<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Services\SatuSehatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    protected SatuSehatService $satuSehat;

    public function __construct(SatuSehatService $satuSehat)
    {
        $this->satuSehat = $satuSehat;
    }

    public function index()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.profil')
                ->with('warning', 'Lengkapi profil Anda terlebih dahulu.');
        }

        $pendaftaran = Pendaftaran::with(['poli', 'dokter'])
            ->where('pasien_id', $pasien->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pasien.pendaftaran.index', compact('pendaftaran', 'pasien'));
    }

    public function create()
    {
        $pasien = Auth::user()->pasien;

        if (!$pasien) {
            return redirect()->route('pasien.profil')
                ->with('warning', 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar.');
        }

        $poli = Poli::where('is_active', true)->get();

        return view('pasien.pendaftaran.create', compact('poli', 'pasien'));
    }

    public function getDokterByPoli(Request $request)
    {
        $tanggal = $request->tanggal ?? today()->format('Y-m-d');

        $daysMap = [
            'Sunday'    => 'minggu',
            'Monday'    => 'senin',
            'Tuesday'   => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday'  => 'kamis',
            'Friday'    => 'jumat',
            'Saturday'  => 'sabtu',
        ];
        $dayEnglish = \Carbon\Carbon::parse($tanggal)->format('l');
        $dayKey = $daysMap[$dayEnglish] ?? 'senin';

        $dokter = Dokter::with('poli')
            ->where('poli_id', $request->poli_id)
            ->where('is_active', true)
            ->get()
            ->filter(function ($d) use ($dayKey) {
                $jadwal = $d->jadwal;
                if (is_string($jadwal)) {
                    $jadwal = json_decode($jadwal, true);
                }
                if (!$jadwal || !is_array($jadwal)) return true;

                $hariJadwal = $jadwal[$dayKey] ?? null;
                return !empty($hariJadwal['aktif']);
            })
            ->map(function ($d) use ($dayKey) {
                $jadwal = is_string($d->jadwal) ? json_decode($d->jadwal, true) : $d->jadwal;
                $jamHariIni = $jadwal[$dayKey] ?? null;
                $jamText = (isset($jamHariIni['jam_mulai']) && isset($jamHariIni['jam_selesai']))
                    ? ($jamHariIni['jam_mulai'] . ' - ' . $jamHariIni['jam_selesai'] . ' WIB')
                    : 'Praktik';
                return [
                    'id'           => $d->id,
                    'nama'         => $d->nama_lengkap,
                    'spesialisasi'  => $d->spesialisasi,
                    'jadwal'       => $jadwal,
                    'jam_praktik'  => $jamText,
                    'hari_ini'     => ucfirst($dayKey),
                ];
            })
            ->values();

        return response()->json($dokter);
    }

    public function getJadwalDokter(Request $request)
    {
        $dokter = Dokter::find($request->dokter_id);
        if (!$dokter) {
            return response()->json(['error' => 'Dokter tidak ditemukan'], 404);
        }

        $tanggal = $request->tanggal ?? today()->format('Y-m-d');

        $daysMap = [
            'Sunday'    => 'minggu',
            'Monday'    => 'senin',
            'Tuesday'   => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday'  => 'kamis',
            'Friday'    => 'jumat',
            'Saturday'  => 'sabtu',
        ];
        $dayEnglish = \Carbon\Carbon::parse($tanggal)->format('l');
        $dayKey = $daysMap[$dayEnglish] ?? 'senin';

        $jadwal = is_string($dokter->jadwal) ? json_decode($dokter->jadwal, true) : $dokter->jadwal;
        $hariJadwal = $jadwal[$dayKey] ?? null;
        $isLibur = empty($hariJadwal['aktif']);

        $jumlahAntrian = Pendaftaran::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->whereNotIn('status', ['batal'])
            ->count();

        return response()->json([
            'dokter'           => $dokter->nama_lengkap,
            'jadwal'           => $dokter->jadwal,
            'is_libur'         => $isLibur,
            'hari'             => ucfirst($dayKey),
            'jam_mulai'        => $hariJadwal['jam_mulai'] ?? '08:00',
            'jam_selesai'      => $hariJadwal['jam_selesai'] ?? '12:00',
            'antrian_hari_ini' => $jumlahAntrian,
            'kuota'            => 30,
            'sisa_kuota'       => max(0, 30 - $jumlahAntrian),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik'               => 'nullable|string|max:20',
            'nama_lengkap'      => 'nullable|string|max:100',
            'tempat_lahir'      => 'nullable|string|max:50',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'pekerjaan'          => 'nullable|string|max:100',
            'agama'              => 'nullable|string|max:50',
            'pendidikan'         => 'nullable|string|max:50',
            'status_pernikahan'  => 'nullable|string|max:50',
            'alamat'            => 'nullable|string|max:255',
            'kelurahan'         => 'nullable|string|max:100',
            'kecamatan'         => 'nullable|string|max:100',
            'kabupaten'         => 'nullable|string|max:100',
            'provinsi'          => 'nullable|string|max:100',
            'warga_negara'      => 'nullable|string|max:50',
            'suku'              => 'nullable|string|max:50',
            'golongan_darah'    => 'nullable|string|max:10',
            'nama_ibu'          => 'nullable|string|max:100',
            'nama_ayah'         => 'nullable|string|max:100',
            'no_hp'             => 'nullable|string|regex:/^08[0-9]{8,11}$/',
            'riwayat_alergi'    => 'nullable|string|max:50',
            'jenis_alergi'      => 'nullable|string|max:255',

            // Penanggung Jawab
            'nama_pj'           => 'nullable|string|max:100',
            'jenis_kelamin_pj'  => 'nullable|in:L,P',
            'hubungan_pj'       => 'nullable|string|max:50',
            'pekerjaan_pj'      => 'nullable|string|max:100',
            'alamat_pj'         => 'nullable|string|max:255',
            'kelurahan_pj'      => 'nullable|string|max:100',
            'kecamatan_pj'      => 'nullable|string|max:100',
            'kabupaten_pj'      => 'nullable|string|max:100',
            'provinsi_pj'       => 'nullable|string|max:100',
            'no_hp_pj'          => 'nullable|string|regex:/^08[0-9]{8,11}$/',

            // Pendaftaran & Poli
            'poli_id'           => 'required|exists:poli,id',
            'dokter_id'         => 'required|exists:dokter,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jam_kunjungan'     => 'required|string',
            'jenis_kunjungan'   => 'required|in:baru,kontrol',
            'keluhan'           => 'required|string|max:500',
        ], [
            'no_hp.regex'                => 'Nomor HP harus diawali dengan 08 dan terdiri dari 10-13 angka.',
            'no_hp_pj.regex'             => 'Nomor HP Penanggung Jawab harus diawali dengan 08 dan terdiri dari 10-13 angka.',
            'poli_id.required'           => 'Poli wajib dipilih',
            'dokter_id.required'         => 'Dokter wajib dipilih',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini',
            'jam_kunjungan.required'     => 'Jam kunjungan wajib dipilih',
            'jenis_kunjungan.required'   => 'Jenis kunjungan wajib dipilih',
            'keluhan.required'           => 'Keluhan wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        if (!$pasien) {
            return back()->withErrors(['error' => 'Profil pasien tidak ditemukan. Lengkapi profil Anda terlebih dahulu.']);
        }

        // Update all fields of FORMULIR IDENTITAS PASIEN
        $pasienFields = [
            'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'pekerjaan', 'agama', 'pendidikan', 'status_pernikahan', 'alamat',
            'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'warga_negara',
            'suku', 'golongan_darah', 'nama_ibu', 'nama_ayah', 'no_hp',
            'riwayat_alergi', 'jenis_alergi',
            'nama_pj', 'jenis_kelamin_pj', 'hubungan_pj', 'pekerjaan_pj',
            'alamat_pj', 'kelurahan_pj', 'kecamatan_pj', 'kabupaten_pj', 'provinsi_pj', 'no_hp_pj'
        ];

        $updateData = [];
        foreach ($pasienFields as $f) {
            if ($request->has($f) && $request->input($f) !== null) {
                $updateData[$f] = $request->input($f);
            }
        }

        if (isset($updateData['nik'])) {
            if ($updateData['nik'] === $pasien->nik) {
                unset($updateData['nik']);
            } else {
                $existsOther = Pasien::where('nik', $updateData['nik'])->where('id', '!=', $pasien->id)->exists();
                if ($existsOther) {
                    unset($updateData['nik']);
                }
            }
        }

        if (!empty($updateData)) {
            $pasien->update($updateData);
            $pasien->refresh();
        }

        $dokter = Dokter::find($request->dokter_id);

        // Maksimal 3 pendaftaran per pasien pada tanggal kunjungan yang sama
        $jumlahPendaftaranHariIni = Pendaftaran::where('pasien_id', $pasien->id)
            ->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->whereNotIn('status', ['batal'])
            ->count();

        if ($jumlahPendaftaranHariIni >= 3) {
            return back()->withErrors([
                'tanggal_kunjungan' => 'Batas maksimal pendaftaran (3 kali) pada tanggal tersebut telah tercapai.',
            ])->withInput();
        }

        // Cek pendaftaran ganda pada dokter yang sama di hari yang sama
        $existingSameDoctor = Pendaftaran::where('pasien_id', $pasien->id)
            ->where('dokter_id', $request->dokter_id)
            ->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->whereNotIn('status', ['batal'])
            ->first();

        if ($existingSameDoctor) {
            return back()->withErrors([
                'dokter_id' => 'Anda sudah mendaftar pada dokter yang sama di tanggal kunjungan ini.',
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // --- Pastikan pasien punya SatuSehat ID sebelum buat encounter ---
            if (!$pasien->satusehat_id) {
                $ssPatient = $this->satuSehat->getOrCreatePatient([
                    'nik'           => $pasien->nik,
                    'nama_lengkap'  => $pasien->nama_lengkap,
                    'no_hp'         => $pasien->no_hp,
                    'jenis_kelamin' => $pasien->jenis_kelamin,
                    'tanggal_lahir' => $pasien->tanggal_lahir
                        ? $pasien->tanggal_lahir->format('Y-m-d') : null,
                    'alamat'        => $pasien->alamat,
                    'kabupaten'     => $pasien->kabupaten,
                    'kode_pos'      => $pasien->kode_pos,
                ]);

                if (!empty($ssPatient['success']) && !empty($ssPatient['data']['id'])) {
                    $pasien->update(['satusehat_id' => $ssPatient['data']['id']]);
                    $pasien->refresh();
                }
            }

            // Buat data Pendaftaran Online (tanpa no_antrian dulu)
            $pendaftaran = Pendaftaran::create([
                'no_antrian'        => 0,
                'pasien_id'         => $pasien->id,
                'dokter_id'         => $request->dokter_id,
                'poli_id'           => $request->poli_id,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jam_kunjungan'     => $request->jam_kunjungan,
                'jenis_kunjungan'   => $request->jenis_kunjungan,
                'keluhan'           => $request->keluhan,
                'status'            => 'terdaftar_online',
                'satusehat_status'  => 'pending',
                'biaya_konsultasi'  => 150000,
                'deposit_awal'      => 200000,
                'jarak_km'          => $request->jarak_km ?? '3.5 km',
                'estimasi_menit'    => $request->estimasi_menit ?? 12,
                'qr_code_data'      => 'CM-' . date('Ymd') . '-' . rand(1000, 9999),
                'is_checkin'        => false,
            ]);

            // Set qr_code_data persis ke kode_booking
            $pendaftaran->update(['qr_code_data' => $pendaftaran->kode_booking]);

            DB::commit();

            return redirect()->route('pasien.pendaftaran.show', $pendaftaran->id)
                ->with('success', 'Pendaftaran berhasil! Kode booking Anda: ' . $pendaftaran->kode_booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Pendaftaran $pendaftaran)
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        if ($pendaftaran->pasien_id !== $pasien->id) {
            return redirect()->route('pasien.pendaftaran.index')->with('error', 'Pendaftaran tidak ditemukan atau bukan milik Anda.');
        }

        $pendaftaran->load(['poli', 'dokter', 'pasien']);

        return view('pasien.pendaftaran.show', compact('pendaftaran'));
    }

    public function cancel(Request $request, Pendaftaran $pendaftaran)
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $pasien = $user->pasien;

        if ($pendaftaran->pasien_id !== $pasien->id) {
            return redirect()->route('pasien.pendaftaran.index')->with('error', 'Pendaftaran tidak ditemukan atau bukan milik Anda.');
        }

        if (!in_array($pendaftaran->status, ['menunggu'])) {
            return back()->withErrors(['error' => 'Pendaftaran tidak dapat dibatalkan.']);
        }

        $pendaftaran->update([
            'status'         => 'batal',
            'catatan_admin'  => 'Dibatalkan oleh pasien: ' . ($request->alasan ?? '-'),
        ]);

        return redirect()->route('pasien.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}