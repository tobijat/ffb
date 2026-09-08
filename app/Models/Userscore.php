<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Userscore extends Model
{
    protected $table = 'ffb_userscore';

    protected $primaryKey = 'userscore_id';

    public $timestamps = false;

    protected $fillable = [
        'userscore_user_id',
        'userscore_game_id',
        'userscore_total',
        'userscore_wc_points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'userscore_user_id', 'user_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'userscore_game_id', 'game_id');
    }
}
