<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    protected $table = 'ffb_goal';

    protected $primaryKey = 'goal_id';

    public $timestamps = false;

    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchGame::class, 'goal_match_id', 'match_id');
    }

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'goal_playerteam_id', 'playerteam_id');
    }
}
