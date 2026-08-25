<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pkm extends Model
{
    use HasFactory;

    protected $table = 'pkms';
    protected $fillable = [
        'nomor',
        'skema_bantuan_id',
        'judul',
        'tahun',
        'tautan_tagihan',
        'file_path',
    ];

    public function skemaBantuan(): BelongsTo
    {
        return $this->belongsTo(SkemaBantuan::class, 'skema_bantuan_id');
    }

    public function pengabdis(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'pkm_pengabdi')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
