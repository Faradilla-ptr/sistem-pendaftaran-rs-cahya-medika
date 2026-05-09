<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien';

    protected $fillable = [
        'user_id',
        'no_rm',
        'nik',
        'nama_lengkap',
        'nama_panggilan',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'agama',
        'status_pernikahan',
        'pekerjaan',
        'pendidikan',
        'no_telepon',
        'no_hp',
        'email',
        'alamat',
        'kelurahan',
        'kode_kelurahan',
        'kecamatan',
        'kode_kecamatan',
        'kabupaten',
        'kode_kabupaten',
        'provinsi',
        'kode_provinsi',
        'kode_pos',
        // Kontak Darurat
        'nama_pj',
        'hubungan_pj',
        'no_hp_pj',
        'alamat_pj',
        // SatuSehat
        'satusehat_id',
        'satusehat_ihs_number',
        // Status
        'status', // aktif, nonaktif
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    protected static function booted()
    {
        static::creating(function ($pasien) {
            if (!$pasien->no_rm) {
                $pasien->no_rm = self::generateNoRM();
            }
        });
    }

    public static function generateNoRM(): string
    {
        $year = date('Y');
        $lastRM = self::where('no_rm', 'like', "CM{$year}%")->latest('id')->first();

        if ($lastRM) {
            $lastNum = (int) substr($lastRM->no_rm, -5);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }

        return 'CM' . $year . str_pad($newNum, 5, '0', STR_PAD_LEFT);
    }
}
