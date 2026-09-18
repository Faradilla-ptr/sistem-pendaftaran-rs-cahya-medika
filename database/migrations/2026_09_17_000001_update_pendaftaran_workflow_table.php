<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran', 'deposit_awal')) {
                $table->decimal('deposit_awal', 12, 2)->default(200000.00)->after('biaya_konsultasi');
            }
            if (!Schema::hasColumn('pendaftaran', 'biaya_total')) {
                $table->decimal('biaya_total', 12, 2)->default(0.00)->after('deposit_awal');
            }
            if (!Schema::hasColumn('pendaftaran', 'sisa_deposit')) {
                $table->decimal('sisa_deposit', 12, 2)->default(0.00)->after('biaya_total');
            }
            if (!Schema::hasColumn('pendaftaran', 'jarak_km')) {
                $table->string('jarak_km')->nullable()->after('sisa_deposit');
            }
            if (!Schema::hasColumn('pendaftaran', 'estimasi_menit')) {
                $table->integer('estimasi_menit')->nullable()->after('jarak_km');
            }
            if (!Schema::hasColumn('pendaftaran', 'qr_code_data')) {
                $table->text('qr_code_data')->nullable()->after('estimasi_menit');
            }
            if (!Schema::hasColumn('pendaftaran', 'is_checkin')) {
                $table->boolean('is_checkin')->default(false)->after('qr_code_data');
            }
            if (!Schema::hasColumn('pendaftaran', 'waktu_checkin')) {
                $table->timestamp('waktu_checkin')->nullable()->after('is_checkin');
            }
        });

        // Modify status column enum to include new workflow statuses
        DB::statement("ALTER TABLE pendaftaran MODIFY COLUMN status ENUM('terdaftar_online', 'menunggu', 'dipanggil', 'pemeriksaan_selesai', 'proses_rekam_medis', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu'");
        DB::statement("ALTER TABLE pendaftaran MODIFY COLUMN satusehat_status ENUM('pending', 'in_progress', 'success', 'failed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'deposit_awal',
                'biaya_total',
                'sisa_deposit',
                'jarak_km',
                'estimasi_menit',
                'qr_code_data',
                'is_checkin',
                'waktu_checkin'
            ]);
        });
    }
};
