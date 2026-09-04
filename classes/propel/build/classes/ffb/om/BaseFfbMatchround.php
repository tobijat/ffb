<?php


/**
 * Base class that represents a row from the 'ffb_matchround' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbMatchround extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbMatchroundPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbMatchroundPeer
	 */
	protected static $peer;

	/**
	 * The value for the matchround_id field.
	 * @var        int
	 */
	protected $matchround_id;

	/**
	 * The value for the matchround_game_id field.
	 * @var        int
	 */
	protected $matchround_game_id;

	/**
	 * The value for the matchround_title field.
	 * Note: this column has a database default value of: 'Round'
	 * @var        string
	 */
	protected $matchround_title;

	/**
	 * The value for the matchround_startdate field.
	 * @var        string
	 */
	protected $matchround_startdate;

	/**
	 * The value for the matchround_enddate field.
	 * @var        string
	 */
	protected $matchround_enddate;

	/**
	 * The value for the matchround_status field.
	 * Note: this column has a database default value of: 1
	 * @var        int
	 */
	protected $matchround_status;

	/**
	 * The value for the matchround_credits field.
	 * Note: this column has a database default value of: 0
	 * @var        double
	 */
	protected $matchround_credits;

	/**
	 * The value for the matchround_max_players_from_team field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $matchround_max_players_from_team;

	/**
	 * @var        FfbGame
	 */
	protected $aFfbGame;

	/**
	 * @var        array FfbComments[] Collection to store aggregation of FfbComments objects.
	 */
	protected $collFfbCommentss;

	/**
	 * @var        array FfbPlayerprice[] Collection to store aggregation of FfbPlayerprice objects.
	 */
	protected $collFfbPlayerprices;

	/**
	 * @var        array FfbMatch[] Collection to store aggregation of FfbMatch objects.
	 */
	protected $collFfbMatchs;

	/**
	 * @var        array FfbPlayerstats[] Collection to store aggregation of FfbPlayerstats objects.
	 */
	protected $collFfbPlayerstatss;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteams;

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
		$this->matchround_title = 'Round';
		$this->matchround_status = 1;
		$this->matchround_credits = 0;
		$this->matchround_max_players_from_team = 0;
	}

	/**
	 * Initializes internal state of BaseFfbMatchround object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [matchround_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatchroundId()
	{
		return $this->matchround_id;
	}

	/**
	 * Get the [matchround_game_id] column value.
	 * 
	 * @return     int
	 */
	public function getMatchroundGameId()
	{
		return $this->matchround_game_id;
	}

	/**
	 * Get the [matchround_title] column value.
	 * 
	 * @return     string
	 */
	public function getMatchroundTitle()
	{
		return $this->matchround_title;
	}

	/**
	 * Get the [optionally formatted] temporal [matchround_startdate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getMatchroundStartdate($format = 'Y-m-d H:i:s')
	{
		if ($this->matchround_startdate === null) {
			return null;
		}


		if ($this->matchround_startdate === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->matchround_startdate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->matchround_startdate, true), $x);
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
	 * Get the [optionally formatted] temporal [matchround_enddate] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getMatchroundEnddate($format = 'Y-m-d H:i:s')
	{
		if ($this->matchround_enddate === null) {
			return null;
		}


		if ($this->matchround_enddate === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->matchround_enddate);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->matchround_enddate, true), $x);
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
	 * Get the [matchround_status] column value.
	 * 
	 * @return     int
	 */
	public function getMatchroundStatus()
	{
		return $this->matchround_status;
	}

	/**
	 * Get the [matchround_credits] column value.
	 * 
	 * @return     double
	 */
	public function getMatchroundCredits()
	{
		return $this->matchround_credits;
	}

	/**
	 * Get the [matchround_max_players_from_team] column value.
	 * 
	 * @return     int
	 */
	public function getMatchroundMaxPlayersFromTeam()
	{
		return $this->matchround_max_players_from_team;
	}

	/**
	 * Set the value of [matchround_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->matchround_id !== $v) {
			$this->matchround_id = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_ID;
		}

		return $this;
	} // setMatchroundId()

	/**
	 * Set the value of [matchround_game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->matchround_game_id !== $v) {
			$this->matchround_game_id = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_GAME_ID;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setMatchroundGameId()

	/**
	 * Set the value of [matchround_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->matchround_title !== $v || $this->isNew()) {
			$this->matchround_title = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_TITLE;
		}

		return $this;
	} // setMatchroundTitle()

	/**
	 * Sets the value of [matchround_startdate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundStartdate($v)
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

		if ( $this->matchround_startdate !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->matchround_startdate !== null && $tmpDt = new DateTime($this->matchround_startdate)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->matchround_startdate = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_STARTDATE;
			}
		} // if either are not null

		return $this;
	} // setMatchroundStartdate()

	/**
	 * Sets the value of [matchround_enddate] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundEnddate($v)
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

		if ( $this->matchround_enddate !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->matchround_enddate !== null && $tmpDt = new DateTime($this->matchround_enddate)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->matchround_enddate = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_ENDDATE;
			}
		} // if either are not null

		return $this;
	} // setMatchroundEnddate()

	/**
	 * Set the value of [matchround_status] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundStatus($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->matchround_status !== $v || $this->isNew()) {
			$this->matchround_status = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_STATUS;
		}

		return $this;
	} // setMatchroundStatus()

	/**
	 * Set the value of [matchround_credits] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundCredits($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->matchround_credits !== $v || $this->isNew()) {
			$this->matchround_credits = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_CREDITS;
		}

		return $this;
	} // setMatchroundCredits()

	/**
	 * Set the value of [matchround_max_players_from_team] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbMatchround The current object (for fluent API support)
	 */
	public function setMatchroundMaxPlayersFromTeam($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->matchround_max_players_from_team !== $v || $this->isNew()) {
			$this->matchround_max_players_from_team = $v;
			$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM;
		}

		return $this;
	} // setMatchroundMaxPlayersFromTeam()

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
			if ($this->matchround_title !== 'Round') {
				return false;
			}

			if ($this->matchround_status !== 1) {
				return false;
			}

			if ($this->matchround_credits !== 0) {
				return false;
			}

			if ($this->matchround_max_players_from_team !== 0) {
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

			$this->matchround_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->matchround_game_id = (($row[$startcol + 1] ?? null) !== null) ? (int) $row[$startcol + 1] : null;
			$this->matchround_title = (($row[$startcol + 2] ?? null) !== null) ? (string) $row[$startcol + 2] : null;
			$this->matchround_startdate = (($row[$startcol + 3] ?? null) !== null) ? (string) $row[$startcol + 3] : null;
			$this->matchround_enddate = (($row[$startcol + 4] ?? null) !== null) ? (string) $row[$startcol + 4] : null;
			$this->matchround_status = (($row[$startcol + 5] ?? null) !== null) ? (int) $row[$startcol + 5] : null;
			$this->matchround_credits = (($row[$startcol + 6] ?? null) !== null) ? (double) $row[$startcol + 6] : null;
			$this->matchround_max_players_from_team = (($row[$startcol + 7] ?? null) !== null) ? (int) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 8; // 8 = FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbMatchround object", $e);
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

		if ($this->aFfbGame !== null && $this->matchround_game_id !== $this->aFfbGame->getGameId()) {
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
	public function reload($deep = false, ?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchroundPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbMatchroundPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbGame = null;
			$this->collFfbCommentss = null;

			$this->collFfbPlayerprices = null;

			$this->collFfbMatchs = null;

			$this->collFfbPlayerstatss = null;

			$this->collFfbUserteams = null;

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
			$con = Propel::getConnection(FfbMatchroundPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbMatchroundQuery::create()
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
			$con = Propel::getConnection(FfbMatchroundPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbMatchroundPeer::addInstanceToPool($this);
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

			if ($this->aFfbGame !== null) {
				if ($this->aFfbGame->isModified() || $this->aFfbGame->isNew()) {
					$affectedRows += $this->aFfbGame->save($con);
				}
				$this->setFfbGame($this->aFfbGame);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbMatchroundPeer::MATCHROUND_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbMatchroundPeer::MATCHROUND_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbMatchroundPeer::MATCHROUND_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setMatchroundId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbMatchroundPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collFfbCommentss !== null) {
				foreach ($this->collFfbCommentss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPlayerprices !== null) {
				foreach ($this->collFfbPlayerprices as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbMatchs !== null) {
				foreach ($this->collFfbMatchs as $referrerFK) {
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

			if ($this->collFfbUserteams !== null) {
				foreach ($this->collFfbUserteams as $referrerFK) {
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

			if ($this->aFfbGame !== null) {
				if (!$this->aFfbGame->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbGame->getValidationFailures());
				}
			}


			if (($retval = FfbMatchroundPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbCommentss !== null) {
					foreach ($this->collFfbCommentss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPlayerprices !== null) {
					foreach ($this->collFfbPlayerprices as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbMatchs !== null) {
					foreach ($this->collFfbMatchs as $referrerFK) {
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

				if ($this->collFfbUserteams !== null) {
					foreach ($this->collFfbUserteams as $referrerFK) {
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
		$pos = FfbMatchroundPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getMatchroundId();
				break;
			case 1:
				return $this->getMatchroundGameId();
				break;
			case 2:
				return $this->getMatchroundTitle();
				break;
			case 3:
				return $this->getMatchroundStartdate();
				break;
			case 4:
				return $this->getMatchroundEnddate();
				break;
			case 5:
				return $this->getMatchroundStatus();
				break;
			case 6:
				return $this->getMatchroundCredits();
				break;
			case 7:
				return $this->getMatchroundMaxPlayersFromTeam();
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
		$keys = FfbMatchroundPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getMatchroundId(),
			$keys[1] => $this->getMatchroundGameId(),
			$keys[2] => $this->getMatchroundTitle(),
			$keys[3] => $this->getMatchroundStartdate(),
			$keys[4] => $this->getMatchroundEnddate(),
			$keys[5] => $this->getMatchroundStatus(),
			$keys[6] => $this->getMatchroundCredits(),
			$keys[7] => $this->getMatchroundMaxPlayersFromTeam(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbGame) {
				$result['FfbGame'] = $this->aFfbGame->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = FfbMatchroundPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setMatchroundId($value);
				break;
			case 1:
				$this->setMatchroundGameId($value);
				break;
			case 2:
				$this->setMatchroundTitle($value);
				break;
			case 3:
				$this->setMatchroundStartdate($value);
				break;
			case 4:
				$this->setMatchroundEnddate($value);
				break;
			case 5:
				$this->setMatchroundStatus($value);
				break;
			case 6:
				$this->setMatchroundCredits($value);
				break;
			case 7:
				$this->setMatchroundMaxPlayersFromTeam($value);
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
		$keys = FfbMatchroundPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setMatchroundId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMatchroundGameId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMatchroundTitle($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setMatchroundStartdate($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setMatchroundEnddate($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setMatchroundStatus($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setMatchroundCredits($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setMatchroundMaxPlayersFromTeam($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbMatchroundPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_ID)) $criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $this->matchround_id);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_GAME_ID)) $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->matchround_game_id);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_TITLE)) $criteria->add(FfbMatchroundPeer::MATCHROUND_TITLE, $this->matchround_title);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_STARTDATE)) $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $this->matchround_startdate);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_ENDDATE)) $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $this->matchround_enddate);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_STATUS)) $criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, $this->matchround_status);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_CREDITS)) $criteria->add(FfbMatchroundPeer::MATCHROUND_CREDITS, $this->matchround_credits);
		if ($this->isColumnModified(FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM)) $criteria->add(FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM, $this->matchround_max_players_from_team);

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
		$criteria = new Criteria(FfbMatchroundPeer::DATABASE_NAME);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $this->matchround_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getMatchroundId();
	}

	/**
	 * Generic method to set the primary key (matchround_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setMatchroundId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getMatchroundId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbMatchround (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setMatchroundGameId($this->matchround_game_id);
		$copyObj->setMatchroundTitle($this->matchround_title);
		$copyObj->setMatchroundStartdate($this->matchround_startdate);
		$copyObj->setMatchroundEnddate($this->matchround_enddate);
		$copyObj->setMatchroundStatus($this->matchround_status);
		$copyObj->setMatchroundCredits($this->matchround_credits);
		$copyObj->setMatchroundMaxPlayersFromTeam($this->matchround_max_players_from_team);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbCommentss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbComments($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerprices() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerprice($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbMatchs() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbMatch($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerstatss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerstats($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteams() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteam($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setMatchroundId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbMatchround Clone of current object.
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
	 * @return     FfbMatchroundPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbMatchroundPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     FfbMatchround The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(?FfbGame $v = null)
	{
		if ($v === null) {
			$this->setMatchroundGameId(NULL);
		} else {
			$this->setMatchroundGameId($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbMatchround($this);
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
	public function getFfbGame(?PropelPDO $con = null)
	{
		if ($this->aFfbGame === null && ($this->matchround_game_id !== null)) {
			$this->aFfbGame = FfbGameQuery::create()->findPk($this->matchround_game_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbGame->addFfbMatchrounds($this);
			 */
		}
		return $this->aFfbGame;
	}

	/**
	 * Clears out the collFfbCommentss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbCommentss()
	 */
	public function clearFfbCommentss()
	{
		$this->collFfbCommentss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbCommentss collection.
	 *
	 * By default this just sets the collFfbCommentss collection to an empty array (like clearcollFfbCommentss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbCommentss()
	{
		$this->collFfbCommentss = new PropelObjectCollection();
		$this->collFfbCommentss->setModel('FfbComments');
	}

	/**
	 * Gets an array of FfbComments objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatchround is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 * @throws     PropelException
	 */
	public function getFfbCommentss($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbCommentss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbCommentss) {
				// return empty collection
				$this->initFfbCommentss();
			} else {
				$collFfbCommentss = FfbCommentsQuery::create(null, $criteria)
					->filterByFfbMatchround($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbCommentss;
				}
				$this->collFfbCommentss = $collFfbCommentss;
			}
		}
		return $this->collFfbCommentss;
	}

	/**
	 * Returns the number of related FfbComments objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbComments objects.
	 * @throws     PropelException
	 */
	public function countFfbCommentss(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbCommentss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbCommentss) {
				return 0;
			} else {
				$query = FfbCommentsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatchround($this)
					->count($con);
			}
		} else {
			return count($this->collFfbCommentss);
		}
	}

	/**
	 * Method called to associate a FfbComments object to this object
	 * through the FfbComments foreign key attribute.
	 *
	 * @param      FfbComments $l FfbComments
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbComments(FfbComments $l)
	{
		if ($this->collFfbCommentss === null) {
			$this->initFfbCommentss();
		}
		if (!$this->collFfbCommentss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbCommentss[]= $l;
			$l->setFfbMatchround($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 */
	public function getFfbCommentssJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbCommentsQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbCommentss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 */
	public function getFfbCommentssJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbCommentsQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getFfbCommentss($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerprices collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerprices()
	 */
	public function clearFfbPlayerprices()
	{
		$this->collFfbPlayerprices = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerprices collection.
	 *
	 * By default this just sets the collFfbPlayerprices collection to an empty array (like clearcollFfbPlayerprices());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerprices()
	{
		$this->collFfbPlayerprices = new PropelObjectCollection();
		$this->collFfbPlayerprices->setModel('FfbPlayerprice');
	}

	/**
	 * Gets an array of FfbPlayerprice objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatchround is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerprice[] List of FfbPlayerprice objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerprices($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerprices || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerprices) {
				// return empty collection
				$this->initFfbPlayerprices();
			} else {
				$collFfbPlayerprices = FfbPlayerpriceQuery::create(null, $criteria)
					->filterByFfbMatchround($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerprices;
				}
				$this->collFfbPlayerprices = $collFfbPlayerprices;
			}
		}
		return $this->collFfbPlayerprices;
	}

	/**
	 * Returns the number of related FfbPlayerprice objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerprice objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerprices(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerprices || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerprices) {
				return 0;
			} else {
				$query = FfbPlayerpriceQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatchround($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerprices);
		}
	}

	/**
	 * Method called to associate a FfbPlayerprice object to this object
	 * through the FfbPlayerprice foreign key attribute.
	 *
	 * @param      FfbPlayerprice $l FfbPlayerprice
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerprice(FfbPlayerprice $l)
	{
		if ($this->collFfbPlayerprices === null) {
			$this->initFfbPlayerprices();
		}
		if (!$this->collFfbPlayerprices->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerprices[]= $l;
			$l->setFfbMatchround($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbPlayerprices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerprice[] List of FfbPlayerprice objects
	 */
	public function getFfbPlayerpricesJoinFfbPlayerteam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerpriceQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteam', $join_behavior);

		return $this->getFfbPlayerprices($query, $con);
	}

	/**
	 * Clears out the collFfbMatchs collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbMatchs()
	 */
	public function clearFfbMatchs()
	{
		$this->collFfbMatchs = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbMatchs collection.
	 *
	 * By default this just sets the collFfbMatchs collection to an empty array (like clearcollFfbMatchs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbMatchs()
	{
		$this->collFfbMatchs = new PropelObjectCollection();
		$this->collFfbMatchs->setModel('FfbMatch');
	}

	/**
	 * Gets an array of FfbMatch objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatchround is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 * @throws     PropelException
	 */
	public function getFfbMatchs($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchs || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchs) {
				// return empty collection
				$this->initFfbMatchs();
			} else {
				$collFfbMatchs = FfbMatchQuery::create(null, $criteria)
					->filterByFfbMatchround($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbMatchs;
				}
				$this->collFfbMatchs = $collFfbMatchs;
			}
		}
		return $this->collFfbMatchs;
	}

	/**
	 * Returns the number of related FfbMatch objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbMatch objects.
	 * @throws     PropelException
	 */
	public function countFfbMatchs(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchs || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchs) {
				return 0;
			} else {
				$query = FfbMatchQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatchround($this)
					->count($con);
			}
		} else {
			return count($this->collFfbMatchs);
		}
	}

	/**
	 * Method called to associate a FfbMatch object to this object
	 * through the FfbMatch foreign key attribute.
	 *
	 * @param      FfbMatch $l FfbMatch
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbMatch(FfbMatch $l)
	{
		if ($this->collFfbMatchs === null) {
			$this->initFfbMatchs();
		}
		if (!$this->collFfbMatchs->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbMatchs[]= $l;
			$l->setFfbMatchround($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbMatchs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 */
	public function getFfbMatchsJoinFfbTeamRelatedByMatchHometeamId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbMatchQuery::create(null, $criteria);
		$query->joinWith('FfbTeamRelatedByMatchHometeamId', $join_behavior);

		return $this->getFfbMatchs($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbMatchs from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 */
	public function getFfbMatchsJoinFfbTeamRelatedByMatchGuestteamId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbMatchQuery::create(null, $criteria);
		$query->joinWith('FfbTeamRelatedByMatchGuestteamId', $join_behavior);

		return $this->getFfbMatchs($query, $con);
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
	 * If this FfbMatchround is new, it will return
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
					->filterByFfbMatchround($this)
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
					->filterByFfbMatchround($this)
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
			$l->setFfbMatchround($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
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
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 */
	public function getFfbPlayerstatssJoinFfbMatch($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerstatsQuery::create(null, $criteria);
		$query->joinWith('FfbMatch', $join_behavior);

		return $this->getFfbPlayerstatss($query, $con);
	}

	/**
	 * Clears out the collFfbUserteams collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteams()
	 */
	public function clearFfbUserteams()
	{
		$this->collFfbUserteams = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteams collection.
	 *
	 * By default this just sets the collFfbUserteams collection to an empty array (like clearcollFfbUserteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteams()
	{
		$this->collFfbUserteams = new PropelObjectCollection();
		$this->collFfbUserteams->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbMatchround is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteams($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteams) {
				// return empty collection
				$this->initFfbUserteams();
			} else {
				$collFfbUserteams = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbMatchround($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteams;
				}
				$this->collFfbUserteams = $collFfbUserteams;
			}
		}
		return $this->collFfbUserteams;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteams(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteams) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbMatchround($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteams);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteam(FfbUserteam $l)
	{
		if ($this->collFfbUserteams === null) {
			$this->initFfbUserteams();
		}
		if (!$this->collFfbUserteams->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteams[]= $l;
			$l->setFfbMatchround($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId1($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId1', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId2($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId2', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId3($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId3', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId4($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId4', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId5($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId5', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId6($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId6', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId7($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId7', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId8($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId8', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId9($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId9', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId10($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId10', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbMatchround is new, it will return
	 * an empty collection; or if this FfbMatchround has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbMatchround.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId11($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId11', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->matchround_id = null;
		$this->matchround_game_id = null;
		$this->matchround_title = null;
		$this->matchround_startdate = null;
		$this->matchround_enddate = null;
		$this->matchround_status = null;
		$this->matchround_credits = null;
		$this->matchround_max_players_from_team = null;
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
			if ($this->collFfbCommentss) {
				foreach ((array) $this->collFfbCommentss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerprices) {
				foreach ((array) $this->collFfbPlayerprices as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbMatchs) {
				foreach ((array) $this->collFfbMatchs as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerstatss) {
				foreach ((array) $this->collFfbPlayerstatss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteams) {
				foreach ((array) $this->collFfbUserteams as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collFfbCommentss = null;
		$this->collFfbPlayerprices = null;
		$this->collFfbMatchs = null;
		$this->collFfbPlayerstatss = null;
		$this->collFfbUserteams = null;
		$this->aFfbGame = null;
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

} // BaseFfbMatchround
