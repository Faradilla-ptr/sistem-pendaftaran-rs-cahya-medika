<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('icd10_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->index();
            $table->string('name_id');
            $table->string('name_en')->nullable();
            $table->string('chapter_number', 10)->index(); // e.g. "Bab I", "Bab II"
            $table->string('chapter_name')->index();   // e.g. "Penyakit Infeksi dan Parasit"
            $table->string('block_range', 30)->nullable(); // e.g. "A00-B99"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icd10_codes');
    }
};
