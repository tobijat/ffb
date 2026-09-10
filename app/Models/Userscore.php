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
        'userscore_league_id',
        'userscore_total',
        'userscore_wc_points',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'userscore_user_id', 'user_id');
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'userscore_league_id', 'league_id');
    }
}
