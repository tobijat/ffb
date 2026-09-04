<?php

/**
 * Base class that represents a row from the 'web_user' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseWebUser extends BaseObject  implements Persistent {


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
	 * @var        Criteria The criteria used to select the current contents of collFfbCommentss.
	 */
	private $lastFfbCommentsCriteria = null;

	/**
	 * @var        array FfbPollResult[] Collection to store aggregation of FfbPollResult objects.
	 */
	protected $collFfbPollResults;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbPollResults.
	 */
	private $lastFfbPollResultCriteria = null;

	/**
	 * @var        array FfbInvitation[] Collection to store aggregation of FfbInvitation objects.
	 */
	protected $collFfbInvitations;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbInvitations.
	 */
	private $lastFfbInvitationCriteria = null;

	/**
	 * @var        array FfbUserteam[] Collection to store aggregation of FfbUserteam objects.
	 */
	protected $collFfbUserteams;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbUserteams.
	 */
	private $lastFfbUserteamCriteria = null;

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
	 * @var        array WebLog[] Collection to store aggregation of WebLog objects.
	 */
	protected $collWebLogs;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWebLogs.
	 */
	private $lastWebLogCriteria = null;

	/**
	 * @var        array FfbUserAwardFinished[] Collection to store aggregation of FfbUserAwardFinished objects.
	 */
	protected $collFfbUserAwardFinisheds;

	/**
	 * @var        Criteria The criteria used to select the current contents of collFfbUserAwardFinisheds.
	 */
	private $lastFfbUserAwardFinishedCriteria = null;

	/**
	 * @var        array WebAdmin[] Collection to store aggregation of WebAdmin objects.
	 */
	protected $collWebAdmins;

	/**
	 * @var        Criteria The criteria used to select the current contents of collWebAdmins.
	 */
	private $lastWebAdminCriteria = null;

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

			// FIXME - using NUM_COLUMNS may be clearer.
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
			$this->lastFfbCommentsCriteria = null;

			$this->collFfbPollResults = null;
			$this->lastFfbPollResultCriteria = null;

			$this->collFfbInvitations = null;
			$this->lastFfbInvitationCriteria = null;

			$this->collFfbUserteams = null;
			$this->lastFfbUserteamCriteria = null;

			$this->collFfbUserscores = null;
			$this->lastFfbUserscoreCriteria = null;

			$this->collFfbAdmins = null;
			$this->lastFfbAdminCriteria = null;

			$this->collWebLogs = null;
			$this->lastWebLogCriteria = null;

			$this->collFfbUserAwardFinisheds = null;
			$this->lastFfbUserAwardFinishedCriteria = null;

			$this->collWebAdmins = null;
			$this->lastWebAdminCriteria = null;

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
				WebUserPeer::doDelete($this, $con);
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
				$con->commit();
				WebUserPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = WebUserPeer::USER_ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = WebUserPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setUserId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += WebUserPeer::doUpdate($this, $con);
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
	 * @param      PropelPDO $con
	 * @return     WebUserDetails
	 * @throws     PropelException
	 */
	public function getWebUserDetails(PropelPDO $con = null)
	{

		if ($this->singleWebUserDetails === null && !$this->isNew()) {
			$this->singleWebUserDetails = WebUserDetailsPeer::retrieveByPK($this->user_id, $con);
		}

		return $this->singleWebUserDetails;
	}

	/**
	 * Sets a single WebUserDetails object as related to this object by a one-to-one relationship.
	 *
	 * @param      WebUserDetails $l WebUserDetails
	 * @return     WebUser The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUserDetails(WebUserDetails $v)
	{
		$this->singleWebUserDetails = $v;

		// Make sure that that the passed-in WebUserDetails isn't already associated with this object
		if ($v->getWebUser() === null) {
			$v->setWebUser($this);
		}

		return $this;
	}

	/**
	 * Gets a single WebUserPermissions object, which is related to this object by a one-to-one relationship.
	 *
	 * @param      PropelPDO $con
	 * @return     WebUserPermissions
	 * @throws     PropelException
	 */
	public function getWebUserPermissions(PropelPDO $con = null)
	{

		if ($this->singleWebUserPermissions === null && !$this->isNew()) {
			$this->singleWebUserPermissions = WebUserPermissionsPeer::retrieveByPK($this->user_id, $con);
		}

		return $this->singleWebUserPermissions;
	}

	/**
	 * Sets a single WebUserPermissions object as related to this object by a one-to-one relationship.
	 *
	 * @param      WebUserPermissions $l WebUserPermissions
	 * @return     WebUser The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setWebUserPermissions(WebUserPermissions $v)
	{
		$this->singleWebUserPermissions = $v;

		// Make sure that that the passed-in WebUserPermissions isn't already associated with this object
		if ($v->getWebUser() === null) {
			$v->setWebUser($this);
		}

		return $this;
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
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbCommentss from storage. If this WebUser is new, it will return
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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
			   $this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

				FfbCommentsPeer::addSelectColumns($criteria);
				$this->collFfbCommentss = FfbCommentsPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
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

				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

				$count = FfbCommentsPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

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
	 */
	public function getFfbCommentssJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
				$this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

			if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbCommentsCriteria = $criteria;

		return $this->collFfbCommentss;
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
	 */
	public function getFfbCommentssJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbCommentss === null) {
			if ($this->isNew()) {
				$this->collFfbCommentss = array();
			} else {

				$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $this->user_id);

			if (!isset($this->lastFfbCommentsCriteria) || !$this->lastFfbCommentsCriteria->equals($criteria)) {
				$this->collFfbCommentss = FfbCommentsPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbCommentsCriteria = $criteria;

		return $this->collFfbCommentss;
	}

	/**
	 * Clears out the collFfbPollResults collection (array).
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
	 * Initializes the collFfbPollResults collection (array).
	 *
	 * By default this just sets the collFfbPollResults collection to an empty array (like clearcollFfbPollResults());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbPollResults()
	{
		$this->collFfbPollResults = array();
	}

	/**
	 * Gets an array of FfbPollResult objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbPollResults from storage. If this WebUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbPollResult[]
	 * @throws     PropelException
	 */
	public function getFfbPollResults($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
			   $this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				FfbPollResultPeer::addSelectColumns($criteria);
				$this->collFfbPollResults = FfbPollResultPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				FfbPollResultPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
					$this->collFfbPollResults = FfbPollResultPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;
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
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				$count = FfbPollResultPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
					$count = FfbPollResultPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbPollResults);
				}
			} else {
				$count = count($this->collFfbPollResults);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collFfbPollResults, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbPollResults, $l);
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
	 */
	public function getFfbPollResultsJoinFfbPoll($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPoll($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPoll($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
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
	 */
	public function getFfbPollResultsJoinFfbPollAnswer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbPollResults === null) {
			if ($this->isNew()) {
				$this->collFfbPollResults = array();
			} else {

				$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPollAnswer($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbPollResultPeer::POLL_RESULT_USER_ID, $this->user_id);

			if (!isset($this->lastFfbPollResultCriteria) || !$this->lastFfbPollResultCriteria->equals($criteria)) {
				$this->collFfbPollResults = FfbPollResultPeer::doSelectJoinFfbPollAnswer($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbPollResultCriteria = $criteria;

		return $this->collFfbPollResults;
	}

	/**
	 * Clears out the collFfbInvitations collection (array).
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
	 * Initializes the collFfbInvitations collection (array).
	 *
	 * By default this just sets the collFfbInvitations collection to an empty array (like clearcollFfbInvitations());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbInvitations()
	{
		$this->collFfbInvitations = array();
	}

	/**
	 * Gets an array of FfbInvitation objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbInvitations from storage. If this WebUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbInvitation[]
	 * @throws     PropelException
	 */
	public function getFfbInvitations($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbInvitations === null) {
			if ($this->isNew()) {
			   $this->collFfbInvitations = array();
			} else {

				$criteria->add(FfbInvitationPeer::INVITATION_SENDER_ID, $this->user_id);

				FfbInvitationPeer::addSelectColumns($criteria);
				$this->collFfbInvitations = FfbInvitationPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbInvitationPeer::INVITATION_SENDER_ID, $this->user_id);

				FfbInvitationPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbInvitationCriteria) || !$this->lastFfbInvitationCriteria->equals($criteria)) {
					$this->collFfbInvitations = FfbInvitationPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbInvitationCriteria = $criteria;
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
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbInvitations === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbInvitationPeer::INVITATION_SENDER_ID, $this->user_id);

				$count = FfbInvitationPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbInvitationPeer::INVITATION_SENDER_ID, $this->user_id);

				if (!isset($this->lastFfbInvitationCriteria) || !$this->lastFfbInvitationCriteria->equals($criteria)) {
					$count = FfbInvitationPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbInvitations);
				}
			} else {
				$count = count($this->collFfbInvitations);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collFfbInvitations, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbInvitations, $l);
			$l->setWebUser($this);
		}
	}

	/**
	 * Clears out the collFfbUserteams collection (array).
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
	 * Initializes the collFfbUserteams collection (array).
	 *
	 * By default this just sets the collFfbUserteams collection to an empty array (like clearcollFfbUserteams());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initFfbUserteams()
	{
		$this->collFfbUserteams = array();
	}

	/**
	 * Gets an array of FfbUserteam objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbUserteams from storage. If this WebUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array FfbUserteam[]
	 * @throws     PropelException
	 */
	public function getFfbUserteams($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
			   $this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				FfbUserteamPeer::addSelectColumns($criteria);
				$this->collFfbUserteams = FfbUserteamPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				FfbUserteamPeer::addSelectColumns($criteria);
				if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
					$this->collFfbUserteams = FfbUserteamPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;
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
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$count = FfbUserteamPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
					$count = FfbUserteamPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collFfbUserteams);
				}
			} else {
				$count = count($this->collFfbUserteams);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collFfbUserteams, true)) { // only add it if the **same** object is not already associated
			array_push($this->collFfbUserteams, $l);
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId1($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId1($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId1($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId2($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId2($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId2($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId3($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId3($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId3($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId4($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId4($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId4($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId5($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId5($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId5($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId6($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId6($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId6($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId7($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId7($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId7($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId8($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId8($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId8($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId9($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId9($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId9($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId10($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId10($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId10($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbPlayerteamRelatedByUserteamPlayerId11($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId11($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId11($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 */
	public function getFfbUserteamsJoinFfbMatchround($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserteams === null) {
			if ($this->isNew()) {
				$this->collFfbUserteams = array();
			} else {

				$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserteamCriteria) || !$this->lastFfbUserteamCriteria->equals($criteria)) {
				$this->collFfbUserteams = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserteamCriteria = $criteria;

		return $this->collFfbUserteams;
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
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbUserscores from storage. If this WebUser is new, it will return
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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserscores === null) {
			if ($this->isNew()) {
			   $this->collFfbUserscores = array();
			} else {

				$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

				FfbUserscorePeer::addSelectColumns($criteria);
				$this->collFfbUserscores = FfbUserscorePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
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

				$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

				$count = FfbUserscorePeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

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
	 */
	public function getFfbUserscoresJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserscores === null) {
			if ($this->isNew()) {
				$this->collFfbUserscores = array();
			} else {

				$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

				$this->collFfbUserscores = FfbUserscorePeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserscoreCriteria) || !$this->lastFfbUserscoreCriteria->equals($criteria)) {
				$this->collFfbUserscores = FfbUserscorePeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
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
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbAdmins from storage. If this WebUser is new, it will return
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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdmins === null) {
			if ($this->isNew()) {
			   $this->collFfbAdmins = array();
			} else {

				$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

				FfbAdminPeer::addSelectColumns($criteria);
				$this->collFfbAdmins = FfbAdminPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
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

				$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

				$count = FfbAdminPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

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
	 */
	public function getFfbAdminsJoinFfbGame($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbAdmins === null) {
			if ($this->isNew()) {
				$this->collFfbAdmins = array();
			} else {

				$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

				$this->collFfbAdmins = FfbAdminPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->user_id);

			if (!isset($this->lastFfbAdminCriteria) || !$this->lastFfbAdminCriteria->equals($criteria)) {
				$this->collFfbAdmins = FfbAdminPeer::doSelectJoinFfbGame($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbAdminCriteria = $criteria;

		return $this->collFfbAdmins;
	}

	/**
	 * Clears out the collWebLogs collection (array).
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
	 * Initializes the collWebLogs collection (array).
	 *
	 * By default this just sets the collWebLogs collection to an empty array (like clearcollWebLogs());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebLogs()
	{
		$this->collWebLogs = array();
	}

	/**
	 * Gets an array of WebLog objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related WebLogs from storage. If this WebUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WebLog[]
	 * @throws     PropelException
	 */
	public function getWebLogs($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebLogs === null) {
			if ($this->isNew()) {
			   $this->collWebLogs = array();
			} else {

				$criteria->add(WebLogPeer::LOG_USER_ID, $this->user_id);

				WebLogPeer::addSelectColumns($criteria);
				$this->collWebLogs = WebLogPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WebLogPeer::LOG_USER_ID, $this->user_id);

				WebLogPeer::addSelectColumns($criteria);
				if (!isset($this->lastWebLogCriteria) || !$this->lastWebLogCriteria->equals($criteria)) {
					$this->collWebLogs = WebLogPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWebLogCriteria = $criteria;
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
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWebLogs === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WebLogPeer::LOG_USER_ID, $this->user_id);

				$count = WebLogPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WebLogPeer::LOG_USER_ID, $this->user_id);

				if (!isset($this->lastWebLogCriteria) || !$this->lastWebLogCriteria->equals($criteria)) {
					$count = WebLogPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collWebLogs);
				}
			} else {
				$count = count($this->collWebLogs);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collWebLogs, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWebLogs, $l);
			$l->setWebUser($this);
		}
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
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related FfbUserAwardFinisheds from storage. If this WebUser is new, it will return
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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserAwardFinisheds === null) {
			if ($this->isNew()) {
			   $this->collFfbUserAwardFinisheds = array();
			} else {

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

				FfbUserAwardFinishedPeer::addSelectColumns($criteria);
				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

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
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
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

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

				$count = FfbUserAwardFinishedPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

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
	 */
	public function getFfbUserAwardFinishedsJoinFfbUserAwardDefines($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collFfbUserAwardFinisheds === null) {
			if ($this->isNew()) {
				$this->collFfbUserAwardFinisheds = array();
			} else {

				$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelectJoinFfbUserAwardDefines($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $this->user_id);

			if (!isset($this->lastFfbUserAwardFinishedCriteria) || !$this->lastFfbUserAwardFinishedCriteria->equals($criteria)) {
				$this->collFfbUserAwardFinisheds = FfbUserAwardFinishedPeer::doSelectJoinFfbUserAwardDefines($criteria, $con, $join_behavior);
			}
		}
		$this->lastFfbUserAwardFinishedCriteria = $criteria;

		return $this->collFfbUserAwardFinisheds;
	}

	/**
	 * Clears out the collWebAdmins collection (array).
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
	 * Initializes the collWebAdmins collection (array).
	 *
	 * By default this just sets the collWebAdmins collection to an empty array (like clearcollWebAdmins());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initWebAdmins()
	{
		$this->collWebAdmins = array();
	}

	/**
	 * Gets an array of WebAdmin objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this WebUser has previously been saved, it will retrieve
	 * related WebAdmins from storage. If this WebUser is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array WebAdmin[]
	 * @throws     PropelException
	 */
	public function getWebAdmins($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collWebAdmins === null) {
			if ($this->isNew()) {
			   $this->collWebAdmins = array();
			} else {

				$criteria->add(WebAdminPeer::ADMIN_USER_ID, $this->user_id);

				WebAdminPeer::addSelectColumns($criteria);
				$this->collWebAdmins = WebAdminPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(WebAdminPeer::ADMIN_USER_ID, $this->user_id);

				WebAdminPeer::addSelectColumns($criteria);
				if (!isset($this->lastWebAdminCriteria) || !$this->lastWebAdminCriteria->equals($criteria)) {
					$this->collWebAdmins = WebAdminPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastWebAdminCriteria = $criteria;
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
		if ($criteria === null) {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collWebAdmins === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(WebAdminPeer::ADMIN_USER_ID, $this->user_id);

				$count = WebAdminPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(WebAdminPeer::ADMIN_USER_ID, $this->user_id);

				if (!isset($this->lastWebAdminCriteria) || !$this->lastWebAdminCriteria->equals($criteria)) {
					$count = WebAdminPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collWebAdmins);
				}
			} else {
				$count = count($this->collWebAdmins);
			}
		}
		return $count;
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
		if (!in_array($l, $this->collWebAdmins, true)) { // only add it if the **same** object is not already associated
			array_push($this->collWebAdmins, $l);
			$l->setWebUser($this);
		}
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

} // BaseWebUser
