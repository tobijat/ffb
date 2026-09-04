<?php

/**
 * Base class that represents a row from the 'ffb_game' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbGame extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbGamePeer
	 */
	protected static $peer;

	/**
	 * The value for the game_id field.
	 * @var        int
	 */
	protected $game_id;

	/**
	 * The value for the game_title field.
	 * Note: this column has a database default value of: 'Round'
	 * @var        string
	 */
	protected $game_title;

	/**
	 * The value for the game_visible field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $game_visible;

	/**
	 * The value for the game_archive field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $game_archive;

	/**
	 * The value for the game_countdown field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $game_countdown;

	/**
	 * The value for the game_status field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $game_status;

	/**
	 * The value for the game_description field.
	 * @var        string
	 */
	protected $game_description;

	/**
	 * The value for the game_symbol field.
	 * Note: this column has a database default value of: 'game_symbol_na.png'
	 * @var        string
	 */
	protected $game_symbol;

	/**
	 * @var        array WebUserDetails[] Collection to store aggregation of WebUserDetails objects.
	 */
	protected $collWebUserDetailss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWebUserDetailss.
	 */
	private $lastWebUserDetailsCriteria = null;

	/**
	 * @var        array FfbComments[] Collection to store aggregation of FfbComments objects.
	 */
	protected $collFfbCommentss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbCommentss.
	 */
	private $lastFfbCommentsCriteria = null;

	/**
	 * @var        array FfbPoll[] Collection to store aggregation of FfbPoll objects.
	 */
	protected $collFfbPolls;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPolls.
	 */
	private $lastFfbPollCriteria = null;

	/**
	 * @var        array FfbMatchround[] Collection to store aggregation of FfbMatchround objects.
	 */
	protected $collFfbMatchrounds;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbMatchrounds.
	 */
	private $lastFfbMatchroundCriteria = null;

	/**
	 * @var        array FfbNews[] Collection to store aggregation of FfbNews objects.
	 */
	protected $collFfbNewss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbNewss.
	 */
	private $lastFfbNewsCriteria = null;

	/**
	 * @var        array FfbUserscore[] Collection to store aggregation of FfbUserscore objects.
	 */
	protected $collFfbUserscores;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbUserscores.
	 */
	private $lastFfbUserscoreCriteria = null;

	/**
	 * @var        array FfbAdmin[] Collection to store aggregation of FfbAdmin objects.
	 */
	protected $collFfbAdmins;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbAdmins.
	 */
	private $lastFfbAdminCriteria = null;

	/**
	 * @var        array FfbOptions[] Collection to store aggregation of FfbOptions objects.
	 */
	protected $collFfbOptionss;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbOptionss.
	 */
	private $lastFfbOptionsCriteria = null;

	/**
	 * @var        array FfbAdsAllocation[] Collection to store aggregation of FfbAdsAllocation objects.
	 */
	protected $collFfbAdsAllocations;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbAdsAllocations.
	 */
	private $lastFfbAdsAllocationCriteria = null;

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
		$this->game_title = 'Round';
		$this->game_visible = false;
		$this->game_archive = false;
		$this->game_countdown = false;
		$this->game_status = false;
		$this->game_symbol = 'game_symbol_na.png';
	}

	/**
	 * Initializes internal state of BaseFfbGame object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [game_id] column value.
	 * 
	 * @return     int
	 */
	public function getGameId()
	{
		return $this->game_id;
	}

	/**
	 * Get the [game_title] column value.
	 * 
	 * @return     string
	 */
	public function getGameTitle()
	{
		return $this->game_title;
	}

	/**
	 * Get the [game_visible] column value.
	 * 
	 * @return     boolean
	 */
	public function getGameVisible()
	{
		return $this->game_visible;
	}

	/**
	 * Get the [game_archive] column value.
	 * 
	 * @return     boolean
	 */
	public function getGameArchive()
	{
		return $this->game_archive;
	}

	/**
	 * Get the [game_countdown] column value.
	 * 
	 * @return     boolean
	 */
	public function getGameCountdown()
	{
		return $this->game_countdown;
	}

	/**
	 * Get the [game_status] column value.
	 * 
	 * @return     boolean
	 */
	public function getGameStatus()
	{
		return $this->game_status;
	}

	/**
	 * Get the [game_description] column value.
	 * 
	 * @return     string
	 */
	public function getGameDescription()
	{
		return $this->game_description;
	}

	/**
	 * Get the [game_symbol] column value.
	 * 
	 * @return     string
	 */
	public function getGameSymbol()
	{
		return $this->game_symbol;
	}

	/**
	 * Set the value of [game_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->game_id !== $v) {
			$this->game_id = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_ID;
		}

		return $this;
	} // setGameId()

	/**
	 * Set the value of [game_title] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameTitle($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->game_title !== $v || $this->isNew()) {
			$this->game_title = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_TITLE;
		}

		return $this;
	} // setGameTitle()

	/**
	 * Set the value of [game_visible] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameVisible($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->game_visible !== $v || $this->isNew()) {
			$this->game_visible = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_VISIBLE;
		}

		return $this;
	} // setGameVisible()

	/**
	 * Set the value of [game_archive] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameArchive($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->game_archive !== $v || $this->isNew()) {
			$this->game_archive = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_ARCHIVE;
		}

		return $this;
	} // setGameArchive()

	/**
	 * Set the value of [game_countdown] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameCountdown($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->game_countdown !== $v || $this->isNew()) {
			$this->game_countdown = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_COUNTDOWN;
		}

		return $this;
	} // setGameCountdown()

	/**
	 * Set the value of [game_status] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameStatus($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->game_status !== $v || $this->isNew()) {
			$this->game_status = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_STATUS;
		}

		return $this;
	} // setGameStatus()

	/**
	 * Set the value of [game_description] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->game_description !== $v) {
			$this->game_description = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_DESCRIPTION;
		}

		return $this;
	} // setGameDescription()

	/**
	 * Set the value of [game_symbol] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbGame The current object (for fluent API support)
	 */
	public function setGameSymbol($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->game_symbol !== $v || $this->isNew()) {
			$this->game_symbol = $v;
			$this->modifiedColumns[] = FfbGamePeer::GAME_SYMBOL;
		}

		return $this;
	} // setGameSymbol()

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
			if ($this->game_title !== 'Round') {
				return false;
			}

			if ($this->game_visible !== false) {
				return false;
			}

			if ($this->game_archive !== false) {
				return false;
			}

			if ($this->game_countdown !== false) {
				return false;
			}

			if ($this->game_status !== false) {
				return false;
			}

			if ($this->game_symbol !== 'game_symbol_na.png') {
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

			$this->game_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->game_title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->game_visible = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
			$this->game_archive = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
			$this->game_countdown = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->game_status = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
			$this->game_description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->game_symbol = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbGame object", $e);
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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbGamePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collWebUserDetailss = null;
			$this->lastWebUserDetailsCriteria = null;

			$this->collFfbCommentss = null;
			$this->lastFfbCommentsCriteria = null;

			$this->collFfbPolls = null;
			$this->lastFfbPollCriteria = null;

			$this->collFfbMatchrounds = null;
			$this->lastFfbMatchroundCriteria = null;

			$this->collFfbNewss = null;
			$this->lastFfbNewsCriteria = null;

			$this->collFfbUserscores = null;
			$this->lastFfbUserscoreCriteria = null;

			$this->collFfbAdmins = null;
			$this->lastFfbAdminCriteria = null;

			$this->collFfbOptionss = null;
			$this->lastFfbOptionsCriteria = null;

			$this->collFfbAdsAllocations = null;
			$this->lastFfbAdsAllocationCriteria = null;

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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbGamePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbGamePeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = FfbGamePeer::GAME_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbGamePeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setGameId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbGamePeer::doUpdate($this, $con);
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

			if ($this->collFfbCommentss !== null) {
				foreach ($this->collFfbCommentss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPolls !== null) {
				foreach ($this->collFfbPolls as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbMatchrounds !== null) {
				foreach ($this->collFfbMatchrounds as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbNewss !== null) {
				foreach ($this->collFfbNewss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserscores !== null) {
				foreach ($this->collFfbUserscores as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbAdmins !== null) {
				foreach ($this->collFfbAdmins as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbOptionss !== null) {
				foreach ($this->collFfbOptionss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbAdsAllocations !== null) {
				foreach ($this->collFfbAdsAllocations as $referrerFK) {
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


			if (($retval = FfbGamePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collWebUserDetailss !== null) {
					foreach ($this->collWebUserDetailss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbCommentss !== null) {
					foreach ($this->collFfbCommentss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPolls !== null) {
					foreach ($this->collFfbPolls as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbMatchrounds !== null) {
					foreach ($this->collFfbMatchrounds as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbNewss !== null) {
					foreach ($this->collFfbNewss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserscores !== null) {
					foreach ($this->collFfbUserscores as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbAdmins !== null) {
					foreach ($this->collFfbAdmins as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbOptionss !== null) {
					foreach ($this->collFfbOptionss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbAdsAllocations !== null) {
					foreach ($this->collFfbAdsAllocations as $referrerFK) {
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
		$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbGamePeer::GAME_ID)) $criteria->add(FfbGamePeer::GAME_ID, $this->game_id);
		if ($this->isColumnModified(FfbGamePeer::GAME_TITLE)) $criteria->add(FfbGamePeer::GAME_TITLE, $this->game_title);
		if ($this->isColumnModified(FfbGamePeer::GAME_VISIBLE)) $criteria->add(FfbGamePeer::GAME_VISIBLE, $this->game_visible);
		if ($this->isColumnModified(FfbGamePeer::GAME_ARCHIVE)) $criteria->add(FfbGamePeer::GAME_ARCHIVE, $this->game_archive);
		if ($this->isColumnModified(FfbGamePeer::GAME_COUNTDOWN)) $criteria->add(FfbGamePeer::GAME_COUNTDOWN, $this->game_countdown);
		if ($this->isColumnModified(FfbGamePeer::GAME_STATUS)) $criteria->add(FfbGamePeer::GAME_STATUS, $this->game_status);
		if ($this->isColumnModified(FfbGamePeer::GAME_DESCRIPTION)) $criteria->add(FfbGamePeer::GAME_DESCRIPTION, $this->game_description);
		if ($this->isColumnModified(FfbGamePeer::GAME_SYMBOL)) $criteria->add(FfbGamePeer::GAME_SYMBOL, $this->game_symbol);

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
		$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);

		$criteria->add(FfbGamePeer::GAME_ID, $this->game_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getGameId();
	}

	/**
	 * Generic method to set the primary key (game_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setGameId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbGame (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setGameTitle($this->game_title);

		$copyObj->setGameVisible($this->game_visible);

		$copyObj->setGameArchive($this->game_archive);

		$copyObj->setGameCountdown($this->game_countdown);

		$copyObj->setGameStatus($this->game_status);

		$copyObj->setGameDescription($this->game_description);

		$copyObj->setGameSymbol($this->game_symbol);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getWebUserDetailss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebUserDetails($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbCommentss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbComments($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPolls() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPoll($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbMatchrounds() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbMatchround($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbNewss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbNews($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserscores() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserscore($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbAdmins() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbAdmin($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbOptionss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbOptions($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbAdsAllocations() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbAdsAllocation($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setGameId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbGame Clone of current object.
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
	 * @return     FfbGamePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbGamePeer();
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
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related WebUserDetailss from storage. If this FfbGame is new, it will return
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
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
			   $this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				WebUserDetailsPeer::addSelectColumns($criteria);
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

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
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
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

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				$count = WebUserDetailsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

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
			$l->setFfbGame($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getWebUserDetailssJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

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
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

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
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getWebUserDetailssJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

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
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related WebUserDetailss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getWebUserDetailssJoinFfbPlayer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebUserDetailss === null) {
			if ($this->isNew()) {
				$this->collWebUserDetailss = array();
			} else {

				$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbPlayer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->game_id);

			if (!isset($this->lastWebUserDetailsCriteria) || !$this->lastWebUserDetailsCriteria->equals($criteria)) {
				$this->collWebUserDetailss = WebUserDetailsPeer::doSelectJoinFfbPlayer($criteria, $con, $join_behavior);
			}
		}
		$this->lastWebUserDetailsCriteria = $criteria;

		return $this->collWebUserDetailss;
	}

	/**
	 * Clears out the collFfbCommentss collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbCommentss()
	 */
	public function clearFfbCommentss()
	{
		$this->collFfbCommentss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbCommentss collection (array).
	 *
	 * By default this just sets the collFfbCommentss collection to an empty array (like clearcollFfbCommentss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbCommentss()
	{
		$this->collFfbCommentss = array();
	}

	/**
	 * Gets an array of FfbComments objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbCommentss from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbComments[]
	 * @throws     PropelException
	 */
	public function getFfbCommentss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
			   $this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				FfbCommentsPeer::addSelectColumns($criteria);
				$this->collFfbCommentss = FfbCommentsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				FfbCommentsPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
					$this->collFfbCommentss = FfbCommentsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbCommentsCriteria = $criteria;
		return $this->collFfbCommentss;
	}

	/**
	 * Returns the number of related FfbComments objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbComments objects.
	 * @throws     PropelException
	 */
	public function countFfbCommentss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				$count = FfbCommentsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
					$count = FfbCommentsPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbCommentss);
				}
			} else {
				$count = count($this->collFfbCommentss);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbComments object to this object
	 * through the FfbComments foreign key attribute.
	 *
	 * @param      FfbComments $l FfbComments
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbComments(FfbComments $l)
	{
		if ($this->collFfbCommentss === null) {
			$this->initFfbCommentss();
		}
		if (!in_array($l, $this->collFfbCommentss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbCommentss, $l);
			$l->setFfbGame($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbCommentssJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
				$this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbCommentsCriteria = $criteria;

		return $this->collFfbCommentss;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbCommentssJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
				$this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbCommentsCriteria = $criteria;

		return $this->collFfbCommentss;
	}

	/**
	 * Clears out the collFfbPolls collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPolls()
	 */
	public function clearFfbPolls()
	{
		$this->collFfbPolls = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPolls collection (array).
	 *
	 * By default this just sets the collFfbPolls collection to an empty array (like clearcollFfbPolls());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPolls()
	{
		$this->collFfbPolls = array();
	}

	/**
	 * Gets an array of FfbPoll objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbPolls from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbPoll[]
	 * @throws     PropelException
	 */
	public function getFfbPolls($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPolls === null) {
			if ($this->isNew()) {
			   $this->collFfbPolls = array();
			} else {

				$criteria->add(FfbPollPeer::POLL_GAME_ID, $this->game_id);

				FfbPollPeer::addSelectColumns($criteria);
				$this->collFfbPolls = FfbPollPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPollPeer::POLL_GAME_ID, $this->game_id);

				FfbPollPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbPollCriteria) || !$this->lastFfbPollCriteria->equals($criteria)) {
					$this->collFfbPolls = FfbPollPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbPollCriteria = $criteria;
		return $this->collFfbPolls;
	}

	/**
	 * Returns the number of related FfbPoll objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPoll objects.
	 * @throws     PropelException
	 */
	public function countFfbPolls(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbPolls === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbPollPeer::POLL_GAME_ID, $this->game_id);

				$count = FfbPollPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPollPeer::POLL_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbPollCriteria) || !$this->lastFfbPollCriteria->equals($criteria)) {
					$count = FfbPollPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbPolls);
				}
			} else {
				$count = count($this->collFfbPolls);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbPoll object to this object
	 * through the FfbPoll foreign key attribute.
	 *
	 * @param      FfbPoll $l FfbPoll
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPoll(FfbPoll $l)
	{
		if ($this->collFfbPolls === null) {
			$this->initFfbPolls();
		}
		if (!in_array($l, $this->collFfbPolls, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbPolls, $l);
			$l->setFfbGame($this);
		}
	}

	/**
	 * Clears out the collFfbMatchrounds collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbMatchrounds()
	 */
	public function clearFfbMatchrounds()
	{
		$this->collFfbMatchrounds = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbMatchrounds collection (array).
	 *
	 * By default this just sets the collFfbMatchrounds collection to an empty array (like clearcollFfbMatchrounds());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbMatchrounds()
	{
		$this->collFfbMatchrounds = array();
	}

	/**
	 * Gets an array of FfbMatchround objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbMatchrounds from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbMatchround[]
	 * @throws     PropelException
	 */
	public function getFfbMatchrounds($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbMatchrounds === null) {
			if ($this->isNew()) {
			   $this->collFfbMatchrounds = array();
			} else {

				$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->game_id);

				FfbMatchroundPeer::addSelectColumns($criteria);
				$this->collFfbMatchrounds = FfbMatchroundPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->game_id);

				FfbMatchroundPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbMatchroundCriteria) || !$this->lastFfbMatchroundCriteria->equals($criteria)) {
					$this->collFfbMatchrounds = FfbMatchroundPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbMatchroundCriteria = $criteria;
		return $this->collFfbMatchrounds;
	}

	/**
	 * Returns the number of related FfbMatchround objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbMatchround objects.
	 * @throws     PropelException
	 */
	public function countFfbMatchrounds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbMatchrounds === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->game_id);

				$count = FfbMatchroundPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbMatchroundCriteria) || !$this->lastFfbMatchroundCriteria->equals($criteria)) {
					$count = FfbMatchroundPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbMatchrounds);
				}
			} else {
				$count = count($this->collFfbMatchrounds);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbMatchround object to this object
	 * through the FfbMatchround foreign key attribute.
	 *
	 * @param      FfbMatchround $l FfbMatchround
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbMatchround(FfbMatchround $l)
	{
		if ($this->collFfbMatchrounds === null) {
			$this->initFfbMatchrounds();
		}
		if (!in_array($l, $this->collFfbMatchrounds, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbMatchrounds, $l);
			$l->setFfbGame($this);
		}
	}

	/**
	 * Clears out the collFfbNewss collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbNewss()
	 */
	public function clearFfbNewss()
	{
		$this->collFfbNewss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbNewss collection (array).
	 *
	 * By default this just sets the collFfbNewss collection to an empty array (like clearcollFfbNewss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbNewss()
	{
		$this->collFfbNewss = array();
	}

	/**
	 * Gets an array of FfbNews objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbNewss from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbNews[]
	 * @throws     PropelException
	 */
	public function getFfbNewss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbNewss === null) {
			if ($this->isNew()) {
			   $this->collFfbNewss = array();
			} else {

				$criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->game_id);

				FfbNewsPeer::addSelectColumns($criteria);
				$this->collFfbNewss = FfbNewsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->game_id);

				FfbNewsPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbNewsCriteria) || !$this->lastFfbNewsCriteria->equals($criteria)) {
					$this->collFfbNewss = FfbNewsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbNewsCriteria = $criteria;
		return $this->collFfbNewss;
	}

	/**
	 * Returns the number of related FfbNews objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbNews objects.
	 * @throws     PropelException
	 */
	public function countFfbNewss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbNewss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->game_id);

				$count = FfbNewsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbNewsCriteria) || !$this->lastFfbNewsCriteria->equals($criteria)) {
					$count = FfbNewsPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbNewss);
				}
			} else {
				$count = count($this->collFfbNewss);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbNews object to this object
	 * through the FfbNews foreign key attribute.
	 *
	 * @param      FfbNews $l FfbNews
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbNews(FfbNews $l)
	{
		if ($this->collFfbNewss === null) {
			$this->initFfbNewss();
		}
		if (!in_array($l, $this->collFfbNewss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbNewss, $l);
			$l->setFfbGame($this);
		}
	}

	/**
	 * Clears out the collFfbUserscores collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserscores()
	 */
	public function clearFfbUserscores()
	{
		$this->collFfbUserscores = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserscores collection (array).
	 *
	 * By default this just sets the collFfbUserscores collection to an empty array (like clearcollFfbUserscores());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserscores()
	{
		$this->collFfbUserscores = array();
	}

	/**
	 * Gets an array of FfbUserscore objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbUserscores from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbUserscore[]
	 * @throws     PropelException
	 */
	public function getFfbUserscores($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserscores === null) {
			if ($this->isNew()) {
			   $this->collFfbUserscores = array();
			} else {

				$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

				FfbUserscorePeer::addSelectColumns($criteria);
				$this->collFfbUserscores = FfbUserscorePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

				FfbUserscorePeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbUserscoreCriteria) || !$this->lastFfbUserscoreCriteria->equals($criteria)) {
					$this->collFfbUserscores = FfbUserscorePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbUserscoreCriteria = $criteria;
		return $this->collFfbUserscores;
	}

	/**
	 * Returns the number of related FfbUserscore objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserscore objects.
	 * @throws     PropelException
	 */
	public function countFfbUserscores(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbUserscores === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

				$count = FfbUserscorePeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbUserscoreCriteria) || !$this->lastFfbUserscoreCriteria->equals($criteria)) {
					$count = FfbUserscorePeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbUserscores);
				}
			} else {
				$count = count($this->collFfbUserscores);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbUserscore object to this object
	 * through the FfbUserscore foreign key attribute.
	 *
	 * @param      FfbUserscore $l FfbUserscore
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserscore(FfbUserscore $l)
	{
		if ($this->collFfbUserscores === null) {
			$this->initFfbUserscores();
		}
		if (!in_array($l, $this->collFfbUserscores, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbUserscores, $l);
			$l->setFfbGame($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbUserscores from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbUserscoresJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserscores === null) {
			if ($this->isNew()) {
				$this->collFfbUserscores = array();
			} else {

				$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

				$this->collFfbUserscores = FfbUserscorePeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbUserscoreCriteria) || !$this->lastFfbUserscoreCriteria->equals($criteria)) {
				$this->collFfbUserscores = FfbUserscorePeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserscoreCriteria = $criteria;

		return $this->collFfbUserscores;
	}

	/**
	 * Clears out the collFfbAdmins collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbAdmins()
	 */
	public function clearFfbAdmins()
	{
		$this->collFfbAdmins = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbAdmins collection (array).
	 *
	 * By default this just sets the collFfbAdmins collection to an empty array (like clearcollFfbAdmins());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbAdmins()
	{
		$this->collFfbAdmins = array();
	}

	/**
	 * Gets an array of FfbAdmin objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbAdmins from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbAdmin[]
	 * @throws     PropelException
	 */
	public function getFfbAdmins($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdmins === null) {
			if ($this->isNew()) {
			   $this->collFfbAdmins = array();
			} else {

				$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

				FfbAdminPeer::addSelectColumns($criteria);
				$this->collFfbAdmins = FfbAdminPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

				FfbAdminPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbAdminCriteria) || !$this->lastFfbAdminCriteria->equals($criteria)) {
					$this->collFfbAdmins = FfbAdminPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbAdminCriteria = $criteria;
		return $this->collFfbAdmins;
	}

	/**
	 * Returns the number of related FfbAdmin objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbAdmin objects.
	 * @throws     PropelException
	 */
	public function countFfbAdmins(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbAdmins === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

				$count = FfbAdminPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbAdminCriteria) || !$this->lastFfbAdminCriteria->equals($criteria)) {
					$count = FfbAdminPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbAdmins);
				}
			} else {
				$count = count($this->collFfbAdmins);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbAdmin object to this object
	 * through the FfbAdmin foreign key attribute.
	 *
	 * @param      FfbAdmin $l FfbAdmin
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbAdmin(FfbAdmin $l)
	{
		if ($this->collFfbAdmins === null) {
			$this->initFfbAdmins();
		}
		if (!in_array($l, $this->collFfbAdmins, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbAdmins, $l);
			$l->setFfbGame($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbAdmins from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbAdminsJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdmins === null) {
			if ($this->isNew()) {
				$this->collFfbAdmins = array();
			} else {

				$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

				$this->collFfbAdmins = FfbAdminPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbAdminCriteria) || !$this->lastFfbAdminCriteria->equals($criteria)) {
				$this->collFfbAdmins = FfbAdminPeer::doSelectJoinWebUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbAdminCriteria = $criteria;

		return $this->collFfbAdmins;
	}

	/**
	 * Clears out the collFfbOptionss collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbOptionss()
	 */
	public function clearFfbOptionss()
	{
		$this->collFfbOptionss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbOptionss collection (array).
	 *
	 * By default this just sets the collFfbOptionss collection to an empty array (like clearcollFfbOptionss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbOptionss()
	{
		$this->collFfbOptionss = array();
	}

	/**
	 * Gets an array of FfbOptions objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbOptionss from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbOptions[]
	 * @throws     PropelException
	 */
	public function getFfbOptionss($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbOptionss === null) {
			if ($this->isNew()) {
			   $this->collFfbOptionss = array();
			} else {

				$criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->game_id);

				FfbOptionsPeer::addSelectColumns($criteria);
				$this->collFfbOptionss = FfbOptionsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->game_id);

				FfbOptionsPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbOptionsCriteria) || !$this->lastFfbOptionsCriteria->equals($criteria)) {
					$this->collFfbOptionss = FfbOptionsPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbOptionsCriteria = $criteria;
		return $this->collFfbOptionss;
	}

	/**
	 * Returns the number of related FfbOptions objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbOptions objects.
	 * @throws     PropelException
	 */
	public function countFfbOptionss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbOptionss === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->game_id);

				$count = FfbOptionsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbOptionsCriteria) || !$this->lastFfbOptionsCriteria->equals($criteria)) {
					$count = FfbOptionsPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbOptionss);
				}
			} else {
				$count = count($this->collFfbOptionss);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbOptions object to this object
	 * through the FfbOptions foreign key attribute.
	 *
	 * @param      FfbOptions $l FfbOptions
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbOptions(FfbOptions $l)
	{
		if ($this->collFfbOptionss === null) {
			$this->initFfbOptionss();
		}
		if (!in_array($l, $this->collFfbOptionss, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbOptionss, $l);
			$l->setFfbGame($this);
		}
	}

	/**
	 * Clears out the collFfbAdsAllocations collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbAdsAllocations()
	 */
	public function clearFfbAdsAllocations()
	{
		$this->collFfbAdsAllocations = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbAdsAllocations collection (array).
	 *
	 * By default this just sets the collFfbAdsAllocations collection to an empty array (like clearcollFfbAdsAllocations());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbAdsAllocations()
	{
		$this->collFfbAdsAllocations = array();
	}

	/**
	 * Gets an array of FfbAdsAllocation objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this FfbGame has previously been saved, it will retrieve
	 * related FfbAdsAllocations from storage. If this FfbGame is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbAdsAllocation[]
	 * @throws     PropelException
	 */
	public function getFfbAdsAllocations($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdsAllocations === null) {
			if ($this->isNew()) {
			   $this->collFfbAdsAllocations = array();
			} else {

				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				FfbAdsAllocationPeer::addSelectColumns($criteria);
				$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				FfbAdsAllocationPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbAdsAllocationCriteria) || !$this->lastFfbAdsAllocationCriteria->equals($criteria)) {
					$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbAdsAllocationCriteria = $criteria;
		return $this->collFfbAdsAllocations;
	}

	/**
	 * Returns the number of related FfbAdsAllocation objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbAdsAllocation objects.
	 * @throws     PropelException
	 */
	public function countFfbAdsAllocations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbAdsAllocations === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				$count = FfbAdsAllocationPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				if (!isset($this->lastFfbAdsAllocationCriteria) || !$this->lastFfbAdsAllocationCriteria->equals($criteria)) {
					$count = FfbAdsAllocationPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbAdsAllocations);
				}
			} else {
				$count = count($this->collFfbAdsAllocations);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a FfbAdsAllocation object to this object
	 * through the FfbAdsAllocation foreign key attribute.
	 *
	 * @param      FfbAdsAllocation $l FfbAdsAllocation
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbAdsAllocation(FfbAdsAllocation $l)
	{
		if ($this->collFfbAdsAllocations === null) {
			$this->initFfbAdsAllocations();
		}
		if (!in_array($l, $this->collFfbAdsAllocations, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbAdsAllocations, $l);
			$l->setFfbGame($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbAdsAllocations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbAdsAllocationsJoinFfbAds($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdsAllocations === null) {
			if ($this->isNew()) {
				$this->collFfbAdsAllocations = array();
			} else {

				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelectJoinFfbAds($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbAdsAllocationCriteria) || !$this->lastFfbAdsAllocationCriteria->equals($criteria)) {
				$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelectJoinFfbAds($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbAdsAllocationCriteria = $criteria;

		return $this->collFfbAdsAllocations;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbGame is new, it will return
	 * an empty collection; or if this FfbGame has previously
	 * been saved, it will retrieve related FfbAdsAllocations from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbGame.
	 */
	public function getFfbAdsAllocationsJoinFfbAdsSlot($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdsAllocations === null) {
			if ($this->isNew()) {
				$this->collFfbAdsAllocations = array();
			} else {

				$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

				$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelectJoinFfbAdsSlot($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $this->game_id);

			if (!isset($this->lastFfbAdsAllocationCriteria) || !$this->lastFfbAdsAllocationCriteria->equals($criteria)) {
				$this->collFfbAdsAllocations = FfbAdsAllocationPeer::doSelectJoinFfbAdsSlot($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbAdsAllocationCriteria = $criteria;

		return $this->collFfbAdsAllocations;
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
			if ($this->collFfbCommentss) {
				foreach ((array) $this->collFfbCommentss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPolls) {
				foreach ((array) $this->collFfbPolls as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbMatchrounds) {
				foreach ((array) $this->collFfbMatchrounds as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbNewss) {
				foreach ((array) $this->collFfbNewss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserscores) {
				foreach ((array) $this->collFfbUserscores as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbAdmins) {
				foreach ((array) $this->collFfbAdmins as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbOptionss) {
				foreach ((array) $this->collFfbOptionss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbAdsAllocations) {
				foreach ((array) $this->collFfbAdsAllocations as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collWebUserDetailss = null;
		$this->collFfbCommentss = null;
		$this->collFfbPolls = null;
		$this->collFfbMatchrounds = null;
		$this->collFfbNewss = null;
		$this->collFfbUserscores = null;
		$this->collFfbAdmins = null;
		$this->collFfbOptionss = null;
		$this->collFfbAdsAllocations = null;
	}

} // BaseFfbGame
