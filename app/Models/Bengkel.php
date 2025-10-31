<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bengkel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bengkel';
    protected $fillable = [
        'nama',
        'slug',
        'gambar_pratinjau',
        'status_operasional',
        'status_kapasitas',
        'kota_id',
        'alamat',
        'nomer_telepon',
        'nama_cs',
    ];

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }


    public function servis(): HasMany
    {
        return $this->hasMany(BengkelServis::class, 'bengkel_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(FotoBengkel::class, 'bengkel_id');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}