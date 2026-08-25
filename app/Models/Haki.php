<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Haki extends Model
{
    use HasFactory;

    protected $table = 'hakis';
    protected $fillable = [
        'nomor_hki',
        'pemegang_hak_cipta',
        'jenis_ciptaan',
        'judul_ciptaan',
        'tahun',
        'tautan_tagihan',
        'file_path',
    ];

    public function penciptas(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'haki_pencipta')
            ->withPivot(['urutan'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
