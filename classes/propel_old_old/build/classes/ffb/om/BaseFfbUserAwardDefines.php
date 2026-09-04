<?php

/**
 * Base class that represents a row from the 'ffb_user_award_defines' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbUserAwardDefines extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbUserAwardDefinesPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_award_defines_id field.
	 * @var        int
	 */
	protected $user_award_defines_id;

	/**
	 * The value for the user_award_defines_award_id field.
	 * @var        int
	 */
	protected $user_award_defines_award_id;

	/**
	 * The value for the user_award_defines_rank field.
	 * @var        int
	 */
	protected $user_award_defines_rank;

	/**
	 * The value for the user_award_defines_rank_name field.
	 * @var        string
	 */
	protected $user_award_defines_rank_name;

	/**
	 * The value for the user_award_defines_aim field.
	 * @var        string
	 */
	protected $user_award_defines_aim;

	/**
	 * The value for the user_award_defines_aim_dbtable field.
	 * @var        string
	 */
	protected $user_award_defines_aim_dbtable;

	/**
	 * The value for the user_award_defines_aim_operator field.
	 * @var        string
	 */
	protected $user_award_defines_aim_operator;

	/**
	 * The value for the user_award_defines_aim_count field.
	 * @var        int
	 */
	protected $user_award_defines_aim_count;

	/**
	 * The value for the user_award_defines_aim_automatic field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $user_award_defines_aim_automatic;

	/**
	 * The value for the user_award_defines_aim_function_name field.
	 * @var        string
	 */
	protected $user_award_defines_aim_function_name;

	/**
	 * The value for the user_award_defines_image field.
	 * @var        string
	 */
	protected $user_award_defines_image;

	/**
	 * The value for the user_award_defines_facebook_description field.
	 * @var        string
	 */
	protected $user_award_defines_facebook_description;

	/**
	 * The value for the user_award_defines_description field.
	 * @var        string
	 */
	protected $user_award_defines_description;

	/**
	 * @var        FfbUserAward
	 */
	protected $aFfbUserAward;

	/**
	 * @var        array FfbUserAwardFinished[] Collection to store aggregation of FfbUserAwardFinished objects.
	 */
	protected $collFfbUserAwardFinisheds;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbUserAwardFinisheds.
	 */
	private $lastFfbUserAwardFinishedCriteria = null;

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
		$this->user_award_defines_aim_automatic = true;
	}

	/**
	 * Initializes internal state of BaseFfbUserAwardDefines object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [user_award_defines_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserAwardDefinesId()
	{
		return $this->user_award_defines_id;
	}

	/**
	 * Get the [user_award_defines_award_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserAwardDefinesAwardId()
	{
		return $this->user_award_defines_award_id;
	}

	/**
	 * Get the [user_award_defines_rank] column value.
	 * 
	 * @return     int
	 */
	public function getUserAwardDefinesRank()
	{
		return $this->user_award_defines_rank;
	}

	/**
	 * Get the [user_award_defines_rank_name] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesRankName()
	{
		return $this->user_award_defines_rank_name;
	}

	/**
	 * Get the [user_award_defines_aim] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesAim()
	{
		return $this->user_award_defines_aim;
	}

	/**
	 * Get the [user_award_defines_aim_dbtable] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesAimDbtable()
	{
		return $this->user_award_defines_aim_dbtable;
	}

	/**
	 * Get the [user_award_defines_aim_operator] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesAimOperator()
	{
		return $this->user_award_defines_aim_operator;
	}

	/**
	 * Get the [user_award_defines_aim_count] column value.
	 * 
	 * @return     int
	 */
	public function getUserAwardDefinesAimCount()
	{
		return $this->user_award_defines_aim_count;
	}

	/**
	 * Get the [user_award_defines_aim_automatic] column value.
	 * 
	 * @return     boolean
	 */
	public function getUserAwardDefinesAimAutomatic()
	{
		return $this->user_award_defines_aim_automatic;
	}

	/**
	 * Get the [user_award_defines_aim_function_name] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesAimFunctionName()
	{
		return $this->user_award_defines_aim_function_name;
	}

	/**
	 * Get the [user_award_defines_image] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesImage()
	{
		return $this->user_award_defines_image;
	}

	/**
	 * Get the [user_award_defines_facebook_description] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesFacebookDescription()
	{
		return $this->user_award_defines_facebook_description;
	}

	/**
	 * Get the [user_award_defines_description] column value.
	 * 
	 * @return     string
	 */
	public function getUserAwardDefinesDescription()
	{
		return $this->user_award_defines_description;
	}

	/**
	 * Set the value of [user_award_defines_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_award_defines_id !== $v) {
			$this->user_award_defines_id = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID;
		}

		return $this;
	} // setUserAwardDefinesId()

	/**
	 * Set the value of [user_award_defines_award_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAwardId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_award_defines_award_id !== $v) {
			$this->user_award_defines_award_id = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID;
		}

		if ($this->aFfbUserAward !== null && $this->aFfbUserAward->getUserAwardId() !== $v) {
			$this->aFfbUserAward = null;
		}

		return $this;
	} // setUserAwardDefinesAwardId()

	/**
	 * Set the value of [user_award_defines_rank] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesRank($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_award_defines_rank !== $v) {
			$this->user_award_defines_rank = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK;
		}

		return $this;
	} // setUserAwardDefinesRank()

	/**
	 * Set the value of [user_award_defines_rank_name] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesRankName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_rank_name !== $v) {
			$this->user_award_defines_rank_name = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME;
		}

		return $this;
	} // setUserAwardDefinesRankName()

	/**
	 * Set the value of [user_award_defines_aim] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAim($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_aim !== $v) {
			$this->user_award_defines_aim = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM;
		}

		return $this;
	} // setUserAwardDefinesAim()

	/**
	 * Set the value of [user_award_defines_aim_dbtable] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAimDbtable($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_aim_dbtable !== $v) {
			$this->user_award_defines_aim_dbtable = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_DBTABLE;
		}

		return $this;
	} // setUserAwardDefinesAimDbtable()

	/**
	 * Set the value of [user_award_defines_aim_operator] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAimOperator($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_aim_operator !== $v) {
			$this->user_award_defines_aim_operator = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_OPERATOR;
		}

		return $this;
	} // setUserAwardDefinesAimOperator()

	/**
	 * Set the value of [user_award_defines_aim_count] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAimCount($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_award_defines_aim_count !== $v) {
			$this->user_award_defines_aim_count = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT;
		}

		return $this;
	} // setUserAwardDefinesAimCount()

	/**
	 * Set the value of [user_award_defines_aim_automatic] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAimAutomatic($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->user_award_defines_aim_automatic !== $v || $this->isNew()) {
			$this->user_award_defines_aim_automatic = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_AUTOMATIC;
		}

		return $this;
	} // setUserAwardDefinesAimAutomatic()

	/**
	 * Set the value of [user_award_defines_aim_function_name] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesAimFunctionName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_aim_function_name !== $v) {
			$this->user_award_defines_aim_function_name = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_FUNCTION_NAME;
		}

		return $this;
	} // setUserAwardDefinesAimFunctionName()

	/**
	 * Set the value of [user_award_defines_image] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesImage($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_image !== $v) {
			$this->user_award_defines_image = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_IMAGE;
		}

		return $this;
	} // setUserAwardDefinesImage()

	/**
	 * Set the value of [user_award_defines_facebook_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesFacebookDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_facebook_description !== $v) {
			$this->user_award_defines_facebook_description = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_FACEBOOK_DESCRIPTION;
		}

		return $this;
	} // setUserAwardDefinesFacebookDescription()

	/**
	 * Set the value of [user_award_defines_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 */
	public function setUserAwardDefinesDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_award_defines_description !== $v) {
			$this->user_award_defines_description = $v;
			$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_DESCRIPTION;
		}

		return $this;
	} // setUserAwardDefinesDescription()

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
			if ($this->user_award_defines_aim_automatic !== true) {
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

			$this->user_award_defines_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->user_award_defines_award_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->user_award_defines_rank = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->user_award_defines_rank_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->user_award_defines_aim = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->user_award_defines_aim_dbtable = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->user_award_defines_aim_operator = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->user_award_defines_aim_count = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->user_award_defines_aim_automatic = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->user_award_defines_aim_function_name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->user_award_defines_image = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->user_award_defines_facebook_description = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->user_award_defines_description = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = FfbUserAwardDefinesPeer::NUM_COLUMNS - FfbUserAwardDefinesPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbUserAwardDefines object", $e);
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

		if ($this->aFfbUserAward !== null && $this->user_award_defines_award_id !== $this->aFfbUserAward->getUserAwardId()) {
			$this->aFfbUserAward = null;
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
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbUserAwardDefinesPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbUserAward = null;
			$this->collFfbUserAwardFinisheds = null;
			$this->lastFfbUserAwardFinishedCriteria = null;

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
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbUserAwardDefinesPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbUserAwardDefinesPeer::addInstanceToPool($this);
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

			if ($this->aFfbUserAward !== null) {
				if ($this->aFfbUserAward->isModified() || $this->aFfbUserAward->isNew()) {
					$affectedRows += $this->aFfbUserAward->save($con);
				}
				$this->setFfbUserAward($this->aFfbUserAward);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbUserAwardDefinesPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setUserAwardDefinesId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbUserAwardDefinesPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collFfbUserAwardFinisheds !== null) {
				foreach ($this->collFfbUserAwardFinisheds as $referrerFK) {
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

			if ($this->aFfbUserAward !== null) {
				if (!$this->aFfbUserAward->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbUserAward->getValidationFailures());
				}
			}


			if (($retval = FfbUserAwardDefinesPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbUserAwardFinisheds !== null) {
					foreach ($this->collFfbUserAwardFinisheds as $referrerFK) {
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
		$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $this->user_award_defines_id);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $this->user_award_defines_award_id);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK, $this->user_award_defines_rank);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME, $this->user_award_defines_rank_name);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM, $this->user_award_defines_aim);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_DBTABLE)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_DBTABLE, $this->user_award_defines_aim_dbtable);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_OPERATOR)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_OPERATOR, $this->user_award_defines_aim_operator);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT, $this->user_award_defines_aim_count);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_AUTOMATIC)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_AUTOMATIC, $this->user_award_defines_aim_automatic);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_FUNCTION_NAME)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_FUNCTION_NAME, $this->user_award_defines_aim_function_name);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_IMAGE)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_IMAGE, $this->user_award_defines_image);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_FACEBOOK_DESCRIPTION)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_FACEBOOK_DESCRIPTION, $this->user_award_defines_facebook_description);
		if ($this->isColumnModified(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_DESCRIPTION)) $criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_DESCRIPTION, $this->user_award_defines_description);

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
		$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);

		$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $this->user_award_defines_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getUserAwardDefinesId();
	}

	/**
	 * Generic method to set the primary key (user_award_defines_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setUserAwardDefinesId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbUserAwardDefines (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserAwardDefinesAwardId($this->user_award_defines_award_id);

		$copyObj->setUserAwardDefinesRank($this->user_award_defines_rank);

		$copyObj->setUserAwardDefinesRankName($this->user_award_defines_rank_name);

		$copyObj->setUserAwardDefinesAim($this->user_award_defines_aim);

		$copyObj->setUserAwardDefinesAimDbtable($this->user_award_defines_aim_dbtable);

		$copyObj->setUserAwardDefinesAimOperator($this->user_award_defines_aim_operator);

		$copyObj->setUserAwardDefinesAimCount($this->user_award_defines_aim_count);

		$copyObj->setUserAwardDefinesAimAutomatic($this->user_award_defines_aim_automatic);

		$copyObj->setUserAwardDefinesAimFunctionName($this->user_award_defines_aim_function_name);

		$copyObj->setUserAwardDefinesImage($this->user_award_defines_image);

		$copyObj->setUserAwardDefinesFacebookDescription($this->user_award_defines_facebook_description);

		$copyObj->setUserAwardDefinesDescription($this->user_award_defines_description);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbUserAwardFinisheds() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserAwardFinished($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setUserAwardDefinesId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbUserAwardDefines Clone of current object.
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
	 * @return     FfbUserAwardDefinesPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbUserAwardDefinesPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbUserAward object.
	 *
	 * @param      FfbUserAward $v
	 * @return     FfbUserAwardDefines The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbUserAward(FfbUserAward $v = null)
	{
		if ($v === null) {
			$this->setUserAwardDefinesAwardId(NULL);
		} else {
			$this->setUserAwardDefinesAwardId($v->getUserAwardId());
		}

		$this->aFfbUserAward = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbUserAward object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserAwardDefines($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbUserAward object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbUserAward The associated FfbUserAward object.
	 * @throws     PropelException
	 */
	public function getFfbUserAward(PropelPDO $con = null)
	{
		if ($this->aFfbUserAward === null && ($this->user_award_defines_award_id !== null)) {
			$this->aFfbUserAward = FfbUserAwardPeer::retrieveByPk($this->user_award_defines_award_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbUserAward->addFfbUserAwardDefiness($this);
			 */
		}
		return $this->aFfbUserAward;
	}

	/**
	 * Clears out the collFfbUserAwardFinisheds collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserAwardFinisheds()
	 */
	public function clearFfbUserAwardFinisheds()
	{
		$this->collFfbUserAwardFinisheds = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserAwardFinisheds collection (array).
	 *
	 * By default this just sets the collFfbUserAwardFinisheds collection to an empty array (like clearcollFfbUserAwardFinisheds());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserAwardFinisheds()
	{
		$this->collFfbUserAwardFinisheds = array();
	}

	/**
	 * Gets an array of FfbUserAwardFinished objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbUserAwardDefines has previously been saved, it will retrieve
	 * related FfbUserAwardFinisheds from storage. If this FfbUserAwardDefines is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbUserAwardFinished[]
	 * @throws     PropelException
	 */
	public function getFfbUserAwardFinisheds($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserAwardFinisheds === null) {
			if ($this->isNew()) {
			   $this->collFfbUserAwardFinisheds = array();
			} else {

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

				FfbUserAwardFinishedPeer::addSelectColumns($criteria);
				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

				FfbUserAwardFinishedPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbUserAwardFinishedCriteria) || !$this->lastFfbUserAwardFinishedCriteria->equals($criteria)) {
					$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbUserAwardFinishedCriteria = $criteria;
		return $this->collFfbUserAwardFinisheds;
	}

	/**
	 * Returns the number of related FfbUserAwardFinished objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserAwardFinished objects.
	 * @throws     PropelException
	 */
	public function countFfbUserAwardFinisheds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbUserAwardFinisheds === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

				$count = FfbUserAwardFinishedPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

				if (!isset($this->lastFfbUserAwardFinishedCriteria) || !$this->lastFfbUserAwardFinishedCriteria->equals($criteria)) {
					$count = FfbUserAwardFinishedPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbUserAwardFinisheds);
				}
			} else {
				$count = count($this->collFfbUserAwardFinisheds);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbUserAwardFinished object to this object
	 * through the FfbUserAwardFinished foreign key attribute.
	 *
	 * @param      FfbUserAwardFinished $l FfbUserAwardFinished
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserAwardFinished(FfbUserAwardFinished $l)
	{
		if ($this->collFfbUserAwardFinisheds === null) {
			$this->initFfbUserAwardFinisheds();
		}
		if (!in_array($l, $this->collFfbUserAwardFinisheds, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbUserAwardFinisheds, $l);
			$l->setFfbUserAwardDefines($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbUserAwardDefines is new, it will return
	 * an empty collection; or if this FfbUserAwardDefines has previously
	 * been saved, it will retrieve related FfbUserAwardFinisheds from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbUserAwardDefines.
	 */
	public function getFfbUserAwardFinishedsJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserAwardFinisheds === null) {
			if ($this->isNew()) {
				$this->collFfbUserAwardFinisheds = array();
			} else {

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $this->user_award_defines_id);

			if (!isset($this->lastFfbUserAwardFinishedCriteria) || !$this->lastFfbUserAwardFinishedCriteria->equals($criteria)) {
				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserAwardFinishedCriteria = $criteria;

		return $this->collFfbUserAwardFinisheds;
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
			if ($this->collFfbUserAwardFinisheds) {
				foreach ((array) $this->collFfbUserAwardFinisheds as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collFfbUserAwardFinisheds = null;
			$this->aFfbUserAward = null;
	}

} // BaseFfbUserAwardDefines
