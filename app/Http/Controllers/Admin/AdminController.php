<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\User;
use App\Services\SatuSehatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected SatuSehatService $satuSehat;

    public function __construct(SatuSehatService $satuSehat)
    {
        $this->satuSehat = $satuSehat;
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_pasien' => Pasien::count(),
            'total_pendaftaran_hari_ini' => Pendaftaran::whereDate('tanggal_kunjungan', today())->count(),
            'pendaftaran_menunggu' => Pendaftaran::where('status', 'menunggu')->whereDate('tanggal_kunjungan', today())->count(),
            'pendaftaran_selesai_hari_ini' => Pendaftaran::where('status', 'selesai')->whereDate('tanggal_kunjungan', today())->count(),
            'total_pendaftaran_bulan_ini' => Pendaftaran::whereMonth('tanggal_kunjungan', now()->month)->count(),
            'total_dokter' => Dokter::where('is_active', true)->count(),
        ];

        $pendaftaran_hari_ini = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereDate('tanggal_kunjungan', today())
            ->orderBy('no_antrian')
            ->limit(10)
            ->get();

        $satusehat_status = [
            'success' => Pendaftaran::where('satusehat_status', 'success')->count(),
            'pending' => Pendaftaran::where('satusehat_status', 'pending')->count(),
            'failed' => Pendaftaran::where('satusehat_status', 'failed')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'pendaftaran_hari_ini', 'satusehat_status'));
    }

    // ======== PASIEN MANAGEMENT ========
    public function pasienIndex(Request $request)
    {
        $query = Pasien::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('no_rm', 'like', '%' . $request->search . '%')
                    ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $pasien = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.pasien.index', compact('pasien'));
    }

    public function pasienShow(Pasien $pasien)
    {
        $pasien->load(['pendaftaran.poli', 'pendaftaran.dokter']);
        $satusehatData = null;

        if ($pasien->nik) {
            $satusehatData = $this->satuSehat->getPatientByNik($pasien->nik);
        }

        return view('admin.pasien.show', compact('pasien', 'satusehatData'));
    }

    public function pasienEdit(Pasien $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function pasienUpdate(Request $request, Pasien $pasien)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|size:16|unique:pasien,nik,' . $pasien->id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pasien->update($request->except(['_token', '_method']));

        return redirect()->route('admin.pasien.show', $pasien->id)
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    // ======== PENDAFTARAN MANAGEMENT ========
    public function pendaftaranIndex(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'dokter', 'poli']);

        if ($request->tanggal) {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        } else {
            $query->whereDate('tanggal_kunjungan', today());
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->poli_id) {
            $query->where('poli_id', $request->poli_id);
        }

        if ($request->search) {
            $query->whereHas('pasien', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                    ->orWhere('no_rm', 'like', '%' . $request->search . '%');
            })->orWhere('kode_booking', 'like', '%' . $request->search . '%');
        }

        $pendaftaran = $query->orderBy('no_antrian')->paginate(20);
        $poli = Poli::where('is_active', true)->get();

        return view('admin.pendaftaran.index', compact('pendaftaran', 'poli'));
    }

    public function pendaftaranShow(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['pasien', 'dokter', 'poli']);
        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    public function pendaftaranUpdateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dipanggil,selesai,batal',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $pendaftaran->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin ?? $pendaftaran->catatan_admin,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
        }

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function pendaftaranUpdateVital(Request $request, Pendaftaran $pendaftaran)
    {
        $pendaftaran->update([
            'tekanan_darah' => $request->tekanan_darah,
            'suhu' => $request->suhu,
            'nadi' => $request->nadi,
            'respirasi' => $request->respirasi,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'spo2' => $request->spo2,
        ]);

        return back()->with('success', 'Tanda vital berhasil disimpan.');
    }

    // ======== DOKTER MANAGEMENT ========
    public function dokterIndex()
    {
        $dokter = Dokter::with('poli')->paginate(15);
        return view('admin.dokter.index', compact('dokter'));
    }

    public function dokterCreate()
    {
        $poli = Poli::where('is_active', true)->get();
        return view('admin.dokter.create', compact('poli'));
    }

    public function dokterStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'poli_id' => 'required|exists:poli,id',
            'spesialisasi' => 'required|string|max:255',
            'str_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $jadwal = [];
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        foreach ($hariList as $hari) {
            if ($request->has("jadwal_{$hari}")) {
                $jadwal[$hari] = [
                    'aktif' => true,
                    'jam_mulai' => $request->input("jam_mulai_{$hari}", '08:00'),
                    'jam_selesai' => $request->input("jam_selesai_{$hari}", '12:00'),
                ];
            }
        }

        Dokter::create([
            'nama' => $request->nama,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'spesialisasi' => $request->spesialisasi,
            'poli_id' => $request->poli_id,
            'str_number' => $request->str_number,
            'nik' => $request->nik,
            'jadwal' => $jadwal,
            'is_active' => true,
        ]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function dokterEdit(Dokter $dokter)
    {
        $poli = Poli::where('is_active', true)->get();
        return view('admin.dokter.edit', compact('dokter', 'poli'));
    }

    public function dokterUpdate(Request $request, Dokter $dokter)
    {
        $jadwal = [];
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        foreach ($hariList as $hari) {
            if ($request->has("jadwal_{$hari}")) {
                $jadwal[$hari] = [
                    'aktif' => true,
                    'jam_mulai' => $request->input("jam_mulai_{$hari}", '08:00'),
                    'jam_selesai' => $request->input("jam_selesai_{$hari}", '12:00'),
                ];
            }
        }

        $dokter->update(array_merge(
            $request->except(['_token', '_method']),
            ['jadwal' => $jadwal]
        ));

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    // ======== POLI MANAGEMENT ========
    public function poliIndex()
    {
        $poli = Poli::withCount(['dokter', 'pendaftaran'])->paginate(15);
        return view('admin.poli.index', compact('poli'));
    }

    public function poliStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|unique:poli,kode',
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Poli::create($request->all());

        return redirect()->route('admin.poli.index')
            ->with('success', 'Poli berhasil ditambahkan.');
    }

    // ======== SATUSEHAT ========
    public function satusehatStatus()
    {
        $organization = $this->satuSehat->getOrganization();
        $stats = [
            'total' => Pendaftaran::count(),
            'success' => Pendaftaran::where('satusehat_status', 'success')->count(),
            'pending' => Pendaftaran::where('satusehat_status', 'pending')->count(),
            'failed' => Pendaftaran::where('satusehat_status', 'failed')->count(),
        ];

        $pendaftaran_failed = Pendaftaran::with(['pasien', 'poli'])
            ->where('satusehat_status', 'failed')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.satusehat.status', compact('organization', 'stats', 'pendaftaran_failed'));
    }

    public function satusehatSync(Pendaftaran $pendaftaran)
    {
        $pasien = $pendaftaran->pasien;
        $dokter = $pendaftaran->dokter;

        $encounterData = [
            'patient_id' => $pasien->satusehat_id ?? 'patient-dummy-' . $pasien->id,
            'nama_pasien' => $pasien->nama_lengkap,
            'dokter_id' => $dokter->satusehat_id ?? 'practitioner-dummy',
            'nama_dokter' => $dokter->nama_lengkap,
            'tanggal_kunjungan' => $pendaftaran->tanggal_kunjungan->format('Y-m-d'),
            'jam_kunjungan' => $pendaftaran->jam_kunjungan,
            'keluhan' => $pendaftaran->keluhan,
        ];

        $response = $this->satuSehat->createEncounter($encounterData);

        if ($response['success']) {
            $pendaftaran->update([
                'satusehat_encounter_id' => $response['data']['id'] ?? null,
                'satusehat_response' => $response['data'],
                'satusehat_status' => 'success',
            ]);
            return back()->with('success', 'Sinkronisasi SatuSehat berhasil.');
        }

        return back()->withErrors(['error' => 'Sinkronisasi gagal.']);
    }

    // ======== LAPORAN ========
    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $data = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->get();

        $byPoli = $data->groupBy('poli_id')->map(function ($items) {
            return [
                'nama' => $items->first()->poli->nama ?? '-',
                'total' => $items->count(),
                'selesai' => $items->where('status', 'selesai')->count(),
            ];
        });

        $byStatus = $data->groupBy('status')->map->count();

        return view('admin.laporan', compact('data', 'byPoli', 'byStatus', 'bulan', 'tahun'));
    }
}
