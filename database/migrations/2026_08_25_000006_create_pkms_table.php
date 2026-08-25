<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkms', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->nullable();
            $table->foreignId('skema_bantuan_id')->nullable()->constrained('skema_bantuans')->nullOnDelete();
            $table->text('judul');
            $table->unsignedSmallInteger('tahun');
            $table->string('tautan_tagihan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('pkm_pengabdi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkm_id')->constrained('pkms')->cascadeOnDelete();
            $table->foreignId('peneliti_id')->constrained('penelitis')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->string('peran')->default('Ketua');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkm_pengabdi');
        Schema::dropIfExists('pkms');
    }
};
