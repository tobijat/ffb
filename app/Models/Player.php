<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $table = 'ffb_player';

    protected $primaryKey = 'player_id';

    public $timestamps = false;

    protected $fillable = [
        'player_foreign_id',
        'player_fname',
        'player_lname',
        'player_nationality',
        'player_status',
        'player_status_description',
        'player_commons_image',
    ];

    public function playerteams(): HasMany
    {
        return $this->hasMany(Playerteam::class, 'playerteam_player_id', 'player_id');
    }
}
