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
        $this->middleware('auth');
        $this->middleware('role:pasien');
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
        $dokter = Dokter::with('poli')
            ->where('poli_id', $request->poli_id)
            ->where('is_active', true)
            ->get()
            ->map(function ($d) {
                return [
                    'id'          => $d->id,
                    'nama'        => $d->nama_lengkap,
                    'spesialisasi' => $d->spesialisasi,
                    'jadwal'      => $d->jadwal,
                ];
            });

        return response()->json($dokter);
    }

    public function getJadwalDokter(Request $request)
    {
        $dokter = Dokter::find($request->dokter_id);
        if (!$dokter) {
            return response()->json(['error' => 'Dokter tidak ditemukan'], 404);
        }

        $tanggal = $request->tanggal ?? today()->format('Y-m-d');
        $jumlahAntrian = Pendaftaran::where('dokter_id', $dokter->id)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->whereNotIn('status', ['batal'])
            ->count();

        return response()->json([
            'dokter'        => $dokter->nama_lengkap,
            'jadwal'        => $dokter->jadwal,
            'antrian_hari_ini' => $jumlahAntrian,
            'kuota'         => 30,
            'sisa_kuota'    => max(0, 30 - $jumlahAntrian),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'poli_id'          => 'required|exists:poli,id',
            'dokter_id'        => 'required|exists:dokter,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jam_kunjungan'    => 'required|string',
            'jenis_kunjungan'  => 'required|in:baru,kontrol',
            'keluhan'          => 'required|string|max:500',
        ], [
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

        $dokter = Dokter::find($request->dokter_id);

        // Cek apakah sudah ada pendaftaran di hari yang sama
        $existing = Pendaftaran::where('pasien_id', $pasien->id)
            ->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->whereNotIn('status', ['batal'])
            ->first();

        if ($existing) {
            return back()->withErrors([
                'tanggal_kunjungan' => 'Anda sudah memiliki pendaftaran pada tanggal tersebut.',
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

            // Buat data Pendaftaran
            $pendaftaran = Pendaftaran::create([
                'pasien_id'         => $pasien->id,
                'dokter_id'         => $request->dokter_id,
                'poli_id'           => $request->poli_id,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jam_kunjungan'     => $request->jam_kunjungan,
                'jenis_kunjungan'   => $request->jenis_kunjungan,
                'keluhan'           => $request->keluhan,
                'status'            => 'menunggu',
                'satusehat_status'  => 'pending',
                'biaya_konsultasi'  => 150000,
            ]);

            // Kirim Encounter ke SatuSehat hanya jika pasien punya SatuSehat ID
            if ($pasien->satusehat_id) {
                $encounterData = [
                    'patient_id'        => $pasien->satusehat_id,
                    'nama_pasien'       => $pasien->nama_lengkap,
                    'dokter_id'         => $dokter->satusehat_id ?? '',
                    'nama_dokter'       => $dokter->nama_lengkap,
                    'tanggal_kunjungan' => $request->tanggal_kunjungan,
                    'jam_kunjungan'     => $request->jam_kunjungan,
                    'keluhan'           => $request->keluhan,
                    'kode_booking'      => $pendaftaran->kode_booking,
                    'nama_poli'         => $pendaftaran->poli->nama ?? 'Rawat Jalan',
                ];

                $ssResponse = $this->satuSehat->createEncounter($encounterData);

                if (!empty($ssResponse['success'])) {
                    $pendaftaran->update([
                        'satusehat_encounter_id' => $ssResponse['data']['id'] ?? null,
                        'satusehat_response'     => $ssResponse['data'],
                        'satusehat_status'       => 'success',
                    ]);
                } else {
                    $pendaftaran->update(['satusehat_status' => 'failed']);
                }
            } else {
                // Pasien belum bisa disinkronkan — tandai failed agar bisa di-retry
                $pendaftaran->update(['satusehat_status' => 'failed']);
            }

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
            abort(403, 'Akses ditolak.');
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
            abort(403);
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