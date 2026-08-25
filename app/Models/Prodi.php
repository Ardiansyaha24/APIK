<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = ['fakultas_id', 'nama', 'jenjang', 'kode'];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function penelitis(): HasMany
    {
        return $this->hasMany(Peneliti::class);
    }
}
