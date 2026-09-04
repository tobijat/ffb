<?php


/**
 * Base static class for performing query and update operations on the 'ffb_options' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbOptionsPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_options';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbOptions';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbOptions';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbOptionsTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 51;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the OPTIONS_ID field */
	const OPTIONS_ID = 'ffb_options.OPTIONS_ID';

	/** the column name for the OPTIONS_GAME_ID field */
	const OPTIONS_GAME_ID = 'ffb_options.OPTIONS_GAME_ID';

	/** the column name for the OPTIONS_SCORE_MINUTES field */
	const OPTIONS_SCORE_MINUTES = 'ffb_options.OPTIONS_SCORE_MINUTES';

	/** the column name for the OPTIONS_SCORE_MINUTES_TRESHOLD field */
	const OPTIONS_SCORE_MINUTES_TRESHOLD = 'ffb_options.OPTIONS_SCORE_MINUTES_TRESHOLD';

	/** the column name for the OPTIONS_SCORE_MINUTES_GT field */
	const OPTIONS_SCORE_MINUTES_GT = 'ffb_options.OPTIONS_SCORE_MINUTES_GT';

	/** the column name for the OPTIONS_SCORE_MINUTES_LT field */
	const OPTIONS_SCORE_MINUTES_LT = 'ffb_options.OPTIONS_SCORE_MINUTES_LT';

	/** the column name for the OPTIONS_SCORE_MINUTES_LT30 field */
	const OPTIONS_SCORE_MINUTES_LT30 = 'ffb_options.OPTIONS_SCORE_MINUTES_LT30';

	/** the column name for the OPTIONS_SCORE_GOALS_G field */
	const OPTIONS_SCORE_GOALS_G = 'ffb_options.OPTIONS_SCORE_GOALS_G';

	/** the column name for the OPTIONS_SCORE_GOALS_D field */
	const OPTIONS_SCORE_GOALS_D = 'ffb_options.OPTIONS_SCORE_GOALS_D';

	/** the column name for the OPTIONS_SCORE_GOALS_M field */
	const OPTIONS_SCORE_GOALS_M = 'ffb_options.OPTIONS_SCORE_GOALS_M';

	/** the column name for the OPTIONS_SCORE_GOALS_S field */
	const OPTIONS_SCORE_GOALS_S = 'ffb_options.OPTIONS_SCORE_GOALS_S';

	/** the column name for the OPTIONS_SCORE_ASSISTS field */
	const OPTIONS_SCORE_ASSISTS = 'ffb_options.OPTIONS_SCORE_ASSISTS';

	/** the column name for the OPTIONS_SCORE_NO_OPPGOALS_G field */
	const OPTIONS_SCORE_NO_OPPGOALS_G = 'ffb_options.OPTIONS_SCORE_NO_OPPGOALS_G';

	/** the column name for the OPTIONS_SCORE_NO_OPPGOALS_D field */
	const OPTIONS_SCORE_NO_OPPGOALS_D = 'ffb_options.OPTIONS_SCORE_NO_OPPGOALS_D';

	/** the column name for the OPTIONS_SCORE_NO_OPPGOALS_M field */
	const OPTIONS_SCORE_NO_OPPGOALS_M = 'ffb_options.OPTIONS_SCORE_NO_OPPGOALS_M';

	/** the column name for the OPTIONS_SCORE_OPPGOALS_G field */
	const OPTIONS_SCORE_OPPGOALS_G = 'ffb_options.OPTIONS_SCORE_OPPGOALS_G';

	/** the column name for the OPTIONS_SCORE_OPPGOALS_D field */
	const OPTIONS_SCORE_OPPGOALS_D = 'ffb_options.OPTIONS_SCORE_OPPGOALS_D';

	/** the column name for the OPTIONS_SCORE_OWNGOALS field */
	const OPTIONS_SCORE_OWNGOALS = 'ffb_options.OPTIONS_SCORE_OWNGOALS';

	/** the column name for the OPTIONS_SCORE_CARD_Y field */
	const OPTIONS_SCORE_CARD_Y = 'ffb_options.OPTIONS_SCORE_CARD_Y';

	/** the column name for the OPTIONS_SCORE_CARD_R field */
	const OPTIONS_SCORE_CARD_R = 'ffb_options.OPTIONS_SCORE_CARD_R';

	/** the column name for the OPTIONS_SCORE_CARD_YR field */
	const OPTIONS_SCORE_CARD_YR = 'ffb_options.OPTIONS_SCORE_CARD_YR';

	/** the column name for the OPTIONS_SCORE_PENALTY_SAVED field */
	const OPTIONS_SCORE_PENALTY_SAVED = 'ffb_options.OPTIONS_SCORE_PENALTY_SAVED';

	/** the column name for the OPTIONS_SCORE_PENALTY_LOST field */
	const OPTIONS_SCORE_PENALTY_LOST = 'ffb_options.OPTIONS_SCORE_PENALTY_LOST';

	/** the column name for the OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE field */
	const OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE = 'ffb_options.OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE';

	/** the column name for the OPTIONS_SCORE_PENALTYSHOOTOUT_LOST field */
	const OPTIONS_SCORE_PENALTYSHOOTOUT_LOST = 'ffb_options.OPTIONS_SCORE_PENALTYSHOOTOUT_LOST';

	/** the column name for the OPTIONS_SCORE_PENALTYSHOOTOUT_HIT field */
	const OPTIONS_SCORE_PENALTYSHOOTOUT_HIT = 'ffb_options.OPTIONS_SCORE_PENALTYSHOOTOUT_HIT';

	/** the column name for the OPTIONS_SCORE_HIGH_LOSS field */
	const OPTIONS_SCORE_HIGH_LOSS = 'ffb_options.OPTIONS_SCORE_HIGH_LOSS';

	/** the column name for the OPTIONS_SCORE_HIGH_WIN field */
	const OPTIONS_SCORE_HIGH_WIN = 'ffb_options.OPTIONS_SCORE_HIGH_WIN';

	/** the column name for the OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD field */
	const OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD = 'ffb_options.OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD';

	/** the column name for the OPTIONS_STATUS_ERROR field */
	const OPTIONS_STATUS_ERROR = 'ffb_options.OPTIONS_STATUS_ERROR';

	/** the column name for the OPTIONS_STATUS_ERROR_VALIDATION field */
	const OPTIONS_STATUS_ERROR_VALIDATION = 'ffb_options.OPTIONS_STATUS_ERROR_VALIDATION';

	/** the column name for the OPTIONS_STATUS_SUCCESS field */
	const OPTIONS_STATUS_SUCCESS = 'ffb_options.OPTIONS_STATUS_SUCCESS';

	/** the column name for the OPTIONS_STATUS_SUCCESS_INSERT field */
	const OPTIONS_STATUS_SUCCESS_INSERT = 'ffb_options.OPTIONS_STATUS_SUCCESS_INSERT';

	/** the column name for the OPTIONS_STATUS_SUCCESS_UPDATE field */
	const OPTIONS_STATUS_SUCCESS_UPDATE = 'ffb_options.OPTIONS_STATUS_SUCCESS_UPDATE';

	/** the column name for the OPTIONS_STATUS_SUCCESS_DELETE field */
	const OPTIONS_STATUS_SUCCESS_DELETE = 'ffb_options.OPTIONS_STATUS_SUCCESS_DELETE';

	/** the column name for the OPTIONS_LINEUP_MAX_PLAYERS field */
	const OPTIONS_LINEUP_MAX_PLAYERS = 'ffb_options.OPTIONS_LINEUP_MAX_PLAYERS';

	/** the column name for the OPTIONS_LINEUP_MAX_CREDITS field */
	const OPTIONS_LINEUP_MAX_CREDITS = 'ffb_options.OPTIONS_LINEUP_MAX_CREDITS';

	/** the column name for the OPTIONS_LINEUP_MAX_PLAYERS_TEAM field */
	const OPTIONS_LINEUP_MAX_PLAYERS_TEAM = 'ffb_options.OPTIONS_LINEUP_MAX_PLAYERS_TEAM';

	/** the column name for the OPTIONS_LINEUP_MIN_G field */
	const OPTIONS_LINEUP_MIN_G = 'ffb_options.OPTIONS_LINEUP_MIN_G';

	/** the column name for the OPTIONS_LINEUP_MIN_D field */
	const OPTIONS_LINEUP_MIN_D = 'ffb_options.OPTIONS_LINEUP_MIN_D';

	/** the column name for the OPTIONS_LINEUP_MIN_M field */
	const OPTIONS_LINEUP_MIN_M = 'ffb_options.OPTIONS_LINEUP_MIN_M';

	/** the column name for the OPTIONS_LINEUP_MIN_S field */
	const OPTIONS_LINEUP_MIN_S = 'ffb_options.OPTIONS_LINEUP_MIN_S';

	/** the column name for the OPTIONS_LINEUP_MAX_G field */
	const OPTIONS_LINEUP_MAX_G = 'ffb_options.OPTIONS_LINEUP_MAX_G';

	/** the column name for the OPTIONS_LINEUP_MAX_D field */
	const OPTIONS_LINEUP_MAX_D = 'ffb_options.OPTIONS_LINEUP_MAX_D';

	/** the column name for the OPTIONS_LINEUP_MAX_M field */
	const OPTIONS_LINEUP_MAX_M = 'ffb_options.OPTIONS_LINEUP_MAX_M';

	/** the column name for the OPTIONS_LINEUP_MAX_S field */
	const OPTIONS_LINEUP_MAX_S = 'ffb_options.OPTIONS_LINEUP_MAX_S';

	/** the column name for the OPTIONS_GAME_RANKMODE field */
	const OPTIONS_GAME_RANKMODE = 'ffb_options.OPTIONS_GAME_RANKMODE';

	/** the column name for the OPTIONS_GAME_PRICEMODE field */
	const OPTIONS_GAME_PRICEMODE = 'ffb_options.OPTIONS_GAME_PRICEMODE';

	/** the column name for the OPTIONS_GAME_POINTSMODE field */
	const OPTIONS_GAME_POINTSMODE = 'ffb_options.OPTIONS_GAME_POINTSMODE';

	/** the column name for the OPTIONS_GAME_WCPOINTS field */
	const OPTIONS_GAME_WCPOINTS = 'ffb_options.OPTIONS_GAME_WCPOINTS';

	/** the column name for the OPTIONS_GAME_REMIND_HOURS_BEFORE field */
	const OPTIONS_GAME_REMIND_HOURS_BEFORE = 'ffb_options.OPTIONS_GAME_REMIND_HOURS_BEFORE';

	/**
	 * An identiy map to hold any loaded instances of FfbOptions objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbOptions[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('OptionsId', 'OptionsGameId', 'OptionsScoreMinutes', 'OptionsScoreMinutesTreshold', 'OptionsScoreMinutesGt', 'OptionsScoreMinutesLt', 'OptionsScoreMinutesLt30', 'OptionsScoreGoalsG', 'OptionsScoreGoalsD', 'OptionsScoreGoalsM', 'OptionsScoreGoalsS', 'OptionsScoreAssists', 'OptionsScoreNoOppgoalsG', 'OptionsScoreNoOppgoalsD', 'OptionsScoreNoOppgoalsM', 'OptionsScoreOppgoalsG', 'OptionsScoreOppgoalsD', 'OptionsScoreOwngoals', 'OptionsScoreCardY', 'OptionsScoreCardR', 'OptionsScoreCardYr', 'OptionsScorePenaltySaved', 'OptionsScorePenaltyLost', 'OptionsScorePenaltyshootoutSave', 'OptionsScorePenaltyshootoutLost', 'OptionsScorePenaltyshootoutHit', 'OptionsScoreHighLoss', 'OptionsScoreHighWin', 'OptionsScoreHighWinLossTreshold', 'OptionsStatusError', 'OptionsStatusErrorValidation', 'OptionsStatusSuccess', 'OptionsStatusSuccessInsert', 'OptionsStatusSuccessUpdate', 'OptionsStatusSuccessDelete', 'OptionsLineupMaxPlayers', 'OptionsLineupMaxCredits', 'OptionsLineupMaxPlayersTeam', 'OptionsLineupMinG', 'OptionsLineupMinD', 'OptionsLineupMinM', 'OptionsLineupMinS', 'OptionsLineupMaxG', 'OptionsLineupMaxD', 'OptionsLineupMaxM', 'OptionsLineupMaxS', 'OptionsGameRankmode', 'OptionsGamePricemode', 'OptionsGamePointsmode', 'OptionsGameWcpoints', 'OptionsGameRemindHoursBefore', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('optionsId', 'optionsGameId', 'optionsScoreMinutes', 'optionsScoreMinutesTreshold', 'optionsScoreMinutesGt', 'optionsScoreMinutesLt', 'optionsScoreMinutesLt30', 'optionsScoreGoalsG', 'optionsScoreGoalsD', 'optionsScoreGoalsM', 'optionsScoreGoalsS', 'optionsScoreAssists', 'optionsScoreNoOppgoalsG', 'optionsScoreNoOppgoalsD', 'optionsScoreNoOppgoalsM', 'optionsScoreOppgoalsG', 'optionsScoreOppgoalsD', 'optionsScoreOwngoals', 'optionsScoreCardY', 'optionsScoreCardR', 'optionsScoreCardYr', 'optionsScorePenaltySaved', 'optionsScorePenaltyLost', 'optionsScorePenaltyshootoutSave', 'optionsScorePenaltyshootoutLost', 'optionsScorePenaltyshootoutHit', 'optionsScoreHighLoss', 'optionsScoreHighWin', 'optionsScoreHighWinLossTreshold', 'optionsStatusError', 'optionsStatusErrorValidation', 'optionsStatusSuccess', 'optionsStatusSuccessInsert', 'optionsStatusSuccessUpdate', 'optionsStatusSuccessDelete', 'optionsLineupMaxPlayers', 'optionsLineupMaxCredits', 'optionsLineupMaxPlayersTeam', 'optionsLineupMinG', 'optionsLineupMinD', 'optionsLineupMinM', 'optionsLineupMinS', 'optionsLineupMaxG', 'optionsLineupMaxD', 'optionsLineupMaxM', 'optionsLineupMaxS', 'optionsGameRankmode', 'optionsGamePricemode', 'optionsGamePointsmode', 'optionsGameWcpoints', 'optionsGameRemindHoursBefore', ),
		BasePeer::TYPE_COLNAME => array (self::OPTIONS_ID, self::OPTIONS_GAME_ID, self::OPTIONS_SCORE_MINUTES, self::OPTIONS_SCORE_MINUTES_TRESHOLD, self::OPTIONS_SCORE_MINUTES_GT, self::OPTIONS_SCORE_MINUTES_LT, self::OPTIONS_SCORE_MINUTES_LT30, self::OPTIONS_SCORE_GOALS_G, self::OPTIONS_SCORE_GOALS_D, self::OPTIONS_SCORE_GOALS_M, self::OPTIONS_SCORE_GOALS_S, self::OPTIONS_SCORE_ASSISTS, self::OPTIONS_SCORE_NO_OPPGOALS_G, self::OPTIONS_SCORE_NO_OPPGOALS_D, self::OPTIONS_SCORE_NO_OPPGOALS_M, self::OPTIONS_SCORE_OPPGOALS_G, self::OPTIONS_SCORE_OPPGOALS_D, self::OPTIONS_SCORE_OWNGOALS, self::OPTIONS_SCORE_CARD_Y, self::OPTIONS_SCORE_CARD_R, self::OPTIONS_SCORE_CARD_YR, self::OPTIONS_SCORE_PENALTY_SAVED, self::OPTIONS_SCORE_PENALTY_LOST, self::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE, self::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST, self::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT, self::OPTIONS_SCORE_HIGH_LOSS, self::OPTIONS_SCORE_HIGH_WIN, self::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD, self::OPTIONS_STATUS_ERROR, self::OPTIONS_STATUS_ERROR_VALIDATION, self::OPTIONS_STATUS_SUCCESS, self::OPTIONS_STATUS_SUCCESS_INSERT, self::OPTIONS_STATUS_SUCCESS_UPDATE, self::OPTIONS_STATUS_SUCCESS_DELETE, self::OPTIONS_LINEUP_MAX_PLAYERS, self::OPTIONS_LINEUP_MAX_CREDITS, self::OPTIONS_LINEUP_MAX_PLAYERS_TEAM, self::OPTIONS_LINEUP_MIN_G, self::OPTIONS_LINEUP_MIN_D, self::OPTIONS_LINEUP_MIN_M, self::OPTIONS_LINEUP_MIN_S, self::OPTIONS_LINEUP_MAX_G, self::OPTIONS_LINEUP_MAX_D, self::OPTIONS_LINEUP_MAX_M, self::OPTIONS_LINEUP_MAX_S, self::OPTIONS_GAME_RANKMODE, self::OPTIONS_GAME_PRICEMODE, self::OPTIONS_GAME_POINTSMODE, self::OPTIONS_GAME_WCPOINTS, self::OPTIONS_GAME_REMIND_HOURS_BEFORE, ),
		BasePeer::TYPE_RAW_COLNAME => array ('OPTIONS_ID', 'OPTIONS_GAME_ID', 'OPTIONS_SCORE_MINUTES', 'OPTIONS_SCORE_MINUTES_TRESHOLD', 'OPTIONS_SCORE_MINUTES_GT', 'OPTIONS_SCORE_MINUTES_LT', 'OPTIONS_SCORE_MINUTES_LT30', 'OPTIONS_SCORE_GOALS_G', 'OPTIONS_SCORE_GOALS_D', 'OPTIONS_SCORE_GOALS_M', 'OPTIONS_SCORE_GOALS_S', 'OPTIONS_SCORE_ASSISTS', 'OPTIONS_SCORE_NO_OPPGOALS_G', 'OPTIONS_SCORE_NO_OPPGOALS_D', 'OPTIONS_SCORE_NO_OPPGOALS_M', 'OPTIONS_SCORE_OPPGOALS_G', 'OPTIONS_SCORE_OPPGOALS_D', 'OPTIONS_SCORE_OWNGOALS', 'OPTIONS_SCORE_CARD_Y', 'OPTIONS_SCORE_CARD_R', 'OPTIONS_SCORE_CARD_YR', 'OPTIONS_SCORE_PENALTY_SAVED', 'OPTIONS_SCORE_PENALTY_LOST', 'OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE', 'OPTIONS_SCORE_PENALTYSHOOTOUT_LOST', 'OPTIONS_SCORE_PENALTYSHOOTOUT_HIT', 'OPTIONS_SCORE_HIGH_LOSS', 'OPTIONS_SCORE_HIGH_WIN', 'OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD', 'OPTIONS_STATUS_ERROR', 'OPTIONS_STATUS_ERROR_VALIDATION', 'OPTIONS_STATUS_SUCCESS', 'OPTIONS_STATUS_SUCCESS_INSERT', 'OPTIONS_STATUS_SUCCESS_UPDATE', 'OPTIONS_STATUS_SUCCESS_DELETE', 'OPTIONS_LINEUP_MAX_PLAYERS', 'OPTIONS_LINEUP_MAX_CREDITS', 'OPTIONS_LINEUP_MAX_PLAYERS_TEAM', 'OPTIONS_LINEUP_MIN_G', 'OPTIONS_LINEUP_MIN_D', 'OPTIONS_LINEUP_MIN_M', 'OPTIONS_LINEUP_MIN_S', 'OPTIONS_LINEUP_MAX_G', 'OPTIONS_LINEUP_MAX_D', 'OPTIONS_LINEUP_MAX_M', 'OPTIONS_LINEUP_MAX_S', 'OPTIONS_GAME_RANKMODE', 'OPTIONS_GAME_PRICEMODE', 'OPTIONS_GAME_POINTSMODE', 'OPTIONS_GAME_WCPOINTS', 'OPTIONS_GAME_REMIND_HOURS_BEFORE', ),
		BasePeer::TYPE_FIELDNAME => array ('options_id', 'options_game_id', 'options_score_minutes', 'options_score_minutes_treshold', 'options_score_minutes_gt', 'options_score_minutes_lt', 'options_score_minutes_lt30', 'options_score_goals_g', 'options_score_goals_d', 'options_score_goals_m', 'options_score_goals_s', 'options_score_assists', 'options_score_no_oppgoals_g', 'options_score_no_oppgoals_d', 'options_score_no_oppgoals_m', 'options_score_oppgoals_g', 'options_score_oppgoals_d', 'options_score_owngoals', 'options_score_card_y', 'options_score_card_r', 'options_score_card_yr', 'options_score_penalty_saved', 'options_score_penalty_lost', 'options_score_penaltyshootout_save', 'options_score_penaltyshootout_lost', 'options_score_penaltyshootout_hit', 'options_score_high_loss', 'options_score_high_win', 'options_score_high_win_loss_treshold', 'options_status_error', 'options_status_error_validation', 'options_status_success', 'options_status_success_insert', 'options_status_success_update', 'options_status_success_delete', 'options_lineup_max_players', 'options_lineup_max_credits', 'options_lineup_max_players_team', 'options_lineup_min_g', 'options_lineup_min_d', 'options_lineup_min_m', 'options_lineup_min_s', 'options_lineup_max_g', 'options_lineup_max_d', 'options_lineup_max_m', 'options_lineup_max_s', 'options_game_rankmode', 'options_game_pricemode', 'options_game_pointsmode', 'options_game_wcpoints', 'options_game_remind_hours_before', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('OptionsId' => 0, 'OptionsGameId' => 1, 'OptionsScoreMinutes' => 2, 'OptionsScoreMinutesTreshold' => 3, 'OptionsScoreMinutesGt' => 4, 'OptionsScoreMinutesLt' => 5, 'OptionsScoreMinutesLt30' => 6, 'OptionsScoreGoalsG' => 7, 'OptionsScoreGoalsD' => 8, 'OptionsScoreGoalsM' => 9, 'OptionsScoreGoalsS' => 10, 'OptionsScoreAssists' => 11, 'OptionsScoreNoOppgoalsG' => 12, 'OptionsScoreNoOppgoalsD' => 13, 'OptionsScoreNoOppgoalsM' => 14, 'OptionsScoreOppgoalsG' => 15, 'OptionsScoreOppgoalsD' => 16, 'OptionsScoreOwngoals' => 17, 'OptionsScoreCardY' => 18, 'OptionsScoreCardR' => 19, 'OptionsScoreCardYr' => 20, 'OptionsScorePenaltySaved' => 21, 'OptionsScorePenaltyLost' => 22, 'OptionsScorePenaltyshootoutSave' => 23, 'OptionsScorePenaltyshootoutLost' => 24, 'OptionsScorePenaltyshootoutHit' => 25, 'OptionsScoreHighLoss' => 26, 'OptionsScoreHighWin' => 27, 'OptionsScoreHighWinLossTreshold' => 28, 'OptionsStatusError' => 29, 'OptionsStatusErrorValidation' => 30, 'OptionsStatusSuccess' => 31, 'OptionsStatusSuccessInsert' => 32, 'OptionsStatusSuccessUpdate' => 33, 'OptionsStatusSuccessDelete' => 34, 'OptionsLineupMaxPlayers' => 35, 'OptionsLineupMaxCredits' => 36, 'OptionsLineupMaxPlayersTeam' => 37, 'OptionsLineupMinG' => 38, 'OptionsLineupMinD' => 39, 'OptionsLineupMinM' => 40, 'OptionsLineupMinS' => 41, 'OptionsLineupMaxG' => 42, 'OptionsLineupMaxD' => 43, 'OptionsLineupMaxM' => 44, 'OptionsLineupMaxS' => 45, 'OptionsGameRankmode' => 46, 'OptionsGamePricemode' => 47, 'OptionsGamePointsmode' => 48, 'OptionsGameWcpoints' => 49, 'OptionsGameRemindHoursBefore' => 50, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('optionsId' => 0, 'optionsGameId' => 1, 'optionsScoreMinutes' => 2, 'optionsScoreMinutesTreshold' => 3, 'optionsScoreMinutesGt' => 4, 'optionsScoreMinutesLt' => 5, 'optionsScoreMinutesLt30' => 6, 'optionsScoreGoalsG' => 7, 'optionsScoreGoalsD' => 8, 'optionsScoreGoalsM' => 9, 'optionsScoreGoalsS' => 10, 'optionsScoreAssists' => 11, 'optionsScoreNoOppgoalsG' => 12, 'optionsScoreNoOppgoalsD' => 13, 'optionsScoreNoOppgoalsM' => 14, 'optionsScoreOppgoalsG' => 15, 'optionsScoreOppgoalsD' => 16, 'optionsScoreOwngoals' => 17, 'optionsScoreCardY' => 18, 'optionsScoreCardR' => 19, 'optionsScoreCardYr' => 20, 'optionsScorePenaltySaved' => 21, 'optionsScorePenaltyLost' => 22, 'optionsScorePenaltyshootoutSave' => 23, 'optionsScorePenaltyshootoutLost' => 24, 'optionsScorePenaltyshootoutHit' => 25, 'optionsScoreHighLoss' => 26, 'optionsScoreHighWin' => 27, 'optionsScoreHighWinLossTreshold' => 28, 'optionsStatusError' => 29, 'optionsStatusErrorValidation' => 30, 'optionsStatusSuccess' => 31, 'optionsStatusSuccessInsert' => 32, 'optionsStatusSuccessUpdate' => 33, 'optionsStatusSuccessDelete' => 34, 'optionsLineupMaxPlayers' => 35, 'optionsLineupMaxCredits' => 36, 'optionsLineupMaxPlayersTeam' => 37, 'optionsLineupMinG' => 38, 'optionsLineupMinD' => 39, 'optionsLineupMinM' => 40, 'optionsLineupMinS' => 41, 'optionsLineupMaxG' => 42, 'optionsLineupMaxD' => 43, 'optionsLineupMaxM' => 44, 'optionsLineupMaxS' => 45, 'optionsGameRankmode' => 46, 'optionsGamePricemode' => 47, 'optionsGamePointsmode' => 48, 'optionsGameWcpoints' => 49, 'optionsGameRemindHoursBefore' => 50, ),
		BasePeer::TYPE_COLNAME => array (self::OPTIONS_ID => 0, self::OPTIONS_GAME_ID => 1, self::OPTIONS_SCORE_MINUTES => 2, self::OPTIONS_SCORE_MINUTES_TRESHOLD => 3, self::OPTIONS_SCORE_MINUTES_GT => 4, self::OPTIONS_SCORE_MINUTES_LT => 5, self::OPTIONS_SCORE_MINUTES_LT30 => 6, self::OPTIONS_SCORE_GOALS_G => 7, self::OPTIONS_SCORE_GOALS_D => 8, self::OPTIONS_SCORE_GOALS_M => 9, self::OPTIONS_SCORE_GOALS_S => 10, self::OPTIONS_SCORE_ASSISTS => 11, self::OPTIONS_SCORE_NO_OPPGOALS_G => 12, self::OPTIONS_SCORE_NO_OPPGOALS_D => 13, self::OPTIONS_SCORE_NO_OPPGOALS_M => 14, self::OPTIONS_SCORE_OPPGOALS_G => 15, self::OPTIONS_SCORE_OPPGOALS_D => 16, self::OPTIONS_SCORE_OWNGOALS => 17, self::OPTIONS_SCORE_CARD_Y => 18, self::OPTIONS_SCORE_CARD_R => 19, self::OPTIONS_SCORE_CARD_YR => 20, self::OPTIONS_SCORE_PENALTY_SAVED => 21, self::OPTIONS_SCORE_PENALTY_LOST => 22, self::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE => 23, self::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST => 24, self::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT => 25, self::OPTIONS_SCORE_HIGH_LOSS => 26, self::OPTIONS_SCORE_HIGH_WIN => 27, self::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD => 28, self::OPTIONS_STATUS_ERROR => 29, self::OPTIONS_STATUS_ERROR_VALIDATION => 30, self::OPTIONS_STATUS_SUCCESS => 31, self::OPTIONS_STATUS_SUCCESS_INSERT => 32, self::OPTIONS_STATUS_SUCCESS_UPDATE => 33, self::OPTIONS_STATUS_SUCCESS_DELETE => 34, self::OPTIONS_LINEUP_MAX_PLAYERS => 35, self::OPTIONS_LINEUP_MAX_CREDITS => 36, self::OPTIONS_LINEUP_MAX_PLAYERS_TEAM => 37, self::OPTIONS_LINEUP_MIN_G => 38, self::OPTIONS_LINEUP_MIN_D => 39, self::OPTIONS_LINEUP_MIN_M => 40, self::OPTIONS_LINEUP_MIN_S => 41, self::OPTIONS_LINEUP_MAX_G => 42, self::OPTIONS_LINEUP_MAX_D => 43, self::OPTIONS_LINEUP_MAX_M => 44, self::OPTIONS_LINEUP_MAX_S => 45, self::OPTIONS_GAME_RANKMODE => 46, self::OPTIONS_GAME_PRICEMODE => 47, self::OPTIONS_GAME_POINTSMODE => 48, self::OPTIONS_GAME_WCPOINTS => 49, self::OPTIONS_GAME_REMIND_HOURS_BEFORE => 50, ),
		BasePeer::TYPE_RAW_COLNAME => array ('OPTIONS_ID' => 0, 'OPTIONS_GAME_ID' => 1, 'OPTIONS_SCORE_MINUTES' => 2, 'OPTIONS_SCORE_MINUTES_TRESHOLD' => 3, 'OPTIONS_SCORE_MINUTES_GT' => 4, 'OPTIONS_SCORE_MINUTES_LT' => 5, 'OPTIONS_SCORE_MINUTES_LT30' => 6, 'OPTIONS_SCORE_GOALS_G' => 7, 'OPTIONS_SCORE_GOALS_D' => 8, 'OPTIONS_SCORE_GOALS_M' => 9, 'OPTIONS_SCORE_GOALS_S' => 10, 'OPTIONS_SCORE_ASSISTS' => 11, 'OPTIONS_SCORE_NO_OPPGOALS_G' => 12, 'OPTIONS_SCORE_NO_OPPGOALS_D' => 13, 'OPTIONS_SCORE_NO_OPPGOALS_M' => 14, 'OPTIONS_SCORE_OPPGOALS_G' => 15, 'OPTIONS_SCORE_OPPGOALS_D' => 16, 'OPTIONS_SCORE_OWNGOALS' => 17, 'OPTIONS_SCORE_CARD_Y' => 18, 'OPTIONS_SCORE_CARD_R' => 19, 'OPTIONS_SCORE_CARD_YR' => 20, 'OPTIONS_SCORE_PENALTY_SAVED' => 21, 'OPTIONS_SCORE_PENALTY_LOST' => 22, 'OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE' => 23, 'OPTIONS_SCORE_PENALTYSHOOTOUT_LOST' => 24, 'OPTIONS_SCORE_PENALTYSHOOTOUT_HIT' => 25, 'OPTIONS_SCORE_HIGH_LOSS' => 26, 'OPTIONS_SCORE_HIGH_WIN' => 27, 'OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD' => 28, 'OPTIONS_STATUS_ERROR' => 29, 'OPTIONS_STATUS_ERROR_VALIDATION' => 30, 'OPTIONS_STATUS_SUCCESS' => 31, 'OPTIONS_STATUS_SUCCESS_INSERT' => 32, 'OPTIONS_STATUS_SUCCESS_UPDATE' => 33, 'OPTIONS_STATUS_SUCCESS_DELETE' => 34, 'OPTIONS_LINEUP_MAX_PLAYERS' => 35, 'OPTIONS_LINEUP_MAX_CREDITS' => 36, 'OPTIONS_LINEUP_MAX_PLAYERS_TEAM' => 37, 'OPTIONS_LINEUP_MIN_G' => 38, 'OPTIONS_LINEUP_MIN_D' => 39, 'OPTIONS_LINEUP_MIN_M' => 40, 'OPTIONS_LINEUP_MIN_S' => 41, 'OPTIONS_LINEUP_MAX_G' => 42, 'OPTIONS_LINEUP_MAX_D' => 43, 'OPTIONS_LINEUP_MAX_M' => 44, 'OPTIONS_LINEUP_MAX_S' => 45, 'OPTIONS_GAME_RANKMODE' => 46, 'OPTIONS_GAME_PRICEMODE' => 47, 'OPTIONS_GAME_POINTSMODE' => 48, 'OPTIONS_GAME_WCPOINTS' => 49, 'OPTIONS_GAME_REMIND_HOURS_BEFORE' => 50, ),
		BasePeer::TYPE_FIELDNAME => array ('options_id' => 0, 'options_game_id' => 1, 'options_score_minutes' => 2, 'options_score_minutes_treshold' => 3, 'options_score_minutes_gt' => 4, 'options_score_minutes_lt' => 5, 'options_score_minutes_lt30' => 6, 'options_score_goals_g' => 7, 'options_score_goals_d' => 8, 'options_score_goals_m' => 9, 'options_score_goals_s' => 10, 'options_score_assists' => 11, 'options_score_no_oppgoals_g' => 12, 'options_score_no_oppgoals_d' => 13, 'options_score_no_oppgoals_m' => 14, 'options_score_oppgoals_g' => 15, 'options_score_oppgoals_d' => 16, 'options_score_owngoals' => 17, 'options_score_card_y' => 18, 'options_score_card_r' => 19, 'options_score_card_yr' => 20, 'options_score_penalty_saved' => 21, 'options_score_penalty_lost' => 22, 'options_score_penaltyshootout_save' => 23, 'options_score_penaltyshootout_lost' => 24, 'options_score_penaltyshootout_hit' => 25, 'options_score_high_loss' => 26, 'options_score_high_win' => 27, 'options_score_high_win_loss_treshold' => 28, 'options_status_error' => 29, 'options_status_error_validation' => 30, 'options_status_success' => 31, 'options_status_success_insert' => 32, 'options_status_success_update' => 33, 'options_status_success_delete' => 34, 'options_lineup_max_players' => 35, 'options_lineup_max_credits' => 36, 'options_lineup_max_players_team' => 37, 'options_lineup_min_g' => 38, 'options_lineup_min_d' => 39, 'options_lineup_min_m' => 40, 'options_lineup_min_s' => 41, 'options_lineup_max_g' => 42, 'options_lineup_max_d' => 43, 'options_lineup_max_m' => 44, 'options_lineup_max_s' => 45, 'options_game_rankmode' => 46, 'options_game_pricemode' => 47, 'options_game_pointsmode' => 48, 'options_game_wcpoints' => 49, 'options_game_remind_hours_before' => 50, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. FfbOptionsPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbOptionsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      Criteria $criteria object containing the columns to add.
	 * @param      string   $alias    optional table alias
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria, $alias = null)
	{
		if (null === $alias) {
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_ID);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_ID);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_MINUTES);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_CARD_R);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_ERROR);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_RANKMODE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_PRICEMODE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_POINTSMODE);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_WCPOINTS);
			$criteria->addSelectColumn(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE);
		} else {
			$criteria->addSelectColumn($alias . '.OPTIONS_ID');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_ID');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_MINUTES');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_MINUTES_TRESHOLD');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_MINUTES_GT');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_MINUTES_LT');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_MINUTES_LT30');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_GOALS_G');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_GOALS_D');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_GOALS_M');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_GOALS_S');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_ASSISTS');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_NO_OPPGOALS_G');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_NO_OPPGOALS_D');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_NO_OPPGOALS_M');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_OPPGOALS_G');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_OPPGOALS_D');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_OWNGOALS');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_CARD_Y');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_CARD_R');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_CARD_YR');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_PENALTY_SAVED');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_PENALTY_LOST');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_PENALTYSHOOTOUT_LOST');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_PENALTYSHOOTOUT_HIT');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_HIGH_LOSS');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_HIGH_WIN');
			$criteria->addSelectColumn($alias . '.OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_ERROR');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_ERROR_VALIDATION');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_SUCCESS');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_SUCCESS_INSERT');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_SUCCESS_UPDATE');
			$criteria->addSelectColumn($alias . '.OPTIONS_STATUS_SUCCESS_DELETE');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_PLAYERS');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_CREDITS');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_PLAYERS_TEAM');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MIN_G');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MIN_D');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MIN_M');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MIN_S');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_G');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_D');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_M');
			$criteria->addSelectColumn($alias . '.OPTIONS_LINEUP_MAX_S');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_RANKMODE');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_PRICEMODE');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_POINTSMODE');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_WCPOINTS');
			$criteria->addSelectColumn($alias . '.OPTIONS_GAME_REMIND_HOURS_BEFORE');
		}
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, ?PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbOptionsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbOptionsPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     FfbOptions
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, ?PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbOptionsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, ?PropelPDO $con = null)
	{
		return FfbOptionsPeer::populateObjects(FfbOptionsPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbOptionsPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      FfbOptions $value A FfbOptions object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbOptions $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getOptionsId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A FfbOptions object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbOptions) {
				$key = (string) $value->getOptionsId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbOptions object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     FfbOptions Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to ffb_options
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol] === null) {
			return null;
		}
		return (string) $row[$startcol];
	}

	/**
	 * Retrieves the primary key from the DB resultset row 
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, an array of the primary key columns will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     mixed The primary key of the row
	 */
	public static function getPrimaryKeyFromRow($row, $startcol = 0)
	{
		return (int) $row[$startcol];
	}
	
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = FfbOptionsPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbOptionsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbOptionsPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbOptionsPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Populates an object of the default type or an object that inherit from the default.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     array (FfbOptions object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = FfbOptionsPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = FfbOptionsPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + FfbOptionsPeer::NUM_COLUMNS;
		} else {
			$cls = FfbOptionsPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			FfbOptionsPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
	}

	/**
	 * Returns the number of rows matching criteria, joining the related FfbGame table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbGame(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbOptionsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbOptionsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbOptionsPeer::OPTIONS_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of FfbOptions objects pre-filled with their FfbGame objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbOptions objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbGame(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbOptionsPeer::addSelectColumns($criteria);
		$startcol = (FfbOptionsPeer::NUM_COLUMNS - FfbOptionsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbGamePeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbOptionsPeer::OPTIONS_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbOptionsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbOptionsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbOptionsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbOptionsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbGamePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbGamePeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbGamePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbOptions) to $obj2 (FfbGame)
				$obj2->addFfbOptions($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbOptionsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbOptionsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbOptionsPeer::OPTIONS_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}

	/**
	 * Selects a collection of FfbOptions objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbOptions objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbOptionsPeer::addSelectColumns($criteria);
		$startcol2 = (FfbOptionsPeer::NUM_COLUMNS - FfbOptionsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbOptionsPeer::OPTIONS_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbOptionsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbOptionsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbOptionsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbOptionsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined FfbGame rows

			$key2 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = FfbGamePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbGamePeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbGamePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (FfbOptions) to the collection in $obj2 (FfbGame)
				$obj2->addFfbOptions($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseFfbOptionsPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbOptionsPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbOptionsTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * If $withPrefix is true, the returned path
	 * uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @param      boolean $withPrefix Whether or not to return the path with the class name
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? FfbOptionsPeer::CLASS_DEFAULT : FfbOptionsPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbOptions or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbOptions object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbOptions object
		}

		if ($criteria->containsKey(FfbOptionsPeer::OPTIONS_ID) && $criteria->keyContainsValue(FfbOptionsPeer::OPTIONS_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbOptionsPeer::OPTIONS_ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a FfbOptions or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbOptions object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbOptionsPeer::OPTIONS_ID);
			$value = $criteria->remove(FfbOptionsPeer::OPTIONS_ID);
			if ($value) {
				$selectCriteria->add(FfbOptionsPeer::OPTIONS_ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(FfbOptionsPeer::TABLE_NAME);
			}

		} else { // $values is FfbOptions object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_options table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(FfbOptionsPeer::TABLE_NAME, $con, FfbOptionsPeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbOptionsPeer::clearInstancePool();
			FfbOptionsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbOptions or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbOptions object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, ?PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			FfbOptionsPeer::clearInstancePool();
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbOptions) { // it's a model object
			// invalidate the cache for this single object
			FfbOptionsPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbOptionsPeer::OPTIONS_ID, (array) $values, Criteria::IN);
			// invalidate the cache for this object(s)
			foreach ((array) $values as $singleval) {
				FfbOptionsPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			FfbOptionsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given FfbOptions object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbOptions $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbOptions $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbOptionsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbOptionsPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(FfbOptionsPeer::DATABASE_NAME, FfbOptionsPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbOptions
	 */
	public static function retrieveByPK($pk, ?PropelPDO $con = null)
	{

		if (null !== ($obj = FfbOptionsPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbOptionsPeer::DATABASE_NAME);
		$criteria->add(FfbOptionsPeer::OPTIONS_ID, $pk);

		$v = FfbOptionsPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbOptionsPeer::DATABASE_NAME);
			$criteria->add(FfbOptionsPeer::OPTIONS_ID, $pks, Criteria::IN);
			$objs = FfbOptionsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbOptionsPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbOptionsPeer::buildTableMap();

