<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    protected $table = 'ffb_team';

    protected $primaryKey = 'team_id';

    public $timestamps = false;

    protected $fillable = [
        'team_foreign_id',
        'team_name',
        'team_nationality',
        'team_num_players',
        'team_status',
        'team_uefa_id',
        'team_team_code',
    ];

    public function teamfid(): HasOne
    {
        return $this->hasOne(Teamfid::class, 'teamfid_team_id', 'team_id');
    }

    public function playerteams(): HasMany
    {
        return $this->hasMany(Playerteam::class, 'playerteam_team_id', 'team_id');
    }
}
