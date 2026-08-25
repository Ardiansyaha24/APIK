<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->nullable();
            $table->string('nama_penerbit')->nullable();
            $table->text('judul');
            $table->unsignedSmallInteger('tahun');
            $table->string('isbn', 50)->nullable();
            $table->string('file_path')->nullable();
            $table->string('tautan')->nullable();
            $table->timestamps();
        });

        Schema::create('buku_penulis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
            $table->foreignId('peneliti_id')->constrained('penelitis')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_penulis');
        Schema::dropIfExists('bukus');
    }
};
