<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gachas extends Model
{
    use SoftDeletes;

    protected $table = 'gachas';

    protected $fillable = [
        'gacha_name',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function rarities()
    {
        return $this->hasMany(GachaRarity::class, 'gacha_id', 'id');
    }

    public function characters()
    {
        return $this->hasMany(Gacha_characters::class, 'gacha_id', 'id');
    }
}