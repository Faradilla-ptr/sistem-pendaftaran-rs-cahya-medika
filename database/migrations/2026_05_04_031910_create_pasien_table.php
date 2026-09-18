<?php
// 2024_01_01_000004_create_pasien_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('no_rm', 20)->unique();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('golongan_darah', 20)->nullable();
            $table->string('agama')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();
            // Penanggung Jawab
            $table->string('nama_pj')->nullable();
            $table->string('hubungan_pj')->nullable();
            $table->string('no_hp_pj')->nullable();
            $table->text('alamat_pj')->nullable();
            // SatuSehat
            $table->string('satusehat_id')->nullable();
            $table->string('satusehat_ihs_number')->nullable();
            // Status
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
