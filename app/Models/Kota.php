<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str as SupportStr;
use App\Models\Bengkel;
use Illuminate\Support\Str;

class Kota extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kota';

    protected $fillable = [
        'nama',
        'slug',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function bengkel(): HasMany
    {
        return $this->hasMany(Bengkel::class);
    }
}