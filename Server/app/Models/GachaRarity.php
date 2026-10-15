<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GachaRarity extends Model
{
    use SoftDeletes;

    protected $table = 'gacha_rarities';

    protected $fillable = [
        'gacha_id',
        'rarity',
        'draw_rate',
        'pickup_rate',
    ];

    protected $casts = [
        'draw_rate' => 'integer',
        'pickup_rate' => 'integer',
    ];
}