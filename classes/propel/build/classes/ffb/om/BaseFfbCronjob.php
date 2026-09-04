<?php


/**
 * Base class that represents a row from the 'ffb_cronjob' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbCronjob extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbCronjobPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbCronjobPeer
	 */
	protected static $peer;

	/**
	 * The value for the cronjob_id field.
	 * @var        int
	 */
	protected $cronjob_id;

	/**
	 * The value for the cronjob_description field.
	 * @var        string
	 */
	protected $cronjob_description;

	/**
	 * The value for the cronjob_function field.
	 * @var        string
	 */
	protected $cronjob_function;

	/**
	 * The value for the cronjob_time_start field.
	 * @var        string
	 */
	protected $cronjob_time_start;

	/**
	 * The value for the cronjob_time_end field.
	 * @var        string
	 */
	protected $cronjob_time_end;

	/**
	 * The value for the cronjob_time_lastrun field.
	 * @var        string
	 */
	protected $cronjob_time_lastrun;

	/**
	 * The value for the cronjob_status field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $cronjob_status;

	/**
	 * The value for the cronjob_interval_hours field.
	 * Note: this column has a database default value of: 24
	 * @var        int
	 */
	protected $cronjob_interval_hours;

	/**
	 * The value for the cronjob_runonce field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $cronjob_runonce;

	/**
	 * The value for the cronjob_runhour field.
	 * Note: this column has a database default value of: 5
	 * @var        int
	 */
	protected $cronjob_runhour;

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
		$this->cronjob_status = true;
		$this->cronjob_interval_hours = 24;
		$this->cronjob_runonce = false;
		$this->cronjob_runhour = 5;
	}

	/**
	 * Initializes internal state of BaseFfbCronjob object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [cronjob_id] column value.
	 * 
	 * @return     int
	 */
	public function getCronjobId()
	{
		return $this->cronjob_id;
	}

	/**
	 * Get the [cronjob_description] column value.
	 * 
	 * @return     string
	 */
	public function getCronjobDescription()
	{
		return $this->cronjob_description;
	}

	/**
	 * Get the [cronjob_function] column value.
	 * 
	 * @return     string
	 */
	public function getCronjobFunction()
	{
		return $this->cronjob_function;
	}

	/**
	 * Get the [optionally formatted] temporal [cronjob_time_start] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCronjobTimeStart($format = 'Y-m-d H:i:s')
	{
		if ($this->cronjob_time_start === null) {
			return null;
		}


		if ($this->cronjob_time_start === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->cronjob_time_start);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->cronjob_time_start, true), $x);
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
	 * Get the [optionally formatted] temporal [cronjob_time_end] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCronjobTimeEnd($format = 'Y-m-d H:i:s')
	{
		if ($this->cronjob_time_end === null) {
			return null;
		}


		if ($this->cronjob_time_end === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->cronjob_time_end);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->cronjob_time_end, true), $x);
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
	 * Get the [optionally formatted] temporal [cronjob_time_lastrun] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCronjobTimeLastrun($format = 'Y-m-d H:i:s')
	{
		if ($this->cronjob_time_lastrun === null) {
			return null;
		}


		if ($this->cronjob_time_lastrun === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->cronjob_time_lastrun);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->cronjob_time_lastrun, true), $x);
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
	 * Get the [cronjob_status] column value.
	 * 
	 * @return     boolean
	 */
	public function getCronjobStatus()
	{
		return $this->cronjob_status;
	}

	/**
	 * Get the [cronjob_interval_hours] column value.
	 * 
	 * @return     int
	 */
	public function getCronjobIntervalHours()
	{
		return $this->cronjob_interval_hours;
	}

	/**
	 * Get the [cronjob_runonce] column value.
	 * 
	 * @return     boolean
	 */
	public function getCronjobRunonce()
	{
		return $this->cronjob_runonce;
	}

	/**
	 * Get the [cronjob_runhour] column value.
	 * 
	 * @return     int
	 */
	public function getCronjobRunhour()
	{
		return $this->cronjob_runhour;
	}

	/**
	 * Set the value of [cronjob_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->cronjob_id !== $v) {
			$this->cronjob_id = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_ID;
		}

		return $this;
	} // setCronjobId()

	/**
	 * Set the value of [cronjob_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->cronjob_description !== $v) {
			$this->cronjob_description = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_DESCRIPTION;
		}

		return $this;
	} // setCronjobDescription()

	/**
	 * Set the value of [cronjob_function] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobFunction($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->cronjob_function !== $v) {
			$this->cronjob_function = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_FUNCTION;
		}

		return $this;
	} // setCronjobFunction()

	/**
	 * Sets the value of [cronjob_time_start] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobTimeStart($v)
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

		if ( $this->cronjob_time_start !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->cronjob_time_start !== null && $tmpDt = new DateTime($this->cronjob_time_start)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->cronjob_time_start = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_TIME_START;
			}
		} // if either are not null

		return $this;
	} // setCronjobTimeStart()

	/**
	 * Sets the value of [cronjob_time_end] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobTimeEnd($v)
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

		if ( $this->cronjob_time_end !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->cronjob_time_end !== null && $tmpDt = new DateTime($this->cronjob_time_end)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->cronjob_time_end = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_TIME_END;
			}
		} // if either are not null

		return $this;
	} // setCronjobTimeEnd()

	/**
	 * Sets the value of [cronjob_time_lastrun] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobTimeLastrun($v)
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

		if ( $this->cronjob_time_lastrun !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->cronjob_time_lastrun !== null && $tmpDt = new DateTime($this->cronjob_time_lastrun)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->cronjob_time_lastrun = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_TIME_LASTRUN;
			}
		} // if either are not null

		return $this;
	} // setCronjobTimeLastrun()

	/**
	 * Set the value of [cronjob_status] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobStatus($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->cronjob_status !== $v || $this->isNew()) {
			$this->cronjob_status = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_STATUS;
		}

		return $this;
	} // setCronjobStatus()

	/**
	 * Set the value of [cronjob_interval_hours] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobIntervalHours($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->cronjob_interval_hours !== $v || $this->isNew()) {
			$this->cronjob_interval_hours = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_INTERVAL_HOURS;
		}

		return $this;
	} // setCronjobIntervalHours()

	/**
	 * Set the value of [cronjob_runonce] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobRunonce($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->cronjob_runonce !== $v || $this->isNew()) {
			$this->cronjob_runonce = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_RUNONCE;
		}

		return $this;
	} // setCronjobRunonce()

	/**
	 * Set the value of [cronjob_runhour] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbCronjob The current object (for fluent API support)
	 */
	public function setCronjobRunhour($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->cronjob_runhour !== $v || $this->isNew()) {
			$this->cronjob_runhour = $v;
			$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_RUNHOUR;
		}

		return $this;
	} // setCronjobRunhour()

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
			if ($this->cronjob_status !== true) {
				return false;
			}

			if ($this->cronjob_interval_hours !== 24) {
				return false;
			}

			if ($this->cronjob_runonce !== false) {
				return false;
			}

			if ($this->cronjob_runhour !== 5) {
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

			$this->cronjob_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->cronjob_description = (($row[$startcol + 1] ?? null) !== null) ? (string) $row[$startcol + 1] : null;
			$this->cronjob_function = (($row[$startcol + 2] ?? null) !== null) ? (string) $row[$startcol + 2] : null;
			$this->cronjob_time_start = (($row[$startcol + 3] ?? null) !== null) ? (string) $row[$startcol + 3] : null;
			$this->cronjob_time_end = (($row[$startcol + 4] ?? null) !== null) ? (string) $row[$startcol + 4] : null;
			$this->cronjob_time_lastrun = (($row[$startcol + 5] ?? null) !== null) ? (string) $row[$startcol + 5] : null;
			$this->cronjob_status = (($row[$startcol + 6] ?? null) !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->cronjob_interval_hours = (($row[$startcol + 7] ?? null) !== null) ? (int) $row[$startcol + 7] : null;
			$this->cronjob_runonce = (($row[$startcol + 8] ?? null) !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->cronjob_runhour = (($row[$startcol + 9] ?? null) !== null) ? (int) $row[$startcol + 9] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 10; // 10 = FfbCronjobPeer::NUM_COLUMNS - FfbCronjobPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbCronjob object", $e);
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
			$con = Propel::getConnection(FfbCronjobPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbCronjobPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

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
			$con = Propel::getConnection(FfbCronjobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbCronjobQuery::create()
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
			$con = Propel::getConnection(FfbCronjobPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbCronjobPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbCronjobPeer::CRONJOB_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbCronjobPeer::CRONJOB_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbCronjobPeer::CRONJOB_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setCronjobId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = FfbCronjobPeer::doUpdate($this, $con);
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


			if (($retval = FfbCronjobPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
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
		$pos = FfbCronjobPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getCronjobId();
				break;
			case 1:
				return $this->getCronjobDescription();
				break;
			case 2:
				return $this->getCronjobFunction();
				break;
			case 3:
				return $this->getCronjobTimeStart();
				break;
			case 4:
				return $this->getCronjobTimeEnd();
				break;
			case 5:
				return $this->getCronjobTimeLastrun();
				break;
			case 6:
				return $this->getCronjobStatus();
				break;
			case 7:
				return $this->getCronjobIntervalHours();
				break;
			case 8:
				return $this->getCronjobRunonce();
				break;
			case 9:
				return $this->getCronjobRunhour();
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
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = FfbCronjobPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getCronjobId(),
			$keys[1] => $this->getCronjobDescription(),
			$keys[2] => $this->getCronjobFunction(),
			$keys[3] => $this->getCronjobTimeStart(),
			$keys[4] => $this->getCronjobTimeEnd(),
			$keys[5] => $this->getCronjobTimeLastrun(),
			$keys[6] => $this->getCronjobStatus(),
			$keys[7] => $this->getCronjobIntervalHours(),
			$keys[8] => $this->getCronjobRunonce(),
			$keys[9] => $this->getCronjobRunhour(),
		);
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
		$pos = FfbCronjobPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setCronjobId($value);
				break;
			case 1:
				$this->setCronjobDescription($value);
				break;
			case 2:
				$this->setCronjobFunction($value);
				break;
			case 3:
				$this->setCronjobTimeStart($value);
				break;
			case 4:
				$this->setCronjobTimeEnd($value);
				break;
			case 5:
				$this->setCronjobTimeLastrun($value);
				break;
			case 6:
				$this->setCronjobStatus($value);
				break;
			case 7:
				$this->setCronjobIntervalHours($value);
				break;
			case 8:
				$this->setCronjobRunonce($value);
				break;
			case 9:
				$this->setCronjobRunhour($value);
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
		$keys = FfbCronjobPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setCronjobId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCronjobDescription($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setCronjobFunction($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCronjobTimeStart($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setCronjobTimeEnd($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setCronjobTimeLastrun($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setCronjobStatus($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setCronjobIntervalHours($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCronjobRunonce($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setCronjobRunhour($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbCronjobPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_ID)) $criteria->add(FfbCronjobPeer::CRONJOB_ID, $this->cronjob_id);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_DESCRIPTION)) $criteria->add(FfbCronjobPeer::CRONJOB_DESCRIPTION, $this->cronjob_description);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_FUNCTION)) $criteria->add(FfbCronjobPeer::CRONJOB_FUNCTION, $this->cronjob_function);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_TIME_START)) $criteria->add(FfbCronjobPeer::CRONJOB_TIME_START, $this->cronjob_time_start);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_TIME_END)) $criteria->add(FfbCronjobPeer::CRONJOB_TIME_END, $this->cronjob_time_end);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_TIME_LASTRUN)) $criteria->add(FfbCronjobPeer::CRONJOB_TIME_LASTRUN, $this->cronjob_time_lastrun);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_STATUS)) $criteria->add(FfbCronjobPeer::CRONJOB_STATUS, $this->cronjob_status);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_INTERVAL_HOURS)) $criteria->add(FfbCronjobPeer::CRONJOB_INTERVAL_HOURS, $this->cronjob_interval_hours);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_RUNONCE)) $criteria->add(FfbCronjobPeer::CRONJOB_RUNONCE, $this->cronjob_runonce);
		if ($this->isColumnModified(FfbCronjobPeer::CRONJOB_RUNHOUR)) $criteria->add(FfbCronjobPeer::CRONJOB_RUNHOUR, $this->cronjob_runhour);

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
		$criteria = new Criteria(FfbCronjobPeer::DATABASE_NAME);
		$criteria->add(FfbCronjobPeer::CRONJOB_ID, $this->cronjob_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getCronjobId();
	}

	/**
	 * Generic method to set the primary key (cronjob_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setCronjobId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getCronjobId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbCronjob (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setCronjobDescription($this->cronjob_description);
		$copyObj->setCronjobFunction($this->cronjob_function);
		$copyObj->setCronjobTimeStart($this->cronjob_time_start);
		$copyObj->setCronjobTimeEnd($this->cronjob_time_end);
		$copyObj->setCronjobTimeLastrun($this->cronjob_time_lastrun);
		$copyObj->setCronjobStatus($this->cronjob_status);
		$copyObj->setCronjobIntervalHours($this->cronjob_interval_hours);
		$copyObj->setCronjobRunonce($this->cronjob_runonce);
		$copyObj->setCronjobRunhour($this->cronjob_runhour);

		$copyObj->setNew(true);
		$copyObj->setCronjobId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbCronjob Clone of current object.
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
	 * @return     FfbCronjobPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbCronjobPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->cronjob_id = null;
		$this->cronjob_description = null;
		$this->cronjob_function = null;
		$this->cronjob_time_start = null;
		$this->cronjob_time_end = null;
		$this->cronjob_time_lastrun = null;
		$this->cronjob_status = null;
		$this->cronjob_interval_hours = null;
		$this->cronjob_runonce = null;
		$this->cronjob_runhour = null;
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
		} // if ($deep)

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

} // BaseFfbCronjob
