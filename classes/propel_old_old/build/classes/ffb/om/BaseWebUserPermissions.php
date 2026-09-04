<?php

/**
 * Base class that represents a row from the 'web_user_permissions' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseWebUserPermissions extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WebUserPermissionsPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the user_permissions_ffb_mailservice_reminder field.
	 * @var        string
	 */
	protected $user_permissions_ffb_mailservice_reminder;

	/**
	 * The value for the user_permissions_ffb_mailservice_info field.
	 * @var        string
	 */
	protected $user_permissions_ffb_mailservice_info;

	/**
	 * The value for the user_permissions_ffb_facebook field.
	 * @var        string
	 */
	protected $user_permissions_ffb_facebook;

	/**
	 * The value for the user_permissions_pictory_facebook field.
	 * @var        string
	 */
	protected $user_permissions_pictory_facebook;

	/**
	 * The value for the user_permissions_facebook_connected field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $user_permissions_facebook_connected;

	/**
	 * The value for the user_permissions_ffb_visible_profile field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $user_permissions_ffb_visible_profile;

	/**
	 * The value for the user_permissions_pictory_visible_profile field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $user_permissions_pictory_visible_profile;

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
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->user_permissions_facebook_connected = false;
		$this->user_permissions_ffb_visible_profile = false;
		$this->user_permissions_pictory_visible_profile = false;
	}

	/**
	 * Initializes internal state of BaseWebUserPermissions object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * Get the [user_permissions_ffb_mailservice_reminder] column value.
	 * 
	 * @return     string
	 */
	public function getUserPermissionsFfbMailserviceReminder()
	{
		return $this->user_permissions_ffb_mailservice_reminder;
	}

	/**
	 * Get the [user_permissions_ffb_mailservice_info] column value.
	 * 
	 * @return     string
	 */
	public function getUserPermissionsFfbMailserviceInfo()
	{
		return $this->user_permissions_ffb_mailservice_info;
	}

	/**
	 * Get the [user_permissions_ffb_facebook] column value.
	 * 
	 * @return     string
	 */
	public function getUserPermissionsFfbFacebook()
	{
		return $this->user_permissions_ffb_facebook;
	}

	/**
	 * Get the [user_permissions_pictory_facebook] column value.
	 * 
	 * @return     string
	 */
	public function getUserPermissionsPictoryFacebook()
	{
		return $this->user_permissions_pictory_facebook;
	}

	/**
	 * Get the [user_permissions_facebook_connected] column value.
	 * 
	 * @return     boolean
	 */
	public function getUserPermissionsFacebookConnected()
	{
		return $this->user_permissions_facebook_connected;
	}

	/**
	 * Get the [user_permissions_ffb_visible_profile] column value.
	 * 
	 * @return     boolean
	 */
	public function getUserPermissionsFfbVisibleProfile()
	{
		return $this->user_permissions_ffb_visible_profile;
	}

	/**
	 * Get the [user_permissions_pictory_visible_profile] column value.
	 * 
	 * @return     boolean
	 */
	public function getUserPermissionsPictoryVisibleProfile()
	{
		return $this->user_permissions_pictory_visible_profile;
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [user_permissions_ffb_mailservice_reminder] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsFfbMailserviceReminder($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_permissions_ffb_mailservice_reminder !== $v) {
			$this->user_permissions_ffb_mailservice_reminder = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER;
		}

		return $this;
	} // setUserPermissionsFfbMailserviceReminder()

	/**
	 * Set the value of [user_permissions_ffb_mailservice_info] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsFfbMailserviceInfo($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_permissions_ffb_mailservice_info !== $v) {
			$this->user_permissions_ffb_mailservice_info = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO;
		}

		return $this;
	} // setUserPermissionsFfbMailserviceInfo()

	/**
	 * Set the value of [user_permissions_ffb_facebook] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsFfbFacebook($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_permissions_ffb_facebook !== $v) {
			$this->user_permissions_ffb_facebook = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_FFB_FACEBOOK;
		}

		return $this;
	} // setUserPermissionsFfbFacebook()

	/**
	 * Set the value of [user_permissions_pictory_facebook] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsPictoryFacebook($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_permissions_pictory_facebook !== $v) {
			$this->user_permissions_pictory_facebook = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_FACEBOOK;
		}

		return $this;
	} // setUserPermissionsPictoryFacebook()

	/**
	 * Set the value of [user_permissions_facebook_connected] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsFacebookConnected($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->user_permissions_facebook_connected !== $v || $this->isNew()) {
			$this->user_permissions_facebook_connected = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_FACEBOOK_CONNECTED;
		}

		return $this;
	} // setUserPermissionsFacebookConnected()

	/**
	 * Set the value of [user_permissions_ffb_visible_profile] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsFfbVisibleProfile($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->user_permissions_ffb_visible_profile !== $v || $this->isNew()) {
			$this->user_permissions_ffb_visible_profile = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_FFB_VISIBLE_PROFILE;
		}

		return $this;
	} // setUserPermissionsFfbVisibleProfile()

	/**
	 * Set the value of [user_permissions_pictory_visible_profile] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WebUserPermissions The current object (for fluent API support)
	 */
	public function setUserPermissionsPictoryVisibleProfile($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->user_permissions_pictory_visible_profile !== $v || $this->isNew()) {
			$this->user_permissions_pictory_visible_profile = $v;
			$this->modifiedColumns[] = WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_VISIBLE_PROFILE;
		}

		return $this;
	} // setUserPermissionsPictoryVisibleProfile()

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
			if ($this->user_permissions_facebook_connected !== false) {
				return false;
			}

			if ($this->user_permissions_ffb_visible_profile !== false) {
				return false;
			}

			if ($this->user_permissions_pictory_visible_profile !== false) {
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

			$this->user_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->user_permissions_ffb_mailservice_reminder = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->user_permissions_ffb_mailservice_info = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->user_permissions_ffb_facebook = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->user_permissions_pictory_facebook = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->user_permissions_facebook_connected = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->user_permissions_ffb_visible_profile = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->user_permissions_pictory_visible_profile = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = WebUserPermissionsPeer::NUM_COLUMNS - WebUserPermissionsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WebUserPermissions object", $e);
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

		if ($this->aWebUser !== null && $this->user_id !== $this->aWebUser->getUserId()) {
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
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(WebUserPermissionsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WebUserPermissionsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(WebUserPermissionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				WebUserPermissionsPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(WebUserPermissionsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				WebUserPermissionsPeer::addInstanceToPool($this);
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


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WebUserPermissionsPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += WebUserPermissionsPeer::doUpdate($this, $con);
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


			if (($retval = WebUserPermissionsPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(WebUserPermissionsPeer::DATABASE_NAME);

		if ($this->isColumnModified(WebUserPermissionsPeer::USER_ID)) $criteria->add(WebUserPermissionsPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER, $this->user_permissions_ffb_mailservice_reminder);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO, $this->user_permissions_ffb_mailservice_info);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_FACEBOOK)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_FACEBOOK, $this->user_permissions_ffb_facebook);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_FACEBOOK)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_FACEBOOK, $this->user_permissions_pictory_facebook);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_FACEBOOK_CONNECTED)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FACEBOOK_CONNECTED, $this->user_permissions_facebook_connected);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_VISIBLE_PROFILE)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_VISIBLE_PROFILE, $this->user_permissions_ffb_visible_profile);
		if ($this->isColumnModified(WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_VISIBLE_PROFILE)) $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_VISIBLE_PROFILE, $this->user_permissions_pictory_visible_profile);

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
		$criteria = new Criteria(WebUserPermissionsPeer::DATABASE_NAME);

		$criteria->add(WebUserPermissionsPeer::USER_ID, $this->user_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getUserId();
	}

	/**
	 * Generic method to set the primary key (user_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setUserId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of WebUserPermissions (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserId($this->user_id);

		$copyObj->setUserPermissionsFfbMailserviceReminder($this->user_permissions_ffb_mailservice_reminder);

		$copyObj->setUserPermissionsFfbMailserviceInfo($this->user_permissions_ffb_mailservice_info);

		$copyObj->setUserPermissionsFfbFacebook($this->user_permissions_ffb_facebook);

		$copyObj->setUserPermissionsPictoryFacebook($this->user_permissions_pictory_facebook);

		$copyObj->setUserPermissionsFacebookConnected($this->user_permissions_facebook_connected);

		$copyObj->setUserPermissionsFfbVisibleProfile($this->user_permissions_ffb_visible_profile);

		$copyObj->setUserPermissionsPictoryVisibleProfile($this->user_permissions_pictory_visible_profile);


		$copyObj->setNew(true);

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
	 * @return     WebUserPermissions Clone of current object.
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
	 * @return     WebUserPermissionsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WebUserPermissionsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     WebUserPermissions The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUser(WebUser $v = null)
	{
		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getUserId());
		}

		$this->aWebUser = $v;

		// Add binding for other direction of this 1:1 relationship.
		if ($v !== null) {
			$v->setWebUserPermissions($this);
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
		if ($this->aWebUser === null && ($this->user_id !== null)) {
			$this->aWebUser = WebUserPeer::retrieveByPk($this->user_id);
			// Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
			$this->aWebUser->setWebUserPermissions($this);
		}
		return $this->aWebUser;
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

} // BaseWebUserPermissions
