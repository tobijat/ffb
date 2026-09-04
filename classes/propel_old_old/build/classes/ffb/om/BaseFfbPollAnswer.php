<?php

/**
 * Base class that represents a row from the 'ffb_poll_answer' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPollAnswer extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPollAnswerPeer
	 */
	protected static $peer;

	/**
	 * The value for the poll_answer_id field.
	 * @var        int
	 */
	protected $poll_answer_id;

	/**
	 * The value for the poll_answer_poll_id field.
	 * @var        int
	 */
	protected $poll_answer_poll_id;

	/**
	 * The value for the poll_answer_title field.
	 * @var        string
	 */
	protected $poll_answer_title;

	/**
	 * The value for the poll_answer_count field.
	 * @var        int
	 */
	protected $poll_answer_count;

	/**
	 * @var        FfbPoll
	 */
	protected $aFfbPoll;

	/**
	 * @var        array FfbPollResult[] Collection to store aggregation of FfbPollResult objects.
	 */
	protected $collFfbPollResults;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPollResults.
	 */
	private $lastFfbPollResultCriteria = null;

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
	 * Get the [poll_answer_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollAnswerId()
	{
		return $this->poll_answer_id;
	}

	/**
	 * Get the [poll_answer_poll_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollAnswerPollId()
	{
		return $this->poll_answer_poll_id;
	}

	/**
	 * Get the [poll_answer_title] column value.
	 * 
	 * @return     string
	 */
	public function getPollAnswerTitle()
	{
		return $this->poll_answer_title;
	}

	/**
	 * Get the [poll_answer_count] column value.
	 * 
	 * @return     int
	 */
	public function getPollAnswerCount()
	{
		return $this->poll_answer_count;
	}

	/**
	 * Set the value of [poll_answer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollAnswer The current object (for fluent API support)
	 */
	public function setPollAnswerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_answer_id !== $v) {
			$this->poll_answer_id = $v;
			$this->modifiedColumns[] = FfbPollAnswerPeer::POLL_ANSWER_ID;
		}

		return $this;
	} // setPollAnswerId()

	/**
	 * Set the value of [poll_answer_poll_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollAnswer The current object (for fluent API support)
	 */
	public function setPollAnswerPollId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_answer_poll_id !== $v) {
			$this->poll_answer_poll_id = $v;
			$this->modifiedColumns[] = FfbPollAnswerPeer::POLL_ANSWER_POLL_ID;
		}

		if ($this->aFfbPoll !== null && $this->aFfbPoll->getPollId() !== $v) {
			$this->aFfbPoll = null;
		}

		return $this;
	} // setPollAnswerPollId()

	/**
	 * Set the value of [poll_answer_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPollAnswer The current object (for fluent API support)
	 */
	public function setPollAnswerTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->poll_answer_title !== $v) {
			$this->poll_answer_title = $v;
			$this->modifiedColumns[] = FfbPollAnswerPeer::POLL_ANSWER_TITLE;
		}

		return $this;
	} // setPollAnswerTitle()

	/**
	 * Set the value of [poll_answer_count] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollAnswer The current object (for fluent API support)
	 */
	public function setPollAnswerCount($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_answer_count !== $v) {
			$this->poll_answer_count = $v;
			$this->modifiedColumns[] = FfbPollAnswerPeer::POLL_ANSWER_COUNT;
		}

		return $this;
	} // setPollAnswerCount()

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

			$this->poll_answer_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->poll_answer_poll_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->poll_answer_title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->poll_answer_count = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 4; // 4 = FfbPollAnswerPeer::NUM_COLUMNS - FfbPollAnswerPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPollAnswer object", $e);
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

		if ($this->aFfbPoll !== null && $this->poll_answer_poll_id !== $this->aFfbPoll->getPollId()) {
			$this->aFfbPoll = null;
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
			$con = Propel::getConnection(FfbPollAnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPollAnswerPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPoll = null;
			$this->collFfbPollResults = null;
			$this->lastFfbPollResultCriteria = null;

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
			$con = Propel::getConnection(FfbPollAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPollAnswerPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbPollAnswerPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPollAnswerPeer::addInstanceToPool($this);
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

			if ($this->aFfbPoll !== null) {
				if ($this->aFfbPoll->isModified() || $this->aFfbPoll->isNew()) {
					$affectedRows += $this->aFfbPoll->save($con);
				}
				$this->setFfbPoll($this->aFfbPoll);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPollAnswerPeer::POLL_ANSWER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbPollAnswerPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setPollAnswerId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbPollAnswerPeer::doUpdate($this, $con);
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

			if ($this->aFfbPoll !== null) {
				if (!$this->aFfbPoll->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPoll->getValidationFailures());
				}
			}


			if (($retval = FfbPollAnswerPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbPollResults !== null) {
					foreach ($this->collFfbPollResults as $referrerFK) {
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
		$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPollAnswerPeer::POLL_ANSWER_ID)) $criteria->add(FfbPollAnswerPeer::POLL_ANSWER_ID, $this->poll_answer_id);
		if ($this->isColumnModified(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID)) $criteria->add(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $this->poll_answer_poll_id);
		if ($this->isColumnModified(FfbPollAnswerPeer::POLL_ANSWER_TITLE)) $criteria->add(FfbPollAnswerPeer::POLL_ANSWER_TITLE, $this->poll_answer_title);
		if ($this->isColumnModified(FfbPollAnswerPeer::POLL_ANSWER_COUNT)) $criteria->add(FfbPollAnswerPeer::POLL_ANSWER_COUNT, $this->poll_answer_count);

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
		$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);

		$criteria->add(FfbPollAnswerPeer::POLL_ANSWER_ID, $this->poll_answer_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPollAnswerId();
	}

	/**
	 * Generic method to set the primary key (poll_answer_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPollAnswerId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPollAnswer (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPollAnswerPollId($this->poll_answer_poll_id);

		$copyObj->setPollAnswerTitle($this->poll_answer_title);

		$copyObj->setPollAnswerCount($this->poll_answer_count);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbPollResults() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPollResult($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setPollAnswerId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbPollAnswer Clone of current object.
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
	 * @return     FfbPollAnswerPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPollAnswerPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPoll object.
	 *
	 * @param      FfbPoll $v
	 * @return     FfbPollAnswer The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPoll(FfbPoll $v = null)
	{
		if ($v === null) {
			$this->setPollAnswerPollId(NULL);
		} else {
			$this->setPollAnswerPollId($v->getPollId());
		}

		$this->aFfbPoll = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPoll object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPollAnswer($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbPoll object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbPoll The associated FfbPoll object.
	 * @throws     PropelException
	 */
	public function getFfbPoll(PropelPDO $con = null)
	{
		if ($this->aFfbPoll === null && ($this->poll_answer_poll_id !== null)) {
			$this->aFfbPoll = FfbPollPeer::retrieveByPk($this->poll_answer_poll_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbPoll->addFfbPollAnswers($this);
			 */
		}
		return $this->aFfbPoll;
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
	 * Otherwise if this FfbPollAnswer has previously been saved, it will retrieve
	 * related FfbPollResults from storage. If this FfbPollAnswer is new, it will return
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
			$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
			   $this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

				FfbPollResultPeer::addSelectColumns($criteria);
				$this->collFfbPollResults = FfbPollResultPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

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
			$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);
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

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

				$count = FfbPollResultPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

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
			$l->setFfbPollAnswer($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPollAnswer is new, it will return
	 * an empty collection; or if this FfbPollAnswer has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPollAnswer.
	 */
	public function getFfbPollResultsJoinFfbPoll($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPoll($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPoll($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPollAnswer is new, it will return
	 * an empty collection; or if this FfbPollAnswer has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPollAnswer.
	 */
	public function getFfbPollResultsJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbPollAnswerPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_answer_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
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
		} // if ($deep)

		$this->collFfbPollResults = null;
			$this->aFfbPoll = null;
	}

} // BaseFfbPollAnswer
