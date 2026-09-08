<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teamfid extends Model
{
    protected $table = 'ffb_teamfid';

    protected $primaryKey = 'teamfid_id';

    public $timestamps = false;

    protected $fillable = [
        'teamfid_team_id',
        'teamfid_fid_foe',
        'teamfid_fid_tm',
        'teamfid_fid_wf',
        'teamfid_name_foe',
        'teamfid_name_tm',
        'teamfid_name_wf',
        'teamfid_url_foe',
        'teamfid_url_tm',
        'teamfid_url_wf',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'teamfid_team_id', 'team_id');
    }
}
