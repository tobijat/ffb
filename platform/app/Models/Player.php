<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'ffb_player';

    protected $primaryKey = 'player_id';

    public $timestamps = false;
}
