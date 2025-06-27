<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WabiGameProfile extends Model
{
    protected $table = 'wabi_game_profile';
    protected $fillable = [
        'user_id',
        'identifier', 
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
