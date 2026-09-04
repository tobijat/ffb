<?php

/**
 * @author Gritschacher
 * @copyright 07/2008
 * @version 0.1
 */

class FFB_Options {

    private $game_id;
    private $options;

    public function __construct($game_id) {
        require_once('ffb/FfbOptions.php');
        if($game_id)
            $this->game_id = $game_id;
        else
            $this->game_id = 0;

        $this->options = array();
        $this->retrieveOptions();
	}

	private function retrieveOptions() {
	    $criteria = new Criteria();
	    $criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->game_id);
	    $criteria->setLimit(1);
	    $items = FfbOptionsPeer::doSelect($criteria);
	    if($items)
	       $options = $items[0];

	    $this->setOptionsArray($options);
    }

    private function setOptionsArray($options) {
        $this->options['options_id'] = $options->getOptionsId();
        $this->options['options_game_id'] = $options->getOptionsGameId();
        $this->options['options_score_minutes'] = $options->getOptionsScoreMinutes();
        $this->options['options_score_minutes_treshold'] = $options->getOptionsScoreMinutesTreshold();
        $this->options['options_score_minutes_gt'] = $options->getOptionsScoreMinutesGt();
        $this->options['options_score_minutes_lt'] = $options->getOptionsScoreMinutesLt();
        $this->options['options_score_minutes_lt30'] = $options->getOptionsScoreMinutesLt30();
        $this->options['options_score_goals_g'] = $options->getOptionsScoreGoalsG();
        $this->options['options_score_goals_d'] = $options->getOptionsScoreGoalsD();
        $this->options['options_score_goals_m'] = $options->getOptionsScoreGoalsM();
        $this->options['options_score_goals_s'] = $options->getOptionsScoreGoalsS();
        $this->options['options_score_assists'] = $options->getOptionsScoreAssists();
        $this->options['options_score_no_oppgoals_g'] = $options->getOptionsScoreNoOppgoalsG();
        $this->options['options_score_no_oppgoals_d'] = $options->getOptionsScoreNoOppgoalsD();
        $this->options['options_score_no_oppgoals_m'] = $options->getOptionsScoreNoOppgoalsM();
        $this->options['options_score_oppgoals_g'] = $options->getOptionsScoreOppgoalsG();
        $this->options['options_score_oppgoals_d'] = $options->getOptionsScoreOppgoalsD();
        $this->options['options_score_owngoals'] = $options->getOptionsScoreOwngoals();
        $this->options['options_score_card_y'] = $options->getOptionsScoreCardY();
        $this->options['options_score_card_r'] = $options->getOptionsScoreCardR();
        $this->options['options_score_card_yr'] = $options->getOptionsScoreCardYr();
        $this->options['options_score_penalty_saved'] = $options->getOptionsScorePenaltySaved();
        $this->options['options_score_penalty_lost'] = $options->getOptionsScorePenaltyLost();
        $this->options['options_score_penaltyshootout_lost'] = $options->getOptionsScorePenaltyshootoutLost();
        $this->options['options_score_penaltyshootout_save'] = $options->getOptionsScorePenaltyshootoutSave();
        $this->options['options_score_penaltyshootout_hit'] = $options->getOptionsScorePenaltyshootoutHit();
        $this->options['options_score_high_loss'] = $options->getOptionsScoreHighLoss();
        $this->options['options_score_high_win'] = $options->getOptionsScoreHighWin();
        $this->options['options_score_high_win_loss_treshold'] = $options->getOptionsScoreHighWinLossTreshold();

        $this->options['options_status_error'] = $options->getOptionsStatusError();
        $this->options['options_status_error_validation'] = $options->getOptionsStatusErrorValidation();
        $this->options['options_status_success'] = $options->getOptionsStatusSuccess();
        $this->options['options_status_success_insert'] = $options->getOptionsStatusSuccessInsert();
        $this->options['options_status_success_update'] = $options->getOptionsStatusSuccessUpdate();
        $this->options['options_status_success_delete'] = $options->getOptionsStatusSuccessDelete();

        $this->options['options_lineup_max_players'] = $options->getOptionsLineupMaxPlayers();
        $this->options['options_lineup_max_credits'] = $options->getOptionsLineupMaxCredits();
        $this->options['options_lineup_max_players_team'] = $options->getOptionsLineupMaxPlayersTeam();
        $this->options['options_lineup_min_g'] = $options->getOptionsLineupMinG();
        $this->options['options_lineup_min_d'] = $options->getOptionsLineupMinD();
        $this->options['options_lineup_min_m'] = $options->getOptionsLineupMinM();
        $this->options['options_lineup_min_s'] = $options->getOptionsLineupMinS();
        $this->options['options_lineup_max_g'] = $options->getOptionsLineupMaxG();
        $this->options['options_lineup_max_d'] = $options->getOptionsLineupMaxD();
        $this->options['options_lineup_max_m'] = $options->getOptionsLineupMaxM();
        $this->options['options_lineup_max_s'] = $options->getOptionsLineupMaxS();

        $this->options['options_game_rankmode'] = $options->getOptionsGameRankmode();
        $this->options['options_game_pricemode'] = $options->getOptionsGamePricemode();
        $this->options['options_game_pointsmode'] = $options->getOptionsGamePointsmode();
        $this->options['options_game_wcpoints'] = $options->getOptionsGameWcpoints();
    }

    public function __get($var) {
        return $this->options[$var];
    }

}

?>