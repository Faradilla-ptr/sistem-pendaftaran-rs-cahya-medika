<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';

    protected $fillable = [
        'satusehat_id',
        'nik',
        'nip',
        'str_number',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'spesialisasi',
        'poli_id',
        'jadwal', // JSON
        'foto',
        'is_active',
    ];

    protected $casts = [
        'jadwal' => 'array',
        'is_active' => 'boolean',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $nama = trim(($this->gelar_depan ? $this->gelar_depan . ' ' : '') . $this->nama);
        if ($this->gelar_belakang) {
            $nama .= ', ' . $this->gelar_belakang;
        }
        return $nama;
    }

    public function getJadwalHariIniAttribute(): ?array
    {
        $hari = strtolower(now()->locale('id')->dayName);
        return $this->jadwal[$hari] ?? null;
    }
}
