<?php


/**
 * Base class that represents a row from the 'ffb_team' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbTeam extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbTeamPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbTeamPeer
	 */
	protected static $peer;

	/**
	 * The value for the team_id field.
	 * @var        int
	 */
	protected $team_id;

	/**
	 * The value for the team_foreign_id field.
	 * @var        string
	 */
	protected $team_foreign_id;

	/**
	 * The value for the team_name field.
	 * @var        string
	 */
	protected $team_name;

	/**
	 * The value for the team_nationality field.
	 * @var        string
	 */
	protected $team_nationality;

	/**
	 * The value for the team_avg_price field.
	 * @var        double
	 */
	protected $team_avg_price;

	/**
	 * The value for the team_num_players field.
	 * @var        int
	 */
	protected $team_num_players;

	/**
	 * The value for the team_status field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $team_status;

	/**
	 * @var        array WebUserDetails[] Collection to store aggregation of WebUserDetails objects.
	 */
	protected $collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam;

	/**
	 * @var        array WebUserDetails[] Collection to store aggregation of WebUserDetails objects.
	 */
	protected $collWebUserDetailssRelatedByUserDetailsFfbOwnTeam;

	/**
	 * @var        array FfbPlayerteam[] Collection to store aggregation of FfbPlayerteam objects.
	 */
	protected $collFfbPlayerteams;

	/**
	 * @var        array FfbMatch[] Collection to store aggregation of FfbMatch objects.
	 */
	protected $collFfbMatchsRelatedByMatchHometeamId;

	/**
	 * @var        array FfbMatch[] Collection to store aggregation of FfbMatch objects.
	 */
	protected $collFfbMatchsRelatedByMatchGuestteamId;

	/**
	 * @var        array FfbPlayerfid[] Collection to store aggregation of FfbPlayerfid objects.
	 */
	protected $collFfbPlayerfids;

	/**
	 * @var        array FfbTeamfid[] Collection to store aggregation of FfbTeamfid objects.
	 */
	protected $collFfbTeamfids;

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
		$this->team_status = true;
	}

	/**
	 * Initializes internal state of BaseFfbTeam object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [team_id] column value.
	 * 
	 * @return     int
	 */
	public function getTeamId()
	{
		return $this->team_id;
	}

	/**
	 * Get the [team_foreign_id] column value.
	 * 
	 * @return     string
	 */
	public function getTeamForeignId()
	{
		return $this->team_foreign_id;
	}

	/**
	 * Get the [team_name] column value.
	 * 
	 * @return     string
	 */
	public function getTeamName()
	{
		return $this->team_name;
	}

	/**
	 * Get the [team_nationality] column value.
	 * 
	 * @return     string
	 */
	public function getTeamNationality()
	{
		return $this->team_nationality;
	}

	/**
	 * Get the [team_avg_price] column value.
	 * 
	 * @return     double
	 */
	public function getTeamAvgPrice()
	{
		return $this->team_avg_price;
	}

	/**
	 * Get the [team_num_players] column value.
	 * 
	 * @return     int
	 */
	public function getTeamNumPlayers()
	{
		return $this->team_num_players;
	}

	/**
	 * Get the [team_status] column value.
	 * 
	 * @return     boolean
	 */
	public function getTeamStatus()
	{
		return $this->team_status;
	}

	/**
	 * Set the value of [team_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->team_id !== $v) {
			$this->team_id = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_ID;
		}

		return $this;
	} // setTeamId()

	/**
	 * Set the value of [team_foreign_id] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamForeignId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->team_foreign_id !== $v) {
			$this->team_foreign_id = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_FOREIGN_ID;
		}

		return $this;
	} // setTeamForeignId()

	/**
	 * Set the value of [team_name] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->team_name !== $v) {
			$this->team_name = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_NAME;
		}

		return $this;
	} // setTeamName()

	/**
	 * Set the value of [team_nationality] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamNationality($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->team_nationality !== $v) {
			$this->team_nationality = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_NATIONALITY;
		}

		return $this;
	} // setTeamNationality()

	/**
	 * Set the value of [team_avg_price] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamAvgPrice($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->team_avg_price !== $v) {
			$this->team_avg_price = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_AVG_PRICE;
		}

		return $this;
	} // setTeamAvgPrice()

	/**
	 * Set the value of [team_num_players] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamNumPlayers($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->team_num_players !== $v) {
			$this->team_num_players = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_NUM_PLAYERS;
		}

		return $this;
	} // setTeamNumPlayers()

	/**
	 * Set the value of [team_status] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbTeam The current object (for fluent API support)
	 */
	public function setTeamStatus($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->team_status !== $v || $this->isNew()) {
			$this->team_status = $v;
			$this->modifiedColumns[] = FfbTeamPeer::TEAM_STATUS;
		}

		return $this;
	} // setTeamStatus()

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
			if ($this->team_status !== true) {
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

			$this->team_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->team_foreign_id = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->team_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->team_nationality = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->team_avg_price = ($row[$startcol + 4] !== null) ? (double) $row[$startcol + 4] : null;
			$this->team_num_players = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->team_status = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 7; // 7 = FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbTeam object", $e);
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
			$con = Propel::getConnection(FfbTeamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbTeamPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = null;

			$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = null;

			$this->collFfbPlayerteams = null;

			$this->collFfbMatchsRelatedByMatchHometeamId = null;

			$this->collFfbMatchsRelatedByMatchGuestteamId = null;

			$this->collFfbPlayerfids = null;

			$this->collFfbTeamfids = null;

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
			$con = Propel::getConnection(FfbTeamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbTeamQuery::create()
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
			$con = Propel::getConnection(FfbTeamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbTeamPeer::addInstanceToPool($this);
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

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbTeamPeer::TEAM_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbTeamPeer::TEAM_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbTeamPeer::TEAM_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setTeamId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = FfbTeamPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam !== null) {
				foreach ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam !== null) {
				foreach ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam as $referrerFK) {
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

			if ($this->collFfbMatchsRelatedByMatchHometeamId !== null) {
				foreach ($this->collFfbMatchsRelatedByMatchHometeamId as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbMatchsRelatedByMatchGuestteamId !== null) {
				foreach ($this->collFfbMatchsRelatedByMatchGuestteamId as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPlayerfids !== null) {
				foreach ($this->collFfbPlayerfids as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbTeamfids !== null) {
				foreach ($this->collFfbTeamfids as $referrerFK) {
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


			if (($retval = FfbTeamPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam !== null) {
					foreach ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam !== null) {
					foreach ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam as $referrerFK) {
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

				if ($this->collFfbMatchsRelatedByMatchHometeamId !== null) {
					foreach ($this->collFfbMatchsRelatedByMatchHometeamId as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbMatchsRelatedByMatchGuestteamId !== null) {
					foreach ($this->collFfbMatchsRelatedByMatchGuestteamId as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPlayerfids !== null) {
					foreach ($this->collFfbPlayerfids as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbTeamfids !== null) {
					foreach ($this->collFfbTeamfids as $referrerFK) {
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
		$pos = FfbTeamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getTeamId();
				break;
			case 1:
				return $this->getTeamForeignId();
				break;
			case 2:
				return $this->getTeamName();
				break;
			case 3:
				return $this->getTeamNationality();
				break;
			case 4:
				return $this->getTeamAvgPrice();
				break;
			case 5:
				return $this->getTeamNumPlayers();
				break;
			case 6:
				return $this->getTeamStatus();
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
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = FfbTeamPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getTeamId(),
			$keys[1] => $this->getTeamForeignId(),
			$keys[2] => $this->getTeamName(),
			$keys[3] => $this->getTeamNationality(),
			$keys[4] => $this->getTeamAvgPrice(),
			$keys[5] => $this->getTeamNumPlayers(),
			$keys[6] => $this->getTeamStatus(),
		);
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
		$pos = FfbTeamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setTeamId($value);
				break;
			case 1:
				$this->setTeamForeignId($value);
				break;
			case 2:
				$this->setTeamName($value);
				break;
			case 3:
				$this->setTeamNationality($value);
				break;
			case 4:
				$this->setTeamAvgPrice($value);
				break;
			case 5:
				$this->setTeamNumPlayers($value);
				break;
			case 6:
				$this->setTeamStatus($value);
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
		$keys = FfbTeamPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setTeamId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTeamForeignId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setTeamName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setTeamNationality($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setTeamAvgPrice($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setTeamNumPlayers($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setTeamStatus($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbTeamPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbTeamPeer::TEAM_ID)) $criteria->add(FfbTeamPeer::TEAM_ID, $this->team_id);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_FOREIGN_ID)) $criteria->add(FfbTeamPeer::TEAM_FOREIGN_ID, $this->team_foreign_id);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_NAME)) $criteria->add(FfbTeamPeer::TEAM_NAME, $this->team_name);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_NATIONALITY)) $criteria->add(FfbTeamPeer::TEAM_NATIONALITY, $this->team_nationality);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_AVG_PRICE)) $criteria->add(FfbTeamPeer::TEAM_AVG_PRICE, $this->team_avg_price);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_NUM_PLAYERS)) $criteria->add(FfbTeamPeer::TEAM_NUM_PLAYERS, $this->team_num_players);
		if ($this->isColumnModified(FfbTeamPeer::TEAM_STATUS)) $criteria->add(FfbTeamPeer::TEAM_STATUS, $this->team_status);

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
		$criteria = new Criteria(FfbTeamPeer::DATABASE_NAME);
		$criteria->add(FfbTeamPeer::TEAM_ID, $this->team_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getTeamId();
	}

	/**
	 * Generic method to set the primary key (team_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setTeamId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getTeamId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbTeam (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setTeamForeignId($this->team_foreign_id);
		$copyObj->setTeamName($this->team_name);
		$copyObj->setTeamNationality($this->team_nationality);
		$copyObj->setTeamAvgPrice($this->team_avg_price);
		$copyObj->setTeamNumPlayers($this->team_num_players);
		$copyObj->setTeamStatus($this->team_status);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWebUserDetailssRelatedByUserDetailsFfbOwnTeam() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerteams() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerteam($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbMatchsRelatedByMatchHometeamId() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbMatchRelatedByMatchHometeamId($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbMatchsRelatedByMatchGuestteamId() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbMatchRelatedByMatchGuestteamId($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerfids() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerfid($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbTeamfids() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbTeamfid($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setTeamId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbTeam Clone of current object.
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
	 * @return     FfbTeamPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbTeamPeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam()
	 */
	public function clearWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam()
	{
		$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam collection.
	 *
	 * By default this just sets the collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam collection to an empty array (like clearcollWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam()
	{
		$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = new PropelObjectCollection();
		$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam->setModel('WebUserDetails');
	}

	/**
	 * Gets an array of WebUserDetails objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 * @throws     PropelException
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam) {
				// return empty collection
				$this->initWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam();
			} else {
				$collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = WebUserDetailsQuery::create(null, $criteria)
					->filterByFfbTeamRelatedByUserDetailsFfbFavouriteTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam;
				}
				$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = $collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam;
			}
		}
		return $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam;
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
	public function countWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam) {
				return 0;
			} else {
				$query = WebUserDetailsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeamRelatedByUserDetailsFfbFavouriteTeam($this)
					->count($con);
			}
		} else {
			return count($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam);
		}
	}

	/**
	 * Method called to associate a WebUserDetails object to this object
	 * through the WebUserDetails foreign key attribute.
	 *
	 * @param      WebUserDetails $l WebUserDetails
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam(WebUserDetails $l)
	{
		if ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam === null) {
			$this->initWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam();
		}
		if (!$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam->contains($l)) { // only add it if the **same** object is not already associated
			$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam[]= $l;
			$l->setFfbTeamRelatedByUserDetailsFfbFavouriteTeam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbFavouriteTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeamJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbFavouriteTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeamJoinFfbPlayer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbPlayer', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbFavouriteTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeamJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam($query, $con);
	}

	/**
	 * Clears out the collWebUserDetailssRelatedByUserDetailsFfbOwnTeam collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWebUserDetailssRelatedByUserDetailsFfbOwnTeam()
	 */
	public function clearWebUserDetailssRelatedByUserDetailsFfbOwnTeam()
	{
		$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWebUserDetailssRelatedByUserDetailsFfbOwnTeam collection.
	 *
	 * By default this just sets the collWebUserDetailssRelatedByUserDetailsFfbOwnTeam collection to an empty array (like clearcollWebUserDetailssRelatedByUserDetailsFfbOwnTeam());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebUserDetailssRelatedByUserDetailsFfbOwnTeam()
	{
		$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = new PropelObjectCollection();
		$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam->setModel('WebUserDetails');
	}

	/**
	 * Gets an array of WebUserDetails objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 * @throws     PropelException
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbOwnTeam($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam) {
				// return empty collection
				$this->initWebUserDetailssRelatedByUserDetailsFfbOwnTeam();
			} else {
				$collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = WebUserDetailsQuery::create(null, $criteria)
					->filterByFfbTeamRelatedByUserDetailsFfbOwnTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collWebUserDetailssRelatedByUserDetailsFfbOwnTeam;
				}
				$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = $collWebUserDetailssRelatedByUserDetailsFfbOwnTeam;
			}
		}
		return $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam;
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
	public function countWebUserDetailssRelatedByUserDetailsFfbOwnTeam(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam) {
				return 0;
			} else {
				$query = WebUserDetailsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeamRelatedByUserDetailsFfbOwnTeam($this)
					->count($con);
			}
		} else {
			return count($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam);
		}
	}

	/**
	 * Method called to associate a WebUserDetails object to this object
	 * through the WebUserDetails foreign key attribute.
	 *
	 * @param      WebUserDetails $l WebUserDetails
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWebUserDetailsRelatedByUserDetailsFfbOwnTeam(WebUserDetails $l)
	{
		if ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam === null) {
			$this->initWebUserDetailssRelatedByUserDetailsFfbOwnTeam();
		}
		if (!$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam->contains($l)) { // only add it if the **same** object is not already associated
			$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam[]= $l;
			$l->setFfbTeamRelatedByUserDetailsFfbOwnTeam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbOwnTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbOwnTeamJoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbOwnTeam($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbOwnTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbOwnTeamJoinFfbPlayer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbPlayer', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbOwnTeam($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related WebUserDetailssRelatedByUserDetailsFfbOwnTeam from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array WebUserDetails[] List of WebUserDetails objects
	 */
	public function getWebUserDetailssRelatedByUserDetailsFfbOwnTeamJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = WebUserDetailsQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getWebUserDetailssRelatedByUserDetailsFfbOwnTeam($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerteams collection
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
	 * Initializes the collFfbPlayerteams collection.
	 *
	 * By default this just sets the collFfbPlayerteams collection to an empty array (like clearcollFfbPlayerteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerteams()
	{
		$this->collFfbPlayerteams = new PropelObjectCollection();
		$this->collFfbPlayerteams->setModel('FfbPlayerteam');
	}

	/**
	 * Gets an array of FfbPlayerteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerteam[] List of FfbPlayerteam objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerteams($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerteams) {
				// return empty collection
				$this->initFfbPlayerteams();
			} else {
				$collFfbPlayerteams = FfbPlayerteamQuery::create(null, $criteria)
					->filterByFfbTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerteams;
				}
				$this->collFfbPlayerteams = $collFfbPlayerteams;
			}
		}
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
		if(null === $this->collFfbPlayerteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerteams) {
				return 0;
			} else {
				$query = FfbPlayerteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerteams);
		}
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
		if (!$this->collFfbPlayerteams->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerteams[]= $l;
			$l->setFfbTeam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related FfbPlayerteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerteam[] List of FfbPlayerteam objects
	 */
	public function getFfbPlayerteamsJoinFfbPlayer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayer', $join_behavior);

		return $this->getFfbPlayerteams($query, $con);
	}

	/**
	 * Clears out the collFfbMatchsRelatedByMatchHometeamId collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbMatchsRelatedByMatchHometeamId()
	 */
	public function clearFfbMatchsRelatedByMatchHometeamId()
	{
		$this->collFfbMatchsRelatedByMatchHometeamId = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbMatchsRelatedByMatchHometeamId collection.
	 *
	 * By default this just sets the collFfbMatchsRelatedByMatchHometeamId collection to an empty array (like clearcollFfbMatchsRelatedByMatchHometeamId());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbMatchsRelatedByMatchHometeamId()
	{
		$this->collFfbMatchsRelatedByMatchHometeamId = new PropelObjectCollection();
		$this->collFfbMatchsRelatedByMatchHometeamId->setModel('FfbMatch');
	}

	/**
	 * Gets an array of FfbMatch objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 * @throws     PropelException
	 */
	public function getFfbMatchsRelatedByMatchHometeamId($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchsRelatedByMatchHometeamId || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchsRelatedByMatchHometeamId) {
				// return empty collection
				$this->initFfbMatchsRelatedByMatchHometeamId();
			} else {
				$collFfbMatchsRelatedByMatchHometeamId = FfbMatchQuery::create(null, $criteria)
					->filterByFfbTeamRelatedByMatchHometeamId($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbMatchsRelatedByMatchHometeamId;
				}
				$this->collFfbMatchsRelatedByMatchHometeamId = $collFfbMatchsRelatedByMatchHometeamId;
			}
		}
		return $this->collFfbMatchsRelatedByMatchHometeamId;
	}

	/**
	 * Returns the number of related FfbMatch objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbMatch objects.
	 * @throws     PropelException
	 */
	public function countFfbMatchsRelatedByMatchHometeamId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchsRelatedByMatchHometeamId || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchsRelatedByMatchHometeamId) {
				return 0;
			} else {
				$query = FfbMatchQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeamRelatedByMatchHometeamId($this)
					->count($con);
			}
		} else {
			return count($this->collFfbMatchsRelatedByMatchHometeamId);
		}
	}

	/**
	 * Method called to associate a FfbMatch object to this object
	 * through the FfbMatch foreign key attribute.
	 *
	 * @param      FfbMatch $l FfbMatch
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbMatchRelatedByMatchHometeamId(FfbMatch $l)
	{
		if ($this->collFfbMatchsRelatedByMatchHometeamId === null) {
			$this->initFfbMatchsRelatedByMatchHometeamId();
		}
		if (!$this->collFfbMatchsRelatedByMatchHometeamId->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbMatchsRelatedByMatchHometeamId[]= $l;
			$l->setFfbTeamRelatedByMatchHometeamId($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related FfbMatchsRelatedByMatchHometeamId from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 */
	public function getFfbMatchsRelatedByMatchHometeamIdJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbMatchQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbMatchsRelatedByMatchHometeamId($query, $con);
	}

	/**
	 * Clears out the collFfbMatchsRelatedByMatchGuestteamId collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbMatchsRelatedByMatchGuestteamId()
	 */
	public function clearFfbMatchsRelatedByMatchGuestteamId()
	{
		$this->collFfbMatchsRelatedByMatchGuestteamId = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbMatchsRelatedByMatchGuestteamId collection.
	 *
	 * By default this just sets the collFfbMatchsRelatedByMatchGuestteamId collection to an empty array (like clearcollFfbMatchsRelatedByMatchGuestteamId());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbMatchsRelatedByMatchGuestteamId()
	{
		$this->collFfbMatchsRelatedByMatchGuestteamId = new PropelObjectCollection();
		$this->collFfbMatchsRelatedByMatchGuestteamId->setModel('FfbMatch');
	}

	/**
	 * Gets an array of FfbMatch objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 * @throws     PropelException
	 */
	public function getFfbMatchsRelatedByMatchGuestteamId($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchsRelatedByMatchGuestteamId || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchsRelatedByMatchGuestteamId) {
				// return empty collection
				$this->initFfbMatchsRelatedByMatchGuestteamId();
			} else {
				$collFfbMatchsRelatedByMatchGuestteamId = FfbMatchQuery::create(null, $criteria)
					->filterByFfbTeamRelatedByMatchGuestteamId($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbMatchsRelatedByMatchGuestteamId;
				}
				$this->collFfbMatchsRelatedByMatchGuestteamId = $collFfbMatchsRelatedByMatchGuestteamId;
			}
		}
		return $this->collFfbMatchsRelatedByMatchGuestteamId;
	}

	/**
	 * Returns the number of related FfbMatch objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbMatch objects.
	 * @throws     PropelException
	 */
	public function countFfbMatchsRelatedByMatchGuestteamId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbMatchsRelatedByMatchGuestteamId || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbMatchsRelatedByMatchGuestteamId) {
				return 0;
			} else {
				$query = FfbMatchQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeamRelatedByMatchGuestteamId($this)
					->count($con);
			}
		} else {
			return count($this->collFfbMatchsRelatedByMatchGuestteamId);
		}
	}

	/**
	 * Method called to associate a FfbMatch object to this object
	 * through the FfbMatch foreign key attribute.
	 *
	 * @param      FfbMatch $l FfbMatch
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbMatchRelatedByMatchGuestteamId(FfbMatch $l)
	{
		if ($this->collFfbMatchsRelatedByMatchGuestteamId === null) {
			$this->initFfbMatchsRelatedByMatchGuestteamId();
		}
		if (!$this->collFfbMatchsRelatedByMatchGuestteamId->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbMatchsRelatedByMatchGuestteamId[]= $l;
			$l->setFfbTeamRelatedByMatchGuestteamId($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related FfbMatchsRelatedByMatchGuestteamId from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbMatch[] List of FfbMatch objects
	 */
	public function getFfbMatchsRelatedByMatchGuestteamIdJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbMatchQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbMatchsRelatedByMatchGuestteamId($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerfids collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerfids()
	 */
	public function clearFfbPlayerfids()
	{
		$this->collFfbPlayerfids = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerfids collection.
	 *
	 * By default this just sets the collFfbPlayerfids collection to an empty array (like clearcollFfbPlayerfids());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerfids()
	{
		$this->collFfbPlayerfids = new PropelObjectCollection();
		$this->collFfbPlayerfids->setModel('FfbPlayerfid');
	}

	/**
	 * Gets an array of FfbPlayerfid objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerfid[] List of FfbPlayerfid objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerfids($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerfids || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerfids) {
				// return empty collection
				$this->initFfbPlayerfids();
			} else {
				$collFfbPlayerfids = FfbPlayerfidQuery::create(null, $criteria)
					->filterByFfbTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerfids;
				}
				$this->collFfbPlayerfids = $collFfbPlayerfids;
			}
		}
		return $this->collFfbPlayerfids;
	}

	/**
	 * Returns the number of related FfbPlayerfid objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerfid objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerfids(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerfids || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerfids) {
				return 0;
			} else {
				$query = FfbPlayerfidQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerfids);
		}
	}

	/**
	 * Method called to associate a FfbPlayerfid object to this object
	 * through the FfbPlayerfid foreign key attribute.
	 *
	 * @param      FfbPlayerfid $l FfbPlayerfid
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerfid(FfbPlayerfid $l)
	{
		if ($this->collFfbPlayerfids === null) {
			$this->initFfbPlayerfids();
		}
		if (!$this->collFfbPlayerfids->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerfids[]= $l;
			$l->setFfbTeam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbTeam is new, it will return
	 * an empty collection; or if this FfbTeam has previously
	 * been saved, it will retrieve related FfbPlayerfids from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbTeam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerfid[] List of FfbPlayerfid objects
	 */
	public function getFfbPlayerfidsJoinFfbPlayerteam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerfidQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteam', $join_behavior);

		return $this->getFfbPlayerfids($query, $con);
	}

	/**
	 * Clears out the collFfbTeamfids collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbTeamfids()
	 */
	public function clearFfbTeamfids()
	{
		$this->collFfbTeamfids = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbTeamfids collection.
	 *
	 * By default this just sets the collFfbTeamfids collection to an empty array (like clearcollFfbTeamfids());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbTeamfids()
	{
		$this->collFfbTeamfids = new PropelObjectCollection();
		$this->collFfbTeamfids->setModel('FfbTeamfid');
	}

	/**
	 * Gets an array of FfbTeamfid objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbTeam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbTeamfid[] List of FfbTeamfid objects
	 * @throws     PropelException
	 */
	public function getFfbTeamfids($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbTeamfids || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbTeamfids) {
				// return empty collection
				$this->initFfbTeamfids();
			} else {
				$collFfbTeamfids = FfbTeamfidQuery::create(null, $criteria)
					->filterByFfbTeam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbTeamfids;
				}
				$this->collFfbTeamfids = $collFfbTeamfids;
			}
		}
		return $this->collFfbTeamfids;
	}

	/**
	 * Returns the number of related FfbTeamfid objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbTeamfid objects.
	 * @throws     PropelException
	 */
	public function countFfbTeamfids(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbTeamfids || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbTeamfids) {
				return 0;
			} else {
				$query = FfbTeamfidQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbTeam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbTeamfids);
		}
	}

	/**
	 * Method called to associate a FfbTeamfid object to this object
	 * through the FfbTeamfid foreign key attribute.
	 *
	 * @param      FfbTeamfid $l FfbTeamfid
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbTeamfid(FfbTeamfid $l)
	{
		if ($this->collFfbTeamfids === null) {
			$this->initFfbTeamfids();
		}
		if (!$this->collFfbTeamfids->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbTeamfids[]= $l;
			$l->setFfbTeam($this);
		}
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->team_id = null;
		$this->team_foreign_id = null;
		$this->team_name = null;
		$this->team_nationality = null;
		$this->team_avg_price = null;
		$this->team_num_players = null;
		$this->team_status = null;
		$this->alreadyInSave = false;
		$this->alreadyInValidation = false;
		$this->clearAllReferences();
		$this->applyDefaultValues();
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
			if ($this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam) {
				foreach ((array) $this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam) {
				foreach ((array) $this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerteams) {
				foreach ((array) $this->collFfbPlayerteams as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbMatchsRelatedByMatchHometeamId) {
				foreach ((array) $this->collFfbMatchsRelatedByMatchHometeamId as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbMatchsRelatedByMatchGuestteamId) {
				foreach ((array) $this->collFfbMatchsRelatedByMatchGuestteamId as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerfids) {
				foreach ((array) $this->collFfbPlayerfids as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbTeamfids) {
				foreach ((array) $this->collFfbTeamfids as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam = null;
		$this->collWebUserDetailssRelatedByUserDetailsFfbOwnTeam = null;
		$this->collFfbPlayerteams = null;
		$this->collFfbMatchsRelatedByMatchHometeamId = null;
		$this->collFfbMatchsRelatedByMatchGuestteamId = null;
		$this->collFfbPlayerfids = null;
		$this->collFfbTeamfids = null;
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

} // BaseFfbTeam
