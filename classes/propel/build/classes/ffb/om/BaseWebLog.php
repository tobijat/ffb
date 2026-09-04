<?php


/**
 * Base class that represents a row from the 'web_log' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebLog extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'WebLogPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WebLogPeer
	 */
	protected static $peer;

	/**
	 * The value for the log_id field.
	 * @var        int
	 */
	protected $log_id;

	/**
	 * The value for the log_user_id field.
	 * @var        int
	 */
	protected $log_user_id;

	/**
	 * The value for the log_user_nickname field.
	 * @var        string
	 */
	protected $log_user_nickname;

	/**
	 * The value for the log_user_ip field.
	 * @var        string
	 */
	protected $log_user_ip;

	/**
	 * The value for the log_module field.
	 * @var        string
	 */
	protected $log_module;

	/**
	 * The value for the log_class field.
	 * @var        string
	 */
	protected $log_class;

	/**
	 * The value for the log_event field.
	 * @var        string
	 */
	protected $log_event;

	/**
	 * The value for the log_presenter field.
	 * @var        string
	 */
	protected $log_presenter;

	/**
	 * The value for the log_subdomain field.
	 * @var        string
	 */
	protected $log_subdomain;

	/**
	 * The value for the log_date field.
	 * @var        string
	 */
	protected $log_date;

	/**
	 * @var        WebUser
	 */
	protected $aWebUser;

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
	 * Get the [log_id] column value.
	 * 
	 * @return     int
	 */
	public function getLogId()
	{
		return $this->log_id;
	}

	/**
	 * Get the [log_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getLogUserId()
	{
		return $this->log_user_id;
	}

	/**
	 * Get the [log_user_nickname] column value.
	 * 
	 * @return     string
	 */
	public function getLogUserNickname()
	{
		return $this->log_user_nickname;
	}

	/**
	 * Get the [log_user_ip] column value.
	 * 
	 * @return     string
	 */
	public function getLogUserIp()
	{
		return $this->log_user_ip;
	}

	/**
	 * Get the [log_module] column value.
	 * 
	 * @return     string
	 */
	public function getLogModule()
	{
		return $this->log_module;
	}

	/**
	 * Get the [log_class] column value.
	 * 
	 * @return     string
	 */
	public function getLogClass()
	{
		return $this->log_class;
	}

	/**
	 * Get the [log_event] column value.
	 * 
	 * @return     string
	 */
	public function getLogEvent()
	{
		return $this->log_event;
	}

	/**
	 * Get the [log_presenter] column value.
	 * 
	 * @return     string
	 */
	public function getLogPresenter()
	{
		return $this->log_presenter;
	}

	/**
	 * Get the [log_subdomain] column value.
	 * 
	 * @return     string
	 */
	public function getLogSubdomain()
	{
		return $this->log_subdomain;
	}

	/**
	 * Get the [optionally formatted] temporal [log_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getLogDate($format = 'Y-m-d H:i:s')
	{
		if ($this->log_date === null) {
			return null;
		}


		if ($this->log_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->log_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->log_date, true), $x);
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
	 * Set the value of [log_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->log_id !== $v) {
			$this->log_id = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_ID;
		}

		return $this;
	} // setLogId()

	/**
	 * Set the value of [log_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->log_user_id !== $v) {
			$this->log_user_id = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setLogUserId()

	/**
	 * Set the value of [log_user_nickname] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogUserNickname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_user_nickname !== $v) {
			$this->log_user_nickname = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_USER_NICKNAME;
		}

		return $this;
	} // setLogUserNickname()

	/**
	 * Set the value of [log_user_ip] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogUserIp($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_user_ip !== $v) {
			$this->log_user_ip = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_USER_IP;
		}

		return $this;
	} // setLogUserIp()

	/**
	 * Set the value of [log_module] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogModule($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_module !== $v) {
			$this->log_module = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_MODULE;
		}

		return $this;
	} // setLogModule()

	/**
	 * Set the value of [log_class] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogClass($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_class !== $v) {
			$this->log_class = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_CLASS;
		}

		return $this;
	} // setLogClass()

	/**
	 * Set the value of [log_event] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogEvent($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_event !== $v) {
			$this->log_event = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_EVENT;
		}

		return $this;
	} // setLogEvent()

	/**
	 * Set the value of [log_presenter] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogPresenter($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_presenter !== $v) {
			$this->log_presenter = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_PRESENTER;
		}

		return $this;
	} // setLogPresenter()

	/**
	 * Set the value of [log_subdomain] column.
	 * 
	 * @param      string $v new value
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogSubdomain($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->log_subdomain !== $v) {
			$this->log_subdomain = $v;
			$this->modifiedColumns[] = WebLogPeer::LOG_SUBDOMAIN;
		}

		return $this;
	} // setLogSubdomain()

	/**
	 * Sets the value of [log_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebLog The current object (for fluent API support)
	 */
	public function setLogDate($v)
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

		if ( $this->log_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->log_date !== null && $tmpDt = new DateTime($this->log_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->log_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebLogPeer::LOG_DATE;
			}
		} // if either are not null

		return $this;
	} // setLogDate()

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

			$this->log_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->log_user_id = (($row[$startcol + 1] ?? null) !== null) ? (int) $row[$startcol + 1] : null;
			$this->log_user_nickname = (($row[$startcol + 2] ?? null) !== null) ? (string) $row[$startcol + 2] : null;
			$this->log_user_ip = (($row[$startcol + 3] ?? null) !== null) ? (string) $row[$startcol + 3] : null;
			$this->log_module = (($row[$startcol + 4] ?? null) !== null) ? (string) $row[$startcol + 4] : null;
			$this->log_class = (($row[$startcol + 5] ?? null) !== null) ? (string) $row[$startcol + 5] : null;
			$this->log_event = (($row[$startcol + 6] ?? null) !== null) ? (string) $row[$startcol + 6] : null;
			$this->log_presenter = (($row[$startcol + 7] ?? null) !== null) ? (string) $row[$startcol + 7] : null;
			$this->log_subdomain = (($row[$startcol + 8] ?? null) !== null) ? (string) $row[$startcol + 8] : null;
			$this->log_date = (($row[$startcol + 9] ?? null) !== null) ? (string) $row[$startcol + 9] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 10; // 10 = WebLogPeer::NUM_COLUMNS - WebLogPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WebLog object", $e);
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

		if ($this->aWebUser !== null && $this->log_user_id !== $this->aWebUser->getUserId()) {
			$this->aWebUser = null;
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
			$con = Propel::getConnection(WebLogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WebLogPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWebUser = null;
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
			$con = Propel::getConnection(WebLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				WebLogQuery::create()
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
			$con = Propel::getConnection(WebLogPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				WebLogPeer::addInstanceToPool($this);
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

			if ($this->aWebUser !== null) {
				if ($this->aWebUser->isModified() || $this->aWebUser->isNew()) {
					$affectedRows += $this->aWebUser->save($con);
				}
				$this->setWebUser($this->aWebUser);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = WebLogPeer::LOG_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(WebLogPeer::LOG_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.WebLogPeer::LOG_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setLogId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += WebLogPeer::doUpdate($this, $con);
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

			if ($this->aWebUser !== null) {
				if (!$this->aWebUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWebUser->getValidationFailures());
				}
			}


			if (($retval = WebLogPeer::doValidate($this, $columns)) !== true) {
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
		$pos = WebLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getLogId();
				break;
			case 1:
				return $this->getLogUserId();
				break;
			case 2:
				return $this->getLogUserNickname();
				break;
			case 3:
				return $this->getLogUserIp();
				break;
			case 4:
				return $this->getLogModule();
				break;
			case 5:
				return $this->getLogClass();
				break;
			case 6:
				return $this->getLogEvent();
				break;
			case 7:
				return $this->getLogPresenter();
				break;
			case 8:
				return $this->getLogSubdomain();
				break;
			case 9:
				return $this->getLogDate();
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
		$keys = WebLogPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getLogId(),
			$keys[1] => $this->getLogUserId(),
			$keys[2] => $this->getLogUserNickname(),
			$keys[3] => $this->getLogUserIp(),
			$keys[4] => $this->getLogModule(),
			$keys[5] => $this->getLogClass(),
			$keys[6] => $this->getLogEvent(),
			$keys[7] => $this->getLogPresenter(),
			$keys[8] => $this->getLogSubdomain(),
			$keys[9] => $this->getLogDate(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aWebUser) {
				$result['WebUser'] = $this->aWebUser->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = WebLogPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setLogId($value);
				break;
			case 1:
				$this->setLogUserId($value);
				break;
			case 2:
				$this->setLogUserNickname($value);
				break;
			case 3:
				$this->setLogUserIp($value);
				break;
			case 4:
				$this->setLogModule($value);
				break;
			case 5:
				$this->setLogClass($value);
				break;
			case 6:
				$this->setLogEvent($value);
				break;
			case 7:
				$this->setLogPresenter($value);
				break;
			case 8:
				$this->setLogSubdomain($value);
				break;
			case 9:
				$this->setLogDate($value);
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
		$keys = WebLogPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setLogId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setLogUserId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setLogUserNickname($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setLogUserIp($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setLogModule($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setLogClass($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setLogEvent($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setLogPresenter($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setLogSubdomain($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setLogDate($arr[$keys[9]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WebLogPeer::DATABASE_NAME);

		if ($this->isColumnModified(WebLogPeer::LOG_ID)) $criteria->add(WebLogPeer::LOG_ID, $this->log_id);
		if ($this->isColumnModified(WebLogPeer::LOG_USER_ID)) $criteria->add(WebLogPeer::LOG_USER_ID, $this->log_user_id);
		if ($this->isColumnModified(WebLogPeer::LOG_USER_NICKNAME)) $criteria->add(WebLogPeer::LOG_USER_NICKNAME, $this->log_user_nickname);
		if ($this->isColumnModified(WebLogPeer::LOG_USER_IP)) $criteria->add(WebLogPeer::LOG_USER_IP, $this->log_user_ip);
		if ($this->isColumnModified(WebLogPeer::LOG_MODULE)) $criteria->add(WebLogPeer::LOG_MODULE, $this->log_module);
		if ($this->isColumnModified(WebLogPeer::LOG_CLASS)) $criteria->add(WebLogPeer::LOG_CLASS, $this->log_class);
		if ($this->isColumnModified(WebLogPeer::LOG_EVENT)) $criteria->add(WebLogPeer::LOG_EVENT, $this->log_event);
		if ($this->isColumnModified(WebLogPeer::LOG_PRESENTER)) $criteria->add(WebLogPeer::LOG_PRESENTER, $this->log_presenter);
		if ($this->isColumnModified(WebLogPeer::LOG_SUBDOMAIN)) $criteria->add(WebLogPeer::LOG_SUBDOMAIN, $this->log_subdomain);
		if ($this->isColumnModified(WebLogPeer::LOG_DATE)) $criteria->add(WebLogPeer::LOG_DATE, $this->log_date);

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
		$criteria = new Criteria(WebLogPeer::DATABASE_NAME);
		$criteria->add(WebLogPeer::LOG_ID, $this->log_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getLogId();
	}

	/**
	 * Generic method to set the primary key (log_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setLogId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getLogId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of WebLog (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setLogUserId($this->log_user_id);
		$copyObj->setLogUserNickname($this->log_user_nickname);
		$copyObj->setLogUserIp($this->log_user_ip);
		$copyObj->setLogModule($this->log_module);
		$copyObj->setLogClass($this->log_class);
		$copyObj->setLogEvent($this->log_event);
		$copyObj->setLogPresenter($this->log_presenter);
		$copyObj->setLogSubdomain($this->log_subdomain);
		$copyObj->setLogDate($this->log_date);

		$copyObj->setNew(true);
		$copyObj->setLogId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     WebLog Clone of current object.
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
	 * @return     WebLogPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WebLogPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     WebLog The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUser(?WebUser $v = null)
	{
		if ($v === null) {
			$this->setLogUserId(NULL);
		} else {
			$this->setLogUserId($v->getUserId());
		}

		$this->aWebUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WebUser object, it will not be re-added.
		if ($v !== null) {
			$v->addWebLog($this);
		}

		return $this;
	}


	/**
	 * Get the associated WebUser object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     WebUser The associated WebUser object.
	 * @throws     PropelException
	 */
	public function getWebUser(?PropelPDO $con = null)
	{
		if ($this->aWebUser === null && ($this->log_user_id !== null)) {
			$this->aWebUser = WebUserQuery::create()->findPk($this->log_user_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aWebUser->addWebLogs($this);
			 */
		}
		return $this->aWebUser;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->log_id = null;
		$this->log_user_id = null;
		$this->log_user_nickname = null;
		$this->log_user_ip = null;
		$this->log_module = null;
		$this->log_class = null;
		$this->log_event = null;
		$this->log_presenter = null;
		$this->log_subdomain = null;
		$this->log_date = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
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

		$this->aWebUser = null;
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

} // BaseWebLog
