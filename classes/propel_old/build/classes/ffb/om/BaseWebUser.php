<?php


/**
 * Base class that represents a row from the 'web_user' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebUser extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
	const PEER = 'WebUserPeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        WebUserPeer
	 */
	protected static $peer;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the user_nickname field.
	 * @var        string
	 */
	protected $user_nickname;

	/**
	 * The value for the user_password field.
	 * @var        string
	 */
	protected $user_password;

	/**
	 * The value for the user_email field.
	 * @var        string
	 */
	protected $user_email;

	/**
	 * The value for the user_fname field.
	 * @var        string
	 */
	protected $user_fname;

	/**
	 * The value for the user_lname field.
	 * @var        string
	 */
	protected $user_lname;

	/**
	 * The value for the user_gender field.
	 * Note: this column has a database default value of: ''
	 * @var        string
	 */
	protected $user_gender;

	/**
	 * The value for the user_status field.
	 * Note: this column has a database default value of: 'active'
	 * @var        string
	 */
	protected $user_status;

	/**
	 * The value for the user_admin field.
	 * Note: this column has a database default value of: false
	 * @var        boolean
	 */
	protected $user_admin;

	/**
	 * The value for the user_facebook_id field.
	 * @var        string
	 */
	protected $user_facebook_id;

	/**
	 * The value for the user_nationality field.
	 * @var        string
	 */
	protected $user_nationality;

	/**
	 * The value for the user_date_birth field.
	 * @var        string
	 */
	protected $user_date_birth;

	/**
	 * The value for the user_ip field.
	 * @var        string
	 */
	protected $user_ip;

	/**
	 * The value for the user_lip field.
	 * @var        string
	 */
	protected $user_lip;

	/**
	 * The value for the user_date_register field.
	 * @var        string
	 */
	protected $user_date_register;

	/**
	 * The value for the user_date_llogin field.
	 * @var        string
	 */
	protected $user_date_llogin;

	/**
	 * The value for the user_date_laction field.
	 * @var        string
	 */
	protected $user_date_laction;

	/**
	 * The value for the user_activation_code field.
	 * @var        string
	 */
	protected $user_activation_code;

	/**
	 * The value for the user_mailservice field.
	 * @var        string
	 */
	protected $user_mailservice;

	/**
	 * @var        WebUserDetails one-to-one related WebUserDetails object
	 */
	protected $singleWebUserDetails;

	/**
	 * @var        WebUserPermissions one-to-one related WebUserPermissions object
	 */
	protected $singleWebUserPermissions;

	/**
	 * @var        array FfbComments[] Collection to store aggregation of FfbComments objects.
	 */
	protected $collFfbCommentss;

	/**
	 * @var        array FfbPollResult[] Collection to store aggregation of FfbPollResult objects.
	 */
	protected $collFfbPollResults;

	/**
	 * @var        array FfbInvitation[] Collection to store aggregation of FfbInvitation objects.
	 */
	protected $collFfbInvitations;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteams;

	/**
	 * @var        array FfbUserscore[] Collection to store aggregation of FfbUserscore objects.
	 */
	protected $collFfbUserscores;

	/**
	 * @var        array FfbAdmin[] Collection to store aggregation of FfbAdmin objects.
	 */
	protected $collFfbAdmins;

	/**
	 * @var        array WebLog[] Collection to store aggregation of WebLog objects.
	 */
	protected $collWebLogs;

	/**
	 * @var        array FfbUserAwardFinished[] Collection to store aggregation of FfbUserAwardFinished objects.
	 */
	protected $collFfbUserAwardFinisheds;

	/**
	 * @var        array WebAdmin[] Collection to store aggregation of WebAdmin objects.
	 */
	protected $collWebAdmins;

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
		$this->user_gender = '';
		$this->user_status = 'active';
		$this->user_admin = false;
	}

	/**
	 * Initializes internal state of BaseWebUser object.
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
	 * Get the [user_nickname] column value.
	 * 
	 * @return     string
	 */
	public function getUserNickname()
	{
		return $this->user_nickname;
	}

	/**
	 * Get the [user_password] column value.
	 * 
	 * @return     string
	 */
	public function getUserPassword()
	{
		return $this->user_password;
	}

	/**
	 * Get the [user_email] column value.
	 * 
	 * @return     string
	 */
	public function getUserEmail()
	{
		return $this->user_email;
	}

	/**
	 * Get the [user_fname] column value.
	 * 
	 * @return     string
	 */
	public function getUserFname()
	{
		return $this->user_fname;
	}

	/**
	 * Get the [user_lname] column value.
	 * 
	 * @return     string
	 */
	public function getUserLname()
	{
		return $this->user_lname;
	}

	/**
	 * Get the [user_gender] column value.
	 * 
	 * @return     string
	 */
	public function getUserGender()
	{
		return $this->user_gender;
	}

	/**
	 * Get the [user_status] column value.
	 * 
	 * @return     string
	 */
	public function getUserStatus()
	{
		return $this->user_status;
	}

	/**
	 * Get the [user_admin] column value.
	 * 
	 * @return     boolean
	 */
	public function getUserAdmin()
	{
		return $this->user_admin;
	}

	/**
	 * Get the [user_facebook_id] column value.
	 * 
	 * @return     string
	 */
	public function getUserFacebookId()
	{
		return $this->user_facebook_id;
	}

	/**
	 * Get the [user_nationality] column value.
	 * 
	 * @return     string
	 */
	public function getUserNationality()
	{
		return $this->user_nationality;
	}

	/**
	 * Get the [optionally formatted] temporal [user_date_birth] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserDateBirth($format = 'Y-m-d H:i:s')
	{
		if ($this->user_date_birth === null) {
			return null;
		}


		if ($this->user_date_birth === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->user_date_birth);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->user_date_birth, true), $x);
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
	 * Get the [user_ip] column value.
	 * 
	 * @return     string
	 */
	public function getUserIp()
	{
		return $this->user_ip;
	}

	/**
	 * Get the [user_lip] column value.
	 * 
	 * @return     string
	 */
	public function getUserLip()
	{
		return $this->user_lip;
	}

	/**
	 * Get the [optionally formatted] temporal [user_date_register] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserDateRegister($format = 'Y-m-d H:i:s')
	{
		if ($this->user_date_register === null) {
			return null;
		}


		if ($this->user_date_register === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->user_date_register);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->user_date_register, true), $x);
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
	 * Get the [optionally formatted] temporal [user_date_llogin] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserDateLlogin($format = 'Y-m-d H:i:s')
	{
		if ($this->user_date_llogin === null) {
			return null;
		}


		if ($this->user_date_llogin === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->user_date_llogin);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->user_date_llogin, true), $x);
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
	 * Get the [optionally formatted] temporal [user_date_laction] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUserDateLaction($format = 'Y-m-d H:i:s')
	{
		if ($this->user_date_laction === null) {
			return null;
		}


		if ($this->user_date_laction === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->user_date_laction);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->user_date_laction, true), $x);
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
	 * Get the [user_activation_code] column value.
	 * 
	 * @return     string
	 */
	public function getUserActivationCode()
	{
		return $this->user_activation_code;
	}

	/**
	 * Get the [user_mailservice] column value.
	 * 
	 * @return     string
	 */
	public function getUserMailservice()
	{
		return $this->user_mailservice;
	}

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_ID;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [user_nickname] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserNickname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_nickname !== $v) {
			$this->user_nickname = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_NICKNAME;
		}

		return $this;
	} // setUserNickname()

	/**
	 * Set the value of [user_password] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserPassword($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_password !== $v) {
			$this->user_password = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_PASSWORD;
		}

		return $this;
	} // setUserPassword()

	/**
	 * Set the value of [user_email] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserEmail($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_email !== $v) {
			$this->user_email = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_EMAIL;
		}

		return $this;
	} // setUserEmail()

	/**
	 * Set the value of [user_fname] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserFname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_fname !== $v) {
			$this->user_fname = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_FNAME;
		}

		return $this;
	} // setUserFname()

	/**
	 * Set the value of [user_lname] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserLname($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_lname !== $v) {
			$this->user_lname = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_LNAME;
		}

		return $this;
	} // setUserLname()

	/**
	 * Set the value of [user_gender] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserGender($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_gender !== $v || $this->isNew()) {
			$this->user_gender = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_GENDER;
		}

		return $this;
	} // setUserGender()

	/**
	 * Set the value of [user_status] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserStatus($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_status !== $v || $this->isNew()) {
			$this->user_status = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_STATUS;
		}

		return $this;
	} // setUserStatus()

	/**
	 * Set the value of [user_admin] column.
	 * 
	 * @param      boolean $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserAdmin($v)
	{
		if ($v !== null) {
			$v = (boolean) $v;
		}

		if ($this->user_admin !== $v || $this->isNew()) {
			$this->user_admin = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_ADMIN;
		}

		return $this;
	} // setUserAdmin()

	/**
	 * Set the value of [user_facebook_id] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserFacebookId($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_facebook_id !== $v) {
			$this->user_facebook_id = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_FACEBOOK_ID;
		}

		return $this;
	} // setUserFacebookId()

	/**
	 * Set the value of [user_nationality] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserNationality($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_nationality !== $v) {
			$this->user_nationality = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_NATIONALITY;
		}

		return $this;
	} // setUserNationality()

	/**
	 * Sets the value of [user_date_birth] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserDateBirth($v)
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

		if ( $this->user_date_birth !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->user_date_birth !== null && $tmpDt = new DateTime($this->user_date_birth)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->user_date_birth = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebUserPeer::USER_DATE_BIRTH;
			}
		} // if either are not null

		return $this;
	} // setUserDateBirth()

	/**
	 * Set the value of [user_ip] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserIp($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_ip !== $v) {
			$this->user_ip = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_IP;
		}

		return $this;
	} // setUserIp()

	/**
	 * Set the value of [user_lip] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserLip($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_lip !== $v) {
			$this->user_lip = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_LIP;
		}

		return $this;
	} // setUserLip()

	/**
	 * Sets the value of [user_date_register] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserDateRegister($v)
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

		if ( $this->user_date_register !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->user_date_register !== null && $tmpDt = new DateTime($this->user_date_register)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->user_date_register = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebUserPeer::USER_DATE_REGISTER;
			}
		} // if either are not null

		return $this;
	} // setUserDateRegister()

	/**
	 * Sets the value of [user_date_llogin] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserDateLlogin($v)
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

		if ( $this->user_date_llogin !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->user_date_llogin !== null && $tmpDt = new DateTime($this->user_date_llogin)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->user_date_llogin = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebUserPeer::USER_DATE_LLOGIN;
			}
		} // if either are not null

		return $this;
	} // setUserDateLlogin()

	/**
	 * Sets the value of [user_date_laction] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserDateLaction($v)
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

		if ( $this->user_date_laction !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->user_date_laction !== null && $tmpDt = new DateTime($this->user_date_laction)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->user_date_laction = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = WebUserPeer::USER_DATE_LACTION;
			}
		} // if either are not null

		return $this;
	} // setUserDateLaction()

	/**
	 * Set the value of [user_activation_code] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserActivationCode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_activation_code !== $v) {
			$this->user_activation_code = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_ACTIVATION_CODE;
		}

		return $this;
	} // setUserActivationCode()

	/**
	 * Set the value of [user_mailservice] column.
	 * 
	 * @param      string $v new value
	 * @return     WebUser The current object (for fluent API support)
	 */
	public function setUserMailservice($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->user_mailservice !== $v) {
			$this->user_mailservice = $v;
			$this->modifiedColumns[] = WebUserPeer::USER_MAILSERVICE;
		}

		return $this;
	} // setUserMailservice()

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
			if ($this->user_gender !== '') {
				return false;
			}

			if ($this->user_status !== 'active') {
				return false;
			}

			if ($this->user_admin !== false) {
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
			$this->user_nickname = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->user_password = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->user_email = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->user_fname = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->user_lname = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->user_gender = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->user_status = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->user_admin = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
			$this->user_facebook_id = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->user_nationality = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->user_date_birth = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->user_ip = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->user_lip = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->user_date_register = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
			$this->user_date_llogin = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
			$this->user_date_laction = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
			$this->user_activation_code = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
			$this->user_mailservice = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 19; // 19 = WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating WebUser object", $e);
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
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = WebUserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->singleWebUserDetails = null;

			$this->singleWebUserPermissions = null;

			$this->collFfbCommentss = null;

			$this->collFfbPollResults = null;

			$this->collFfbInvitations = null;

			$this->collFfbUserteams = null;

			$this->collFfbUserscores = null;

			$this->collFfbAdmins = null;

			$this->collWebLogs = null;

			$this->collFfbUserAwardFinisheds = null;

			$this->collWebAdmins = null;

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
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				WebUserQuery::create()
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
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
				WebUserPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = WebUserPeer::USER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(WebUserPeer::USER_ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.WebUserPeer::USER_ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setUserId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = WebUserPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->singleWebUserDetails !== null) {
				if (!$this->singleWebUserDetails->isDeleted()) {
						$affectedRows += $this->singleWebUserDetails->save($con);
				}
			}

			if ($this->singleWebUserPermissions !== null) {
				if (!$this->singleWebUserPermissions->isDeleted()) {
						$affectedRows += $this->singleWebUserPermissions->save($con);
				}
			}

			if ($this->collFfbCommentss !== null) {
				foreach ($this->collFfbCommentss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbPollResults !== null) {
				foreach ($this->collFfbPollResults as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbInvitations !== null) {
				foreach ($this->collFfbInvitations as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserteams !== null) {
				foreach ($this->collFfbUserteams as $referrerFK) {
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

			if ($this->collWebLogs !== null) {
				foreach ($this->collWebLogs as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collFfbUserAwardFinisheds !== null) {
				foreach ($this->collFfbUserAwardFinisheds as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collWebAdmins !== null) {
				foreach ($this->collWebAdmins as $referrerFK) {
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


			if (($retval = WebUserPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->singleWebUserDetails !== null) {
					if (!$this->singleWebUserDetails->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleWebUserDetails->getValidationFailures());
					}
				}

				if ($this->singleWebUserPermissions !== null) {
					if (!$this->singleWebUserPermissions->validate($columns)) {
						$failureMap = array_merge($failureMap, $this->singleWebUserPermissions->getValidationFailures());
					}
				}

				if ($this->collFfbCommentss !== null) {
					foreach ($this->collFfbCommentss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbPollResults !== null) {
					foreach ($this->collFfbPollResults as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbInvitations !== null) {
					foreach ($this->collFfbInvitations as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserteams !== null) {
					foreach ($this->collFfbUserteams as $referrerFK) {
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

				if ($this->collWebLogs !== null) {
					foreach ($this->collWebLogs as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collFfbUserAwardFinisheds !== null) {
					foreach ($this->collFfbUserAwardFinisheds as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collWebAdmins !== null) {
					foreach ($this->collWebAdmins as $referrerFK) {
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
		$pos = WebUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getUserNickname();
				break;
			case 2:
				return $this->getUserPassword();
				break;
			case 3:
				return $this->getUserEmail();
				break;
			case 4:
				return $this->getUserFname();
				break;
			case 5:
				return $this->getUserLname();
				break;
			case 6:
				return $this->getUserGender();
				break;
			case 7:
				return $this->getUserStatus();
				break;
			case 8:
				return $this->getUserAdmin();
				break;
			case 9:
				return $this->getUserFacebookId();
				break;
			case 10:
				return $this->getUserNationality();
				break;
			case 11:
				return $this->getUserDateBirth();
				break;
			case 12:
				return $this->getUserIp();
				break;
			case 13:
				return $this->getUserLip();
				break;
			case 14:
				return $this->getUserDateRegister();
				break;
			case 15:
				return $this->getUserDateLlogin();
				break;
			case 16:
				return $this->getUserDateLaction();
				break;
			case 17:
				return $this->getUserActivationCode();
				break;
			case 18:
				return $this->getUserMailservice();
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
		$keys = WebUserPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getUserId(),
			$keys[1] => $this->getUserNickname(),
			$keys[2] => $this->getUserPassword(),
			$keys[3] => $this->getUserEmail(),
			$keys[4] => $this->getUserFname(),
			$keys[5] => $this->getUserLname(),
			$keys[6] => $this->getUserGender(),
			$keys[7] => $this->getUserStatus(),
			$keys[8] => $this->getUserAdmin(),
			$keys[9] => $this->getUserFacebookId(),
			$keys[10] => $this->getUserNationality(),
			$keys[11] => $this->getUserDateBirth(),
			$keys[12] => $this->getUserIp(),
			$keys[13] => $this->getUserLip(),
			$keys[14] => $this->getUserDateRegister(),
			$keys[15] => $this->getUserDateLlogin(),
			$keys[16] => $this->getUserDateLaction(),
			$keys[17] => $this->getUserActivationCode(),
			$keys[18] => $this->getUserMailservice(),
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
		$pos = WebUserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setUserNickname($value);
				break;
			case 2:
				$this->setUserPassword($value);
				break;
			case 3:
				$this->setUserEmail($value);
				break;
			case 4:
				$this->setUserFname($value);
				break;
			case 5:
				$this->setUserLname($value);
				break;
			case 6:
				$this->setUserGender($value);
				break;
			case 7:
				$this->setUserStatus($value);
				break;
			case 8:
				$this->setUserAdmin($value);
				break;
			case 9:
				$this->setUserFacebookId($value);
				break;
			case 10:
				$this->setUserNationality($value);
				break;
			case 11:
				$this->setUserDateBirth($value);
				break;
			case 12:
				$this->setUserIp($value);
				break;
			case 13:
				$this->setUserLip($value);
				break;
			case 14:
				$this->setUserDateRegister($value);
				break;
			case 15:
				$this->setUserDateLlogin($value);
				break;
			case 16:
				$this->setUserDateLaction($value);
				break;
			case 17:
				$this->setUserActivationCode($value);
				break;
			case 18:
				$this->setUserMailservice($value);
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
		$keys = WebUserPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setUserId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserNickname($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setUserPassword($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setUserEmail($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setUserFname($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setUserLname($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setUserGender($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setUserStatus($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setUserAdmin($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUserFacebookId($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setUserNationality($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setUserDateBirth($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setUserIp($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUserLip($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setUserDateRegister($arr[$keys[14]]);
		if (array_key_exists($keys[15], $arr)) $this->setUserDateLlogin($arr[$keys[15]]);
		if (array_key_exists($keys[16], $arr)) $this->setUserDateLaction($arr[$keys[16]]);
		if (array_key_exists($keys[17], $arr)) $this->setUserActivationCode($arr[$keys[17]]);
		if (array_key_exists($keys[18], $arr)) $this->setUserMailservice($arr[$keys[18]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(WebUserPeer::DATABASE_NAME);

		if ($this->isColumnModified(WebUserPeer::USER_ID)) $criteria->add(WebUserPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(WebUserPeer::USER_NICKNAME)) $criteria->add(WebUserPeer::USER_NICKNAME, $this->user_nickname);
		if ($this->isColumnModified(WebUserPeer::USER_PASSWORD)) $criteria->add(WebUserPeer::USER_PASSWORD, $this->user_password);
		if ($this->isColumnModified(WebUserPeer::USER_EMAIL)) $criteria->add(WebUserPeer::USER_EMAIL, $this->user_email);
		if ($this->isColumnModified(WebUserPeer::USER_FNAME)) $criteria->add(WebUserPeer::USER_FNAME, $this->user_fname);
		if ($this->isColumnModified(WebUserPeer::USER_LNAME)) $criteria->add(WebUserPeer::USER_LNAME, $this->user_lname);
		if ($this->isColumnModified(WebUserPeer::USER_GENDER)) $criteria->add(WebUserPeer::USER_GENDER, $this->user_gender);
		if ($this->isColumnModified(WebUserPeer::USER_STATUS)) $criteria->add(WebUserPeer::USER_STATUS, $this->user_status);
		if ($this->isColumnModified(WebUserPeer::USER_ADMIN)) $criteria->add(WebUserPeer::USER_ADMIN, $this->user_admin);
		if ($this->isColumnModified(WebUserPeer::USER_FACEBOOK_ID)) $criteria->add(WebUserPeer::USER_FACEBOOK_ID, $this->user_facebook_id);
		if ($this->isColumnModified(WebUserPeer::USER_NATIONALITY)) $criteria->add(WebUserPeer::USER_NATIONALITY, $this->user_nationality);
		if ($this->isColumnModified(WebUserPeer::USER_DATE_BIRTH)) $criteria->add(WebUserPeer::USER_DATE_BIRTH, $this->user_date_birth);
		if ($this->isColumnModified(WebUserPeer::USER_IP)) $criteria->add(WebUserPeer::USER_IP, $this->user_ip);
		if ($this->isColumnModified(WebUserPeer::USER_LIP)) $criteria->add(WebUserPeer::USER_LIP, $this->user_lip);
		if ($this->isColumnModified(WebUserPeer::USER_DATE_REGISTER)) $criteria->add(WebUserPeer::USER_DATE_REGISTER, $this->user_date_register);
		if ($this->isColumnModified(WebUserPeer::USER_DATE_LLOGIN)) $criteria->add(WebUserPeer::USER_DATE_LLOGIN, $this->user_date_llogin);
		if ($this->isColumnModified(WebUserPeer::USER_DATE_LACTION)) $criteria->add(WebUserPeer::USER_DATE_LACTION, $this->user_date_laction);
		if ($this->isColumnModified(WebUserPeer::USER_ACTIVATION_CODE)) $criteria->add(WebUserPeer::USER_ACTIVATION_CODE, $this->user_activation_code);
		if ($this->isColumnModified(WebUserPeer::USER_MAILSERVICE)) $criteria->add(WebUserPeer::USER_MAILSERVICE, $this->user_mailservice);

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
		$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		$criteria->add(WebUserPeer::USER_ID, $this->user_id);

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
	 * @param      object $copyObj An object of WebUser (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setUserNickname($this->user_nickname);
		$copyObj->setUserPassword($this->user_password);
		$copyObj->setUserEmail($this->user_email);
		$copyObj->setUserFname($this->user_fname);
		$copyObj->setUserLname($this->user_lname);
		$copyObj->setUserGender($this->user_gender);
		$copyObj->setUserStatus($this->user_status);
		$copyObj->setUserAdmin($this->user_admin);
		$copyObj->setUserFacebookId($this->user_facebook_id);
		$copyObj->setUserNationality($this->user_nationality);
		$copyObj->setUserDateBirth($this->user_date_birth);
		$copyObj->setUserIp($this->user_ip);
		$copyObj->setUserLip($this->user_lip);
		$copyObj->setUserDateRegister($this->user_date_register);
		$copyObj->setUserDateLlogin($this->user_date_llogin);
		$copyObj->setUserDateLaction($this->user_date_laction);
		$copyObj->setUserActivationCode($this->user_activation_code);
		$copyObj->setUserMailservice($this->user_mailservice);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			$relObj = $this->getWebUserDetails();
			if ($relObj) {
				$copyObj->setWebUserDetails($relObj->copy($deepCopy));
			}

			$relObj = $this->getWebUserPermissions();
			if ($relObj) {
				$copyObj->setWebUserPermissions($relObj->copy($deepCopy));
			}

			foreach ($this->getFfbCommentss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbComments($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbPollResults() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbPollResult($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbInvitations() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbInvitation($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserteams() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserteam($relObj->copy($deepCopy));
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

			foreach ($this->getWebLogs() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebLog($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getFfbUserAwardFinisheds() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addFfbUserAwardFinished($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getWebAdmins() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addWebAdmin($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setUserId(NULL); // this is a auto-increment column, so set to default value
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
	 * @return     WebUser Clone of current object.
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
	 * @return     WebUserPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new WebUserPeer();
		}
		return self::$peer;
	}

	/**
	 * Gets a single WebUserDetails object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     WebUserDetails
	 * @throws     PropelException
	 */
	public function getWebUserDetails(PropelPDO $con = null)
	{

		if ($this->singleWebUserDetails === null && !$this->isNew()) {
			$this->singleWebUserDetails = WebUserDetailsQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleWebUserDetails;
	}

	/**
	 * Sets a single WebUserDetails object as related to this object by a one-to-one relationship.
	 *
	 * @param      WebUserDetails $v WebUserDetails
	 * @return     WebUser The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUserDetails(WebUserDetails $v = null)
	{
		$this->singleWebUserDetails = $v;

		// Make sure that that the passed-in WebUserDetails isn't already associated with this object
		if ($v !== null && $v->getWebUser() === null) {
			$v->setWebUser($this);
		}

		return $this;
	}

	/**
	 * Gets a single WebUserPermissions object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con optional connection object
	 * @return     WebUserPermissions
	 * @throws     PropelException
	 */
	public function getWebUserPermissions(PropelPDO $con = null)
	{

		if ($this->singleWebUserPermissions === null && !$this->isNew()) {
			$this->singleWebUserPermissions = WebUserPermissionsQuery::create()->findPk($this->getPrimaryKey(), $con);
		}

		return $this->singleWebUserPermissions;
	}

	/**
	 * Sets a single WebUserPermissions object as related to this object by a one-to-one relationship.
	 *
	 * @param      WebUserPermissions $v WebUserPermissions
	 * @return     WebUser The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUserPermissions(WebUserPermissions $v = null)
	{
		$this->singleWebUserPermissions = $v;

		// Make sure that that the passed-in WebUserPermissions isn't already associated with this object
		if ($v !== null && $v->getWebUser() === null) {
			$v->setWebUser($this);
		}

		return $this;
	}

	/**
	 * Clears out the collFfbCommentss collection
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
	 * Initializes the collFfbCommentss collection.
	 *
	 * By default this just sets the collFfbCommentss collection to an empty array (like clearcollFfbCommentss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbCommentss()
	{
		$this->collFfbCommentss = new PropelObjectCollection();
		$this->collFfbCommentss->setModel('FfbComments');
	}

	/**
	 * Gets an array of FfbComments objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 * @throws     PropelException
	 */
	public function getFfbCommentss($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbCommentss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbCommentss) {
				// return empty collection
				$this->initFfbCommentss();
			} else {
				$collFfbCommentss = FfbCommentsQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbCommentss;
				}
				$this->collFfbCommentss = $collFfbCommentss;
			}
		}
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
		if(null === $this->collFfbCommentss || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbCommentss) {
				return 0;
			} else {
				$query = FfbCommentsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbCommentss);
		}
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
		if (!$this->collFfbCommentss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbCommentss[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 */
	public function getFfbCommentssJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbCommentsQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getFfbCommentss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbCommentss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbComments[] List of FfbComments objects
	 */
	public function getFfbCommentssJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbCommentsQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbCommentss($query, $con);
	}

	/**
	 * Clears out the collFfbPollResults collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbPollResults()
	 */
	public function clearFfbPollResults()
	{
		$this->collFfbPollResults = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbPollResults collection.
	 *
	 * By default this just sets the collFfbPollResults collection to an empty array (like clearcollFfbPollResults());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPollResults()
	{
		$this->collFfbPollResults = new PropelObjectCollection();
		$this->collFfbPollResults->setModel('FfbPollResult');
	}

	/**
	 * Gets an array of FfbPollResult objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbPollResult[] List of FfbPollResult objects
	 * @throws     PropelException
	 */
	public function getFfbPollResults($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbPollResults || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPollResults) {
				// return empty collection
				$this->initFfbPollResults();
			} else {
				$collFfbPollResults = FfbPollResultQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbPollResults;
				}
				$this->collFfbPollResults = $collFfbPollResults;
			}
		}
		return $this->collFfbPollResults;
	}

	/**
	 * Returns the number of related FfbPollResult objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbPollResult objects.
	 * @throws     PropelException
	 */
	public function countFfbPollResults(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbPollResults || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbPollResults) {
				return 0;
			} else {
				$query = FfbPollResultQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbPollResults);
		}
	}

	/**
	 * Method called to associate a FfbPollResult object to this object
	 * through the FfbPollResult foreign key attribute.
	 *
	 * @param      FfbPollResult $l FfbPollResult
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbPollResult(FfbPollResult $l)
	{
		if ($this->collFfbPollResults === null) {
			$this->initFfbPollResults();
		}
		if (!$this->collFfbPollResults->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbPollResults[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPollResult[] List of FfbPollResult objects
	 */
	public function getFfbPollResultsJoinFfbPoll($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPollResultQuery::create(null, $criteria);
		$query->joinWith('FfbPoll', $join_behavior);

		return $this->getFfbPollResults($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbPollResults from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbPollResult[] List of FfbPollResult objects
	 */
	public function getFfbPollResultsJoinFfbPollAnswer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbPollResultQuery::create(null, $criteria);
		$query->joinWith('FfbPollAnswer', $join_behavior);

		return $this->getFfbPollResults($query, $con);
	}

	/**
	 * Clears out the collFfbInvitations collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbInvitations()
	 */
	public function clearFfbInvitations()
	{
		$this->collFfbInvitations = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbInvitations collection.
	 *
	 * By default this just sets the collFfbInvitations collection to an empty array (like clearcollFfbInvitations());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbInvitations()
	{
		$this->collFfbInvitations = new PropelObjectCollection();
		$this->collFfbInvitations->setModel('FfbInvitation');
	}

	/**
	 * Gets an array of FfbInvitation objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbInvitation[] List of FfbInvitation objects
	 * @throws     PropelException
	 */
	public function getFfbInvitations($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbInvitations || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbInvitations) {
				// return empty collection
				$this->initFfbInvitations();
			} else {
				$collFfbInvitations = FfbInvitationQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbInvitations;
				}
				$this->collFfbInvitations = $collFfbInvitations;
			}
		}
		return $this->collFfbInvitations;
	}

	/**
	 * Returns the number of related FfbInvitation objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related FfbInvitation objects.
	 * @throws     PropelException
	 */
	public function countFfbInvitations(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbInvitations || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbInvitations) {
				return 0;
			} else {
				$query = FfbInvitationQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbInvitations);
		}
	}

	/**
	 * Method called to associate a FfbInvitation object to this object
	 * through the FfbInvitation foreign key attribute.
	 *
	 * @param      FfbInvitation $l FfbInvitation
	 * @return     void
	 * @throws     PropelException
	 */
	public function addFfbInvitation(FfbInvitation $l)
	{
		if ($this->collFfbInvitations === null) {
			$this->initFfbInvitations();
		}
		if (!$this->collFfbInvitations->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbInvitations[]= $l;
			$l->setWebUser($this);
		}
	}

	/**
	 * Clears out the collFfbUserteams collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addFfbUserteams()
	 */
	public function clearFfbUserteams()
	{
		$this->collFfbUserteams = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collFfbUserteams collection.
	 *
	 * By default this just sets the collFfbUserteams collection to an empty array (like clearcollFfbUserteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteams()
	{
		$this->collFfbUserteams = new PropelObjectCollection();
		$this->collFfbUserteams->setModel('FfbUserteam');
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 * @throws     PropelException
	 */
	public function getFfbUserteams($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteams) {
				// return empty collection
				$this->initFfbUserteams();
			} else {
				$collFfbUserteams = FfbUserteamQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserteams;
				}
				$this->collFfbUserteams = $collFfbUserteams;
			}
		}
		return $this->collFfbUserteams;
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
	public function countFfbUserteams(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collFfbUserteams || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserteams) {
				return 0;
			} else {
				$query = FfbUserteamQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserteams);
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
	public function addFfbUserteam(FfbUserteam $l)
	{
		if ($this->collFfbUserteams === null) {
			$this->initFfbUserteams();
		}
		if (!$this->collFfbUserteams->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserteams[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId1($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId1', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId2($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId2', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId3($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId3', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId4($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId4', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId5($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId5', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId6($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId6', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId7($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId7', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId8($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId8', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId9($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId9', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId10($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId10', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId11($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbPlayerteamRelatedByUserteamPlayerId11', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserteams from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserteam[] List of FfbUserteam objects
	 */
	public function getFfbUserteamsJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserteamQuery::create(null, $criteria);
		$query->joinWith('FfbMatchround', $join_behavior);

		return $this->getFfbUserteams($query, $con);
	}

	/**
	 * Clears out the collFfbUserscores collection
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
	 * Initializes the collFfbUserscores collection.
	 *
	 * By default this just sets the collFfbUserscores collection to an empty array (like clearcollFfbUserscores());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserscores()
	{
		$this->collFfbUserscores = new PropelObjectCollection();
		$this->collFfbUserscores->setModel('FfbUserscore');
	}

	/**
	 * Gets an array of FfbUserscore objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserscore[] List of FfbUserscore objects
	 * @throws     PropelException
	 */
	public function getFfbUserscores($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbUserscores || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserscores) {
				// return empty collection
				$this->initFfbUserscores();
			} else {
				$collFfbUserscores = FfbUserscoreQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserscores;
				}
				$this->collFfbUserscores = $collFfbUserscores;
			}
		}
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
		if(null === $this->collFfbUserscores || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserscores) {
				return 0;
			} else {
				$query = FfbUserscoreQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserscores);
		}
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
		if (!$this->collFfbUserscores->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserscores[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserscores from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserscore[] List of FfbUserscore objects
	 */
	public function getFfbUserscoresJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserscoreQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getFfbUserscores($query, $con);
	}

	/**
	 * Clears out the collFfbAdmins collection
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
	 * Initializes the collFfbAdmins collection.
	 *
	 * By default this just sets the collFfbAdmins collection to an empty array (like clearcollFfbAdmins());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbAdmins()
	{
		$this->collFfbAdmins = new PropelObjectCollection();
		$this->collFfbAdmins->setModel('FfbAdmin');
	}

	/**
	 * Gets an array of FfbAdmin objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbAdmin[] List of FfbAdmin objects
	 * @throws     PropelException
	 */
	public function getFfbAdmins($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbAdmins || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbAdmins) {
				// return empty collection
				$this->initFfbAdmins();
			} else {
				$collFfbAdmins = FfbAdminQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbAdmins;
				}
				$this->collFfbAdmins = $collFfbAdmins;
			}
		}
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
		if(null === $this->collFfbAdmins || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbAdmins) {
				return 0;
			} else {
				$query = FfbAdminQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbAdmins);
		}
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
		if (!$this->collFfbAdmins->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbAdmins[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbAdmins from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbAdmin[] List of FfbAdmin objects
	 */
	public function getFfbAdminsJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbAdminQuery::create(null, $criteria);
		$query->joinWith('FfbGame', $join_behavior);

		return $this->getFfbAdmins($query, $con);
	}

	/**
	 * Clears out the collWebLogs collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWebLogs()
	 */
	public function clearWebLogs()
	{
		$this->collWebLogs = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWebLogs collection.
	 *
	 * By default this just sets the collWebLogs collection to an empty array (like clearcollWebLogs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebLogs()
	{
		$this->collWebLogs = new PropelObjectCollection();
		$this->collWebLogs->setModel('WebLog');
	}

	/**
	 * Gets an array of WebLog objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array WebLog[] List of WebLog objects
	 * @throws     PropelException
	 */
	public function getWebLogs($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collWebLogs || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebLogs) {
				// return empty collection
				$this->initWebLogs();
			} else {
				$collWebLogs = WebLogQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collWebLogs;
				}
				$this->collWebLogs = $collWebLogs;
			}
		}
		return $this->collWebLogs;
	}

	/**
	 * Returns the number of related WebLog objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WebLog objects.
	 * @throws     PropelException
	 */
	public function countWebLogs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collWebLogs || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebLogs) {
				return 0;
			} else {
				$query = WebLogQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collWebLogs);
		}
	}

	/**
	 * Method called to associate a WebLog object to this object
	 * through the WebLog foreign key attribute.
	 *
	 * @param      WebLog $l WebLog
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWebLog(WebLog $l)
	{
		if ($this->collWebLogs === null) {
			$this->initWebLogs();
		}
		if (!$this->collWebLogs->contains($l)) { // only add it if the **same** object is not already associated
			$this->collWebLogs[]= $l;
			$l->setWebUser($this);
		}
	}

	/**
	 * Clears out the collFfbUserAwardFinisheds collection
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
	 * Initializes the collFfbUserAwardFinisheds collection.
	 *
	 * By default this just sets the collFfbUserAwardFinisheds collection to an empty array (like clearcollFfbUserAwardFinisheds());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserAwardFinisheds()
	{
		$this->collFfbUserAwardFinisheds = new PropelObjectCollection();
		$this->collFfbUserAwardFinisheds->setModel('FfbUserAwardFinished');
	}

	/**
	 * Gets an array of FfbUserAwardFinished objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array FfbUserAwardFinished[] List of FfbUserAwardFinished objects
	 * @throws     PropelException
	 */
	public function getFfbUserAwardFinisheds($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collFfbUserAwardFinisheds || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserAwardFinisheds) {
				// return empty collection
				$this->initFfbUserAwardFinisheds();
			} else {
				$collFfbUserAwardFinisheds = FfbUserAwardFinishedQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collFfbUserAwardFinisheds;
				}
				$this->collFfbUserAwardFinisheds = $collFfbUserAwardFinisheds;
			}
		}
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
		if(null === $this->collFfbUserAwardFinisheds || null !== $criteria) {
			if ($this->isNew() && null === $this->collFfbUserAwardFinisheds) {
				return 0;
			} else {
				$query = FfbUserAwardFinishedQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collFfbUserAwardFinisheds);
		}
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
		if (!$this->collFfbUserAwardFinisheds->contains($l)) { // only add it if the **same** object is not already associated
			$this->collFfbUserAwardFinisheds[]= $l;
			$l->setWebUser($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this WebUser is new, it will return
	 * an empty collection; or if this WebUser has previously
	 * been saved, it will retrieve related FfbUserAwardFinisheds from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in WebUser.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @param      string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
	 * @return     PropelCollection|array FfbUserAwardFinished[] List of FfbUserAwardFinished objects
	 */
	public function getFfbUserAwardFinishedsJoinFfbUserAwardDefines($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = FfbUserAwardFinishedQuery::create(null, $criteria);
		$query->joinWith('FfbUserAwardDefines', $join_behavior);

		return $this->getFfbUserAwardFinisheds($query, $con);
	}

	/**
	 * Clears out the collWebAdmins collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addWebAdmins()
	 */
	public function clearWebAdmins()
	{
		$this->collWebAdmins = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collWebAdmins collection.
	 *
	 * By default this just sets the collWebAdmins collection to an empty array (like clearcollWebAdmins());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebAdmins()
	{
		$this->collWebAdmins = new PropelObjectCollection();
		$this->collWebAdmins->setModel('WebAdmin');
	}

	/**
	 * Gets an array of WebAdmin objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this WebUser is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria optional Criteria object to narrow the query
	 * @param      PropelPDO $con optional connection object
	 * @return     PropelCollection|array WebAdmin[] List of WebAdmin objects
	 * @throws     PropelException
	 */
	public function getWebAdmins($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collWebAdmins || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebAdmins) {
				// return empty collection
				$this->initWebAdmins();
			} else {
				$collWebAdmins = WebAdminQuery::create(null, $criteria)
					->filterByWebUser($this)
					->find($con);
				if (null !== $criteria) {
					return $collWebAdmins;
				}
				$this->collWebAdmins = $collWebAdmins;
			}
		}
		return $this->collWebAdmins;
	}

	/**
	 * Returns the number of related WebAdmin objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related WebAdmin objects.
	 * @throws     PropelException
	 */
	public function countWebAdmins(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collWebAdmins || null !== $criteria) {
			if ($this->isNew() && null === $this->collWebAdmins) {
				return 0;
			} else {
				$query = WebAdminQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByWebUser($this)
					->count($con);
			}
		} else {
			return count($this->collWebAdmins);
		}
	}

	/**
	 * Method called to associate a WebAdmin object to this object
	 * through the WebAdmin foreign key attribute.
	 *
	 * @param      WebAdmin $l WebAdmin
	 * @return     void
	 * @throws     PropelException
	 */
	public function addWebAdmin(WebAdmin $l)
	{
		if ($this->collWebAdmins === null) {
			$this->initWebAdmins();
		}
		if (!$this->collWebAdmins->contains($l)) { // only add it if the **same** object is not already associated
			$this->collWebAdmins[]= $l;
			$l->setWebUser($this);
		}
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->user_id = null;
		$this->user_nickname = null;
		$this->user_password = null;
		$this->user_email = null;
		$this->user_fname = null;
		$this->user_lname = null;
		$this->user_gender = null;
		$this->user_status = null;
		$this->user_admin = null;
		$this->user_facebook_id = null;
		$this->user_nationality = null;
		$this->user_date_birth = null;
		$this->user_ip = null;
		$this->user_lip = null;
		$this->user_date_register = null;
		$this->user_date_llogin = null;
		$this->user_date_laction = null;
		$this->user_activation_code = null;
		$this->user_mailservice = null;
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
			if ($this->singleWebUserDetails) {
				$this->singleWebUserDetails->clearAllReferences($deep);
			}
			if ($this->singleWebUserPermissions) {
				$this->singleWebUserPermissions->clearAllReferences($deep);
			}
			if ($this->collFfbCommentss) {
				foreach ((array) $this->collFfbCommentss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbPollResults) {
				foreach ((array) $this->collFfbPollResults as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbInvitations) {
				foreach ((array) $this->collFfbInvitations as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserteams) {
				foreach ((array) $this->collFfbUserteams as $o) {
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
			if ($this->collWebLogs) {
				foreach ((array) $this->collWebLogs as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collFfbUserAwardFinisheds) {
				foreach ((array) $this->collFfbUserAwardFinisheds as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collWebAdmins) {
				foreach ((array) $this->collWebAdmins as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->singleWebUserDetails = null;
		$this->singleWebUserPermissions = null;
		$this->collFfbCommentss = null;
		$this->collFfbPollResults = null;
		$this->collFfbInvitations = null;
		$this->collFfbUserteams = null;
		$this->collFfbUserscores = null;
		$this->collFfbAdmins = null;
		$this->collWebLogs = null;
		$this->collFfbUserAwardFinisheds = null;
		$this->collWebAdmins = null;
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

} // BaseWebUser
