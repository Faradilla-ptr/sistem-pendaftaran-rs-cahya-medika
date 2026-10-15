<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Icd9Code;

class Icd9Seeder extends Seeder
{
    public function run(): void
    {
        Icd9Code::truncate();

        $data = [
            // Operasi & Tindakan Rawat Jalan & Poli Umum/Spesialis
            ['code' => '89.03', 'name_id' => 'Wawancara dan Evaluasi Medis, Konsultasi Rawat Jalan (Konsultasi Dokter)'],
            ['code' => '89.07', 'name_id' => 'Pemeriksaan Fisik Lengkap (General Physical Examination)'],
            ['code' => '89.52', 'name_id' => 'Elektrokardiogram (EKG 12 Sandapan)'],
            ['code' => '88.76', 'name_id' => 'Ultrasonografi Diagnostik Abdomen (USG Abdomen)'],
            ['code' => '88.78', 'name_id' => 'Ultrasonografi Kandungan & Kebidanan (USG Obstetri / Ginekologi)'],
            ['code' => '87.44', 'name_id' => 'Rontgen Dada Rutin (Foto Thorax PA / AP)'],
            ['code' => '93.94', 'name_id' => 'Nebulisasi / Terapi Aerosol Saluran Napas'],
            ['code' => '96.07', 'name_id' => 'Pemasangan Selang Nasogastrik (NGT)'],
            ['code' => '57.94', 'name_id' => 'Pemasangan Kateter Urin Menetap (Kateter Foley)'],
            ['code' => '99.18', 'name_id' => 'Injeksi atau Infus Elektrolit / Cairan Intravena (Infus RL / PZ / D5)'],
            ['code' => '99.21', 'name_id' => 'Injeksi Antibiotik Intravena / Intramuskular'],
            ['code' => '99.23', 'name_id' => 'Injeksi Kortikosteroid / Antiinflamasi'],
            ['code' => '99.29', 'name_id' => 'Injeksi Terapi Medis Lainnya (Analgetik / Antipiretik / Antiemetik)'],
            ['code' => '93.57', 'name_id' => 'Perawatan dan Pembalutan Luka Bersih / Kotor (Ganti Verban)'],
            ['code' => '86.59', 'name_id' => 'Penjahitan Luka Kulit dan Jaringan Subkutan (Hecting Luka)'],
            ['code' => '97.83', 'name_id' => 'Pelepasan Jahitan Luka Bedah (Angkat Jahitan / Aff Hecting)'],
            ['code' => '96.59', 'name_id' => 'Irigasi Luka atau Pembersihan Jaringan / Debridement Minor'],
            ['code' => '90.59', 'name_id' => 'Pengambilan Sampel Darah Vena (Pemeriksaan Laboratorium Darah Rutin)'],
            ['code' => '91.39', 'name_id' => 'Pemeriksaan Urin Rutin (Urinalisis)'],
            ['code' => '99.99', 'name_id' => 'Tindakan Medis Lainnya Tidak Diklasifikasikan'],
            // Kasus Anak / Neonatus
            ['code' => '99.31', 'name_id' => 'Vaksinasi / Imunisasi BCG'],
            ['code' => '99.33', 'name_id' => 'Vaksinasi / Imunisasi DTP / Hepatitis B'],
            ['code' => '99.35', 'name_id' => 'Vaksinasi / Imunisasi Polio'],
            ['code' => '99.38', 'name_id' => 'Vaksinasi / Imunisasi Campak / MMR'],
            ['code' => '96.56', 'name_id' => 'Resusitasi Bayi Baru Lahir / Suction Lendir Jalan Napas Neonatus'],
            ['code' => '99.83', 'name_id' => 'Fototerapi Neonatus (Bilirubinemia Bayi)'],
            // Kasus Kandungan / Obstetri
            ['code' => '73.59', 'name_id' => 'Pertolongan Persalinan Normal (Spontan Pervaginam)'],
            ['code' => '73.6',  'name_id' => 'Episiotomi dengan Penjahitan (Episiotomy)'],
            ['code' => '75.34', 'name_id' => 'Pemantauan Denyut Jantung Janin / CTG (Cardiotocography)'],
            ['code' => '69.09', 'name_id' => 'Kuretase Uterus / Dilatasi dan Kuretase (D&C)'],
        ];

        foreach ($data as $item) {
            Icd9Code::create($item);
        }
    }
}
