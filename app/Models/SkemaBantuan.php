<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkemaBantuan extends Model
{
    use HasFactory;

    protected $table = 'skema_bantuans';
    protected $fillable = ['nama', 'jenis', 'keterangan'];

    public function penelitians(): HasMany
    {
        return $this->hasMany(Penelitian::class);
    }

    public function pkms(): HasMany
    {
        return $this->hasMany(Pkm::class);
    }
}
