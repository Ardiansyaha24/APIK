<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Publikasi extends Model
{
    use HasFactory;

    protected $table = 'publikasis';
    protected $fillable = [
        'jenis_publikasi',
        'kategori_indeks',
        'judul',
        'tahun',
        'nama_jurnal_prosiding',
        'tautan_doi',
        'file_path',
    ];

    public function penuliss(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'publikasi_penulis')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
