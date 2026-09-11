<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchroundOptions extends Model
{
    protected $table = 'ffb_matchround_options';

    protected $primaryKey = 'matchround_options_id';

    public $timestamps = false;

    protected $fillable = [
        'matchround_options_matchround_id',
        'matchround_options_lineup_max_players',
        'matchround_options_lineup_max_credits',
        'matchround_options_lineup_max_players_team',
        'matchround_options_lineup_min_g',
        'matchround_options_lineup_min_d',
        'matchround_options_lineup_min_m',
        'matchround_options_lineup_min_s',
        'matchround_options_lineup_max_g',
        'matchround_options_lineup_max_d',
        'matchround_options_lineup_max_m',
        'matchround_options_lineup_max_s',
    ];

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'matchround_options_matchround_id', 'matchround_id');
    }
}
