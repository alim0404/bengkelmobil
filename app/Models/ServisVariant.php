<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServisVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servis_variants';
    
    protected $fillable = [
        'servis_mobil_id',
        'nama',
        'harga',
        'deskripsi',
    ];

    public function servisMobil(): BelongsTo
    {
        return $this->belongsTo(ServisMobil::class, 'servis_mobil_id');
    }
}