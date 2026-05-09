<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('kode_provinsi',  10)->nullable()->after('provinsi');
            $table->string('kode_kabupaten', 10)->nullable()->after('kabupaten');
            $table->string('kode_kecamatan', 10)->nullable()->after('kecamatan');
            $table->string('kode_kelurahan', 10)->nullable()->after('kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn(['kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'kode_kelurahan']);
        });
    }
};
