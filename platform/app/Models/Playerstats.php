<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playerstats extends Model
{
    protected $table = 'ffb_playerstats';

    protected $primaryKey = 'playerstats_id';

    public $timestamps = false;

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'playerstats_playerteam_id', 'playerteam_id');
    }
}
