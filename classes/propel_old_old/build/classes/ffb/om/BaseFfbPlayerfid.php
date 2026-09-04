<?php

/**
 * Base class that represents a row from the 'ffb_playerfid' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPlayerfid extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPlayerfidPeer
	 */
	protected static $peer;

	/**
	 * The value for the playerfid_id field.
	 * @var        int
	 */
	protected $playerfid_id;

	/**
	 * The value for the playerfid_playerteam_id field.
	 * @var        int
	 */
	protected $playerfid_playerteam_id;

	/**
	 * The value for the playerfid_team_id field.
	 * @var        int
	 */
	protected $playerfid_team_id;

	/**
	 * The value for the playerfid_fid_foe field.
	 * @var        string
	 */
	protected $playerfid_fid_foe;

	/**
	 * The value for the playerfid_fid_fifa field.
	 * @var        string
	 */
	protected $playerfid_fid_fifa;

	/**
	 * The value for the playerfid_fid_tm field.
	 * @var        string
	 */
	protected $playerfid_fid_tm;

	/**
	 * The value for the playerfid_fid_uefa field.
	 * @var        string
	 */
	protected $playerfid_fid_uefa;

	/**
	 * The value for the playerfid_fid_wf field.
	 * @var        string
	 */
	protected $playerfid_fid_wf;

	/**
	 * The value for the playerfid_name_foe field.
	 * @var        string
	 */
	protected $playerfid_name_foe;

	/**
	 * The value for the playerfid_name_fifa field.
	 * @var        string
	 */
	protected $playerfid_name_fifa;

	/**
	 * The value for the playerfid_name_tm field.
	 * @var        string
	 */
	protected $playerfid_name_tm;

	/**
	 * The value for the playerfid_name_uefa field.
	 * @var        string
	 */
	protected $playerfid_name_uefa;

	/**
	 * The value for the playerfid_name_wf field.
	 * @var        string
	 */
	protected $playerfid_name_wf;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteam;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeam;

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
	 * Get the [playerfid_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerfidId()
	{
		return $this->playerfid_id;
	}

	/**
	 * Get the [playerfid_playerteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerfidPlayerteamId()
	{
		return $this->playerfid_playerteam_id;
	}

	/**
	 * Get the [playerfid_team_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerfidTeamId()
	{
		return $this->playerfid_team_id;
	}

	/**
	 * Get the [playerfid_fid_foe] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidFidFoe()
	{
		return $this->playerfid_fid_foe;
	}

	/**
	 * Get the [playerfid_fid_fifa] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidFidFifa()
	{
		return $this->playerfid_fid_fifa;
	}

	/**
	 * Get the [playerfid_fid_tm] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidFidTm()
	{
		return $this->playerfid_fid_tm;
	}

	/**
	 * Get the [playerfid_fid_uefa] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidFidUefa()
	{
		return $this->playerfid_fid_uefa;
	}

	/**
	 * Get the [playerfid_fid_wf] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidFidWf()
	{
		return $this->playerfid_fid_wf;
	}

	/**
	 * Get the [playerfid_name_foe] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidNameFoe()
	{
		return $this->playerfid_name_foe;
	}

	/**
	 * Get the [playerfid_name_fifa] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidNameFifa()
	{
		return $this->playerfid_name_fifa;
	}

	/**
	 * Get the [playerfid_name_tm] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidNameTm()
	{
		return $this->playerfid_name_tm;
	}

	/**
	 * Get the [playerfid_name_uefa] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidNameUefa()
	{
		return $this->playerfid_name_uefa;
	}

	/**
	 * Get the [playerfid_name_wf] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerfidNameWf()
	{
		return $this->playerfid_name_wf;
	}

	/**
	 * Set the value of [playerfid_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerfid_id !== $v) {
			$this->playerfid_id = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_ID;
		}

		return $this;
	} // setPlayerfidId()

	/**
	 * Set the value of [playerfid_playerteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidPlayerteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerfid_playerteam_id !== $v) {
			$this->playerfid_playerteam_id = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID;
		}

		if ($this->aFfbPlayerteam !== null && $this->aFfbPlayerteam->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteam = null;
		}

		return $this;
	} // setPlayerfidPlayerteamId()

	/**
	 * Set the value of [playerfid_team_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidTeamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerfid_team_id !== $v) {
			$this->playerfid_team_id = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_TEAM_ID;
		}

		if ($this->aFfbTeam !== null && $this->aFfbTeam->getTeamId() !== $v) {
			$this->aFfbTeam = null;
		}

		return $this;
	} // setPlayerfidTeamId()

	/**
	 * Set the value of [playerfid_fid_foe] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidFidFoe($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_fid_foe !== $v) {
			$this->playerfid_fid_foe = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_FID_FOE;
		}

		return $this;
	} // setPlayerfidFidFoe()

	/**
	 * Set the value of [playerfid_fid_fifa] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidFidFifa($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_fid_fifa !== $v) {
			$this->playerfid_fid_fifa = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_FID_FIFA;
		}

		return $this;
	} // setPlayerfidFidFifa()

	/**
	 * Set the value of [playerfid_fid_tm] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidFidTm($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_fid_tm !== $v) {
			$this->playerfid_fid_tm = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_FID_TM;
		}

		return $this;
	} // setPlayerfidFidTm()

	/**
	 * Set the value of [playerfid_fid_uefa] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidFidUefa($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_fid_uefa !== $v) {
			$this->playerfid_fid_uefa = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_FID_UEFA;
		}

		return $this;
	} // setPlayerfidFidUefa()

	/**
	 * Set the value of [playerfid_fid_wf] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidFidWf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_fid_wf !== $v) {
			$this->playerfid_fid_wf = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_FID_WF;
		}

		return $this;
	} // setPlayerfidFidWf()

	/**
	 * Set the value of [playerfid_name_foe] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidNameFoe($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_name_foe !== $v) {
			$this->playerfid_name_foe = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_NAME_FOE;
		}

		return $this;
	} // setPlayerfidNameFoe()

	/**
	 * Set the value of [playerfid_name_fifa] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidNameFifa($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_name_fifa !== $v) {
			$this->playerfid_name_fifa = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_NAME_FIFA;
		}

		return $this;
	} // setPlayerfidNameFifa()

	/**
	 * Set the value of [playerfid_name_tm] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidNameTm($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_name_tm !== $v) {
			$this->playerfid_name_tm = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_NAME_TM;
		}

		return $this;
	} // setPlayerfidNameTm()

	/**
	 * Set the value of [playerfid_name_uefa] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidNameUefa($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_name_uefa !== $v) {
			$this->playerfid_name_uefa = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_NAME_UEFA;
		}

		return $this;
	} // setPlayerfidNameUefa()

	/**
	 * Set the value of [playerfid_name_wf] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 */
	public function setPlayerfidNameWf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerfid_name_wf !== $v) {
			$this->playerfid_name_wf = $v;
			$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_NAME_WF;
		}

		return $this;
	} // setPlayerfidNameWf()

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

			$this->playerfid_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->playerfid_playerteam_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->playerfid_team_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->playerfid_fid_foe = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->playerfid_fid_fifa = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->playerfid_fid_tm = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->playerfid_fid_uefa = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->playerfid_fid_wf = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->playerfid_name_foe = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->playerfid_name_fifa = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->playerfid_name_tm = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->playerfid_name_uefa = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->playerfid_name_wf = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 13; // 13 = FfbPlayerfidPeer::NUM_COLUMNS - FfbPlayerfidPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPlayerfid object", $e);
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

		if ($this->aFfbPlayerteam !== null && $this->playerfid_playerteam_id !== $this->aFfbPlayerteam->getPlayerteamId()) {
			$this->aFfbPlayerteam = null;
		}
		if ($this->aFfbTeam !== null && $this->playerfid_team_id !== $this->aFfbTeam->getTeamId()) {
			$this->aFfbTeam = null;
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
			$con = Propel::getConnection(FfbPlayerfidPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPlayerfidPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPlayerteam = null;
			$this->aFfbTeam = null;
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
			$con = Propel::getConnection(FfbPlayerfidPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerfidPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbPlayerfidPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPlayerfidPeer::addInstanceToPool($this);
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

			if ($this->aFfbPlayerteam !== null) {
				if ($this->aFfbPlayerteam->isModified() || $this->aFfbPlayerteam->isNew()) {
					$affectedRows += $this->aFfbPlayerteam->save($con);
				}
				$this->setFfbPlayerteam($this->aFfbPlayerteam);
			}

			if ($this->aFfbTeam !== null) {
				if ($this->aFfbTeam->isModified() || $this->aFfbTeam->isNew()) {
					$affectedRows += $this->aFfbTeam->save($con);
				}
				$this->setFfbTeam($this->aFfbTeam);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPlayerfidPeer::PLAYERFID_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbPlayerfidPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setPlayerfidId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbPlayerfidPeer::doUpdate($this, $con);
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

			if ($this->aFfbTeam !== null) {
				if (!$this->aFfbTeam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeam->getValidationFailures());
				}
			}


			if (($retval = FfbPlayerfidPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbPlayerfidPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_ID)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_ID, $this->playerfid_id);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $this->playerfid_playerteam_id);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_TEAM_ID)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_TEAM_ID, $this->playerfid_team_id);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_FID_FOE)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_FID_FOE, $this->playerfid_fid_foe);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_FID_FIFA)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_FID_FIFA, $this->playerfid_fid_fifa);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_FID_TM)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_FID_TM, $this->playerfid_fid_tm);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_FID_UEFA)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_FID_UEFA, $this->playerfid_fid_uefa);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_FID_WF)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_FID_WF, $this->playerfid_fid_wf);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_NAME_FOE)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $this->playerfid_name_foe);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_NAME_FIFA)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_FIFA, $this->playerfid_name_fifa);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_NAME_TM)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_TM, $this->playerfid_name_tm);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_NAME_UEFA)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_UEFA, $this->playerfid_name_uefa);
		if ($this->isColumnModified(FfbPlayerfidPeer::PLAYERFID_NAME_WF)) $criteria->add(FfbPlayerfidPeer::PLAYERFID_NAME_WF, $this->playerfid_name_wf);

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
		$criteria = new Criteria(FfbPlayerfidPeer::DATABASE_NAME);

		$criteria->add(FfbPlayerfidPeer::PLAYERFID_ID, $this->playerfid_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPlayerfidId();
	}

	/**
	 * Generic method to set the primary key (playerfid_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPlayerfidId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPlayerfid (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setPlayerfidPlayerteamId($this->playerfid_playerteam_id);

		$copyObj->setPlayerfidTeamId($this->playerfid_team_id);

		$copyObj->setPlayerfidFidFoe($this->playerfid_fid_foe);

		$copyObj->setPlayerfidFidFifa($this->playerfid_fid_fifa);

		$copyObj->setPlayerfidFidTm($this->playerfid_fid_tm);

		$copyObj->setPlayerfidFidUefa($this->playerfid_fid_uefa);

		$copyObj->setPlayerfidFidWf($this->playerfid_fid_wf);

		$copyObj->setPlayerfidNameFoe($this->playerfid_name_foe);

		$copyObj->setPlayerfidNameFifa($this->playerfid_name_fifa);

		$copyObj->setPlayerfidNameTm($this->playerfid_name_tm);

		$copyObj->setPlayerfidNameUefa($this->playerfid_name_uefa);

		$copyObj->setPlayerfidNameWf($this->playerfid_name_wf);


		$copyObj->setNew(true);

		$copyObj->setPlayerfidId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbPlayerfid Clone of current object.
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
	 * @return     FfbPlayerfidPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPlayerfidPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteam(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setPlayerfidPlayerteamId(NULL);
		} else {
			$this->setPlayerfidPlayerteamId($v->getPlayerteamId());
		}

		$this->aFfbPlayerteam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerfid($this);
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
		if ($this->aFfbPlayerteam === null && ($this->playerfid_playerteam_id !== null)) {
			$this->aFfbPlayerteam = FfbPlayerteamPeer::retrieveByPk($this->playerfid_playerteam_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbPlayerteam->addFfbPlayerfids($this);
			 */
		}
		return $this->aFfbPlayerteam;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     FfbPlayerfid The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeam(FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setPlayerfidTeamId(NULL);
		} else {
			$this->setPlayerfidTeamId($v->getTeamId());
		}

		$this->aFfbTeam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerfid($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbTeam object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbTeam The associated FfbTeam object.
	 * @throws     PropelException
	 */
	public function getFfbTeam(PropelPDO $con = null)
	{
		if ($this->aFfbTeam === null && ($this->playerfid_team_id !== null)) {
			$this->aFfbTeam = FfbTeamPeer::retrieveByPk($this->playerfid_team_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbTeam->addFfbPlayerfids($this);
			 */
		}
		return $this->aFfbTeam;
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
			$this->aFfbTeam = null;
	}

} // BaseFfbPlayerfid
