<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetails extends Model
{
    protected $table = 'web_user_details';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'user_id', 'user_id');
    }

    public function selectedGame(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'user_details_ffb_selected_game', 'game_id');
    }
}
