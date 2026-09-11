<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matchround extends Model
{
    protected $table = 'ffb_matchround';

    protected $primaryKey = 'matchround_id';

    public $timestamps = false;

    protected $fillable = [
        'matchround_league_id',
        'matchround_title',
        'matchround_startdate',
        'matchround_enddate',
        'matchround_status',
        'matchround_credits',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'matchround_league_id', 'league_id');
    }

    public function options(): HasOne
    {
        return $this->hasOne(MatchroundOptions::class, 'matchround_options_matchround_id', 'matchround_id');
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
