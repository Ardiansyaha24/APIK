<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitians';
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

    public function penelitis(): BelongsToMany
    {
        return $this->belongsToMany(Peneliti::class, 'penelitian_peneliti')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
