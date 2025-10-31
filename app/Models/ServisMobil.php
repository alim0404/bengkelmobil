<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServisMobil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servis_mobil';
    protected $fillable = [
        'nama',
        'foto',
        'harga',
        'detail',
        'slug',
        'icon',
    ];

    public function servis(): HasMany
    {
        return $this->hasMany(BengkelServis::class, 'servis_mobil_id');
    }

    // Tambahkan relasi variants
    public function variants(): HasMany
    {
        return $this->hasMany(ServisVariant::class, 'servis_mobil_id');
    }

    // Helper method untuk cek apakah punya variant
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }
}