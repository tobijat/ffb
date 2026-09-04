<?php


/**
 * Base class that represents a row from the 'ffb_playerteam' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerteam extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbPlayerteamPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbPlayerteamPeer
	 */
	protected static $peer;

	/**
	 * The value for the playerteam_id field.
	 * @var        int
	 */
	protected $playerteam_id;

	/**
	 * The value for the playerteam_player_id field.
	 * @var        int
	 */
	protected $playerteam_player_id;

	/**
	 * The value for the playerteam_team_id field.
	 * @var        int
	 */
	protected $playerteam_team_id;

	/**
	 * The value for the playerteam_player_picture field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $playerteam_player_picture;

	/**
	 * The value for the playerteam_status field.
	 * Note: this column has a database default value of: true
	 * @var        boolean
	 */
	protected $playerteam_status;

	/**
	 * The value for the playerteam_player_price field.
	 * Note: this column has a database default value of: 0
	 * @var        double
	 */
	protected $playerteam_player_price;

	/**
	 * The value for the playerteam_player_position field.
	 * Note: this column has a database default value of: 'd'
	 * @var        string
	 */
	protected $playerteam_player_position;

	/**
	 * The value for the playerteam_date_transfer field.
	 * @var        string
	 */
	protected $playerteam_date_transfer;

	/**
	 * @var        FfbPlayer
	 */
	protected $aFfbPlayer;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeam;

	/**
	 * @var        array FfbPlayerprice[] Collection to store aggregation of FfbPlayerprice objects.
	 */
	protected $collFfbPlayerprices;

	/**
	 * @var        array FfbGoal[] Collection to store aggregation of FfbGoal objects.
	 */
	protected $collFfbGoals;

	/**
	 * @var        array FfbPsgoal[] Collection to store aggregation of FfbPsgoal objects.
	 */
	protected $collFfbPsgoals;

	/**
	 * @var        array FfbPlayerstats[] Collection to store aggregation of FfbPlayerstats objects.
	 */
	protected $collFfbPlayerstatss;

	/**
	 * @var        array FfbPlayerfid[] Collection to store aggregation of FfbPlayerfid objects.
	 */
	protected $collFfbPlayerfids;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId1;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId2;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId3;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId4;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId5;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId6;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId7;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId8;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId9;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId10;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteamsRelatedByUserteamPlayerId11;

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
		$this->playerteam_player_picture = '';
		$this->playerteam_status = true;
		$this->playerteam_player_price = 0;
		$this->playerteam_player_position = 'd';
	}

	/**
	 * Initializes internal state of BaseFfbPlayerteam object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [playerteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerteamId()
	{
		return $this->playerteam_id;
	}

	/**
	 * Get the [playerteam_player_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerteamPlayerId()
	{
		return $this->playerteam_player_id;
	}

	/**
	 * Get the [playerteam_team_id] column value.
	 * 
	 * @return     int
	 */
	public function getPlayerteamTeamId()
	{
		return $this->playerteam_team_id;
	}

	/**
	 * Get the [playerteam_player_picture] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerteamPlayerPicture()
	{
		return $this->playerteam_player_picture;
	}

	/**
	 * Get the [playerteam_status] column value.
	 * 
	 * @return     boolean
	 */
	public function getPlayerteamStatus()
	{
		return $this->playerteam_status;
	}

	/**
	 * Get the [playerteam_player_price] column value.
	 * 
	 * @return     double
	 */
	public function getPlayerteamPlayerPrice()
	{
		return $this->playerteam_player_price;
	}

	/**
	 * Get the [playerteam_player_position] column value.
	 * 
	 * @return     string
	 */
	public function getPlayerteamPlayerPosition()
	{
		return $this->playerteam_player_position;
	}

	/**
	 * Get the [optionally formatted] temporal [playerteam_date_transfer] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getPlayerteamDateTransfer($format = 'Y-m-d H:i:s')
	{
		if ($this->playerteam_date_transfer === null) {
			return null;
		}


		if ($this->playerteam_date_transfer === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->playerteam_date_transfer);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->playerteam_date_transfer, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return $dt->format(strtr($format, array('%Y'=>'Y','%m'=>'m','%d'=>'d','%H'=>'H','%M'=>'i','%S'=>'s','%A'=>'l','%B'=>'F','%a'=>'D','%b'=>'M','%%'=>'%')));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [playerteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerteam_id !== $v) {
			$this->playerteam_id = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_ID;
		}

		return $this;
	} // setPlayerteamId()

	/**
	 * Set the value of [playerteam_player_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamPlayerId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerteam_player_id !== $v) {
			$this->playerteam_player_id = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID;
		}

		if ($this->aFfbPlayer !== null && $this->aFfbPlayer->getPlayerId() !== $v) {
			$this->aFfbPlayer = null;
		}

		return $this;
	} // setPlayerteamPlayerId()

	/**
	 * Set the value of [playerteam_team_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamTeamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->playerteam_team_id !== $v) {
			$this->playerteam_team_id = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID;
		}

		if ($this->aFfbTeam !== null && $this->aFfbTeam->getTeamId() !== $v) {
			$this->aFfbTeam = null;
		}

		return $this;
	} // setPlayerteamTeamId()

	/**
	 * Set the value of [playerteam_player_picture] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamPlayerPicture($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerteam_player_picture !== $v || $this->isNew()) {
			$this->playerteam_player_picture = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PICTURE;
		}

		return $this;
	} // setPlayerteamPlayerPicture()

	/**
	 * Set the value of [playerteam_status] column.
	 * 
	 * @param      boolean $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamStatus($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->playerteam_status !== $v || $this->isNew()) {
			$this->playerteam_status = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_STATUS;
		}

		return $this;
	} // setPlayerteamStatus()

	/**
	 * Set the value of [playerteam_player_price] column.
	 * 
	 * @param      double $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamPlayerPrice($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->playerteam_player_price !== $v || $this->isNew()) {
			$this->playerteam_player_price = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE;
		}

		return $this;
	} // setPlayerteamPlayerPrice()

	/**
	 * Set the value of [playerteam_player_position] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamPlayerPosition($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->playerteam_player_position !== $v || $this->isNew()) {
			$this->playerteam_player_position = $v;
			$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION;
		}

		return $this;
	} // setPlayerteamPlayerPosition()

	/**
	 * Sets the value of [playerteam_date_transfer] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 */
	public function setPlayerteamDateTransfer($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->playerteam_date_transfer !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->playerteam_date_transfer !== null && $tmpDt = new DateTime($this->playerteam_date_transfer)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->playerteam_date_transfer = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER;
			}
		} // if either are not null

		return $this;
	} // setPlayerteamDateTransfer()

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
			if ($this->playerteam_player_picture !== '') {
				return false;
			}

			if ($this->playerteam_status !== true) {
				return false;
			}

			if ($this->playerteam_player_price !== 0) {
				return false;
			}

			if ($this->playerteam_player_position !== 'd') {
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

			$this->playerteam_id = (($row[$startcol + 0] ?? null) !== null) ? (int) $row[$startcol + 0] : null;
			$this->playerteam_player_id = (($row[$startcol + 1] ?? null) !== null) ? (int) $row[$startcol + 1] : null;
			$this->playerteam_team_id = (($row[$startcol + 2] ?? null) !== null) ? (int) $row[$startcol + 2] : null;
			$this->playerteam_player_picture = (($row[$startcol + 3] ?? null) !== null) ? (string) $row[$startcol + 3] : null;
			$this->playerteam_status = (($row[$startcol + 4] ?? null) !== null) ? (boolean) $row[$startcol + 4] : null;
			$this->playerteam_player_price = (($row[$startcol + 5] ?? null) !== null) ? (double) $row[$startcol + 5] : null;
			$this->playerteam_player_position = (($row[$startcol + 6] ?? null) !== null) ? (string) $row[$startcol + 6] : null;
			$this->playerteam_date_transfer = (($row[$startcol + 7] ?? null) !== null) ? (string) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 8; // 8 = FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbPlayerteam object", $e);
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

		if ($this->aFfbPlayer !== null && $this->playerteam_player_id !== $this->aFfbPlayer->getPlayerId()) {
			$this->aFfbPlayer = null;
		}
		if ($this->aFfbTeam !== null && $this->playerteam_team_id !== $this->aFfbTeam->getTeamId()) {
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
	public function reload($deep = false, ?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbPlayerteamPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aFfbPlayer = null;
			$this->aFfbTeam = null;
			$this->collFfbPlayerprices = null;

			$this->collFfbGoals = null;

			$this->collFfbPsgoals = null;

			$this->collFfbPlayerstatss = null;

			$this->collFfbPlayerfids = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId1 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId2 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId3 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId4 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId5 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId6 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId7 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId8 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId9 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId10 = null;

			$this->collFfbUserteamsRelatedByUserteamPlayerId11 = null;

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
	public function delete(?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbPlayerteamQuery::create()
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
	public function save(?PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbPlayerteamPeer::addInstanceToPool($this);
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

			if ($this->aFfbPlayer !== null) {
				if ($this->aFfbPlayer->isModified() || $this->aFfbPlayer->isNew()) {
					$affectedRows += $this->aFfbPlayer->save($con);
				}
				$this->setFfbPlayer($this->aFfbPlayer);
			}

			if ($this->aFfbTeam !== null) {
				if ($this->aFfbTeam->isModified() || $this->aFfbTeam->isNew()) {
					$affectedRows += $this->aFfbTeam->save($con);
				}
				$this->setFfbTeam($this->aFfbTeam);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbPlayerteamPeer::PLAYERTEAM_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbPlayerteamPeer::PLAYERTEAM_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbPlayerteamPeer::PLAYERTEAM_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setPlayerteamId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbPlayerteamPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collFfbPlayerprices !== null) {
				foreach ($this->collFfbPlayerprices as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbGoals !== null) {
				foreach ($this->collFfbGoals as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPsgoals !== null) {
				foreach ($this->collFfbPsgoals as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPlayerstatss !== null) {
				foreach ($this->collFfbPlayerstatss as $referrerFK) {
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

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId1 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId1 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId2 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId2 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId3 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId3 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId4 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId4 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId5 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId5 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId6 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId6 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId7 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId7 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId8 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId8 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId9 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId9 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId10 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId10 as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteamsRelatedByUserteamPlayerId11 !== null) {
				foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId11 as $referrerFK) {
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

			if ($this->aFfbPlayer !== null) {
				if (!$this->aFfbPlayer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayer->getValidationFailures());
				}
			}

			if ($this->aFfbTeam !== null) {
				if (!$this->aFfbTeam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeam->getValidationFailures());
				}
			}


			if (($retval = FfbPlayerteamPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collFfbPlayerprices !== null) {
					foreach ($this->collFfbPlayerprices as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbGoals !== null) {
					foreach ($this->collFfbGoals as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPsgoals !== null) {
					foreach ($this->collFfbPsgoals as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPlayerstatss !== null) {
					foreach ($this->collFfbPlayerstatss as $referrerFK) {
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

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId1 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId1 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId2 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId2 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId3 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId3 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId4 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId4 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId5 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId5 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId6 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId6 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId7 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId7 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId8 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId8 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId9 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId9 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId10 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId10 as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteamsRelatedByUserteamPlayerId11 !== null) {
					foreach ($this->collFfbUserteamsRelatedByUserteamPlayerId11 as $referrerFK) {
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
		$pos = FfbPlayerteamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPlayerteamId();
				break;
			case 1:
				return $this->getPlayerteamPlayerId();
				break;
			case 2:
				return $this->getPlayerteamTeamId();
				break;
			case 3:
				return $this->getPlayerteamPlayerPicture();
				break;
			case 4:
				return $this->getPlayerteamStatus();
				break;
			case 5:
				return $this->getPlayerteamPlayerPrice();
				break;
			case 6:
				return $this->getPlayerteamPlayerPosition();
				break;
			case 7:
				return $this->getPlayerteamDateTransfer();
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
		$keys = FfbPlayerteamPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getPlayerteamId(),
			$keys[1] => $this->getPlayerteamPlayerId(),
			$keys[2] => $this->getPlayerteamTeamId(),
			$keys[3] => $this->getPlayerteamPlayerPicture(),
			$keys[4] => $this->getPlayerteamStatus(),
			$keys[5] => $this->getPlayerteamPlayerPrice(),
			$keys[6] => $this->getPlayerteamPlayerPosition(),
			$keys[7] => $this->getPlayerteamDateTransfer(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aFfbPlayer) {
				$result['FfbPlayer'] = $this->aFfbPlayer->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbTeam) {
				$result['FfbTeam'] = $this->aFfbTeam->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = FfbPlayerteamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPlayerteamId($value);
				break;
			case 1:
				$this->setPlayerteamPlayerId($value);
				break;
			case 2:
				$this->setPlayerteamTeamId($value);
				break;
			case 3:
				$this->setPlayerteamPlayerPicture($value);
				break;
			case 4:
				$this->setPlayerteamStatus($value);
				break;
			case 5:
				$this->setPlayerteamPlayerPrice($value);
				break;
			case 6:
				$this->setPlayerteamPlayerPosition($value);
				break;
			case 7:
				$this->setPlayerteamDateTransfer($value);
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
		$keys = FfbPlayerteamPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setPlayerteamId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setPlayerteamPlayerId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPlayerteamTeamId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPlayerteamPlayerPicture($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setPlayerteamStatus($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPlayerteamPlayerPrice($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPlayerteamPlayerPosition($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setPlayerteamDateTransfer($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbPlayerteamPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_ID)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_ID, $this->playerteam_id);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $this->playerteam_player_id);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $this->playerteam_team_id);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PICTURE)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PICTURE, $this->playerteam_player_picture);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_STATUS)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, $this->playerteam_status);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE, $this->playerteam_player_price);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $this->playerteam_player_position);
		if ($this->isColumnModified(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER)) $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER, $this->playerteam_date_transfer);

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
		$criteria = new Criteria(FfbPlayerteamPeer::DATABASE_NAME);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_ID, $this->playerteam_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getPlayerteamId();
	}

	/**
	 * Generic method to set the primary key (playerteam_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setPlayerteamId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getPlayerteamId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbPlayerteam (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setPlayerteamPlayerId($this->playerteam_player_id);
		$copyObj->setPlayerteamTeamId($this->playerteam_team_id);
		$copyObj->setPlayerteamPlayerPicture($this->playerteam_player_picture);
		$copyObj->setPlayerteamStatus($this->playerteam_status);
		$copyObj->setPlayerteamPlayerPrice($this->playerteam_player_price);
		$copyObj->setPlayerteamPlayerPosition($this->playerteam_player_position);
		$copyObj->setPlayerteamDateTransfer($this->playerteam_date_transfer);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getFfbPlayerprices() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerprice($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbGoals() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbGoal($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPsgoals() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPsgoal($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerstatss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerstats($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPlayerfids() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPlayerfid($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId1() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId1($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId2() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId2($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId3() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId3($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId4() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId4($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId5() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId5($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId6() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId6($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId7() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId7($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId8() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId8($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId9() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId9($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId10() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId10($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteamsRelatedByUserteamPlayerId11() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteamRelatedByUserteamPlayerId11($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setPlayerteamId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbPlayerteam Clone of current object.
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
	 * @return     FfbPlayerteamPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbPlayerteamPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a FfbPlayer object.
	 *
	 * @param      FfbPlayer $v
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayer(?FfbPlayer $v = null)
	{
		if ($v === null) {
			$this->setPlayerteamPlayerId(NULL);
		} else {
			$this->setPlayerteamPlayerId($v->getPlayerId());
		}

		$this->aFfbPlayer = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayer object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerteam($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbPlayer object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbPlayer The associated FfbPlayer object.
	 * @throws     PropelException
	 */
	public function getFfbPlayer(?PropelPDO $con = null)
	{
		if ($this->aFfbPlayer === null && ($this->playerteam_player_id !== null)) {
			$this->aFfbPlayer = FfbPlayerQuery::create()->findPk($this->playerteam_player_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayer->addFfbPlayerteams($this);
			 */
		}
		return $this->aFfbPlayer;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     FfbPlayerteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeam(?FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setPlayerteamTeamId(NULL);
		} else {
			$this->setPlayerteamTeamId($v->getTeamId());
		}

		$this->aFfbTeam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbPlayerteam($this);
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
	public function getFfbTeam(?PropelPDO $con = null)
	{
		if ($this->aFfbTeam === null && ($this->playerteam_team_id !== null)) {
			$this->aFfbTeam = FfbTeamQuery::create()->findPk($this->playerteam_team_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbTeam->addFfbPlayerteams($this);
			 */
		}
		return $this->aFfbTeam;
	}

	/**
	 * Clears out the collFfbPlayerprices collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerprices()
	 */
	public function clearFfbPlayerprices()
	{
		$this->collFfbPlayerprices = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerprices collection.
	 *
	 * By default this just sets the collFfbPlayerprices collection to an empty array (like clearcollFfbPlayerprices());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerprices()
	{
		$this->collFfbPlayerprices = new PropelObjectCollection();
		$this->collFfbPlayerprices->setModel('FfbPlayerprice');
	}

	/**
	 * Gets an array of FfbPlayerprice objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerprice[] List of FfbPlayerprice objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerprices($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerprices || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerprices) {
				// return empty collection
				$this->initFfbPlayerprices();
			} else {
				$collFfbPlayerprices = FfbPlayerpriceQuery::create(null, $criteria)
					->filterByFfbPlayerteam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerprices;
				}
				$this->collFfbPlayerprices = $collFfbPlayerprices;
			}
		}
		return $this->collFfbPlayerprices;
	}

	/**
	 * Returns the number of related FfbPlayerprice objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerprice objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerprices(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerprices || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerprices) {
				return 0;
			} else {
				$query = FfbPlayerpriceQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerprices);
		}
	}

	/**
	 * Method called to associate a FfbPlayerprice object to this object
	 * through the FfbPlayerprice foreign key attribute.
	 *
	 * @param      FfbPlayerprice $l FfbPlayerprice
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerprice(FfbPlayerprice $l)
	{
		if ($this->collFfbPlayerprices === null) {
			$this->initFfbPlayerprices();
		}
		if (!$this->collFfbPlayerprices->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerprices[]= $l;
			$l->setFfbPlayerteam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbPlayerprices from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerprice[] List of FfbPlayerprice objects
	 */
	public function getFfbPlayerpricesJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerpriceQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbPlayerprices($query, $con);
	}

	/**
	 * Clears out the collFfbGoals collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbGoals()
	 */
	public function clearFfbGoals()
	{
		$this->collFfbGoals = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbGoals collection.
	 *
	 * By default this just sets the collFfbGoals collection to an empty array (like clearcollFfbGoals());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbGoals()
	{
		$this->collFfbGoals = new PropelObjectCollection();
		$this->collFfbGoals->setModel('FfbGoal');
	}

	/**
	 * Gets an array of FfbGoal objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbGoal[] List of FfbGoal objects
	 * @throws     PropelException
	 */
	public function getFfbGoals($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbGoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbGoals) {
				// return empty collection
				$this->initFfbGoals();
			} else {
				$collFfbGoals = FfbGoalQuery::create(null, $criteria)
					->filterByFfbPlayerteam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbGoals;
				}
				$this->collFfbGoals = $collFfbGoals;
			}
		}
		return $this->collFfbGoals;
	}

	/**
	 * Returns the number of related FfbGoal objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbGoal objects.
	 * @throws     PropelException
	 */
	public function countFfbGoals(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbGoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbGoals) {
				return 0;
			} else {
				$query = FfbGoalQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbGoals);
		}
	}

	/**
	 * Method called to associate a FfbGoal object to this object
	 * through the FfbGoal foreign key attribute.
	 *
	 * @param      FfbGoal $l FfbGoal
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbGoal(FfbGoal $l)
	{
		if ($this->collFfbGoals === null) {
			$this->initFfbGoals();
		}
		if (!$this->collFfbGoals->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbGoals[]= $l;
			$l->setFfbPlayerteam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbGoals from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbGoal[] List of FfbGoal objects
	 */
	public function getFfbGoalsJoinFfbMatch($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbGoalQuery::create(null, $criteria);
		$query->joinWith('FfbMatch', $join_behavior);

		return $this->getFfbGoals($query, $con);
	}

	/**
	 * Clears out the collFfbPsgoals collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPsgoals()
	 */
	public function clearFfbPsgoals()
	{
		$this->collFfbPsgoals = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPsgoals collection.
	 *
	 * By default this just sets the collFfbPsgoals collection to an empty array (like clearcollFfbPsgoals());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPsgoals()
	{
		$this->collFfbPsgoals = new PropelObjectCollection();
		$this->collFfbPsgoals->setModel('FfbPsgoal');
	}

	/**
	 * Gets an array of FfbPsgoal objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPsgoal[] List of FfbPsgoal objects
	 * @throws     PropelException
	 */
	public function getFfbPsgoals($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPsgoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPsgoals) {
				// return empty collection
				$this->initFfbPsgoals();
			} else {
				$collFfbPsgoals = FfbPsgoalQuery::create(null, $criteria)
					->filterByFfbPlayerteam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPsgoals;
				}
				$this->collFfbPsgoals = $collFfbPsgoals;
			}
		}
		return $this->collFfbPsgoals;
	}

	/**
	 * Returns the number of related FfbPsgoal objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPsgoal objects.
	 * @throws     PropelException
	 */
	public function countFfbPsgoals(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPsgoals || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPsgoals) {
				return 0;
			} else {
				$query = FfbPsgoalQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPsgoals);
		}
	}

	/**
	 * Method called to associate a FfbPsgoal object to this object
	 * through the FfbPsgoal foreign key attribute.
	 *
	 * @param      FfbPsgoal $l FfbPsgoal
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPsgoal(FfbPsgoal $l)
	{
		if ($this->collFfbPsgoals === null) {
			$this->initFfbPsgoals();
		}
		if (!$this->collFfbPsgoals->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPsgoals[]= $l;
			$l->setFfbPlayerteam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbPsgoals from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPsgoal[] List of FfbPsgoal objects
	 */
	public function getFfbPsgoalsJoinFfbMatch($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPsgoalQuery::create(null, $criteria);
		$query->joinWith('FfbMatch', $join_behavior);

		return $this->getFfbPsgoals($query, $con);
	}

	/**
	 * Clears out the collFfbPlayerstatss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPlayerstatss()
	 */
	public function clearFfbPlayerstatss()
	{
		$this->collFfbPlayerstatss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPlayerstatss collection.
	 *
	 * By default this just sets the collFfbPlayerstatss collection to an empty array (like clearcollFfbPlayerstatss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPlayerstatss()
	{
		$this->collFfbPlayerstatss = new PropelObjectCollection();
		$this->collFfbPlayerstatss->setModel('FfbPlayerstats');
	}

	/**
	 * Gets an array of FfbPlayerstats objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerstatss($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerstatss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerstatss) {
				// return empty collection
				$this->initFfbPlayerstatss();
			} else {
				$collFfbPlayerstatss = FfbPlayerstatsQuery::create(null, $criteria)
					->filterByFfbPlayerteam($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPlayerstatss;
				}
				$this->collFfbPlayerstatss = $collFfbPlayerstatss;
			}
		}
		return $this->collFfbPlayerstatss;
	}

	/**
	 * Returns the number of related FfbPlayerstats objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPlayerstats objects.
	 * @throws     PropelException
	 */
	public function countFfbPlayerstatss(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerstatss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerstatss) {
				return 0;
			} else {
				$query = FfbPlayerstatsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteam($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPlayerstatss);
		}
	}

	/**
	 * Method called to associate a FfbPlayerstats object to this object
	 * through the FfbPlayerstats foreign key attribute.
	 *
	 * @param      FfbPlayerstats $l FfbPlayerstats
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPlayerstats(FfbPlayerstats $l)
	{
		if ($this->collFfbPlayerstatss === null) {
			$this->initFfbPlayerstatss();
		}
		if (!$this->collFfbPlayerstatss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPlayerstatss[]= $l;
			$l->setFfbPlayerteam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 */
	public function getFfbPlayerstatssJoinFfbMatch($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerstatsQuery::create(null, $criteria);
		$query->joinWith('FfbMatch', $join_behavior);

		return $this->getFfbPlayerstatss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbPlayerstatss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerstats[] List of FfbPlayerstats objects
	 */
	public function getFfbPlayerstatssJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerstatsQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbPlayerstatss($query, $con);
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
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPlayerfid[] List of FfbPlayerfid objects
	 * @throws     PropelException
	 */
	public function getFfbPlayerfids($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbPlayerfids || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPlayerfids) {
				// return empty collection
				$this->initFfbPlayerfids();
			} else {
				$collFfbPlayerfids = FfbPlayerfidQuery::create(null, $criteria)
					->filterByFfbPlayerteam($this)
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
	public function countFfbPlayerfids(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
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
					->filterByFfbPlayerteam($this)
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
			$l->setFfbPlayerteam($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbPlayerfids from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPlayerfid[] List of FfbPlayerfid objects
	 */
	public function getFfbPlayerfidsJoinFfbTeam($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPlayerfidQuery::create(null, $criteria);
		$query->joinWith('FfbTeam', $join_behavior);

		return $this->getFfbPlayerfids($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId1 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId1()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId1()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId1 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId1 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId1 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId1());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId1()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId1 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId1->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId1($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId1 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId1) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId1();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId1 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId1($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId1;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId1 = $collFfbUserteamsRelatedByUserteamPlayerId1;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId1;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId1(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId1 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId1) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId1($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId1);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId1(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId1 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId1();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId1->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId1[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId1($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId1 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId1JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId1($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId1 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId1JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId1($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId2 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId2()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId2()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId2 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId2 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId2 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId2());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId2()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId2 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId2->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId2($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId2 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId2) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId2();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId2 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId2($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId2;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId2 = $collFfbUserteamsRelatedByUserteamPlayerId2;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId2;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId2(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId2 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId2) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId2($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId2);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId2(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId2 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId2();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId2->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId2[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId2($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId2 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId2JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId2($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId2 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId2JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId2($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId3 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId3()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId3()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId3 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId3 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId3 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId3());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId3()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId3 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId3->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId3($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId3 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId3) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId3();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId3 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId3($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId3;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId3 = $collFfbUserteamsRelatedByUserteamPlayerId3;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId3;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId3(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId3 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId3) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId3($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId3);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId3(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId3 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId3();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId3->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId3[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId3($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId3 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId3JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId3($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId3 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId3JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId3($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId4 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId4()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId4()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId4 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId4 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId4 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId4());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId4()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId4 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId4->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId4($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId4 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId4) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId4();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId4 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId4($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId4;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId4 = $collFfbUserteamsRelatedByUserteamPlayerId4;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId4;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId4(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId4 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId4) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId4($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId4);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId4(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId4 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId4();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId4->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId4[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId4($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId4 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId4JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId4($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId4 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId4JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId4($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId5 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId5()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId5()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId5 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId5 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId5 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId5());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId5()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId5 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId5->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId5($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId5 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId5) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId5();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId5 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId5($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId5;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId5 = $collFfbUserteamsRelatedByUserteamPlayerId5;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId5;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId5(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId5 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId5) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId5($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId5);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId5(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId5 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId5();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId5->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId5[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId5($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId5 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId5JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId5($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId5 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId5JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId5($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId6 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId6()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId6()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId6 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId6 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId6 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId6());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId6()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId6 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId6->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId6($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId6 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId6) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId6();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId6 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId6($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId6;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId6 = $collFfbUserteamsRelatedByUserteamPlayerId6;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId6;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId6(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId6 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId6) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId6($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId6);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId6(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId6 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId6();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId6->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId6[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId6($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId6 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId6JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId6($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId6 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId6JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId6($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId7 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId7()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId7()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId7 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId7 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId7 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId7());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId7()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId7 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId7->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId7($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId7 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId7) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId7();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId7 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId7($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId7;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId7 = $collFfbUserteamsRelatedByUserteamPlayerId7;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId7;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId7(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId7 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId7) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId7($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId7);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId7(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId7 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId7();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId7->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId7[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId7($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId7 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId7JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId7($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId7 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId7JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId7($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId8 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId8()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId8()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId8 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId8 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId8 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId8());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId8()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId8 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId8->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId8($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId8 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId8) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId8();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId8 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId8($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId8;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId8 = $collFfbUserteamsRelatedByUserteamPlayerId8;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId8;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId8(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId8 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId8) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId8($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId8);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId8(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId8 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId8();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId8->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId8[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId8($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId8 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId8JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId8($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId8 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId8JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId8($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId9 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId9()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId9()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId9 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId9 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId9 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId9());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId9()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId9 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId9->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId9($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId9 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId9) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId9();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId9 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId9($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId9;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId9 = $collFfbUserteamsRelatedByUserteamPlayerId9;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId9;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId9(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId9 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId9) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId9($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId9);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId9(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId9 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId9();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId9->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId9[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId9($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId9 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId9JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId9($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId9 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId9JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId9($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId10 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId10()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId10()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId10 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId10 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId10 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId10());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId10()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId10 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId10->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId10($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId10 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId10) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId10();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId10 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId10($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId10;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId10 = $collFfbUserteamsRelatedByUserteamPlayerId10;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId10;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId10(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId10 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId10) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId10($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId10);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId10(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId10 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId10();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId10->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId10[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId10($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId10 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId10JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId10($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId10 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId10JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId10($query, $con);
	}

	/**
	 * Clears out the collFfbUserteamsRelatedByUserteamPlayerId11 collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteamsRelatedByUserteamPlayerId11()
	 */
	public function clearFfbUserteamsRelatedByUserteamPlayerId11()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId11 = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteamsRelatedByUserteamPlayerId11 collection.
	 *
	 * By default this just sets the collFfbUserteamsRelatedByUserteamPlayerId11 collection to an empty array (like clearcollFfbUserteamsRelatedByUserteamPlayerId11());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteamsRelatedByUserteamPlayerId11()
	{
		$this->collFfbUserteamsRelatedByUserteamPlayerId11 = new PropelObjectCollection();
		$this->collFfbUserteamsRelatedByUserteamPlayerId11->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this FfbPlayerteam is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId11($criteria = null, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId11 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId11) {
				// return empty collection
				$this->initFfbUserteamsRelatedByUserteamPlayerId11();
			} else {
				$collFfbUserteamsRelatedByUserteamPlayerId11 = FfbUserteamQuery::create(null, $criteria)
					->filterByFfbPlayerteamRelatedByUserteamPlayerId11($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteamsRelatedByUserteamPlayerId11;
				}
				$this->collFfbUserteamsRelatedByUserteamPlayerId11 = $collFfbUserteamsRelatedByUserteamPlayerId11;
			}
		}
		return $this->collFfbUserteamsRelatedByUserteamPlayerId11;
	}

	/**
	 * Returns the number of related FfbUserteam objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbUserteam objects.
	 * @throws     PropelException
	 */
	public function countFfbUserteamsRelatedByUserteamPlayerId11(?Criteria $criteria = null, $distinct = false, ?PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteamsRelatedByUserteamPlayerId11 || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteamsRelatedByUserteamPlayerId11) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByFfbPlayerteamRelatedByUserteamPlayerId11($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteamsRelatedByUserteamPlayerId11);
		}
	}

	/**
	 * Method called to associate a FfbUserteam object to this object
	 * through the FfbUserteam foreign key attribute.
	 *
	 * @param      FfbUserteam $l FfbUserteam
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbUserteamRelatedByUserteamPlayerId11(FfbUserteam $l)
	{
		if ($this->collFfbUserteamsRelatedByUserteamPlayerId11 === null) {
			$this->initFfbUserteamsRelatedByUserteamPlayerId11();
		}
		if (!$this->collFfbUserteamsRelatedByUserteamPlayerId11->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteamsRelatedByUserteamPlayerId11[]= $l;
			$l->setFfbPlayerteamRelatedByUserteamPlayerId11($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId11 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId11JoinWebUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('WebUser', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId11($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this FfbPlayerteam is new, it will return
	 * an empty collection; or if this FfbPlayerteam has previously
	 * been saved, it will retrieve related FfbUserteamsRelatedByUserteamPlayerId11 from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in FfbPlayerteam.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsRelatedByUserteamPlayerId11JoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteamsRelatedByUserteamPlayerId11($query, $con);
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->playerteam_id = null;
		$this->playerteam_player_id = null;
		$this->playerteam_team_id = null;
		$this->playerteam_player_picture = null;
		$this->playerteam_status = null;
		$this->playerteam_player_price = null;
		$this->playerteam_player_position = null;
		$this->playerteam_date_transfer = null;
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
			if ($this->collFfbPlayerprices) {
				foreach ((array) $this->collFfbPlayerprices as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbGoals) {
				foreach ((array) $this->collFfbGoals as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPsgoals) {
				foreach ((array) $this->collFfbPsgoals as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerstatss) {
				foreach ((array) $this->collFfbPlayerstatss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPlayerfids) {
				foreach ((array) $this->collFfbPlayerfids as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId1) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId1 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId2) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId2 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId3) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId3 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId4) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId4 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId5) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId5 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId6) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId6 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId7) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId7 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId8) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId8 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId9) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId9 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId10) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId10 as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteamsRelatedByUserteamPlayerId11) {
				foreach ((array) $this->collFfbUserteamsRelatedByUserteamPlayerId11 as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collFfbPlayerprices = null;
		$this->collFfbGoals = null;
		$this->collFfbPsgoals = null;
		$this->collFfbPlayerstatss = null;
		$this->collFfbPlayerfids = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId1 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId2 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId3 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId4 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId5 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId6 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId7 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId8 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId9 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId10 = null;
		$this->collFfbUserteamsRelatedByUserteamPlayerId11 = null;
		$this->aFfbPlayer = null;
		$this->aFfbTeam = null;
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

} // BaseFfbPlayerteam
