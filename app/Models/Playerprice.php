<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playerprice extends Model
{
    protected $table = 'ffb_playerprice';

    protected $primaryKey = 'playerprice_id';

    public $timestamps = false;

    protected $fillable = [
        'playerprice_playerteam_id',
        'playerprice_matchround_id',
        'playerprice_price',
        'playerprice_player_power',
        'playerprice_av_power',
        'playerprice_recent_performance',
    ];

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'playerprice_playerteam_id', 'playerteam_id');
    }
}
