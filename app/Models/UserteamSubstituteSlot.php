<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserteamSubstituteSlot extends Model
{
    protected $table = 'ffb_userteam_substitute_slot';

    protected $primaryKey = 'substitute_slot_id';

    public $timestamps = false;

    protected $fillable = [
        'substitute_slot_userteam_id',
        'substitute_slot_slot',
        'substitute_slot_playerteam_id',
        'substitute_slot_replaces_playerteam_id',
    ];

    public function userteam(): BelongsTo
    {
        return $this->belongsTo(Userteam::class, 'substitute_slot_userteam_id', 'userteam_id');
    }

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'substitute_slot_playerteam_id', 'playerteam_id');
    }

    public function replacesPlayerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'substitute_slot_replaces_playerteam_id', 'playerteam_id');
    }
}
