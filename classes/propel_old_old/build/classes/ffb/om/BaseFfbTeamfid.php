<?php

/**
 * Base class that represents a row from the 'ffb_teamfid' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbTeamfid extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbTeamfidPeer
	 */
	protected static $peer;

	/**
	 * The value for the teamfid_id field.
	 * @var        int
	 */
	protected $teamfid_id;

	/**
	 * The value for the teamfid_team_id field.
	 * @var        int
	 */
	protected $teamfid_team_id;

	/**
	 * The value for the teamfid_fid_foe field.
	 * @var        string
	 */
	protected $teamfid_fid_foe;

	/**
	 * The value for the teamfid_fid_tm field.
	 * @var        string
	 */
	protected $teamfid_fid_tm;

	/**
	 * The value for the teamfid_fid_wf field.
	 * @var        string
	 */
	protected $teamfid_fid_wf;

	/**
	 * The value for the teamfid_name_foe field.
	 * @var        string
	 */
	protected $teamfid_name_foe;

	/**
	 * The value for the teamfid_name_tm field.
	 * @var        string
	 */
	protected $teamfid_name_tm;

	/**
	 * The value for the teamfid_name_wf field.
	 * @var        string
	 */
	protected $teamfid_name_wf;

	/**
	 * The value for the teamfid_url_foe field.
	 * @var        string
	 */
	protected $teamfid_url_foe;

	/**
	 * The value for the teamfid_url_tm field.
	 * @var        string
	 */
	protected $teamfid_url_tm;

	/**
	 * The value for the teamfid_url_wf field.
	 * @var        string
	 */
	protected $teamfid_url_wf;

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
	 * Get the [teamfid_id] column value.
	 * 
	 * @return     int
	 */
	public function getTeamfidId()
	{
		return $this->teamfid_id;
	}

	/**
	 * Get the [teamfid_team_id] column value.
	 * 
	 * @return     int
	 */
	public function getTeamfidTeamId()
	{
		return $this->teamfid_team_id;
	}

	/**
	 * Get the [teamfid_fid_foe] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidFidFoe()
	{
		return $this->teamfid_fid_foe;
	}

	/**
	 * Get the [teamfid_fid_tm] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidFidTm()
	{
		return $this->teamfid_fid_tm;
	}

	/**
	 * Get the [teamfid_fid_wf] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidFidWf()
	{
		return $this->teamfid_fid_wf;
	}

	/**
	 * Get the [teamfid_name_foe] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidNameFoe()
	{
		return $this->teamfid_name_foe;
	}

	/**
	 * Get the [teamfid_name_tm] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidNameTm()
	{
		return $this->teamfid_name_tm;
	}

	/**
	 * Get the [teamfid_name_wf] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidNameWf()
	{
		return $this->teamfid_name_wf;
	}

	/**
	 * Get the [teamfid_url_foe] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidUrlFoe()
	{
		return $this->teamfid_url_foe;
	}

	/**
	 * Get the [teamfid_url_tm] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidUrlTm()
	{
		return $this->teamfid_url_tm;
	}

	/**
	 * Get the [teamfid_url_wf] column value.
	 * 
	 * @return     string
	 */
	public function getTeamfidUrlWf()
	{
		return $this->teamfid_url_wf;
	}

	/**
	 * Set the value of [teamfid_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->teamfid_id !== $v) {
			$this->teamfid_id = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_ID;
		}

		return $this;
	} // setTeamfidId()

	/**
	 * Set the value of [teamfid_team_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidTeamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->teamfid_team_id !== $v) {
			$this->teamfid_team_id = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_TEAM_ID;
		}

		if ($this->aFfbTeam !== null && $this->aFfbTeam->getTeamId() !== $v) {
			$this->aFfbTeam = null;
		}

		return $this;
	} // setTeamfidTeamId()

	/**
	 * Set the value of [teamfid_fid_foe] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidFidFoe($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_fid_foe !== $v) {
			$this->teamfid_fid_foe = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_FID_FOE;
		}

		return $this;
	} // setTeamfidFidFoe()

	/**
	 * Set the value of [teamfid_fid_tm] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidFidTm($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_fid_tm !== $v) {
			$this->teamfid_fid_tm = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_FID_TM;
		}

		return $this;
	} // setTeamfidFidTm()

	/**
	 * Set the value of [teamfid_fid_wf] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidFidWf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_fid_wf !== $v) {
			$this->teamfid_fid_wf = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_FID_WF;
		}

		return $this;
	} // setTeamfidFidWf()

	/**
	 * Set the value of [teamfid_name_foe] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidNameFoe($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_name_foe !== $v) {
			$this->teamfid_name_foe = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_NAME_FOE;
		}

		return $this;
	} // setTeamfidNameFoe()

	/**
	 * Set the value of [teamfid_name_tm] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidNameTm($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_name_tm !== $v) {
			$this->teamfid_name_tm = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_NAME_TM;
		}

		return $this;
	} // setTeamfidNameTm()

	/**
	 * Set the value of [teamfid_name_wf] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidNameWf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_name_wf !== $v) {
			$this->teamfid_name_wf = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_NAME_WF;
		}

		return $this;
	} // setTeamfidNameWf()

	/**
	 * Set the value of [teamfid_url_foe] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidUrlFoe($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_url_foe !== $v) {
			$this->teamfid_url_foe = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_URL_FOE;
		}

		return $this;
	} // setTeamfidUrlFoe()

	/**
	 * Set the value of [teamfid_url_tm] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidUrlTm($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_url_tm !== $v) {
			$this->teamfid_url_tm = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_URL_TM;
		}

		return $this;
	} // setTeamfidUrlTm()

	/**
	 * Set the value of [teamfid_url_wf] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeamfid The current object (for fluent API support)
	 */
	public function setTeamfidUrlWf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->teamfid_url_wf !== $v) {
			$this->teamfid_url_wf = $v;
			$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_URL_WF;
		}

		return $this;
	} // setTeamfidUrlWf()

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

			$this->teamfid_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->teamfid_team_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->teamfid_fid_foe = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->teamfid_fid_tm = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->teamfid_fid_wf = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->teamfid_name_foe = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->teamfid_name_tm = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->teamfid_name_wf = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->teamfid_url_foe = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->teamfid_url_tm = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->teamfid_url_wf = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 11; // 11 = FfbTeamfidPeer::NUM_COLUMNS - FfbTeamfidPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbTeamfid object", $e);
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

		if ($this->aFfbTeam !== null && $this->teamfid_team_id !== $this->aFfbTeam->getTeamId()) {
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
			$con = Propel::getConnection(FfbTeamfidPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbTeamfidPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

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
			$con = Propel::getConnection(FfbTeamfidPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbTeamfidPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(FfbTeamfidPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbTeamfidPeer::addInstanceToPool($this);
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

			if ($this->aFfbTeam !== null) {
				if ($this->aFfbTeam->isModified() || $this->aFfbTeam->isNew()) {
					$affectedRows += $this->aFfbTeam->save($con);
				}
				$this->setFfbTeam($this->aFfbTeam);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbTeamfidPeer::TEAMFID_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FfbTeamfidPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setTeamfidId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += FfbTeamfidPeer::doUpdate($this, $con);
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

			if ($this->aFfbTeam !== null) {
				if (!$this->aFfbTeam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeam->getValidationFailures());
				}
			}


			if (($retval = FfbTeamfidPeer::doValidate($this, $columns)) !== true) {
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
		$criteria = new Criteria(FfbTeamfidPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_ID)) $criteria->add(FfbTeamfidPeer::TEAMFID_ID, $this->teamfid_id);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_TEAM_ID)) $criteria->add(FfbTeamfidPeer::TEAMFID_TEAM_ID, $this->teamfid_team_id);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_FID_FOE)) $criteria->add(FfbTeamfidPeer::TEAMFID_FID_FOE, $this->teamfid_fid_foe);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_FID_TM)) $criteria->add(FfbTeamfidPeer::TEAMFID_FID_TM, $this->teamfid_fid_tm);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_FID_WF)) $criteria->add(FfbTeamfidPeer::TEAMFID_FID_WF, $this->teamfid_fid_wf);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_NAME_FOE)) $criteria->add(FfbTeamfidPeer::TEAMFID_NAME_FOE, $this->teamfid_name_foe);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_NAME_TM)) $criteria->add(FfbTeamfidPeer::TEAMFID_NAME_TM, $this->teamfid_name_tm);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_NAME_WF)) $criteria->add(FfbTeamfidPeer::TEAMFID_NAME_WF, $this->teamfid_name_wf);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_URL_FOE)) $criteria->add(FfbTeamfidPeer::TEAMFID_URL_FOE, $this->teamfid_url_foe);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_URL_TM)) $criteria->add(FfbTeamfidPeer::TEAMFID_URL_TM, $this->teamfid_url_tm);
		if ($this->isColumnModified(FfbTeamfidPeer::TEAMFID_URL_WF)) $criteria->add(FfbTeamfidPeer::TEAMFID_URL_WF, $this->teamfid_url_wf);

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
		$criteria = new Criteria(FfbTeamfidPeer::DATABASE_NAME);

		$criteria->add(FfbTeamfidPeer::TEAMFID_ID, $this->teamfid_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getTeamfidId();
	}

	/**
	 * Generic method to set the primary key (teamfid_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setTeamfidId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbTeamfid (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setTeamfidTeamId($this->teamfid_team_id);

		$copyObj->setTeamfidFidFoe($this->teamfid_fid_foe);

		$copyObj->setTeamfidFidTm($this->teamfid_fid_tm);

		$copyObj->setTeamfidFidWf($this->teamfid_fid_wf);

		$copyObj->setTeamfidNameFoe($this->teamfid_name_foe);

		$copyObj->setTeamfidNameTm($this->teamfid_name_tm);

		$copyObj->setTeamfidNameWf($this->teamfid_name_wf);

		$copyObj->setTeamfidUrlFoe($this->teamfid_url_foe);

		$copyObj->setTeamfidUrlTm($this->teamfid_url_tm);

		$copyObj->setTeamfidUrlWf($this->teamfid_url_wf);


		$copyObj->setNew(true);

		$copyObj->setTeamfidId(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     FfbTeamfid Clone of current object.
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
	 * @return     FfbTeamfidPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbTeamfidPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     FfbTeamfid The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeam(FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setTeamfidTeamId(NULL);
		} else {
			$this->setTeamfidTeamId($v->getTeamId());
		}

		$this->aFfbTeam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbTeamfid($this);
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
		if ($this->aFfbTeam === null && ($this->teamfid_team_id !== null)) {
			$this->aFfbTeam = FfbTeamPeer::retrieveByPk($this->teamfid_team_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aFfbTeam->addFfbTeamfids($this);
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

			$this->aFfbTeam = null;
	}

} // BaseFfbTeamfid
