<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function userCharacters()
    {
        return $this->hasMany(UserCharacter::class, 'user_id', 'id');
    }
}