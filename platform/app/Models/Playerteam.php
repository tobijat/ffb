<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playerteam extends Model
{
    protected $table = 'ffb_playerteam';

    protected $primaryKey = 'playerteam_id';

    public $timestamps = false;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'playerteam_player_id', 'player_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'playerteam_team_id', 'team_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Playerprice::class, 'playerprice_playerteam_id', 'playerteam_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany(Playerstats::class, 'playerstats_playerteam_id', 'playerteam_id');
    }
}
