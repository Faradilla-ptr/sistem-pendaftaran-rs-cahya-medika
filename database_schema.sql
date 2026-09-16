-- ============================================================================
-- SKEMA DATABASE LENGKAP SISTEM PENDAFTARAN RAWAT JALAN & REKAM MEDIS ELEKTRONIK
-- Berdasarkan Analisis Observasi & Wawancara Lapangan RSU/Klinik Cahya Medika
-- Peneliti: Faradilla Anastasia Putri (D4 Manajemen Informasi Kesehatan, Polije)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `cahya_medika_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cahya_medika_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `satusehat_logs`;
DROP TABLE IF EXISTS `pelayanan_poli`;
DROP TABLE IF EXISTS `pembayaran_deposit`;
DROP TABLE IF EXISTS `pendaftaran`;
DROP TABLE IF EXISTS `jadwal_dokter`;
DROP TABLE IF EXISTS `dokter`;
DROP TABLE IF EXISTS `poli`;
DROP TABLE IF EXISTS `pasien`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
-- 1. TABEL PENGGUNA & HAK AKSES (USERS)
-- ----------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'pendaftaran', 'rekam_medis', 'perawat', 'dokter', 'pasien') NOT NULL DEFAULT 'pasien',
  `no_hp` VARCHAR(20) NULL,
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. TABEL MASTER PASIEN (PASIEN)
-- Memuat identifikasi NIK, Bayi (NIK Ibu), Wilayah (Kemenkes), dan Flag Kelengkapan Identitas
-- ----------------------------------------------------------------------------
CREATE TABLE `pasien` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL COMMENT 'Terkoneksi jika pasien memiliki akun login mandiri',
  `no_rkm_medis` VARCHAR(15) NOT NULL UNIQUE COMMENT 'Format nomor RM unik 6-8 digit (cth: 00-01-24)',
  `no_ktp` VARCHAR(16) NULL UNIQUE COMMENT 'NIK Pasien (16 digit)',
  `no_kk` VARCHAR(16) NULL,
  `nama_pasien` VARCHAR(150) NOT NULL,
  `tempat_lahir` VARCHAR(100) NOT NULL,
  `tgl_lahir` DATE NOT NULL,
  `jenis_kelamin` ENUM('L', 'P') NOT NULL,
  `gol_darah` ENUM('A', 'B', 'AB', 'O', '-') DEFAULT '-',
  
  -- Logika Pasien Bayi (< 30 Hari) & Khusus
  `is_bayi` BOOLEAN NOT NULL DEFAULT FALSE,
  `nik_ibu` VARCHAR(16) NULL COMMENT 'Wajib diisi jika pasien adalah bayi baru lahir tanpa NIK mandiri',
  `nama_ibu` VARCHAR(150) NULL,
  `is_identitas_lengkap` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'False jika pasien darurat tanpa KTP/NIK saat pendaftaran',
  `catatan_identitas` TEXT NULL COMMENT 'Catatan identitas tidak lengkap untuk follow-up WA pendaftaran',

  -- Segmentasi Wilayah untuk Pelaporan Sensus Harian Kemenkes
  `alamat_ktp` TEXT NOT NULL,
  `alamat_domisili` TEXT NOT NULL COMMENT 'Alamat tempat tinggal saat ini jika berbeda dengan KTP',
  `kelurahan` VARCHAR(100) NULL,
  `kecamatan` VARCHAR(100) NULL,
  `kabupaten_kota` VARCHAR(100) NOT NULL,
  `provinsi` VARCHAR(100) NOT NULL,
  `status_wilayah` ENUM('dalam_kota', 'luar_kota') NOT NULL DEFAULT 'dalam_kota',

  `agama` ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya') DEFAULT 'Islam',
  `status_nikah` ENUM('Belum Menikah', 'Menikah', 'Janda', 'Duda') DEFAULT 'Belum Menikah',
  `pekerjaan` VARCHAR(100) NULL,
  `pendidikan` ENUM('Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'Diploma', 'Sarjana', 'Pascasarjana') DEFAULT 'SMA/SMK',
  `no_tlp` VARCHAR(20) NOT NULL COMMENT 'Nomor WhatsApp aktif untuk konfirmasi & notifikasi',
  
  -- Penanggung Jawab Pasien (Wajib untuk Anak & Lansia)
  `nama_penanggung_jawab` VARCHAR(150) NULL,
  `hubungan_penanggung_jawab` VARCHAR(50) NULL,
  `no_hp_penanggung_jawab` VARCHAR(20) NULL,

  -- Integrasi SATUSEHAT
  `ihs_number` VARCHAR(50) NULL UNIQUE COMMENT 'ID Pasien SATUSEHAT Kemenkes RI',

  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pasien_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. TABEL MASTER POLIKLINIK (POLI)
-- ----------------------------------------------------------------------------
CREATE TABLE `poli` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kode_poli` VARCHAR(10) NOT NULL UNIQUE,
  `nama_poli` VARCHAR(100) NOT NULL,
  `kategori_pelayanan` ENUM('umum', 'spesialis', 'anak_neonatal', 'obstetri_ginekologi', 'gigi', 'penunjang') NOT NULL DEFAULT 'spesialis',
  `satusehat_location_id` VARCHAR(100) NULL COMMENT 'Location Identifier Resource SATUSEHAT',
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. TABEL MASTER DOKTER (DOKTER)
-- ----------------------------------------------------------------------------
CREATE TABLE `dokter` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `kode_dokter` VARCHAR(20) NOT NULL UNIQUE,
  `nama_dokter` VARCHAR(150) NOT NULL,
  `sip` VARCHAR(50) NOT NULL,
  `spesialisasi` VARCHAR(100) NOT NULL,
  `poli_id` INT UNSIGNED NOT NULL,
  `no_hp` VARCHAR(20) NULL,
  `satusehat_practitioner_id` VARCHAR(100) NULL COMMENT 'IHS Practitioner ID Dokter',
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_dokter_poli` FOREIGN KEY (`poli_id`) REFERENCES `poli` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_dokter_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. TABEL JADWAL PRAKTIK & KUOTA DOKTER (JADWAL_DOKTER)
-- ----------------------------------------------------------------------------
CREATE TABLE `jadwal_dokter` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `dokter_id` INT UNSIGNED NOT NULL,
  `hari` ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu') NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  `kuota_maksimal` INT UNSIGNED NOT NULL DEFAULT 30 COMMENT 'Standar kuota poli 25-30 pasien/hari',
  `status_praktik` ENUM('aktif', 'libur', 'dibatalkan') NOT NULL DEFAULT 'aktif',
  `catatan_perubahan` VARCHAR(255) NULL COMMENT 'Catatan jika dokter berhalangan atau perubahan jam',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_jadwal_dokter` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 6. TABEL TRANSAKSI PENDAFTARAN RAWAT JALAN (PENDAFTARAN)
-- Memuat QR Code, status kunjungan baru/lama, keluhan, deposit, status CPPT Lock
-- ----------------------------------------------------------------------------
CREATE TABLE `pendaftaran` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `no_rawat` VARCHAR(25) NOT NULL UNIQUE COMMENT 'Format: YYYY/MM/DD/NNNNNN',
  `kode_booking` VARCHAR(36) NOT NULL UNIQUE COMMENT 'Kode UUID untuk QR Code e-Tiket',
  `tgl_registrasi` DATE NOT NULL,
  `jam_registrasi` TIME NOT NULL,
  `pasien_id` BIGINT UNSIGNED NOT NULL,
  `dokter_id` INT UNSIGNED NOT NULL,
  `poli_id` INT UNSIGNED NOT NULL,
  `no_antrean` INT UNSIGNED NOT NULL,
  `status_pasien` ENUM('Baru', 'Lama') NOT NULL,
  `status_wilayah` ENUM('dalam_kota', 'luar_kota') NOT NULL,
  `cara_bayar` ENUM('Umum', 'BPJS Kesehatan', 'Asuransi Swasta', 'Perusahaan') NOT NULL DEFAULT 'Umum',
  `keluhan_utama` TEXT NOT NULL,
  
  -- Status Pelayanan & Lock Hak Edit Loket
  `status_pelayanan` ENUM('menunggu', 'terverifikasi_loket', 'dalam_pemeriksaan', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu',
  `is_locked_by_poli` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'TRUE jika CPPT telah diisi di poli, mengunci akses edit loket pendaftaran',
  
  -- Identitas & Verifikasi Loket
  `metode_pendaftaran` ENUM('online_qr', 'online_web', 'onsite_loket') NOT NULL DEFAULT 'online_qr',
  `petugas_loket_id` BIGINT UNSIGNED NULL,
  `waktu_verifikasi_loket` DATETIME NULL,

  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pendaftaran_pasien` FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pendaftaran_dokter` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pendaftaran_poli` FOREIGN KEY (`poli_id`) REFERENCES `poli` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pendaftaran_petugas` FOREIGN KEY (`petugas_loket_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. TABEL DEPOSIT & PEMBAYARAN PENDAFTARAN (PEMBAYARAN_DEPOSIT)
-- Sesuai SOP deposit rawat jalan Rp 200.000,- dengan pengembalian sisa/pelunasan
-- ----------------------------------------------------------------------------
CREATE TABLE `pembayaran_deposit` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `pendaftaran_id` BIGINT UNSIGNED NOT NULL,
  `no_kwitansi` VARCHAR(50) NOT NULL UNIQUE,
  `nominal_deposit` DECIMAL(12,2) NOT NULL DEFAULT 200000.00,
  `total_tagihan_akhir` DECIMAL(12,2) NULL,
  `selisih_pembayaran` DECIMAL(12,2) NULL COMMENT 'Positif: Pasien bayar kekurangan; Negatif: Uang dikembalikan ke pasien',
  `status_deposit` ENUM('dibayar', 'selesai_pelunasan', 'dikembalikan') NOT NULL DEFAULT 'dibayar',
  `kasir_user_id` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_deposit_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_deposit_kasir` FOREIGN KEY (`kasir_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 8. TABEL PELAYANAN MEDIS POLI & CPPT (PELAYANAN_POLI)
-- Pengisian diagnosa, tindakan, neonatal/obstetri, dan penguncian pendaftaran
-- ----------------------------------------------------------------------------
CREATE TABLE `pelayanan_poli` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `pendaftaran_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `subjektif_anamnesa` TEXT NOT NULL,
  `objektif_pemeriksaan` TEXT NOT NULL,
  `tensi_darah` VARCHAR(20) NULL,
  `suhu_tubuh` DECIMAL(4,1) NULL,
  `berat_badan` DECIMAL(5,2) NULL,
  `tinggi_badan` DECIMAL(5,2) NULL,
  
  -- Kategori Layanan Spesifik Sensus
  `kategori_layanan_khusus` ENUM('Neonatal', 'Anak', 'Obstetri', 'Ginekologi', 'Umum/Lainnya') DEFAULT 'Umum/Lainnya',
  
  -- Koding Rekam Medis (ICD-10 & ICD-9-CM)
  `kode_diagnosa_icd10` VARCHAR(10) NULL COMMENT 'Kode Primer Diagnosa (cth: J00, E11.9)',
  `nama_diagnosa` VARCHAR(255) NULL,
  `kode_tindakan_icd9cm` VARCHAR(10) NULL COMMENT 'Kode Prosedur Tindakan (cth: 89.07)',
  `nama_tindakan` VARCHAR(255) NULL,
  `resep_obat` TEXT NULL,
  
  `dokter_id` INT UNSIGNED NOT NULL,
  `perawat_id` BIGINT UNSIGNED NULL,
  `waktu_pemeriksaan_selesai` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pelayanan_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pelayanan_dokter` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pelayanan_perawat` FOREIGN KEY (`perawat_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 9. TABEL LOG & INTEROPERABILITAS SATUSEHAT KEMENKES (SATUSEHAT_LOGS)
-- Memuat status 13 resource FHIR, Encounter, Condition, dan Accession Number
-- ----------------------------------------------------------------------------
CREATE TABLE `satusehat_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `pendaftaran_id` BIGINT UNSIGNED NOT NULL,
  `resource_type` ENUM('Encounter', 'Condition', 'Procedure', 'Observation', 'MedicationRequest', 'DiagnosticReport') NOT NULL,
  `accession_number` VARCHAR(100) NULL COMMENT 'Kode Unik Pengiriman Data Radiologi / DICOM',
  `satusehat_id` VARCHAR(100) NULL COMMENT 'ID Respon dari Server Kemenkes',
  `http_status_code` INT NULL,
  `payload_request` JSON NOT NULL,
  `response_body` JSON NULL,
  `status_kirim` ENUM('draft', 'siap_verifikasi', 'terkirim', 'gagal') NOT NULL DEFAULT 'draft',
  `verifikator_rm_id` BIGINT UNSIGNED NULL COMMENT 'Staf Rekam Medis yang melakukan manual cross-check',
  `waktu_kirim` DATETIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_satusehat_pendaftaran` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_satusehat_verifikator` FOREIGN KEY (`verifikator_rm_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 10. TABEL AUDIT LOG (PERUBAHAN DATA MASTER PASIEN & TRANSAKSI)
-- ----------------------------------------------------------------------------
CREATE TABLE `audit_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `aksi` VARCHAR(100) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, MERGE_RM, OVERRIDE_LOCK',
  `tabel_terkait` VARCHAR(50) NOT NULL,
  `record_id` BIGINT UNSIGNED NOT NULL,
  `data_lama` JSON NULL,
  `data_baru` JSON NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- DATA AWAL / SEEDER AWAL (INITIAL MASTER DATA)
-- ----------------------------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `no_hp`) VALUES
(1, 'admin', 'Administrator IT', 'it@cahyamedika.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '081234567890'),
(2, 'sania', 'Sania Amd.RMIK (Staf RM)', 'sania@cahyamedika.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rekam_medis', '081234567891'),
(3, 'loket1', 'Sharon (Petugas Loket)', 'loket@cahyamedika.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pendaftaran', '081234567892'),
(4, 'dr_uri', 'dr. Uri Spesialis Penyakit Dalam', 'dr.uri@cahyamedika.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dokter', '081234567893'),
(5, 'perawat_poli', 'Suster Ningsih Amd.Kep', 'ningsih@cahyamedika.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'perawat', '081234567894');

INSERT INTO `poli` (`id`, `kode_poli`, `nama_poli`, `kategori_pelayanan`) VALUES
(1, 'POL-INT', 'Poli Penyakit Dalam', 'spesialis'),
(2, 'POL-ANA', 'Poli Anak & Neonatal', 'anak_neonatal'),
(3, 'POL-OBG', 'Poli Kandungan (Obsgyn)', 'obstetri_ginekologi'),
(4, 'POL-UMU', 'Poli Umum', 'umum'),
(5, 'POL-RAD', 'Unit Radiologi & Diagnostik', 'penunjang');

INSERT INTO `dokter` (`id`, `user_id`, `kode_dokter`, `nama_dokter`, `sip`, `spesialisasi`, `poli_id`, `no_hp`) VALUES
(1, 4, 'DOC-001', 'dr. Uri Sp.PD', 'SIP.449/123/DS/2024', 'Spesialis Penyakit Dalam', 1, '081122334455'),
(2, NULL, 'DOC-002', 'dr. Maya Sp.A', 'SIP.449/124/DS/2024', 'Spesialis Anak', 2, '081122334466'),
(3, NULL, 'DOC-003', 'dr. Hendra Sp.OG', 'SIP.449/125/DS/2024', 'Spesialis Obstetri & Ginekologi', 3, '081122334477');

INSERT INTO `jadwal_dokter` (`dokter_id`, `hari`, `jam_mulai`, `jam_selesai`, `kuota_maksimal`, `status_praktik`) VALUES
(1, 'Selasa', '08:00:00', '12:00:00', 30, 'aktif'),
(1, 'Kamis', '08:00:00', '12:00:00', 30, 'aktif'),
(2, 'Senin', '09:00:00', '13:00:00', 25, 'aktif'),
(3, 'Rabu', '08:00:00', '12:00:00', 25, 'aktif');

INSERT INTO `pasien` (`id`, `no_rkm_medis`, `no_ktp`, `no_kk`, `nama_pasien`, `tempat_lahir`, `tgl_lahir`, `jenis_kelamin`, `alamat_ktp`, `alamat_domisili`, `kabupaten_kota`, `provinsi`, `status_wilayah`, `no_tlp`) VALUES
(1, '00-00-01', '3511012005980001', '3511012005980000', 'Budi Santoso', 'Bondowoso', '1998-05-20', 'L', 'Jl. PB Sudirman No. 12, Bondowoso', 'Jl. PB Sudirman No. 12, Bondowoso', 'Bondowoso', 'Jawa Timur', 'dalam_kota', '081234567801'),
(2, '00-00-02', '3509015508000002', '3509015508000000', 'Siti Aminah', 'Jember', '2000-08-15', 'P', 'Jl. Kalimantan No. 45, Jember', 'Jl. Letjen Panjaitan No. 8, Bondowoso', 'Jember', 'Jawa Timur', 'luar_kota', '081234567802'),
(3, '00-00-03', NULL, '3511012005980000', 'By. Ny. Siti Aminah', 'Bondowoso', CURDATE(), 'L', 'Jl. Letjen Panjaitan No. 8, Bondowoso', 'Jl. Letjen Panjaitan No. 8, Bondowoso', 'Bondowoso', 'Jawa Timur', 'dalam_kota', '081234567802');

UPDATE `pasien` SET `is_bayi` = TRUE, `nik_ibu` = '3509015508000002', `nama_ibu` = 'Siti Aminah' WHERE `id` = 3;
