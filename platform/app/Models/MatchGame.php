<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchGame extends Model
{
    protected $table = 'ffb_match';

    protected $primaryKey = 'match_id';

    public $timestamps = false;

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'match_hometeam_id', 'team_id');
    }

    public function guestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'match_guestteam_id', 'team_id');
    }

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'match_round', 'matchround_id');
    }
}
