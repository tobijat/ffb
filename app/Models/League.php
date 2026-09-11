<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class League extends Model
{
    protected $table = 'ffb_league';

    protected $primaryKey = 'league_id';

    public $timestamps = false;

    protected $fillable = [
        'league_title',
        'league_visible',
        'league_archive',
        'league_status',
        'league_symbol',
    ];

    public function matchrounds(): HasMany
    {
        return $this->hasMany(Matchround::class, 'matchround_league_id', 'league_id');
    }

    public function options(): HasOne
    {
        return $this->hasOne(LeagueOptions::class, 'options_league_id', 'league_id');
    }
}
