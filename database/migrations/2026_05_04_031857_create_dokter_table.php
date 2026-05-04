<?php
// 2024_01_01_000003_create_dokter_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->string('satusehat_id')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nip')->nullable();
            $table->string('str_number')->nullable();
            $table->string('nama');
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('spesialisasi');
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');
            $table->json('jadwal')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
