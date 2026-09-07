<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matchround extends Model
{
    protected $table = 'ffb_matchround';

    protected $primaryKey = 'matchround_id';

    public $timestamps = false;

    protected $fillable = [
        'matchround_game_id',
        'matchround_title',
        'matchround_startdate',
        'matchround_enddate',
        'matchround_status',
        'matchround_credits',
        'matchround_max_players_from_team',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'matchround_game_id', 'game_id');
    }

    public function userteams(): HasMany
    {
        return $this->hasMany(Userteam::class, 'userteam_matchround_id', 'matchround_id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchGame::class, 'match_round', 'matchround_id');
    }

    public function playerstats(): HasMany
    {
        return $this->hasMany(Playerstats::class, 'playerstats_matchround_id', 'matchround_id');
    }
}
