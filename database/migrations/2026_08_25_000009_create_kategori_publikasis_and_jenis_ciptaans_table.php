<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['jurnal', 'prosiding', 'keduanya'])->default('jurnal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('jenis_ciptaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Seed default Kategori Publikasi
        $kategoriJurnal = [
            'Jurnal Internasional Bereputasi (Scopus Q1)',
            'Jurnal Internasional Bereputasi (Scopus Q2)',
            'Jurnal Internasional Bereputasi (Scopus Q3)',
            'Jurnal Internasional Bereputasi (Scopus Q4)',
            'Jurnal Internasional Terindeks (DOAJ/Copernicus/dll)',
            'Jurnal Nasional Terakreditasi (SINTA 1)',
            'Jurnal Nasional Terakreditasi (SINTA 2)',
            'Jurnal Nasional Terakreditasi (SINTA 3)',
            'Jurnal Nasional Terakreditasi (SINTA 4)',
            'Jurnal Nasional Terakreditasi (SINTA 5)',
            'Jurnal Nasional Terakreditasi (SINTA 6)',
            'Jurnal Nasional Tidak Terakreditasi',
        ];
        foreach ($kategoriJurnal as $nama) {
            DB::table('kategori_publikasis')->insert([
                'nama' => $nama,
                'jenis' => 'jurnal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $kategoriProsiding = [
            'Prosiding Internasional Terindeks (Scopus/IEEE/AIP/dll)',
            'Prosiding Internasional Tidak Terindeks',
            'Prosiding Nasional Terindeks',
            'Prosiding Seminar Nasional',
        ];
        foreach ($kategoriProsiding as $nama) {
            DB::table('kategori_publikasis')->insert([
                'nama' => $nama,
                'jenis' => 'prosiding',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed default Jenis Ciptaan UU 28/2014
        $jenisCiptaan = [
            'Buku, pamflet, dan semua hasil karya tulis lainnya',
            'Ceramah, kuliah, pidato, dan ciptaan sejenis',
            'Alat peraga untuk pendidikan dan ilmu pengetahuan',
            'Lagu dan/atau musik dengan atau tanpa teks',
            'Drama, drama musikal, tari, koreografi, pewayangan, pantomim',
            'Karya seni rupa (lukisan, gambar, ukiran, kaligrafi, patung)',
            'Karya seni terapan',
            'Karya arsitektur',
            'Peta',
            'Karya seni batik atau seni motif lain',
            'Karya fotografi',
            'Potret',
            'Karya sinematografi',
            'Terjemahan, tafsir, saduran, bunga rampai, basis data',
            'Kompilasi ciptaan atau data',
            'Permainan video',
            'Program komputer / Aplikasi Perangkat Lunak',
        ];
        foreach ($jenisCiptaan as $nama) {
            DB::table('jenis_ciptaans')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_ciptaans');
        Schema::dropIfExists('kategori_publikasis');
    }
};
