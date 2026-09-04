<?php

/**
 * Base class that represents a row from the 'ffb_no_ads' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbNoAds extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbNoAdsPeer
	 */
	protected static $peer;

	/**
	 * The value for the no_ads_id field.
	 * @var        int
	 */
	protected $no_ads_id;

	/**
	 * The value for the no_ads_user_id_ip field.
	 * @var        string
	 */
	protected $no_ads_user_id_ip;

	/**
	 * The value for the no_ads_slot_id field.
	 * @var        int
	 */
	protected $no_ads_slot_id;

	/**
	 * @var        FfbAdsSlot
	 */
	protected $aFfbAdsSlot;

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
	 * Get the [no_ads_id] column value.
	 * 
	 * @return     int
	 */
	public function getNoAdsId()
	{
		return $this->no_ads_id;
	}

	/**
	 * Get the [no_ads_user_id_ip] column value.
	 * 
	 * @return     string
	 */
	public function getNoAdsUserIdIp()
	{
		return $this->no_ads_user_id_ip;
	}

	/**
	 * Get the [no_ads_slot_id] column value.
	 * 
	 * @return     int
	 */
	public function getNoAdsSlotId()
	{
		return $this->no_ads_slot_id;
	}

	/**
	 * Set the value of [no_ads_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbNoAds The current object (for fluent API support)
	 */
	public function setNoAdsId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->no_ads_id !== $v) {
			$this->no_ads_id = $v;
			$this->modifiedColumns[] = FfbNoAdsPeer::NO_ADS_ID;
		}

		return $this;
	} // setNoAdsId()

	/**
	 * Set the value of [no_ads_user_id_ip] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbNoAds The current object (for fluent API support)
	 */
	public function setNoAdsUserIdIp($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->no_ads_user_id_ip !== $v) {
			$this->no_ads_user_id_ip = $v;
			$this->modifiedColumns[] = FfbNoAdsPeer::NO_ADS_USER_ID_IP;
		}

		return $this;
	} // setNoAdsUserIdIp()

	/**
	 * Set the value of [no_ads_slot_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbNoAds The current object (for fluent API support)
	 */
	public function setNoAdsSlotId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->no_ads_slot_id !== $v) {
			$this->no_ads_slot_id = $v;
			$this->modifiedColumns[] = FfbNoAdsPeer::NO_ADS_SLOT_ID;
		}

		if ($this->aFfbAdsSlot !== null && $this->aFfbAdsSlot->getAdsSlotId() !== $v) {
			$this->aFfbAdsSlot = null;
		}

		return $this;
	} // setNoAdsSlotId()

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

			$this->no_ads_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->no_ads_user_id_ip = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->no_ads_slot_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 3; // 3 = FfbNoAdsPeer::NUM_COLUMNS - FfbNoAdsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbNoAds object", $e);
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

		if ($this->aFfbAdsSlot !== null && $this->no_ads_slot_id !== $this->aFfbAdsSlot->getAdsSlotId()) {
			$this->aFfbAdsSlot = null;
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
			$con = Propel::getConnection(FfbNoAdsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbNoAdsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbAdsSlot = null;
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
			$con = Propel::getConnection(FfbNoAdsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbNoAdsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbNoAdsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbNoAdsPeer::addInstanceToPool($this);
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

			if ($this->aFfbAdsSlot !== null) {
				if ($this->aFfbAdsSlot->isModified() || $this->aFfbAdsSlot->isNew()) {
					$affectedRows += $this->aFfbAdsSlot->save($con);
				}
				$this->setFfbAdsSlot($this->aFfbAdsSlot);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbNoAdsPeer::NO_ADS_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbNoAdsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNoAdsId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbNoAdsPeer::doUpdate($this, $con);
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

			if ($this->aFfbAdsSlot !== null) {
				if (!$this->aFfbAdsSlot->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbAdsSlot->getValidationFailures());
				}
			}


			if (($retval = FfbNoAdsPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbNoAdsPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbNoAdsPeer::NO_ADS_ID)) $criteria->add(FfbNoAdsPeer::NO_ADS_ID, $this->no_ads_id);
		if ($this->isColumnModified(FfbNoAdsPeer::NO_ADS_USER_ID_IP)) $criteria->add(FfbNoAdsPeer::NO_ADS_USER_ID_IP, $this->no_ads_user_id_ip);
		if ($this->isColumnModified(FfbNoAdsPeer::NO_ADS_SLOT_ID)) $criteria->add(FfbNoAdsPeer::NO_ADS_SLOT_ID, $this->no_ads_slot_id);

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
		$criteria = new Criteria(FfbNoAdsPeer::DATABASE_NAME);

		$criteria->add(FfbNoAdsPeer::NO_ADS_ID, $this->no_ads_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getNoAdsId();
	}

	/**
	 * Generic method to set the primary key (no_ads_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setNoAdsId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbNoAds (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setNoAdsUserIdIp($this->no_ads_user_id_ip);

		$copyObj->setNoAdsSlotId($this->no_ads_slot_id);


		$copyObj->setNew(true);

		$copyObj->setNoAdsId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbNoAds Clone of current object.
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
	 * @return     FfbNoAdsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbNoAdsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbAdsSlot object.
	 *
	 * @param      FfbAdsSlot $v
	 * @return     FfbNoAds The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbAdsSlot(FfbAdsSlot $v = null)
	{
		if ($v === null) {
			$this->setNoAdsSlotId(NULL);
		} else {
			$this->setNoAdsSlotId($v->getAdsSlotId());
		}

		$this->aFfbAdsSlot = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbAdsSlot object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbNoAds($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbAdsSlot object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbAdsSlot The associated FfbAdsSlot object.
	 * @throws     PropelException
	 */
	public function getFfbAdsSlot(PropelPDO $con = null)
	{
		if ($this->aFfbAdsSlot === null && ($this->no_ads_slot_id !== null)) {
			$this->aFfbAdsSlot = FfbAdsSlotPeer::retrieveByPk($this->no_ads_slot_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbAdsSlot->addFfbNoAdss($this);
			 */
		}
		return $this->aFfbAdsSlot;
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

			$this->aFfbAdsSlot = null;
	}

} // BaseFfbNoAds
