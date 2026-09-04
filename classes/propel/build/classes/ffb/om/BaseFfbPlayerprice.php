<?php


/**
 * Base class that represents a row from the 'ffb_playerprice' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerprice extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbPlayerpricePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPlayerpricePeer
	 */
	protected static $peer;

	/**
	 * The value for the playerprice_id field.
	 * @var        int
	 */
	protected $playerprice_id;

	/**
	 * The value for the playerprice_playerteam_id field.
	 * @var        int
	 */
	protected $playerprice_playerteam_id;

	/**
	 * The value for the playerprice_matchround_id field.
	 * @var        int
	 */
	protected $playerprice_matchround_id;

	/**
	 * The value for the playerprice_price field.
	 * Note: this column has a database default value of: 0
	 * @var        double
	 */
	protected $playerprice_price;

	/**
	 * The value for the playerprice_player_power field.
	 * Note: this column has a database default value of: 0
	 * @var        double
	 */
	protected $playerprice_player_power;

	/**
	 * The value for the playerprice_av_power field.
	 * Note: this column has a database default value of: 0
	 * @var        double
	 */
	protected $playerprice_av_power;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteam;

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
		$this->playerprice_price = 0;
		$this->playerprice_player_power = 0;
		$this->playerprice_av_power = 0;
	}

	/**
	 * Initializes internal state of BaseFfbPlayerprice object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [playerprice_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerpriceId()
	{
		return $this->playerprice_id;
	}

	/**
	 * Get the [playerprice_playerteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerpricePlayerteamId()
	{
		return $this->playerprice_playerteam_id;
	}

	/**
	 * Get the [playerprice_matchround_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerpriceMatchroundId()
	{
		return $this->playerprice_matchround_id;
	}

	/**
	 * Get the [playerprice_price] column value.
	 * 
	 * @return     double
	 */
	public function getPlayerpricePrice()
	{
		return $this->playerprice_price;
	}

	/**
	 * Get the [playerprice_player_power] column value.
	 * 
	 * @return     double
	 */
	public function getPlayerpricePlayerPower()
	{
		return $this->playerprice_player_power;
	}

	/**
	 * Get the [playerprice_av_power] column value.
	 * 
	 * @return     double
	 */
	public function getPlayerpriceAvPower()
	{
		return $this->playerprice_av_power;
	}

	/**
	 * Set the value of [playerprice_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpriceId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerprice_id !== $v) {
			$this->playerprice_id = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_ID;
		}

		return $this;
	} // setPlayerpriceId()

	/**
	 * Set the value of [playerprice_playerteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpricePlayerteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerprice_playerteam_id !== $v) {
			$this->playerprice_playerteam_id = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID;
		}

		if ($this->aFfbPlayerteam !== null && $this->aFfbPlayerteam->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteam = null;
		}

		return $this;
	} // setPlayerpricePlayerteamId()

	/**
	 * Set the value of [playerprice_matchround_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpriceMatchroundId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerprice_matchround_id !== $v) {
			$this->playerprice_matchround_id = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID;
		}

		if ($this->aFfbMatchround !== null && $this->aFfbMatchround->getMatchroundId() !== $v) {
			$this->aFfbMatchround = null;
		}

		return $this;
	} // setPlayerpriceMatchroundId()

	/**
	 * Set the value of [playerprice_price] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpricePrice($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->playerprice_price !== $v || $this->isNew()) {
			$this->playerprice_price = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_PRICE;
		}

		return $this;
	} // setPlayerpricePrice()

	/**
	 * Set the value of [playerprice_player_power] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpricePlayerPower($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->playerprice_player_power !== $v || $this->isNew()) {
			$this->playerprice_player_power = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER;
		}

		return $this;
	} // setPlayerpricePlayerPower()

	/**
	 * Set the value of [playerprice_av_power] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 */
	public function setPlayerpriceAvPower($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->playerprice_av_power !== $v || $this->isNew()) {
			$this->playerprice_av_power = $v;
			$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_AV_POWER;
		}

		return $this;
	} // setPlayerpriceAvPower()

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
			if ($this->playerprice_price !== 0) {
				return false;
			}

			if ($this->playerprice_player_power !== 0) {
				return false;
			}

			if ($this->playerprice_av_power !== 0) {
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

			$this->playerprice_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->playerprice_playerteam_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->playerprice_matchround_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->playerprice_price = ($row[$startcol + 3] !== null) ? (double) $row[$startcol + 3] : null;
			$this->playerprice_player_power = ($row[$startcol + 4] !== null) ? (double) $row[$startcol + 4] : null;
			$this->playerprice_av_power = ($row[$startcol + 5] !== null) ? (double) $row[$startcol + 5] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 6; // 6 = FfbPlayerpricePeer::NUM_COLUMNS - FfbPlayerpricePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPlayerprice object", $e);
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

		if ($this->aFfbPlayerteam !== null && $this->playerprice_playerteam_id !== $this->aFfbPlayerteam->getPlayerteamId()) {
			$this->aFfbPlayerteam = null;
		}
		if ($this->aFfbMatchround !== null && $this->playerprice_matchround_id !== $this->aFfbMatchround->getMatchroundId()) {
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
			$con = Propel::getConnection(FfbPlayerpricePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPlayerpricePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPlayerteam = null;
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
			$con = Propel::getConnection(FfbPlayerpricePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerpriceQuery::create()
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
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerpricePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPlayerpricePeer::addInstanceToPool($this);
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

			if ($this->aFfbPlayerteam !== null) {
				if ($this->aFfbPlayerteam->isModified() || $this->aFfbPlayerteam->isNew()) {
					$affectedRows += $this->aFfbPlayerteam->save($con);
				}
				$this->setFfbPlayerteam($this->aFfbPlayerteam);
			}

			if ($this->aFfbMatchround !== null) {
				if ($this->aFfbMatchround->isModified() || $this->aFfbMatchround->isNew()) {
					$affectedRows += $this->aFfbMatchround->save($con);
				}
				$this->setFfbMatchround($this->aFfbMatchround);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPlayerpricePeer::PLAYERPRICE_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbPlayerpricePeer::PLAYERPRICE_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbPlayerpricePeer::PLAYERPRICE_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setPlayerpriceId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbPlayerpricePeer::doUpdate($this, $con);
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

			if ($this->aFfbMatchround !== null) {
				if (!$this->aFfbMatchround->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatchround->getValidationFailures());
				}
			}


			if (($retval = FfbPlayerpricePeer::doValidate($this, $columns)) !== true) {
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
		$pos = FfbPlayerpricePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPlayerpriceId();
				break;
			case 1:
				return $this->getPlayerpricePlayerteamId();
				break;
			case 2:
				return $this->getPlayerpriceMatchroundId();
				break;
			case 3:
				return $this->getPlayerpricePrice();
				break;
			case 4:
				return $this->getPlayerpricePlayerPower();
				break;
			case 5:
				return $this->getPlayerpriceAvPower();
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
		$keys = FfbPlayerpricePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPlayerpriceId(),
			$keys[1] => $this->getPlayerpricePlayerteamId(),
			$keys[2] => $this->getPlayerpriceMatchroundId(),
			$keys[3] => $this->getPlayerpricePrice(),
			$keys[4] => $this->getPlayerpricePlayerPower(),
			$keys[5] => $this->getPlayerpriceAvPower(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbPlayerteam) {
				$result['FfbPlayerteam'] = $this->aFfbPlayerteam->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbMatchround) {
				$result['FfbMatchround'] = $this->aFfbMatchround->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = FfbPlayerpricePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPlayerpriceId($value);
				break;
			case 1:
				$this->setPlayerpricePlayerteamId($value);
				break;
			case 2:
				$this->setPlayerpriceMatchroundId($value);
				break;
			case 3:
				$this->setPlayerpricePrice($value);
				break;
			case 4:
				$this->setPlayerpricePlayerPower($value);
				break;
			case 5:
				$this->setPlayerpriceAvPower($value);
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
		$keys = FfbPlayerpricePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPlayerpriceId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPlayerpricePlayerteamId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPlayerpriceMatchroundId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPlayerpricePrice($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPlayerpricePlayerPower($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPlayerpriceAvPower($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbPlayerpricePeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_ID)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_ID, $this->playerprice_id);
		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $this->playerprice_playerteam_id);
		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $this->playerprice_matchround_id);
		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_PRICE)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PRICE, $this->playerprice_price);
		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER, $this->playerprice_player_power);
		if ($this->isColumnModified(FfbPlayerpricePeer::PLAYERPRICE_AV_POWER)) $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_AV_POWER, $this->playerprice_av_power);

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
		$criteria = new Criteria(FfbPlayerpricePeer::DATABASE_NAME);
		$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_ID, $this->playerprice_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPlayerpriceId();
	}

	/**
	 * Generic method to set the primary key (playerprice_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPlayerpriceId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getPlayerpriceId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPlayerprice (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setPlayerpricePlayerteamId($this->playerprice_playerteam_id);
		$copyObj->setPlayerpriceMatchroundId($this->playerprice_matchround_id);
		$copyObj->setPlayerpricePrice($this->playerprice_price);
		$copyObj->setPlayerpricePlayerPower($this->playerprice_player_power);
		$copyObj->setPlayerpriceAvPower($this->playerprice_av_power);

		$copyObj->setNew(true);
		$copyObj->setPlayerpriceId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbPlayerprice Clone of current object.
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
	 * @return     FfbPlayerpricePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPlayerpricePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteam(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setPlayerpricePlayerteamId(NULL);
		} else {
			$this->setPlayerpricePlayerteamId($v->getPlayerteamId());
		}

		$this->aFfbPlayerteam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerprice($this);
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
		if ($this->aFfbPlayerteam === null && ($this->playerprice_playerteam_id !== null)) {
			$this->aFfbPlayerteam = FfbPlayerteamQuery::create()->findPk($this->playerprice_playerteam_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteam->addFfbPlayerprices($this);
			 */
		}
		return $this->aFfbPlayerteam;
	}

	/**
	 * Declares an association between this object and a FfbMatchround object.
	 *
	 * @param      FfbMatchround $v
	 * @return     FfbPlayerprice The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatchround(FfbMatchround $v = null)
	{
		if ($v === null) {
			$this->setPlayerpriceMatchroundId(NULL);
		} else {
			$this->setPlayerpriceMatchroundId($v->getMatchroundId());
		}

		$this->aFfbMatchround = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatchround object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerprice($this);
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
		if ($this->aFfbMatchround === null && ($this->playerprice_matchround_id !== null)) {
			$this->aFfbMatchround = FfbMatchroundQuery::create()->findPk($this->playerprice_matchround_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbMatchround->addFfbPlayerprices($this);
			 */
		}
		return $this->aFfbMatchround;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->playerprice_id = null;
		$this->playerprice_playerteam_id = null;
		$this->playerprice_matchround_id = null;
		$this->playerprice_price = null;
		$this->playerprice_player_power = null;
		$this->playerprice_av_power = null;
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

		$this->aFfbPlayerteam = null;
		$this->aFfbMatchround = null;
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

} // BaseFfbPlayerprice
