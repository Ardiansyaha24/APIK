<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penelitis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nidn')->unique()->nullable();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('foto_path')->nullable();
            $table->text('bidang_keahlian')->nullable();
            $table->string('sinta_id')->nullable();
            $table->string('scopus_id')->nullable();
            $table->string('gscholar_id')->nullable();
            $table->string('orcid_id')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif', 'purna_tugas'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penelitis');
    }
};
