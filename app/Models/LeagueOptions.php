<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueOptions extends Model
{
    protected $table = 'ffb_league_options';

    protected $primaryKey = 'options_id';

    public $timestamps = false;

    protected $fillable = [
        'options_league_id',
        'options_score_minutes_threshold_upper',
        'options_score_minutes_threshold_lower',
        'options_score_minutes_high',
        'options_score_minutes_middle',
        'options_score_minutes_low',
        'options_score_goals_g',
        'options_score_goals_d',
        'options_score_goals_m',
        'options_score_goals_s',
        'options_score_assists',
        'options_score_no_oppgoals_g',
        'options_score_no_oppgoals_d',
        'options_score_no_oppgoals_m',
        'options_score_oppgoals_g',
        'options_score_oppgoals_d',
        'options_score_owngoals',
        'options_score_card_y',
        'options_score_card_r',
        'options_score_card_yr',
        'options_score_penalty_saved',
        'options_score_penalty_lost',
        'options_score_penaltyshootout_save',
        'options_score_penaltyshootout_lost',
        'options_score_penaltyshootout_hit',
        'options_score_high_loss',
        'options_score_high_win',
        'options_score_high_win_loss_treshold',
        'options_lineup_max_players',
        'options_lineup_max_credits',
        'options_lineup_max_players_team',
        'options_lineup_min_g',
        'options_lineup_min_d',
        'options_lineup_min_m',
        'options_lineup_min_s',
        'options_lineup_max_g',
        'options_lineup_max_d',
        'options_lineup_max_m',
        'options_lineup_max_s',
        'options_league_rankmode',
        'options_league_pricemode',
        'options_league_pointsmode',
        'options_league_lcpoints',
        'options_league_remind_hours_before',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class, 'options_league_id', 'league_id');
    }
}
