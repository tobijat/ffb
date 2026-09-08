<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameOptions extends Model
{
    protected $table = 'ffb_options';

    protected $primaryKey = 'options_id';

    public $timestamps = false;

    protected $fillable = [
        'options_game_id',
        'options_score_minutes',
        'options_score_minutes_treshold',
        'options_score_minutes_gt',
        'options_score_minutes_lt',
        'options_score_minutes_lt30',
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
        'options_status_error',
        'options_status_error_validation',
        'options_status_success',
        'options_status_success_insert',
        'options_status_success_update',
        'options_status_success_delete',
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
        'options_game_rankmode',
        'options_game_pricemode',
        'options_game_pointsmode',
        'options_game_wcpoints',
        'options_game_remind_hours_before',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'options_game_id', 'game_id');
    }
}
