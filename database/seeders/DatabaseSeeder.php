<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ====== ADMIN ======
        User::firstOrCreate(['email' => 'admin@rscahyamedika.co.id'], [
            'name' => 'Admin RS Cahya Medika',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ====== STAF PENDAFTARAN ======
        User::firstOrCreate(['email' => 'pendaftaran@rscahyamedika.co.id'], [
            'name' => 'Staf Loket Pendaftaran',
            'password' => Hash::make('admin123'),
            'role' => 'pendaftaran',
            'is_active' => true,
        ]);

        // ====== STAF REKAM MEDIS ======
        User::firstOrCreate(['email' => 'rekammedis@rscahyamedika.co.id'], [
            'name' => 'Staf Rekam Medis',
            'password' => Hash::make('admin123'),
            'role' => 'rekam_medis',
            'is_active' => true,
        ]);

        // ====== POLI ======
        $polis = [
            ['kode' => 'UMUM', 'nama' => 'Poli Umum', 'deskripsi' => 'Pelayanan kesehatan umum', 'lantai' => 'Lantai 1', 'icon' => '🏥', 'warna' => '#0891b2'],
            ['kode' => 'ANAK', 'nama' => 'Poli Anak', 'deskripsi' => 'Pelayanan kesehatan anak', 'lantai' => 'Lantai 1', 'icon' => '👶', 'warna' => '#059669'],
            ['kode' => 'DALAM', 'nama' => 'Poli Penyakit Dalam', 'deskripsi' => 'Pelayanan penyakit dalam & internist', 'lantai' => 'Lantai 2', 'icon' => '🫀', 'warna' => '#dc2626'],
            ['kode' => 'KANDUNGAN', 'nama' => 'Poli Kebidanan & Kandungan', 'deskripsi' => 'Pelayanan obstetri & ginekologi', 'lantai' => 'Lantai 2', 'icon' => '🤱', 'warna' => '#db2777'],
            ['kode' => 'BEDAH', 'nama' => 'Poli Bedah', 'deskripsi' => 'Pelayanan bedah umum', 'lantai' => 'Lantai 2', 'icon' => '🔬', 'warna' => '#7c3aed'],
            ['kode' => 'JANTUNG', 'nama' => 'Poli Jantung', 'deskripsi' => 'Pelayanan kardiologi', 'lantai' => 'Lantai 3', 'icon' => '❤️', 'warna' => '#e11d48'],
            ['kode' => 'SARAF', 'nama' => 'Poli Saraf', 'deskripsi' => 'Pelayanan neurologi', 'lantai' => 'Lantai 3', 'icon' => '🧠', 'warna' => '#9333ea'],
            ['kode' => 'MATA', 'nama' => 'Poli Mata', 'deskripsi' => 'Pelayanan oftalmologi', 'lantai' => 'Lantai 1', 'icon' => '👁️', 'warna' => '#0284c7'],
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(['kode' => $poli['kode']], array_merge($poli, ['jam_buka' => '07:30', 'jam_tutup' => '14:00', 'is_active' => true]));
        }

        // ====== DOKTER (Maksimal 3 per Poli) ======
        $poliMap = Poli::pluck('id', 'kode')->toArray();

        $jadwalFull = [
            'senin' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'selasa' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'rabu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'kamis' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'jumat' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
            'sabtu' => ['aktif' => false],
            'minggu' => ['aktif' => false],
        ];

        $jadwalShiftA = [
            'senin' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'selasa' => ['aktif' => false],
            'rabu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'kamis' => ['aktif' => false],
            'jumat' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
            'sabtu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'minggu' => ['aktif' => false],
        ];

        $jadwalShiftB = [
            'senin' => ['aktif' => false],
            'selasa' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'rabu' => ['aktif' => false],
            'kamis' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'jumat' => ['aktif' => false],
            'sabtu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'minggu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
        ];

        $dokters = [
            // POLI UMUM
            ['nama' => 'Dewi Kusuma', 'gelar_depan' => 'dr.', 'gelar_belakang' => null, 'spesialisasi' => 'Dokter Umum', 'poli_id' => $poliMap['UMUM'], 'satusehat_id' => 'dr-ss-001', 'jadwal' => $jadwalFull],
            ['nama' => 'Budi Setiawan', 'gelar_depan' => 'dr.', 'gelar_belakang' => null, 'spesialisasi' => 'Dokter Umum', 'poli_id' => $poliMap['UMUM'], 'satusehat_id' => 'dr-ss-002', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Maya Putri', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'M.Kes', 'spesialisasi' => 'Dokter Umum', 'poli_id' => $poliMap['UMUM'], 'satusehat_id' => 'dr-ss-003', 'jadwal' => $jadwalShiftB],

            // POLI ANAK
            ['nama' => 'Siti Rahma Dewi', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.A', 'spesialisasi' => 'Dokter Spesialis Anak', 'poli_id' => $poliMap['ANAK'], 'satusehat_id' => 'dr-ss-004', 'jadwal' => $jadwalFull],
            ['nama' => 'Hendra Wijaya', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.A', 'spesialisasi' => 'Dokter Spesialis Anak', 'poli_id' => $poliMap['ANAK'], 'satusehat_id' => 'dr-ss-005', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Anisa Permata', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.A', 'spesialisasi' => 'Dokter Spesialis Anak', 'poli_id' => $poliMap['ANAK'], 'satusehat_id' => 'dr-ss-006', 'jadwal' => $jadwalShiftB],

            // POLI PENYAKIT DALAM
            ['nama' => 'Ahmad Fauzi', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.PD', 'spesialisasi' => 'Penyakit Dalam', 'poli_id' => $poliMap['DALAM'], 'satusehat_id' => 'dr-ss-007', 'jadwal' => $jadwalFull],
            ['nama' => 'Rizky Kurniawan', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.PD, K-HOM', 'spesialisasi' => 'Hematologi & Onkologi', 'poli_id' => $poliMap['DALAM'], 'satusehat_id' => 'dr-ss-008', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Tri Handayani', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.PD', 'spesialisasi' => 'Penyakit Dalam', 'poli_id' => $poliMap['DALAM'], 'satusehat_id' => 'dr-ss-009', 'jadwal' => $jadwalShiftB],

            // POLI KEBIDANAN & KANDUNGAN
            ['nama' => 'Budi Santoso', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.OG', 'spesialisasi' => 'Obstetri & Ginekologi', 'poli_id' => $poliMap['KANDUNGAN'], 'satusehat_id' => 'dr-ss-010', 'jadwal' => $jadwalFull],
            ['nama' => 'Ratna Juwita', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.OG', 'spesialisasi' => 'Obstetri & Ginekologi', 'poli_id' => $poliMap['KANDUNGAN'], 'satusehat_id' => 'dr-ss-011', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Diah Ayu', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.OG', 'spesialisasi' => 'Obstetri & Ginekologi', 'poli_id' => $poliMap['KANDUNGAN'], 'satusehat_id' => 'dr-ss-012', 'jadwal' => $jadwalShiftB],

            // POLI BEDAH
            ['nama' => 'Maya Indah Lestari', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.B', 'spesialisasi' => 'Bedah Umum', 'poli_id' => $poliMap['BEDAH'], 'satusehat_id' => 'dr-ss-013', 'jadwal' => $jadwalFull],
            ['nama' => 'Fajar Hidayat', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.B', 'spesialisasi' => 'Bedah Umum', 'poli_id' => $poliMap['BEDAH'], 'satusehat_id' => 'dr-ss-014', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Eko Prasetyo', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.B', 'spesialisasi' => 'Bedah Umum', 'poli_id' => $poliMap['BEDAH'], 'satusehat_id' => 'dr-ss-015', 'jadwal' => $jadwalShiftB],

            // POLI JANTUNG
            ['nama' => 'Rudi Pratama', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.JP', 'spesialisasi' => 'Jantung & Pembuluh Darah', 'poli_id' => $poliMap['JANTUNG'], 'satusehat_id' => 'dr-ss-016', 'jadwal' => $jadwalFull],
            ['nama' => 'Diana Kartika', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.JP, FIHA', 'spesialisasi' => 'Kardiologi Intervensi', 'poli_id' => $poliMap['JANTUNG'], 'satusehat_id' => 'dr-ss-017', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Wahyu Nugroho', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.JP', 'spesialisasi' => 'Jantung & Pembuluh Darah', 'poli_id' => $poliMap['JANTUNG'], 'satusehat_id' => 'dr-ss-018', 'jadwal' => $jadwalShiftB],

            // POLI SARAF
            ['nama' => 'Bambang Suherman', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.N', 'spesialisasi' => 'Neurologi / Saraf', 'poli_id' => $poliMap['SARAF'], 'satusehat_id' => 'dr-ss-019', 'jadwal' => $jadwalFull],
            ['nama' => 'Rina Amalia', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.N', 'spesialisasi' => 'Neurologi / Saraf', 'poli_id' => $poliMap['SARAF'], 'satusehat_id' => 'dr-ss-020', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Farhan Mubarak', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.N', 'spesialisasi' => 'Neurologi / Saraf', 'poli_id' => $poliMap['SARAF'], 'satusehat_id' => 'dr-ss-021', 'jadwal' => $jadwalShiftB],

            // POLI MATA
            ['nama' => 'Nurul Hidayah', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.M', 'spesialisasi' => 'Oftalmologi / Mata', 'poli_id' => $poliMap['MATA'], 'satusehat_id' => 'dr-ss-022', 'jadwal' => $jadwalFull],
            ['nama' => 'Irfan Hakim', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.M', 'spesialisasi' => 'Oftalmologi / Mata', 'poli_id' => $poliMap['MATA'], 'satusehat_id' => 'dr-ss-023', 'jadwal' => $jadwalShiftA],
            ['nama' => 'Tari Sukmawati', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.M', 'spesialisasi' => 'Oftalmologi / Mata', 'poli_id' => $poliMap['MATA'], 'satusehat_id' => 'dr-ss-024', 'jadwal' => $jadwalShiftB],
        ];

        foreach ($dokters as $dokter) {
            Dokter::updateOrCreate(
                ['satusehat_id' => $dokter['satusehat_id']],
                array_merge($dokter, ['is_active' => true])
            );
        }

        // ====== PASIEN DEMO ======
        $userPasien = User::firstOrCreate(['email' => 'pasien@demo.com'], [
            'name' => 'Budi Hartono',
            'password' => Hash::make('pasien123'),
            'role' => 'pasien',
            'is_active' => true,
        ]);

        Pasien::firstOrCreate(['nik' => '3511010101900001'], [
            'user_id' => $userPasien->id,
            'nama_lengkap' => 'Budi Hartono',
            'tanggal_lahir' => '1990-01-01',
            'tempat_lahir' => 'Bondowoso',
            'jenis_kelamin' => 'L',
            'golongan_darah' => 'O',
            'agama' => 'Islam',
            'status_pernikahan' => 'Menikah',
            'pekerjaan' => 'Wiraswasta',
            'no_hp' => '081234567890',
            'email' => 'pasien@demo.com',
            'alamat' => 'Jl. Mastrip No. 25, RT 01 RW 02',
            'kecamatan' => 'Bondowoso',
            'kabupaten' => 'Bondowoso',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '68219',
            'nama_pj' => 'Siti Hartono',
            'hubungan_pj' => 'Istri',
            'no_hp_pj' => '081234567891',
            'status' => 'aktif',
        ]);
    }
}
