<?php

/**
 * Base static class for performing query and update operations on the 'web_user' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseWebUserPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'web_user';

	/** the related Propel class for this table */
	const OM_CLASS = 'WebUser';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.WebUser';

	/** the related TableMap class for this table */
	const TM_CLASS = 'WebUserTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 19;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the USER_ID field */
	const USER_ID = 'web_user.USER_ID';

	/** the column name for the USER_NICKNAME field */
	const USER_NICKNAME = 'web_user.USER_NICKNAME';

	/** the column name for the USER_PASSWORD field */
	const USER_PASSWORD = 'web_user.USER_PASSWORD';

	/** the column name for the USER_EMAIL field */
	const USER_EMAIL = 'web_user.USER_EMAIL';

	/** the column name for the USER_FNAME field */
	const USER_FNAME = 'web_user.USER_FNAME';

	/** the column name for the USER_LNAME field */
	const USER_LNAME = 'web_user.USER_LNAME';

	/** the column name for the USER_GENDER field */
	const USER_GENDER = 'web_user.USER_GENDER';

	/** the column name for the USER_STATUS field */
	const USER_STATUS = 'web_user.USER_STATUS';

	/** the column name for the USER_ADMIN field */
	const USER_ADMIN = 'web_user.USER_ADMIN';

	/** the column name for the USER_FACEBOOK_ID field */
	const USER_FACEBOOK_ID = 'web_user.USER_FACEBOOK_ID';

	/** the column name for the USER_NATIONALITY field */
	const USER_NATIONALITY = 'web_user.USER_NATIONALITY';

	/** the column name for the USER_DATE_BIRTH field */
	const USER_DATE_BIRTH = 'web_user.USER_DATE_BIRTH';

	/** the column name for the USER_IP field */
	const USER_IP = 'web_user.USER_IP';

	/** the column name for the USER_LIP field */
	const USER_LIP = 'web_user.USER_LIP';

	/** the column name for the USER_DATE_REGISTER field */
	const USER_DATE_REGISTER = 'web_user.USER_DATE_REGISTER';

	/** the column name for the USER_DATE_LLOGIN field */
	const USER_DATE_LLOGIN = 'web_user.USER_DATE_LLOGIN';

	/** the column name for the USER_DATE_LACTION field */
	const USER_DATE_LACTION = 'web_user.USER_DATE_LACTION';

	/** the column name for the USER_ACTIVATION_CODE field */
	const USER_ACTIVATION_CODE = 'web_user.USER_ACTIVATION_CODE';

	/** the column name for the USER_MAILSERVICE field */
	const USER_MAILSERVICE = 'web_user.USER_MAILSERVICE';

	/**
	 * An identiy map to hold any loaded instances of WebUser objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array WebUser[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('UserId', 'UserNickname', 'UserPassword', 'UserEmail', 'UserFname', 'UserLname', 'UserGender', 'UserStatus', 'UserAdmin', 'UserFacebookId', 'UserNationality', 'UserDateBirth', 'UserIp', 'UserLip', 'UserDateRegister', 'UserDateLlogin', 'UserDateLaction', 'UserActivationCode', 'UserMailservice', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userId', 'userNickname', 'userPassword', 'userEmail', 'userFname', 'userLname', 'userGender', 'userStatus', 'userAdmin', 'userFacebookId', 'userNationality', 'userDateBirth', 'userIp', 'userLip', 'userDateRegister', 'userDateLlogin', 'userDateLaction', 'userActivationCode', 'userMailservice', ),
		BasePeer::TYPE_COLNAME => array (self::USER_ID, self::USER_NICKNAME, self::USER_PASSWORD, self::USER_EMAIL, self::USER_FNAME, self::USER_LNAME, self::USER_GENDER, self::USER_STATUS, self::USER_ADMIN, self::USER_FACEBOOK_ID, self::USER_NATIONALITY, self::USER_DATE_BIRTH, self::USER_IP, self::USER_LIP, self::USER_DATE_REGISTER, self::USER_DATE_LLOGIN, self::USER_DATE_LACTION, self::USER_ACTIVATION_CODE, self::USER_MAILSERVICE, ),
		BasePeer::TYPE_FIELDNAME => array ('user_id', 'user_nickname', 'user_password', 'user_email', 'user_fname', 'user_lname', 'user_gender', 'user_status', 'user_admin', 'user_facebook_id', 'user_nationality', 'user_date_birth', 'user_ip', 'user_lip', 'user_date_register', 'user_date_llogin', 'user_date_laction', 'user_activation_code', 'user_mailservice', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('UserId' => 0, 'UserNickname' => 1, 'UserPassword' => 2, 'UserEmail' => 3, 'UserFname' => 4, 'UserLname' => 5, 'UserGender' => 6, 'UserStatus' => 7, 'UserAdmin' => 8, 'UserFacebookId' => 9, 'UserNationality' => 10, 'UserDateBirth' => 11, 'UserIp' => 12, 'UserLip' => 13, 'UserDateRegister' => 14, 'UserDateLlogin' => 15, 'UserDateLaction' => 16, 'UserActivationCode' => 17, 'UserMailservice' => 18, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userId' => 0, 'userNickname' => 1, 'userPassword' => 2, 'userEmail' => 3, 'userFname' => 4, 'userLname' => 5, 'userGender' => 6, 'userStatus' => 7, 'userAdmin' => 8, 'userFacebookId' => 9, 'userNationality' => 10, 'userDateBirth' => 11, 'userIp' => 12, 'userLip' => 13, 'userDateRegister' => 14, 'userDateLlogin' => 15, 'userDateLaction' => 16, 'userActivationCode' => 17, 'userMailservice' => 18, ),
		BasePeer::TYPE_COLNAME => array (self::USER_ID => 0, self::USER_NICKNAME => 1, self::USER_PASSWORD => 2, self::USER_EMAIL => 3, self::USER_FNAME => 4, self::USER_LNAME => 5, self::USER_GENDER => 6, self::USER_STATUS => 7, self::USER_ADMIN => 8, self::USER_FACEBOOK_ID => 9, self::USER_NATIONALITY => 10, self::USER_DATE_BIRTH => 11, self::USER_IP => 12, self::USER_LIP => 13, self::USER_DATE_REGISTER => 14, self::USER_DATE_LLOGIN => 15, self::USER_DATE_LACTION => 16, self::USER_ACTIVATION_CODE => 17, self::USER_MAILSERVICE => 18, ),
		BasePeer::TYPE_FIELDNAME => array ('user_id' => 0, 'user_nickname' => 1, 'user_password' => 2, 'user_email' => 3, 'user_fname' => 4, 'user_lname' => 5, 'user_gender' => 6, 'user_status' => 7, 'user_admin' => 8, 'user_facebook_id' => 9, 'user_nationality' => 10, 'user_date_birth' => 11, 'user_ip' => 12, 'user_lip' => 13, 'user_date_register' => 14, 'user_date_llogin' => 15, 'user_date_laction' => 16, 'user_activation_code' => 17, 'user_mailservice' => 18, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. WebUserPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(WebUserPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{
		$criteria->addSelectColumn(WebUserPeer::USER_ID);
		$criteria->addSelectColumn(WebUserPeer::USER_NICKNAME);
		$criteria->addSelectColumn(WebUserPeer::USER_PASSWORD);
		$criteria->addSelectColumn(WebUserPeer::USER_EMAIL);
		$criteria->addSelectColumn(WebUserPeer::USER_FNAME);
		$criteria->addSelectColumn(WebUserPeer::USER_LNAME);
		$criteria->addSelectColumn(WebUserPeer::USER_GENDER);
		$criteria->addSelectColumn(WebUserPeer::USER_STATUS);
		$criteria->addSelectColumn(WebUserPeer::USER_ADMIN);
		$criteria->addSelectColumn(WebUserPeer::USER_FACEBOOK_ID);
		$criteria->addSelectColumn(WebUserPeer::USER_NATIONALITY);
		$criteria->addSelectColumn(WebUserPeer::USER_DATE_BIRTH);
		$criteria->addSelectColumn(WebUserPeer::USER_IP);
		$criteria->addSelectColumn(WebUserPeer::USER_LIP);
		$criteria->addSelectColumn(WebUserPeer::USER_DATE_REGISTER);
		$criteria->addSelectColumn(WebUserPeer::USER_DATE_LLOGIN);
		$criteria->addSelectColumn(WebUserPeer::USER_DATE_LACTION);
		$criteria->addSelectColumn(WebUserPeer::USER_ACTIVATION_CODE);
		$criteria->addSelectColumn(WebUserPeer::USER_MAILSERVICE);
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     WebUser
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = WebUserPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return WebUserPeer::populateObjects(WebUserPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			WebUserPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      WebUser $value A WebUser object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(WebUser $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getUserId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A WebUser object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof WebUser) {
				$key = (string) $value->getUserId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or WebUser object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     WebUser Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to web_user
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
		// invalidate objects in WebUserDetailsPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		WebUserDetailsPeer::clearInstancePool();

		// invalidate objects in WebUserPermissionsPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		WebUserPermissionsPeer::clearInstancePool();

		// invalidate objects in FfbCommentsPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		FfbCommentsPeer::clearInstancePool();

		// invalidate objects in FfbInvitationPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		FfbInvitationPeer::clearInstancePool();

		// invalidate objects in FfbUserscorePeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		FfbUserscorePeer::clearInstancePool();

		// invalidate objects in FfbAdminPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		FfbAdminPeer::clearInstancePool();

		// invalidate objects in WebLogPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		WebLogPeer::clearInstancePool();

		// invalidate objects in FfbUserAwardFinishedPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		FfbUserAwardFinishedPeer::clearInstancePool();

		// invalidate objects in WebAdminPeer instance pool, since one or more of them may be deleted by ON DELETE CASCADE rule.
		WebAdminPeer::clearInstancePool();

	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol] === null) {
			return null;
		}
		return (string) $row[$startcol];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = WebUserPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = WebUserPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = WebUserPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				WebUserPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseWebUserPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseWebUserPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new WebUserTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * If $withPrefix is true, the returned path
	 * uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @param      boolean  Whether or not to return the path wit hthe class name 
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? WebUserPeer::CLASS_DEFAULT : WebUserPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a WebUser or Criteria object.
	 *
	 * @param      mixed $values Criteria or WebUser object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from WebUser object
		}

		if ($criteria->containsKey(WebUserPeer::USER_ID) && $criteria->keyContainsValue(WebUserPeer::USER_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.WebUserPeer::USER_ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a WebUser or Criteria object.
	 *
	 * @param      mixed $values Criteria or WebUser object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(WebUserPeer::USER_ID);
			$selectCriteria->add(WebUserPeer::USER_ID, $criteria->remove(WebUserPeer::USER_ID), $comparison);

		} else { // $values is WebUser object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the web_user table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += WebUserPeer::doOnDeleteCascade(new Criteria(WebUserPeer::DATABASE_NAME), $con);
			$affectedRows += BasePeer::doDeleteAll(WebUserPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			WebUserPeer::clearInstancePool();
			WebUserPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a WebUser or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or WebUser object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			WebUserPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof WebUser) {
			// invalidate the cache for this single object
			WebUserPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(WebUserPeer::USER_ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				WebUserPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += WebUserPeer::doOnDeleteCascade($criteria, $con);
			
				// Because this db requires some delete cascade/set null emulation, we have to
				// clear the cached instance *after* the emulation has happened (since
				// instances get re-added by the select statement contained therein).
				if ($values instanceof Criteria) {
					WebUserPeer::clearInstancePool();
				} else { // it's a PK or object
					WebUserPeer::removeInstanceFromPool($values);
				}
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			WebUserPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
	 * feature (like MySQL or SQLite).
	 *
	 * This method is not very speedy because it must perform a query first to get
	 * the implicated records and then perform the deletes by calling those Peer classes.
	 *
	 * This method should be used within a transaction if possible.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
	{
		// initialize var to track total num of affected rows
		$affectedRows = 0;

		// first find the objects that are implicated by the $criteria
		$objects = WebUserPeer::doSelect($criteria, $con);
		foreach ($objects as $obj) {


			// delete related WebUserDetails objects
			$criteria = new Criteria(WebUserDetailsPeer::DATABASE_NAME);
			
			$criteria->add(WebUserDetailsPeer::USER_ID, $obj->getUserId());
			$affectedRows += WebUserDetailsPeer::doDelete($criteria, $con);

			// delete related WebUserPermissions objects
			$criteria = new Criteria(WebUserPermissionsPeer::DATABASE_NAME);
			
			$criteria->add(WebUserPermissionsPeer::USER_ID, $obj->getUserId());
			$affectedRows += WebUserPermissionsPeer::doDelete($criteria, $con);

			// delete related FfbComments objects
			$criteria = new Criteria(FfbCommentsPeer::DATABASE_NAME);
			
			$criteria->add(FfbCommentsPeer::COMMENTS_USER_ID, $obj->getUserId());
			$affectedRows += FfbCommentsPeer::doDelete($criteria, $con);

			// delete related FfbInvitation objects
			$criteria = new Criteria(FfbInvitationPeer::DATABASE_NAME);
			
			$criteria->add(FfbInvitationPeer::INVITATION_SENDER_ID, $obj->getUserId());
			$affectedRows += FfbInvitationPeer::doDelete($criteria, $con);

			// delete related FfbUserscore objects
			$criteria = new Criteria(FfbUserscorePeer::DATABASE_NAME);
			
			$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $obj->getUserId());
			$affectedRows += FfbUserscorePeer::doDelete($criteria, $con);

			// delete related FfbAdmin objects
			$criteria = new Criteria(FfbAdminPeer::DATABASE_NAME);
			
			$criteria->add(FfbAdminPeer::ADMIN_USER_ID, $obj->getUserId());
			$affectedRows += FfbAdminPeer::doDelete($criteria, $con);

			// delete related WebLog objects
			$criteria = new Criteria(WebLogPeer::DATABASE_NAME);
			
			$criteria->add(WebLogPeer::LOG_USER_ID, $obj->getUserId());
			$affectedRows += WebLogPeer::doDelete($criteria, $con);

			// delete related FfbUserAwardFinished objects
			$criteria = new Criteria(FfbUserAwardFinishedPeer::DATABASE_NAME);
			
			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $obj->getUserId());
			$affectedRows += FfbUserAwardFinishedPeer::doDelete($criteria, $con);

			// delete related WebAdmin objects
			$criteria = new Criteria(WebAdminPeer::DATABASE_NAME);
			
			$criteria->add(WebAdminPeer::ADMIN_USER_ID, $obj->getUserId());
			$affectedRows += WebAdminPeer::doDelete($criteria, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given WebUser object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      WebUser $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(WebUser $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(WebUserPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(WebUserPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(WebUserPeer::DATABASE_NAME, WebUserPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     WebUser
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = WebUserPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
		$criteria->add(WebUserPeer::USER_ID, $pk);

		$v = WebUserPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(WebUserPeer::DATABASE_NAME);
			$criteria->add(WebUserPeer::USER_ID, $pks, Criteria::IN);
			$objs = WebUserPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseWebUserPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseWebUserPeer::buildTableMap();

