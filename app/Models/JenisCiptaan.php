<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCiptaan extends Model
{
    use HasFactory;

    protected $table = 'jenis_ciptaans';
    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
