<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'ffb_team';

    protected $primaryKey = 'team_id';

    public $timestamps = false;
}
