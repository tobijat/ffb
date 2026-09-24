<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teamelo extends Model
{
    protected $table = 'ffb_teamelo';

    protected $primaryKey = 'teamelo_id';

    public $timestamps = false;

    protected $fillable = [
        'teamelo_team_id',
        'teamelo_league_id',
        'teamelo_elo',
        'teamelo_elo_year',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'teamelo_team_id', 'team_id');
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'teamelo_league_id', 'league_id');
    }
}
