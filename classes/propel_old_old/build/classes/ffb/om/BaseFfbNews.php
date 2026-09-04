<?php

/**
 * Base class that represents a row from the 'ffb_news' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbNews extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbNewsPeer
	 */
	protected static $peer;

	/**
	 * The value for the news_id field.
	 * @var        int
	 */
	protected $news_id;

	/**
	 * The value for the news_title field.
	 * @var        string
	 */
	protected $news_title;

	/**
	 * The value for the news_text field.
	 * @var        string
	 */
	protected $news_text;

	/**
	 * The value for the news_date field.
	 * @var        string
	 */
	protected $news_date;

	/**
	 * The value for the news_priority field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $news_priority;

	/**
	 * The value for the news_game_id field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $news_game_id;

	/**
	 * The value for the news_symbol field.
	 * @var        string
	 */
	protected $news_symbol;

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
		$this->news_priority = 0;
		$this->news_game_id = 0;
	}

	/**
	 * Initializes internal state of BaseFfbNews object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [news_id] column value.
	 * 
	 * @return     int
	 */
	public function getNewsId()
	{
		return $this->news_id;
	}

	/**
	 * Get the [news_title] column value.
	 * 
	 * @return     string
	 */
	public function getNewsTitle()
	{
		return $this->news_title;
	}

	/**
	 * Get the [news_text] column value.
	 * 
	 * @return     string
	 */
	public function getNewsText()
	{
		return $this->news_text;
	}

	/**
	 * Get the [optionally formatted] temporal [news_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getNewsDate($format = 'Y-m-d H:i:s')
	{
		if ($this->news_date === null) {
			return null;
		}


		if ($this->news_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->news_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->news_date, true), $x);
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
	 * Get the [news_priority] column value.
	 * 
	 * @return     int
	 */
	public function getNewsPriority()
	{
		return $this->news_priority;
	}

	/**
	 * Get the [news_game_id] column value.
	 * 
	 * @return     int
	 */
	public function getNewsGameId()
	{
		return $this->news_game_id;
	}

	/**
	 * Get the [news_symbol] column value.
	 * 
	 * @return     string
	 */
	public function getNewsSymbol()
	{
		return $this->news_symbol;
	}

	/**
	 * Set the value of [news_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->news_id !== $v) {
			$this->news_id = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_ID;
		}

		return $this;
	} // setNewsId()

	/**
	 * Set the value of [news_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->news_title !== $v) {
			$this->news_title = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_TITLE;
		}

		return $this;
	} // setNewsTitle()

	/**
	 * Set the value of [news_text] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsText($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->news_text !== $v) {
			$this->news_text = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_TEXT;
		}

		return $this;
	} // setNewsText()

	/**
	 * Sets the value of [news_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsDate($v)
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

		if ( $this->news_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->news_date !== null && $tmpDt = new DateTime($this->news_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->news_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbNewsPeer::NEWS_DATE;
			}
		} // if either are not null

		return $this;
	} // setNewsDate()

	/**
	 * Set the value of [news_priority] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsPriority($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->news_priority !== $v || $this->isNew()) {
			$this->news_priority = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_PRIORITY;
		}

		return $this;
	} // setNewsPriority()

	/**
	 * Set the value of [news_game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->news_game_id !== $v || $this->isNew()) {
			$this->news_game_id = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_GAME_ID;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setNewsGameId()

	/**
	 * Set the value of [news_symbol] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbNews The current object (for fluent API support)
	 */
	public function setNewsSymbol($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->news_symbol !== $v) {
			$this->news_symbol = $v;
			$this->modifiedColumns[] = FfbNewsPeer::NEWS_SYMBOL;
		}

		return $this;
	} // setNewsSymbol()

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
			if ($this->news_priority !== 0) {
				return false;
			}

			if ($this->news_game_id !== 0) {
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

			$this->news_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->news_title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->news_text = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->news_date = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->news_priority = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->news_game_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->news_symbol = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = FfbNewsPeer::NUM_COLUMNS - FfbNewsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbNews object", $e);
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

		if ($this->aFfbGame !== null && $this->news_game_id !== $this->aFfbGame->getGameId()) {
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
			$con = Propel::getConnection(FfbNewsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbNewsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
			$con = Propel::getConnection(FfbNewsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbNewsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbNewsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbNewsPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = FfbNewsPeer::NEWS_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbNewsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNewsId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbNewsPeer::doUpdate($this, $con);
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


			if (($retval = FfbNewsPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbNewsPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbNewsPeer::NEWS_ID)) $criteria->add(FfbNewsPeer::NEWS_ID, $this->news_id);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_TITLE)) $criteria->add(FfbNewsPeer::NEWS_TITLE, $this->news_title);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_TEXT)) $criteria->add(FfbNewsPeer::NEWS_TEXT, $this->news_text);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_DATE)) $criteria->add(FfbNewsPeer::NEWS_DATE, $this->news_date);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_PRIORITY)) $criteria->add(FfbNewsPeer::NEWS_PRIORITY, $this->news_priority);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_GAME_ID)) $criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->news_game_id);
		if ($this->isColumnModified(FfbNewsPeer::NEWS_SYMBOL)) $criteria->add(FfbNewsPeer::NEWS_SYMBOL, $this->news_symbol);

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
		$criteria = new Criteria(FfbNewsPeer::DATABASE_NAME);

		$criteria->add(FfbNewsPeer::NEWS_ID, $this->news_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getNewsId();
	}

	/**
	 * Generic method to set the primary key (news_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setNewsId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbNews (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setNewsTitle($this->news_title);

		$copyObj->setNewsText($this->news_text);

		$copyObj->setNewsDate($this->news_date);

		$copyObj->setNewsPriority($this->news_priority);

		$copyObj->setNewsGameId($this->news_game_id);

		$copyObj->setNewsSymbol($this->news_symbol);


		$copyObj->setNew(true);

		$copyObj->setNewsId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbNews Clone of current object.
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
	 * @return     FfbNewsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbNewsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     FfbNews The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(FfbGame $v = null)
	{
		if ($v === null) {
			$this->setNewsGameId(0);
		} else {
			$this->setNewsGameId($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbNews($this);
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
		if ($this->aFfbGame === null && ($this->news_game_id !== null)) {
			$this->aFfbGame = FfbGamePeer::retrieveByPk($this->news_game_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbGame->addFfbNewss($this);
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

} // BaseFfbNews
