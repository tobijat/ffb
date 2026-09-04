<?php

/**
 * Base class that represents a row from the 'ffb_userscore' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbUserscore extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbUserscorePeer
	 */
	protected static $peer;

	/**
	 * The value for the userscore_id field.
	 * @var        int
	 */
	protected $userscore_id;

	/**
	 * The value for the userscore_user_id field.
	 * @var        int
	 */
	protected $userscore_user_id;

	/**
	 * The value for the userscore_game_id field.
	 * @var        int
	 */
	protected $userscore_game_id;

	/**
	 * The value for the userscore_total field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $userscore_total;

	/**
	 * The value for the userscore_wc_points field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $userscore_wc_points;

	/**
	 * @var        WebUser
	 */
	protected $aWebUser;

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
		$this->userscore_total = 0;
		$this->userscore_wc_points = 0;
	}

	/**
	 * Initializes internal state of BaseFfbUserscore object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [userscore_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserscoreId()
	{
		return $this->userscore_id;
	}

	/**
	 * Get the [userscore_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserscoreUserId()
	{
		return $this->userscore_user_id;
	}

	/**
	 * Get the [userscore_game_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserscoreGameId()
	{
		return $this->userscore_game_id;
	}

	/**
	 * Get the [userscore_total] column value.
	 * 
	 * @return     int
	 */
	public function getUserscoreTotal()
	{
		return $this->userscore_total;
	}

	/**
	 * Get the [userscore_wc_points] column value.
	 * 
	 * @return     int
	 */
	public function getUserscoreWcPoints()
	{
		return $this->userscore_wc_points;
	}

	/**
	 * Set the value of [userscore_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserscore The current object (for fluent API support)
	 */
	public function setUserscoreId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userscore_id !== $v) {
			$this->userscore_id = $v;
			$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_ID;
		}

		return $this;
	} // setUserscoreId()

	/**
	 * Set the value of [userscore_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserscore The current object (for fluent API support)
	 */
	public function setUserscoreUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userscore_user_id !== $v) {
			$this->userscore_user_id = $v;
			$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setUserscoreUserId()

	/**
	 * Set the value of [userscore_game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserscore The current object (for fluent API support)
	 */
	public function setUserscoreGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userscore_game_id !== $v) {
			$this->userscore_game_id = $v;
			$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_GAME_ID;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setUserscoreGameId()

	/**
	 * Set the value of [userscore_total] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserscore The current object (for fluent API support)
	 */
	public function setUserscoreTotal($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userscore_total !== $v || $this->isNew()) {
			$this->userscore_total = $v;
			$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_TOTAL;
		}

		return $this;
	} // setUserscoreTotal()

	/**
	 * Set the value of [userscore_wc_points] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserscore The current object (for fluent API support)
	 */
	public function setUserscoreWcPoints($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userscore_wc_points !== $v || $this->isNew()) {
			$this->userscore_wc_points = $v;
			$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_WC_POINTS;
		}

		return $this;
	} // setUserscoreWcPoints()

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
			if ($this->userscore_total !== 0) {
				return false;
			}

			if ($this->userscore_wc_points !== 0) {
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

			$this->userscore_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->userscore_user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->userscore_game_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->userscore_total = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->userscore_wc_points = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 5; // 5 = FfbUserscorePeer::NUM_COLUMNS - FfbUserscorePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbUserscore object", $e);
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

		if ($this->aWebUser !== null && $this->userscore_user_id !== $this->aWebUser->getUserId()) {
			$this->aWebUser = null;
		}
		if ($this->aFfbGame !== null && $this->userscore_game_id !== $this->aFfbGame->getGameId()) {
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
			$con = Propel::getConnection(FfbUserscorePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbUserscorePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWebUser = null;
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
			$con = Propel::getConnection(FfbUserscorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbUserscorePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbUserscorePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbUserscorePeer::addInstanceToPool($this);
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

			if ($this->aWebUser !== null) {
				if ($this->aWebUser->isModified() || $this->aWebUser->isNew()) {
					$affectedRows += $this->aWebUser->save($con);
				}
				$this->setWebUser($this->aWebUser);
			}

			if ($this->aFfbGame !== null) {
				if ($this->aFfbGame->isModified() || $this->aFfbGame->isNew()) {
					$affectedRows += $this->aFfbGame->save($con);
				}
				$this->setFfbGame($this->aFfbGame);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbUserscorePeer::USERSCORE_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbUserscorePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setUserscoreId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbUserscorePeer::doUpdate($this, $con);
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

			if ($this->aFfbGame !== null) {
				if (!$this->aFfbGame->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbGame->getValidationFailures());
				}
			}


			if (($retval = FfbUserscorePeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbUserscorePeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbUserscorePeer::USERSCORE_ID)) $criteria->add(FfbUserscorePeer::USERSCORE_ID, $this->userscore_id);
		if ($this->isColumnModified(FfbUserscorePeer::USERSCORE_USER_ID)) $criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->userscore_user_id);
		if ($this->isColumnModified(FfbUserscorePeer::USERSCORE_GAME_ID)) $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->userscore_game_id);
		if ($this->isColumnModified(FfbUserscorePeer::USERSCORE_TOTAL)) $criteria->add(FfbUserscorePeer::USERSCORE_TOTAL, $this->userscore_total);
		if ($this->isColumnModified(FfbUserscorePeer::USERSCORE_WC_POINTS)) $criteria->add(FfbUserscorePeer::USERSCORE_WC_POINTS, $this->userscore_wc_points);

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
		$criteria = new Criteria(FfbUserscorePeer::DATABASE_NAME);

		$criteria->add(FfbUserscorePeer::USERSCORE_ID, $this->userscore_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getUserscoreId();
	}

	/**
	 * Generic method to set the primary key (userscore_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setUserscoreId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbUserscore (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserscoreUserId($this->userscore_user_id);

		$copyObj->setUserscoreGameId($this->userscore_game_id);

		$copyObj->setUserscoreTotal($this->userscore_total);

		$copyObj->setUserscoreWcPoints($this->userscore_wc_points);


		$copyObj->setNew(true);

		$copyObj->setUserscoreId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbUserscore Clone of current object.
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
	 * @return     FfbUserscorePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbUserscorePeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     FfbUserscore The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUser(WebUser $v = null)
	{
		if ($v === null) {
			$this->setUserscoreUserId(NULL);
		} else {
			$this->setUserscoreUserId($v->getUserId());
		}

		$this->aWebUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WebUser object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserscore($this);
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
	public function getWebUser(PropelPDO $con = null)
	{
		if ($this->aWebUser === null && ($this->userscore_user_id !== null)) {
			$this->aWebUser = WebUserPeer::retrieveByPk($this->userscore_user_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aWebUser->addFfbUserscores($this);
			 */
		}
		return $this->aWebUser;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     FfbUserscore The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(FfbGame $v = null)
	{
		if ($v === null) {
			$this->setUserscoreGameId(NULL);
		} else {
			$this->setUserscoreGameId($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserscore($this);
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
		if ($this->aFfbGame === null && ($this->userscore_game_id !== null)) {
			$this->aFfbGame = FfbGamePeer::retrieveByPk($this->userscore_game_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbGame->addFfbUserscores($this);
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

			$this->aWebUser = null;
			$this->aFfbGame = null;
	}

} // BaseFfbUserscore
