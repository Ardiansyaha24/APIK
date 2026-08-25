<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_publikasi', ['jurnal', 'prosiding'])->default('jurnal');
            $table->string('kategori_indeks');
            $table->text('judul');
            $table->unsignedSmallInteger('tahun');
            $table->string('nama_jurnal_prosiding');
            $table->string('tautan_doi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        Schema::create('publikasi_penulis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publikasi_id')->constrained('publikasis')->cascadeOnDelete();
            $table->foreignId('peneliti_id')->constrained('penelitis')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->string('peran')->default('Penulis Pertama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_penulis');
        Schema::dropIfExists('publikasis');
    }
};
