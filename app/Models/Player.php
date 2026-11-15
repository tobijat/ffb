<?php

namespace App\Models;

use App\Support\PlayerDisplayName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $table = 'ffb_player';

    protected $primaryKey = 'player_id';

    public $timestamps = false;

    protected $fillable = [
        'asset_key',
        'player_foreign_id',
        'player_uefa_id',
        'player_fifa_id',
        'player_fname',
        'player_lname',
        'player_display_name',
        'player_nationality',
        'player_status',
        'player_status_description',
        'player_commons_image',
    ];

    public function playerteams(): HasMany
    {
        return $this->hasMany(Playerteam::class, 'playerteam_player_id', 'player_id');
    }

    public function resolvedDisplayName(): string
    {
        return PlayerDisplayName::resolve(
            $this->player_display_name ?? null,
            $this->player_fname ?? null,
            $this->player_lname ?? null,
        );
    }
}
