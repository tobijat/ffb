<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserteamSlot extends Model
{
    protected $table = 'ffb_userteam_slot';

    protected $primaryKey = 'userteam_slot_id';

    public $timestamps = false;

    protected $fillable = [
        'userteam_slot_userteam_id',
        'userteam_slot_slot',
        'userteam_slot_playerteam_id',
    ];

    public function userteam(): BelongsTo
    {
        return $this->belongsTo(Userteam::class, 'userteam_slot_userteam_id', 'userteam_id');
    }

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'userteam_slot_playerteam_id', 'playerteam_id');
    }
}
