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

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'matchround_game_id', 'game_id');
    }

    public function userteams(): HasMany
    {
        return $this->hasMany(Userteam::class, 'userteam_matchround_id', 'matchround_id');
    }
}
