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
            'total_pasien'                 => Pasien::count(),
            'total_pendaftaran_hari_ini'   => Pendaftaran::whereDate('tanggal_kunjungan', today())->count(),
            'pendaftaran_menunggu'         => Pendaftaran::where('status', 'menunggu')->whereDate('tanggal_kunjungan', today())->count(),
            'pendaftaran_selesai_hari_ini' => Pendaftaran::where('status', 'selesai')->whereDate('tanggal_kunjungan', today())->count(),
            'total_pendaftaran_bulan_ini'  => Pendaftaran::whereMonth('tanggal_kunjungan', now()->month)->whereYear('tanggal_kunjungan', now()->year)->count(),
            'total_dokter'                 => Dokter::where('is_active', true)->count(),
        ];

        $pendaftaran_hari_ini = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereDate('tanggal_kunjungan', today())
            ->orderBy('no_antrian')
            ->limit(10)
            ->get();

        $satusehat_status = [
            'success' => Pendaftaran::where('satusehat_status', 'success')->count(),
            'pending'  => Pendaftaran::where('satusehat_status', 'pending')->count(),
            'failed'   => Pendaftaran::where('satusehat_status', 'failed')->count(),
        ];

        // Chart: pendaftaran per bulan (12 bulan terakhir) — line chart
        $chartLabels  = [];
        $chartData    = [];
        $chartSelesai = [];
        for ($i = 11; $i >= 0; $i--) {
            $bln = now()->subMonths($i);
            $chartLabels[]  = $bln->locale('id')->isoFormat('MMM YYYY');
            $chartData[]    = Pendaftaran::whereYear('tanggal_kunjungan', $bln->year)
                ->whereMonth('tanggal_kunjungan', $bln->month)->count();
            $chartSelesai[] = Pendaftaran::whereYear('tanggal_kunjungan', $bln->year)
                ->whereMonth('tanggal_kunjungan', $bln->month)->where('status', 'selesai')->count();
        }

        // Chart: pendaftaran per poli bulan ini — bar chart
        $poliBarLabels = [];
        $poliBarData   = [];
        foreach (Poli::where('is_active', true)->get() as $poli) {
            $cnt = Pendaftaran::where('poli_id', $poli->id)
                ->whereMonth('tanggal_kunjungan', now()->month)
                ->whereYear('tanggal_kunjungan', now()->year)
                ->count();
            if ($cnt > 0) {
                $poliBarLabels[] = $poli->nama;
                $poliBarData[]   = $cnt;
            }
        }

        return view('admin.dashboard', compact(
            'stats', 'pendaftaran_hari_ini', 'satusehat_status',
            'chartLabels', 'chartData', 'chartSelesai',
            'poliBarLabels', 'poliBarData'
        ));
    }

    // ======== PASIEN MANAGEMENT ========
    public function pasienIndex(Request $request)
    {
        $query = Pasien::with('user');

        if ($request->jenis_kelamin) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->bulan_kunjungan && $request->tahun_kunjungan) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->whereMonth('tanggal_kunjungan', $request->bulan_kunjungan)
                  ->whereYear('tanggal_kunjungan', $request->tahun_kunjungan);
            });
        }

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

        // Filter: bulan+tahun OR tanggal spesifik OR default hari ini
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal_kunjungan', $request->bulan)
                  ->whereYear('tanggal_kunjungan', $request->tahun);
        } elseif ($request->tanggal) {
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
            $query->where(function ($q) use ($request) {
                $q->whereHas('pasien', function ($sq) use ($request) {
                    $sq->where('nama_lengkap', 'like', '%' . $request->search . '%')
                        ->orWhere('no_rm', 'like', '%' . $request->search . '%');
                })->orWhere('kode_booking', 'like', '%' . $request->search . '%');
            });
        }

        $pendaftaran = $query->orderBy('tanggal_kunjungan')->orderBy('no_antrian')->paginate(20);
        $poli        = Poli::where('is_active', true)->get();

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
    public function dokterIndex(Request $request)
    {
        $query = Dokter::with('poli');

        if ($request->poli_id) {
            $query->where('poli_id', $request->poli_id);
        }
        if ($request->spesialisasi) {
            $query->where('spesialisasi', 'like', '%' . $request->spesialisasi . '%');
        }
        if ($request->hari) {
            $hari = $request->hari;
            $query->where(function ($q) use ($hari) {
                $q->whereNotNull('jadwal')
                  ->whereRaw("JSON_EXTRACT(jadwal, '$.{$hari}.aktif') = true");
            });
        }

        $dokter = $query->paginate(15);
        $poli   = Poli::where('is_active', true)->get();

        return view('admin.dokter.index', compact('dokter', 'poli'));
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

    /**
     * Test koneksi ke SatuSehat API — bisa dicek via browser atau Postman
     * GET /admin/satusehat/test-koneksi
     */
    public function satusehatTestKoneksi()
    {
        $hasil = [
            'waktu'           => now()->toDateTimeString(),
            'base_url'        => config('satusehat.base_url'),
            'organization_id' => config('satusehat.organization_id'),
            'location_id'     => config('satusehat.location_id') ?: '(belum diset)',
            'use_dummy'       => config('satusehat.use_dummy'),
        ];

        // 1. Coba ambil access token
        $token = $this->satuSehat->getAccessToken();
        $hasil['access_token_status'] = $token && $token !== 'dummy_access_token_for_development'
            ? 'BERHASIL ✅'
            : 'GAGAL / DUMMY ❌';
        $hasil['access_token_preview'] = $token ? substr($token, 0, 30) . '...' : null;

        // 2. Coba GET Organization
        $org = $this->satuSehat->getOrganization();
        $hasil['organization_status'] = isset($org['resourceType']) && $org['resourceType'] === 'Organization'
            ? 'BERHASIL ✅'
            : (isset($org['_dummy']) ? 'DUMMY (tidak terhubung) ⚠️' : 'GAGAL ❌');
        $hasil['organization_name']   = $org['name'] ?? null;
        $hasil['organization_data']   = $org;

        return response()->json($hasil, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cari pasien di SatuSehat by NIK — GET /admin/satusehat/cari-pasien?nik=XXXX
     * Juga bisa by ID: ?id=P02478375538
     */
    public function satusehatCariPasien(\Illuminate\Http\Request $request)
    {
        $nik = trim($request->get('nik', ''));
        $id  = trim($request->get('id', ''));

        if (!$nik && !$id) {
            return response()->json([
                'petunjuk' => 'Gunakan: ?nik=9271060312000001 atau ?id=P02478375538',
                'dummy_nik' => [
                    '9271060312000001' => 'Dummy pasien #1',
                    '9271060312000002' => 'Dummy pasien #2',
                    '9271060312000003' => 'Dummy pasien #3',
                ],
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if ($id) {
            $resource = $this->satuSehat->getPatientById($id);
            if (empty($resource)) {
                return response()->json(['error' => 'Pasien tidak ditemukan', 'id' => $id], 404);
            }
            $info = $this->satuSehat->extractPatientInfo($resource);
            return response()->json([
                'ditemukan'     => true,
                'info_ringkas'  => $info,
                'fhir_resource' => $resource,
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $result = $this->satuSehat->getPatientByNik($nik);
        $total  = $result['total'] ?? 0;

        if ($total === 0) {
            return response()->json([
                'ditemukan' => false,
                'pesan'     => "NIK $nik tidak ditemukan di SatuSehat",
                'response'  => $result,
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $resource = $result['entry'][0]['resource'];
        $info     = $this->satuSehat->extractPatientInfo($resource);

        // Jika NIK sama dengan pasien di DB lokal, update satusehat_id
        $pasienLokal = \App\Models\Pasien::where('nik', $nik)->first();
        $updated     = false;
        if ($pasienLokal && !$pasienLokal->satusehat_id) {
            $pasienLokal->update(['satusehat_id' => $info['id']]);
            $updated = true;
        }

        return response()->json([
            'ditemukan'         => true,
            'total'             => $total,
            'info_ringkas'      => $info,
            'pasien_lokal'      => $pasienLokal ? ['id' => $pasienLokal->id, 'nama' => $pasienLokal->nama_lengkap, 'satusehat_id_updated' => $updated] : null,
            'fhir_resource'     => $resource,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cari dokter (Practitioner) di SatuSehat by NIK — GET /admin/satusehat/cari-dokter?nik=XXXX
     * Juga bisa by ID: ?id=UUID
     */
    public function satusehatCariDokter(\Illuminate\Http\Request $request)
    {
        $nik = trim($request->get('nik', ''));
        $id  = trim($request->get('id', ''));

        if (!$nik && !$id) {
            return response()->json([
                'petunjuk' => 'Gunakan: ?nik=NIK_DOKTER atau ?id=UUID_PRACTITIONER',
                'cara_pakai' => [
                    'by NIK'  => '/admin/satusehat/cari-dokter?nik=3171071012890005',
                    'by ID'   => '/admin/satusehat/cari-dokter?id=UUID-dari-SatuSehat',
                ],
                'catatan' => 'NIK harus NIK dokter yang sudah terdaftar di SatuSehat',
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if ($id) {
            $resource = $this->satuSehat->getPractitionerById($id);
            if (empty($resource)) {
                return response()->json(['error' => 'Practitioner tidak ditemukan', 'id' => $id], 404);
            }
            $info = $this->satuSehat->extractPractitionerInfo($resource);
            return response()->json([
                'ditemukan'     => true,
                'info_ringkas'  => $info,
                'fhir_resource' => $resource,
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $result = $this->satuSehat->getPractitioner($nik);
        $total  = $result['total'] ?? 0;

        if ($total === 0) {
            return response()->json([
                'ditemukan' => false,
                'pesan'     => "NIK $nik tidak ditemukan sebagai Practitioner di SatuSehat",
                'solusi'    => 'NIK dokter harus sudah terdaftar di SatuSehat (STR harus aktif)',
            ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        $resource = $result['entry'][0]['resource'];
        $info     = $this->satuSehat->extractPractitionerInfo($resource);

        // Auto-update satusehat_id di DB lokal jika NIK cocok
        $dokterLokal = \App\Models\Dokter::where('nik', $nik)->first();
        $updated     = false;
        if ($dokterLokal && !$dokterLokal->satusehat_id) {
            $dokterLokal->update(['satusehat_id' => $info['id']]);
            $updated = true;
        }

        return response()->json([
            'ditemukan'      => true,
            'total'          => $total,
            'info_ringkas'   => $info,
            'dokter_lokal'   => $dokterLokal ? ['id' => $dokterLokal->id, 'nama' => $dokterLokal->nama_lengkap, 'satusehat_id_updated' => $updated] : null,
            'fhir_resource'  => $resource,
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Cari kode wilayah BPS via SatuSehat — GET /admin/satusehat/cari-wilayah?nama=bondowoso&part=district
     */
    public function satusehatCariWilayah(\Illuminate\Http\Request $request)
    {
        $nama   = $request->get('nama', '');
        $part   = $request->get('part', 'district'); // province|city|district|village
        $parent = $request->get('parent', '');

        if (!$nama) {
            return response()->json([
                'petunjuk' => 'Gunakan query: ?nama=bondowoso&part=district&parent=3511',
                'contoh'   => [
                    'Cari provinsi'   => '?nama=jawa+timur&part=province',
                    'Cari kab/kota'   => '?nama=bondowoso&part=city&parent=35',
                    'Cari kecamatan'  => '?nama=bondowoso&part=district&parent=3511',
                    'Cari kelurahan'  => '?nama=kademangan&part=village&parent=351101',
                ],
            ]);
        }

        $result = $this->satuSehat->searchAdministrativeArea($nama, $part, $parent);
        return response()->json($result, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

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

        if (!$pasien) {
            return back()->withErrors(['error' => 'Data pasien tidak ditemukan.']);
        }

        // Pastikan pasien punya SatuSehat ID
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
            } else {
                return back()->withErrors(['error' => 'Gagal mendaftarkan pasien ke SatuSehat. Cek log untuk detail.']);
            }
        }

        $encounterData = [
            'patient_id'        => $pasien->satusehat_id,
            'nama_pasien'       => $pasien->nama_lengkap,
            'dokter_id'         => $dokter->satusehat_id ?? '',
            'nama_dokter'       => $dokter ? $dokter->nama_lengkap : '',
            'tanggal_kunjungan' => $pendaftaran->tanggal_kunjungan->format('Y-m-d'),
            'jam_kunjungan'     => $pendaftaran->jam_kunjungan,
            'keluhan'           => $pendaftaran->keluhan,
            'kode_booking'      => $pendaftaran->kode_booking,
            'nama_poli'         => $pendaftaran->poli->nama ?? 'Rawat Jalan',
        ];

        $response = $this->satuSehat->createEncounter($encounterData);

        if (!empty($response['success'])) {
            $pendaftaran->update([
                'satusehat_encounter_id' => $response['data']['id'] ?? null,
                'satusehat_response'     => $response['data'],
                'satusehat_status'       => 'success',
            ]);
            return back()->with('success', 'Sinkronisasi SatuSehat berhasil.');
        }

        return back()->withErrors([
            'error' => 'Sinkronisasi Encounter gagal: ' . ($response['error'] ?? 'Unknown error'),
        ]);
    }

    // ======== LAPORAN ========
    public function laporan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $data = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->orderBy('tanggal_kunjungan')
            ->get();

        $byPoli = $data->groupBy('poli_id')->map(function ($items) {
            return [
                'nama'    => $items->first()->poli->nama ?? '-',
                'total'   => $items->count(),
                'selesai' => $items->where('status', 'selesai')->count(),
                'baru'    => $items->where('jenis_kunjungan', 'baru')->count(),
                'kontrol' => $items->where('jenis_kunjungan', 'kontrol')->count(),
            ];
        })->sortByDesc('total');

        $byStatus = $data->groupBy('status')->map->count();

        // Chart harian dalam bulan ini
        $hariList  = [];
        $hariData  = [];
        $daysInMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $hariList[] = $d;
            $hariData[] = $data->filter(fn($p) => $p->tanggal_kunjungan->day === $d)->count();
        }

        $namabulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        return view('admin.laporan', compact(
            'data', 'byPoli', 'byStatus', 'bulan', 'tahun', 'namabulan',
            'hariList', 'hariData'
        ));
    }

    public function laporanExportExcel(Request $request)
    {
        $bulan     = $request->bulan ?? now()->month;
        $tahun     = $request->tahun ?? now()->year;
        $namabulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM_YYYY');

        $data = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->orderBy('tanggal_kunjungan')
            ->get();

        $filename = "Laporan_Kunjungan_{$namabulan}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($data, $bulan, $tahun) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel baca karakter Indonesia dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No', 'Kode Booking', 'Tanggal', 'Jam', 'No. RM',
                'Nama Pasien', 'NIK', 'Poli', 'Dokter',
                'Jenis Kunjungan', 'Keluhan', 'Status', 'SatuSehat Status',
                'Biaya (Rp)', 'Tekanan Darah', 'Suhu', 'Nadi', 'BB', 'TB',
            ]);

            foreach ($data as $i => $p) {
                fputcsv($handle, [
                    $i + 1,
                    $p->kode_booking,
                    $p->tanggal_kunjungan->format('d/m/Y'),
                    $p->jam_kunjungan,
                    $p->pasien->no_rm ?? '-',
                    $p->pasien->nama_lengkap ?? '-',
                    $p->pasien->nik ?? '-',
                    $p->poli->nama ?? '-',
                    $p->dokter->nama_lengkap ?? '-',
                    $p->jenis_kunjungan === 'baru' ? 'Baru' : 'Kontrol',
                    $p->keluhan,
                    ucfirst($p->status),
                    ucfirst($p->satusehat_status),
                    number_format($p->biaya_konsultasi, 0, ',', '.'),
                    $p->tekanan_darah ?? '-',
                    $p->suhu ?? '-',
                    $p->nadi ?? '-',
                    $p->berat_badan ?? '-',
                    $p->tinggi_badan ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** AJAX — data chart dashboard */
    public function dashboardChartData(Request $request): \Illuminate\Http\JsonResponse
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $daysInMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
        $namaBulan   = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        // Line chart — kunjungan harian dalam bulan yang dipilih
        $lineLabels  = [];
        $lineTotal   = [];
        $lineSelesai = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $lineLabels[]  = $d;
            $base = Pendaftaran::whereYear('tanggal_kunjungan', $tahun)
                               ->whereMonth('tanggal_kunjungan', $bulan)
                               ->whereDay('tanggal_kunjungan', $d);
            $lineTotal[]   = (clone $base)->count();
            $lineSelesai[] = (clone $base)->where('status', 'selesai')->count();
        }

        // Bar chart — kunjungan per poli dalam bulan yang dipilih
        $barLabels = [];
        $barData   = [];
        foreach (Poli::where('is_active', true)->get() as $poli) {
            $cnt = Pendaftaran::where('poli_id', $poli->id)
                ->whereYear('tanggal_kunjungan', $tahun)
                ->whereMonth('tanggal_kunjungan', $bulan)
                ->count();
            $barLabels[] = $poli->nama;
            $barData[]   = $cnt;
        }

        return response()->json([
            'namaBulan' => $namaBulan,
            'line' => ['labels' => $lineLabels, 'total' => $lineTotal, 'selesai' => $lineSelesai],
            'bar'  => ['labels' => $barLabels,  'data'  => $barData],
        ]);
    }

    public function laporanExportPdf(Request $request)
    {
        $bulan     = $request->bulan ?? now()->month;
        $tahun     = $request->tahun ?? now()->year;

        $data = Pendaftaran::with(['pasien', 'dokter', 'poli'])
            ->whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->orderBy('tanggal_kunjungan')
            ->get();

        $byPoli    = $data->groupBy('poli_id')->map(fn($items) => [
            'nama'    => $items->first()->poli->nama ?? '-',
            'total'   => $items->count(),
            'selesai' => $items->where('status', 'selesai')->count(),
        ])->sortByDesc('total');
        $byStatus  = $data->groupBy('status')->map->count();
        $namabulan = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        return view('admin.laporan-pdf', compact('data', 'byPoli', 'byStatus', 'bulan', 'tahun', 'namabulan'));
    }
}
