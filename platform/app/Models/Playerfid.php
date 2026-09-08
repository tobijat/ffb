<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playerfid extends Model
{
    protected $table = 'ffb_playerfid';

    protected $primaryKey = 'playerfid_id';

    public $timestamps = false;

    protected $fillable = [
        'playerfid_playerteam_id',
        'playerfid_team_id',
        'playerfid_name_wf',
        'playerfid_fid_wf',
    ];

    public function playerteam(): BelongsTo
    {
        return $this->belongsTo(Playerteam::class, 'playerfid_playerteam_id', 'playerteam_id');
    }
}
