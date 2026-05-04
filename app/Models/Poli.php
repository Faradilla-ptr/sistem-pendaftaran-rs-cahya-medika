<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'poli';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'lantai',
        'jam_buka',
        'jam_tutup',
        'icon',
        'warna',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dokter()
    {
        return $this->hasMany(Dokter::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
