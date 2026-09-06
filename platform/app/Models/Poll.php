<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $table = 'ffb_poll';

    protected $primaryKey = 'poll_id';

    public $timestamps = false;

    public function answers(): HasMany
    {
        return $this->hasMany(PollAnswer::class, 'poll_answer_poll_id', 'poll_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(PollResult::class, 'poll_result_poll_id', 'poll_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'poll_game_id', 'game_id');
    }
}
