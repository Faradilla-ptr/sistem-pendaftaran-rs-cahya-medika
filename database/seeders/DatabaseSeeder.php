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
        User::create([
            'name' => 'Admin RS Cahya Medika',
            'email' => 'admin@rscahyamedika.co.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
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
            Poli::create(array_merge($poli, ['jam_buka' => '07:30', 'jam_tutup' => '14:00', 'is_active' => true]));
        }

        // ====== DOKTER ======
        $poliUmum = Poli::where('kode', 'UMUM')->first();
        $poliAnak = Poli::where('kode', 'ANAK')->first();
        $poliDalam = Poli::where('kode', 'DALAM')->first();
        $poliKandungan = Poli::where('kode', 'KANDUNGAN')->first();
        $poliBedah = Poli::where('kode', 'BEDAH')->first();
        $poliJantung = Poli::where('kode', 'JANTUNG')->first();

        $jadwalDefault = [
            'senin' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'selasa' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'rabu' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'kamis' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00'],
            'jumat' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '11:00'],
        ];

        $dokters = [
            ['nama' => 'Ahmad Fauzi', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.PD', 'spesialisasi' => 'Penyakit Dalam', 'poli_id' => $poliDalam->id, 'satusehat_id' => 'dr-ss-001'],
            ['nama' => 'Siti Rahma Dewi', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.A', 'spesialisasi' => 'Dokter Spesialis Anak', 'poli_id' => $poliAnak->id, 'satusehat_id' => 'dr-ss-002'],
            ['nama' => 'Budi Santoso', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.OG', 'spesialisasi' => 'Obstetri & Ginekologi', 'poli_id' => $poliKandungan->id, 'satusehat_id' => 'dr-ss-003'],
            ['nama' => 'Maya Indah Lestari', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.B', 'spesialisasi' => 'Bedah Umum', 'poli_id' => $poliBedah->id, 'satusehat_id' => 'dr-ss-004'],
            ['nama' => 'Rudi Pratama', 'gelar_depan' => 'dr.', 'gelar_belakang' => 'Sp.JP', 'spesialisasi' => 'Jantung & Pembuluh Darah', 'poli_id' => $poliJantung->id, 'satusehat_id' => 'dr-ss-005'],
            ['nama' => 'Dewi Kusuma', 'gelar_depan' => 'dr.', 'gelar_belakang' => null, 'spesialisasi' => 'Dokter Umum', 'poli_id' => $poliUmum->id, 'satusehat_id' => 'dr-ss-006'],
        ];

        foreach ($dokters as $dokter) {
            Dokter::create(array_merge($dokter, ['jadwal' => $jadwalDefault, 'is_active' => true]));
        }

        // ====== PASIEN DEMO ======
        $userPasien = User::create([
            'name' => 'Budi Hartono',
            'email' => 'pasien@demo.com',
            'password' => Hash::make('pasien123'),
            'role' => 'pasien',
            'is_active' => true,
        ]);

        Pasien::create([
            'user_id' => $userPasien->id,
            'nik' => '3511010101900001',
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
