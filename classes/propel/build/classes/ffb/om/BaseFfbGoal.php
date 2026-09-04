<?php


/**
 * Base class that represents a row from the 'ffb_goal' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbGoal extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbGoalPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbGoalPeer
	 */
	protected static $peer;

	/**
	 * The value for the goal_id field.
	 * @var        int
	 */
	protected $goal_id;

	/**
	 * The value for the goal_match_id field.
	 * @var        int
	 */
	protected $goal_match_id;

	/**
	 * The value for the goal_playerteam_id field.
	 * @var        int
	 */
	protected $goal_playerteam_id;

	/**
	 * The value for the goal_minute field.
	 * @var        int
	 */
	protected $goal_minute;

	/**
	 * The value for the goal_owngoal field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $goal_owngoal;

	/**
	 * The value for the goal_penalty field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $goal_penalty;

	/**
	 * The value for the goal_penaltyshootout field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $goal_penaltyshootout;

	/**
	 * @var        FfbMatch
	 */
	protected $aFfbMatch;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteam;

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
		$this->goal_owngoal = false;
		$this->goal_penalty = false;
		$this->goal_penaltyshootout = false;
	}

	/**
	 * Initializes internal state of BaseFfbGoal object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [goal_id] column value.
	 * 
	 * @return     int
	 */
	public function getGoalId()
	{
		return $this->goal_id;
	}

	/**
	 * Get the [goal_match_id] column value.
	 * 
	 * @return     int
	 */
	public function getGoalMatchId()
	{
		return $this->goal_match_id;
	}

	/**
	 * Get the [goal_playerteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getGoalPlayerteamId()
	{
		return $this->goal_playerteam_id;
	}

	/**
	 * Get the [goal_minute] column value.
	 * 
	 * @return     int
	 */
	public function getGoalMinute()
	{
		return $this->goal_minute;
	}

	/**
	 * Get the [goal_owngoal] column value.
	 * 
	 * @return     boolean
	 */
	public function getGoalOwngoal()
	{
		return $this->goal_owngoal;
	}

	/**
	 * Get the [goal_penalty] column value.
	 * 
	 * @return     boolean
	 */
	public function getGoalPenalty()
	{
		return $this->goal_penalty;
	}

	/**
	 * Get the [goal_penaltyshootout] column value.
	 * 
	 * @return     boolean
	 */
	public function getGoalPenaltyshootout()
	{
		return $this->goal_penaltyshootout;
	}

	/**
	 * Set the value of [goal_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->goal_id !== $v) {
			$this->goal_id = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_ID;
		}

		return $this;
	} // setGoalId()

	/**
	 * Set the value of [goal_match_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalMatchId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->goal_match_id !== $v) {
			$this->goal_match_id = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_MATCH_ID;
		}

		if ($this->aFfbMatch !== null && $this->aFfbMatch->getMatchId() !== $v) {
			$this->aFfbMatch = null;
		}

		return $this;
	} // setGoalMatchId()

	/**
	 * Set the value of [goal_playerteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalPlayerteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->goal_playerteam_id !== $v) {
			$this->goal_playerteam_id = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_PLAYERTEAM_ID;
		}

		if ($this->aFfbPlayerteam !== null && $this->aFfbPlayerteam->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteam = null;
		}

		return $this;
	} // setGoalPlayerteamId()

	/**
	 * Set the value of [goal_minute] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalMinute($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->goal_minute !== $v) {
			$this->goal_minute = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_MINUTE;
		}

		return $this;
	} // setGoalMinute()

	/**
	 * Set the value of [goal_owngoal] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalOwngoal($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->goal_owngoal !== $v || $this->isNew()) {
			$this->goal_owngoal = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_OWNGOAL;
		}

		return $this;
	} // setGoalOwngoal()

	/**
	 * Set the value of [goal_penalty] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalPenalty($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->goal_penalty !== $v || $this->isNew()) {
			$this->goal_penalty = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_PENALTY;
		}

		return $this;
	} // setGoalPenalty()

	/**
	 * Set the value of [goal_penaltyshootout] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGoal The current object (for fluent API support)
	 */
	public function setGoalPenaltyshootout($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->goal_penaltyshootout !== $v || $this->isNew()) {
			$this->goal_penaltyshootout = $v;
			$this->modifiedColumns[] = FfbGoalPeer::GOAL_PENALTYSHOOTOUT;
		}

		return $this;
	} // setGoalPenaltyshootout()

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
			if ($this->goal_owngoal !== false) {
				return false;
			}

			if ($this->goal_penalty !== false) {
				return false;
			}

			if ($this->goal_penaltyshootout !== false) {
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

			$this->goal_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->goal_match_id = (($row[$startcol + 1] ?? null) !== null) ? (int) $row[$startcol + 1] : null;
			$this->goal_playerteam_id = (($row[$startcol + 2] ?? null) !== null) ? (int) $row[$startcol + 2] : null;
			$this->goal_minute = (($row[$startcol + 3] ?? null) !== null) ? (int) $row[$startcol + 3] : null;
			$this->goal_owngoal = (($row[$startcol + 4] ?? null) !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->goal_penalty = (($row[$startcol + 5] ?? null) !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->goal_penaltyshootout = (($row[$startcol + 6] ?? null) !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 7; // 7 = FfbGoalPeer::NUM_COLUMNS - FfbGoalPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbGoal object", $e);
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

		if ($this->aFfbMatch !== null && $this->goal_match_id !== $this->aFfbMatch->getMatchId()) {
			$this->aFfbMatch = null;
		}
		if ($this->aFfbPlayerteam !== null && $this->goal_playerteam_id !== $this->aFfbPlayerteam->getPlayerteamId()) {
			$this->aFfbPlayerteam = null;
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
			$con = Propel::getConnection(FfbGoalPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbGoalPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbMatch = null;
			$this->aFfbPlayerteam = null;
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
			$con = Propel::getConnection(FfbGoalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbGoalQuery::create()
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
			$con = Propel::getConnection(FfbGoalPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbGoalPeer::addInstanceToPool($this);
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

			if ($this->aFfbMatch !== null) {
				if ($this->aFfbMatch->isModified() || $this->aFfbMatch->isNew()) {
					$affectedRows += $this->aFfbMatch->save($con);
				}
				$this->setFfbMatch($this->aFfbMatch);
			}

			if ($this->aFfbPlayerteam !== null) {
				if ($this->aFfbPlayerteam->isModified() || $this->aFfbPlayerteam->isNew()) {
					$affectedRows += $this->aFfbPlayerteam->save($con);
				}
				$this->setFfbPlayerteam($this->aFfbPlayerteam);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbGoalPeer::GOAL_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbGoalPeer::GOAL_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbGoalPeer::GOAL_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setGoalId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbGoalPeer::doUpdate($this, $con);
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

			if ($this->aFfbMatch !== null) {
				if (!$this->aFfbMatch->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatch->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteam !== null) {
				if (!$this->aFfbPlayerteam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteam->getValidationFailures());
				}
			}


			if (($retval = FfbGoalPeer::doValidate($this, $columns)) !== true) {
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
		$pos = FfbGoalPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getGoalId();
				break;
			case 1:
				return $this->getGoalMatchId();
				break;
			case 2:
				return $this->getGoalPlayerteamId();
				break;
			case 3:
				return $this->getGoalMinute();
				break;
			case 4:
				return $this->getGoalOwngoal();
				break;
			case 5:
				return $this->getGoalPenalty();
				break;
			case 6:
				return $this->getGoalPenaltyshootout();
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
		$keys = FfbGoalPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getGoalId(),
			$keys[1] => $this->getGoalMatchId(),
			$keys[2] => $this->getGoalPlayerteamId(),
			$keys[3] => $this->getGoalMinute(),
			$keys[4] => $this->getGoalOwngoal(),
			$keys[5] => $this->getGoalPenalty(),
			$keys[6] => $this->getGoalPenaltyshootout(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbMatch) {
				$result['FfbMatch'] = $this->aFfbMatch->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteam) {
				$result['FfbPlayerteam'] = $this->aFfbPlayerteam->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = FfbGoalPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setGoalId($value);
				break;
			case 1:
				$this->setGoalMatchId($value);
				break;
			case 2:
				$this->setGoalPlayerteamId($value);
				break;
			case 3:
				$this->setGoalMinute($value);
				break;
			case 4:
				$this->setGoalOwngoal($value);
				break;
			case 5:
				$this->setGoalPenalty($value);
				break;
			case 6:
				$this->setGoalPenaltyshootout($value);
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
		$keys = FfbGoalPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setGoalId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setGoalMatchId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setGoalPlayerteamId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setGoalMinute($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setGoalOwngoal($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setGoalPenalty($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setGoalPenaltyshootout($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbGoalPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbGoalPeer::GOAL_ID)) $criteria->add(FfbGoalPeer::GOAL_ID, $this->goal_id);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_MATCH_ID)) $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $this->goal_match_id);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_PLAYERTEAM_ID)) $criteria->add(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $this->goal_playerteam_id);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_MINUTE)) $criteria->add(FfbGoalPeer::GOAL_MINUTE, $this->goal_minute);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_OWNGOAL)) $criteria->add(FfbGoalPeer::GOAL_OWNGOAL, $this->goal_owngoal);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_PENALTY)) $criteria->add(FfbGoalPeer::GOAL_PENALTY, $this->goal_penalty);
		if ($this->isColumnModified(FfbGoalPeer::GOAL_PENALTYSHOOTOUT)) $criteria->add(FfbGoalPeer::GOAL_PENALTYSHOOTOUT, $this->goal_penaltyshootout);

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
		$criteria = new Criteria(FfbGoalPeer::DATABASE_NAME);
		$criteria->add(FfbGoalPeer::GOAL_ID, $this->goal_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getGoalId();
	}

	/**
	 * Generic method to set the primary key (goal_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setGoalId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getGoalId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbGoal (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setGoalMatchId($this->goal_match_id);
		$copyObj->setGoalPlayerteamId($this->goal_playerteam_id);
		$copyObj->setGoalMinute($this->goal_minute);
		$copyObj->setGoalOwngoal($this->goal_owngoal);
		$copyObj->setGoalPenalty($this->goal_penalty);
		$copyObj->setGoalPenaltyshootout($this->goal_penaltyshootout);

		$copyObj->setNew(true);
		$copyObj->setGoalId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbGoal Clone of current object.
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
	 * @return     FfbGoalPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbGoalPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbMatch object.
	 *
	 * @param      FfbMatch $v
	 * @return     FfbGoal The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatch(?FfbMatch $v = null)
	{
		if ($v === null) {
			$this->setGoalMatchId(NULL);
		} else {
			$this->setGoalMatchId($v->getMatchId());
		}

		$this->aFfbMatch = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatch object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbGoal($this);
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
	public function getFfbMatch(?PropelPDO $con = null)
	{
		if ($this->aFfbMatch === null && ($this->goal_match_id !== null)) {
			$this->aFfbMatch = FfbMatchQuery::create()->findPk($this->goal_match_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbMatch->addFfbGoals($this);
			 */
		}
		return $this->aFfbMatch;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbGoal The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteam(?FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setGoalPlayerteamId(NULL);
		} else {
			$this->setGoalPlayerteamId($v->getPlayerteamId());
		}

		$this->aFfbPlayerteam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbGoal($this);
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
	public function getFfbPlayerteam(?PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteam === null && ($this->goal_playerteam_id !== null)) {
			$this->aFfbPlayerteam = FfbPlayerteamQuery::create()->findPk($this->goal_playerteam_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteam->addFfbGoals($this);
			 */
		}
		return $this->aFfbPlayerteam;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->goal_id = null;
		$this->goal_match_id = null;
		$this->goal_playerteam_id = null;
		$this->goal_minute = null;
		$this->goal_owngoal = null;
		$this->goal_penalty = null;
		$this->goal_penaltyshootout = null;
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

		$this->aFfbMatch = null;
		$this->aFfbPlayerteam = null;
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

} // BaseFfbGoal
