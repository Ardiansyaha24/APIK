<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peneliti extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'nidn',
        'prodi_id',
        'email',
        'no_hp',
        'foto_path',
        'bidang_keahlian',
        'sinta_id',
        'scopus_id',
        'gscholar_id',
        'orcid_id',
        'status',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function penelitians(): BelongsToMany
    {
        return $this->belongsToMany(Penelitian::class, 'penelitian_peneliti')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }

    public function bukus(): BelongsToMany
    {
        return $this->belongsToMany(Buku::class, 'buku_penulis')
            ->withPivot(['urutan'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }

    public function pkms(): BelongsToMany
    {
        return $this->belongsToMany(Pkm::class, 'pkm_pengabdi')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }

    public function hakis(): BelongsToMany
    {
        return $this->belongsToMany(Haki::class, 'haki_pencipta')
            ->withPivot(['urutan'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }

    public function publikasis(): BelongsToMany
    {
        return $this->belongsToMany(Publikasi::class, 'publikasi_penulis')
            ->withPivot(['urutan', 'peran'])
            ->withTimestamps()
            ->orderByPivot('urutan');
    }
}
