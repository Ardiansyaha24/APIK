<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $table->string('nama');
            $table->string('jenjang', 10)->default('S1');
            $table->string('kode', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
        Schema::dropIfExists('fakultas');
    }
};
