<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $fillable = [
        'nomor',
        'nama_penerbit',
        'judul',
        'tahun',
        'isbn',
        'file_path',
        'tautan',
    ];

    public function penuliss(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'buku_penulis')
            ->withPivot(['urutan'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
