# PRODUCT REQUIREMENT DOCUMENT (PRD)
## SISTEM INFORMASI PENDAFTARAN RAWAT JALAN BERBASIS WEB DENGAN QR CODE, MODUL OCR-AI SCANNER, DAN INTEGRASI SATUSEHAT KEMENKES RI

**Peneliti / Penyusun**: Faradilla Anastasia Putri (NIM Mahasiswi D4 MIK, Politeknik Negeri Jember)  
**Program Studi**: D4 Manajemen Informasi Kesehatan (MIK), Jurusan Kesehatan  
**Lokasi Studi Kasus**: RSU / Klinik Cahya Medika  
**Versi Dokumen**: 2.0 (Berdasarkan Hasil Wawancara & Observasi Lapangan Rekam Medis & IT)  
**Tanggal**: Agustus 2026  

---

### 1. RINGKASAN EKSEKUTIF & LATAR BELAKANG
Berdasarkan hasil wawancara dan observasi mendalam bersama Unit Rekam Medis (Mbak Sania), Unit SIMRS / IT (Mas Adip), dan Petugas Loket Pendaftaran:
1. **Masalah Operasional Saat Ini**:
   - Pasien baru dan pasien lama sering mengalami kesalahan entri data (*typo*) pada nama, jenis kelamin, dan alamat domisili saat proses pendaftaran manual.
   - Pendaftaran online masih menggunakan form WhatsApp konvensional, sehingga petugas loket harus mengetik ulang (*double input*) ke dalam aplikasi SIMRS.
   - Sensus harian rawat jalan harus dipisahkan berdasarkan demografi wilayah (Dalam Kota vs Luar Kota) dan jenis kelamin untuk pelaporan rutin Kemenkes RI.
   - Penanganan pasien khusus seperti bayi baru lahir (usia < 30 hari yang belum memiliki NIK mandiri sehingga wajib ditautkan ke NIK Ibu) dan pasien darurat tanpa identitas (*emergency / unidentified*) memerlukan validasi sistem yang fleksibel namun ketat.
   - Adanya kebijakan operasional deposit pembayaran awal (Rp 200.000,-) untuk pasien umum rawat jalan dan pembatasan kuota poli dokter (25–30 pasien/hari) dengan pendaftaran H-1.
2. **Kebutuhan Regulasi**:
   - Kewajiban interoperabilitas data Rekam Medis Elektronik (RME) dengan platform **SATUSEHAT Kemenkes RI** (13 resource FHIR untuk Rawat Jalan).
   - Pengiriman data SATUSEHAT memerlukan tahapan *manual cross-check* dan koding ICD-10/ICD-9-CM oleh staf Rekam Medis sebelum dikirimkan ke server Kemenkes.
3. **Solusi Inovasi Sistem**:
   - Membangun portal pendaftaran mandiri pasien berbasis web responsif yang menghasilkan e-Tiket berisi **QR Code Registrasi**.
   - Menyediakan modul **QR Code & OCR-AI Scanner** di loket pendaftaran untuk mengekstrak data KTP / QR Code secara instan ke dalam form SIMRS tanpa mengetik ulang, meniadakan ketergantungan *bridging* berbayar ke pihak ketiga.
   - Mengintegrasikan pengelolaan kuota poli, jadwal dinamis dokter, pelaporan sensus harian otomatis, dan modul pengiriman SATUSEHAT.

---

### 2. TUJUAN & TARGET PROJECT
- **Meniadakan Double Input**: Menghemat waktu pelayanan loket dari rata-rata 3-5 menit menjadi < 30 detik per pasien.
- **Mencegah Duplikasi Nomor Rekam Medis**: Validasi NIK unik secara realtime dengan *warning system* otomatis.
- **Mendukung Akreditasi & RME**: Menyediakan pencatatan digital untuk General Consent, Form Edukasi, dan KIB (Kartu Identitas Berobat).
- **Otomatisasi Sensus Harian**: Menghasilkan rekapitulasi data demografi pasien (Kunjungan Baru/Lama, Laki-laki/Perempuan, Dalam Kota/Luar Kota, Poli Tujuan, Cara Bayar) yang siap diekspor ke format Excel dan PDF laporan resmi.

---

### 3. USER PERSONA & HAK AKSES (ROLE-BASED ACCESS CONTROL)
1. **Pasien (Umum / Terdaftar)**:
   - Registrasi akun dengan NIK/No. Identitas & No. WhatsApp.
   - Mendaftar antrean rawat jalan H-1 / hari H sesuai kuota poli yang tersedia.
   - Mengunggah foto KTP/identitas untuk ekstraksi otomatis saat verifikasi loket.
   - Mendapatkan e-Tiket Pendaftaran & QR Code antrean.
   - Melihat riwayat kunjungan dan status reservasi.
2. **Petugas Loket Pendaftaran (Front Office)**:
   - Scan QR Code e-Tiket pasien atau Scan KTP fisik menggunakan kamera/webcam loket (Modul OCR-AI).
   - Verifikasi data demografi, domisili, dan status penanggung jawab (khusus bayi/anak/lansia).
   - Mengelola deposit awal pendaftaran (Rp 200.000,-) dan mencetak bukti registrasi / KIB / Label Pasien.
   - Mengubah data pendaftaran **hanya selama pasien belum selesai dilayani di poli (sebelum CPPT/pemeriksaan diisi oleh perawat/dokter)**.
   - Mengelola pembatalan / pemindahan jadwal pasien jika dokter poli berhalangan praktik.
3. **Petugas Rekam Medis (Medical Record Officer)**:
   - Berwenang penuh melakukan perbaikan data master pasien (*merging* data duplikat, koreksi NIK/Nama/Alamat permanen).
   - Memberikan koding diagnosis (ICD-10) dan tindakan medis (ICD-9-CM).
   - Melakukan verifikasi akhir dan pengiriman data (Manual Cross-Check) ke SATUSEHAT Kemenkes.
   - Mengelola dan mengunduh Sensus Harian Rawat Jalan (format Excel & PDF) untuk pelaporan RL Kemenkes.
4. **Petugas Poli / Perawat / Dokter (Poli Rawat Jalan)**:
   - Memanggil antrean pasien sesuai urutan kedatangan.
   - Mengisi data anamnesa, pemeriksaan klinis, CPPT, tindakan (neonatal/anak/obstetri/ginekologi), dan resep obat.
5. **Administrator Sistem / IT**:
   - Manajemen user, master poli, dokter, jadwal praktik, kuota harian, serta konfigurasi kredensial OAuth2 SATUSEHAT Kemenkes.

---

### 4. SPESIFIKASI FITUR & KEBUTUHAN FUNGSIONAL (FUNCTIONAL REQUIREMENTS)

#### FR-01: Modul Registrasi & Identitas Pasien
- Input identitas wajib: NIK (16 digit), No. KK, Nama Lengkap, Tempat & Tanggal Lahir, Jenis Kelamin, Alamat KTP, Alamat Domisili, No. HP/WhatsApp Aktif, Status Pernikahan, Agama, Penanggung Jawab.
- **Logika Pasien Bayi (< 30 Hari)**: Jika usia < 30 hari, kolom NIK Bayi bersifat opsional dan sistem secara otomatis menampilkan *field mandatory* **NIK Ibu** & **Nama Ibu Kandung**.
- **Logika Pasien Tanpa Identitas (Emergency/Mr. X)**: Opsi *checkbox* "Tanpa Identitas" yang memberikan penomoran sementara dan flag `is_identitas_lengkap = false` serta catatan khusus untuk verifikasi susulan via WhatsApp.
- **Deteksi NIK Ganda**: Validasi AJAX realtime saat input NIK. Jika NIK sudah ada di database, sistem langsung menampilkan modal peringatan dan memuat data pasien lama tanpa membuat nomor RM baru.

#### FR-02: Modul Reservasi Rawat Jalan & Manajemen Kuota
- Pasien memilih Poli Tujuan, Dokter, Tanggal Kunjungan, dan Cara Bayar (Umum / Asuransi / BPJS).
- Validasi Kuota: Kuota maksimal default 25-30 pasien per sesi dokter. Jika kuota penuh, tombol pendaftaran dinonaktifkan dan pasien disarankan memilih tanggal berikutnya.
- Pembatalan / Reschedule Dokter: Jika dokter berhalangan (misal: kuota dibatalkan mendadak), admin loket dapat melakukan *broadcast* notifikasi status ke nomor WA pasien dan memindahkan antrean ke hari berikutnya.
- Penghitungan Deposit Pendaftaran: Fitur pencatatan deposit awal Rp 200.000,- dengan status pembayaran dan pencatatan tagihan akhir.

#### FR-03: Modul QR Code & OCR-AI Scanner (Loket)
- Pembuatan QR Code unik berformat UUID / Token Enkripsi untuk setiap nomor pendaftaran.
- Fitur Webcam Scanner di dashboard loket pendaftaran untuk memindai QR Code e-Tiket pasien dalam waktu < 1 detik.
- Fitur OCR-AI: Ekstraksi gambar KTP/dokumen menggunakan Tesseract.js / OCR Service yang dirapikan oleh AI Parser untuk otomatis mengisi *form field* pendaftaran baru tanpa ketik manual.

#### FR-04: Modul Pelayanan Poli & Pembatasan Edit (CPPT Lock)
- Perawat/Dokter mencatat diagnosa klinis awal.
- **Kunci Hak Edit Pendaftaran**: Begitu status pelayanan berubah menjadi `dalam_pemeriksaan` atau data CPPT mulai terisi, akses edit data di level loket pendaftaran terkunci secara otomatis. Segala bentuk revisi data demografi harus dialihkan melalui otorisasi Unit Rekam Medis.

#### FR-05: Modul Sensus Harian & Pelaporan Rekam Medis
- Rekapitulasi sensus harian mencakup:
  - Jumlah kunjungan Baru vs Lama.
  - Klasifikasi wilayah: Pasien Dalam Kota vs Pasien Luar Kota (berdasarkan kode wilayah / alamat).
  - Klasifikasi jenis kelamin: Laki-laki vs Perempuan.
  - Distribusi per Poli & Dokter Spesialis.
  - Laporan khusus poli anak (Neonatus vs Anak) dan poli kandungan (Obstetri vs Ginekologi).
- Ekspor laporan ke format `.xlsx` (Excel terformat rapi) dan `.pdf` (siap cetak).

#### FR-06: Modul Integrasi SATUSEHAT Kemenkes RI
- Autentikasi OAuth2 menggunakan `client_id` dan `client_secret` dari Kemenkes Sandbox/Production.
- Pemetaan Resource FHIR Kemenkes:
  - `Patient` (Pencarian & Verifikasi NIK / IHS Number).
  - `Encounter` (Pencatatan kontak rawat jalan & kedatangan).
  - `Condition` (Diagnosis ICD-10).
  - `Procedure` (Tindakan Medis ICD-9-CM).
- Mode Pengiriman Data: Manual Cross-Check (Staf Rekam Medis memverifikasi kelengkapan kode diagnosa sebelum menekan tombol "Kirim ke SATUSEHAT").
- Manajemen Accession Number untuk pemeriksaan penunjang/radiologi.

---

### 5. KEBUTUHAN NON-FUNGSIONAL (NON-FUNCTIONAL REQUIREMENTS)
- **Keamanan Data**: Enkripsi password menggunakan Bcrypt/Argon2, perlindungan CSRF Token, dan enkripsi data sensitif pasien.
- **Performa & Kecepatan**: Waktu respon pemindaian QR Code < 500 ms; proses OCR < 2 detik.
- **Kompatibilitas**: Web responsif (Bootstrap 5 / Tailwind CSS), dapat diakses melalui smartphone, tablet, maupun PC loket pendaftaran.
- **Arsitektur Perangkat Lunak**: Monolith MVC (PHP Laravel 10/11 / Python Flask / Node.js) dengan MySQL Database.
