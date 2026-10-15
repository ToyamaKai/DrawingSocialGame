<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gacha_characters extends Model
{
    use SoftDeletes;

    protected $table = 'gacha_characters';

    protected $fillable = [
        'gacha_id',
        'character_id',
        'is_pickup',
    ];

    protected $casts = [
        'is_pickup' => 'boolean',
    ];

    public function character()
    {
        return $this->belongsTo(Characters::class, 'character_id', 'id');
    }
}