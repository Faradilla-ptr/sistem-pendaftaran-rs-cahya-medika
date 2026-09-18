<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Icd10Code;

class Icd10Seeder extends Seeder
{
    public function run(): void
    {
        Icd10Code::truncate();

        $data = [
            // BAB I (A00-B99): Penyakit infeksi dan parasit tertentu
            ['code' => 'A00.0', 'name_id' => 'Kolera akibat Vibrio cholerae 01, biovar cholerae', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A01.0', 'name_id' => 'Demam Tifoid (Typhoid Fever / Tipes)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A03.9', 'name_id' => 'Shigellosis, tidak ditentukan (Disentri Basiler)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A06.0', 'name_id' => 'Disentri amuba akut (Amoebiasis)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A09', 'name_id' => 'Gastroenteritis dan kolitis infeksius (Diare Akut / Muntaber)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A15.0', 'name_id' => 'Tuberkulosis Paru BTA (+) (TBC Paru)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A16.2', 'name_id' => 'Tuberkulosis Paru tanpa konfirmasi bakteriologis', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A30.9', 'name_id' => 'Lepra / Kusta, tidak ditentukan', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A35', 'name_id' => 'Tetanus Lainnya (Tetanus Neonatorum / Otitis)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A36.9', 'name_id' => 'Difteria, tidak ditentukan', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A37.9', 'name_id' => 'Pertusis (Batuk 100 Hari / Rejan)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A90', 'name_id' => 'Demam Dengue (Dengue Fever / DF)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'A91', 'name_id' => 'Demam Berdarah Dengue (Dengue Hemorrhagic Fever / DHF)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B50.9', 'name_id' => 'Malaria Plasmodium falciparum', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B51.9', 'name_id' => 'Malaria Plasmodium vivax', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B05.9', 'name_id' => 'Campak tanpa komplikasi (Measles / Morbilli)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B01.9', 'name_id' => 'Varicella (Cacar Air)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B02.9', 'name_id' => 'Herpes Zoster tanpa komplikasi (Cacar Ular)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B15.9', 'name_id' => 'Hepatitis A Akut tanpa koma hepatikum', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B16.9', 'name_id' => 'Hepatitis B Akut tanpa agen delta', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B20', 'name_id' => 'Penyakit HIV (Human Immunodeficiency Virus)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],
            ['code' => 'B34.9', 'name_id' => 'Infeksi virus tidak ditentukan (Viral Infection)', 'chapter_number' => 'Bab I', 'chapter_name' => 'Penyakit Infeksi dan Parasit', 'block_range' => 'A00-B99'],

            // BAB II (C00-D48): Neoplasma (Tumor & Kanker)
            ['code' => 'C16.9', 'name_id' => 'Neoplasma ganas Lambung (Kanker Lambung)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'C18.9', 'name_id' => 'Neoplasma ganas Kolon (Kanker Usus Besar)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'C34.9', 'name_id' => 'Neoplasma ganas Bronkus & Paru (Kanker Paru)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'C50.9', 'name_id' => 'Neoplasma ganas Payudara (Kanker Payudara / MAMMAE)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'C53.9', 'name_id' => 'Neoplasma ganas Serviks Uteri (Kanker Leher Rahim)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'D12.6', 'name_id' => 'Tumor jinak Kolon (Polip Usus)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'D17.9', 'name_id' => 'Lipoma, lokasi tidak ditentukan (Benjolan Lemak)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'D25.9', 'name_id' => 'Leiomioma Uteri (Miom Uterus)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],
            ['code' => 'D24', 'name_id' => 'Tumor jinak Payudara (FAM / Fibroadenoma Mammae)', 'chapter_number' => 'Bab II', 'chapter_name' => 'Neoplasma (Tumor & Kanker)', 'block_range' => 'C00-D48'],

            // BAB III (D50-D89): Penyakit Darah & Kekebalan Tubuh
            ['code' => 'D50.9', 'name_id' => 'Anemia Defisiensi Besi, tidak ditentukan', 'chapter_number' => 'Bab III', 'chapter_name' => 'Penyakit Darah & Kekebalan Tubuh', 'block_range' => 'D50-D89'],
            ['code' => 'D56.9', 'name_id' => 'Thalassemia, tidak ditentukan', 'chapter_number' => 'Bab III', 'chapter_name' => 'Penyakit Darah & Kekebalan Tubuh', 'block_range' => 'D50-D89'],
            ['code' => 'D61.9', 'name_id' => 'Anemia Aplastik, tidak ditentukan', 'chapter_number' => 'Bab III', 'chapter_name' => 'Penyakit Darah & Kekebalan Tubuh', 'block_range' => 'D50-D89'],
            ['code' => 'D64.9', 'name_id' => 'Anemia, tidak ditentukan', 'chapter_number' => 'Bab III', 'chapter_name' => 'Penyakit Darah & Kekebalan Tubuh', 'block_range' => 'D50-D89'],
            ['code' => 'D68.9', 'name_id' => 'Gangguan Koagulasi Darah (Hemofilia / Perdarahan)', 'chapter_number' => 'Bab III', 'chapter_name' => 'Penyakit Darah & Kekebalan Tubuh', 'block_range' => 'D50-D89'],

            // BAB IV (E00-E90): Penyakit Endokrin, Nutrisi & Metabolik
            ['code' => 'E03.9', 'name_id' => 'Hipotiroidisme, tidak ditentukan', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E05.9', 'name_id' => 'Tirotoksikosis / Hipertiroidisme', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E10.9', 'name_id' => 'Diabetes Mellitus Tipe 1 tanpa komplikasi', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E11.9', 'name_id' => 'Diabetes Mellitus Tipe 2 tanpa komplikasi (Kencing Manis)', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E11.5', 'name_id' => 'Diabetes Mellitus Tipe 2 dengan Komplikasi Sirkulasi Perifer', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E66.9', 'name_id' => 'Obesitas, tidak ditentukan', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E78.5', 'name_id' => 'Hyperlipidemia, tidak ditentukan (Kolesterol Tinggi)', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'M10.9', 'name_id' => 'Gout, tidak ditentukan (Asam Urat Akut)', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],
            ['code' => 'E46', 'name_id' => 'Malnutrisi Protein-Energi, tidak ditentukan (Gizi Buruk)', 'chapter_number' => 'Bab IV', 'chapter_name' => 'Penyakit Endokrin, Nutrisi & Metabolik', 'block_range' => 'E00-E90'],

            // BAB V (F00-F99): Gangguan Mental dan Perilaku
            ['code' => 'F03', 'name_id' => 'Demensia, tidak ditentukan (Pikun)', 'chapter_number' => 'Bab V', 'chapter_name' => 'Gangguan Mental dan Perilaku', 'block_range' => 'F00-F99'],
            ['code' => 'F20.9', 'name_id' => 'Skizofrenia, tidak ditentukan', 'chapter_number' => 'Bab V', 'chapter_name' => 'Gangguan Mental dan Perilaku', 'block_range' => 'F00-F99'],
            ['code' => 'F32.9', 'name_id' => 'Episode Depresif, tidak ditentukan (Depresi)', 'chapter_number' => 'Bab V', 'chapter_name' => 'Gangguan Mental dan Perilaku', 'block_range' => 'F00-F99'],
            ['code' => 'F41.9', 'name_id' => 'Gangguan Anxietas / Kecemasan, tidak ditentukan', 'chapter_number' => 'Bab V', 'chapter_name' => 'Gangguan Mental dan Perilaku', 'block_range' => 'F00-F99'],
            ['code' => 'F51.0', 'name_id' => 'Insomnia Non-organik (Susah Tidur)', 'chapter_number' => 'Bab V', 'chapter_name' => 'Gangguan Mental dan Perilaku', 'block_range' => 'F00-F99'],

            // BAB VI (G00-G99): Penyakit Sistem Saraf
            ['code' => 'G03.9', 'name_id' => 'Meningitis, tidak ditentukan (Radang Selaput Otak)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G40.9', 'name_id' => 'Epilepsi, tidak ditentukan (Ayan / Kejang)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G43.9', 'name_id' => 'Migren, tidak ditentukan (Sakit Kepala Sebelah)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G44.2', 'name_id' => 'Tension-type Headache (Sakit Kepala Tegang)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G51.0', 'name_id' => 'Bells Palsy (Kelumpuhan Saraf Wajah)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G54.2', 'name_id' => 'Radikulopati Servikal (Saraf Terjepit Leher)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],
            ['code' => 'G54.4', 'name_id' => 'Radikulopati Lumbosakral (HNP / Saraf Terjepit Pinggang)', 'chapter_number' => 'Bab VI', 'chapter_name' => 'Penyakit Sistem Saraf', 'block_range' => 'G00-G99'],

            // BAB VII (H00-H59): Penyakit Mata dan Adnexa
            ['code' => 'H00.0', 'name_id' => 'Hordeolum dan Kalazion (Bintitan / Benjolan Kelopak Mata)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H10.9', 'name_id' => 'Konjungtivitis, tidak ditentukan (Mata Merah / Belekan)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H11.0', 'name_id' => 'Pterigium (Selaput Mata)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H26.9', 'name_id' => 'Katarak, tidak ditentukan', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H40.9', 'name_id' => 'Glaukoma, tidak ditentukan (Tekanan Bola Mata Tinggi)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H52.1', 'name_id' => 'Miopia (Rabun Jauh / Minus)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],
            ['code' => 'H52.4', 'name_id' => 'Presbiopia (Rabun Dekat Usia Tua / Plus)', 'chapter_number' => 'Bab VII', 'chapter_name' => 'Penyakit Mata dan Adnexa', 'block_range' => 'H00-H59'],

            // BAB VIII (H60-H95): Penyakit Telinga dan Proses Mastoid
            ['code' => 'H60.9', 'name_id' => 'Otitis Eksterna, tidak ditentukan (Infeksi Liang Telinga Luar)', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],
            ['code' => 'H65.9', 'name_id' => 'Otitis Media NONSUPURATIF (Congek / Infeksi Telinga Tengah)', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],
            ['code' => 'H66.9', 'name_id' => 'Otitis Media SUPURATIF / Akut Kronis', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],
            ['code' => 'H81.1', 'name_id' => 'Benign Paroxysmal Positional Vertigo (BPPV / Pusing Tujuh Keliling)', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],
            ['code' => 'H93.1', 'name_id' => 'Tinnitus (Telinga Berdenging)', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],
            ['code' => 'H61.2', 'name_id' => 'Serumen Impaksi (Kotoran Telinga Tersumbat)', 'chapter_number' => 'Bab VIII', 'chapter_name' => 'Penyakit Telinga dan Mastoid', 'block_range' => 'H60-H95'],

            // BAB IX (I00-I99): Penyakit Sistem Sirkulasi (Jantung & Pembuluh Darah)
            ['code' => 'I10', 'name_id' => 'Hipertensi Esensial (Primer / Darah Tinggi)', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I11.9', 'name_id' => 'Penyakit Jantung Hipertensi tanpa gagal jantung', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I20.9', 'name_id' => 'Angina Pektoris, tidak ditentukan (Nyeri Dada Jantung)', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I21.9', 'name_id' => 'Infark Miokard Akut (Serangan Jantung Akut)', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I50.9', 'name_id' => 'Gagal Jantung, tidak ditentukan (Heart Failure)', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I64', 'name_id' => 'Stroke, tidak ditentukan sebagai perdarahan atau infark', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I84.9', 'name_id' => 'Hemoroid / Wasir tanpa komplikasi', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],
            ['code' => 'I95.9', 'name_id' => 'Hipotensi, tidak ditentukan (Tekanan Darah Rendah)', 'chapter_number' => 'Bab IX', 'chapter_name' => 'Penyakit Sistem Sirkulasi', 'block_range' => 'I00-I99'],

            // BAB X (J00-J99): Penyakit Sistem Pernapasan
            ['code' => 'J00', 'name_id' => 'Nasofaringitis Akut [Common Cold / Batuk Pilek]', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J01.9', 'name_id' => 'Sinusitis Akut, tidak ditentukan', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J02.9', 'name_id' => 'Faringitis Akut (Radang Tenggorokan)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J03.9', 'name_id' => 'Tonsilitis Akut (Radang Amandel)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J06.9', 'name_id' => 'Infeksi Saluran Pernapasan Atas Akut (ISPA)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J18.9', 'name_id' => 'Pneumonia, tidak ditentukan (Infeksi Paru-paru)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J20.9', 'name_id' => 'Bronkitis Akut, tidak ditentukan', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J30.4', 'name_id' => 'Rinitis Alergi, tidak ditentukan (Bersin-bersin Alergi)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J44.9', 'name_id' => 'Penyakit Paru Obstruktif Kronik (PPOK / COPD)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],
            ['code' => 'J45.909', 'name_id' => 'Asma Bronkial, tidak ditentukan (Sesak Napas Asma)', 'chapter_number' => 'Bab X', 'chapter_name' => 'Penyakit Sistem Pernapasan', 'block_range' => 'J00-J99'],

            // BAB XI (K00-K95): Penyakit Sistem Pencernaan
            ['code' => 'K02.9', 'name_id' => 'Karies Gigi, tidak ditentukan (Gigi Berlubang)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K05.3', 'name_id' => 'Periodontitis Kronis (Gusi Berdarah / Infeksi Gusi)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K12.1', 'name_id' => 'Stomatitis (Sariawan mulut)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K21.9', 'name_id' => 'Gastro-esophageal Reflux Disease (GERD / Asam Lambung Naik)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K25.9', 'name_id' => 'Ulkus Lambung (Tukak Lambung)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K29.7', 'name_id' => 'Gastritis, tidak ditentukan (Maag Akut / Kronis)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K30', 'name_id' => 'Dispepsia (Nyeri Ulu Hati / Kembung)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K35.80', 'name_id' => 'Apendisitis Akut, tidak ditentukan (Usus Buntu)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K40.9', 'name_id' => 'Hernia Inguinalis tanpa obstruksi atau gangren (Turun Berok)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K80.8', 'name_id' => 'Kolelitiasis (Batu Empedu)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],
            ['code' => 'K59.0', 'name_id' => 'Konstipasi (Sembelit / Susah BAB)', 'chapter_number' => 'Bab XI', 'chapter_name' => 'Penyakit Sistem Pencernaan', 'block_range' => 'K00-K95'],

            // BAB XII (L00-L99): Penyakit Kulit dan Jaringan Subkutan
            ['code' => 'L02.9', 'name_id' => 'Abses Kulit, Furunkel dan Karbunkel (Bisul)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L03.9', 'name_id' => 'Selulitis, tidak ditentukan (Infeksi Jaringan Kulit)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L20.9', 'name_id' => 'Dermatitis Atopik (Eksim)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L23.9', 'name_id' => 'Dermatitis Kontak Alergi', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L40.9', 'name_id' => 'Psoriasis, tidak ditentukan', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L50.9', 'name_id' => 'Urtikaria (Biduran / Kaligata)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'L70.0', 'name_id' => 'Acne Vulgaris (Jerawat)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'B35.4', 'name_id' => 'Tinea Corporis (Kurap / Jamur Kulit Badan)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],
            ['code' => 'B35.3', 'name_id' => 'Tinea Pedis (Kutu Air / Jamur Kaki)', 'chapter_number' => 'Bab XII', 'chapter_name' => 'Penyakit Kulit & Subkutan', 'block_range' => 'L00-L99'],

            // BAB XIII (M00-M99): Penyakit Sistem Muskuloskeletal dan Jaringan Ikat
            ['code' => 'M06.9', 'name_id' => 'Artritis Reumatoid, tidak ditentukan (Rematik)', 'chapter_number' => 'Bab XIII', 'chapter_name' => 'Penyakit Muskuloskeletal & Jaringan Ikat', 'block_range' => 'M00-M99'],
            ['code' => 'M19.9', 'name_id' => 'Osteoartritis (Pengapuran Sendi)', 'chapter_number' => 'Bab XIII', 'chapter_name' => 'Penyakit Muskuloskeletal & Jaringan Ikat', 'block_range' => 'M00-M99'],
            ['code' => 'M54.5', 'name_id' => 'Low Back Pain / LBP (Nyeri Punggung Bawah)', 'chapter_number' => 'Bab XIII', 'chapter_name' => 'Penyakit Muskuloskeletal & Jaringan Ikat', 'block_range' => 'M00-M99'],
            ['code' => 'M79.1', 'name_id' => 'Mialgia (Nyeri Otot / Pegal-pegal)', 'chapter_number' => 'Bab XIII', 'chapter_name' => 'Penyakit Muskuloskeletal & Jaringan Ikat', 'block_range' => 'M00-M99'],
            ['code' => 'M81.9', 'name_id' => 'Osteoporosis (Pengeroposan Tulang)', 'chapter_number' => 'Bab XIII', 'chapter_name' => 'Penyakit Muskuloskeletal & Jaringan Ikat', 'block_range' => 'M00-M99'],

            // BAB XIV (N00-N99): Penyakit Sistem Kemih dan Genital
            ['code' => 'N04.9', 'name_id' => 'Sindrom Nefrotik, tidak ditentukan', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N18.9', 'name_id' => 'Penyakit Ginjal Kronis (Gagal Ginjal Kronis / CKD)', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N20.1', 'name_id' => 'Batu Ureter (Kencing Batu)', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N39.0', 'name_id' => 'Infeksi Saluran Kemih / ISK (Urinary Tract Infection)', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N40', 'name_id' => 'Hiperplasia Prostat Benigna (BPH / Pembesaran Prostat)', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N83.2', 'name_id' => 'Kista Ovarium, tidak ditentukan', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],
            ['code' => 'N94.6', 'name_id' => 'Dismenorea, tidak ditentukan (Nyeri Haid)', 'chapter_number' => 'Bab XIV', 'chapter_name' => 'Penyakit Sistem Kemih dan Genital', 'block_range' => 'N00-N99'],

            // BAB XV (O00-O99): Kehamilan, Persalinan dan Nifas
            ['code' => 'O00.9', 'name_id' => 'Kehamilan Ektopik (Kehamilan Di Luar Kandungan)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O03.9', 'name_id' => 'Keguguran Spontan (Abortus Spontan)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O21.0', 'name_id' => 'Hiperemesis Gravidarum Ringan / Sedang (Mual Muntah Hamil)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O14.9', 'name_id' => 'Preeklampsia, tidak ditentukan (Keracunan Kehamilan)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O42.9', 'name_id' => 'Ketuban Pecah Dini (KPD)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O80.9', 'name_id' => 'Persalinan Tunggal Spontan (Melahirkan Normal)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],
            ['code' => 'O82.9', 'name_id' => 'Seksio Sesarea / Operasi Caesar (SC)', 'chapter_number' => 'Bab XV', 'chapter_name' => 'Kehamilan, Persalinan & Nifas', 'block_range' => 'O00-O99'],

            // BAB XVI (P00-P96): Kondisi Perinatal
            ['code' => 'P07.3', 'name_id' => 'Bayi Berat Lahir Rendah / BBLR (Prematur)', 'chapter_number' => 'Bab XVI', 'chapter_name' => 'Kondisi Perinatal', 'block_range' => 'P00-P96'],
            ['code' => 'P21.9', 'name_id' => 'Asfiksia Lahir, tidak ditentukan (Bayi Tidak Langsung Menangis)', 'chapter_number' => 'Bab XVI', 'chapter_name' => 'Kondisi Perinatal', 'block_range' => 'P00-P96'],
            ['code' => 'P59.9', 'name_id' => 'Ikterus Neonatorum (Bayi Kuning)', 'chapter_number' => 'Bab XVI', 'chapter_name' => 'Kondisi Perinatal', 'block_range' => 'P00-P96'],
            ['code' => 'P36.9', 'name_id' => 'Sepsis Neonatal, tidak ditentukan (Infeksi Bayi Baru Lahir)', 'chapter_number' => 'Bab XVI', 'chapter_name' => 'Kondisi Perinatal', 'block_range' => 'P00-P96'],

            // BAB XVII (Q00-Q99): Kelainan Kongenital & Kromosom
            ['code' => 'Q36.9', 'name_id' => 'Bibir Sumbing (Labioschisis)', 'chapter_number' => 'Bab XVII', 'chapter_name' => 'Kelainan Kongenital & Kromosom', 'block_range' => 'Q00-Q99'],
            ['code' => 'Q03.9', 'name_id' => 'Hidrosefalus Kongenital', 'chapter_number' => 'Bab XVII', 'chapter_name' => 'Kelainan Kongenital & Kromosom', 'block_range' => 'Q00-Q99'],
            ['code' => 'Q21.1', 'name_id' => 'Defek Septum Atrium (ASD / Kebocoran Jantung Bawaan)', 'chapter_number' => 'Bab XVII', 'chapter_name' => 'Kelainan Kongenital & Kromosom', 'block_range' => 'Q00-Q99'],
            ['code' => 'Q90.9', 'name_id' => 'Sindrom Down, tidak ditentukan', 'chapter_number' => 'Bab XVII', 'chapter_name' => 'Kelainan Kongenital & Kromosom', 'block_range' => 'Q00-Q99'],

            // BAB XVIII (R00-R99): Gejala, Tanda & Temuan Klinis
            ['code' => 'R05', 'name_id' => 'Batuk (Cough)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R06.0', 'name_id' => 'Dispnea (Sesak Napas / Dyspnea)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R07.4', 'name_id' => 'Nyeri Dada, tidak ditentukan (Chest Pain)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R10.4', 'name_id' => 'Nyeri Perut / Abdomen (Abdominal Pain)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R11', 'name_id' => 'Mual dan Muntah (Nausea & Vomiting)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R42', 'name_id' => 'Pusing dan Vertigo (Dizziness & Giddiness)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R50.9', 'name_id' => 'Demam, tidak ditentukan (Febris)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R51', 'name_id' => 'Sakit Kepala (Headache)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R53', 'name_id' => 'Malaise dan Kelelahan (Fatigue / Badan Lemas)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],
            ['code' => 'R59.9', 'name_id' => 'Pembesaran Kelenjar Getah Bening (Limfadenopati)', 'chapter_number' => 'Bab XVIII', 'chapter_name' => 'Gejala, Tanda & Temuan Klinis', 'block_range' => 'R00-R99'],

            // BAB XIX (S00-T98): Cedera, Keracunan & Akibat Luar
            ['code' => 'S01.9', 'name_id' => 'Luka Terbuka pada Kepala (Vulnus Laceratum Kepala)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'S06.9', 'name_id' => 'Cedera Kepala Sedang / Berat (Trauma Kapitis)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'S52.9', 'name_id' => 'Fraktur Lengan Bawah / Radioulnar (Patah Tulang Tangan)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'S82.9', 'name_id' => 'Fraktur Tungkai Bawah / Tibia Fibula (Patah Tulang Kaki)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'T14.1', 'name_id' => 'Luka Robek / Vulnus Laceratum pada Lokasi Tubuh Lain', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'T30.0', 'name_id' => 'Luka Bakar, derajat tidak ditentukan (Combustio)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],
            ['code' => 'T62.9', 'name_id' => 'Keracunan Makanan (Intoksikasi Makanan)', 'chapter_number' => 'Bab XIX', 'chapter_name' => 'Cedera, Keracunan & Akibat Luar', 'block_range' => 'S00-T98'],

            // BAB XX (V01-Y98): Penyebab Luar Morbiditas
            ['code' => 'V89.2', 'name_id' => 'Kecelakaan Lalu Lintas Sepeda Motor / Mobil (KLL)', 'chapter_number' => 'Bab XX', 'chapter_name' => 'Penyebab Luar Morbiditas', 'block_range' => 'V01-Y98'],
            ['code' => 'W19', 'name_id' => 'Jatuh, tidak ditentukan', 'chapter_number' => 'Bab XX', 'chapter_name' => 'Penyebab Luar Morbiditas', 'block_range' => 'V01-Y98'],
            ['code' => 'X59', 'name_id' => 'Paparan Faktor Yang Tidak Ditentukan (Kecelakaan Kerja)', 'chapter_number' => 'Bab XX', 'chapter_name' => 'Penyebab Luar Morbiditas', 'block_range' => 'V01-Y98'],

            // BAB XXI (Z00-Z99): Faktor Yang Mempengaruhi Status Kesehatan
            ['code' => 'Z00.00', 'name_id' => 'Pemeriksaan Kesehatan Umum Dewasa (General Medical Checkup)', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],
            ['code' => 'Z01.2', 'name_id' => 'Pemeriksaan Gigi Rutin', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],
            ['code' => 'Z23', 'name_id' => 'Kebutuhan Imunisasi Rutin (Vaksinasi Anak/Dewasa)', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],
            ['code' => 'Z30.0', 'name_id' => 'Konseling dan Saran Kontrasepsi / Keluarga Berencana (KB)', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],
            ['code' => 'Z34.9', 'name_id' => 'Pemeriksaan Kehamilan Normal (ANC / Antenatal Care)', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],
            ['code' => 'Z39.2', 'name_id' => 'Pemeriksaan Pasca Persalinan Rutin (PNC / Postnatal Care)', 'chapter_number' => 'Bab XXI', 'chapter_name' => 'Faktor Mempengaruhi Status Kesehatan', 'block_range' => 'Z00-Z99'],

            // BAB XXII (U00-U85): Kode Untuk Tujuan Khusus
            ['code' => 'U07.1', 'name_id' => 'COVID-19, virus teridentifikasi (Terkonfirmasi Lab)', 'chapter_number' => 'Bab XXII', 'chapter_name' => 'Kode Untuk Tujuan Khusus', 'block_range' => 'U00-U85'],
            ['code' => 'U07.2', 'name_id' => 'COVID-19, virus tidak teridentifikasi (Suspek / Probable)', 'chapter_number' => 'Bab XXII', 'chapter_name' => 'Kode Untuk Tujuan Khusus', 'block_range' => 'U00-U85'],
            ['code' => 'U82.1', 'name_id' => 'Resistensi terhadap Penisilin / Antibiotik (AMR)', 'chapter_number' => 'Bab XXII', 'chapter_name' => 'Kode Untuk Tujuan Khusus', 'block_range' => 'U00-U85'],
        ];

        foreach ($data as $item) {
            Icd10Code::create($item);
        }
    }
}
