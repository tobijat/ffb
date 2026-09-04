<?php


/**
 * Base class that represents a row from the 'ffb_userteam' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserteam extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'FfbUserteamPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        FfbUserteamPeer
	 */
	protected static $peer;

	/**
	 * The value for the userteam_id field.
	 * @var        int
	 */
	protected $userteam_id;

	/**
	 * The value for the userteam_user_id field.
	 * @var        int
	 */
	protected $userteam_user_id;

	/**
	 * The value for the userteam_date field.
	 * @var        string
	 */
	protected $userteam_date;

	/**
	 * The value for the userteam_player_id1 field.
	 * @var        int
	 */
	protected $userteam_player_id1;

	/**
	 * The value for the userteam_player_id2 field.
	 * @var        int
	 */
	protected $userteam_player_id2;

	/**
	 * The value for the userteam_player_id3 field.
	 * @var        int
	 */
	protected $userteam_player_id3;

	/**
	 * The value for the userteam_player_id4 field.
	 * @var        int
	 */
	protected $userteam_player_id4;

	/**
	 * The value for the userteam_player_id5 field.
	 * @var        int
	 */
	protected $userteam_player_id5;

	/**
	 * The value for the userteam_player_id6 field.
	 * @var        int
	 */
	protected $userteam_player_id6;

	/**
	 * The value for the userteam_player_id7 field.
	 * @var        int
	 */
	protected $userteam_player_id7;

	/**
	 * The value for the userteam_player_id8 field.
	 * @var        int
	 */
	protected $userteam_player_id8;

	/**
	 * The value for the userteam_player_id9 field.
	 * @var        int
	 */
	protected $userteam_player_id9;

	/**
	 * The value for the userteam_player_id10 field.
	 * @var        int
	 */
	protected $userteam_player_id10;

	/**
	 * The value for the userteam_player_id11 field.
	 * @var        int
	 */
	protected $userteam_player_id11;

	/**
	 * The value for the userteam_price field.
	 * Note: this column has a database default value of: '0'
	 * @var        string
	 */
	protected $userteam_price;

	/**
	 * The value for the userteam_matchround_id field.
	 * @var        int
	 */
	protected $userteam_matchround_id;

	/**
	 * The value for the userteam_score field.
	 * Note: this column has a database default value of: -1
	 * @var        int
	 */
	protected $userteam_score;

	/**
	 * The value for the userteam_wc_points field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $userteam_wc_points;

	/**
	 * @var        WebUser
	 */
	protected $aWebUser;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId1;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId2;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId3;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId4;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId5;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId6;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId7;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId8;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId9;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId10;

	/**
	 * @var        FfbPlayerteam
	 */
	protected $aFfbPlayerteamRelatedByUserteamPlayerId11;

	/**
	 * @var        FfbMatchround
	 */
	protected $aFfbMatchround;

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
		$this->userteam_price = '0';
		$this->userteam_score = -1;
		$this->userteam_wc_points = 0;
	}

	/**
	 * Initializes internal state of BaseFfbUserteam object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [userteam_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamId()
	{
		return $this->userteam_id;
	}

	/**
	 * Get the [userteam_user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamUserId()
	{
		return $this->userteam_user_id;
	}

	/**
	 * Get the [optionally formatted] temporal [userteam_date] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserteamDate($format = 'Y-m-d H:i:s')
	{
		if ($this->userteam_date === null) {
			return null;
		}


		if ($this->userteam_date === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->userteam_date);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->userteam_date, true), $x);
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
	 * Get the [userteam_player_id1] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId1()
	{
		return $this->userteam_player_id1;
	}

	/**
	 * Get the [userteam_player_id2] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId2()
	{
		return $this->userteam_player_id2;
	}

	/**
	 * Get the [userteam_player_id3] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId3()
	{
		return $this->userteam_player_id3;
	}

	/**
	 * Get the [userteam_player_id4] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId4()
	{
		return $this->userteam_player_id4;
	}

	/**
	 * Get the [userteam_player_id5] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId5()
	{
		return $this->userteam_player_id5;
	}

	/**
	 * Get the [userteam_player_id6] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId6()
	{
		return $this->userteam_player_id6;
	}

	/**
	 * Get the [userteam_player_id7] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId7()
	{
		return $this->userteam_player_id7;
	}

	/**
	 * Get the [userteam_player_id8] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId8()
	{
		return $this->userteam_player_id8;
	}

	/**
	 * Get the [userteam_player_id9] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId9()
	{
		return $this->userteam_player_id9;
	}

	/**
	 * Get the [userteam_player_id10] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId10()
	{
		return $this->userteam_player_id10;
	}

	/**
	 * Get the [userteam_player_id11] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamPlayerId11()
	{
		return $this->userteam_player_id11;
	}

	/**
	 * Get the [userteam_price] column value.
	 * 
	 * @return     string
	 */
	public function getUserteamPrice()
	{
		return $this->userteam_price;
	}

	/**
	 * Get the [userteam_matchround_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamMatchroundId()
	{
		return $this->userteam_matchround_id;
	}

	/**
	 * Get the [userteam_score] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamScore()
	{
		return $this->userteam_score;
	}

	/**
	 * Get the [userteam_wc_points] column value.
	 * 
	 * @return     int
	 */
	public function getUserteamWcPoints()
	{
		return $this->userteam_wc_points;
	}

	/**
	 * Set the value of [userteam_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_id !== $v) {
			$this->userteam_id = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_ID;
		}

		return $this;
	} // setUserteamId()

	/**
	 * Set the value of [userteam_user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_user_id !== $v) {
			$this->userteam_user_id = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_USER_ID;
		}

		if ($this->aWebUser !== null && $this->aWebUser->getUserId() !== $v) {
			$this->aWebUser = null;
		}

		return $this;
	} // setUserteamUserId()

	/**
	 * Sets the value of [userteam_date] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamDate($v)
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

		if ( $this->userteam_date !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->userteam_date !== null && $tmpDt = new DateTime($this->userteam_date)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->userteam_date = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_DATE;
			}
		} // if either are not null

		return $this;
	} // setUserteamDate()

	/**
	 * Set the value of [userteam_player_id1] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId1($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id1 !== $v) {
			$this->userteam_player_id1 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID1;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId1->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = null;
		}

		return $this;
	} // setUserteamPlayerId1()

	/**
	 * Set the value of [userteam_player_id2] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId2($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id2 !== $v) {
			$this->userteam_player_id2 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID2;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId2->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = null;
		}

		return $this;
	} // setUserteamPlayerId2()

	/**
	 * Set the value of [userteam_player_id3] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId3($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id3 !== $v) {
			$this->userteam_player_id3 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID3;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId3->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = null;
		}

		return $this;
	} // setUserteamPlayerId3()

	/**
	 * Set the value of [userteam_player_id4] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId4($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id4 !== $v) {
			$this->userteam_player_id4 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID4;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId4->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = null;
		}

		return $this;
	} // setUserteamPlayerId4()

	/**
	 * Set the value of [userteam_player_id5] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId5($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id5 !== $v) {
			$this->userteam_player_id5 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID5;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId5->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = null;
		}

		return $this;
	} // setUserteamPlayerId5()

	/**
	 * Set the value of [userteam_player_id6] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId6($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id6 !== $v) {
			$this->userteam_player_id6 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID6;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId6->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = null;
		}

		return $this;
	} // setUserteamPlayerId6()

	/**
	 * Set the value of [userteam_player_id7] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId7($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id7 !== $v) {
			$this->userteam_player_id7 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID7;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId7->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = null;
		}

		return $this;
	} // setUserteamPlayerId7()

	/**
	 * Set the value of [userteam_player_id8] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId8($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id8 !== $v) {
			$this->userteam_player_id8 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID8;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId8->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = null;
		}

		return $this;
	} // setUserteamPlayerId8()

	/**
	 * Set the value of [userteam_player_id9] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId9($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id9 !== $v) {
			$this->userteam_player_id9 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID9;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId9->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = null;
		}

		return $this;
	} // setUserteamPlayerId9()

	/**
	 * Set the value of [userteam_player_id10] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId10($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id10 !== $v) {
			$this->userteam_player_id10 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID10;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId10->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = null;
		}

		return $this;
	} // setUserteamPlayerId10()

	/**
	 * Set the value of [userteam_player_id11] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPlayerId11($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_player_id11 !== $v) {
			$this->userteam_player_id11 = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PLAYER_ID11;
		}

		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11 !== null && $this->aFfbPlayerteamRelatedByUserteamPlayerId11->getPlayerteamId() !== $v) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = null;
		}

		return $this;
	} // setUserteamPlayerId11()

	/**
	 * Set the value of [userteam_price] column.
	 * 
	 * @param      string $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamPrice($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->userteam_price !== $v || $this->isNew()) {
			$this->userteam_price = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_PRICE;
		}

		return $this;
	} // setUserteamPrice()

	/**
	 * Set the value of [userteam_matchround_id] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamMatchroundId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_matchround_id !== $v) {
			$this->userteam_matchround_id = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_MATCHROUND_ID;
		}

		if ($this->aFfbMatchround !== null && $this->aFfbMatchround->getMatchroundId() !== $v) {
			$this->aFfbMatchround = null;
		}

		return $this;
	} // setUserteamMatchroundId()

	/**
	 * Set the value of [userteam_score] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamScore($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_score !== $v || $this->isNew()) {
			$this->userteam_score = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_SCORE;
		}

		return $this;
	} // setUserteamScore()

	/**
	 * Set the value of [userteam_wc_points] column.
	 * 
	 * @param      int $v new value
	 * @return     FfbUserteam The current object (for fluent API support)
	 */
	public function setUserteamWcPoints($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->userteam_wc_points !== $v || $this->isNew()) {
			$this->userteam_wc_points = $v;
			$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_WC_POINTS;
		}

		return $this;
	} // setUserteamWcPoints()

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
			if ($this->userteam_price !== '0') {
				return false;
			}

			if ($this->userteam_score !== -1) {
				return false;
			}

			if ($this->userteam_wc_points !== 0) {
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

			$this->userteam_id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->userteam_user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->userteam_date = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->userteam_player_id1 = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->userteam_player_id2 = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->userteam_player_id3 = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->userteam_player_id4 = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->userteam_player_id5 = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->userteam_player_id6 = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
			$this->userteam_player_id7 = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
			$this->userteam_player_id8 = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->userteam_player_id9 = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
			$this->userteam_player_id10 = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
			$this->userteam_player_id11 = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
			$this->userteam_price = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->userteam_matchround_id = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
			$this->userteam_score = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
			$this->userteam_wc_points = ($row[$startcol + 17] !== null) ? (int) $row[$startcol + 17] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 18; // 18 = FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating FfbUserteam object", $e);
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

		if ($this->aWebUser !== null && $this->userteam_user_id !== $this->aWebUser->getUserId()) {
			$this->aWebUser = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1 !== null && $this->userteam_player_id1 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId1->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2 !== null && $this->userteam_player_id2 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId2->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3 !== null && $this->userteam_player_id3 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId3->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4 !== null && $this->userteam_player_id4 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId4->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5 !== null && $this->userteam_player_id5 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId5->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6 !== null && $this->userteam_player_id6 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId6->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7 !== null && $this->userteam_player_id7 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId7->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8 !== null && $this->userteam_player_id8 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId8->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9 !== null && $this->userteam_player_id9 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId9->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10 !== null && $this->userteam_player_id10 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId10->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = null;
		}
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11 !== null && $this->userteam_player_id11 !== $this->aFfbPlayerteamRelatedByUserteamPlayerId11->getPlayerteamId()) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = null;
		}
		if ($this->aFfbMatchround !== null && $this->userteam_matchround_id !== $this->aFfbMatchround->getMatchroundId()) {
			$this->aFfbMatchround = null;
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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = FfbUserteamPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aWebUser = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = null;
			$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = null;
			$this->aFfbMatchround = null;
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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				FfbUserteamQuery::create()
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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				FfbUserteamPeer::addInstanceToPool($this);
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

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId1->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId1->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId1($this->aFfbPlayerteamRelatedByUserteamPlayerId1);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId2->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId2->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId2($this->aFfbPlayerteamRelatedByUserteamPlayerId2);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId3->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId3->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId3($this->aFfbPlayerteamRelatedByUserteamPlayerId3);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId4->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId4->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId4($this->aFfbPlayerteamRelatedByUserteamPlayerId4);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId5->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId5->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId5($this->aFfbPlayerteamRelatedByUserteamPlayerId5);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId6->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId6->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId6($this->aFfbPlayerteamRelatedByUserteamPlayerId6);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId7->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId7->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId7($this->aFfbPlayerteamRelatedByUserteamPlayerId7);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId8->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId8->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId8($this->aFfbPlayerteamRelatedByUserteamPlayerId8);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId9->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId9->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId9($this->aFfbPlayerteamRelatedByUserteamPlayerId9);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId10->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId10->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId10($this->aFfbPlayerteamRelatedByUserteamPlayerId10);
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11 !== null) {
				if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11->isModified() || $this->aFfbPlayerteamRelatedByUserteamPlayerId11->isNew()) {
					$affectedRows += $this->aFfbPlayerteamRelatedByUserteamPlayerId11->save($con);
				}
				$this->setFfbPlayerteamRelatedByUserteamPlayerId11($this->aFfbPlayerteamRelatedByUserteamPlayerId11);
			}

			if ($this->aFfbMatchround !== null) {
				if ($this->aFfbMatchround->isModified() || $this->aFfbMatchround->isNew()) {
					$affectedRows += $this->aFfbMatchround->save($con);
				}
				$this->setFfbMatchround($this->aFfbMatchround);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = FfbUserteamPeer::USERTEAM_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(FfbUserteamPeer::USERTEAM_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbUserteamPeer::USERTEAM_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows += 1;
					$this->setUserteamId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows += FfbUserteamPeer::doUpdate($this, $con);
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

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId1->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId1->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId2->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId2->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId3->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId3->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId4->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId4->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId5->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId5->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId6->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId6->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId7->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId7->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId8->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId8->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId9->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId9->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId10->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId10->getValidationFailures());
				}
			}

			if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11 !== null) {
				if (!$this->aFfbPlayerteamRelatedByUserteamPlayerId11->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbPlayerteamRelatedByUserteamPlayerId11->getValidationFailures());
				}
			}

			if ($this->aFfbMatchround !== null) {
				if (!$this->aFfbMatchround->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aFfbMatchround->getValidationFailures());
				}
			}


			if (($retval = FfbUserteamPeer::doValidate($this, $columns)) !== true) {
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
		$pos = FfbUserteamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUserteamId();
				break;
			case 1:
				return $this->getUserteamUserId();
				break;
			case 2:
				return $this->getUserteamDate();
				break;
			case 3:
				return $this->getUserteamPlayerId1();
				break;
			case 4:
				return $this->getUserteamPlayerId2();
				break;
			case 5:
				return $this->getUserteamPlayerId3();
				break;
			case 6:
				return $this->getUserteamPlayerId4();
				break;
			case 7:
				return $this->getUserteamPlayerId5();
				break;
			case 8:
				return $this->getUserteamPlayerId6();
				break;
			case 9:
				return $this->getUserteamPlayerId7();
				break;
			case 10:
				return $this->getUserteamPlayerId8();
				break;
			case 11:
				return $this->getUserteamPlayerId9();
				break;
			case 12:
				return $this->getUserteamPlayerId10();
				break;
			case 13:
				return $this->getUserteamPlayerId11();
				break;
			case 14:
				return $this->getUserteamPrice();
				break;
			case 15:
				return $this->getUserteamMatchroundId();
				break;
			case 16:
				return $this->getUserteamScore();
				break;
			case 17:
				return $this->getUserteamWcPoints();
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
		$keys = FfbUserteamPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getUserteamId(),
			$keys[1] => $this->getUserteamUserId(),
			$keys[2] => $this->getUserteamDate(),
			$keys[3] => $this->getUserteamPlayerId1(),
			$keys[4] => $this->getUserteamPlayerId2(),
			$keys[5] => $this->getUserteamPlayerId3(),
			$keys[6] => $this->getUserteamPlayerId4(),
			$keys[7] => $this->getUserteamPlayerId5(),
			$keys[8] => $this->getUserteamPlayerId6(),
			$keys[9] => $this->getUserteamPlayerId7(),
			$keys[10] => $this->getUserteamPlayerId8(),
			$keys[11] => $this->getUserteamPlayerId9(),
			$keys[12] => $this->getUserteamPlayerId10(),
			$keys[13] => $this->getUserteamPlayerId11(),
			$keys[14] => $this->getUserteamPrice(),
			$keys[15] => $this->getUserteamMatchroundId(),
			$keys[16] => $this->getUserteamScore(),
			$keys[17] => $this->getUserteamWcPoints(),
		);
		if ($includeForeignObjects) {
			if (null !== $this->aWebUser) {
				$result['WebUser'] = $this->aWebUser->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId1) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId1'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId1->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId2) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId2'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId2->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId3) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId3'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId3->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId4) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId4'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId4->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId5) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId5'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId5->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId6) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId6'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId6->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId7) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId7'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId7->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId8) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId8'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId8->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId9) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId9'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId9->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId10) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId10'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId10->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbPlayerteamRelatedByUserteamPlayerId11) {
				$result['FfbPlayerteamRelatedByUserteamPlayerId11'] = $this->aFfbPlayerteamRelatedByUserteamPlayerId11->toArray($keyType, $includeLazyLoadColumns, true);
			}
			if (null !== $this->aFfbMatchround) {
				$result['FfbMatchround'] = $this->aFfbMatchround->toArray($keyType, $includeLazyLoadColumns, true);
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
		$pos = FfbUserteamPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUserteamId($value);
				break;
			case 1:
				$this->setUserteamUserId($value);
				break;
			case 2:
				$this->setUserteamDate($value);
				break;
			case 3:
				$this->setUserteamPlayerId1($value);
				break;
			case 4:
				$this->setUserteamPlayerId2($value);
				break;
			case 5:
				$this->setUserteamPlayerId3($value);
				break;
			case 6:
				$this->setUserteamPlayerId4($value);
				break;
			case 7:
				$this->setUserteamPlayerId5($value);
				break;
			case 8:
				$this->setUserteamPlayerId6($value);
				break;
			case 9:
				$this->setUserteamPlayerId7($value);
				break;
			case 10:
				$this->setUserteamPlayerId8($value);
				break;
			case 11:
				$this->setUserteamPlayerId9($value);
				break;
			case 12:
				$this->setUserteamPlayerId10($value);
				break;
			case 13:
				$this->setUserteamPlayerId11($value);
				break;
			case 14:
				$this->setUserteamPrice($value);
				break;
			case 15:
				$this->setUserteamMatchroundId($value);
				break;
			case 16:
				$this->setUserteamScore($value);
				break;
			case 17:
				$this->setUserteamWcPoints($value);
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
		$keys = FfbUserteamPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setUserteamId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserteamUserId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUserteamDate($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUserteamPlayerId1($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setUserteamPlayerId2($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUserteamPlayerId3($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUserteamPlayerId4($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUserteamPlayerId5($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUserteamPlayerId6($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUserteamPlayerId7($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setUserteamPlayerId8($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUserteamPlayerId9($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setUserteamPlayerId10($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUserteamPlayerId11($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUserteamPrice($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setUserteamMatchroundId($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setUserteamScore($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setUserteamWcPoints($arr[$keys[17]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(FfbUserteamPeer::DATABASE_NAME);

		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_ID)) $criteria->add(FfbUserteamPeer::USERTEAM_ID, $this->userteam_id);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_USER_ID)) $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->userteam_user_id);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_DATE)) $criteria->add(FfbUserteamPeer::USERTEAM_DATE, $this->userteam_date);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID1)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $this->userteam_player_id1);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID2)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $this->userteam_player_id2);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID3)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $this->userteam_player_id3);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID4)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $this->userteam_player_id4);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID5)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $this->userteam_player_id5);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID6)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $this->userteam_player_id6);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID7)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $this->userteam_player_id7);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID8)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $this->userteam_player_id8);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID9)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $this->userteam_player_id9);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID10)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $this->userteam_player_id10);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PLAYER_ID11)) $criteria->add(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $this->userteam_player_id11);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_PRICE)) $criteria->add(FfbUserteamPeer::USERTEAM_PRICE, $this->userteam_price);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_MATCHROUND_ID)) $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $this->userteam_matchround_id);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_SCORE)) $criteria->add(FfbUserteamPeer::USERTEAM_SCORE, $this->userteam_score);
		if ($this->isColumnModified(FfbUserteamPeer::USERTEAM_WC_POINTS)) $criteria->add(FfbUserteamPeer::USERTEAM_WC_POINTS, $this->userteam_wc_points);

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
		$criteria = new Criteria(FfbUserteamPeer::DATABASE_NAME);
		$criteria->add(FfbUserteamPeer::USERTEAM_ID, $this->userteam_id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getUserteamId();
	}

	/**
	 * Generic method to set the primary key (userteam_id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setUserteamId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getUserteamId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of FfbUserteam (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setUserteamUserId($this->userteam_user_id);
		$copyObj->setUserteamDate($this->userteam_date);
		$copyObj->setUserteamPlayerId1($this->userteam_player_id1);
		$copyObj->setUserteamPlayerId2($this->userteam_player_id2);
		$copyObj->setUserteamPlayerId3($this->userteam_player_id3);
		$copyObj->setUserteamPlayerId4($this->userteam_player_id4);
		$copyObj->setUserteamPlayerId5($this->userteam_player_id5);
		$copyObj->setUserteamPlayerId6($this->userteam_player_id6);
		$copyObj->setUserteamPlayerId7($this->userteam_player_id7);
		$copyObj->setUserteamPlayerId8($this->userteam_player_id8);
		$copyObj->setUserteamPlayerId9($this->userteam_player_id9);
		$copyObj->setUserteamPlayerId10($this->userteam_player_id10);
		$copyObj->setUserteamPlayerId11($this->userteam_player_id11);
		$copyObj->setUserteamPrice($this->userteam_price);
		$copyObj->setUserteamMatchroundId($this->userteam_matchround_id);
		$copyObj->setUserteamScore($this->userteam_score);
		$copyObj->setUserteamWcPoints($this->userteam_wc_points);

		$copyObj->setNew(true);
		$copyObj->setUserteamId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     FfbUserteam Clone of current object.
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
	 * @return     FfbUserteamPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new FfbUserteamPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a WebUser object.
	 *
	 * @param      WebUser $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUser(WebUser $v = null)
	{
		if ($v === null) {
			$this->setUserteamUserId(NULL);
		} else {
			$this->setUserteamUserId($v->getUserId());
		}

		$this->aWebUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the WebUser object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteam($this);
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
		if ($this->aWebUser === null && ($this->userteam_user_id !== null)) {
			$this->aWebUser = WebUserQuery::create()->findPk($this->userteam_user_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aWebUser->addFfbUserteams($this);
			 */
		}
		return $this->aWebUser;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId1(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId1(NULL);
		} else {
			$this->setUserteamPlayerId1($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId1($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId1(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId1 === null && ($this->userteam_player_id1 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id1, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId1->addFfbUserteamsRelatedByUserteamPlayerId1($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId1;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId2(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId2(NULL);
		} else {
			$this->setUserteamPlayerId2($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId2($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId2(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId2 === null && ($this->userteam_player_id2 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id2, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId2->addFfbUserteamsRelatedByUserteamPlayerId2($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId2;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId3(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId3(NULL);
		} else {
			$this->setUserteamPlayerId3($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId3($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId3(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId3 === null && ($this->userteam_player_id3 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id3, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId3->addFfbUserteamsRelatedByUserteamPlayerId3($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId3;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId4(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId4(NULL);
		} else {
			$this->setUserteamPlayerId4($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId4($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId4(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId4 === null && ($this->userteam_player_id4 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id4, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId4->addFfbUserteamsRelatedByUserteamPlayerId4($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId4;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId5(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId5(NULL);
		} else {
			$this->setUserteamPlayerId5($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId5($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId5(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId5 === null && ($this->userteam_player_id5 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id5, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId5->addFfbUserteamsRelatedByUserteamPlayerId5($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId5;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId6(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId6(NULL);
		} else {
			$this->setUserteamPlayerId6($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId6($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId6(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId6 === null && ($this->userteam_player_id6 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id6, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId6->addFfbUserteamsRelatedByUserteamPlayerId6($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId6;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId7(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId7(NULL);
		} else {
			$this->setUserteamPlayerId7($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId7($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId7(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId7 === null && ($this->userteam_player_id7 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id7, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId7->addFfbUserteamsRelatedByUserteamPlayerId7($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId7;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId8(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId8(NULL);
		} else {
			$this->setUserteamPlayerId8($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId8($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId8(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId8 === null && ($this->userteam_player_id8 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id8, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId8->addFfbUserteamsRelatedByUserteamPlayerId8($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId8;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId9(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId9(NULL);
		} else {
			$this->setUserteamPlayerId9($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId9($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId9(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId9 === null && ($this->userteam_player_id9 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id9, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId9->addFfbUserteamsRelatedByUserteamPlayerId9($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId9;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId10(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId10(NULL);
		} else {
			$this->setUserteamPlayerId10($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId10($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId10(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId10 === null && ($this->userteam_player_id10 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id10, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId10->addFfbUserteamsRelatedByUserteamPlayerId10($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId10;
	}

	/**
	 * Declares an association between this object and a FfbPlayerteam object.
	 *
	 * @param      FfbPlayerteam $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbPlayerteamRelatedByUserteamPlayerId11(FfbPlayerteam $v = null)
	{
		if ($v === null) {
			$this->setUserteamPlayerId11(NULL);
		} else {
			$this->setUserteamPlayerId11($v->getPlayerteamId());
		}

		$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbPlayerteam object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteamRelatedByUserteamPlayerId11($this);
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
	public function getFfbPlayerteamRelatedByUserteamPlayerId11(PropelPDO $con = null)
	{
		if ($this->aFfbPlayerteamRelatedByUserteamPlayerId11 === null && ($this->userteam_player_id11 !== null)) {
			$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = FfbPlayerteamQuery::create()->findPk($this->userteam_player_id11, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbPlayerteamRelatedByUserteamPlayerId11->addFfbUserteamsRelatedByUserteamPlayerId11($this);
			 */
		}
		return $this->aFfbPlayerteamRelatedByUserteamPlayerId11;
	}

	/**
	 * Declares an association between this object and a FfbMatchround object.
	 *
	 * @param      FfbMatchround $v
	 * @return     FfbUserteam The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setFfbMatchround(FfbMatchround $v = null)
	{
		if ($v === null) {
			$this->setUserteamMatchroundId(NULL);
		} else {
			$this->setUserteamMatchroundId($v->getMatchroundId());
		}

		$this->aFfbMatchround = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the FfbMatchround object, it will not be re-added.
		if ($v !== null) {
			$v->addFfbUserteam($this);
		}

		return $this;
	}


	/**
	 * Get the associated FfbMatchround object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     FfbMatchround The associated FfbMatchround object.
	 * @throws     PropelException
	 */
	public function getFfbMatchround(PropelPDO $con = null)
	{
		if ($this->aFfbMatchround === null && ($this->userteam_matchround_id !== null)) {
			$this->aFfbMatchround = FfbMatchroundQuery::create()->findPk($this->userteam_matchround_id, $con);
			/* The following can be used additionally to
				 guarantee the related object contains a reference
				 to this object.  This level of coupling may, however, be
				 undesirable since it could result in an only partially populated collection
				 in the referenced object.
				 $this->aFfbMatchround->addFfbUserteams($this);
			 */
		}
		return $this->aFfbMatchround;
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->userteam_id = null;
		$this->userteam_user_id = null;
		$this->userteam_date = null;
		$this->userteam_player_id1 = null;
		$this->userteam_player_id2 = null;
		$this->userteam_player_id3 = null;
		$this->userteam_player_id4 = null;
		$this->userteam_player_id5 = null;
		$this->userteam_player_id6 = null;
		$this->userteam_player_id7 = null;
		$this->userteam_player_id8 = null;
		$this->userteam_player_id9 = null;
		$this->userteam_player_id10 = null;
		$this->userteam_player_id11 = null;
		$this->userteam_price = null;
		$this->userteam_matchround_id = null;
		$this->userteam_score = null;
		$this->userteam_wc_points = null;
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
		} // if ($deep)

		$this->aWebUser = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId1 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId2 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId3 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId4 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId5 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId6 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId7 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId8 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId9 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId10 = null;
		$this->aFfbPlayerteamRelatedByUserteamPlayerId11 = null;
		$this->aFfbMatchround = null;
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

} // BaseFfbUserteam
