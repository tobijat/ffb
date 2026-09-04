<?php

/**
 * Base class that represents a row from the 'ffb_player' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPlayer extends BaseObject  implements Persistent {


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
	 * @var        Criteria The criteria used to select the current contents of collWebUserDetailss.
	 */
	private $lastWebUserDetailsCriteria = null;

	/**
	 * @var        array FfbPlayerteam[] Collection to store aggregation of FfbPlayerteam objects.
	 */
	protected $collFfbPlayerteams;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPlayerteams.
	 */
	private $lastFfbPlayerteamCriteria = null;

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

			$this->player_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->player_foreign_id = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->player_fname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->player_lname = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->player_nationality = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->player_status = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->player_status_description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
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
	public function reload($deep = false, PropelPDO $con = null)
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
			$this->lastWebUserDetailsCriteria = null;

			$this->collFfbPlayerteams = null;
			$this->lastFfbPlayerteamCriteria = null;

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
			$con = Propel::getConnection(FfbPlayerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerPeer::doDelete($this, $con);
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
				$con->commit();
				FfbPlayerPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPlayerPeer::PLAYER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbPlayerPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setPlayerId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbPlayerPeer::doUpdate($this, $con);
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
	 * Clears out the collWebUserDetailss collection (array).
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
	 * Initializes the collWebUserDetailss collection (array).
	 *
	 * By default this just sets the collWebUserDetailss collection to an empty array (like clearcollWebUserDetailss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebUserDetailss()
	{
		$this->collWebUserDetailss = array();
	}

	/**
	 * Gets an array of WebUserDetails objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbPlayer has previously been saved, it will retrieve
	 * related WebUserDetailss from storage. If this FfbPlayer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WebUserDetails[]
	 * @throws     PropelException
	 */
	public function getWebUserDetailss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
			   $this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				WebUserDetailsPeer::addSelectColumns($criteria);
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				WebUserDetailsPeer::addSelectColumns($criteria);
				if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
					$this->collWebUserDetailss = WebUserDetailsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;
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
	public function countWebUserDetailss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				$count = WebUserDetailsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
					$count = WebUserDetailsPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collWebUserDetailss);
				}
			} else {
				$count = count($this->collWebUserDetailss);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collWebUserDetailss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWebUserDetailss, $l);
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
	 */
	public function getWebUserDetailssJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

			if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;

		return $this->collWebUserDetailss;
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
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

			if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria, $con, $join_behavior);
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;

		return $this->collWebUserDetailss;
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
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

			if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria, $con, $join_behavior);
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;

		return $this->collWebUserDetailss;
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
	 */
	public function getWebUserDetailssJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->player_id);

			if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;

		return $this->collWebUserDetailss;
	}

	/**
	 * Clears out the collFfbPlayerteams collection (array).
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
	 * Initializes the collFfbPlayerteams collection (array).
	 *
	 * By default this just sets the collFfbPlayerteams collection to an empty array (like clearcollFfbPlayerteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerteams()
	{
		$this->collFfbPlayerteams = array();
	}

	/**
	 * Gets an array of FfbPlayerteam objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbPlayer has previously been saved, it will retrieve
	 * related FfbPlayerteams from storage. If this FfbPlayer is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbPlayerteam[]
	 * @throws     PropelException
	 */
	public function getFfbPlayerteams($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPlayerteams === null) {
			if ($this->isNew()) {
			   $this->collFfbPlayerteams = array();
			} else {

				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

				FfbPlayerteamPeer::addSelectColumns($criteria);
				$this->collFfbPlayerteams = FfbPlayerteamPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

				FfbPlayerteamPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbPlayerteamCriteria) || !$this->lastFfbPlayerteamCriteria->equals($criteria)) {
					$this->collFfbPlayerteams = FfbPlayerteamPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbPlayerteamCriteria = $criteria;
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
	public function countFfbPlayerteams(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbPlayerteams === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

				$count = FfbPlayerteamPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

				if (!isset($this->lastFfbPlayerteamCriteria) || !$this->lastFfbPlayerteamCriteria->equals($criteria)) {
					$count = FfbPlayerteamPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbPlayerteams);
				}
			} else {
				$count = count($this->collFfbPlayerteams);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collFfbPlayerteams, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbPlayerteams, $l);
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
	 */
	public function getFfbPlayerteamsJoinFfbTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPlayerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPlayerteams === null) {
			if ($this->isNew()) {
				$this->collFfbPlayerteams = array();
			} else {

				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

				$this->collFfbPlayerteams = FfbPlayerteamPeer::doSelectJoinFfbTeam($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->player_id);

			if (!isset($this->lastFfbPlayerteamCriteria) || !$this->lastFfbPlayerteamCriteria->equals($criteria)) {
				$this->collFfbPlayerteams = FfbPlayerteamPeer::doSelectJoinFfbTeam($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPlayerteamCriteria = $criteria;

		return $this->collFfbPlayerteams;
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

} // BaseFfbPlayer
