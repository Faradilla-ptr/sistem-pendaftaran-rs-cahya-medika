<?php
// 2024_01_01_000005_create_pendaftaran_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->integer('no_antrian')->default(0);
            $table->string('kode_booking', 20)->unique();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokter')->onDelete('cascade');
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->string('jam_kunjungan', 5);
            $table->enum('jenis_kunjungan', ['baru', 'kontrol'])->default('baru');
            $table->text('keluhan');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->decimal('biaya_konsultasi', 12, 2)->default(0);
            // SatuSehat
            $table->string('satusehat_encounter_id')->nullable();
            $table->json('satusehat_response')->nullable();
            $table->enum('satusehat_status', ['pending', 'success', 'failed'])->default('pending');
            // Tanda Vital
            $table->string('tekanan_darah')->nullable();
            $table->string('suhu')->nullable();
            $table->string('nadi')->nullable();
            $table->string('respirasi')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('spo2')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
