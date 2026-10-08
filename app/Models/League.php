<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class League extends Model
{
    protected $table = 'ffb_league';

    protected $primaryKey = 'league_id';

    public $timestamps = false;

    protected $fillable = [
        'asset_key',
        'league_title',
        'league_visible',
        'league_archive',
        'league_test',
        'league_uefa_competition_identifier',
        'league_fifa_competition_identifier',
    ];

    /**
     * Leagues that may appear in the player app (exclude admin-only test games).
     */
    public function scopeForPlayerApp(Builder $query): Builder
    {
        return $query->where('league_test', 0);
    }

    public function matchrounds(): HasMany
    {
        return $this->hasMany(Matchround::class, 'matchround_league_id', 'league_id');
    }

    public function options(): HasOne
    {
        return $this->hasOne(LeagueOptions::class, 'options_league_id', 'league_id');
    }
}
