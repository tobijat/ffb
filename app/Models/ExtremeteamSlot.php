<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtremeteamSlot extends Model
{
    protected $table = 'ffb_extremeteam_slot';

    protected $primaryKey = 'extremeteam_slot_id';

    public $timestamps = false;

    protected $fillable = [
        'extremeteam_slot_extremeteam_id',
        'extremeteam_slot_slot',
        'extremeteam_slot_playerteam_id',
    ];

    public function extremeteam(): BelongsTo
    {
        return $this->belongsTo(Extremeteam::class, 'extremeteam_slot_extremeteam_id', 'extremeteam_id');
    }

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'extremeteam_slot_playerteam_id', 'playerteam_id');
    }
}
