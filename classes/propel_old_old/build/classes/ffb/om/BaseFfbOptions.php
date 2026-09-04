<?php

/**
 * Base class that represents a row from the 'ffb_options' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbOptions extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbOptionsPeer
	 */
	protected static $peer;

	/**
	 * The value for the options_id field.
	 * @var        int
	 */
	protected $options_id;

	/**
	 * The value for the options_game_id field.
	 * @var        int
	 */
	protected $options_game_id;

	/**
	 * The value for the options_score_minutes field.
	 * @var        int
	 */
	protected $options_score_minutes;

	/**
	 * The value for the options_score_minutes_treshold field.
	 * @var        int
	 */
	protected $options_score_minutes_treshold;

	/**
	 * The value for the options_score_minutes_gt field.
	 * @var        int
	 */
	protected $options_score_minutes_gt;

	/**
	 * The value for the options_score_minutes_lt field.
	 * @var        int
	 */
	protected $options_score_minutes_lt;

	/**
	 * The value for the options_score_minutes_lt30 field.
	 * @var        int
	 */
	protected $options_score_minutes_lt30;

	/**
	 * The value for the options_score_goals_g field.
	 * @var        int
	 */
	protected $options_score_goals_g;

	/**
	 * The value for the options_score_goals_d field.
	 * @var        int
	 */
	protected $options_score_goals_d;

	/**
	 * The value for the options_score_goals_m field.
	 * @var        int
	 */
	protected $options_score_goals_m;

	/**
	 * The value for the options_score_goals_s field.
	 * @var        int
	 */
	protected $options_score_goals_s;

	/**
	 * The value for the options_score_assists field.
	 * @var        int
	 */
	protected $options_score_assists;

	/**
	 * The value for the options_score_no_oppgoals_g field.
	 * @var        int
	 */
	protected $options_score_no_oppgoals_g;

	/**
	 * The value for the options_score_no_oppgoals_d field.
	 * @var        int
	 */
	protected $options_score_no_oppgoals_d;

	/**
	 * The value for the options_score_no_oppgoals_m field.
	 * @var        int
	 */
	protected $options_score_no_oppgoals_m;

	/**
	 * The value for the options_score_oppgoals_g field.
	 * @var        int
	 */
	protected $options_score_oppgoals_g;

	/**
	 * The value for the options_score_oppgoals_d field.
	 * @var        int
	 */
	protected $options_score_oppgoals_d;

	/**
	 * The value for the options_score_owngoals field.
	 * @var        int
	 */
	protected $options_score_owngoals;

	/**
	 * The value for the options_score_card_y field.
	 * @var        int
	 */
	protected $options_score_card_y;

	/**
	 * The value for the options_score_card_r field.
	 * @var        int
	 */
	protected $options_score_card_r;

	/**
	 * The value for the options_score_card_yr field.
	 * @var        int
	 */
	protected $options_score_card_yr;

	/**
	 * The value for the options_score_penalty_saved field.
	 * @var        int
	 */
	protected $options_score_penalty_saved;

	/**
	 * The value for the options_score_penalty_lost field.
	 * @var        int
	 */
	protected $options_score_penalty_lost;

	/**
	 * The value for the options_score_penaltyshootout_save field.
	 * @var        int
	 */
	protected $options_score_penaltyshootout_save;

	/**
	 * The value for the options_score_penaltyshootout_lost field.
	 * @var        int
	 */
	protected $options_score_penaltyshootout_lost;

	/**
	 * The value for the options_score_penaltyshootout_hit field.
	 * @var        int
	 */
	protected $options_score_penaltyshootout_hit;

	/**
	 * The value for the options_score_high_loss field.
	 * @var        int
	 */
	protected $options_score_high_loss;

	/**
	 * The value for the options_score_high_win field.
	 * @var        int
	 */
	protected $options_score_high_win;

	/**
	 * The value for the options_score_high_win_loss_treshold field.
	 * @var        int
	 */
	protected $options_score_high_win_loss_treshold;

	/**
	 * The value for the options_status_error field.
	 * @var        int
	 */
	protected $options_status_error;

	/**
	 * The value for the options_status_error_validation field.
	 * @var        int
	 */
	protected $options_status_error_validation;

	/**
	 * The value for the options_status_success field.
	 * @var        int
	 */
	protected $options_status_success;

	/**
	 * The value for the options_status_success_insert field.
	 * @var        int
	 */
	protected $options_status_success_insert;

	/**
	 * The value for the options_status_success_update field.
	 * @var        int
	 */
	protected $options_status_success_update;

	/**
	 * The value for the options_status_success_delete field.
	 * @var        int
	 */
	protected $options_status_success_delete;

	/**
	 * The value for the options_lineup_max_players field.
	 * @var        int
	 */
	protected $options_lineup_max_players;

	/**
	 * The value for the options_lineup_max_credits field.
	 * @var        int
	 */
	protected $options_lineup_max_credits;

	/**
	 * The value for the options_lineup_max_players_team field.
	 * @var        int
	 */
	protected $options_lineup_max_players_team;

	/**
	 * The value for the options_lineup_min_g field.
	 * @var        int
	 */
	protected $options_lineup_min_g;

	/**
	 * The value for the options_lineup_min_d field.
	 * @var        int
	 */
	protected $options_lineup_min_d;

	/**
	 * The value for the options_lineup_min_m field.
	 * @var        int
	 */
	protected $options_lineup_min_m;

	/**
	 * The value for the options_lineup_min_s field.
	 * @var        int
	 */
	protected $options_lineup_min_s;

	/**
	 * The value for the options_lineup_max_g field.
	 * @var        int
	 */
	protected $options_lineup_max_g;

	/**
	 * The value for the options_lineup_max_d field.
	 * @var        int
	 */
	protected $options_lineup_max_d;

	/**
	 * The value for the options_lineup_max_m field.
	 * @var        int
	 */
	protected $options_lineup_max_m;

	/**
	 * The value for the options_lineup_max_s field.
	 * @var        int
	 */
	protected $options_lineup_max_s;

	/**
	 * The value for the options_game_rankmode field.
	 * Note: this column has a database default value of: 'wc'
	 * @var        string
	 */
	protected $options_game_rankmode;

	/**
	 * The value for the options_game_pricemode field.
	 * Note: this column has a database default value of: 'dynamic'
	 * @var        string
	 */
	protected $options_game_pricemode;

	/**
	 * The value for the options_game_pointsmode field.
	 * Note: this column has a database default value of: 'new'
	 * @var        string
	 */
	protected $options_game_pointsmode;

	/**
	 * The value for the options_game_wcpoints field.
	 * Note: this column has a database default value of: 'new'
	 * @var        string
	 */
	protected $options_game_wcpoints;

	/**
	 * The value for the options_game_remind_hours_before field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $options_game_remind_hours_before;

	/**
	 * @var        FfbGame
	 */
	protected $aFfbGame;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->options_game_rankmode = 'wc';
		$this->options_game_pricemode = 'dynamic';
		$this->options_game_pointsmode = 'new';
		$this->options_game_wcpoints = 'new';
		$this->options_game_remind_hours_before = 0;
	}

	/**
	 * Initializes internal state of BaseFfbOptions object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [options_id] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsId()
	{
		return $this->options_id;
	}

	/**
	 * Get the [options_game_id] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsGameId()
	{
		return $this->options_game_id;
	}

	/**
	 * Get the [options_score_minutes] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreMinutes()
	{
		return $this->options_score_minutes;
	}

	/**
	 * Get the [options_score_minutes_treshold] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreMinutesTreshold()
	{
		return $this->options_score_minutes_treshold;
	}

	/**
	 * Get the [options_score_minutes_gt] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreMinutesGt()
	{
		return $this->options_score_minutes_gt;
	}

	/**
	 * Get the [options_score_minutes_lt] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreMinutesLt()
	{
		return $this->options_score_minutes_lt;
	}

	/**
	 * Get the [options_score_minutes_lt30] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreMinutesLt30()
	{
		return $this->options_score_minutes_lt30;
	}

	/**
	 * Get the [options_score_goals_g] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreGoalsG()
	{
		return $this->options_score_goals_g;
	}

	/**
	 * Get the [options_score_goals_d] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreGoalsD()
	{
		return $this->options_score_goals_d;
	}

	/**
	 * Get the [options_score_goals_m] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreGoalsM()
	{
		return $this->options_score_goals_m;
	}

	/**
	 * Get the [options_score_goals_s] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreGoalsS()
	{
		return $this->options_score_goals_s;
	}

	/**
	 * Get the [options_score_assists] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreAssists()
	{
		return $this->options_score_assists;
	}

	/**
	 * Get the [options_score_no_oppgoals_g] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreNoOppgoalsG()
	{
		return $this->options_score_no_oppgoals_g;
	}

	/**
	 * Get the [options_score_no_oppgoals_d] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreNoOppgoalsD()
	{
		return $this->options_score_no_oppgoals_d;
	}

	/**
	 * Get the [options_score_no_oppgoals_m] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreNoOppgoalsM()
	{
		return $this->options_score_no_oppgoals_m;
	}

	/**
	 * Get the [options_score_oppgoals_g] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreOppgoalsG()
	{
		return $this->options_score_oppgoals_g;
	}

	/**
	 * Get the [options_score_oppgoals_d] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreOppgoalsD()
	{
		return $this->options_score_oppgoals_d;
	}

	/**
	 * Get the [options_score_owngoals] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreOwngoals()
	{
		return $this->options_score_owngoals;
	}

	/**
	 * Get the [options_score_card_y] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreCardY()
	{
		return $this->options_score_card_y;
	}

	/**
	 * Get the [options_score_card_r] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreCardR()
	{
		return $this->options_score_card_r;
	}

	/**
	 * Get the [options_score_card_yr] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreCardYr()
	{
		return $this->options_score_card_yr;
	}

	/**
	 * Get the [options_score_penalty_saved] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScorePenaltySaved()
	{
		return $this->options_score_penalty_saved;
	}

	/**
	 * Get the [options_score_penalty_lost] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScorePenaltyLost()
	{
		return $this->options_score_penalty_lost;
	}

	/**
	 * Get the [options_score_penaltyshootout_save] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScorePenaltyshootoutSave()
	{
		return $this->options_score_penaltyshootout_save;
	}

	/**
	 * Get the [options_score_penaltyshootout_lost] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScorePenaltyshootoutLost()
	{
		return $this->options_score_penaltyshootout_lost;
	}

	/**
	 * Get the [options_score_penaltyshootout_hit] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScorePenaltyshootoutHit()
	{
		return $this->options_score_penaltyshootout_hit;
	}

	/**
	 * Get the [options_score_high_loss] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreHighLoss()
	{
		return $this->options_score_high_loss;
	}

	/**
	 * Get the [options_score_high_win] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreHighWin()
	{
		return $this->options_score_high_win;
	}

	/**
	 * Get the [options_score_high_win_loss_treshold] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsScoreHighWinLossTreshold()
	{
		return $this->options_score_high_win_loss_treshold;
	}

	/**
	 * Get the [options_status_error] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusError()
	{
		return $this->options_status_error;
	}

	/**
	 * Get the [options_status_error_validation] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusErrorValidation()
	{
		return $this->options_status_error_validation;
	}

	/**
	 * Get the [options_status_success] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusSuccess()
	{
		return $this->options_status_success;
	}

	/**
	 * Get the [options_status_success_insert] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusSuccessInsert()
	{
		return $this->options_status_success_insert;
	}

	/**
	 * Get the [options_status_success_update] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusSuccessUpdate()
	{
		return $this->options_status_success_update;
	}

	/**
	 * Get the [options_status_success_delete] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsStatusSuccessDelete()
	{
		return $this->options_status_success_delete;
	}

	/**
	 * Get the [options_lineup_max_players] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxPlayers()
	{
		return $this->options_lineup_max_players;
	}

	/**
	 * Get the [options_lineup_max_credits] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxCredits()
	{
		return $this->options_lineup_max_credits;
	}

	/**
	 * Get the [options_lineup_max_players_team] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxPlayersTeam()
	{
		return $this->options_lineup_max_players_team;
	}

	/**
	 * Get the [options_lineup_min_g] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMinG()
	{
		return $this->options_lineup_min_g;
	}

	/**
	 * Get the [options_lineup_min_d] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMinD()
	{
		return $this->options_lineup_min_d;
	}

	/**
	 * Get the [options_lineup_min_m] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMinM()
	{
		return $this->options_lineup_min_m;
	}

	/**
	 * Get the [options_lineup_min_s] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMinS()
	{
		return $this->options_lineup_min_s;
	}

	/**
	 * Get the [options_lineup_max_g] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxG()
	{
		return $this->options_lineup_max_g;
	}

	/**
	 * Get the [options_lineup_max_d] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxD()
	{
		return $this->options_lineup_max_d;
	}

	/**
	 * Get the [options_lineup_max_m] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxM()
	{
		return $this->options_lineup_max_m;
	}

	/**
	 * Get the [options_lineup_max_s] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsLineupMaxS()
	{
		return $this->options_lineup_max_s;
	}

	/**
	 * Get the [options_game_rankmode] column value.
	 * 
	 * @return     string
	 */
	public function getOptionsGameRankmode()
	{
		return $this->options_game_rankmode;
	}

	/**
	 * Get the [options_game_pricemode] column value.
	 * 
	 * @return     string
	 */
	public function getOptionsGamePricemode()
	{
		return $this->options_game_pricemode;
	}

	/**
	 * Get the [options_game_pointsmode] column value.
	 * 
	 * @return     string
	 */
	public function getOptionsGamePointsmode()
	{
		return $this->options_game_pointsmode;
	}

	/**
	 * Get the [options_game_wcpoints] column value.
	 * 
	 * @return     string
	 */
	public function getOptionsGameWcpoints()
	{
		return $this->options_game_wcpoints;
	}

	/**
	 * Get the [options_game_remind_hours_before] column value.
	 * 
	 * @return     int
	 */
	public function getOptionsGameRemindHoursBefore()
	{
		return $this->options_game_remind_hours_before;
	}

	/**
	 * Set the value of [options_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_id !== $v) {
			$this->options_id = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_ID;
		}

		return $this;
	} // setOptionsId()

	/**
	 * Set the value of [options_game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_game_id !== $v) {
			$this->options_game_id = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_ID;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setOptionsGameId()

	/**
	 * Set the value of [options_score_minutes] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreMinutes($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_minutes !== $v) {
			$this->options_score_minutes = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_MINUTES;
		}

		return $this;
	} // setOptionsScoreMinutes()

	/**
	 * Set the value of [options_score_minutes_treshold] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreMinutesTreshold($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_minutes_treshold !== $v) {
			$this->options_score_minutes_treshold = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD;
		}

		return $this;
	} // setOptionsScoreMinutesTreshold()

	/**
	 * Set the value of [options_score_minutes_gt] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreMinutesGt($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_minutes_gt !== $v) {
			$this->options_score_minutes_gt = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT;
		}

		return $this;
	} // setOptionsScoreMinutesGt()

	/**
	 * Set the value of [options_score_minutes_lt] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreMinutesLt($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_minutes_lt !== $v) {
			$this->options_score_minutes_lt = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT;
		}

		return $this;
	} // setOptionsScoreMinutesLt()

	/**
	 * Set the value of [options_score_minutes_lt30] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreMinutesLt30($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_minutes_lt30 !== $v) {
			$this->options_score_minutes_lt30 = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30;
		}

		return $this;
	} // setOptionsScoreMinutesLt30()

	/**
	 * Set the value of [options_score_goals_g] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreGoalsG($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_goals_g !== $v) {
			$this->options_score_goals_g = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_GOALS_G;
		}

		return $this;
	} // setOptionsScoreGoalsG()

	/**
	 * Set the value of [options_score_goals_d] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreGoalsD($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_goals_d !== $v) {
			$this->options_score_goals_d = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_GOALS_D;
		}

		return $this;
	} // setOptionsScoreGoalsD()

	/**
	 * Set the value of [options_score_goals_m] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreGoalsM($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_goals_m !== $v) {
			$this->options_score_goals_m = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_GOALS_M;
		}

		return $this;
	} // setOptionsScoreGoalsM()

	/**
	 * Set the value of [options_score_goals_s] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreGoalsS($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_goals_s !== $v) {
			$this->options_score_goals_s = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_GOALS_S;
		}

		return $this;
	} // setOptionsScoreGoalsS()

	/**
	 * Set the value of [options_score_assists] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreAssists($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_assists !== $v) {
			$this->options_score_assists = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_ASSISTS;
		}

		return $this;
	} // setOptionsScoreAssists()

	/**
	 * Set the value of [options_score_no_oppgoals_g] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreNoOppgoalsG($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_no_oppgoals_g !== $v) {
			$this->options_score_no_oppgoals_g = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G;
		}

		return $this;
	} // setOptionsScoreNoOppgoalsG()

	/**
	 * Set the value of [options_score_no_oppgoals_d] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreNoOppgoalsD($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_no_oppgoals_d !== $v) {
			$this->options_score_no_oppgoals_d = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D;
		}

		return $this;
	} // setOptionsScoreNoOppgoalsD()

	/**
	 * Set the value of [options_score_no_oppgoals_m] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreNoOppgoalsM($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_no_oppgoals_m !== $v) {
			$this->options_score_no_oppgoals_m = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M;
		}

		return $this;
	} // setOptionsScoreNoOppgoalsM()

	/**
	 * Set the value of [options_score_oppgoals_g] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreOppgoalsG($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_oppgoals_g !== $v) {
			$this->options_score_oppgoals_g = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G;
		}

		return $this;
	} // setOptionsScoreOppgoalsG()

	/**
	 * Set the value of [options_score_oppgoals_d] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreOppgoalsD($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_oppgoals_d !== $v) {
			$this->options_score_oppgoals_d = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D;
		}

		return $this;
	} // setOptionsScoreOppgoalsD()

	/**
	 * Set the value of [options_score_owngoals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreOwngoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_owngoals !== $v) {
			$this->options_score_owngoals = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS;
		}

		return $this;
	} // setOptionsScoreOwngoals()

	/**
	 * Set the value of [options_score_card_y] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreCardY($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_card_y !== $v) {
			$this->options_score_card_y = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_CARD_Y;
		}

		return $this;
	} // setOptionsScoreCardY()

	/**
	 * Set the value of [options_score_card_r] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreCardR($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_card_r !== $v) {
			$this->options_score_card_r = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_CARD_R;
		}

		return $this;
	} // setOptionsScoreCardR()

	/**
	 * Set the value of [options_score_card_yr] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreCardYr($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_card_yr !== $v) {
			$this->options_score_card_yr = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_CARD_YR;
		}

		return $this;
	} // setOptionsScoreCardYr()

	/**
	 * Set the value of [options_score_penalty_saved] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScorePenaltySaved($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_penalty_saved !== $v) {
			$this->options_score_penalty_saved = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED;
		}

		return $this;
	} // setOptionsScorePenaltySaved()

	/**
	 * Set the value of [options_score_penalty_lost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScorePenaltyLost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_penalty_lost !== $v) {
			$this->options_score_penalty_lost = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST;
		}

		return $this;
	} // setOptionsScorePenaltyLost()

	/**
	 * Set the value of [options_score_penaltyshootout_save] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScorePenaltyshootoutSave($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_penaltyshootout_save !== $v) {
			$this->options_score_penaltyshootout_save = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE;
		}

		return $this;
	} // setOptionsScorePenaltyshootoutSave()

	/**
	 * Set the value of [options_score_penaltyshootout_lost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScorePenaltyshootoutLost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_penaltyshootout_lost !== $v) {
			$this->options_score_penaltyshootout_lost = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST;
		}

		return $this;
	} // setOptionsScorePenaltyshootoutLost()

	/**
	 * Set the value of [options_score_penaltyshootout_hit] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScorePenaltyshootoutHit($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_penaltyshootout_hit !== $v) {
			$this->options_score_penaltyshootout_hit = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT;
		}

		return $this;
	} // setOptionsScorePenaltyshootoutHit()

	/**
	 * Set the value of [options_score_high_loss] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreHighLoss($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_high_loss !== $v) {
			$this->options_score_high_loss = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS;
		}

		return $this;
	} // setOptionsScoreHighLoss()

	/**
	 * Set the value of [options_score_high_win] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreHighWin($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_high_win !== $v) {
			$this->options_score_high_win = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN;
		}

		return $this;
	} // setOptionsScoreHighWin()

	/**
	 * Set the value of [options_score_high_win_loss_treshold] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsScoreHighWinLossTreshold($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_score_high_win_loss_treshold !== $v) {
			$this->options_score_high_win_loss_treshold = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD;
		}

		return $this;
	} // setOptionsScoreHighWinLossTreshold()

	/**
	 * Set the value of [options_status_error] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusError($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_error !== $v) {
			$this->options_status_error = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_ERROR;
		}

		return $this;
	} // setOptionsStatusError()

	/**
	 * Set the value of [options_status_error_validation] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusErrorValidation($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_error_validation !== $v) {
			$this->options_status_error_validation = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION;
		}

		return $this;
	} // setOptionsStatusErrorValidation()

	/**
	 * Set the value of [options_status_success] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusSuccess($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_success !== $v) {
			$this->options_status_success = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_SUCCESS;
		}

		return $this;
	} // setOptionsStatusSuccess()

	/**
	 * Set the value of [options_status_success_insert] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusSuccessInsert($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_success_insert !== $v) {
			$this->options_status_success_insert = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT;
		}

		return $this;
	} // setOptionsStatusSuccessInsert()

	/**
	 * Set the value of [options_status_success_update] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusSuccessUpdate($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_success_update !== $v) {
			$this->options_status_success_update = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE;
		}

		return $this;
	} // setOptionsStatusSuccessUpdate()

	/**
	 * Set the value of [options_status_success_delete] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsStatusSuccessDelete($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_status_success_delete !== $v) {
			$this->options_status_success_delete = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE;
		}

		return $this;
	} // setOptionsStatusSuccessDelete()

	/**
	 * Set the value of [options_lineup_max_players] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxPlayers($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_players !== $v) {
			$this->options_lineup_max_players = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS;
		}

		return $this;
	} // setOptionsLineupMaxPlayers()

	/**
	 * Set the value of [options_lineup_max_credits] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxCredits($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_credits !== $v) {
			$this->options_lineup_max_credits = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS;
		}

		return $this;
	} // setOptionsLineupMaxCredits()

	/**
	 * Set the value of [options_lineup_max_players_team] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxPlayersTeam($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_players_team !== $v) {
			$this->options_lineup_max_players_team = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM;
		}

		return $this;
	} // setOptionsLineupMaxPlayersTeam()

	/**
	 * Set the value of [options_lineup_min_g] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMinG($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_min_g !== $v) {
			$this->options_lineup_min_g = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MIN_G;
		}

		return $this;
	} // setOptionsLineupMinG()

	/**
	 * Set the value of [options_lineup_min_d] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMinD($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_min_d !== $v) {
			$this->options_lineup_min_d = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MIN_D;
		}

		return $this;
	} // setOptionsLineupMinD()

	/**
	 * Set the value of [options_lineup_min_m] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMinM($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_min_m !== $v) {
			$this->options_lineup_min_m = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MIN_M;
		}

		return $this;
	} // setOptionsLineupMinM()

	/**
	 * Set the value of [options_lineup_min_s] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMinS($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_min_s !== $v) {
			$this->options_lineup_min_s = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MIN_S;
		}

		return $this;
	} // setOptionsLineupMinS()

	/**
	 * Set the value of [options_lineup_max_g] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxG($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_g !== $v) {
			$this->options_lineup_max_g = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_G;
		}

		return $this;
	} // setOptionsLineupMaxG()

	/**
	 * Set the value of [options_lineup_max_d] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxD($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_d !== $v) {
			$this->options_lineup_max_d = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_D;
		}

		return $this;
	} // setOptionsLineupMaxD()

	/**
	 * Set the value of [options_lineup_max_m] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxM($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_m !== $v) {
			$this->options_lineup_max_m = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_M;
		}

		return $this;
	} // setOptionsLineupMaxM()

	/**
	 * Set the value of [options_lineup_max_s] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsLineupMaxS($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_lineup_max_s !== $v) {
			$this->options_lineup_max_s = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_LINEUP_MAX_S;
		}

		return $this;
	} // setOptionsLineupMaxS()

	/**
	 * Set the value of [options_game_rankmode] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGameRankmode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->options_game_rankmode !== $v || $this->isNew()) {
			$this->options_game_rankmode = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_RANKMODE;
		}

		return $this;
	} // setOptionsGameRankmode()

	/**
	 * Set the value of [options_game_pricemode] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGamePricemode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->options_game_pricemode !== $v || $this->isNew()) {
			$this->options_game_pricemode = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_PRICEMODE;
		}

		return $this;
	} // setOptionsGamePricemode()

	/**
	 * Set the value of [options_game_pointsmode] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGamePointsmode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->options_game_pointsmode !== $v || $this->isNew()) {
			$this->options_game_pointsmode = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_POINTSMODE;
		}

		return $this;
	} // setOptionsGamePointsmode()

	/**
	 * Set the value of [options_game_wcpoints] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGameWcpoints($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->options_game_wcpoints !== $v || $this->isNew()) {
			$this->options_game_wcpoints = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_WCPOINTS;
		}

		return $this;
	} // setOptionsGameWcpoints()

	/**
	 * Set the value of [options_game_remind_hours_before] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbOptions The current object (for fluent API support)
	 */
	public function setOptionsGameRemindHoursBefore($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->options_game_remind_hours_before !== $v || $this->isNew()) {
			$this->options_game_remind_hours_before = $v;
			$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE;
		}

		return $this;
	} // setOptionsGameRemindHoursBefore()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			if ($this->options_game_rankmode !== 'wc') {
				return false;
			}

			if ($this->options_game_pricemode !== 'dynamic') {
				return false;
			}

			if ($this->options_game_pointsmode !== 'new') {
				return false;
			}

			if ($this->options_game_wcpoints !== 'new') {
				return false;
			}

			if ($this->options_game_remind_hours_before !== 0) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->options_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->options_game_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->options_score_minutes = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->options_score_minutes_treshold = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->options_score_minutes_gt = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->options_score_minutes_lt = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->options_score_minutes_lt30 = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->options_score_goals_g = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->options_score_goals_d = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->options_score_goals_m = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->options_score_goals_s = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->options_score_assists = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->options_score_no_oppgoals_g = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
			$this->options_score_no_oppgoals_d = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
			$this->options_score_no_oppgoals_m = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
			$this->options_score_oppgoals_g = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
			$this->options_score_oppgoals_d = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
			$this->options_score_owngoals = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
			$this->options_score_card_y = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
			$this->options_score_card_r = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
			$this->options_score_card_yr = ($row[$startcol + 20] !== null) ? (int) $row[$startcol + 20] : null;
			$this->options_score_penalty_saved = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
			$this->options_score_penalty_lost = ($row[$startcol + 22] !== null) ? (int) $row[$startcol + 22] : null;
			$this->options_score_penaltyshootout_save = ($row[$startcol + 23] !== null) ? (int) $row[$startcol + 23] : null;
			$this->options_score_penaltyshootout_lost = ($row[$startcol + 24] !== null) ? (int) $row[$startcol + 24] : null;
			$this->options_score_penaltyshootout_hit = ($row[$startcol + 25] !== null) ? (int) $row[$startcol + 25] : null;
			$this->options_score_high_loss = ($row[$startcol + 26] !== null) ? (int) $row[$startcol + 26] : null;
			$this->options_score_high_win = ($row[$startcol + 27] !== null) ? (int) $row[$startcol + 27] : null;
			$this->options_score_high_win_loss_treshold = ($row[$startcol + 28] !== null) ? (int) $row[$startcol + 28] : null;
			$this->options_status_error = ($row[$startcol + 29] !== null) ? (int) $row[$startcol + 29] : null;
			$this->options_status_error_validation = ($row[$startcol + 30] !== null) ? (int) $row[$startcol + 30] : null;
			$this->options_status_success = ($row[$startcol + 31] !== null) ? (int) $row[$startcol + 31] : null;
			$this->options_status_success_insert = ($row[$startcol + 32] !== null) ? (int) $row[$startcol + 32] : null;
			$this->options_status_success_update = ($row[$startcol + 33] !== null) ? (int) $row[$startcol + 33] : null;
			$this->options_status_success_delete = ($row[$startcol + 34] !== null) ? (int) $row[$startcol + 34] : null;
			$this->options_lineup_max_players = ($row[$startcol + 35] !== null) ? (int) $row[$startcol + 35] : null;
			$this->options_lineup_max_credits = ($row[$startcol + 36] !== null) ? (int) $row[$startcol + 36] : null;
			$this->options_lineup_max_players_team = ($row[$startcol + 37] !== null) ? (int) $row[$startcol + 37] : null;
			$this->options_lineup_min_g = ($row[$startcol + 38] !== null) ? (int) $row[$startcol + 38] : null;
			$this->options_lineup_min_d = ($row[$startcol + 39] !== null) ? (int) $row[$startcol + 39] : null;
			$this->options_lineup_min_m = ($row[$startcol + 40] !== null) ? (int) $row[$startcol + 40] : null;
			$this->options_lineup_min_s = ($row[$startcol + 41] !== null) ? (int) $row[$startcol + 41] : null;
			$this->options_lineup_max_g = ($row[$startcol + 42] !== null) ? (int) $row[$startcol + 42] : null;
			$this->options_lineup_max_d = ($row[$startcol + 43] !== null) ? (int) $row[$startcol + 43] : null;
			$this->options_lineup_max_m = ($row[$startcol + 44] !== null) ? (int) $row[$startcol + 44] : null;
			$this->options_lineup_max_s = ($row[$startcol + 45] !== null) ? (int) $row[$startcol + 45] : null;
			$this->options_game_rankmode = ($row[$startcol + 46] !== null) ? (string) $row[$startcol + 46] : null;
			$this->options_game_pricemode = ($row[$startcol + 47] !== null) ? (string) $row[$startcol + 47] : null;
			$this->options_game_pointsmode = ($row[$startcol + 48] !== null) ? (string) $row[$startcol + 48] : null;
			$this->options_game_wcpoints = ($row[$startcol + 49] !== null) ? (string) $row[$startcol + 49] : null;
			$this->options_game_remind_hours_before = ($row[$startcol + 50] !== null) ? (int) $row[$startcol + 50] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 51; // 51 = FfbOptionsPeer::NUM_COLUMNS - FfbOptionsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbOptions object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->aFfbGame !== null && $this->options_game_id !== $this->aFfbGame->getGameId()) {
			$this->aFfbGame = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbOptionsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbGame = null;
		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbOptionsPeer::doDelete($this, $con);
				$this->postDelete($con);
				$this->setDeleted(true);
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbOptionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				$con->commit();
				FfbOptionsPeer::addInstanceToPool($this);
				return $affectedRows;
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aFfbGame !== null) {
				if ($this->aFfbGame->isModified() || $this->aFfbGame->isNew()) {
					$affectedRows += $this->aFfbGame->save($con);
				}
				$this->setFfbGame($this->aFfbGame);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbOptionsPeer::OPTIONS_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbOptionsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setOptionsId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbOptionsPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aFfbGame !== null) {
				if (!$this->aFfbGame->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbGame->getValidationFailures());
				}
			}


			if (($retval = FfbOptionsPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbOptionsPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_ID)) $criteria->add(FfbOptionsPeer::OPTIONS_ID, $this->options_id);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_ID)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->options_game_id);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_MINUTES)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_MINUTES, $this->options_score_minutes);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_TRESHOLD, $this->options_score_minutes_treshold);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_GT, $this->options_score_minutes_gt);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT, $this->options_score_minutes_lt);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_MINUTES_LT30, $this->options_score_minutes_lt30);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_GOALS_G, $this->options_score_goals_g);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_GOALS_D, $this->options_score_goals_d);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_GOALS_M, $this->options_score_goals_m);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_GOALS_S, $this->options_score_goals_s);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_ASSISTS, $this->options_score_assists);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_G, $this->options_score_no_oppgoals_g);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_D, $this->options_score_no_oppgoals_d);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_NO_OPPGOALS_M, $this->options_score_no_oppgoals_m);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_G, $this->options_score_oppgoals_g);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_OPPGOALS_D, $this->options_score_oppgoals_d);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_OWNGOALS, $this->options_score_owngoals);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_CARD_Y, $this->options_score_card_y);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_CARD_R)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_CARD_R, $this->options_score_card_r);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_CARD_YR, $this->options_score_card_yr);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_SAVED, $this->options_score_penalty_saved);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_PENALTY_LOST, $this->options_score_penalty_lost);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE, $this->options_score_penaltyshootout_save);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_LOST, $this->options_score_penaltyshootout_lost);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_PENALTYSHOOTOUT_HIT, $this->options_score_penaltyshootout_hit);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_HIGH_LOSS, $this->options_score_high_loss);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN, $this->options_score_high_win);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD)) $criteria->add(FfbOptionsPeer::OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD, $this->options_score_high_win_loss_treshold);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_ERROR)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_ERROR, $this->options_status_error);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_ERROR_VALIDATION, $this->options_status_error_validation);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS, $this->options_status_success);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_INSERT, $this->options_status_success_insert);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_UPDATE, $this->options_status_success_update);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE)) $criteria->add(FfbOptionsPeer::OPTIONS_STATUS_SUCCESS_DELETE, $this->options_status_success_delete);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS, $this->options_lineup_max_players);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_CREDITS, $this->options_lineup_max_credits);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_PLAYERS_TEAM, $this->options_lineup_max_players_team);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MIN_G, $this->options_lineup_min_g);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MIN_D, $this->options_lineup_min_d);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MIN_M, $this->options_lineup_min_m);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MIN_S, $this->options_lineup_min_s);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_G, $this->options_lineup_max_g);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_D, $this->options_lineup_max_d);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_M, $this->options_lineup_max_m);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S)) $criteria->add(FfbOptionsPeer::OPTIONS_LINEUP_MAX_S, $this->options_lineup_max_s);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_RANKMODE)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_RANKMODE, $this->options_game_rankmode);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_PRICEMODE)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_PRICEMODE, $this->options_game_pricemode);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_POINTSMODE)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_POINTSMODE, $this->options_game_pointsmode);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_WCPOINTS)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_WCPOINTS, $this->options_game_wcpoints);
		if ($this->isColumnModified(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE)) $criteria->add(FfbOptionsPeer::OPTIONS_GAME_REMIND_HOURS_BEFORE, $this->options_game_remind_hours_before);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(FfbOptionsPeer::DATABASE_NAME);

		$criteria->add(FfbOptionsPeer::OPTIONS_ID, $this->options_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getOptionsId();
	}

	/**
	 * Generic method to set the primary key (options_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setOptionsId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbOptions (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setOptionsGameId($this->options_game_id);

		$copyObj->setOptionsScoreMinutes($this->options_score_minutes);

		$copyObj->setOptionsScoreMinutesTreshold($this->options_score_minutes_treshold);

		$copyObj->setOptionsScoreMinutesGt($this->options_score_minutes_gt);

		$copyObj->setOptionsScoreMinutesLt($this->options_score_minutes_lt);

		$copyObj->setOptionsScoreMinutesLt30($this->options_score_minutes_lt30);

		$copyObj->setOptionsScoreGoalsG($this->options_score_goals_g);

		$copyObj->setOptionsScoreGoalsD($this->options_score_goals_d);

		$copyObj->setOptionsScoreGoalsM($this->options_score_goals_m);

		$copyObj->setOptionsScoreGoalsS($this->options_score_goals_s);

		$copyObj->setOptionsScoreAssists($this->options_score_assists);

		$copyObj->setOptionsScoreNoOppgoalsG($this->options_score_no_oppgoals_g);

		$copyObj->setOptionsScoreNoOppgoalsD($this->options_score_no_oppgoals_d);

		$copyObj->setOptionsScoreNoOppgoalsM($this->options_score_no_oppgoals_m);

		$copyObj->setOptionsScoreOppgoalsG($this->options_score_oppgoals_g);

		$copyObj->setOptionsScoreOppgoalsD($this->options_score_oppgoals_d);

		$copyObj->setOptionsScoreOwngoals($this->options_score_owngoals);

		$copyObj->setOptionsScoreCardY($this->options_score_card_y);

		$copyObj->setOptionsScoreCardR($this->options_score_card_r);

		$copyObj->setOptionsScoreCardYr($this->options_score_card_yr);

		$copyObj->setOptionsScorePenaltySaved($this->options_score_penalty_saved);

		$copyObj->setOptionsScorePenaltyLost($this->options_score_penalty_lost);

		$copyObj->setOptionsScorePenaltyshootoutSave($this->options_score_penaltyshootout_save);

		$copyObj->setOptionsScorePenaltyshootoutLost($this->options_score_penaltyshootout_lost);

		$copyObj->setOptionsScorePenaltyshootoutHit($this->options_score_penaltyshootout_hit);

		$copyObj->setOptionsScoreHighLoss($this->options_score_high_loss);

		$copyObj->setOptionsScoreHighWin($this->options_score_high_win);

		$copyObj->setOptionsScoreHighWinLossTreshold($this->options_score_high_win_loss_treshold);

		$copyObj->setOptionsStatusError($this->options_status_error);

		$copyObj->setOptionsStatusErrorValidation($this->options_status_error_validation);

		$copyObj->setOptionsStatusSuccess($this->options_status_success);

		$copyObj->setOptionsStatusSuccessInsert($this->options_status_success_insert);

		$copyObj->setOptionsStatusSuccessUpdate($this->options_status_success_update);

		$copyObj->setOptionsStatusSuccessDelete($this->options_status_success_delete);

		$copyObj->setOptionsLineupMaxPlayers($this->options_lineup_max_players);

		$copyObj->setOptionsLineupMaxCredits($this->options_lineup_max_credits);

		$copyObj->setOptionsLineupMaxPlayersTeam($this->options_lineup_max_players_team);

		$copyObj->setOptionsLineupMinG($this->options_lineup_min_g);

		$copyObj->setOptionsLineupMinD($this->options_lineup_min_d);

		$copyObj->setOptionsLineupMinM($this->options_lineup_min_m);

		$copyObj->setOptionsLineupMinS($this->options_lineup_min_s);

		$copyObj->setOptionsLineupMaxG($this->options_lineup_max_g);

		$copyObj->setOptionsLineupMaxD($this->options_lineup_max_d);

		$copyObj->setOptionsLineupMaxM($this->options_lineup_max_m);

		$copyObj->setOptionsLineupMaxS($this->options_lineup_max_s);

		$copyObj->setOptionsGameRankmode($this->options_game_rankmode);

		$copyObj->setOptionsGamePricemode($this->options_game_pricemode);

		$copyObj->setOptionsGamePointsmode($this->options_game_pointsmode);

		$copyObj->setOptionsGameWcpoints($this->options_game_wcpoints);

		$copyObj->setOptionsGameRemindHoursBefore($this->options_game_remind_hours_before);


		$copyObj->setNew(true);

		$copyObj->setOptionsId(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     FfbOptions Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     FfbOptionsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbOptionsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     FfbOptions The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(FfbGame $v = null)
	{
		if ($v === null) {
			$this->setOptionsGameId(NULL);
		} else {
			$this->setOptionsGameId($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbOptions($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbGame object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbGame The associated FfbGame object.
	 * @throws     PropelException
	 */
	public function getFfbGame(PropelPDO $con = null)
	{
		if ($this->aFfbGame === null && ($this->options_game_id !== null)) {
			$this->aFfbGame = FfbGamePeer::retrieveByPk($this->options_game_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbGame->addFfbOptionss($this);
			 */
		}
		return $this->aFfbGame;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

			$this->aFfbGame = null;
	}

} // BaseFfbOptions
