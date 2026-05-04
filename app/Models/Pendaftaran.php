<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'no_antrian',
        'kode_booking',
        'pasien_id',
        'dokter_id',
        'poli_id',
        'tanggal_kunjungan',
        'jam_kunjungan',
        'jenis_kunjungan', // baru, kontrol
        'keluhan',
        'status', // menunggu, dipanggil, selesai, batal
        'catatan_admin',
        'biaya_konsultasi',
        // SatuSehat
        'satusehat_encounter_id',
        'satusehat_response',
        'satusehat_status', // pending, success, failed
        // Tanda Vital (diisi saat kunjungan)
        'tekanan_darah',
        'suhu',
        'nadi',
        'respirasi',
        'berat_badan',
        'tinggi_badan',
        'spo2',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'satusehat_response' => 'array',
        'biaya_konsultasi' => 'decimal:2',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'dipanggil' => 'Dipanggil',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'warning',
            'dipanggil' => 'info',
            'selesai' => 'success',
            'batal' => 'danger',
            default => 'secondary',
        };
    }

    protected static function booted()
    {
        static::creating(function ($pendaftaran) {
            if (!$pendaftaran->kode_booking) {
                $pendaftaran->kode_booking = self::generateKodeBooking();
            }
            if (!$pendaftaran->no_antrian) {
                $pendaftaran->no_antrian = self::generateNoAntrian(
                    $pendaftaran->poli_id,
                    $pendaftaran->tanggal_kunjungan
                );
            }
        });
    }

    public static function generateKodeBooking(): string
    {
        return 'CM' . strtoupper(substr(uniqid(), -8));
    }

    public static function generateNoAntrian(int $poliId, string $tanggal): int
    {
        return self::where('poli_id', $poliId)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->whereNotIn('status', ['batal'])
            ->count() + 1;
    }
}
