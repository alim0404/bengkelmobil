<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BengkelServis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bengkel_servis';

    protected $fillable = [
        'servis_mobil_id',
        'bengkel_id',
    ];

    public function bengkel(): BelongsTo
    {
        return $this->belongsTo(Bengkel::class, 'bengkel_id');
    }

    public function servismobil(): BelongsTo
    {
        return $this->belongsTo(ServisMobil::class, 'servis_mobil_id');
    }
}
