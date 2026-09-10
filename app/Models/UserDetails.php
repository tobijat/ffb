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

    public function selectedLeague(): BelongsTo
    {
        return $this->belongsTo(League::class, 'user_details_ffb_selected_league', 'league_id');
    }
}
