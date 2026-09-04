<?php

/**
 * Base class that represents a row from the 'ffb_poll' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPoll extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPollPeer
	 */
	protected static $peer;

	/**
	 * The value for the poll_id field.
	 * @var        int
	 */
	protected $poll_id;

	/**
	 * The value for the poll_title field.
	 * @var        string
	 */
	protected $poll_title;

	/**
	 * The value for the poll_start field.
	 * @var        string
	 */
	protected $poll_start;

	/**
	 * The value for the poll_end field.
	 * @var        string
	 */
	protected $poll_end;

	/**
	 * The value for the poll_game_id field.
	 * @var        int
	 */
	protected $poll_game_id;

	/**
	 * The value for the poll_location field.
	 * @var        string
	 */
	protected $poll_location;

	/**
	 * The value for the poll_type field.
	 * @var        string
	 */
	protected $poll_type;

	/**
	 * The value for the poll_visible field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $poll_visible;

	/**
	 * @var        FfbGame
	 */
	protected $aFfbGame;

	/**
	 * @var        array FfbPollResult[] Collection to store aggregation of FfbPollResult objects.
	 */
	protected $collFfbPollResults;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPollResults.
	 */
	private $lastFfbPollResultCriteria = null;

	/**
	 * @var        array FfbPollAnswer[] Collection to store aggregation of FfbPollAnswer objects.
	 */
	protected $collFfbPollAnswers;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPollAnswers.
	 */
	private $lastFfbPollAnswerCriteria = null;

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
		$this->poll_visible = true;
	}

	/**
	 * Initializes internal state of BaseFfbPoll object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [poll_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollId()
	{
		return $this->poll_id;
	}

	/**
	 * Get the [poll_title] column value.
	 * 
	 * @return     string
	 */
	public function getPollTitle()
	{
		return $this->poll_title;
	}

	/**
	 * Get the [optionally formatted] temporal [poll_start] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getPollStart($format = 'Y-m-d H:i:s')
	{
		if ($this->poll_start === null) {
			return null;
		}


		if ($this->poll_start === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->poll_start);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->poll_start, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [optionally formatted] temporal [poll_end] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getPollEnd($format = 'Y-m-d H:i:s')
	{
		if ($this->poll_end === null) {
			return null;
		}


		if ($this->poll_end === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->poll_end);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->poll_end, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [poll_game_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollGameId()
	{
		return $this->poll_game_id;
	}

	/**
	 * Get the [poll_location] column value.
	 * 
	 * @return     string
	 */
	public function getPollLocation()
	{
		return $this->poll_location;
	}

	/**
	 * Get the [poll_type] column value.
	 * 
	 * @return     string
	 */
	public function getPollType()
	{
		return $this->poll_type;
	}

	/**
	 * Get the [poll_visible] column value.
	 * 
	 * @return     boolean
	 */
	public function getPollVisible()
	{
		return $this->poll_visible;
	}

	/**
	 * Set the value of [poll_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_id !== $v) {
			$this->poll_id = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_ID;
		}

		return $this;
	} // setPollId()

	/**
	 * Set the value of [poll_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->poll_title !== $v) {
			$this->poll_title = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_TITLE;
		}

		return $this;
	} // setPollTitle()

	/**
	 * Sets the value of [poll_start] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollStart($v)
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

		if ( $this->poll_start !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->poll_start !== null && $tmpDt = new DateTime($this->poll_start)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->poll_start = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbPollPeer::POLL_START;
			}
		} // if either are not null

		return $this;
	} // setPollStart()

	/**
	 * Sets the value of [poll_end] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollEnd($v)
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

		if ( $this->poll_end !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->poll_end !== null && $tmpDt = new DateTime($this->poll_end)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->poll_end = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbPollPeer::POLL_END;
			}
		} // if either are not null

		return $this;
	} // setPollEnd()

	/**
	 * Set the value of [poll_game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_game_id !== $v) {
			$this->poll_game_id = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_GAME_ID;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setPollGameId()

	/**
	 * Set the value of [poll_location] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollLocation($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->poll_location !== $v) {
			$this->poll_location = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_LOCATION;
		}

		return $this;
	} // setPollLocation()

	/**
	 * Set the value of [poll_type] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollType($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->poll_type !== $v) {
			$this->poll_type = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_TYPE;
		}

		return $this;
	} // setPollType()

	/**
	 * Set the value of [poll_visible] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbPoll The current object (for fluent API support)
	 */
	public function setPollVisible($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->poll_visible !== $v || $this->isNew()) {
			$this->poll_visible = $v;
			$this->modifiedColumns[] = FfbPollPeer::POLL_VISIBLE;
		}

		return $this;
	} // setPollVisible()

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
			if ($this->poll_visible !== true) {
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

			$this->poll_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->poll_title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->poll_start = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->poll_end = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->poll_game_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->poll_location = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->poll_type = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->poll_visible = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = FfbPollPeer::NUM_COLUMNS - FfbPollPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPoll object", $e);
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

		if ($this->aFfbGame !== null && $this->poll_game_id !== $this->aFfbGame->getGameId()) {
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
			$con = Propel::getConnection(FfbPollPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPollPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbGame = null;
			$this->collFfbPollResults = null;
			$this->lastFfbPollResultCriteria = null;

			$this->collFfbPollAnswers = null;
			$this->lastFfbPollAnswerCriteria = null;

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
			$con = Propel::getConnection(FfbPollPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPollPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbPollPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPollPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = FfbPollPeer::POLL_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbPollPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setPollId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbPollPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collFfbPollResults !== null) {
				foreach ($this->collFfbPollResults as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPollAnswers !== null) {
				foreach ($this->collFfbPollAnswers as $referrerFK) {
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


			if (($retval = FfbPollPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbPollResults !== null) {
					foreach ($this->collFfbPollResults as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPollAnswers !== null) {
					foreach ($this->collFfbPollAnswers as $referrerFK) {
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
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPollPeer::POLL_ID)) $criteria->add(FfbPollPeer::POLL_ID, $this->poll_id);
		if ($this->isColumnModified(FfbPollPeer::POLL_TITLE)) $criteria->add(FfbPollPeer::POLL_TITLE, $this->poll_title);
		if ($this->isColumnModified(FfbPollPeer::POLL_START)) $criteria->add(FfbPollPeer::POLL_START, $this->poll_start);
		if ($this->isColumnModified(FfbPollPeer::POLL_END)) $criteria->add(FfbPollPeer::POLL_END, $this->poll_end);
		if ($this->isColumnModified(FfbPollPeer::POLL_GAME_ID)) $criteria->add(FfbPollPeer::POLL_GAME_ID, $this->poll_game_id);
		if ($this->isColumnModified(FfbPollPeer::POLL_LOCATION)) $criteria->add(FfbPollPeer::POLL_LOCATION, $this->poll_location);
		if ($this->isColumnModified(FfbPollPeer::POLL_TYPE)) $criteria->add(FfbPollPeer::POLL_TYPE, $this->poll_type);
		if ($this->isColumnModified(FfbPollPeer::POLL_VISIBLE)) $criteria->add(FfbPollPeer::POLL_VISIBLE, $this->poll_visible);

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
		$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);

		$criteria->add(FfbPollPeer::POLL_ID, $this->poll_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPollId();
	}

	/**
	 * Generic method to set the primary key (poll_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPollId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPoll (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPollTitle($this->poll_title);

		$copyObj->setPollStart($this->poll_start);

		$copyObj->setPollEnd($this->poll_end);

		$copyObj->setPollGameId($this->poll_game_id);

		$copyObj->setPollLocation($this->poll_location);

		$copyObj->setPollType($this->poll_type);

		$copyObj->setPollVisible($this->poll_visible);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbPollResults() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPollResult($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPollAnswers() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPollAnswer($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setPollId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbPoll Clone of current object.
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
	 * @return     FfbPollPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPollPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     FfbPoll The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(FfbGame $v = null)
	{
		if ($v === null) {
			$this->setPollGameId(NULL);
		} else {
			$this->setPollGameId($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPoll($this);
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
		if ($this->aFfbGame === null && ($this->poll_game_id !== null)) {
			$this->aFfbGame = FfbGamePeer::retrieveByPk($this->poll_game_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbGame->addFfbPolls($this);
			 */
		}
		return $this->aFfbGame;
	}

	/**
	 * Clears out the collFfbPollResults collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPollResults()
	 */
	public function clearFfbPollResults()
	{
		$this->collFfbPollResults = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPollResults collection (array).
	 *
	 * By default this just sets the collFfbPollResults collection to an empty array (like clearcollFfbPollResults());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPollResults()
	{
		$this->collFfbPollResults = array();
	}

	/**
	 * Gets an array of FfbPollResult objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbPoll has previously been saved, it will retrieve
	 * related FfbPollResults from storage. If this FfbPoll is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbPollResult[]
	 * @throws     PropelException
	 */
	public function getFfbPollResults($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
			   $this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				FfbPollResultPeer::addSelectColumns($criteria);
				$this->collFfbPollResults = FfbPollResultPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				FfbPollResultPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
					$this->collFfbPollResults = FfbPollResultPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;
		return $this->collFfbPollResults;
	}

	/**
	 * Returns the number of related FfbPollResult objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPollResult objects.
	 * @throws     PropelException
	 */
	public function countFfbPollResults(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				$count = FfbPollResultPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
					$count = FfbPollResultPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbPollResults);
				}
			} else {
				$count = count($this->collFfbPollResults);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbPollResult object to this object
	 * through the FfbPollResult foreign key attribute.
	 *
	 * @param      FfbPollResult $l FfbPollResult
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPollResult(FfbPollResult $l)
	{
		if ($this->collFfbPollResults === null) {
			$this->initFfbPollResults();
		}
		if (!in_array($l, $this->collFfbPollResults, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbPollResults, $l);
			$l->setFfbPoll($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPoll is new, it will return
	 * an empty collection; or if this FfbPoll has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPoll.
	 */
	public function getFfbPollResultsJoinFfbPollAnswer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPollAnswer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPollAnswer($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPoll is new, it will return
	 * an empty collection; or if this FfbPoll has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPoll.
	 */
	public function getFfbPollResultsJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
	}

	/**
	 * Clears out the collFfbPollAnswers collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPollAnswers()
	 */
	public function clearFfbPollAnswers()
	{
		$this->collFfbPollAnswers = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPollAnswers collection (array).
	 *
	 * By default this just sets the collFfbPollAnswers collection to an empty array (like clearcollFfbPollAnswers());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPollAnswers()
	{
		$this->collFfbPollAnswers = array();
	}

	/**
	 * Gets an array of FfbPollAnswer objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbPoll has previously been saved, it will retrieve
	 * related FfbPollAnswers from storage. If this FfbPoll is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbPollAnswer[]
	 * @throws     PropelException
	 */
	public function getFfbPollAnswers($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollAnswers === null) {
			if ($this->isNew()) {
			   $this->collFfbPollAnswers = array();
			} else {

				$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $this->poll_id);

				FfbPollAnswerPeer::addSelectColumns($criteria);
				$this->collFfbPollAnswers = FfbPollAnswerPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $this->poll_id);

				FfbPollAnswerPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbPollAnswerCriteria) || !$this->lastFfbPollAnswerCriteria->equals($criteria)) {
					$this->collFfbPollAnswers = FfbPollAnswerPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbPollAnswerCriteria = $criteria;
		return $this->collFfbPollAnswers;
	}

	/**
	 * Returns the number of related FfbPollAnswer objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPollAnswer objects.
	 * @throws     PropelException
	 */
	public function countFfbPollAnswers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbPollAnswers === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $this->poll_id);

				$count = FfbPollAnswerPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $this->poll_id);

				if (!isset($this->lastFfbPollAnswerCriteria) || !$this->lastFfbPollAnswerCriteria->equals($criteria)) {
					$count = FfbPollAnswerPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbPollAnswers);
				}
			} else {
				$count = count($this->collFfbPollAnswers);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbPollAnswer object to this object
	 * through the FfbPollAnswer foreign key attribute.
	 *
	 * @param      FfbPollAnswer $l FfbPollAnswer
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPollAnswer(FfbPollAnswer $l)
	{
		if ($this->collFfbPollAnswers === null) {
			$this->initFfbPollAnswers();
		}
		if (!in_array($l, $this->collFfbPollAnswers, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbPollAnswers, $l);
			$l->setFfbPoll($this);
		}
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
			if ($this->collFfbPollResults) {
				foreach ((array) $this->collFfbPollResults as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPollAnswers) {
				foreach ((array) $this->collFfbPollAnswers as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collFfbPollResults = null;
		$this->collFfbPollAnswers = null;
			$this->aFfbGame = null;
	}

} // BaseFfbPoll
