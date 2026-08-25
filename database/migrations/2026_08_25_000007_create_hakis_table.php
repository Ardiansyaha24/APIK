<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hakis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_hki')->nullable();
            $table->string('pemegang_hak_cipta')->nullable();
            $table->string('jenis_ciptaan');
            $table->text('judul_ciptaan');
            $table->unsignedSmallInteger('tahun');
            $table->string('tautan_tagihan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('haki_pencipta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('haki_id')->constrained('hakis')->cascadeOnDelete();
            $table->foreignId('peneliti_id')->constrained('penelitis')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('haki_pencipta');
        Schema::dropIfExists('hakis');
    }
};
