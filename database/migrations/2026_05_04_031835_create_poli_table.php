<?php
// 2024_01_01_000002_create_poli_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poli', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('lantai')->nullable();
            $table->string('jam_buka', 5)->default('07:00');
            $table->string('jam_tutup', 5)->default('16:00');
            $table->string('icon')->nullable()->default('🏥');
            $table->string('warna')->nullable()->default('#0891b2');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poli');
    }
};
