<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Kolom tambahan tabel pasien
        Schema::table('pasien', function (Blueprint $table) {
            if (!Schema::hasColumn('pasien', 'no_kk')) {
                $table->string('no_kk', 20)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('pasien', 'nik_ibu')) {
                $table->string('nik_ibu', 20)->nullable()->after('nama_ibu');
            }
            if (!Schema::hasColumn('pasien', 'alamat_domisili')) {
                $table->text('alamat_domisili')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pasien', 'is_identitas_lengkap')) {
                $table->boolean('is_identitas_lengkap')->default(true)->after('status');
            }
        });

        // 2. Kolom tambahan tabel pendaftaran
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran', 'cara_bayar')) {
                $table->string('cara_bayar', 50)->default('Umum')->after('jenis_kunjungan');
            }
            if (!Schema::hasColumn('pendaftaran', 'status_wilayah')) {
                $table->string('status_wilayah', 30)->default('dalam_kota')->after('cara_bayar'); // dalam_kota / luar_kota
            }
            if (!Schema::hasColumn('pendaftaran', 'is_locked_by_poli')) {
                $table->boolean('is_locked_by_poli')->default(false)->after('status');
            }
        });

        // 3. Tabel Master ICD-9-CM (Prosedur / Tindakan Medis)
        if (!Schema::hasTable('icd9_codes')) {
            Schema::create('icd9_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->index();
                $table->string('name_id');
                $table->string('name_en')->nullable();
                $table->string('chapter_number', 20)->nullable();
                $table->string('chapter_name')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn(['no_kk', 'nik_ibu', 'alamat_domisili', 'is_identitas_lengkap']);
        });

        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['cara_bayar', 'status_wilayah', 'is_locked_by_poli']);
        });

        Schema::dropIfExists('icd9_codes');
    }
};
