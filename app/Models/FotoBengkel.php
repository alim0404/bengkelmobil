<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoBengkel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'foto_bengkel';
    protected $fillable = [
        'foto',
        'bengkel_id',
    ];
}