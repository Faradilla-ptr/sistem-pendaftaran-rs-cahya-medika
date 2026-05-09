<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\User;
use App\Services\SatuSehatService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasienDummySeeder extends Seeder
{
    public function run(): void
    {
        /** @var SatuSehatService $satuSehat */
        $satuSehat = app(SatuSehatService::class);

        $polis   = Poli::all()->keyBy('kode');
        $dokters = Dokter::where('is_active', true)->get()->groupBy('poli_id');

        $dataList = $this->getPasienData();
        $total    = count($dataList);

        $this->command->info("======================================================");
        $this->command->info(" RS Cahya Medika — Seeder 20 Pasien Dummy SatuSehat  ");
        $this->command->info("======================================================");
        $this->command->info("Base URL  : " . config('satusehat.base_url'));
        $this->command->info("Use dummy : " . (config('satusehat.use_dummy') ? 'true' : 'false'));
        $this->command->newLine();

        $berhasil = 0;

        foreach ($dataList as $idx => $data) {
            $no = $idx + 1;
            $this->command->info("[{$no}/{$total}] {$data['nama_lengkap']} — NIK: {$data['nik']}");

            // Cegah duplikat
            if (Pasien::where('nik', $data['nik'])->exists()) {
                $this->command->warn("  ⚠ NIK sudah ada di DB lokal, skip.");
                continue;
            }
            if (User::where('email', $data['email'])->exists()) {
                $this->command->warn("  ⚠ Email sudah ada di DB lokal, skip.");
                continue;
            }

            // 1. Buat akun login
            $user = User::create([
                'name'      => $data['nama_lengkap'],
                'email'     => $data['email'],
                'password'  => Hash::make('pasien123'),
                'role'      => 'pasien',
                'is_active' => true,
            ]);

            // 2. Buat profil pasien lokal
            $pasien = Pasien::create([
                'user_id'          => $user->id,
                'nik'              => $data['nik'],
                'nama_lengkap'     => $data['nama_lengkap'],
                'nama_panggilan'   => $data['nama_panggilan'],
                'tanggal_lahir'    => $data['tanggal_lahir'],
                'tempat_lahir'     => $data['tempat_lahir'],
                'jenis_kelamin'    => $data['jenis_kelamin'],
                'golongan_darah'   => $data['golongan_darah'],
                'agama'            => $data['agama'],
                'status_pernikahan'=> $data['status_pernikahan'],
                'pekerjaan'        => $data['pekerjaan'],
                'no_hp'            => $data['no_hp'],
                'email'            => $data['email'],
                'alamat'           => $data['alamat'],
                'kelurahan'        => $data['kelurahan'],
                'kode_kelurahan'   => $data['kode_kelurahan'],
                'kecamatan'        => $data['kecamatan'],
                'kode_kecamatan'   => $data['kode_kecamatan'],
                'kabupaten'        => $data['kabupaten'],
                'kode_kabupaten'   => $data['kode_kabupaten'],
                'provinsi'         => 'Jawa Timur',
                'kode_provinsi'    => '35',
                'kode_pos'         => $data['kode_pos'],
                'nama_pj'          => $data['nama_pj'],
                'hubungan_pj'      => $data['hubungan_pj'],
                'no_hp_pj'         => $data['no_hp_pj'],
                'status'           => 'aktif',
            ]);

            // 3. Daftarkan ke SatuSehat API (cari existing atau buat baru)
            $this->command->line("  → Menghubungi SatuSehat API...");
            $ssResult = $satuSehat->getOrCreatePatient([
                'nik'           => $data['nik'],
                'nama_lengkap'  => $data['nama_lengkap'],
                'no_hp'         => $data['no_hp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat'        => $data['alamat'],
                'kabupaten'     => 'Bondowoso',
                'kode_pos'      => $data['kode_pos'],
                // kode wilayah staging: fallback ke Surabaya (Bondowoso belum di DB staging)
                'kode_provinsi'  => '35',
                'kode_kabupaten' => '3578',
                'kode_kecamatan' => '357801',
                'kode_kelurahan' => '3578011001',
            ]);

            $ihsId = null;
            if (!empty($ssResult['success']) && !empty($ssResult['data']['id'])) {
                $ihsId = $ssResult['data']['id'];
                $pasien->update(['satusehat_id' => $ihsId]);
                $label = !empty($ssResult['found_existing']) ? 'EXISTING' : 'BARU DIBUAT';
                $this->command->info("  ✅ SatuSehat Patient ID: {$ihsId} ({$label})");
            } else {
                $errMsg = $ssResult['error'] ?? ($ssResult['details'] ?? 'unknown');
                $this->command->warn("  ⚠ SatuSehat gagal/dummy — " . substr($errMsg, 0, 80));
            }

            // 4. Buat riwayat pendaftaran
            $this->buatPendaftaran($pasien, $polis, $dokters, $data['kunjungan']);

            $berhasil++;
            usleep(300000); // 300ms antar pasien (rate limit)
        }

        $this->command->newLine();
        $this->command->info("======================================================");
        $this->command->info(" SELESAI: {$berhasil}/{$total} pasien berhasil dibuat");
        $this->command->info("======================================================");
        $this->command->newLine();
        $this->command->info("LOGIN PASIEN (semua password: pasien123):");
        foreach ($dataList as $data) {
            $this->command->line("  {$data['email']}");
        }
        $this->command->newLine();
        $this->command->info("Untuk verifikasi di Postman: lihat panduan di bawah atau");
        $this->command->info("jalankan: GET http://localhost:8000/admin/satusehat/cari-pasien?nik=<NIK>");
    }

    // -------------------------------------------------------------------------

    private function buatPendaftaran(Pasien $pasien, $polis, $dokters, array $kunjungan): void
    {
        foreach ($kunjungan as $k) {
            $poli = $polis->get($k['poli_kode']);
            if (!$poli) {
                continue;
            }

            $dokterList = $dokters->get($poli->id);
            if (!$dokterList || $dokterList->isEmpty()) {
                continue;
            }

            $dokter = $dokterList->random();

            Pendaftaran::create([
                'pasien_id'          => $pasien->id,
                'dokter_id'          => $dokter->id,
                'poli_id'            => $poli->id,
                'tanggal_kunjungan'  => $k['tanggal'],
                'jam_kunjungan'      => $k['jam'],
                'jenis_kunjungan'    => $k['jenis'],
                'keluhan'            => $k['keluhan'],
                'status'             => $k['status'],
                'biaya_konsultasi'   => $k['biaya'],
                'satusehat_status'   => $k['ss_status'],
                'catatan_admin'      => $k['catatan'] ?? null,
                'tekanan_darah'      => $k['td'] ?? null,
                'suhu'               => $k['suhu'] ?? null,
                'nadi'               => $k['nadi'] ?? null,
                'berat_badan'        => $k['bb'] ?? null,
                'tinggi_badan'       => $k['tb'] ?? null,
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // DATA MASTER 20 PASIEN
    // NIK Format: [kode_wilayah(6)][DDMMYY(6)][nomor_urut(4)]
    // Kode wilayah: 351101 = Kecamatan Bondowoso Kota, Kab Bondowoso, Jawa Timur
    // Wanita: DD + 40
    // -------------------------------------------------------------------------

    private function getPasienData(): array
    {
        $today   = Carbon::today()->format('Y-m-d');
        $kemarin = Carbon::yesterday()->format('Y-m-d');

        return [
            // ======================== 1 ========================
            [
                'nama_lengkap'      => 'Ahmad Rizki Maulana',
                'nama_panggilan'    => 'Rizki',
                'nik'               => '3511011501850001',  // L, 15-01-1985
                'tanggal_lahir'     => '1985-01-15',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'PNS',
                'no_hp'             => '081234500001',
                'email'             => 'ahmad.rizki.maulana@gmail.com',
                'alamat'            => 'Jl. Mastrip No. 12, RT 02 RW 01',
                'kelurahan'         => 'Kademangan',
                'kode_kelurahan'    => '3511010010',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Siti Maulana',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234500101',
                'kunjungan'         => [
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-01-08', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Kontrol gula darah, riwayat DM tipe 2', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Glukosa terkontrol', 'td' => '130/85', 'suhu' => '36.5', 'nadi' => '78', 'bb' => '68', 'tb' => '168'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-03-12', 'jam' => '09:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol rutin DM tipe 2, ada keluhan kesemutan kaki', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'Diberikan vitamin B12', 'td' => '125/80', 'suhu' => '36.7', 'nadi' => '76', 'bb' => '67', 'tb' => '168'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-07-15', 'jam' => '08:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol HbA1c dan tekanan darah', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '128/82', 'suhu' => '36.6', 'nadi' => '80', 'bb' => '67', 'tb' => '168'],
                ],
            ],

            // ======================== 2 ========================
            [
                'nama_lengkap'      => 'Siti Rahayu',
                'nama_panggilan'    => 'Rahayu',
                'nik'               => '3511016006900001',  // P, 20-06-1990 → 60
                'tanggal_lahir'     => '1990-06-20',
                'tempat_lahir'      => 'Situbondo',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Guru',
                'no_hp'             => '081234500002',
                'email'             => 'siti.rahayu.bwi@gmail.com',
                'alamat'            => 'Jl. Veteran No. 45, RT 01 RW 03',
                'kelurahan'         => 'Badean',
                'kode_kelurahan'    => '3511010011',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Budi Santoso',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234500201',
                'kunjungan'         => [
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => '2025-01-15', 'jam' => '10:00', 'jenis' => 'baru',    'keluhan' => 'Kontrol kehamilan trimester 1, mual muntah', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'catatan' => 'USG normal, janin berkembang baik', 'td' => '110/70', 'suhu' => '36.8', 'nadi' => '84', 'bb' => '56', 'tb' => '158'],
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => '2025-03-20', 'jam' => '10:00', 'jenis' => 'kontrol', 'keluhan' => 'USG trimester 2, kontrol rutin kehamilan', 'status' => 'selesai', 'biaya' => 250000, 'ss_status' => 'success', 'td' => '115/72', 'suhu' => '36.7', 'nadi' => '82', 'bb' => '60', 'tb' => '158'],
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => $today,      'jam' => '09:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol trimester 3, persiapan persalinan', 'status' => 'menunggu', 'biaya' => 0, 'ss_status' => 'pending'],
                ],
            ],

            // ======================== 3 ========================
            [
                'nama_lengkap'      => 'Budi Setiawan',
                'nama_panggilan'    => 'Budi',
                'nik'               => '3511012503780001',  // L, 25-03-1978
                'tanggal_lahir'     => '1978-03-25',
                'tempat_lahir'      => 'Jember',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Petani',
                'no_hp'             => '081234500003',
                'email'             => 'budi.setiawan.bwi@gmail.com',
                'alamat'            => 'Jl. Diponegoro No. 8, RT 03 RW 02',
                'kelurahan'         => 'Bondowoso',
                'kode_kelurahan'    => '3511010001',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Maryam Setiawan',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234500301',
                'kunjungan'         => [
                    ['poli_kode' => 'BEDAH',  'tanggal' => '2025-01-20', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Benjolan di perut kiri bawah sejak 2 bulan, terasa nyeri', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'catatan' => 'Didiagnosa hernia inguinal, disarankan operasi', 'td' => '140/90', 'suhu' => '36.5', 'nadi' => '82', 'bb' => '72', 'tb' => '165'],
                    ['poli_kode' => 'BEDAH',  'tanggal' => '2025-02-10', 'jam' => '08:00', 'jenis' => 'kontrol', 'keluhan' => 'Konsultasi pre-operasi hernia', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'td' => '138/88', 'suhu' => '36.4', 'nadi' => '80', 'bb' => '72', 'tb' => '165'],
                    ['poli_kode' => 'BEDAH',  'tanggal' => '2025-04-05', 'jam' => '08:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol post operasi hernia inguinal', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '130/85', 'suhu' => '36.6', 'nadi' => '78', 'bb' => '71', 'tb' => '165'],
                ],
            ],

            // ======================== 4 ========================
            [
                'nama_lengkap'      => 'Dewi Anjani',
                'nama_panggilan'    => 'Dewi',
                'nik'               => '3511014811920001',  // P, 08-11-1992 → 48
                'tanggal_lahir'     => '1992-11-08',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Belum Menikah',
                'pekerjaan'         => 'Karyawan Swasta',
                'no_hp'             => '081234500004',
                'email'             => 'dewi.anjani.bwi@gmail.com',
                'alamat'            => 'Jl. S. Parman No. 22, RT 04 RW 01',
                'kelurahan'         => 'Tamansari',
                'kode_kelurahan'    => '3511010012',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68212',
                'nama_pj'           => 'Hendra Anjani',
                'hubungan_pj'       => 'Ayah',
                'no_hp_pj'          => '081234500401',
                'kunjungan'         => [
                    ['poli_kode' => 'MATA',  'tanggal' => '2025-02-03', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Pandangan kabur saat baca jarak dekat, mata sering perih', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Miopi sedang, diresepkan kacamata -1.75', 'td' => '110/70', 'suhu' => '36.5', 'nadi' => '76', 'bb' => '52', 'tb' => '160'],
                    ['poli_kode' => 'MATA',  'tanggal' => '2025-05-10', 'jam' => '09:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol miopi, cek lensa kontak', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '112/70', 'suhu' => '36.4', 'nadi' => '74', 'bb' => '52', 'tb' => '160'],
                ],
            ],

            // ======================== 5 ========================
            [
                'nama_lengkap'      => 'Hendra Gunawan',
                'nama_panggilan'    => 'Hendra',
                'nik'               => '3511011207820001',  // L, 12-07-1982
                'tanggal_lahir'     => '1982-07-12',
                'tempat_lahir'      => 'Probolinggo',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Wiraswasta',
                'no_hp'             => '081234500005',
                'email'             => 'hendra.gunawan.bwi@gmail.com',
                'alamat'            => 'Jl. Pemuda No. 33, RT 01 RW 04',
                'kelurahan'         => 'Dabasah',
                'kode_kelurahan'    => '3511010002',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Rina Gunawan',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234500501',
                'kunjungan'         => [
                    ['poli_kode' => 'JANTUNG', 'tanggal' => '2025-02-14', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri dada kiri saat aktivitas berat, sesak napas', 'status' => 'selesai', 'biaya' => 250000, 'ss_status' => 'success', 'catatan' => 'EKG dalam batas normal, disarankan echo jantung', 'td' => '150/95', 'suhu' => '36.6', 'nadi' => '88', 'bb' => '78', 'tb' => '170'],
                    ['poli_kode' => 'JANTUNG', 'tanggal' => '2025-04-18', 'jam' => '08:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol EKG dan echo jantung', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'td' => '145/90', 'suhu' => '36.7', 'nadi' => '85', 'bb' => '77', 'tb' => '170'],
                    ['poli_kode' => 'JANTUNG', 'tanggal' => '2025-09-20', 'jam' => '08:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol rutin jantung koroner', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'td' => '140/88', 'suhu' => '36.5', 'nadi' => '82', 'bb' => '76', 'tb' => '170'],
                ],
            ],

            // ======================== 6 ========================
            [
                'nama_lengkap'      => 'Fitriani Wulandari',
                'nama_panggilan'    => 'Fitri',
                'nik'               => '3511014304950001',  // P, 03-04-1995 → 43
                'tanggal_lahir'     => '1995-04-03',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'AB',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Bidan',
                'no_hp'             => '081234500006',
                'email'             => 'fitriani.wulandari@gmail.com',
                'alamat'            => 'Perum Graha Indah Blok B No. 5',
                'kelurahan'         => 'Nangkaan',
                'kode_kelurahan'    => '3511010003',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68213',
                'nama_pj'           => 'Eko Wulandari',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234500601',
                'kunjungan'         => [
                    ['poli_kode' => 'UMUM',  'tanggal' => '2025-02-20', 'jam' => '08:30', 'jenis' => 'baru',    'keluhan' => 'Demam tinggi 3 hari, batuk berdahak, nyeri kepala', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'ISPA, diberikan antibiotik', 'td' => '110/70', 'suhu' => '38.5', 'nadi' => '90', 'bb' => '55', 'tb' => '162'],
                    ['poli_kode' => 'UMUM',  'tanggal' => '2025-06-10', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri perut bawah, mual, diare 2 hari', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '108/68', 'suhu' => '37.8', 'nadi' => '88', 'bb' => '54', 'tb' => '162'],
                    ['poli_kode' => 'UMUM',  'tanggal' => $today,      'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Batuk pilek sudah 4 hari, sakit tenggorokan', 'status' => 'menunggu', 'biaya' => 0, 'ss_status' => 'pending'],
                ],
            ],

            // ======================== 7 ========================
            [
                'nama_lengkap'      => 'Agus Prasetyo',
                'nama_panggilan'    => 'Agus',
                'nik'               => '3511013009750001',  // L, 30-09-1975
                'tanggal_lahir'     => '1975-09-30',
                'tempat_lahir'      => 'Lumajang',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Pedagang',
                'no_hp'             => '081234500007',
                'email'             => 'agus.prasetyo.bwi@gmail.com',
                'alamat'            => 'Jl. Gajah Mada No. 17, RT 02 RW 05',
                'kelurahan'         => 'Kademangan',
                'kode_kelurahan'    => '3511010010',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Sunarti Prasetyo',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234500701',
                'kunjungan'         => [
                    ['poli_kode' => 'SARAF', 'tanggal' => '2025-03-05', 'jam' => '09:30', 'jenis' => 'baru',    'keluhan' => 'Pusing berputar, telinga berdenging, vertigo sejak seminggu', 'status' => 'selesai', 'biaya' => 175000, 'ss_status' => 'success', 'catatan' => 'BPPV, fisioterapi Epley maneuver', 'td' => '135/85', 'suhu' => '36.5', 'nadi' => '80', 'bb' => '70', 'tb' => '167'],
                    ['poli_kode' => 'SARAF', 'tanggal' => '2025-05-20', 'jam' => '10:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol vertigo, masih ada keluhan pusing ringan', 'status' => 'selesai', 'biaya' => 125000, 'ss_status' => 'success', 'td' => '132/84', 'suhu' => '36.4', 'nadi' => '78', 'bb' => '70', 'tb' => '167'],
                    ['poli_kode' => 'SARAF', 'tanggal' => '2025-11-08', 'jam' => '09:00', 'jenis' => 'kontrol', 'keluhan' => 'Migrain berulang, nyeri kepala sebelah kanan', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'td' => '130/82', 'suhu' => '36.6', 'nadi' => '76', 'bb' => '69', 'tb' => '167'],
                ],
            ],

            // ======================== 8 ========================
            [
                'nama_lengkap'      => 'Nurul Hidayah',
                'nama_panggilan'    => 'Nurul',
                'nik'               => '3511015512880001',  // P, 15-12-1988 → 55
                'tanggal_lahir'     => '1988-12-15',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Ibu Rumah Tangga',
                'no_hp'             => '081234500008',
                'email'             => 'nurul.hidayah.bwi@gmail.com',
                'alamat'            => 'Jl. Teuku Umar No. 9, RT 05 RW 02',
                'kelurahan'         => 'Badean',
                'kode_kelurahan'    => '3511010011',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Sumarno Hidayah',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234500801',
                'kunjungan'         => [
                    ['poli_kode' => 'DALAM', 'tanggal' => '2025-03-10', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Tekanan darah tinggi sejak 2 minggu, pusing, leher kaku', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Hipertensi grade 1, diresepkan amlodipine', 'td' => '160/100', 'suhu' => '36.7', 'nadi' => '90', 'bb' => '65', 'tb' => '155'],
                    ['poli_kode' => 'DALAM', 'tanggal' => '2025-05-15', 'jam' => '08:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol hipertensi, evaluasi obat', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '145/92', 'suhu' => '36.5', 'nadi' => '85', 'bb' => '64', 'tb' => '155'],
                    ['poli_kode' => 'DALAM', 'tanggal' => '2025-10-20', 'jam' => '09:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol rutin hipertensi', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '138/88', 'suhu' => '36.4', 'nadi' => '82', 'bb' => '63', 'tb' => '155'],
                ],
            ],

            // ======================== 9 ========================
            [
                'nama_lengkap'      => 'Rizal Fahmi',
                'nama_panggilan'    => 'Rizal',
                'nik'               => '3511011802930001',  // L, 18-02-1993
                'tanggal_lahir'     => '1993-02-18',
                'tempat_lahir'      => 'Banyuwangi',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Belum Menikah',
                'pekerjaan'         => 'Mahasiswa',
                'no_hp'             => '081234500009',
                'email'             => 'rizal.fahmi.bwi@gmail.com',
                'alamat'            => 'Jl. Raya Tamanan No. 5, RT 01 RW 01',
                'kelurahan'         => 'Tamanan',
                'kode_kelurahan'    => '3511020001',
                'kecamatan'         => 'Tamanan',
                'kode_kecamatan'    => '351102',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68262',
                'nama_pj'           => 'Fahmi Senior',
                'hubungan_pj'       => 'Ayah',
                'no_hp_pj'          => '081234500901',
                'kunjungan'         => [
                    ['poli_kode' => 'UMUM', 'tanggal' => '2025-03-22', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Demam, sakit kepala, nyeri sendi, dugaan flu', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'Influenza, bed rest dan antipiretik', 'td' => '115/75', 'suhu' => '38.9', 'nadi' => '96', 'bb' => '65', 'tb' => '172'],
                    ['poli_kode' => 'BEDAH', 'tanggal' => '2025-07-08', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri perut kanan bawah mendadak, demam ringan', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'catatan' => 'Appendisitis akut, dirujuk operasi', 'td' => '118/76', 'suhu' => '37.9', 'nadi' => '92', 'bb' => '64', 'tb' => '172'],
                ],
            ],

            // ======================== 10 ========================
            [
                'nama_lengkap'      => 'Ratna Sari',
                'nama_panggilan'    => 'Ratna',
                'nik'               => '3511016205800001',  // P, 22-05-1980 → 62
                'tanggal_lahir'     => '1980-05-22',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Perawat',
                'no_hp'             => '081234500010',
                'email'             => 'ratna.sari.bwi@gmail.com',
                'alamat'            => 'Jl. Kapuas No. 14, RT 03 RW 04',
                'kelurahan'         => 'Nangkaan',
                'kode_kelurahan'    => '3511010003',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68213',
                'nama_pj'           => 'Darmawan Sari',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234501001',
                'kunjungan'         => [
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-04-01', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Kolesterol tinggi, kelelahan, rambut rontok', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Dislipidemia, diet rendah lemak + statin', 'td' => '125/80', 'suhu' => '36.6', 'nadi' => '80', 'bb' => '62', 'tb' => '157'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-08-12', 'jam' => '09:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol kolesterol post terapi 4 bulan', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '122/78', 'suhu' => '36.5', 'nadi' => '78', 'bb' => '61', 'tb' => '157'],
                ],
            ],

            // ======================== 11 ========================
            [
                'nama_lengkap'      => 'Dony Kurniawan',
                'nama_panggilan'    => 'Dony',
                'nik'               => '3511010708870001',  // L, 07-08-1987
                'tanggal_lahir'     => '1987-08-07',
                'tempat_lahir'      => 'Jember',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Teknisi',
                'no_hp'             => '081234500011',
                'email'             => 'dony.kurniawan.bwi@gmail.com',
                'alamat'            => 'Jl. Raya Ijen No. 21, RT 01 RW 02',
                'kelurahan'         => 'Dabasah',
                'kode_kelurahan'    => '3511010002',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Yuni Kurniawan',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234501101',
                'kunjungan'         => [
                    ['poli_kode' => 'UMUM',   'tanggal' => '2025-04-10', 'jam' => '08:30', 'jenis' => 'baru',    'keluhan' => 'Gatal-gatal seluruh badan sejak 3 hari, alergi makanan', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'Urtikaria akut, antihistamin', 'td' => '120/80', 'suhu' => '36.5', 'nadi' => '78', 'bb' => '73', 'tb' => '169'],
                    ['poli_kode' => 'BEDAH',   'tanggal' => '2025-09-03', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Jari tangan kanan terluka akibat kecelakaan kerja, perlu jahit', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'td' => '122/82', 'suhu' => '36.7', 'nadi' => '82', 'bb' => '73', 'tb' => '169'],
                ],
            ],

            // ======================== 12 ========================
            [
                'nama_lengkap'      => 'Lestari Ningrum',
                'nama_panggilan'    => 'Lestari',
                'nik'               => '3511015110980001',  // P, 11-10-1998 → 51
                'tanggal_lahir'     => '1998-10-11',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Belum Menikah',
                'pekerjaan'         => 'Pelajar',
                'no_hp'             => '081234500012',
                'email'             => 'lestari.ningrum.bwi@gmail.com',
                'alamat'            => 'Jl. Sukarno Hatta No. 30, RT 06 RW 01',
                'kelurahan'         => 'Tamansari',
                'kode_kelurahan'    => '3511010012',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68212',
                'nama_pj'           => 'Ningrum Senior',
                'hubungan_pj'       => 'Ibu',
                'no_hp_pj'          => '081234501201',
                'kunjungan'         => [
                    ['poli_kode' => 'ANAK',  'tanggal' => '2025-04-22', 'jam' => '09:30', 'jenis' => 'baru',    'keluhan' => 'Demam 3 hari, batuk pilek, nafsu makan turun', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'ISPA ringan, obat simptomatik', 'td' => '100/65', 'suhu' => '38.2', 'nadi' => '95', 'bb' => '48', 'tb' => '155'],
                    ['poli_kode' => 'UMUM',  'tanggal' => '2025-12-15', 'jam' => '10:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri kepala berulang, sulit konsentrasi belajar', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '102/66', 'suhu' => '36.5', 'nadi' => '80', 'bb' => '49', 'tb' => '155'],
                ],
            ],

            // ======================== 13 ========================
            [
                'nama_lengkap'      => 'Wahyu Santoso',
                'nama_panggilan'    => 'Wahyu',
                'nik'               => '3511011911730001',  // L, 19-11-1973
                'tanggal_lahir'     => '1973-11-19',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'TNI',
                'no_hp'             => '081234500013',
                'email'             => 'wahyu.santoso.bwi@gmail.com',
                'alamat'            => 'Komplek Koramil Blok C No. 3',
                'kelurahan'         => 'Blindungan',
                'kode_kelurahan'    => '3511010004',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Endang Santoso',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234501301',
                'kunjungan'         => [
                    ['poli_kode' => 'DALAM',   'tanggal' => '2025-05-05', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri punggung bawah kronis, asam urat tinggi', 'status' => 'selesai', 'biaya' => 175000, 'ss_status' => 'success', 'catatan' => 'Hiperurisemia, diet rendah purin', 'td' => '145/88', 'suhu' => '36.4', 'nadi' => '82', 'bb' => '80', 'tb' => '172'],
                    ['poli_kode' => 'SARAF',   'tanggal' => '2025-08-18', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri pinggang menjalar ke kaki kiri (lumbago)', 'status' => 'selesai', 'biaya' => 175000, 'ss_status' => 'success', 'td' => '142/86', 'suhu' => '36.5', 'nadi' => '80', 'bb' => '79', 'tb' => '172'],
                ],
            ],

            // ======================== 14 ========================
            [
                'nama_lengkap'      => 'Anisa Putri',
                'nama_panggilan'    => 'Anisa',
                'nik'               => '3511014407960001',  // P, 04-07-1996 → 44
                'tanggal_lahir'     => '1996-07-04',
                'tempat_lahir'      => 'Malang',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Apoteker',
                'no_hp'             => '081234500014',
                'email'             => 'anisa.putri.bwi@gmail.com',
                'alamat'            => 'Jl. Mangga No. 7, Perum Griya Asri',
                'kelurahan'         => 'Nangkaan',
                'kode_kelurahan'    => '3511010003',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68213',
                'nama_pj'           => 'Haris Putri',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234501401',
                'kunjungan'         => [
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => '2025-05-12', 'jam' => '10:00', 'jenis' => 'baru',    'keluhan' => 'Konsultasi program hamil, siklus haid tidak teratur', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'catatan' => 'PCOS, diet dan olahraga rutin', 'td' => '112/72', 'suhu' => '36.6', 'nadi' => '78', 'bb' => '58', 'tb' => '163'],
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => '2025-09-22', 'jam' => '10:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol PCOS, evaluasi terapi hormonal', 'status' => 'selesai', 'biaya' => 200000, 'ss_status' => 'success', 'td' => '114/74', 'suhu' => '36.5', 'nadi' => '76', 'bb' => '57', 'tb' => '163'],
                    ['poli_kode' => 'KANDUNGAN', 'tanggal' => $today,      'jam' => '10:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol program kehamilan', 'status' => 'menunggu', 'biaya' => 0, 'ss_status' => 'pending'],
                ],
            ],

            // ======================== 15 ========================
            [
                'nama_lengkap'      => 'Eko Prasetya',
                'nama_panggilan'    => 'Eko',
                'nik'               => '3511012804690001',  // L, 28-04-1969
                'tanggal_lahir'     => '1969-04-28',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Pensiunan',
                'no_hp'             => '081234500015',
                'email'             => 'eko.prasetya.bwi@gmail.com',
                'alamat'            => 'Jl. Trunojoyo No. 5, RT 01 RW 06',
                'kelurahan'         => 'Blindungan',
                'kode_kelurahan'    => '3511010004',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Suminah Prasetya',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234501501',
                'kunjungan'         => [
                    ['poli_kode' => 'JANTUNG', 'tanggal' => '2025-05-19', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Sesak napas saat aktivitas ringan, mudah lelah, riwayat PJK', 'status' => 'selesai', 'biaya' => 275000, 'ss_status' => 'success', 'catatan' => 'PJK stabil, terapi ACE inhibitor diteruskan', 'td' => '155/95', 'suhu' => '36.5', 'nadi' => '88', 'bb' => '75', 'tb' => '166'],
                    ['poli_kode' => 'JANTUNG', 'tanggal' => '2025-07-28', 'jam' => '08:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol rutin PJK, treadmill test', 'status' => 'selesai', 'biaya' => 250000, 'ss_status' => 'success', 'td' => '150/92', 'suhu' => '36.6', 'nadi' => '85', 'bb' => '74', 'tb' => '166'],
                    ['poli_kode' => 'DALAM',   'tanggal' => '2025-11-18', 'jam' => '09:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol DM + hipertensi, keduanya sudah lama diderita', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'td' => '148/90', 'suhu' => '36.4', 'nadi' => '84', 'bb' => '74', 'tb' => '166'],
                ],
            ],

            // ======================== 16 ========================
            [
                'nama_lengkap'      => 'Yuliana Sari',
                'nama_panggilan'    => 'Yuli',
                'nik'               => '3511015609010001',  // P, 16-09-2001 → 56
                'tanggal_lahir'     => '2001-09-16',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'AB',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Belum Menikah',
                'pekerjaan'         => 'Mahasiswa',
                'no_hp'             => '081234500016',
                'email'             => 'yuliana.sari.bwi@gmail.com',
                'alamat'            => 'Jl. Ahmad Yani No. 44, RT 02 RW 03',
                'kelurahan'         => 'Kademangan',
                'kode_kelurahan'    => '3511010010',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Sarwono Sari',
                'hubungan_pj'       => 'Ayah',
                'no_hp_pj'          => '081234501601',
                'kunjungan'         => [
                    ['poli_kode' => 'UMUM', 'tanggal' => '2025-06-05', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri haid (dismenore) hebat, mual, lemas saat menstruasi', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'catatan' => 'Dismenore primer, diberikan NSAIDs', 'td' => '105/68', 'suhu' => '36.7', 'nadi' => '86', 'bb' => '50', 'tb' => '158'],
                    ['poli_kode' => 'UMUM', 'tanggal' => '2026-01-14', 'jam' => '08:30', 'jenis' => 'baru',    'keluhan' => 'Demam 2 hari pasca UTS, badan pegal, diare', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '108/70', 'suhu' => '38.1', 'nadi' => '92', 'bb' => '50', 'tb' => '158'],
                ],
            ],

            // ======================== 17 ========================
            [
                'nama_lengkap'      => 'Faisal Rahman',
                'nama_panggilan'    => 'Faisal',
                'nik'               => '3511010312910001',  // L, 03-12-1991
                'tanggal_lahir'     => '1991-12-03',
                'tempat_lahir'      => 'Situbondo',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Polri',
                'no_hp'             => '081234500017',
                'email'             => 'faisal.rahman.bwi@gmail.com',
                'alamat'            => 'Jl. Cipto Mangunkusumo No. 10',
                'kelurahan'         => 'Tamansari',
                'kode_kelurahan'    => '3511010012',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68212',
                'nama_pj'           => 'Laila Rahman',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234501701',
                'kunjungan'         => [
                    ['poli_kode' => 'BEDAH',  'tanggal' => '2025-06-16', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Bisul besar di punggung, nyeri dan bengkak', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Insisi abses + packing, antibiotik sistemik', 'td' => '125/80', 'suhu' => '37.2', 'nadi' => '84', 'bb' => '76', 'tb' => '174'],
                    ['poli_kode' => 'UMUM',   'tanggal' => '2025-10-07', 'jam' => '08:30', 'jenis' => 'baru',    'keluhan' => 'Batuk rejan lebih dari 2 minggu, sesak napas malam', 'status' => 'selesai', 'biaya' => 100000, 'ss_status' => 'success', 'td' => '122/78', 'suhu' => '36.8', 'nadi' => '82', 'bb' => '75', 'tb' => '174'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2026-02-20', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'MCU rutin tahunan PNS, cek darah lengkap', 'status' => 'selesai', 'biaya' => 350000, 'ss_status' => 'success', 'td' => '120/76', 'suhu' => '36.5', 'nadi' => '78', 'bb' => '75', 'tb' => '174'],
                ],
            ],

            // ======================== 18 ========================
            [
                'nama_lengkap'      => 'Marlina Susanti',
                'nama_panggilan'    => 'Marlina',
                'nik'               => '3511016903840001',  // P, 29-03-1984 → 69
                'tanggal_lahir'     => '1984-03-29',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'A',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Dokter Gigi',
                'no_hp'             => '081234500018',
                'email'             => 'marlina.susanti.bwi@gmail.com',
                'alamat'            => 'Jl. Kenangan No. 19, RT 04 RW 02',
                'kelurahan'         => 'Badean',
                'kode_kelurahan'    => '3511010011',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Anton Susanti',
                'hubungan_pj'       => 'Suami',
                'no_hp_pj'          => '081234501801',
                'kunjungan'         => [
                    ['poli_kode' => 'MATA',    'tanggal' => '2025-07-01', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Mata merah, berair, gatal dan belekan sejak 5 hari', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Konjungtivitis bakterial, tetes mata antibiotik', 'td' => '118/74', 'suhu' => '36.6', 'nadi' => '76', 'bb' => '57', 'tb' => '160'],
                    ['poli_kode' => 'SARAF',   'tanggal' => '2025-11-25', 'jam' => '10:00', 'jenis' => 'baru',    'keluhan' => 'Kebas dan kesemutan tangan kanan saat kerja (Carpal Tunnel)', 'status' => 'selesai', 'biaya' => 175000, 'ss_status' => 'success', 'td' => '120/76', 'suhu' => '36.5', 'nadi' => '74', 'bb' => '56', 'tb' => '160'],
                ],
            ],

            // ======================== 19 ========================
            [
                'nama_lengkap'      => 'Teguh Pramono',
                'nama_panggilan'    => 'Teguh',
                'nik'               => '3511011106770001',  // L, 11-06-1977
                'tanggal_lahir'     => '1977-06-11',
                'tempat_lahir'      => 'Bondowoso',
                'jenis_kelamin'     => 'L',
                'golongan_darah'    => 'B',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Menikah',
                'pekerjaan'         => 'Kontraktor',
                'no_hp'             => '081234500019',
                'email'             => 'teguh.pramono.bwi@gmail.com',
                'alamat'            => 'Jl. Letjen Suprapto No. 25, RT 03 RW 01',
                'kelurahan'         => 'Bondowoso',
                'kode_kelurahan'    => '3511010001',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68211',
                'nama_pj'           => 'Sri Pramono',
                'hubungan_pj'       => 'Istri',
                'no_hp_pj'          => '081234501901',
                'kunjungan'         => [
                    ['poli_kode' => 'BEDAH',  'tanggal' => '2025-08-04', 'jam' => '08:30', 'jenis' => 'baru',    'keluhan' => 'Luka sayat dalam di tangan akibat kerja, perlu dijahit', 'status' => 'selesai', 'biaya' => 175000, 'ss_status' => 'success', 'catatan' => 'Hechting 5 jahitan, antibiotik oral', 'td' => '135/85', 'suhu' => '36.8', 'nadi' => '84', 'bb' => '80', 'tb' => '170'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2025-12-08', 'jam' => '09:00', 'jenis' => 'baru',    'keluhan' => 'Nyeri ulu hati, kembung, mual setelah makan pedas (gastritis)', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'td' => '132/84', 'suhu' => '36.7', 'nadi' => '80', 'bb' => '80', 'tb' => '170'],
                    ['poli_kode' => 'DALAM',  'tanggal' => $kemarin,    'jam' => '09:30', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol gastritis, evaluasi terapi PPI', 'status' => 'selesai', 'biaya' => 125000, 'ss_status' => 'success', 'td' => '130/82', 'suhu' => '36.5', 'nadi' => '78', 'bb' => '79', 'tb' => '170'],
                ],
            ],

            // ======================== 20 ========================
            [
                'nama_lengkap'      => 'Indah Permata',
                'nama_panggilan'    => 'Indah',
                'nik'               => '3511016408990001',  // P, 24-08-1999 → 64
                'tanggal_lahir'     => '1999-08-24',
                'tempat_lahir'      => 'Surabaya',
                'jenis_kelamin'     => 'P',
                'golongan_darah'    => 'O',
                'agama'             => 'Islam',
                'status_pernikahan' => 'Belum Menikah',
                'pekerjaan'         => 'Dokter Muda',
                'no_hp'             => '081234500020',
                'email'             => 'indah.permata.bwi@gmail.com',
                'alamat'            => 'Jl. Pantai Blambangan No. 3, RT 01 RW 07',
                'kelurahan'         => 'Nangkaan',
                'kode_kelurahan'    => '3511010003',
                'kecamatan'         => 'Bondowoso',
                'kode_kecamatan'    => '351101',
                'kabupaten'         => 'Bondowoso',
                'kode_kabupaten'    => '3511',
                'kode_pos'          => '68213',
                'nama_pj'           => 'Budiman Permata',
                'hubungan_pj'       => 'Ayah',
                'no_hp_pj'          => '081234502001',
                'kunjungan'         => [
                    ['poli_kode' => 'UMUM',   'tanggal' => '2025-09-10', 'jam' => '08:00', 'jenis' => 'baru',    'keluhan' => 'Anemia, mudah lelah, pusing, pucat', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'catatan' => 'Anemia defisiensi besi, suplemen Fe', 'td' => '100/65', 'suhu' => '36.6', 'nadi' => '88', 'bb' => '51', 'tb' => '161'],
                    ['poli_kode' => 'DALAM',  'tanggal' => '2026-03-18', 'jam' => '09:00', 'jenis' => 'kontrol', 'keluhan' => 'Kontrol anemia post terapi 6 bulan, cek Hb ulang', 'status' => 'selesai', 'biaya' => 150000, 'ss_status' => 'success', 'td' => '108/70', 'suhu' => '36.5', 'nadi' => '80', 'bb' => '52', 'tb' => '161'],
                    ['poli_kode' => 'UMUM',   'tanggal' => $today,      'jam' => '10:00', 'jenis' => 'kontrol', 'keluhan' => 'Cek hasil lab darah lengkap terbaru', 'status' => 'menunggu', 'biaya' => 0, 'ss_status' => 'pending'],
                ],
            ],
        ];
    }
}
