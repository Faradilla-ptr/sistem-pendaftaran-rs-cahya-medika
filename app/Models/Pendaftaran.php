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
        'status', // terdaftar_online, menunggu, dipanggil, pemeriksaan_selesai, proses_rekam_medis, selesai, batal
        'catatan_admin',
        'biaya_konsultasi',
        'deposit_awal',
        'biaya_total',
        'sisa_deposit',
        'jarak_km',
        'estimasi_menit',
        'qr_code_data',
        'is_checkin',
        'waktu_checkin',
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
        'deposit_awal' => 'decimal:2',
        'biaya_total' => 'decimal:2',
        'sisa_deposit' => 'decimal:2',
        'is_checkin' => 'boolean',
        'waktu_checkin' => 'datetime',
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
            'terdaftar_online' => 'Terdaftar Online',
            'menunggu' => 'Menunggu Antrean',
            'dipanggil' => 'Dipanggil Dokter',
            'pemeriksaan_selesai' => 'Pemeriksaan Selesai',
            'proses_rekam_medis' => 'Proses Rekam Medis',
            'selesai' => 'Pemeriksaan Selesai (Final)',
            'batal' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'terdaftar_online' => 'info',
            'menunggu' => 'warning',
            'dipanggil' => 'primary',
            'pemeriksaan_selesai' => 'secondary',
            'proses_rekam_medis' => 'warning',
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
        });
    }

    public static function generateKodeBooking(): string
    {
        return 'CM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }

    public static function generateNoAntrian(int $poliId, string $tanggal): int
    {
        return self::where('poli_id', $poliId)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->where('no_antrian', '>', 0)
            ->whereNotIn('status', ['batal'])
            ->count() + 1;
    }
}
