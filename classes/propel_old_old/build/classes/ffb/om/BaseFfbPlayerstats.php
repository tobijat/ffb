<?php

/**
 * Base class that represents a row from the 'ffb_playerstats' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPlayerstats extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPlayerstatsPeer
	 */
	protected static $peer;

	/**
	 * The value for the playerstats_id field.
	 * @var        int
	 */
	protected $playerstats_id;

	/**
	 * The value for the playerstats_playerteam_id field.
	 * @var        int
	 */
	protected $playerstats_playerteam_id;

	/**
	 * The value for the playerstats_match_id field.
	 * @var        int
	 */
	protected $playerstats_match_id;

	/**
	 * The value for the playerstats_matchround_id field.
	 * @var        int
	 */
	protected $playerstats_matchround_id;

	/**
	 * The value for the playerstats_goals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_goals;

	/**
	 * The value for the playerstats_assists field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_assists;

	/**
	 * The value for the playerstats_minutes field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_minutes;

	/**
	 * The value for the playerstats_minute_in field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_minute_in;

	/**
	 * The value for the playerstats_minute_out field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_minute_out;

	/**
	 * The value for the playerstats_cards field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $playerstats_cards;

	/**
	 * The value for the playerstats_owngoals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_owngoals;

	/**
	 * The value for the playerstats_penaltieslost field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_penaltieslost;

	/**
	 * The value for the playerstats_penaltiessaved field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_penaltiessaved;

	/**
	 * The value for the playerstats_penaltyshootout_save field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_penaltyshootout_save;

	/**
	 * The value for the playerstats_penaltyshootout_lost field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_penaltyshootout_lost;

	/**
	 * The value for the playerstats_penaltyshootout_hit field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_penaltyshootout_hit;

	/**
	 * The value for the playerstats_score_goals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_goals;

	/**
	 * The value for the playerstats_score_assists field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_assists;

	/**
	 * The value for the playerstats_score_minutes field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_minutes;

	/**
	 * The value for the playerstats_score_cards field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_cards;

	/**
	 * The value for the playerstats_score_owngoals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_owngoals;

	/**
	 * The value for the playerstats_score_penaltieslost field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_penaltieslost;

	/**
	 * The value for the playerstats_score_penaltiessaved field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_penaltiessaved;

	/**
	 * The value for the playerstats_score_oppgoals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_oppgoals;

	/**
	 * The value for the playerstats_score_nooppgoals field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_nooppgoals;

	/**
	 * The value for the playerstats_score_high_loss field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_high_loss;

	/**
	 * The value for the playerstats_score_high_win field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_high_win;

	/**
	 * The value for the playerstats_score_penaltyshootout_save field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_penaltyshootout_save;

	/**
	 * The value for the playerstats_score_penaltyshootout_lost field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_penaltyshootout_lost;

	/**
	 * The value for the playerstats_score_penaltyshootout_hit field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score_penaltyshootout_hit;

	/**
	 * The value for the playerstats_score field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $playerstats_score;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteam;

	/**
	 * @var        FfbMatch
	 */
	protected $aFfbMatch;

	/**
	 * @var        FfbMatchround
	 */
	protected $aFfbMatchround;

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
		$this->playerstats_goals = 0;
		$this->playerstats_assists = 0;
		$this->playerstats_minutes = 0;
		$this->playerstats_minute_in = 0;
		$this->playerstats_minute_out = 0;
		$this->playerstats_cards = '';
		$this->playerstats_owngoals = 0;
		$this->playerstats_penaltieslost = 0;
		$this->playerstats_penaltiessaved = 0;
		$this->playerstats_penaltyshootout_save = 0;
		$this->playerstats_penaltyshootout_lost = 0;
		$this->playerstats_penaltyshootout_hit = 0;
		$this->playerstats_score_goals = 0;
		$this->playerstats_score_assists = 0;
		$this->playerstats_score_minutes = 0;
		$this->playerstats_score_cards = 0;
		$this->playerstats_score_owngoals = 0;
		$this->playerstats_score_penaltieslost = 0;
		$this->playerstats_score_penaltiessaved = 0;
		$this->playerstats_score_oppgoals = 0;
		$this->playerstats_score_nooppgoals = 0;
		$this->playerstats_score_high_loss = 0;
		$this->playerstats_score_high_win = 0;
		$this->playerstats_score_penaltyshootout_save = 0;
		$this->playerstats_score_penaltyshootout_lost = 0;
		$this->playerstats_score_penaltyshootout_hit = 0;
		$this->playerstats_score = 0;
	}

	/**
	 * Initializes internal state of BaseFfbPlayerstats object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [playerstats_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsId()
	{
		return $this->playerstats_id;
	}

	/**
	 * Get the [playerstats_playerteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPlayerteamId()
	{
		return $this->playerstats_playerteam_id;
	}

	/**
	 * Get the [playerstats_match_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsMatchId()
	{
		return $this->playerstats_match_id;
	}

	/**
	 * Get the [playerstats_matchround_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsMatchroundId()
	{
		return $this->playerstats_matchround_id;
	}

	/**
	 * Get the [playerstats_goals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsGoals()
	{
		return $this->playerstats_goals;
	}

	/**
	 * Get the [playerstats_assists] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsAssists()
	{
		return $this->playerstats_assists;
	}

	/**
	 * Get the [playerstats_minutes] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsMinutes()
	{
		return $this->playerstats_minutes;
	}

	/**
	 * Get the [playerstats_minute_in] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsMinuteIn()
	{
		return $this->playerstats_minute_in;
	}

	/**
	 * Get the [playerstats_minute_out] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsMinuteOut()
	{
		return $this->playerstats_minute_out;
	}

	/**
	 * Get the [playerstats_cards] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerstatsCards()
	{
		return $this->playerstats_cards;
	}

	/**
	 * Get the [playerstats_owngoals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsOwngoals()
	{
		return $this->playerstats_owngoals;
	}

	/**
	 * Get the [playerstats_penaltieslost] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPenaltieslost()
	{
		return $this->playerstats_penaltieslost;
	}

	/**
	 * Get the [playerstats_penaltiessaved] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPenaltiessaved()
	{
		return $this->playerstats_penaltiessaved;
	}

	/**
	 * Get the [playerstats_penaltyshootout_save] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPenaltyshootoutSave()
	{
		return $this->playerstats_penaltyshootout_save;
	}

	/**
	 * Get the [playerstats_penaltyshootout_lost] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPenaltyshootoutLost()
	{
		return $this->playerstats_penaltyshootout_lost;
	}

	/**
	 * Get the [playerstats_penaltyshootout_hit] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsPenaltyshootoutHit()
	{
		return $this->playerstats_penaltyshootout_hit;
	}

	/**
	 * Get the [playerstats_score_goals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreGoals()
	{
		return $this->playerstats_score_goals;
	}

	/**
	 * Get the [playerstats_score_assists] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreAssists()
	{
		return $this->playerstats_score_assists;
	}

	/**
	 * Get the [playerstats_score_minutes] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreMinutes()
	{
		return $this->playerstats_score_minutes;
	}

	/**
	 * Get the [playerstats_score_cards] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreCards()
	{
		return $this->playerstats_score_cards;
	}

	/**
	 * Get the [playerstats_score_owngoals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreOwngoals()
	{
		return $this->playerstats_score_owngoals;
	}

	/**
	 * Get the [playerstats_score_penaltieslost] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScorePenaltieslost()
	{
		return $this->playerstats_score_penaltieslost;
	}

	/**
	 * Get the [playerstats_score_penaltiessaved] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScorePenaltiessaved()
	{
		return $this->playerstats_score_penaltiessaved;
	}

	/**
	 * Get the [playerstats_score_oppgoals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreOppgoals()
	{
		return $this->playerstats_score_oppgoals;
	}

	/**
	 * Get the [playerstats_score_nooppgoals] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreNooppgoals()
	{
		return $this->playerstats_score_nooppgoals;
	}

	/**
	 * Get the [playerstats_score_high_loss] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreHighLoss()
	{
		return $this->playerstats_score_high_loss;
	}

	/**
	 * Get the [playerstats_score_high_win] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScoreHighWin()
	{
		return $this->playerstats_score_high_win;
	}

	/**
	 * Get the [playerstats_score_penaltyshootout_save] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScorePenaltyshootoutSave()
	{
		return $this->playerstats_score_penaltyshootout_save;
	}

	/**
	 * Get the [playerstats_score_penaltyshootout_lost] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScorePenaltyshootoutLost()
	{
		return $this->playerstats_score_penaltyshootout_lost;
	}

	/**
	 * Get the [playerstats_score_penaltyshootout_hit] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScorePenaltyshootoutHit()
	{
		return $this->playerstats_score_penaltyshootout_hit;
	}

	/**
	 * Get the [playerstats_score] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerstatsScore()
	{
		return $this->playerstats_score;
	}

	/**
	 * Set the value of [playerstats_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_id !== $v) {
			$this->playerstats_id = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_ID;
		}

		return $this;
	} // setPlayerstatsId()

	/**
	 * Set the value of [playerstats_playerteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPlayerteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_playerteam_id !== $v) {
			$this->playerstats_playerteam_id = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID;
		}

		if ($this->aFfbPlayerteam !== null && $this->aFfbPlayerteam->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteam = null;
		}

		return $this;
	} // setPlayerstatsPlayerteamId()

	/**
	 * Set the value of [playerstats_match_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsMatchId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_match_id !== $v) {
			$this->playerstats_match_id = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID;
		}

		if ($this->aFfbMatch !== null && $this->aFfbMatch->getMatchId() !== $v) {
			$this->aFfbMatch = null;
		}

		return $this;
	} // setPlayerstatsMatchId()

	/**
	 * Set the value of [playerstats_matchround_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsMatchroundId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_matchround_id !== $v) {
			$this->playerstats_matchround_id = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID;
		}

		if ($this->aFfbMatchround !== null && $this->aFfbMatchround->getMatchroundId() !== $v) {
			$this->aFfbMatchround = null;
		}

		return $this;
	} // setPlayerstatsMatchroundId()

	/**
	 * Set the value of [playerstats_goals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsGoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_goals !== $v || $this->isNew()) {
			$this->playerstats_goals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_GOALS;
		}

		return $this;
	} // setPlayerstatsGoals()

	/**
	 * Set the value of [playerstats_assists] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsAssists($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_assists !== $v || $this->isNew()) {
			$this->playerstats_assists = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS;
		}

		return $this;
	} // setPlayerstatsAssists()

	/**
	 * Set the value of [playerstats_minutes] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsMinutes($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_minutes !== $v || $this->isNew()) {
			$this->playerstats_minutes = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_MINUTES;
		}

		return $this;
	} // setPlayerstatsMinutes()

	/**
	 * Set the value of [playerstats_minute_in] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsMinuteIn($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_minute_in !== $v || $this->isNew()) {
			$this->playerstats_minute_in = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN;
		}

		return $this;
	} // setPlayerstatsMinuteIn()

	/**
	 * Set the value of [playerstats_minute_out] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsMinuteOut($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_minute_out !== $v || $this->isNew()) {
			$this->playerstats_minute_out = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT;
		}

		return $this;
	} // setPlayerstatsMinuteOut()

	/**
	 * Set the value of [playerstats_cards] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsCards($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerstats_cards !== $v || $this->isNew()) {
			$this->playerstats_cards = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_CARDS;
		}

		return $this;
	} // setPlayerstatsCards()

	/**
	 * Set the value of [playerstats_owngoals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsOwngoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_owngoals !== $v || $this->isNew()) {
			$this->playerstats_owngoals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS;
		}

		return $this;
	} // setPlayerstatsOwngoals()

	/**
	 * Set the value of [playerstats_penaltieslost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPenaltieslost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_penaltieslost !== $v || $this->isNew()) {
			$this->playerstats_penaltieslost = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST;
		}

		return $this;
	} // setPlayerstatsPenaltieslost()

	/**
	 * Set the value of [playerstats_penaltiessaved] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPenaltiessaved($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_penaltiessaved !== $v || $this->isNew()) {
			$this->playerstats_penaltiessaved = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED;
		}

		return $this;
	} // setPlayerstatsPenaltiessaved()

	/**
	 * Set the value of [playerstats_penaltyshootout_save] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPenaltyshootoutSave($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_penaltyshootout_save !== $v || $this->isNew()) {
			$this->playerstats_penaltyshootout_save = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE;
		}

		return $this;
	} // setPlayerstatsPenaltyshootoutSave()

	/**
	 * Set the value of [playerstats_penaltyshootout_lost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPenaltyshootoutLost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_penaltyshootout_lost !== $v || $this->isNew()) {
			$this->playerstats_penaltyshootout_lost = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST;
		}

		return $this;
	} // setPlayerstatsPenaltyshootoutLost()

	/**
	 * Set the value of [playerstats_penaltyshootout_hit] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsPenaltyshootoutHit($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_penaltyshootout_hit !== $v || $this->isNew()) {
			$this->playerstats_penaltyshootout_hit = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT;
		}

		return $this;
	} // setPlayerstatsPenaltyshootoutHit()

	/**
	 * Set the value of [playerstats_score_goals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreGoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_goals !== $v || $this->isNew()) {
			$this->playerstats_score_goals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS;
		}

		return $this;
	} // setPlayerstatsScoreGoals()

	/**
	 * Set the value of [playerstats_score_assists] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreAssists($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_assists !== $v || $this->isNew()) {
			$this->playerstats_score_assists = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS;
		}

		return $this;
	} // setPlayerstatsScoreAssists()

	/**
	 * Set the value of [playerstats_score_minutes] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreMinutes($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_minutes !== $v || $this->isNew()) {
			$this->playerstats_score_minutes = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES;
		}

		return $this;
	} // setPlayerstatsScoreMinutes()

	/**
	 * Set the value of [playerstats_score_cards] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreCards($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_cards !== $v || $this->isNew()) {
			$this->playerstats_score_cards = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS;
		}

		return $this;
	} // setPlayerstatsScoreCards()

	/**
	 * Set the value of [playerstats_score_owngoals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreOwngoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_owngoals !== $v || $this->isNew()) {
			$this->playerstats_score_owngoals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS;
		}

		return $this;
	} // setPlayerstatsScoreOwngoals()

	/**
	 * Set the value of [playerstats_score_penaltieslost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScorePenaltieslost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_penaltieslost !== $v || $this->isNew()) {
			$this->playerstats_score_penaltieslost = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST;
		}

		return $this;
	} // setPlayerstatsScorePenaltieslost()

	/**
	 * Set the value of [playerstats_score_penaltiessaved] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScorePenaltiessaved($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_penaltiessaved !== $v || $this->isNew()) {
			$this->playerstats_score_penaltiessaved = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED;
		}

		return $this;
	} // setPlayerstatsScorePenaltiessaved()

	/**
	 * Set the value of [playerstats_score_oppgoals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreOppgoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_oppgoals !== $v || $this->isNew()) {
			$this->playerstats_score_oppgoals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS;
		}

		return $this;
	} // setPlayerstatsScoreOppgoals()

	/**
	 * Set the value of [playerstats_score_nooppgoals] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreNooppgoals($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_nooppgoals !== $v || $this->isNew()) {
			$this->playerstats_score_nooppgoals = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS;
		}

		return $this;
	} // setPlayerstatsScoreNooppgoals()

	/**
	 * Set the value of [playerstats_score_high_loss] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreHighLoss($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_high_loss !== $v || $this->isNew()) {
			$this->playerstats_score_high_loss = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS;
		}

		return $this;
	} // setPlayerstatsScoreHighLoss()

	/**
	 * Set the value of [playerstats_score_high_win] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScoreHighWin($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_high_win !== $v || $this->isNew()) {
			$this->playerstats_score_high_win = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN;
		}

		return $this;
	} // setPlayerstatsScoreHighWin()

	/**
	 * Set the value of [playerstats_score_penaltyshootout_save] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScorePenaltyshootoutSave($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_penaltyshootout_save !== $v || $this->isNew()) {
			$this->playerstats_score_penaltyshootout_save = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE;
		}

		return $this;
	} // setPlayerstatsScorePenaltyshootoutSave()

	/**
	 * Set the value of [playerstats_score_penaltyshootout_lost] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScorePenaltyshootoutLost($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_penaltyshootout_lost !== $v || $this->isNew()) {
			$this->playerstats_score_penaltyshootout_lost = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST;
		}

		return $this;
	} // setPlayerstatsScorePenaltyshootoutLost()

	/**
	 * Set the value of [playerstats_score_penaltyshootout_hit] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScorePenaltyshootoutHit($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score_penaltyshootout_hit !== $v || $this->isNew()) {
			$this->playerstats_score_penaltyshootout_hit = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT;
		}

		return $this;
	} // setPlayerstatsScorePenaltyshootoutHit()

	/**
	 * Set the value of [playerstats_score] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 */
	public function setPlayerstatsScore($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerstats_score !== $v || $this->isNew()) {
			$this->playerstats_score = $v;
			$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_SCORE;
		}

		return $this;
	} // setPlayerstatsScore()

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
			if ($this->playerstats_goals !== 0) {
				return false;
			}

			if ($this->playerstats_assists !== 0) {
				return false;
			}

			if ($this->playerstats_minutes !== 0) {
				return false;
			}

			if ($this->playerstats_minute_in !== 0) {
				return false;
			}

			if ($this->playerstats_minute_out !== 0) {
				return false;
			}

			if ($this->playerstats_cards !== '') {
				return false;
			}

			if ($this->playerstats_owngoals !== 0) {
				return false;
			}

			if ($this->playerstats_penaltieslost !== 0) {
				return false;
			}

			if ($this->playerstats_penaltiessaved !== 0) {
				return false;
			}

			if ($this->playerstats_penaltyshootout_save !== 0) {
				return false;
			}

			if ($this->playerstats_penaltyshootout_lost !== 0) {
				return false;
			}

			if ($this->playerstats_penaltyshootout_hit !== 0) {
				return false;
			}

			if ($this->playerstats_score_goals !== 0) {
				return false;
			}

			if ($this->playerstats_score_assists !== 0) {
				return false;
			}

			if ($this->playerstats_score_minutes !== 0) {
				return false;
			}

			if ($this->playerstats_score_cards !== 0) {
				return false;
			}

			if ($this->playerstats_score_owngoals !== 0) {
				return false;
			}

			if ($this->playerstats_score_penaltieslost !== 0) {
				return false;
			}

			if ($this->playerstats_score_penaltiessaved !== 0) {
				return false;
			}

			if ($this->playerstats_score_oppgoals !== 0) {
				return false;
			}

			if ($this->playerstats_score_nooppgoals !== 0) {
				return false;
			}

			if ($this->playerstats_score_high_loss !== 0) {
				return false;
			}

			if ($this->playerstats_score_high_win !== 0) {
				return false;
			}

			if ($this->playerstats_score_penaltyshootout_save !== 0) {
				return false;
			}

			if ($this->playerstats_score_penaltyshootout_lost !== 0) {
				return false;
			}

			if ($this->playerstats_score_penaltyshootout_hit !== 0) {
				return false;
			}

			if ($this->playerstats_score !== 0) {
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

			$this->playerstats_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->playerstats_playerteam_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->playerstats_match_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->playerstats_matchround_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->playerstats_goals = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->playerstats_assists = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->playerstats_minutes = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->playerstats_minute_in = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->playerstats_minute_out = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->playerstats_cards = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->playerstats_owngoals = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->playerstats_penaltieslost = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->playerstats_penaltiessaved = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
			$this->playerstats_penaltyshootout_save = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
			$this->playerstats_penaltyshootout_lost = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
			$this->playerstats_penaltyshootout_hit = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
			$this->playerstats_score_goals = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
			$this->playerstats_score_assists = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
			$this->playerstats_score_minutes = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
			$this->playerstats_score_cards = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
			$this->playerstats_score_owngoals = ($row[$startcol + 20] !== null) ? (int) $row[$startcol + 20] : null;
			$this->playerstats_score_penaltieslost = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
			$this->playerstats_score_penaltiessaved = ($row[$startcol + 22] !== null) ? (int) $row[$startcol + 22] : null;
			$this->playerstats_score_oppgoals = ($row[$startcol + 23] !== null) ? (int) $row[$startcol + 23] : null;
			$this->playerstats_score_nooppgoals = ($row[$startcol + 24] !== null) ? (int) $row[$startcol + 24] : null;
			$this->playerstats_score_high_loss = ($row[$startcol + 25] !== null) ? (int) $row[$startcol + 25] : null;
			$this->playerstats_score_high_win = ($row[$startcol + 26] !== null) ? (int) $row[$startcol + 26] : null;
			$this->playerstats_score_penaltyshootout_save = ($row[$startcol + 27] !== null) ? (int) $row[$startcol + 27] : null;
			$this->playerstats_score_penaltyshootout_lost = ($row[$startcol + 28] !== null) ? (int) $row[$startcol + 28] : null;
			$this->playerstats_score_penaltyshootout_hit = ($row[$startcol + 29] !== null) ? (int) $row[$startcol + 29] : null;
			$this->playerstats_score = ($row[$startcol + 30] !== null) ? (int) $row[$startcol + 30] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 31; // 31 = FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPlayerstats object", $e);
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

		if ($this->aFfbPlayerteam !== null && $this->playerstats_playerteam_id !== $this->aFfbPlayerteam->getPlayerteamId()) {
			$this->aFfbPlayerteam = null;
		}
		if ($this->aFfbMatch !== null && $this->playerstats_match_id !== $this->aFfbMatch->getMatchId()) {
			$this->aFfbMatch = null;
		}
		if ($this->aFfbMatchround !== null && $this->playerstats_matchround_id !== $this->aFfbMatchround->getMatchroundId()) {
			$this->aFfbMatchround = null;
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
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPlayerstatsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPlayerteam = null;
			$this->aFfbMatch = null;
			$this->aFfbMatchround = null;
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
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerstatsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPlayerstatsPeer::addInstanceToPool($this);
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

			if ($this->aFfbPlayerteam !== null) {
				if ($this->aFfbPlayerteam->isModified() || $this->aFfbPlayerteam->isNew()) {
					$affectedRows += $this->aFfbPlayerteam->save($con);
				}
				$this->setFfbPlayerteam($this->aFfbPlayerteam);
			}

			if ($this->aFfbMatch !== null) {
				if ($this->aFfbMatch->isModified() || $this->aFfbMatch->isNew()) {
					$affectedRows += $this->aFfbMatch->save($con);
				}
				$this->setFfbMatch($this->aFfbMatch);
			}

			if ($this->aFfbMatchround !== null) {
				if ($this->aFfbMatchround->isModified() || $this->aFfbMatchround->isNew()) {
					$affectedRows += $this->aFfbMatchround->save($con);
				}
				$this->setFfbMatchround($this->aFfbMatchround);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPlayerstatsPeer::PLAYERSTATS_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbPlayerstatsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setPlayerstatsId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbPlayerstatsPeer::doUpdate($this, $con);
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

			if ($this->aFfbPlayerteam !== null) {
				if (!$this->aFfbPlayerteam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteam->getValidationFailures());
				}
			}

			if ($this->aFfbMatch !== null) {
				if (!$this->aFfbMatch->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatch->getValidationFailures());
				}
			}

			if ($this->aFfbMatchround !== null) {
				if (!$this->aFfbMatchround->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatchround->getValidationFailures());
				}
			}


			if (($retval = FfbPlayerstatsPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbPlayerstatsPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_ID)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, $this->playerstats_id);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $this->playerstats_playerteam_id);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $this->playerstats_match_id);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $this->playerstats_matchround_id);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_GOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, $this->playerstats_goals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS, $this->playerstats_assists);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES, $this->playerstats_minutes);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN, $this->playerstats_minute_in);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT, $this->playerstats_minute_out);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_CARDS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_CARDS, $this->playerstats_cards);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS, $this->playerstats_owngoals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST, $this->playerstats_penaltieslost);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED, $this->playerstats_penaltiessaved);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE, $this->playerstats_penaltyshootout_save);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST, $this->playerstats_penaltyshootout_lost);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT, $this->playerstats_penaltyshootout_hit);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS, $this->playerstats_score_goals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS, $this->playerstats_score_assists);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES, $this->playerstats_score_minutes);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS, $this->playerstats_score_cards);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS, $this->playerstats_score_owngoals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST, $this->playerstats_score_penaltieslost);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED, $this->playerstats_score_penaltiessaved);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS, $this->playerstats_score_oppgoals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS, $this->playerstats_score_nooppgoals);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS, $this->playerstats_score_high_loss);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN, $this->playerstats_score_high_win);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE, $this->playerstats_score_penaltyshootout_save);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST, $this->playerstats_score_penaltyshootout_lost);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT, $this->playerstats_score_penaltyshootout_hit);
		if ($this->isColumnModified(FfbPlayerstatsPeer::PLAYERSTATS_SCORE)) $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_SCORE, $this->playerstats_score);

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
		$criteria = new Criteria(FfbPlayerstatsPeer::DATABASE_NAME);

		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, $this->playerstats_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPlayerstatsId();
	}

	/**
	 * Generic method to set the primary key (playerstats_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPlayerstatsId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPlayerstats (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPlayerstatsPlayerteamId($this->playerstats_playerteam_id);

		$copyObj->setPlayerstatsMatchId($this->playerstats_match_id);

		$copyObj->setPlayerstatsMatchroundId($this->playerstats_matchround_id);

		$copyObj->setPlayerstatsGoals($this->playerstats_goals);

		$copyObj->setPlayerstatsAssists($this->playerstats_assists);

		$copyObj->setPlayerstatsMinutes($this->playerstats_minutes);

		$copyObj->setPlayerstatsMinuteIn($this->playerstats_minute_in);

		$copyObj->setPlayerstatsMinuteOut($this->playerstats_minute_out);

		$copyObj->setPlayerstatsCards($this->playerstats_cards);

		$copyObj->setPlayerstatsOwngoals($this->playerstats_owngoals);

		$copyObj->setPlayerstatsPenaltieslost($this->playerstats_penaltieslost);

		$copyObj->setPlayerstatsPenaltiessaved($this->playerstats_penaltiessaved);

		$copyObj->setPlayerstatsPenaltyshootoutSave($this->playerstats_penaltyshootout_save);

		$copyObj->setPlayerstatsPenaltyshootoutLost($this->playerstats_penaltyshootout_lost);

		$copyObj->setPlayerstatsPenaltyshootoutHit($this->playerstats_penaltyshootout_hit);

		$copyObj->setPlayerstatsScoreGoals($this->playerstats_score_goals);

		$copyObj->setPlayerstatsScoreAssists($this->playerstats_score_assists);

		$copyObj->setPlayerstatsScoreMinutes($this->playerstats_score_minutes);

		$copyObj->setPlayerstatsScoreCards($this->playerstats_score_cards);

		$copyObj->setPlayerstatsScoreOwngoals($this->playerstats_score_owngoals);

		$copyObj->setPlayerstatsScorePenaltieslost($this->playerstats_score_penaltieslost);

		$copyObj->setPlayerstatsScorePenaltiessaved($this->playerstats_score_penaltiessaved);

		$copyObj->setPlayerstatsScoreOppgoals($this->playerstats_score_oppgoals);

		$copyObj->setPlayerstatsScoreNooppgoals($this->playerstats_score_nooppgoals);

		$copyObj->setPlayerstatsScoreHighLoss($this->playerstats_score_high_loss);

		$copyObj->setPlayerstatsScoreHighWin($this->playerstats_score_high_win);

		$copyObj->setPlayerstatsScorePenaltyshootoutSave($this->playerstats_score_penaltyshootout_save);

		$copyObj->setPlayerstatsScorePenaltyshootoutLost($this->playerstats_score_penaltyshootout_lost);

		$copyObj->setPlayerstatsScorePenaltyshootoutHit($this->playerstats_score_penaltyshootout_hit);

		$copyObj->setPlayerstatsScore($this->playerstats_score);


		$copyObj->setNew(true);

		$copyObj->setPlayerstatsId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbPlayerstats Clone of current object.
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
	 * @return     FfbPlayerstatsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPlayerstatsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteam(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setPlayerstatsPlayerteamId(NULL);
		} else {
			$this->setPlayerstatsPlayerteamId($v->getPlayerteamId());
		}

		$this->aFfbPlayerteam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerstats($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbPlayerteam object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbPlayerteam The associated FfbPlayerteam object.
	 * @throws     PropelException
	 */
	public function getFfbPlayerteam(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteam === null && ($this->playerstats_playerteam_id !== null)) {
			$this->aFfbPlayerteam = FfbPlayerteamPeer::retrieveByPk($this->playerstats_playerteam_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbPlayerteam->addFfbPlayerstatss($this);
			 */
		}
		return $this->aFfbPlayerteam;
	}

	/**
	 * Declares an association between this object and a FfbMatch object.
	 *
	 * @param      FfbMatch $v
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatch(FfbMatch $v = null)
	{
		if ($v === null) {
			$this->setPlayerstatsMatchId(NULL);
		} else {
			$this->setPlayerstatsMatchId($v->getMatchId());
		}

		$this->aFfbMatch = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatch object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerstats($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbMatch object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbMatch The associated FfbMatch object.
	 * @throws     PropelException
	 */
	public function getFfbMatch(PropelPDO $con = null)
	{
		if ($this->aFfbMatch === null && ($this->playerstats_match_id !== null)) {
			$this->aFfbMatch = FfbMatchPeer::retrieveByPk($this->playerstats_match_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbMatch->addFfbPlayerstatss($this);
			 */
		}
		return $this->aFfbMatch;
	}

	/**
	 * Declares an association between this object and a FfbMatchround object.
	 *
	 * @param      FfbMatchround $v
	 * @return     FfbPlayerstats The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatchround(FfbMatchround $v = null)
	{
		if ($v === null) {
			$this->setPlayerstatsMatchroundId(NULL);
		} else {
			$this->setPlayerstatsMatchroundId($v->getMatchroundId());
		}

		$this->aFfbMatchround = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatchround object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerstats($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbMatchround object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbMatchround The associated FfbMatchround object.
	 * @throws     PropelException
	 */
	public function getFfbMatchround(PropelPDO $con = null)
	{
		if ($this->aFfbMatchround === null && ($this->playerstats_matchround_id !== null)) {
			$this->aFfbMatchround = FfbMatchroundPeer::retrieveByPk($this->playerstats_matchround_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbMatchround->addFfbPlayerstatss($this);
			 */
		}
		return $this->aFfbMatchround;
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

			$this->aFfbPlayerteam = null;
			$this->aFfbMatch = null;
			$this->aFfbMatchround = null;
	}

} // BaseFfbPlayerstats
