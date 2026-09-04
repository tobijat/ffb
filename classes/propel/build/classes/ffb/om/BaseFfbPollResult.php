<?php


/**
 * Base class that represents a row from the 'ffb_poll_result' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPollResult extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbPollResultPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPollResultPeer
	 */
	protected static $peer;

	/**
	 * The value for the poll_result_id field.
	 * @var        int
	 */
	protected $poll_result_id;

	/**
	 * The value for the poll_result_poll_id field.
	 * @var        int
	 */
	protected $poll_result_poll_id;

	/**
	 * The value for the poll_result_user_id field.
	 * @var        int
	 */
	protected $poll_result_user_id;

	/**
	 * The value for the poll_result_poll_answer_id field.
	 * @var        int
	 */
	protected $poll_result_poll_answer_id;

	/**
	 * The value for the poll_result_text field.
	 * @var        string
	 */
	protected $poll_result_text;

	/**
	 * @var        FfbPoll
	 */
	protected $aFfbPoll;

	/**
	 * @var        FfbPollAnswer
	 */
	protected $aFfbPollAnswer;

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
	 * Get the [poll_result_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollResultId()
	{
		return $this->poll_result_id;
	}

	/**
	 * Get the [poll_result_poll_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollResultPollId()
	{
		return $this->poll_result_poll_id;
	}

	/**
	 * Get the [poll_result_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollResultUserId()
	{
		return $this->poll_result_user_id;
	}

	/**
	 * Get the [poll_result_poll_answer_id] column value.
	 * 
	 * @return     int
	 */
	public function getPollResultPollAnswerId()
	{
		return $this->poll_result_poll_answer_id;
	}

	/**
	 * Get the [poll_result_text] column value.
	 * 
	 * @return     string
	 */
	public function getPollResultText()
	{
		return $this->poll_result_text;
	}

	/**
	 * Set the value of [poll_result_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollResult The current object (for fluent API support)
	 */
	public function setPollResultId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_result_id !== $v) {
			$this->poll_result_id = $v;
			$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_ID;
		}

		return $this;
	} // setPollResultId()

	/**
	 * Set the value of [poll_result_poll_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollResult The current object (for fluent API support)
	 */
	public function setPollResultPollId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_result_poll_id !== $v) {
			$this->poll_result_poll_id = $v;
			$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_POLL_ID;
		}

		if ($this->aFfbPoll !== null && $this->aFfbPoll->getPollId() !== $v) {
			$this->aFfbPoll = null;
		}

		return $this;
	} // setPollResultPollId()

	/**
	 * Set the value of [poll_result_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollResult The current object (for fluent API support)
	 */
	public function setPollResultUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_result_user_id !== $v) {
			$this->poll_result_user_id = $v;
			$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setPollResultUserId()

	/**
	 * Set the value of [poll_result_poll_answer_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPollResult The current object (for fluent API support)
	 */
	public function setPollResultPollAnswerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->poll_result_poll_answer_id !== $v) {
			$this->poll_result_poll_answer_id = $v;
			$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID;
		}

		if ($this->aFfbPollAnswer !== null && $this->aFfbPollAnswer->getPollAnswerId() !== $v) {
			$this->aFfbPollAnswer = null;
		}

		return $this;
	} // setPollResultPollAnswerId()

	/**
	 * Set the value of [poll_result_text] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPollResult The current object (for fluent API support)
	 */
	public function setPollResultText($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->poll_result_text !== $v) {
			$this->poll_result_text = $v;
			$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_TEXT;
		}

		return $this;
	} // setPollResultText()

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

			$this->poll_result_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->poll_result_poll_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->poll_result_user_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->poll_result_poll_answer_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->poll_result_text = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 5; // 5 = FfbPollResultPeer::NUM_COLUMNS - FfbPollResultPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPollResult object", $e);
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

		if ($this->aFfbPoll !== null && $this->poll_result_poll_id !== $this->aFfbPoll->getPollId()) {
			$this->aFfbPoll = null;
		}
		if ($this->aWebUser !== null && $this->poll_result_user_id !== $this->aWebUser->getUserId()) {
			$this->aWebUser = null;
		}
		if ($this->aFfbPollAnswer !== null && $this->poll_result_poll_answer_id !== $this->aFfbPollAnswer->getPollAnswerId()) {
			$this->aFfbPollAnswer = null;
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
			$con = Propel::getConnection(FfbPollResultPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPollResultPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPoll = null;
			$this->aFfbPollAnswer = null;
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
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPollResultPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPollResultQuery::create()
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
			$con = Propel::getConnection(FfbPollResultPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPollResultPeer::addInstanceToPool($this);
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

			if ($this->aFfbPoll !== null) {
				if ($this->aFfbPoll->isModified() || $this->aFfbPoll->isNew()) {
					$affectedRows += $this->aFfbPoll->save($con);
				}
				$this->setFfbPoll($this->aFfbPoll);
			}

			if ($this->aFfbPollAnswer !== null) {
				if ($this->aFfbPollAnswer->isModified() || $this->aFfbPollAnswer->isNew()) {
					$affectedRows += $this->aFfbPollAnswer->save($con);
				}
				$this->setFfbPollAnswer($this->aFfbPollAnswer);
			}

			if ($this->aWebUser !== null) {
				if ($this->aWebUser->isModified() || $this->aWebUser->isNew()) {
					$affectedRows += $this->aWebUser->save($con);
				}
				$this->setWebUser($this->aWebUser);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPollResultPeer::POLL_RESULT_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbPollResultPeer::POLL_RESULT_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbPollResultPeer::POLL_RESULT_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setPollResultId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbPollResultPeer::doUpdate($this, $con);
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

			if ($this->aFfbPoll !== null) {
				if (!$this->aFfbPoll->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPoll->getValidationFailures());
				}
			}

			if ($this->aFfbPollAnswer !== null) {
				if (!$this->aFfbPollAnswer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPollAnswer->getValidationFailures());
				}
			}

			if ($this->aWebUser !== null) {
				if (!$this->aWebUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aWebUser->getValidationFailures());
				}
			}


			if (($retval = FfbPollResultPeer::doValidate($this, $columns)) !== true) {
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
		$pos = FfbPollResultPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPollResultId();
				break;
			case 1:
				return $this->getPollResultPollId();
				break;
			case 2:
				return $this->getPollResultUserId();
				break;
			case 3:
				return $this->getPollResultPollAnswerId();
				break;
			case 4:
				return $this->getPollResultText();
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
		$keys = FfbPollResultPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPollResultId(),
			$keys[1] => $this->getPollResultPollId(),
			$keys[2] => $this->getPollResultUserId(),
			$keys[3] => $this->getPollResultPollAnswerId(),
			$keys[4] => $this->getPollResultText(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbPoll) {
				$result['FfbPoll'] = $this->aFfbPoll->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPollAnswer) {
				$result['FfbPollAnswer'] = $this->aFfbPollAnswer->toArray($keyType, $includeLazyLoadColumns, true);
			}
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
		$pos = FfbPollResultPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPollResultId($value);
				break;
			case 1:
				$this->setPollResultPollId($value);
				break;
			case 2:
				$this->setPollResultUserId($value);
				break;
			case 3:
				$this->setPollResultPollAnswerId($value);
				break;
			case 4:
				$this->setPollResultText($value);
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
		$keys = FfbPollResultPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPollResultId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPollResultPollId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPollResultUserId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPollResultPollAnswerId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPollResultText($arr[$keys[4]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbPollResultPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPollResultPeer::POLL_RESULT_ID)) $criteria->add(FfbPollResultPeer::POLL_RESULT_ID, $this->poll_result_id);
		if ($this->isColumnModified(FfbPollResultPeer::POLL_RESULT_POLL_ID)) $criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ID, $this->poll_result_poll_id);
		if ($this->isColumnModified(FfbPollResultPeer::POLL_RESULT_USER_ID)) $criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->poll_result_user_id);
		if ($this->isColumnModified(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID)) $criteria->add(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $this->poll_result_poll_answer_id);
		if ($this->isColumnModified(FfbPollResultPeer::POLL_RESULT_TEXT)) $criteria->add(FfbPollResultPeer::POLL_RESULT_TEXT, $this->poll_result_text);

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
		$criteria = new Criteria(FfbPollResultPeer::DATABASE_NAME);
		$criteria->add(FfbPollResultPeer::POLL_RESULT_ID, $this->poll_result_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPollResultId();
	}

	/**
	 * Generic method to set the primary key (poll_result_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPollResultId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getPollResultId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPollResult (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setPollResultPollId($this->poll_result_poll_id);
		$copyObj->setPollResultUserId($this->poll_result_user_id);
		$copyObj->setPollResultPollAnswerId($this->poll_result_poll_answer_id);
		$copyObj->setPollResultText($this->poll_result_text);

		$copyObj->setNew(true);
		$copyObj->setPollResultId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbPollResult Clone of current object.
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
	 * @return     FfbPollResultPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPollResultPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPoll object.
	 *
	 * @param      FfbPoll $v
	 * @return     FfbPollResult The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPoll(FfbPoll $v = null)
	{
		if ($v === null) {
			$this->setPollResultPollId(NULL);
		} else {
			$this->setPollResultPollId($v->getPollId());
		}

		$this->aFfbPoll = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPoll object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPollResult($this);
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
		if ($this->aFfbPoll === null && ($this->poll_result_poll_id !== null)) {
			$this->aFfbPoll = FfbPollQuery::create()->findPk($this->poll_result_poll_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPoll->addFfbPollResults($this);
			 */
		}
		return $this->aFfbPoll;
	}

	/**
	 * Declares an association between this object and a FfbPollAnswer object.
	 *
	 * @param      FfbPollAnswer $v
	 * @return     FfbPollResult The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPollAnswer(FfbPollAnswer $v = null)
	{
		if ($v === null) {
			$this->setPollResultPollAnswerId(NULL);
		} else {
			$this->setPollResultPollAnswerId($v->getPollAnswerId());
		}

		$this->aFfbPollAnswer = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPollAnswer object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPollResult($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbPollAnswer object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbPollAnswer The associated FfbPollAnswer object.
	 * @throws     PropelException
	 */
	public function getFfbPollAnswer(PropelPDO $con = null)
	{
		if ($this->aFfbPollAnswer === null && ($this->poll_result_poll_answer_id !== null)) {
			$this->aFfbPollAnswer = FfbPollAnswerQuery::create()->findPk($this->poll_result_poll_answer_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPollAnswer->addFfbPollResults($this);
			 */
		}
		return $this->aFfbPollAnswer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     FfbPollResult The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUser(WebUser $v = null)
	{
		if ($v === null) {
			$this->setPollResultUserId(NULL);
		} else {
			$this->setPollResultUserId($v->getUserId());
		}

		$this->aWebUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WebUser object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPollResult($this);
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
		if ($this->aWebUser === null && ($this->poll_result_user_id !== null)) {
			$this->aWebUser = WebUserQuery::create()->findPk($this->poll_result_user_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aWebUser->addFfbPollResults($this);
			 */
		}
		return $this->aWebUser;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->poll_result_id = null;
		$this->poll_result_poll_id = null;
		$this->poll_result_user_id = null;
		$this->poll_result_poll_answer_id = null;
		$this->poll_result_text = null;
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

		$this->aFfbPoll = null;
		$this->aFfbPollAnswer = null;
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

} // BaseFfbPollResult
