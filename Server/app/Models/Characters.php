<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Characters extends Model
{
    use SoftDeletes;

    protected $table = 'characters';

    protected $fillable = [
        'character_name',
        'rarity',
        'attack',
        'hit_point',
    ];

    protected $casts = [
        'rarity' => 'integer',
    ];

    public function userCharacters()
    {
        return $this->hasMany(User_characters::class, 'character_id', 'id');
    }
}
