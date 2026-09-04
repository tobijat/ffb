<?php


/**
 * Base class that represents a row from the 'ffb_player' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayer extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbPlayerPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPlayerPeer
	 */
	protected static $peer;

	/**
	 * The value for the player_id field.
	 * @var        int
	 */
	protected $player_id;

	/**
	 * The value for the player_foreign_id field.
	 * @var        string
	 */
	protected $player_foreign_id;

	/**
	 * The value for the player_fname field.
	 * @var        string
	 */
	protected $player_fname;

	/**
	 * The value for the player_lname field.
	 * @var        string
	 */
	protected $player_lname;

	/**
	 * The value for the player_nationality field.
	 * @var        string
	 */
	protected $player_nationality;

	/**
	 * The value for the player_status field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $player_status;

	/**
	 * The value for the player_status_description field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $player_status_description;

	/**
	 * @var        array WebUserDetails[] Collection to store aggregation of WebUserDetails objects.
	 */
	protected $collWebUserDetailss;

	/**
	 * @var        array FfbPlayerteam[] Collection to store aggregation of FfbPlayerteam objects.
	 */
	protected $collFfbPlayerteams;

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
		$this->player_status = 0;
		$this->player_status_description = '';
	}

	/**
	 * Initializes internal state of BaseFfbPlayer object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [player_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerId()
	{
		return $this->player_id;
	}

	/**
	 * Get the [player_foreign_id] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerForeignId()
	{
		return $this->player_foreign_id;
	}

	/**
	 * Get the [player_fname] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerFname()
	{
		return $this->player_fname;
	}

	/**
	 * Get the [player_lname] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerLname()
	{
		return $this->player_lname;
	}

	/**
	 * Get the [player_nationality] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerNationality()
	{
		return $this->player_nationality;
	}

	/**
	 * Get the [player_status] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerStatus()
	{
		return $this->player_status;
	}

	/**
	 * Get the [player_status_description] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerStatusDescription()
	{
		return $this->player_status_description;
	}

	/**
	 * Set the value of [player_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->player_id !== $v) {
			$this->player_id = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_ID;
		}

		return $this;
	} // setPlayerId()

	/**
	 * Set the value of [player_foreign_id] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerForeignId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->player_foreign_id !== $v) {
			$this->player_foreign_id = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_FOREIGN_ID;
		}

		return $this;
	} // setPlayerForeignId()

	/**
	 * Set the value of [player_fname] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerFname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->player_fname !== $v) {
			$this->player_fname = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_FNAME;
		}

		return $this;
	} // setPlayerFname()

	/**
	 * Set the value of [player_lname] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerLname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->player_lname !== $v) {
			$this->player_lname = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_LNAME;
		}

		return $this;
	} // setPlayerLname()

	/**
	 * Set the value of [player_nationality] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerNationality($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->player_nationality !== $v) {
			$this->player_nationality = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_NATIONALITY;
		}

		return $this;
	} // setPlayerNationality()

	/**
	 * Set the value of [player_status] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerStatus($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->player_status !== $v || $this->isNew()) {
			$this->player_status = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_STATUS;
		}

		return $this;
	} // setPlayerStatus()

	/**
	 * Set the value of [player_status_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayer The current object (for fluent API support)
	 */
	public function setPlayerStatusDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->player_status_description !== $v || $this->isNew()) {
			$this->player_status_description = $v;
			$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_STATUS_DESCRIPTION;
		}

		return $this;
	} // setPlayerStatusDescription()

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
			if ($this->player_status !== 0) {
				return false;
			}

			if ($this->player_status_description !== '') {
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

			$this->player_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->player_foreign_id = (($row[$startcol + 1] ?? null) !== null) ? (string) $row[$startcol + 1] : null;
			$this->player_fname = (($row[$startcol + 2] ?? null) !== null) ? (string) $row[$startcol + 2] : null;
			$this->player_lname = (($row[$startcol + 3] ?? null) !== null) ? (string) $row[$startcol + 3] : null;
			$this->player_nationality = (($row[$startcol + 4] ?? null) !== null) ? (string) $row[$startcol + 4] : null;
			$this->player_status = (($row[$startcol + 5] ?? null) !== null) ? (int) $row[$startcol + 5] : null;
			$this->player_status_description = (($row[$startcol + 6] ?? null) !== null) ? (string) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 7; // 7 = FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPlayer object", $e);
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
			$con = Propel::getConnection(FfbPlayerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPlayerPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collWebUserDetailss = null;

			$this->collFfbPlayerteams = null;

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
			$con = Propel::getConnection(FfbPlayerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerQuery::create()
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
			$con = Propel::getConnection(FfbPlayerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPlayerPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbPlayerPeer::PLAYER_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbPlayerPeer::PLAYER_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setPlayerId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = FfbPlayerPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collWebUserDetailss !== null) {
				foreach ($this->collWebUserDetailss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPlayerteams !== null) {
				foreach ($this->collFfbPlayerteams as $referrerFK) {
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


			if (($retval = FfbPlayerPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collWebUserDetailss !== null) {
					foreach ($this->collWebUserDetailss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPlayerteams !== null) {
					foreach ($this->collFfbPlayerteams as $referrerFK) {
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
		$pos = FfbPlayerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPlayerId();
				break;
			case 1:
				return $this->getPlayerForeignId();
				break;
			case 2:
				return $this->getPlayerFname();
				break;
			case 3:
				return $this->getPlayerLname();
				break;
			case 4:
				return $this->getPlayerNationality();
				break;
			case 5:
				return $this->getPlayerStatus();
				break;
			case 6:
				return $this->getPlayerStatusDescription();
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
		$keys = FfbPlayerPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPlayerId(),
			$keys[1] => $this->getPlayerForeignId(),
			$keys[2] => $this->getPlayerFname(),
			$keys[3] => $this->getPlayerLname(),
			$keys[4] => $this->getPlayerNationality(),
			$keys[5] => $this->getPlayerStatus(),
			$keys[6] => $this->getPlayerStatusDescription(),
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
		$pos = FfbPlayerPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPlayerId($value);
				break;
			case 1:
				$this->setPlayerForeignId($value);
				break;
			case 2:
				$this->setPlayerFname($value);
				break;
			case 3:
				$this->setPlayerLname($value);
				break;
			case 4:
				$this->setPlayerNationality($value);
				break;
			case 5:
				$this->setPlayerStatus($value);
				break;
			case 6:
				$this->setPlayerStatusDescription($value);
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
		$keys = FfbPlayerPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPlayerId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPlayerForeignId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPlayerFname($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPlayerLname($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPlayerNationality($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPlayerStatus($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPlayerStatusDescription($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_ID)) $criteria->add(FfbPlayerPeer::PLAYER_ID, $this->player_id);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_FOREIGN_ID)) $criteria->add(FfbPlayerPeer::PLAYER_FOREIGN_ID, $this->player_foreign_id);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_FNAME)) $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $this->player_fname);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_LNAME)) $criteria->add(FfbPlayerPeer::PLAYER_LNAME, $this->player_lname);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_NATIONALITY)) $criteria->add(FfbPlayerPeer::PLAYER_NATIONALITY, $this->player_nationality);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_STATUS)) $criteria->add(FfbPlayerPeer::PLAYER_STATUS, $this->player_status);
		if ($this->isColumnModified(FfbPlayerPeer::PLAYER_STATUS_DESCRIPTION)) $criteria->add(FfbPlayerPeer::PLAYER_STATUS_DESCRIPTION, $this->player_status_description);

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
		$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		$criteria->add(FfbPlayerPeer::PLAYER_ID, $this->player_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPlayerId();
	}

	/**
	 * Generic method to set the primary key (player_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPlayerId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getPlayerId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPlayer (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setPlayerForeignId($this->player_foreign_id);
		$copyObj->setPlayerFname($this->player_fname);
		$copyObj->setPlayerLname($this->player_lname);
		$copyObj->setPlayerNationality($this->player_nationality);
		$copyObj->setPlayerStatus($this->player_status);
		$copyObj->setPlayerStatusDescription($this->player_status_description);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getWebUserDetailss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebUserDetails($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerteams() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerteam($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setPlayerId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbPlayer Clone of current object.
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
	 * @return     FfbPlayerPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPlayerPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collWebUserDetailss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWebUserDetailss()
	 */
	public function clearWebUserDetailss()
	{
		$this->collWebUserDetailss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWebUserDetailss collection.
	 *
	 * By default this just sets the collWebUserDetailss collection to an empty array (like clearcollWebUserDetailss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebUserDetailss()
	{
		$this->collWebUserDetailss = new PropelObjectCollection();
		$this->collWebUserDetailss->setModel('WebUserDetails');
	}

	/**
	 * Gets an array of WebUserDetails objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayer is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 * @throws     PropelException
	 */
	public function getWebUserDetailss($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailss || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailss) {
				// return empty collection
				$this->initWebUserDetailss();
			} else {
				$collWebUserDetailss = WebUserDetailsQuery::create(null, $criteria)
					->filterByFfbPlayer($this)
					->find($con);
				if (null !== $criteria) {
					return $collWebUserDetailss;
				}
				$this->collWebUserDetailss = $collWebUserDetailss;
			}
		}
		return $this->collWebUserDetailss;
	}

	/**
	 * Returns the number of related WebUserDetails objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WebUserDetails objects.
	 * @throws     PropelException
	 */
	public function countWebUserDetailss(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailss || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailss) {
				return 0;
			} else {
				$query = WebUserDetailsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayer($this)
					->count($con);
			}
		} else {
			return count($this->collWebUserDetailss);
		}
	}

	/**
	 * Method called to associate a WebUserDetails object to this object
	 * through the WebUserDetails foreign key attribute.
	 *
	 * @param      WebUserDetails $l WebUserDetails
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWebUserDetails(WebUserDetails $l)
	{
		if ($this->collWebUserDetailss === null) {
			$this->initWebUserDetailss();
		}
		if (!$this->collWebUserDetailss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collWebUserDetailss[]= $l;
			$l->setFfbPlayer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayer is new, it will return
	 * an empty collection; or if this FfbPlayer has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayer.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getWebUserDetailss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayer is new, it will return
	 * an empty collection; or if this FfbPlayer has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayer.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbTeamRelatedByUserDetailsFfbFavouriteTeam', $join_behavior);

		return $this->getWebUserDetailss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayer is new, it will return
	 * an empty collection; or if this FfbPlayer has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayer.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbTeamRelatedByUserDetailsFfbOwnTeam', $join_behavior);

		return $this->getWebUserDetailss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayer is new, it will return
	 * an empty collection; or if this FfbPlayer has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayer.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getWebUserDetailss($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerteams collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerteams()
	 */
	public function clearFfbPlayerteams()
	{
		$this->collFfbPlayerteams = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerteams collection.
	 *
	 * By default this just sets the collFfbPlayerteams collection to an empty array (like clearcollFfbPlayerteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerteams()
	{
		$this->collFfbPlayerteams = new PropelObjectCollection();
		$this->collFfbPlayerteams->setModel('FfbPlayerteam');
	}

	/**
	 * Gets an array of FfbPlayerteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayer is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerteam[] List of FfbPlayerteam objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerteams($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerteams) {
				// return empty collection
				$this->initFfbPlayerteams();
			} else {
				$collFfbPlayerteams = FfbPlayerteamQuery::create(null, $criteria)
					->filterByFfbPlayer($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerteams;
				}
				$this->collFfbPlayerteams = $collFfbPlayerteams;
			}
		}
		return $this->collFfbPlayerteams;
	}

	/**
	 * Returns the number of related FfbPlayerteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerteam objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerteams(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerteams) {
				return 0;
			} else {
				$query = FfbPlayerteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayer($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerteams);
		}
	}

	/**
	 * Method called to associate a FfbPlayerteam object to this object
	 * through the FfbPlayerteam foreign key attribute.
	 *
	 * @param      FfbPlayerteam $l FfbPlayerteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerteam(FfbPlayerteam $l)
	{
		if ($this->collFfbPlayerteams === null) {
			$this->initFfbPlayerteams();
		}
		if (!$this->collFfbPlayerteams->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerteams[]= $l;
			$l->setFfbPlayer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayer is new, it will return
	 * an empty collection; or if this FfbPlayer has previously
	 * been saved, it will retrieve related FfbPlayerteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayer.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerteam[] List of FfbPlayerteam objects
	 */
	public function getFfbPlayerteamsJoinFfbTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerteamQuery::create(null, $criteria);
		$query->joinWith('FfbTeam', $join_behavior);

		return $this->getFfbPlayerteams($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->player_id = null;
		$this->player_foreign_id = null;
		$this->player_fname = null;
		$this->player_lname = null;
		$this->player_nationality = null;
		$this->player_status = null;
		$this->player_status_description = null;
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
			if ($this->collWebUserDetailss) {
				foreach ((array) $this->collWebUserDetailss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerteams) {
				foreach ((array) $this->collFfbPlayerteams as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collWebUserDetailss = null;
		$this->collFfbPlayerteams = null;
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

} // BaseFfbPlayer
