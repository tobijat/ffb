<?php


/**
 * Base class that represents a query for the 'ffb_options' table.
 *
 * 
 *
 * @method     FfbOptionsQuery orderByOptionsId($order = Criteria::ASC) Order by the options_id column
 * @method     FfbOptionsQuery orderByOptionsGameId($order = Criteria::ASC) Order by the options_game_id column
 * @method     FfbOptionsQuery orderByOptionsScoreMinutes($order = Criteria::ASC) Order by the options_score_minutes column
 * @method     FfbOptionsQuery orderByOptionsScoreMinutesTreshold($order = Criteria::ASC) Order by the options_score_minutes_treshold column
 * @method     FfbOptionsQuery orderByOptionsScoreMinutesGt($order = Criteria::ASC) Order by the options_score_minutes_gt column
 * @method     FfbOptionsQuery orderByOptionsScoreMinutesLt($order = Criteria::ASC) Order by the options_score_minutes_lt column
 * @method     FfbOptionsQuery orderByOptionsScoreMinutesLt30($order = Criteria::ASC) Order by the options_score_minutes_lt30 column
 * @method     FfbOptionsQuery orderByOptionsScoreGoalsG($order = Criteria::ASC) Order by the options_score_goals_g column
 * @method     FfbOptionsQuery orderByOptionsScoreGoalsD($order = Criteria::ASC) Order by the options_score_goals_d column
 * @method     FfbOptionsQuery orderByOptionsScoreGoalsM($order = Criteria::ASC) Order by the options_score_goals_m column
 * @method     FfbOptionsQuery orderByOptionsScoreGoalsS($order = Criteria::ASC) Order by the options_score_goals_s column
 * @method     FfbOptionsQuery orderByOptionsScoreAssists($order = Criteria::ASC) Order by the options_score_assists column
 * @method     FfbOptionsQuery orderByOptionsScoreNoOppgoalsG($order = Criteria::ASC) Order by the options_score_no_oppgoals_g column
 * @method     FfbOptionsQuery orderByOptionsScoreNoOppgoalsD($order = Criteria::ASC) Order by the options_score_no_oppgoals_d column
 * @method     FfbOptionsQuery orderByOptionsScoreNoOppgoalsM($order = Criteria::ASC) Order by the options_score_no_oppgoals_m column
 * @method     FfbOptionsQuery orderByOptionsScoreOppgoalsG($order = Criteria::ASC) Order by the options_score_oppgoals_g column
 * @method     FfbOptionsQuery orderByOptionsScoreOppgoalsD($order = Criteria::ASC) Order by the options_score_oppgoals_d column
 * @method     FfbOptionsQuery orderByOptionsScoreOwngoals($order = Criteria::ASC) Order by the options_score_owngoals column
 * @method     FfbOptionsQuery orderByOptionsScoreCardY($order = Criteria::ASC) Order by the options_score_card_y column
 * @method     FfbOptionsQuery orderByOptionsScoreCardR($order = Criteria::ASC) Order by the options_score_card_r column
 * @method     FfbOptionsQuery orderByOptionsScoreCardYr($order = Criteria::ASC) Order by the options_score_card_yr column
 * @method     FfbOptionsQuery orderByOptionsScorePenaltySaved($order = Criteria::ASC) Order by the options_score_penalty_saved column
 * @method     FfbOptionsQuery orderByOptionsScorePenaltyLost($order = Criteria::ASC) Order by the options_score_penalty_lost column
 * @method     FfbOptionsQuery orderByOptionsScorePenaltyshootoutSave($order = Criteria::ASC) Order by the options_score_penaltyshootout_save column
 * @method     FfbOptionsQuery orderByOptionsScorePenaltyshootoutLost($order = Criteria::ASC) Order by the options_score_penaltyshootout_lost column
 * @method     FfbOptionsQuery orderByOptionsScorePenaltyshootoutHit($order = Criteria::ASC) Order by the options_score_penaltyshootout_hit column
 * @method     FfbOptionsQuery orderByOptionsScoreHighLoss($order = Criteria::ASC) Order by the options_score_high_loss column
 * @method     FfbOptionsQuery orderByOptionsScoreHighWin($order = Criteria::ASC) Order by the options_score_high_win column
 * @method     FfbOptionsQuery orderByOptionsScoreHighWinLossTreshold($order = Criteria::ASC) Order by the options_score_high_win_loss_treshold column
 * @method     FfbOptionsQuery orderByOptionsStatusError($order = Criteria::ASC) Order by the options_status_error column
 * @method     FfbOptionsQuery orderByOptionsStatusErrorValidation($order = Criteria::ASC) Order by the options_status_error_validation column
 * @method     FfbOptionsQuery orderByOptionsStatusSuccess($order = Criteria::ASC) Order by the options_status_success column
 * @method     FfbOptionsQuery orderByOptionsStatusSuccessInsert($order = Criteria::ASC) Order by the options_status_success_insert column
 * @method     FfbOptionsQuery orderByOptionsStatusSuccessUpdate($order = Criteria::ASC) Order by the options_status_success_update column
 * @method     FfbOptionsQuery orderByOptionsStatusSuccessDelete($order = Criteria::ASC) Order by the options_status_success_delete column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxPlayers($order = Criteria::ASC) Order by the options_lineup_max_players column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxCredits($order = Criteria::ASC) Order by the options_lineup_max_credits column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxPlayersTeam($order = Criteria::ASC) Order by the options_lineup_max_players_team column
 * @method     FfbOptionsQuery orderByOptionsLineupMinG($order = Criteria::ASC) Order by the options_lineup_min_g column
 * @method     FfbOptionsQuery orderByOptionsLineupMinD($order = Criteria::ASC) Order by the options_lineup_min_d column
 * @method     FfbOptionsQuery orderByOptionsLineupMinM($order = Criteria::ASC) Order by the options_lineup_min_m column
 * @method     FfbOptionsQuery orderByOptionsLineupMinS($order = Criteria::ASC) Order by the options_lineup_min_s column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxG($order = Criteria::ASC) Order by the options_lineup_max_g column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxD($order = Criteria::ASC) Order by the options_lineup_max_d column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxM($order = Criteria::ASC) Order by the options_lineup_max_m column
 * @method     FfbOptionsQuery orderByOptionsLineupMaxS($order = Criteria::ASC) Order by the options_lineup_max_s column
 * @method     FfbOptionsQuery orderByOptionsGameRankmode($order = Criteria::ASC) Order by the options_game_rankmode column
 * @method     FfbOptionsQuery orderByOptionsGamePricemode($order = Criteria::ASC) Order by the options_game_pricemode column
 * @method     FfbOptionsQuery orderByOptionsGamePointsmode($order = Criteria::ASC) Order by the options_game_pointsmode column
 * @method     FfbOptionsQuery orderByOptionsGameWcpoints($order = Criteria::ASC) Order by the options_game_wcpoints column
 * @method     FfbOptionsQuery orderByOptionsGameRemindHoursBefore($order = Criteria::ASC) Order by the options_game_remind_hours_before column
 *
 * @method     FfbOptionsQuery groupByOptionsId() Group by the options_id column
 * @method     FfbOptionsQuery groupByOptionsGameId() Group by the options_game_id column
 * @method     FfbOptionsQuery groupByOptionsScoreMinutes() Group by the options_score_minutes column
 * @method     FfbOptionsQuery groupByOptionsScoreMinutesTreshold() Group by the options_score_minutes_treshold column
 * @method     FfbOptionsQuery groupByOptionsScoreMinutesGt() Group by the options_score_minutes_gt column
 * @method     FfbOptionsQuery groupByOptionsScoreMinutesLt() Group by the options_score_minutes_lt column
 * @method     FfbOptionsQuery groupByOptionsScoreMinutesLt30() Group by the options_score_minutes_lt30 column
 * @method     FfbOptionsQuery groupByOptionsScoreGoalsG() Group by the options_score_goals_g column
 * @method     FfbOptionsQuery groupByOptionsScoreGoalsD() Group by the options_score_goals_d column
 * @method     FfbOptionsQuery groupByOptionsScoreGoalsM() Group by the options_score_goals_m column
 * @method     FfbOptionsQuery groupByOptionsScoreGoalsS() Group by the options_score_goals_s column
 * @method     FfbOptionsQuery groupByOptionsScoreAssists() Group by the options_score_assists column
 * @method     FfbOptionsQuery groupByOptionsScoreNoOppgoalsG() Group by the options_score_no_oppgoals_g column
 * @method     FfbOptionsQuery groupByOptionsScoreNoOppgoalsD() Group by the options_score_no_oppgoals_d column
 * @method     FfbOptionsQuery groupByOptionsScoreNoOppgoalsM() Group by the options_score_no_oppgoals_m column
 * @method     FfbOptionsQuery groupByOptionsScoreOppgoalsG() Group by the options_score_oppgoals_g column
 * @method     FfbOptionsQuery groupByOptionsScoreOppgoalsD() Group by the options_score_oppgoals_d column
 * @method     FfbOptionsQuery groupByOptionsScoreOwngoals() Group by the options_score_owngoals column
 * @method     FfbOptionsQuery groupByOptionsScoreCardY() Group by the options_score_card_y column
 * @method     FfbOptionsQuery groupByOptionsScoreCardR() Group by the options_score_card_r column
 * @method     FfbOptionsQuery groupByOptionsScoreCardYr() Group by the options_score_card_yr column
 * @method     FfbOptionsQuery groupByOptionsScorePenaltySaved() Group by the options_score_penalty_saved column
 * @method     FfbOptionsQuery groupByOptionsScorePenaltyLost() Group by the options_score_penalty_lost column
 * @method     FfbOptionsQuery groupByOptionsScorePenaltyshootoutSave() Group by the options_score_penaltyshootout_save column
 * @method     FfbOptionsQuery groupByOptionsScorePenaltyshootoutLost() Group by the options_score_penaltyshootout_lost column
 * @method     FfbOptionsQuery groupByOptionsScorePenaltyshootoutHit() Group by the options_score_penaltyshootout_hit column
 * @method     FfbOptionsQuery groupByOptionsScoreHighLoss() Group by the options_score_high_loss column
 * @method     FfbOptionsQuery groupByOptionsScoreHighWin() Group by the options_score_high_win column
 * @method     FfbOptionsQuery groupByOptionsScoreHighWinLossTreshold() Group by the options_score_high_win_loss_treshold column
 * @method     FfbOptionsQuery groupByOptionsStatusError() Group by the options_status_error column
 * @method     FfbOptionsQuery groupByOptionsStatusErrorValidation() Group by the options_status_error_validation column
 * @method     FfbOptionsQuery groupByOptionsStatusSuccess() Group by the options_status_success column
 * @method     FfbOptionsQuery groupByOptionsStatusSuccessInsert() Group by the options_status_success_insert column
 * @method     FfbOptionsQuery groupByOptionsStatusSuccessUpdate() Group by the options_status_success_update column
 * @method     FfbOptionsQuery groupByOptionsStatusSuccessDelete() Group by the options_status_success_delete column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxPlayers() Group by the options_lineup_max_players column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxCredits() Group by the options_lineup_max_credits column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxPlayersTeam() Group by the options_lineup_max_players_team column
 * @method     FfbOptionsQuery groupByOptionsLineupMinG() Group by the options_lineup_min_g column
 * @method     FfbOptionsQuery groupByOptionsLineupMinD() Group by the options_lineup_min_d column
 * @method     FfbOptionsQuery groupByOptionsLineupMinM() Group by the options_lineup_min_m column
 * @method     FfbOptionsQuery groupByOptionsLineupMinS() Group by the options_lineup_min_s column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxG() Group by the options_lineup_max_g column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxD() Group by the options_lineup_max_d column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxM() Group by the options_lineup_max_m column
 * @method     FfbOptionsQuery groupByOptionsLineupMaxS() Group by the options_lineup_max_s column
 * @method     FfbOptionsQuery groupByOptionsGameRankmode() Group by the options_game_rankmode column
 * @method     FfbOptionsQuery groupByOptionsGamePricemode() Group by the options_game_pricemode column
 * @method     FfbOptionsQuery groupByOptionsGamePointsmode() Group by the options_game_pointsmode column
 * @method     FfbOptionsQuery groupByOptionsGameWcpoints() Group by the options_game_wcpoints column
 * @method     FfbOptionsQuery groupByOptionsGameRemindHoursBefore() Group by the options_game_remind_hours_before column
 *
 * @method     FfbOptionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbOptionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbOptionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbOptionsQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbOptionsQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbOptionsQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbOptions findOne(PropelPDO $con = null) Return the first FfbOptions matching the query
 * @method     FfbOptions findOneOrCreate(PropelPDO $con = null) Return the first FfbOptions matching the query, or a new FfbOptions object populated from the query conditions when no match is found
 *
 * @method     FfbOptions findOneByOptionsId(int $options_id) Return the first FfbOptions filtered by the options_id column
 * @method     FfbOptions findOneByOptionsGameId(int $options_game_id) Return the first FfbOptions filtered by the options_game_id column
 * @method     FfbOptions findOneByOptionsScoreMinutes(int $options_score_minutes) Return the first FfbOptions filtered by the options_score_minutes column
 * @method     FfbOptions findOneByOptionsScoreMinutesTreshold(int $options_score_minutes_treshold) Return the first FfbOptions filtered by the options_score_minutes_treshold column
 * @method     FfbOptions findOneByOptionsScoreMinutesGt(int $options_score_minutes_gt) Return the first FfbOptions filtered by the options_score_minutes_gt column
 * @method     FfbOptions findOneByOptionsScoreMinutesLt(int $options_score_minutes_lt) Return the first FfbOptions filtered by the options_score_minutes_lt column
 * @method     FfbOptions findOneByOptionsScoreMinutesLt30(int $options_score_minutes_lt30) Return the first FfbOptions filtered by the options_score_minutes_lt30 column
 * @method     FfbOptions findOneByOptionsScoreGoalsG(int $options_score_goals_g) Return the first FfbOptions filtered by the options_score_goals_g column
 * @method     FfbOptions findOneByOptionsScoreGoalsD(int $options_score_goals_d) Return the first FfbOptions filtered by the options_score_goals_d column
 * @method     FfbOptions findOneByOptionsScoreGoalsM(int $options_score_goals_m) Return the first FfbOptions filtered by the options_score_goals_m column
 * @method     FfbOptions findOneByOptionsScoreGoalsS(int $options_score_goals_s) Return the first FfbOptions filtered by the options_score_goals_s column
 * @method     FfbOptions findOneByOptionsScoreAssists(int $options_score_assists) Return the first FfbOptions filtered by the options_score_assists column
 * @method     FfbOptions findOneByOptionsScoreNoOppgoalsG(int $options_score_no_oppgoals_g) Return the first FfbOptions filtered by the options_score_no_oppgoals_g column
 * @method     FfbOptions findOneByOptionsScoreNoOppgoalsD(int $options_score_no_oppgoals_d) Return the first FfbOptions filtered by the options_score_no_oppgoals_d column
 * @method     FfbOptions findOneByOptionsScoreNoOppgoalsM(int $options_score_no_oppgoals_m) Return the first FfbOptions filtered by the options_score_no_oppgoals_m column
 * @method     FfbOptions findOneByOptionsScoreOppgoalsG(int $options_score_oppgoals_g) Return the first FfbOptions filtered by the options_score_oppgoals_g column
 * @method     FfbOptions findOneByOptionsScoreOppgoalsD(int $options_score_oppgoals_d) Return the first FfbOptions filtered by the options_score_oppgoals_d column
 * @method     FfbOptions findOneByOptionsScoreOwngoals(int $options_score_owngoals) Return the first FfbOptions filtered by the options_score_owngoals column
 * @method     FfbOptions findOneByOptionsScoreCardY(int $options_score_card_y) Return the first FfbOptions filtered by the options_score_card_y column
 * @method     FfbOptions findOneByOptionsScoreCardR(int $options_score_card_r) Return the first FfbOptions filtered by the options_score_card_r column
 * @method     FfbOptions findOneByOptionsScoreCardYr(int $options_score_card_yr) Return the first FfbOptions filtered by the options_score_card_yr column
 * @method     FfbOptions findOneByOptionsScorePenaltySaved(int $options_score_penalty_saved) Return the first FfbOptions filtered by the options_score_penalty_saved column
 * @method     FfbOptions findOneByOptionsScorePenaltyLost(int $options_score_penalty_lost) Return the first FfbOptions filtered by the options_score_penalty_lost column
 * @method     FfbOptions findOneByOptionsScorePenaltyshootoutSave(int $options_score_penaltyshootout_save) Return the first FfbOptions filtered by the options_score_penaltyshootout_save column
 * @method     FfbOptions findOneByOptionsScorePenaltyshootoutLost(int $options_score_penaltyshootout_lost) Return the first FfbOptions filtered by the options_score_penaltyshootout_lost column
 * @method     FfbOptions findOneByOptionsScorePenaltyshootoutHit(int $options_score_penaltyshootout_hit) Return the first FfbOptions filtered by the options_score_penaltyshootout_hit column
 * @method     FfbOptions findOneByOptionsScoreHighLoss(int $options_score_high_loss) Return the first FfbOptions filtered by the options_score_high_loss column
 * @method     FfbOptions findOneByOptionsScoreHighWin(int $options_score_high_win) Return the first FfbOptions filtered by the options_score_high_win column
 * @method     FfbOptions findOneByOptionsScoreHighWinLossTreshold(int $options_score_high_win_loss_treshold) Return the first FfbOptions filtered by the options_score_high_win_loss_treshold column
 * @method     FfbOptions findOneByOptionsStatusError(int $options_status_error) Return the first FfbOptions filtered by the options_status_error column
 * @method     FfbOptions findOneByOptionsStatusErrorValidation(int $options_status_error_validation) Return the first FfbOptions filtered by the options_status_error_validation column
 * @method     FfbOptions findOneByOptionsStatusSuccess(int $options_status_success) Return the first FfbOptions filtered by the options_status_success column
 * @method     FfbOptions findOneByOptionsStatusSuccessInsert(int $options_status_success_insert) Return the first FfbOptions filtered by the options_status_success_insert column
 * @method     FfbOptions findOneByOptionsStatusSuccessUpdate(int $options_status_success_update) Return the first FfbOptions filtered by the options_status_success_update column
 * @method     FfbOptions findOneByOptionsStatusSuccessDelete(int $options_status_success_delete) Return the first FfbOptions filtered by the options_status_success_delete column
 * @method     FfbOptions findOneByOptionsLineupMaxPlayers(int $options_lineup_max_players) Return the first FfbOptions filtered by the options_lineup_max_players column
 * @method     FfbOptions findOneByOptionsLineupMaxCredits(int $options_lineup_max_credits) Return the first FfbOptions filtered by the options_lineup_max_credits column
 * @method     FfbOptions findOneByOptionsLineupMaxPlayersTeam(int $options_lineup_max_players_team) Return the first FfbOptions filtered by the options_lineup_max_players_team column
 * @method     FfbOptions findOneByOptionsLineupMinG(int $options_lineup_min_g) Return the first FfbOptions filtered by the options_lineup_min_g column
 * @method     FfbOptions findOneByOptionsLineupMinD(int $options_lineup_min_d) Return the first FfbOptions filtered by the options_lineup_min_d column
 * @method     FfbOptions findOneByOptionsLineupMinM(int $options_lineup_min_m) Return the first FfbOptions filtered by the options_lineup_min_m column
 * @method     FfbOptions findOneByOptionsLineupMinS(int $options_lineup_min_s) Return the first FfbOptions filtered by the options_lineup_min_s column
 * @method     FfbOptions findOneByOptionsLineupMaxG(int $options_lineup_max_g) Return the first FfbOptions filtered by the options_lineup_max_g column
 * @method     FfbOptions findOneByOptionsLineupMaxD(int $options_lineup_max_d) Return the first FfbOptions filtered by the options_lineup_max_d column
 * @method     FfbOptions findOneByOptionsLineupMaxM(int $options_lineup_max_m) Return the first FfbOptions filtered by the options_lineup_max_m column
 * @method     FfbOptions findOneByOptionsLineupMaxS(int $options_lineup_max_s) Return the first FfbOptions filtered by the options_lineup_max_s column
 * @method     FfbOptions findOneByOptionsGameRankmode(string $options_game_rankmode) Return the first FfbOptions filtered by the options_game_rankmode column
 * @method     FfbOptions findOneByOptionsGamePricemode(string $options_game_pricemode) Return the first FfbOptions filtered by the options_game_pricemode column
 * @method     FfbOptions findOneByOptionsGamePointsmode(string $options_game_pointsmode) Return the first FfbOptions filtered by the options_game_pointsmode column
 * @method     FfbOptions findOneByOptionsGameWcpoints(string $options_game_wcpoints) Return the first FfbOptions filtered by the options_game_wcpoints column
 * @method     FfbOptions findOneByOptionsGameRemindHoursBefore(int $options_game_remind_hours_before) Return the first FfbOptions filtered by the options_game_remind_hours_before column
 *
 * @method     array findByOptionsId(int $options_id) Return FfbOptions objects filtered by the options_id column
 * @method     array findByOptionsGameId(int $options_game_id) Return FfbOptions objects filtered by the options_game_id column
 * @method     array findByOptionsScoreMinutes(int $options_score_minutes) Return FfbOptions objects filtered by the options_score_minutes column
 * @method     array findByOptionsScoreMinutesTreshold(int $options_score_minutes_treshold) Return FfbOptions objects filtered by the options_score_minutes_treshold column
 * @method     array findByOptionsScoreMinutesGt(int $options_score_minutes_gt) Return FfbOptions objects filtered by the options_score_minutes_gt column
 * @method     array findByOptionsScoreMinutesLt(int $options_score_minutes_lt) Return FfbOptions objects filtered by the options_score_minutes_lt column
 * @method     array findByOptionsScoreMinutesLt30(int $options_score_minutes_lt30) Return FfbOptions objects filtered by the options_score_minutes_lt30 column
 * @method     array findByOptionsScoreGoalsG(int $options_score_goals_g) Return FfbOptions objects filtered by the options_score_goals_g column
 * @method     array findByOptionsScoreGoalsD(int $options_score_goals_d) Return FfbOptions objects filtered by the options_score_goals_d column
 * @method     array findByOptionsScoreGoalsM(int $options_score_goals_m) Return FfbOptions objects filtered by the options_score_goals_m column
 * @method     array findByOptionsScoreGoalsS(int $options_score_goals_s) Return FfbOptions objects filtered by the options_score_goals_s column
 * @method     array findByOptionsScoreAssists(int $options_score_assists) Return FfbOptions objects filtered by the options_score_assists column
 * @method     array findByOptionsScoreNoOppgoalsG(int $options_score_no_oppgoals_g) Return FfbOptions objects filtered by the options_score_no_oppgoals_g column
 * @method     array findByOptionsScoreNoOppgoalsD(int $options_score_no_oppgoals_d) Return FfbOptions objects filtered by the options_score_no_oppgoals_d column
 * @method     array findByOptionsScoreNoOppgoalsM(int $options_score_no_oppgoals_m) Return FfbOptions objects filtered by the options_score_no_oppgoals_m column
 * @method     array findByOptionsScoreOppgoalsG(int $options_score_oppgoals_g) Return FfbOptions objects filtered by the options_score_oppgoals_g column
 * @method     array findByOptionsScoreOppgoalsD(int $options_score_oppgoals_d) Return FfbOptions objects filtered by the options_score_oppgoals_d column
 * @method     array findByOptionsScoreOwngoals(int $options_score_owngoals) Return FfbOptions objects filtered by the options_score_owngoals column
 * @method     array findByOptionsScoreCardY(int $options_score_card_y) Return FfbOptions objects filtered by the options_score_card_y column
 * @method     array findByOptionsScoreCardR(int $options_score_card_r) Return FfbOptions objects filtered by the options_score_card_r column
 * @method     array findByOptionsScoreCardYr(int $options_score_card_yr) Return FfbOptions objects filtered by the options_score_card_yr column
 * @method     array findByOptionsScorePenaltySaved(int $options_score_penalty_saved) Return FfbOptions objects filtered by the options_score_penalty_saved column
 * @method     array findByOptionsScorePenaltyLost(int $options_score_penalty_lost) Return FfbOptions objects filtered by the options_score_penalty_lost column
 * @method     array findByOptionsScorePenaltyshootoutSave(int $options_score_penaltyshootout_save) Return FfbOptions objects filtered by the options_score_penaltyshootout_save column
 * @method     array findByOptionsScorePenaltyshootoutLost(int $options_score_penaltyshootout_lost) Return FfbOptions objects filtered by the options_score_penaltyshootout_lost column
 * @method     array findByOptionsScorePenaltyshootoutHit(int $options_score_penaltyshootout_hit) Return FfbOptions objects filtered by the options_score_penaltyshootout_hit column
 * @method     array findByOptionsScoreHighLoss(int $options_score_high_loss) Return FfbOptions objects filtered by the options_score_high_loss column
 * @method     array findByOptionsScoreHighWin(int $options_score_high_win) Return FfbOptions objects filtered by the options_score_high_win column
 * @method     array findByOptionsScoreHighWinLossTreshold(int $options_score_high_win_loss_treshold) Return FfbOptions objects filtered by the options_score_high_win_loss_treshold column
 * @method     array findByOptionsStatusError(int $options_status_error) Return FfbOptions objects filtered by the options_status_error column
 * @method     array findByOptionsStatusErrorValidation(int $options_status_error_validation) Return FfbOptions objects filtered by the options_status_error_validation column
 * @method     array findByOptionsStatusSuccess(int $options_status_success) Return FfbOptions objects filtered by the options_status_success column
 * @method     array findByOptionsStatusSuccessInsert(int $options_status_success_insert) Return FfbOptions objects filtered by the options_status_success_insert column
 * @method     array findByOptionsStatusSuccessUpdate(int $options_status_success_update) Return FfbOptions objects filtered by the options_status_success_update column
 * @method     array findByOptionsStatusSuccessDelete(int $options_status_success_delete) Return FfbOptions objects filtered by the options_status_success_delete column
 * @method     array findByOptionsLineupMaxPlayers(int $options_lineup_max_players) Return FfbOptions objects filtered by the options_lineup_max_players column
 * @method     array findByOptionsLineupMaxCredits(int $options_lineup_max_credits) Return FfbOptions objects filtered by the options_lineup_max_credits column
 * @method     array findByOptionsLineupMaxPlayersTeam(int $options_lineup_max_players_team) Return FfbOptions objects filtered by the options_lineup_max_players_team column
 * @method     array findByOptionsLineupMinG(int $options_lineup_min_g) Return FfbOptions objects filtered by the options_lineup_min_g column
 * @method     array findByOptionsLineupMinD(int $options_lineup_min_d) Return FfbOptions objects filtered by the options_lineup_min_d column
 * @method     array findByOptionsLineupMinM(int $options_lineup_min_m) Return FfbOptions objects filtered by the options_lineup_min_m column
 * @method     array findByOptionsLineupMinS(int $options_lineup_min_s) Return FfbOptions objects filtered by the options_lineup_min_s column
 * @method     array findByOptionsLineupMaxG(int $options_lineup_max_g) Return FfbOptions objects filtered by the options_lineup_max_g column
 * @method     array findByOptionsLineupMaxD(int $options_lineup_max_d) Return FfbOptions objects filtered by the options_lineup_max_d column
 * @method     array findByOptionsLineupMaxM(int $options_lineup_max_m) Return FfbOptions objects filtered by the options_lineup_max_m column
 * @method     array findByOptionsLineupMaxS(int $options_lineup_max_s) Return FfbOptions objects filtered by the options_lineup_max_s column
 * @method     array findByOptionsGameRankmode(string $options_game_rankmode) Return FfbOptions objects filtered by the options_game_rankmode column
 * @method     array findByOptionsGamePricemode(string $options_game_pricemode) Return FfbOptions objects filtered by the options_game_pricemode column
 * @method     array findByOptionsGamePointsmode(string $options_game_pointsmode) Return FfbOptions objects filtered by the options_game_pointsmode column
 * @method     array findByOptionsGameWcpoints(string $options_game_wcpoints) Return FfbOptions objects filtered by the options_game_wcpoints column
 * @method     array findByOptionsGameRemindHoursBefore(int $options_game_remind_hours_before) Return FfbOptions objects filtered by the options_game_remind_hours_before column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbOptionsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbOptionsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbOptions', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbOptionsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbOptionsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbOptionsQuery) {
			return $criteria;
		}
		$query = new FfbOptionsQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    FfbOptions|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbOptionsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the options_id column
	 * 
	 * @param     int|array $optionsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsId($optionsId = null, $comparison = null)
	{
		if (is_array($optionsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_ID, $optionsId, $comparison);
	}

	/**
	 * Filter the query on the options_game_id column
	 * 
	 * @param     int|array $optionsGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGameId($optionsGameId = null, $comparison = null)
	{
		if (is_array($optionsGameId)) {
			$useMinMax = false;
			if (isset($optionsGameId['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_ID, $optionsGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsGameId['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_ID, $optionsGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_ID, $optionsGameId, $comparison);
	}

	/**
	 * Filter the query on the options_score_minutes column
	 * 
	 * @param     int|array $optionsScoreMinutes The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreMinutes($optionsScoreMinutes = null, $comparison = null)
	{
		if (is_array($optionsScoreMinutes)) {
			$useMinMax = false;
			if (isset($optionsScoreMinutes['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES, $optionsScoreMinutes['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreMinutes['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES, $optionsScoreMinutes['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES, $optionsScoreMinutes, $comparison);
	}

	/**
	 * Filter the query on the options_score_minutes_treshold column
	 * 
	 * @param     int|array $optionsScoreMinutesTreshold The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreMinutesTreshold($optionsScoreMinutesTreshold = null, $comparison = null)
	{
		if (is_array($optionsScoreMinutesTreshold)) {
			$useMinMax = false;
			if (isset($optionsScoreMinutesTreshold['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD, $optionsScoreMinutesTreshold['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreMinutesTreshold['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD, $optionsScoreMinutesTreshold['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD, $optionsScoreMinutesTreshold, $comparison);
	}

	/**
	 * Filter the query on the options_score_minutes_gt column
	 * 
	 * @param     int|array $optionsScoreMinutesGt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreMinutesGt($optionsScoreMinutesGt = null, $comparison = null)
	{
		if (is_array($optionsScoreMinutesGt)) {
			$useMinMax = false;
			if (isset($optionsScoreMinutesGt['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT, $optionsScoreMinutesGt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreMinutesGt['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT, $optionsScoreMinutesGt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT, $optionsScoreMinutesGt, $comparison);
	}

	/**
	 * Filter the query on the options_score_minutes_lt column
	 * 
	 * @param     int|array $optionsScoreMinutesLt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreMinutesLt($optionsScoreMinutesLt = null, $comparison = null)
	{
		if (is_array($optionsScoreMinutesLt)) {
			$useMinMax = false;
			if (isset($optionsScoreMinutesLt['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT, $optionsScoreMinutesLt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreMinutesLt['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT, $optionsScoreMinutesLt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT, $optionsScoreMinutesLt, $comparison);
	}

	/**
	 * Filter the query on the options_score_minutes_lt30 column
	 * 
	 * @param     int|array $optionsScoreMinutesLt30 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreMinutesLt30($optionsScoreMinutesLt30 = null, $comparison = null)
	{
		if (is_array($optionsScoreMinutesLt30)) {
			$useMinMax = false;
			if (isset($optionsScoreMinutesLt30['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30, $optionsScoreMinutesLt30['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreMinutesLt30['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30, $optionsScoreMinutesLt30['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30, $optionsScoreMinutesLt30, $comparison);
	}

	/**
	 * Filter the query on the options_score_goals_g column
	 * 
	 * @param     int|array $optionsScoreGoalsG The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreGoalsG($optionsScoreGoalsG = null, $comparison = null)
	{
		if (is_array($optionsScoreGoalsG)) {
			$useMinMax = false;
			if (isset($optionsScoreGoalsG['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G, $optionsScoreGoalsG['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreGoalsG['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G, $optionsScoreGoalsG['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G, $optionsScoreGoalsG, $comparison);
	}

	/**
	 * Filter the query on the options_score_goals_d column
	 * 
	 * @param     int|array $optionsScoreGoalsD The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreGoalsD($optionsScoreGoalsD = null, $comparison = null)
	{
		if (is_array($optionsScoreGoalsD)) {
			$useMinMax = false;
			if (isset($optionsScoreGoalsD['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D, $optionsScoreGoalsD['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreGoalsD['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D, $optionsScoreGoalsD['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D, $optionsScoreGoalsD, $comparison);
	}

	/**
	 * Filter the query on the options_score_goals_m column
	 * 
	 * @param     int|array $optionsScoreGoalsM The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreGoalsM($optionsScoreGoalsM = null, $comparison = null)
	{
		if (is_array($optionsScoreGoalsM)) {
			$useMinMax = false;
			if (isset($optionsScoreGoalsM['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M, $optionsScoreGoalsM['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreGoalsM['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M, $optionsScoreGoalsM['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M, $optionsScoreGoalsM, $comparison);
	}

	/**
	 * Filter the query on the options_score_goals_s column
	 * 
	 * @param     int|array $optionsScoreGoalsS The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreGoalsS($optionsScoreGoalsS = null, $comparison = null)
	{
		if (is_array($optionsScoreGoalsS)) {
			$useMinMax = false;
			if (isset($optionsScoreGoalsS['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S, $optionsScoreGoalsS['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreGoalsS['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S, $optionsScoreGoalsS['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S, $optionsScoreGoalsS, $comparison);
	}

	/**
	 * Filter the query on the options_score_assists column
	 * 
	 * @param     int|array $optionsScoreAssists The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreAssists($optionsScoreAssists = null, $comparison = null)
	{
		if (is_array($optionsScoreAssists)) {
			$useMinMax = false;
			if (isset($optionsScoreAssists['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS, $optionsScoreAssists['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreAssists['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS, $optionsScoreAssists['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS, $optionsScoreAssists, $comparison);
	}

	/**
	 * Filter the query on the options_score_no_oppgoals_g column
	 * 
	 * @param     int|array $optionsScoreNoOppgoalsG The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreNoOppgoalsG($optionsScoreNoOppgoalsG = null, $comparison = null)
	{
		if (is_array($optionsScoreNoOppgoalsG)) {
			$useMinMax = false;
			if (isset($optionsScoreNoOppgoalsG['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G, $optionsScoreNoOppgoalsG['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreNoOppgoalsG['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G, $optionsScoreNoOppgoalsG['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G, $optionsScoreNoOppgoalsG, $comparison);
	}

	/**
	 * Filter the query on the options_score_no_oppgoals_d column
	 * 
	 * @param     int|array $optionsScoreNoOppgoalsD The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreNoOppgoalsD($optionsScoreNoOppgoalsD = null, $comparison = null)
	{
		if (is_array($optionsScoreNoOppgoalsD)) {
			$useMinMax = false;
			if (isset($optionsScoreNoOppgoalsD['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D, $optionsScoreNoOppgoalsD['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreNoOppgoalsD['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D, $optionsScoreNoOppgoalsD['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D, $optionsScoreNoOppgoalsD, $comparison);
	}

	/**
	 * Filter the query on the options_score_no_oppgoals_m column
	 * 
	 * @param     int|array $optionsScoreNoOppgoalsM The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreNoOppgoalsM($optionsScoreNoOppgoalsM = null, $comparison = null)
	{
		if (is_array($optionsScoreNoOppgoalsM)) {
			$useMinMax = false;
			if (isset($optionsScoreNoOppgoalsM['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M, $optionsScoreNoOppgoalsM['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreNoOppgoalsM['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M, $optionsScoreNoOppgoalsM['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M, $optionsScoreNoOppgoalsM, $comparison);
	}

	/**
	 * Filter the query on the options_score_oppgoals_g column
	 * 
	 * @param     int|array $optionsScoreOppgoalsG The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreOppgoalsG($optionsScoreOppgoalsG = null, $comparison = null)
	{
		if (is_array($optionsScoreOppgoalsG)) {
			$useMinMax = false;
			if (isset($optionsScoreOppgoalsG['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G, $optionsScoreOppgoalsG['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreOppgoalsG['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G, $optionsScoreOppgoalsG['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G, $optionsScoreOppgoalsG, $comparison);
	}

	/**
	 * Filter the query on the options_score_oppgoals_d column
	 * 
	 * @param     int|array $optionsScoreOppgoalsD The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreOppgoalsD($optionsScoreOppgoalsD = null, $comparison = null)
	{
		if (is_array($optionsScoreOppgoalsD)) {
			$useMinMax = false;
			if (isset($optionsScoreOppgoalsD['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D, $optionsScoreOppgoalsD['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreOppgoalsD['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D, $optionsScoreOppgoalsD['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D, $optionsScoreOppgoalsD, $comparison);
	}

	/**
	 * Filter the query on the options_score_owngoals column
	 * 
	 * @param     int|array $optionsScoreOwngoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreOwngoals($optionsScoreOwngoals = null, $comparison = null)
	{
		if (is_array($optionsScoreOwngoals)) {
			$useMinMax = false;
			if (isset($optionsScoreOwngoals['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS, $optionsScoreOwngoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreOwngoals['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS, $optionsScoreOwngoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS, $optionsScoreOwngoals, $comparison);
	}

	/**
	 * Filter the query on the options_score_card_y column
	 * 
	 * @param     int|array $optionsScoreCardY The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreCardY($optionsScoreCardY = null, $comparison = null)
	{
		if (is_array($optionsScoreCardY)) {
			$useMinMax = false;
			if (isset($optionsScoreCardY['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y, $optionsScoreCardY['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreCardY['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y, $optionsScoreCardY['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y, $optionsScoreCardY, $comparison);
	}

	/**
	 * Filter the query on the options_score_card_r column
	 * 
	 * @param     int|array $optionsScoreCardR The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreCardR($optionsScoreCardR = null, $comparison = null)
	{
		if (is_array($optionsScoreCardR)) {
			$useMinMax = false;
			if (isset($optionsScoreCardR['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_R, $optionsScoreCardR['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreCardR['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_R, $optionsScoreCardR['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_R, $optionsScoreCardR, $comparison);
	}

	/**
	 * Filter the query on the options_score_card_yr column
	 * 
	 * @param     int|array $optionsScoreCardYr The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreCardYr($optionsScoreCardYr = null, $comparison = null)
	{
		if (is_array($optionsScoreCardYr)) {
			$useMinMax = false;
			if (isset($optionsScoreCardYr['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR, $optionsScoreCardYr['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreCardYr['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR, $optionsScoreCardYr['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR, $optionsScoreCardYr, $comparison);
	}

	/**
	 * Filter the query on the options_score_penalty_saved column
	 * 
	 * @param     int|array $optionsScorePenaltySaved The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScorePenaltySaved($optionsScorePenaltySaved = null, $comparison = null)
	{
		if (is_array($optionsScorePenaltySaved)) {
			$useMinMax = false;
			if (isset($optionsScorePenaltySaved['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED, $optionsScorePenaltySaved['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScorePenaltySaved['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED, $optionsScorePenaltySaved['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED, $optionsScorePenaltySaved, $comparison);
	}

	/**
	 * Filter the query on the options_score_penalty_lost column
	 * 
	 * @param     int|array $optionsScorePenaltyLost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScorePenaltyLost($optionsScorePenaltyLost = null, $comparison = null)
	{
		if (is_array($optionsScorePenaltyLost)) {
			$useMinMax = false;
			if (isset($optionsScorePenaltyLost['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST, $optionsScorePenaltyLost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScorePenaltyLost['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST, $optionsScorePenaltyLost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST, $optionsScorePenaltyLost, $comparison);
	}

	/**
	 * Filter the query on the options_score_penaltyshootout_save column
	 * 
	 * @param     int|array $optionsScorePenaltyshootoutSave The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScorePenaltyshootoutSave($optionsScorePenaltyshootoutSave = null, $comparison = null)
	{
		if (is_array($optionsScorePenaltyshootoutSave)) {
			$useMinMax = false;
			if (isset($optionsScorePenaltyshootoutSave['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE, $optionsScorePenaltyshootoutSave['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScorePenaltyshootoutSave['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE, $optionsScorePenaltyshootoutSave['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE, $optionsScorePenaltyshootoutSave, $comparison);
	}

	/**
	 * Filter the query on the options_score_penaltyshootout_lost column
	 * 
	 * @param     int|array $optionsScorePenaltyshootoutLost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScorePenaltyshootoutLost($optionsScorePenaltyshootoutLost = null, $comparison = null)
	{
		if (is_array($optionsScorePenaltyshootoutLost)) {
			$useMinMax = false;
			if (isset($optionsScorePenaltyshootoutLost['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST, $optionsScorePenaltyshootoutLost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScorePenaltyshootoutLost['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST, $optionsScorePenaltyshootoutLost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST, $optionsScorePenaltyshootoutLost, $comparison);
	}

	/**
	 * Filter the query on the options_score_penaltyshootout_hit column
	 * 
	 * @param     int|array $optionsScorePenaltyshootoutHit The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScorePenaltyshootoutHit($optionsScorePenaltyshootoutHit = null, $comparison = null)
	{
		if (is_array($optionsScorePenaltyshootoutHit)) {
			$useMinMax = false;
			if (isset($optionsScorePenaltyshootoutHit['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT, $optionsScorePenaltyshootoutHit['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScorePenaltyshootoutHit['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT, $optionsScorePenaltyshootoutHit['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT, $optionsScorePenaltyshootoutHit, $comparison);
	}

	/**
	 * Filter the query on the options_score_high_loss column
	 * 
	 * @param     int|array $optionsScoreHighLoss The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreHighLoss($optionsScoreHighLoss = null, $comparison = null)
	{
		if (is_array($optionsScoreHighLoss)) {
			$useMinMax = false;
			if (isset($optionsScoreHighLoss['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS, $optionsScoreHighLoss['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreHighLoss['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS, $optionsScoreHighLoss['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS, $optionsScoreHighLoss, $comparison);
	}

	/**
	 * Filter the query on the options_score_high_win column
	 * 
	 * @param     int|array $optionsScoreHighWin The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreHighWin($optionsScoreHighWin = null, $comparison = null)
	{
		if (is_array($optionsScoreHighWin)) {
			$useMinMax = false;
			if (isset($optionsScoreHighWin['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN, $optionsScoreHighWin['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreHighWin['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN, $optionsScoreHighWin['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN, $optionsScoreHighWin, $comparison);
	}

	/**
	 * Filter the query on the options_score_high_win_loss_treshold column
	 * 
	 * @param     int|array $optionsScoreHighWinLossTreshold The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsScoreHighWinLossTreshold($optionsScoreHighWinLossTreshold = null, $comparison = null)
	{
		if (is_array($optionsScoreHighWinLossTreshold)) {
			$useMinMax = false;
			if (isset($optionsScoreHighWinLossTreshold['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD, $optionsScoreHighWinLossTreshold['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsScoreHighWinLossTreshold['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD, $optionsScoreHighWinLossTreshold['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD, $optionsScoreHighWinLossTreshold, $comparison);
	}

	/**
	 * Filter the query on the options_status_error column
	 * 
	 * @param     int|array $optionsStatusError The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusError($optionsStatusError = null, $comparison = null)
	{
		if (is_array($optionsStatusError)) {
			$useMinMax = false;
			if (isset($optionsStatusError['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR, $optionsStatusError['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusError['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR, $optionsStatusError['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR, $optionsStatusError, $comparison);
	}

	/**
	 * Filter the query on the options_status_error_validation column
	 * 
	 * @param     int|array $optionsStatusErrorValidation The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusErrorValidation($optionsStatusErrorValidation = null, $comparison = null)
	{
		if (is_array($optionsStatusErrorValidation)) {
			$useMinMax = false;
			if (isset($optionsStatusErrorValidation['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION, $optionsStatusErrorValidation['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusErrorValidation['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION, $optionsStatusErrorValidation['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION, $optionsStatusErrorValidation, $comparison);
	}

	/**
	 * Filter the query on the options_status_success column
	 * 
	 * @param     int|array $optionsStatusSuccess The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusSuccess($optionsStatusSuccess = null, $comparison = null)
	{
		if (is_array($optionsStatusSuccess)) {
			$useMinMax = false;
			if (isset($optionsStatusSuccess['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS, $optionsStatusSuccess['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusSuccess['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS, $optionsStatusSuccess['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS, $optionsStatusSuccess, $comparison);
	}

	/**
	 * Filter the query on the options_status_success_insert column
	 * 
	 * @param     int|array $optionsStatusSuccessInsert The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusSuccessInsert($optionsStatusSuccessInsert = null, $comparison = null)
	{
		if (is_array($optionsStatusSuccessInsert)) {
			$useMinMax = false;
			if (isset($optionsStatusSuccessInsert['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT, $optionsStatusSuccessInsert['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusSuccessInsert['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT, $optionsStatusSuccessInsert['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT, $optionsStatusSuccessInsert, $comparison);
	}

	/**
	 * Filter the query on the options_status_success_update column
	 * 
	 * @param     int|array $optionsStatusSuccessUpdate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusSuccessUpdate($optionsStatusSuccessUpdate = null, $comparison = null)
	{
		if (is_array($optionsStatusSuccessUpdate)) {
			$useMinMax = false;
			if (isset($optionsStatusSuccessUpdate['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE, $optionsStatusSuccessUpdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusSuccessUpdate['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE, $optionsStatusSuccessUpdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE, $optionsStatusSuccessUpdate, $comparison);
	}

	/**
	 * Filter the query on the options_status_success_delete column
	 * 
	 * @param     int|array $optionsStatusSuccessDelete The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsStatusSuccessDelete($optionsStatusSuccessDelete = null, $comparison = null)
	{
		if (is_array($optionsStatusSuccessDelete)) {
			$useMinMax = false;
			if (isset($optionsStatusSuccessDelete['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE, $optionsStatusSuccessDelete['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsStatusSuccessDelete['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE, $optionsStatusSuccessDelete['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE, $optionsStatusSuccessDelete, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_players column
	 * 
	 * @param     int|array $optionsLineupMaxPlayers The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxPlayers($optionsLineupMaxPlayers = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxPlayers)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxPlayers['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS, $optionsLineupMaxPlayers['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxPlayers['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS, $optionsLineupMaxPlayers['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS, $optionsLineupMaxPlayers, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_credits column
	 * 
	 * @param     int|array $optionsLineupMaxCredits The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxCredits($optionsLineupMaxCredits = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxCredits)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxCredits['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS, $optionsLineupMaxCredits['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxCredits['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS, $optionsLineupMaxCredits['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS, $optionsLineupMaxCredits, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_players_team column
	 * 
	 * @param     int|array $optionsLineupMaxPlayersTeam The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxPlayersTeam($optionsLineupMaxPlayersTeam = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxPlayersTeam)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxPlayersTeam['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM, $optionsLineupMaxPlayersTeam['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxPlayersTeam['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM, $optionsLineupMaxPlayersTeam['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM, $optionsLineupMaxPlayersTeam, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_min_g column
	 * 
	 * @param     int|array $optionsLineupMinG The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMinG($optionsLineupMinG = null, $comparison = null)
	{
		if (is_array($optionsLineupMinG)) {
			$useMinMax = false;
			if (isset($optionsLineupMinG['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G, $optionsLineupMinG['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMinG['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G, $optionsLineupMinG['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G, $optionsLineupMinG, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_min_d column
	 * 
	 * @param     int|array $optionsLineupMinD The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMinD($optionsLineupMinD = null, $comparison = null)
	{
		if (is_array($optionsLineupMinD)) {
			$useMinMax = false;
			if (isset($optionsLineupMinD['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D, $optionsLineupMinD['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMinD['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D, $optionsLineupMinD['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D, $optionsLineupMinD, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_min_m column
	 * 
	 * @param     int|array $optionsLineupMinM The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMinM($optionsLineupMinM = null, $comparison = null)
	{
		if (is_array($optionsLineupMinM)) {
			$useMinMax = false;
			if (isset($optionsLineupMinM['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M, $optionsLineupMinM['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMinM['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M, $optionsLineupMinM['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M, $optionsLineupMinM, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_min_s column
	 * 
	 * @param     int|array $optionsLineupMinS The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMinS($optionsLineupMinS = null, $comparison = null)
	{
		if (is_array($optionsLineupMinS)) {
			$useMinMax = false;
			if (isset($optionsLineupMinS['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S, $optionsLineupMinS['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMinS['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S, $optionsLineupMinS['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S, $optionsLineupMinS, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_g column
	 * 
	 * @param     int|array $optionsLineupMaxG The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxG($optionsLineupMaxG = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxG)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxG['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G, $optionsLineupMaxG['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxG['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G, $optionsLineupMaxG['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G, $optionsLineupMaxG, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_d column
	 * 
	 * @param     int|array $optionsLineupMaxD The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxD($optionsLineupMaxD = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxD)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxD['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D, $optionsLineupMaxD['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxD['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D, $optionsLineupMaxD['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D, $optionsLineupMaxD, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_m column
	 * 
	 * @param     int|array $optionsLineupMaxM The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxM($optionsLineupMaxM = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxM)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxM['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M, $optionsLineupMaxM['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxM['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M, $optionsLineupMaxM['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M, $optionsLineupMaxM, $comparison);
	}

	/**
	 * Filter the query on the options_lineup_max_s column
	 * 
	 * @param     int|array $optionsLineupMaxS The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsLineupMaxS($optionsLineupMaxS = null, $comparison = null)
	{
		if (is_array($optionsLineupMaxS)) {
			$useMinMax = false;
			if (isset($optionsLineupMaxS['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S, $optionsLineupMaxS['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsLineupMaxS['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S, $optionsLineupMaxS['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S, $optionsLineupMaxS, $comparison);
	}

	/**
	 * Filter the query on the options_game_rankmode column
	 * 
	 * @param     string $optionsGameRankmode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGameRankmode($optionsGameRankmode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($optionsGameRankmode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $optionsGameRankmode)) {
				$optionsGameRankmode = str_replace('*', '%', $optionsGameRankmode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_RANKMODE, $optionsGameRankmode, $comparison);
	}

	/**
	 * Filter the query on the options_game_pricemode column
	 * 
	 * @param     string $optionsGamePricemode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGamePricemode($optionsGamePricemode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($optionsGamePricemode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $optionsGamePricemode)) {
				$optionsGamePricemode = str_replace('*', '%', $optionsGamePricemode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_PRICEMODE, $optionsGamePricemode, $comparison);
	}

	/**
	 * Filter the query on the options_game_pointsmode column
	 * 
	 * @param     string $optionsGamePointsmode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGamePointsmode($optionsGamePointsmode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($optionsGamePointsmode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $optionsGamePointsmode)) {
				$optionsGamePointsmode = str_replace('*', '%', $optionsGamePointsmode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_POINTSMODE, $optionsGamePointsmode, $comparison);
	}

	/**
	 * Filter the query on the options_game_wcpoints column
	 * 
	 * @param     string $optionsGameWcpoints The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGameWcpoints($optionsGameWcpoints = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($optionsGameWcpoints)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $optionsGameWcpoints)) {
				$optionsGameWcpoints = str_replace('*', '%', $optionsGameWcpoints);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_WCPOINTS, $optionsGameWcpoints, $comparison);
	}

	/**
	 * Filter the query on the options_game_remind_hours_before column
	 * 
	 * @param     int|array $optionsGameRemindHoursBefore The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByOptionsGameRemindHoursBefore($optionsGameRemindHoursBefore = null, $comparison = null)
	{
		if (is_array($optionsGameRemindHoursBefore)) {
			$useMinMax = false;
			if (isset($optionsGameRemindHoursBefore['min'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE, $optionsGameRemindHoursBefore['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($optionsGameRemindHoursBefore['max'])) {
				$this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE, $optionsGameRemindHoursBefore['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE, $optionsGameRemindHoursBefore, $comparison);
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbOptionsPeer::OPTIONS_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function joinFfbGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbGame');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'FfbGame');
		}
		
		return $this;
	}

	/**
	 * Use the FfbGame relation FfbGame object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery A secondary query class using the current class as primary query
	 */
	public function useFfbGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbGame($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbGame', 'FfbGameQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbOptions $ffbOptions Object to remove from the list of results
	 *
	 * @return    FfbOptionsQuery The current query, for fluid interface
	 */
	public function prune($ffbOptions = null)
	{
		if ($ffbOptions) {
			$this->addUsingAlias(FfbOptionsPeer::OPTIONS_ID, $ffbOptions->getOptionsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbOptionsQuery
