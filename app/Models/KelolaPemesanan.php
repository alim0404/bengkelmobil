<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notifiable;
use illuminate\Database\Eloquent\Attributes\ObserverdBy;
use App\Observers\NewbookingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class KelolaPemesanan extends Model
{
    use HasFactory, SoftDeletes;
    use HasFactory, Notifiable;


    protected $table = 'kelola_pemesanan';
    protected $fillable = [
        'nama',
        'trx_id',
        'nomer_telepon',
        'slug',
        'thumbnail',
        'bukti',
        'total_bayar',
        'status_pembayaran',
        'rating',
        'komentar',
        'waktu_mulai',
        'jam_mulai',
        'catatan',
        'servis_mobil_id',
        'servis_variant_id',
        'bengkel_id',
    ];

    protected $casts = [
        'waktu_mulai' => 'date'
    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'BM';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('trx_id', $randomString)->exists());

        return $randomString;
    }


    public function service_details(): BelongsTo
    {
        return $this->belongsTo(ServisMobil::class, 'servis_mobil_id');
    }
    public function store_details(): BelongsTo
    {
        return $this->belongsTo(Bengkel::class, 'bengkel_id');
    }

    // Tambahkan relasi variant
    public function variant_details(): BelongsTo
    {
        return $this->belongsTo(ServisVariant::class, 'servis_variant_id');
    }
}
