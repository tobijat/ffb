<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playerteam extends Model
{
    protected $table = 'ffb_playerteam';

    protected $primaryKey = 'playerteam_id';

    public $timestamps = false;

    protected $fillable = [
        'playerteam_player_id',
        'playerteam_team_id',
        'playerteam_league_id',
        'playerteam_player_picture',
        'playerteam_status',
        'playerteam_player_position',
        'playerteam_date_transfer',
    ];

    /**
     * @param  Builder<Playerteam>  $query
     * @return Builder<Playerteam>
     */
    public function scopeForLeague(Builder $query, int $leagueId): Builder
    {
        return $query->where('playerteam_league_id', $leagueId);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'playerteam_player_id', 'player_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'playerteam_team_id', 'team_id');
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'playerteam_league_id', 'league_id');
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
