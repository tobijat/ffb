<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameOptions extends Model
{
    protected $table = 'ffb_options';

    protected $primaryKey = 'options_id';

    public $timestamps = false;

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'options_game_id', 'game_id');
    }
}
