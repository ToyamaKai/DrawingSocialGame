<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_characters extends Model
{
    use SoftDeletes;

    protected $table = 'user_characters';

    protected $fillable = [
        'user_id',
        'character_id',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function character()
    {
        return $this->belongsTo(Characters::class, 'character_id', 'id');
    }
}
