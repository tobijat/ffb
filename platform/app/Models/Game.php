<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    protected $table = 'ffb_game';

    protected $primaryKey = 'game_id';

    public $timestamps = false;

    protected $fillable = [
        'game_title',
        'game_visible',
        'game_archive',
        'game_countdown',
        'game_status',
        'game_description',
        'game_symbol',
    ];

    public function matchrounds(): HasMany
    {
        return $this->hasMany(Matchround::class, 'matchround_game_id', 'game_id');
    }

    public function options(): HasOne
    {
        return $this->hasOne(GameOptions::class, 'options_game_id', 'game_id');
    }
}
