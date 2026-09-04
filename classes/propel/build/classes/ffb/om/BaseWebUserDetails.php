<?php


/**
 * Base class that represents a row from the 'web_user_details' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebUserDetails extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'WebUserDetailsPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WebUserDetailsPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the user_details_avatar field.
	 * @var        string
	 */
	protected $user_details_avatar;

	/**
	 * The value for the user_details_photo field.
	 * @var        string
	 */
	protected $user_details_photo;

	/**
	 * The value for the user_details_website field.
	 * @var        string
	 */
	protected $user_details_website;

	/**
	 * The value for the user_details_zip field.
	 * @var        string
	 */
	protected $user_details_zip;

	/**
	 * The value for the user_details_street field.
	 * @var        string
	 */
	protected $user_details_street;

	/**
	 * The value for the user_details_city field.
	 * @var        string
	 */
	protected $user_details_city;

	/**
	 * The value for the user_details_phone field.
	 * @var        string
	 */
	protected $user_details_phone;

	/**
	 * The value for the user_details_ffb_favourite_team field.
	 * @var        int
	 */
	protected $user_details_ffb_favourite_team;

	/**
	 * The value for the user_details_ffb_own_team field.
	 * @var        int
	 */
	protected $user_details_ffb_own_team;

	/**
	 * The value for the user_details_ffb_own_player field.
	 * @var        int
	 */
	protected $user_details_ffb_own_player;

	/**
	 * The value for the user_details_ffb_selected_game field.
	 * @var        int
	 */
	protected $user_details_ffb_selected_game;

	/**
	 * The value for the user_details_last_update field.
	 * @var        string
	 */
	protected $user_details_last_update;

	/**
	 * @var        WebUser
	 */
	protected $aWebUser;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeamRelatedByUserDetailsFfbFavouriteTeam;

	/**
	 * @var        FfbTeam
	 */
	protected $aFfbTeamRelatedByUserDetailsFfbOwnTeam;

	/**
	 * @var        FfbPlayer
	 */
	protected $aFfbPlayer;

	/**
	 * @var        FfbGame
	 */
	protected $aFfbGame;

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
	 * Get the [user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * Get the [user_details_avatar] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsAvatar()
	{
		return $this->user_details_avatar;
	}

	/**
	 * Get the [user_details_photo] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsPhoto()
	{
		return $this->user_details_photo;
	}

	/**
	 * Get the [user_details_website] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsWebsite()
	{
		return $this->user_details_website;
	}

	/**
	 * Get the [user_details_zip] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsZip()
	{
		return $this->user_details_zip;
	}

	/**
	 * Get the [user_details_street] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsStreet()
	{
		return $this->user_details_street;
	}

	/**
	 * Get the [user_details_city] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsCity()
	{
		return $this->user_details_city;
	}

	/**
	 * Get the [user_details_phone] column value.
	 * 
	 * @return     string
	 */
	public function getUserDetailsPhone()
	{
		return $this->user_details_phone;
	}

	/**
	 * Get the [user_details_ffb_favourite_team] column value.
	 * 
	 * @return     int
	 */
	public function getUserDetailsFfbFavouriteTeam()
	{
		return $this->user_details_ffb_favourite_team;
	}

	/**
	 * Get the [user_details_ffb_own_team] column value.
	 * 
	 * @return     int
	 */
	public function getUserDetailsFfbOwnTeam()
	{
		return $this->user_details_ffb_own_team;
	}

	/**
	 * Get the [user_details_ffb_own_player] column value.
	 * 
	 * @return     int
	 */
	public function getUserDetailsFfbOwnPlayer()
	{
		return $this->user_details_ffb_own_player;
	}

	/**
	 * Get the [user_details_ffb_selected_game] column value.
	 * 
	 * @return     int
	 */
	public function getUserDetailsFfbSelectedGame()
	{
		return $this->user_details_ffb_selected_game;
	}

	/**
	 * Get the [optionally formatted] temporal [user_details_last_update] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserDetailsLastUpdate($format = 'Y-m-d H:i:s')
	{
		if ($this->user_details_last_update === null) {
			return null;
		}


		if ($this->user_details_last_update === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->user_details_last_update);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->user_details_last_update, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [user_details_avatar] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsAvatar($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_avatar !== $v) {
			$this->user_details_avatar = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_AVATAR;
		}

		return $this;
	} // setUserDetailsAvatar()

	/**
	 * Set the value of [user_details_photo] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsPhoto($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_photo !== $v) {
			$this->user_details_photo = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_PHOTO;
		}

		return $this;
	} // setUserDetailsPhoto()

	/**
	 * Set the value of [user_details_website] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsWebsite($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_website !== $v) {
			$this->user_details_website = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_WEBSITE;
		}

		return $this;
	} // setUserDetailsWebsite()

	/**
	 * Set the value of [user_details_zip] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsZip($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_zip !== $v) {
			$this->user_details_zip = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_ZIP;
		}

		return $this;
	} // setUserDetailsZip()

	/**
	 * Set the value of [user_details_street] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsStreet($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_street !== $v) {
			$this->user_details_street = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_STREET;
		}

		return $this;
	} // setUserDetailsStreet()

	/**
	 * Set the value of [user_details_city] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsCity($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_city !== $v) {
			$this->user_details_city = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_CITY;
		}

		return $this;
	} // setUserDetailsCity()

	/**
	 * Set the value of [user_details_phone] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsPhone($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_details_phone !== $v) {
			$this->user_details_phone = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_PHONE;
		}

		return $this;
	} // setUserDetailsPhone()

	/**
	 * Set the value of [user_details_ffb_favourite_team] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsFfbFavouriteTeam($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_details_ffb_favourite_team !== $v) {
			$this->user_details_ffb_favourite_team = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM;
		}

		if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam !== null && $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->getTeamId() !== $v) {
			$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = null;
		}

		return $this;
	} // setUserDetailsFfbFavouriteTeam()

	/**
	 * Set the value of [user_details_ffb_own_team] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsFfbOwnTeam($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_details_ffb_own_team !== $v) {
			$this->user_details_ffb_own_team = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM;
		}

		if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam !== null && $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->getTeamId() !== $v) {
			$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = null;
		}

		return $this;
	} // setUserDetailsFfbOwnTeam()

	/**
	 * Set the value of [user_details_ffb_own_player] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsFfbOwnPlayer($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_details_ffb_own_player !== $v) {
			$this->user_details_ffb_own_player = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER;
		}

		if ($this->aFfbPlayer !== null && $this->aFfbPlayer->getPlayerId() !== $v) {
			$this->aFfbPlayer = null;
		}

		return $this;
	} // setUserDetailsFfbOwnPlayer()

	/**
	 * Set the value of [user_details_ffb_selected_game] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsFfbSelectedGame($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_details_ffb_selected_game !== $v) {
			$this->user_details_ffb_selected_game = $v;
			$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME;
		}

		if ($this->aFfbGame !== null && $this->aFfbGame->getGameId() !== $v) {
			$this->aFfbGame = null;
		}

		return $this;
	} // setUserDetailsFfbSelectedGame()

	/**
	 * Sets the value of [user_details_last_update] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebUserDetails The current object (for fluent API support)
	 */
	public function setUserDetailsLastUpdate($v)
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

		if ( $this->user_details_last_update !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->user_details_last_update !== null && $tmpDt = new DateTime($this->user_details_last_update)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->user_details_last_update = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE;
			}
		} // if either are not null

		return $this;
	} // setUserDetailsLastUpdate()

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

			$this->user_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->user_details_avatar = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->user_details_photo = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->user_details_website = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->user_details_zip = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->user_details_street = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->user_details_city = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->user_details_phone = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->user_details_ffb_favourite_team = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->user_details_ffb_own_team = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->user_details_ffb_own_player = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->user_details_ffb_selected_game = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->user_details_last_update = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 13; // 13 = WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WebUserDetails object", $e);
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
		if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam !== null && $this->user_details_ffb_favourite_team !== $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->getTeamId()) {
			$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = null;
		}
		if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam !== null && $this->user_details_ffb_own_team !== $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->getTeamId()) {
			$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = null;
		}
		if ($this->aFfbPlayer !== null && $this->user_details_ffb_own_player !== $this->aFfbPlayer->getPlayerId()) {
			$this->aFfbPlayer = null;
		}
		if ($this->aFfbGame !== null && $this->user_details_ffb_selected_game !== $this->aFfbGame->getGameId()) {
			$this->aFfbGame = null;
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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WebUserDetailsPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWebUser = null;
			$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = null;
			$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = null;
			$this->aFfbPlayer = null;
			$this->aFfbGame = null;
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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				WebUserDetailsQuery::create()
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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				WebUserDetailsPeer::addInstanceToPool($this);
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

			if ($this->aWebUser !== null) {
				if ($this->aWebUser->isModified() || $this->aWebUser->isNew()) {
					$affectedRows += $this->aWebUser->save($con);
				}
				$this->setWebUser($this->aWebUser);
			}

			if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam !== null) {
				if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->isModified() || $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->isNew()) {
					$affectedRows += $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->save($con);
				}
				$this->setFfbTeamRelatedByUserDetailsFfbFavouriteTeam($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam);
			}

			if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam !== null) {
				if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->isModified() || $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->isNew()) {
					$affectedRows += $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->save($con);
				}
				$this->setFfbTeamRelatedByUserDetailsFfbOwnTeam($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam);
			}

			if ($this->aFfbPlayer !== null) {
				if ($this->aFfbPlayer->isModified() || $this->aFfbPlayer->isNew()) {
					$affectedRows += $this->aFfbPlayer->save($con);
				}
				$this->setFfbPlayer($this->aFfbPlayer);
			}

			if ($this->aFfbGame !== null) {
				if ($this->aFfbGame->isModified() || $this->aFfbGame->isNew()) {
					$affectedRows += $this->aFfbGame->save($con);
				}
				$this->setFfbGame($this->aFfbGame);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setNew(false);
				} else {
					$affectedRows += WebUserDetailsPeer::doUpdate($this, $con);
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

			if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam !== null) {
				if (!$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->getValidationFailures());
				}
			}

			if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam !== null) {
				if (!$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->getValidationFailures());
				}
			}

			if ($this->aFfbPlayer !== null) {
				if (!$this->aFfbPlayer->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayer->getValidationFailures());
				}
			}

			if ($this->aFfbGame !== null) {
				if (!$this->aFfbGame->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbGame->getValidationFailures());
				}
			}


			if (($retval = WebUserDetailsPeer::doValidate($this, $columns)) !== true) {
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
		$pos = WebUserDetailsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUserId();
				break;
			case 1:
				return $this->getUserDetailsAvatar();
				break;
			case 2:
				return $this->getUserDetailsPhoto();
				break;
			case 3:
				return $this->getUserDetailsWebsite();
				break;
			case 4:
				return $this->getUserDetailsZip();
				break;
			case 5:
				return $this->getUserDetailsStreet();
				break;
			case 6:
				return $this->getUserDetailsCity();
				break;
			case 7:
				return $this->getUserDetailsPhone();
				break;
			case 8:
				return $this->getUserDetailsFfbFavouriteTeam();
				break;
			case 9:
				return $this->getUserDetailsFfbOwnTeam();
				break;
			case 10:
				return $this->getUserDetailsFfbOwnPlayer();
				break;
			case 11:
				return $this->getUserDetailsFfbSelectedGame();
				break;
			case 12:
				return $this->getUserDetailsLastUpdate();
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
		$keys = WebUserDetailsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getUserId(),
			$keys[1] => $this->getUserDetailsAvatar(),
			$keys[2] => $this->getUserDetailsPhoto(),
			$keys[3] => $this->getUserDetailsWebsite(),
			$keys[4] => $this->getUserDetailsZip(),
			$keys[5] => $this->getUserDetailsStreet(),
			$keys[6] => $this->getUserDetailsCity(),
			$keys[7] => $this->getUserDetailsPhone(),
			$keys[8] => $this->getUserDetailsFfbFavouriteTeam(),
			$keys[9] => $this->getUserDetailsFfbOwnTeam(),
			$keys[10] => $this->getUserDetailsFfbOwnPlayer(),
			$keys[11] => $this->getUserDetailsFfbSelectedGame(),
			$keys[12] => $this->getUserDetailsLastUpdate(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aWebUser) {
				$result['WebUser'] = $this->aWebUser->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam) {
				$result['FfbTeamRelatedByUserDetailsFfbFavouriteTeam'] = $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam) {
				$result['FfbTeamRelatedByUserDetailsFfbOwnTeam'] = $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayer) {
				$result['FfbPlayer'] = $this->aFfbPlayer->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbGame) {
				$result['FfbGame'] = $this->aFfbGame->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = WebUserDetailsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUserId($value);
				break;
			case 1:
				$this->setUserDetailsAvatar($value);
				break;
			case 2:
				$this->setUserDetailsPhoto($value);
				break;
			case 3:
				$this->setUserDetailsWebsite($value);
				break;
			case 4:
				$this->setUserDetailsZip($value);
				break;
			case 5:
				$this->setUserDetailsStreet($value);
				break;
			case 6:
				$this->setUserDetailsCity($value);
				break;
			case 7:
				$this->setUserDetailsPhone($value);
				break;
			case 8:
				$this->setUserDetailsFfbFavouriteTeam($value);
				break;
			case 9:
				$this->setUserDetailsFfbOwnTeam($value);
				break;
			case 10:
				$this->setUserDetailsFfbOwnPlayer($value);
				break;
			case 11:
				$this->setUserDetailsFfbSelectedGame($value);
				break;
			case 12:
				$this->setUserDetailsLastUpdate($value);
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
		$keys = WebUserDetailsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setUserId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserDetailsAvatar($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUserDetailsPhoto($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUserDetailsWebsite($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setUserDetailsZip($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUserDetailsStreet($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUserDetailsCity($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUserDetailsPhone($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUserDetailsFfbFavouriteTeam($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUserDetailsFfbOwnTeam($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setUserDetailsFfbOwnPlayer($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUserDetailsFfbSelectedGame($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setUserDetailsLastUpdate($arr[$keys[12]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WebUserDetailsPeer::DATABASE_NAME);

		if ($this->isColumnModified(WebUserDetailsPeer::USER_ID)) $criteria->add(WebUserDetailsPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_AVATAR)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_AVATAR, $this->user_details_avatar);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_PHOTO)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_PHOTO, $this->user_details_photo);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_WEBSITE)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_WEBSITE, $this->user_details_website);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_ZIP)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_ZIP, $this->user_details_zip);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_STREET)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_STREET, $this->user_details_street);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_CITY)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_CITY, $this->user_details_city);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_PHONE)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_PHONE, $this->user_details_phone);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, $this->user_details_ffb_favourite_team);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, $this->user_details_ffb_own_team);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $this->user_details_ffb_own_player);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $this->user_details_ffb_selected_game);
		if ($this->isColumnModified(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE)) $criteria->add(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE, $this->user_details_last_update);

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
		$criteria = new Criteria(WebUserDetailsPeer::DATABASE_NAME);
		$criteria->add(WebUserDetailsPeer::USER_ID, $this->user_id);

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
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getUserId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of WebUserDetails (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setUserId($this->user_id);
		$copyObj->setUserDetailsAvatar($this->user_details_avatar);
		$copyObj->setUserDetailsPhoto($this->user_details_photo);
		$copyObj->setUserDetailsWebsite($this->user_details_website);
		$copyObj->setUserDetailsZip($this->user_details_zip);
		$copyObj->setUserDetailsStreet($this->user_details_street);
		$copyObj->setUserDetailsCity($this->user_details_city);
		$copyObj->setUserDetailsPhone($this->user_details_phone);
		$copyObj->setUserDetailsFfbFavouriteTeam($this->user_details_ffb_favourite_team);
		$copyObj->setUserDetailsFfbOwnTeam($this->user_details_ffb_own_team);
		$copyObj->setUserDetailsFfbOwnPlayer($this->user_details_ffb_own_player);
		$copyObj->setUserDetailsFfbSelectedGame($this->user_details_ffb_selected_game);
		$copyObj->setUserDetailsLastUpdate($this->user_details_last_update);

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
	 * @return     WebUserDetails Clone of current object.
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
	 * @return     WebUserDetailsPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WebUserDetailsPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     WebUserDetails The current object (for fluent API support)
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
			$v->setWebUserDetails($this);
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
			$this->aWebUser = WebUserQuery::create()->findPk($this->user_id, $con);
			// Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
			$this->aWebUser->setWebUserDetails($this);
		}
		return $this->aWebUser;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     WebUserDetails The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeamRelatedByUserDetailsFfbFavouriteTeam(FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setUserDetailsFfbFavouriteTeam(NULL);
		} else {
			$this->setUserDetailsFfbFavouriteTeam($v->getTeamId());
		}

		$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($this);
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
	public function getFfbTeamRelatedByUserDetailsFfbFavouriteTeam(PropelPDO $con = null)
	{
		if ($this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam === null && ($this->user_details_ffb_favourite_team !== null)) {
			$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = FfbTeamQuery::create()->findPk($this->user_details_ffb_favourite_team, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam->addWebUserDetailssRelatedByUserDetailsFfbFavouriteTeam($this);
			 */
		}
		return $this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam;
	}

	/**
	 * Declares an association between this object and a FfbTeam object.
	 *
	 * @param      FfbTeam $v
	 * @return     WebUserDetails The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbTeamRelatedByUserDetailsFfbOwnTeam(FfbTeam $v = null)
	{
		if ($v === null) {
			$this->setUserDetailsFfbOwnTeam(NULL);
		} else {
			$this->setUserDetailsFfbOwnTeam($v->getTeamId());
		}

		$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbTeam object, it will not be re-added.
		if ($v !== null) {
			$v->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($this);
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
	public function getFfbTeamRelatedByUserDetailsFfbOwnTeam(PropelPDO $con = null)
	{
		if ($this->aFfbTeamRelatedByUserDetailsFfbOwnTeam === null && ($this->user_details_ffb_own_team !== null)) {
			$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = FfbTeamQuery::create()->findPk($this->user_details_ffb_own_team, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam->addWebUserDetailssRelatedByUserDetailsFfbOwnTeam($this);
			 */
		}
		return $this->aFfbTeamRelatedByUserDetailsFfbOwnTeam;
	}

	/**
	 * Declares an association between this object and a FfbPlayer object.
	 *
	 * @param      FfbPlayer $v
	 * @return     WebUserDetails The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayer(FfbPlayer $v = null)
	{
		if ($v === null) {
			$this->setUserDetailsFfbOwnPlayer(NULL);
		} else {
			$this->setUserDetailsFfbOwnPlayer($v->getPlayerId());
		}

		$this->aFfbPlayer = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayer object, it will not be re-added.
		if ($v !== null) {
			$v->addWebUserDetails($this);
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
	public function getFfbPlayer(PropelPDO $con = null)
	{
		if ($this->aFfbPlayer === null && ($this->user_details_ffb_own_player !== null)) {
			$this->aFfbPlayer = FfbPlayerQuery::create()->findPk($this->user_details_ffb_own_player, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayer->addWebUserDetailss($this);
			 */
		}
		return $this->aFfbPlayer;
	}

	/**
	 * Declares an association between this object and a FfbGame object.
	 *
	 * @param      FfbGame $v
	 * @return     WebUserDetails The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbGame(FfbGame $v = null)
	{
		if ($v === null) {
			$this->setUserDetailsFfbSelectedGame(NULL);
		} else {
			$this->setUserDetailsFfbSelectedGame($v->getGameId());
		}

		$this->aFfbGame = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbGame object, it will not be re-added.
		if ($v !== null) {
			$v->addWebUserDetails($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbGame object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbGame The associated FfbGame object.
	 * @throws     PropelException
	 */
	public function getFfbGame(PropelPDO $con = null)
	{
		if ($this->aFfbGame === null && ($this->user_details_ffb_selected_game !== null)) {
			$this->aFfbGame = FfbGameQuery::create()->findPk($this->user_details_ffb_selected_game, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbGame->addWebUserDetailss($this);
			 */
		}
		return $this->aFfbGame;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->user_id = null;
		$this->user_details_avatar = null;
		$this->user_details_photo = null;
		$this->user_details_website = null;
		$this->user_details_zip = null;
		$this->user_details_street = null;
		$this->user_details_city = null;
		$this->user_details_phone = null;
		$this->user_details_ffb_favourite_team = null;
		$this->user_details_ffb_own_team = null;
		$this->user_details_ffb_own_player = null;
		$this->user_details_ffb_selected_game = null;
		$this->user_details_last_update = null;
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

		$this->aWebUser = null;
		$this->aFfbTeamRelatedByUserDetailsFfbFavouriteTeam = null;
		$this->aFfbTeamRelatedByUserDetailsFfbOwnTeam = null;
		$this->aFfbPlayer = null;
		$this->aFfbGame = null;
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

} // BaseWebUserDetails
