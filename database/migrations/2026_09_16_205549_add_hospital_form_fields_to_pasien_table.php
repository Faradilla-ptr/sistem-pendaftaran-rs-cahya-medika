<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('warga_negara')->nullable()->default('WNI')->after('kode_pos');
            $table->string('suku')->nullable()->default('Jawa')->after('warga_negara');
            $table->string('nama_ibu')->nullable()->after('suku');
            $table->string('nama_ayah')->nullable()->after('nama_ibu');
            $table->string('riwayat_alergi')->nullable()->default('Tidak Ada')->after('nama_ayah');
            $table->string('jenis_alergi')->nullable()->after('riwayat_alergi');
            $table->string('jenis_kelamin_pj')->nullable()->after('alamat_pj');
            $table->string('pekerjaan_pj')->nullable()->after('jenis_kelamin_pj');
            $table->string('kelurahan_pj')->nullable()->after('pekerjaan_pj');
            $table->string('kecamatan_pj')->nullable()->after('kelurahan_pj');
            $table->string('kabupaten_pj')->nullable()->after('kecamatan_pj');
            $table->string('provinsi_pj')->nullable()->after('kabupaten_pj');
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn([
                'warga_negara',
                'suku',
                'nama_ibu',
                'nama_ayah',
                'riwayat_alergi',
                'jenis_alergi',
                'jenis_kelamin_pj',
                'pekerjaan_pj',
                'kelurahan_pj',
                'kecamatan_pj',
                'kabupaten_pj',
                'provinsi_pj',
            ]);
        });
    }
};
