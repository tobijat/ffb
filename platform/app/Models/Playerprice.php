<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playerprice extends Model
{
    protected $table = 'ffb_playerprice';

    protected $primaryKey = 'playerprice_id';

    public $timestamps = false;

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'playerprice_playerteam_id', 'playerteam_id');
    }
}
