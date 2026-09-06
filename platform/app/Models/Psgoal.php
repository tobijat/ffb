<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Psgoal extends Model
{
    protected $table = 'ffb_psgoal';

    protected $primaryKey = 'psgoal_id';

    public $timestamps = false;

    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchGame::class, 'psgoal_match_id', 'match_id');
    }

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'psgoal_playerteam_id', 'playerteam_id');
    }
}
