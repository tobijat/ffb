<?php


/**
 * Base class that represents a row from the 'ffb_match' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbMatch extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbMatchPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbMatchPeer
	 */
	protected static $peer;

	/**
	 * The value for the match_id field.
	 * @var        int
	 */
	protected $match_id;

	/**
	 * The value for the match_round field.
	 * @var        int
	 */
	protected $match_round;

	/**
	 * The value for the match_hometeam_id field.
	 * @var        int
	 */
	protected $match_hometeam_id;

	/**
	 * The value for the match_guestteam_id field.
	 * @var        int
	 */
	protected $match_guestteam_id;

	/**
	 * The value for the match_homescore field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_homescore;

	/**
	 * The value for the match_guestscore field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_guestscore;

	/**
	 * The value for the match_homescore_penalty field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_homescore_penalty;

	/**
	 * The value for the match_guestscore_penalty field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_guestscore_penalty;

	/**
	 * The value for the match_date field.
	 * @var        string
	 */
	protected $match_date;

	/**
	 * The value for the match_minutes field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $match_minutes;

	/**
	 * The value for the match_status field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_status;

	/**
	 * The value for the match_url field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $match_url;

	/**
	 * @var        FfbMatchround
	 */
	protected $aFfbMatchround;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeamRelatedByMatchHometeamId;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeamRelatedByMatchGuestteamId;

	/**
	 * @var        array FfbGoal[] Collection to store aggregation of FfbGoal objects.
	 */
	protected $collFfbGoals;

	/**
	 * @var        array FfbPsgoal[] Collection to store aggregation of FfbPsgoal objects.
	 */
	protected $collFfbPsgoals;

	/**
	 * @var        array FfbPlayerstats[] Collection to store aggregation of FfbPlayerstats objects.
	 */
	protected $collFfbPlayerstatss;

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
		$this->match_homescore = '';
		$this->match_guestscore = '';
		$this->match_homescore_penalty = '';
		$this->match_guestscore_penalty = '';
		$this->match_minutes = 0;
		$this->match_status = '';
		$this->match_url = '';
	}

	/**
	 * Initializes internal state of BaseFfbMatch object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [match_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatchId()
	{
		return $this->match_id;
	}

	/**
	 * Get the [match_round] column value.
	 * 
	 * @return     int
	 */
	public function getMatchRound()
	{
		return $this->match_round;
	}

	/**
	 * Get the [match_hometeam_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatchHometeamId()
	{
		return $this->match_hometeam_id;
	}

	/**
	 * Get the [match_guestteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatchGuestteamId()
	{
		return $this->match_guestteam_id;
	}

	/**
	 * Get the [match_homescore] column value.
	 * 
	 * @return     string
	 */
	public function getMatchHomescore()
	{
		return $this->match_homescore;
	}

	/**
	 * Get the [match_guestscore] column value.
	 * 
	 * @return     string
	 */
	public function getMatchGuestscore()
	{
		return $this->match_guestscore;
	}

	/**
	 * Get the [match_homescore_penalty] column value.
	 * 
	 * @return     string
	 */
	public function getMatchHomescorePenalty()
	{
		return $this->match_homescore_penalty;
	}

	/**
	 * Get the [match_guestscore_penalty] column value.
	 * 
	 * @return     string
	 */
	public function getMatchGuestscorePenalty()
	{
		return $this->match_guestscore_penalty;
	}

	/**
	 * Get the [optionally formatted] temporal [match_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getMatchDate($format = 'Y-m-d H:i:s')
	{
		if ($this->match_date === null) {
			return null;
		}


		if ($this->match_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->match_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->match_date, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return $dt->format(strtr($format, array('%Y'=>'Y','%m'=>'m','%d'=>'d','%H'=>'H','%M'=>'i','%S'=>'s','%A'=>'l','%B'=>'F','%a'=>'D','%b'=>'M','%%'=>'%')));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [match_minutes] column value.
	 * 
	 * @return     int
	 */
	public function getMatchMinutes()
	{
		return $this->match_minutes;
	}

	/**
	 * Get the [match_status] column value.
	 * 
	 * @return     string
	 */
	public function getMatchStatus()
	{
		return $this->match_status;
	}

	/**
	 * Get the [match_url] column value.
	 * 
	 * @return     string
	 */
	public function getMatchUrl()
	{
		return $this->match_url;
	}

	/**
	 * Set the value of [match_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->match_id !== $v) {
			$this->match_id = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_ID;
		}

		return $this;
	} // setMatchId()

	/**
	 * Set the value of [match_round] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchRound($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->match_round !== $v) {
			$this->match_round = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_ROUND;
		}

		if ($this->aFfbMatchround !== null && $this->aFfbMatchround->getMatchroundId() !== $v) {
			$this->aFfbMatchround = null;
		}

		return $this;
	} // setMatchRound()

	/**
	 * Set the value of [match_hometeam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchHometeamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->match_hometeam_id !== $v) {
			$this->match_hometeam_id = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_HOMETEAM_ID;
		}

		if ($this->aFfbTeamRelatedByMatchHometeamId !== null && $this->aFfbTeamRelatedByMatchHometeamId->getTeamId() !== $v) {
			$this->aFfbTeamRelatedByMatchHometeamId = null;
		}

		return $this;
	} // setMatchHometeamId()

	/**
	 * Set the value of [match_guestteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchGuestteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->match_guestteam_id !== $v) {
			$this->match_guestteam_id = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_GUESTTEAM_ID;
		}

		if ($this->aFfbTeamRelatedByMatchGuestteamId !== null && $this->aFfbTeamRelatedByMatchGuestteamId->getTeamId() !== $v) {
			$this->aFfbTeamRelatedByMatchGuestteamId = null;
		}

		return $this;
	} // setMatchGuestteamId()

	/**
	 * Set the value of [match_homescore] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchHomescore($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_homescore !== $v || $this->isNew()) {
			$this->match_homescore = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_HOMESCORE;
		}

		return $this;
	} // setMatchHomescore()

	/**
	 * Set the value of [match_guestscore] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchGuestscore($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_guestscore !== $v || $this->isNew()) {
			$this->match_guestscore = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_GUESTSCORE;
		}

		return $this;
	} // setMatchGuestscore()

	/**
	 * Set the value of [match_homescore_penalty] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchHomescorePenalty($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_homescore_penalty !== $v || $this->isNew()) {
			$this->match_homescore_penalty = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_HOMESCORE_PENALTY;
		}

		return $this;
	} // setMatchHomescorePenalty()

	/**
	 * Set the value of [match_guestscore_penalty] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchGuestscorePenalty($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_guestscore_penalty !== $v || $this->isNew()) {
			$this->match_guestscore_penalty = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_GUESTSCORE_PENALTY;
		}

		return $this;
	} // setMatchGuestscorePenalty()

	/**
	 * Sets the value of [match_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchDate($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->match_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->match_date !== null && $tmpDt = new DateTime($this->match_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->match_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbMatchPeer::MATCH_DATE;
			}
		} // if either are not null

		return $this;
	} // setMatchDate()

	/**
	 * Set the value of [match_minutes] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchMinutes($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->match_minutes !== $v || $this->isNew()) {
			$this->match_minutes = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_MINUTES;
		}

		return $this;
	} // setMatchMinutes()

	/**
	 * Set the value of [match_status] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchStatus($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_status !== $v || $this->isNew()) {
			$this->match_status = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_STATUS;
		}

		return $this;
	} // setMatchStatus()

	/**
	 * Set the value of [match_url] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatch The current object (for fluent API support)
	 */
	public function setMatchUrl($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->match_url !== $v || $this->isNew()) {
			$this->match_url = $v;
			$this->modifiedColumns[] = FfbMatchPeer::MATCH_URL;
		}

		return $this;
	} // setMatchUrl()

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
			if ($this->match_homescore !== '') {
				return false;
			}

			if ($this->match_guestscore !== '') {
				return false;
			}

			if ($this->match_homescore_penalty !== '') {
				return false;
			}

			if ($this->match_guestscore_penalty !== '') {
				return false;
			}

			if ($this->match_minutes !== 0) {
				return false;
			}

			if ($this->match_status !== '') {
				return false;
			}

			if ($this->match_url !== '') {
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

			$this->match_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->match_round = (($row[$startcol + 1] ?? null) !== null) ? (int) $row[$startcol + 1] : null;
			$this->match_hometeam_id = (($row[$startcol + 2] ?? null) !== null) ? (int) $row[$startcol + 2] : null;
			$this->match_guestteam_id = (($row[$startcol + 3] ?? null) !== null) ? (int) $row[$startcol + 3] : null;
			$this->match_homescore = (($row[$startcol + 4] ?? null) !== null) ? (string) $row[$startcol + 4] : null;
			$this->match_guestscore = (($row[$startcol + 5] ?? null) !== null) ? (string) $row[$startcol + 5] : null;
			$this->match_homescore_penalty = (($row[$startcol + 6] ?? null) !== null) ? (string) $row[$startcol + 6] : null;
			$this->match_guestscore_penalty = (($row[$startcol + 7] ?? null) !== null) ? (string) $row[$startcol + 7] : null;
			$this->match_date = (($row[$startcol + 8] ?? null) !== null) ? (string) $row[$startcol + 8] : null;
			$this->match_minutes = (($row[$startcol + 9] ?? null) !== null) ? (int) $row[$startcol + 9] : null;
			$this->match_status = (($row[$startcol + 10] ?? null) !== null) ? (string) $row[$startcol + 10] : null;
			$this->match_url = (($row[$startcol + 11] ?? null) !== null) ? (string) $row[$startcol + 11] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 12; // 12 = FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbMatch object", $e);
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

		if ($this->aFfbMatchround !== null && $this->match_round !== $this->aFfbMatchround->getMatchroundId()) {
			$this->aFfbMatchround = null;
		}
		if ($this->aFfbTeamRelatedByMatchHometeamId !== null && $this->match_hometeam_id !== $this->aFfbTeamRelatedByMatchHometeamId->getTeamId()) {
			$this->aFfbTeamRelatedByMatchHometeamId = null;
		}
		if ($this->aFfbTeamRelatedByMatchGuestteamId !== null && $this->match_guestteam_id !== $this->aFfbTeamRelatedByMatchGuestteamId->getTeamId()) {
			$this->aFfbTeamRelatedByMatchGuestteamId = null;
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
	public function reload($deep = false, ?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbMatchPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbMatchround = null;
			$this->aFfbTeamRelatedByMatchHometeamId = null;
			$this->aFfbTeamRelatedByMatchGuestteamId = null;
			$this->collFfbGoals = null;

			$this->collFfbPsgoals = null;

			$this->collFfbPlayerstatss = null;

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
	public function delete(?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbMatchQuery::create()
					->filterByPrimaryKey($this->getPrimaryKey())
					->delete($con);
				$this->postDelete($con);
				$con->commit();
				$this->setDeleted(true);
			} else {
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
	public function save(?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbMatchPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
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

			if ($this->aFfbMatchround !== null) {
				if ($this->aFfbMatchround->isModified() || $this->aFfbMatchround->isNew()) {
					$affectedRows += $this->aFfbMatchround->save($con);
				}
				$this->setFfbMatchround($this->aFfbMatchround);
			}

			if ($this->aFfbTeamRelatedByMatchHometeamId !== null) {
				if ($this->aFfbTeamRelatedByMatchHometeamId->isModified() || $this->aFfbTeamRelatedByMatchHometeamId->isNew()) {
					$affectedRows += $this->aFfbTeamRelatedByMatchHometeamId->save($con);
				}
				$this->setFfbTeamRelatedByMatchHometeamId($this->aFfbTeamRelatedByMatchHometeamId);
			}

			if ($this->aFfbTeamRelatedByMatchGuestteamId !== null) {
				if ($this->aFfbTeamRelatedByMatchGuestteamId->isModified() || $this->aFfbTeamRelatedByMatchGuestteamId->isNew()) {
					$affectedRows += $this->aFfbTeamRelatedByMatchGuestteamId->save($con);
				}
				$this->setFfbTeamRelatedByMatchGuestteamId($this->aFfbTeamRelatedByMatchGuestteamId);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbMatchPeer::MATCH_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbMatchPeer::MATCH_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbMatchPeer::MATCH_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setMatchId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbMatchPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collFfbGoals !== null) {
				foreach ($this->collFfbGoals as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPsgoals !== null) {
				foreach ($this->collFfbPsgoals as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPlayerstatss !== null) {
				foreach ($this->collFfbPlayerstatss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
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

			if ($this->aFfbMatchround !== null) {
				if (!$this->aFfbMatchround->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatchround->getValidationFailures());
				}
			}

			if ($this->aFfbTeamRelatedByMatchHometeamId !== null) {
				if (!$this->aFfbTeamRelatedByMatchHometeamId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeamRelatedByMatchHometeamId->getValidationFailures());
				}
			}

			if ($this->aFfbTeamRelatedByMatchGuestteamId !== null) {
				if (!$this->aFfbTeamRelatedByMatchGuestteamId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeamRelatedByMatchGuestteamId->getValidationFailures());
				}
			}


			if (($retval = FfbMatchPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbGoals !== null) {
					foreach ($this->collFfbGoals as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPsgoals !== null) {
					foreach ($this->collFfbPsgoals as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPlayerstatss !== null) {
					foreach ($this->collFfbPlayerstatss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FfbMatchPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getMatchId();
				break;
			case 1:
				return $this->getMatchRound();
				break;
			case 2:
				return $this->getMatchHometeamId();
				break;
			case 3:
				return $this->getMatchGuestteamId();
				break;
			case 4:
				return $this->getMatchHomescore();
				break;
			case 5:
				return $this->getMatchGuestscore();
				break;
			case 6:
				return $this->getMatchHomescorePenalty();
				break;
			case 7:
				return $this->getMatchGuestscorePenalty();
				break;
			case 8:
				return $this->getMatchDate();
				break;
			case 9:
				return $this->getMatchMinutes();
				break;
			case 10:
				return $this->getMatchStatus();
				break;
			case 11:
				return $this->getMatchUrl();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 *                    Defaults to BasePeer::TYPE_PHPNAME.
	 * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
	 * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $includeForeignObjects = false)
	{
		$keys = FfbMatchPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getMatchId(),
			$keys[1] => $this->getMatchRound(),
			$keys[2] => $this->getMatchHometeamId(),
			$keys[3] => $this->getMatchGuestteamId(),
			$keys[4] => $this->getMatchHomescore(),
			$keys[5] => $this->getMatchGuestscore(),
			$keys[6] => $this->getMatchHomescorePenalty(),
			$keys[7] => $this->getMatchGuestscorePenalty(),
			$keys[8] => $this->getMatchDate(),
			$keys[9] => $this->getMatchMinutes(),
			$keys[10] => $this->getMatchStatus(),
			$keys[11] => $this->getMatchUrl(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbMatchround) {
				$result['FfbMatchround'] = $this->aFfbMatchround->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbTeamRelatedByMatchHometeamId) {
				$result['FfbTeamRelatedByMatchHometeamId'] = $this->aFfbTeamRelatedByMatchHometeamId->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbTeamRelatedByMatchGuestteamId) {
				$result['FfbTeamRelatedByMatchGuestteamId'] = $this->aFfbTeamRelatedByMatchGuestteamId->toArray($keyType, $includeLazyLoadColumns, true);
			}
		}
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FfbMatchPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setMatchId($value);
				break;
			case 1:
				$this->setMatchRound($value);
				break;
			case 2:
				$this->setMatchHometeamId($value);
				break;
			case 3:
				$this->setMatchGuestteamId($value);
				break;
			case 4:
				$this->setMatchHomescore($value);
				break;
			case 5:
				$this->setMatchGuestscore($value);
				break;
			case 6:
				$this->setMatchHomescorePenalty($value);
				break;
			case 7:
				$this->setMatchGuestscorePenalty($value);
				break;
			case 8:
				$this->setMatchDate($value);
				break;
			case 9:
				$this->setMatchMinutes($value);
				break;
			case 10:
				$this->setMatchStatus($value);
				break;
			case 11:
				$this->setMatchUrl($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FfbMatchPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setMatchId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMatchRound($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMatchHometeamId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setMatchGuestteamId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMatchHomescore($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setMatchGuestscore($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setMatchHomescorePenalty($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setMatchGuestscorePenalty($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setMatchDate($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setMatchMinutes($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setMatchStatus($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setMatchUrl($arr[$keys[11]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbMatchPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbMatchPeer::MATCH_ID)) $criteria->add(FfbMatchPeer::MATCH_ID, $this->match_id);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_ROUND)) $criteria->add(FfbMatchPeer::MATCH_ROUND, $this->match_round);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_HOMETEAM_ID)) $criteria->add(FfbMatchPeer::MATCH_HOMETEAM_ID, $this->match_hometeam_id);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_GUESTTEAM_ID)) $criteria->add(FfbMatchPeer::MATCH_GUESTTEAM_ID, $this->match_guestteam_id);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_HOMESCORE)) $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, $this->match_homescore);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_GUESTSCORE)) $criteria->add(FfbMatchPeer::MATCH_GUESTSCORE, $this->match_guestscore);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_HOMESCORE_PENALTY)) $criteria->add(FfbMatchPeer::MATCH_HOMESCORE_PENALTY, $this->match_homescore_penalty);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_GUESTSCORE_PENALTY)) $criteria->add(FfbMatchPeer::MATCH_GUESTSCORE_PENALTY, $this->match_guestscore_penalty);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_DATE)) $criteria->add(FfbMatchPeer::MATCH_DATE, $this->match_date);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_MINUTES)) $criteria->add(FfbMatchPeer::MATCH_MINUTES, $this->match_minutes);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_STATUS)) $criteria->add(FfbMatchPeer::MATCH_STATUS, $this->match_status);
		if ($this->isColumnModified(FfbMatchPeer::MATCH_URL)) $criteria->add(FfbMatchPeer::MATCH_URL, $this->match_url);

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
		$criteria = new Criteria(FfbMatchPeer::DATABASE_NAME);
		$criteria->add(FfbMatchPeer::MATCH_ID, $this->match_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getMatchId();
	}

	/**
	 * Generic method to set the primary key (match_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setMatchId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getMatchId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbMatch (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setMatchRound($this->match_round);
		$copyObj->setMatchHometeamId($this->match_hometeam_id);
		$copyObj->setMatchGuestteamId($this->match_guestteam_id);
		$copyObj->setMatchHomescore($this->match_homescore);
		$copyObj->setMatchGuestscore($this->match_guestscore);
		$copyObj->setMatchHomescorePenalty($this->match_homescore_penalty);
		$copyObj->setMatchGuestscorePenalty($this->match_guestscore_penalty);
		$copyObj->setMatchDate($this->match_date);
		$copyObj->setMatchMinutes($this->match_minutes);
		$copyObj->setMatchStatus($this->match_status);
		$copyObj->setMatchUrl($this->match_url);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbGoals() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbGoal($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPsgoals() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPsgoal($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerstatss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerstats($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setMatchId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbMatch Clone of current object.
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
	 * @return     FfbMatchPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbMatchPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbMatchround object.
	 *
	 * @param      FfbMatchround $v
	 * @return     FfbMatch The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatchround(?FfbMatchround $v = null)
	{
		if ($v === null) {
			$this->setMatchRound(NULL);
		} else {
			$this->setMatchRound($v->getMatchroundId());
		}

		$this->aFfbMatchround = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatchround object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbMatch($this);
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
	public function getFfbMatchround(?PropelPDO $con = null)
	{
		if ($this->aFfbMatchround === null && ($this->match_round !== null)) {
			$this->aFfbMatchround = FfbMatchroundQuery::create()->findPk($this->match_round, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbMatchround->addFfbMatchs($this);
			 */
		}
		return $this->aFfbMatchround;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     FfbMatch The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeamRelatedByMatchHometeamId(?FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setMatchHometeamId(NULL);
		} else {
			$this->setMatchHometeamId($v->getTeamId());
		}

		$this->aFfbTeamRelatedByMatchHometeamId = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbMatchRelatedByMatchHometeamId($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbTeam object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbTeam The associated FfbTeam object.
	 * @throws     PropelException
	 */
	public function getFfbTeamRelatedByMatchHometeamId(?PropelPDO $con = null)
	{
		if ($this->aFfbTeamRelatedByMatchHometeamId === null && ($this->match_hometeam_id !== null)) {
			$this->aFfbTeamRelatedByMatchHometeamId = FfbTeamQuery::create()->findPk($this->match_hometeam_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbTeamRelatedByMatchHometeamId->addFfbMatchsRelatedByMatchHometeamId($this);
			 */
		}
		return $this->aFfbTeamRelatedByMatchHometeamId;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     FfbMatch The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeamRelatedByMatchGuestteamId(?FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setMatchGuestteamId(NULL);
		} else {
			$this->setMatchGuestteamId($v->getTeamId());
		}

		$this->aFfbTeamRelatedByMatchGuestteamId = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbMatchRelatedByMatchGuestteamId($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbTeam object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbTeam The associated FfbTeam object.
	 * @throws     PropelException
	 */
	public function getFfbTeamRelatedByMatchGuestteamId(?PropelPDO $con = null)
	{
		if ($this->aFfbTeamRelatedByMatchGuestteamId === null && ($this->match_guestteam_id !== null)) {
			$this->aFfbTeamRelatedByMatchGuestteamId = FfbTeamQuery::create()->findPk($this->match_guestteam_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbTeamRelatedByMatchGuestteamId->addFfbMatchsRelatedByMatchGuestteamId($this);
			 */
		}
		return $this->aFfbTeamRelatedByMatchGuestteamId;
	}

	/**
	 * Clears out the collFfbGoals collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbGoals()
	 */
	public function clearFfbGoals()
	{
		$this->collFfbGoals = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbGoals collection.
	 *
	 * By default this just sets the collFfbGoals collection to an empty array (like clearcollFfbGoals());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbGoals()
	{
		$this->collFfbGoals = new PropelObjectCollection();
		$this->collFfbGoals->setModel('FfbGoal');
	}

	/**
	 * Gets an array of FfbGoal objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatch is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbGoal[] List of FfbGoal objects
	 * @throws     PropelException
	 */
	public function getFfbGoals($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbGoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbGoals) {
				// return empty collection
				$this->initFfbGoals();
			} else {
				$collFfbGoals = FfbGoalQuery::create(null, $criteria)
					->filterByFfbMatch($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbGoals;
				}
				$this->collFfbGoals = $collFfbGoals;
			}
		}
		return $this->collFfbGoals;
	}

	/**
	 * Returns the number of related FfbGoal objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbGoal objects.
	 * @throws     PropelException
	 */
	public function countFfbGoals(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbGoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbGoals) {
				return 0;
			} else {
				$query = FfbGoalQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatch($this)
					->count($con);
			}
		} else {
			return count($this->collFfbGoals);
		}
	}

	/**
	 * Method called to associate a FfbGoal object to this object
	 * through the FfbGoal foreign key attribute.
	 *
	 * @param      FfbGoal $l FfbGoal
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbGoal(FfbGoal $l)
	{
		if ($this->collFfbGoals === null) {
			$this->initFfbGoals();
		}
		if (!$this->collFfbGoals->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbGoals[]= $l;
			$l->setFfbMatch($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatch is new, it will return
	 * an empty collection; or if this FfbMatch has previously
	 * been saved, it will retrieve related FfbGoals from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatch.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbGoal[] List of FfbGoal objects
	 */
	public function getFfbGoalsJoinFfbPlayerteam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbGoalQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteam', $join_behavior);

		return $this->getFfbGoals($query, $con);
	}

	/**
	 * Clears out the collFfbPsgoals collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPsgoals()
	 */
	public function clearFfbPsgoals()
	{
		$this->collFfbPsgoals = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPsgoals collection.
	 *
	 * By default this just sets the collFfbPsgoals collection to an empty array (like clearcollFfbPsgoals());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPsgoals()
	{
		$this->collFfbPsgoals = new PropelObjectCollection();
		$this->collFfbPsgoals->setModel('FfbPsgoal');
	}

	/**
	 * Gets an array of FfbPsgoal objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatch is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPsgoal[] List of FfbPsgoal objects
	 * @throws     PropelException
	 */
	public function getFfbPsgoals($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPsgoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPsgoals) {
				// return empty collection
				$this->initFfbPsgoals();
			} else {
				$collFfbPsgoals = FfbPsgoalQuery::create(null, $criteria)
					->filterByFfbMatch($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPsgoals;
				}
				$this->collFfbPsgoals = $collFfbPsgoals;
			}
		}
		return $this->collFfbPsgoals;
	}

	/**
	 * Returns the number of related FfbPsgoal objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPsgoal objects.
	 * @throws     PropelException
	 */
	public function countFfbPsgoals(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPsgoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPsgoals) {
				return 0;
			} else {
				$query = FfbPsgoalQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatch($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPsgoals);
		}
	}

	/**
	 * Method called to associate a FfbPsgoal object to this object
	 * through the FfbPsgoal foreign key attribute.
	 *
	 * @param      FfbPsgoal $l FfbPsgoal
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPsgoal(FfbPsgoal $l)
	{
		if ($this->collFfbPsgoals === null) {
			$this->initFfbPsgoals();
		}
		if (!$this->collFfbPsgoals->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPsgoals[]= $l;
			$l->setFfbMatch($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatch is new, it will return
	 * an empty collection; or if this FfbMatch has previously
	 * been saved, it will retrieve related FfbPsgoals from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatch.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPsgoal[] List of FfbPsgoal objects
	 */
	public function getFfbPsgoalsJoinFfbPlayerteam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPsgoalQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteam', $join_behavior);

		return $this->getFfbPsgoals($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerstatss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerstatss()
	 */
	public function clearFfbPlayerstatss()
	{
		$this->collFfbPlayerstatss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerstatss collection.
	 *
	 * By default this just sets the collFfbPlayerstatss collection to an empty array (like clearcollFfbPlayerstatss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerstatss()
	{
		$this->collFfbPlayerstatss = new PropelObjectCollection();
		$this->collFfbPlayerstatss->setModel('FfbPlayerstats');
	}

	/**
	 * Gets an array of FfbPlayerstats objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatch is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerstatss($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerstatss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerstatss) {
				// return empty collection
				$this->initFfbPlayerstatss();
			} else {
				$collFfbPlayerstatss = FfbPlayerstatsQuery::create(null, $criteria)
					->filterByFfbMatch($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerstatss;
				}
				$this->collFfbPlayerstatss = $collFfbPlayerstatss;
			}
		}
		return $this->collFfbPlayerstatss;
	}

	/**
	 * Returns the number of related FfbPlayerstats objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerstats objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerstatss(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerstatss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerstatss) {
				return 0;
			} else {
				$query = FfbPlayerstatsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatch($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerstatss);
		}
	}

	/**
	 * Method called to associate a FfbPlayerstats object to this object
	 * through the FfbPlayerstats foreign key attribute.
	 *
	 * @param      FfbPlayerstats $l FfbPlayerstats
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerstats(FfbPlayerstats $l)
	{
		if ($this->collFfbPlayerstatss === null) {
			$this->initFfbPlayerstatss();
		}
		if (!$this->collFfbPlayerstatss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerstatss[]= $l;
			$l->setFfbMatch($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatch is new, it will return
	 * an empty collection; or if this FfbMatch has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatch.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 */
	public function getFfbPlayerstatssJoinFfbPlayerteam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerstatsQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteam', $join_behavior);

		return $this->getFfbPlayerstatss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatch is new, it will return
	 * an empty collection; or if this FfbMatch has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatch.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 */
	public function getFfbPlayerstatssJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerstatsQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbPlayerstatss($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->match_id = null;
		$this->match_round = null;
		$this->match_hometeam_id = null;
		$this->match_guestteam_id = null;
		$this->match_homescore = null;
		$this->match_guestscore = null;
		$this->match_homescore_penalty = null;
		$this->match_guestscore_penalty = null;
		$this->match_date = null;
		$this->match_minutes = null;
		$this->match_status = null;
		$this->match_url = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
		$this->applyDefaultValues();
		$this->resetModified();
		$this->setNew(true);
		$this->setDeleted(false);
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
			if ($this->collFfbGoals) {
				foreach ((array) $this->collFfbGoals as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPsgoals) {
				foreach ((array) $this->collFfbPsgoals as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerstatss) {
				foreach ((array) $this->collFfbPlayerstatss as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collFfbGoals = null;
		$this->collFfbPsgoals = null;
		$this->collFfbPlayerstatss = null;
		$this->aFfbMatchround = null;
		$this->aFfbTeamRelatedByMatchHometeamId = null;
		$this->aFfbTeamRelatedByMatchGuestteamId = null;
	}

	/**
	 * Catches calls to virtual methods
	 */
	public function __call($name, $params)
	{
		if (preg_match('/get(\w+)/', $name, $matches)) {
			$virtualColumn = $matches[1];
			if ($this->hasVirtualColumn($virtualColumn)) {
				return $this->getVirtualColumn($virtualColumn);
			}
			// no lcfirst in php<5.3...
			$virtualColumn[0] = strtolower($virtualColumn[0]);
			if ($this->hasVirtualColumn($virtualColumn)) {
				return $this->getVirtualColumn($virtualColumn);
			}
		}
		return parent::__call($name, $params);
	}

} // BaseFfbMatch
