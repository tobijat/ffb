<?php

/**
 * Base static class for performing query and update operations on the 'web_user_details' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseWebUserDetailsPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'web_user_details';

	/** the related Propel class for this table */
	const OM_CLASS = 'WebUserDetails';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.WebUserDetails';

	/** the related TableMap class for this table */
	const TM_CLASS = 'WebUserDetailsTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 13;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the USER_ID field */
	const USER_ID = 'web_user_details.USER_ID';

	/** the column name for the USER_DETAILS_AVATAR field */
	const USER_DETAILS_AVATAR = 'web_user_details.USER_DETAILS_AVATAR';

	/** the column name for the USER_DETAILS_PHOTO field */
	const USER_DETAILS_PHOTO = 'web_user_details.USER_DETAILS_PHOTO';

	/** the column name for the USER_DETAILS_WEBSITE field */
	const USER_DETAILS_WEBSITE = 'web_user_details.USER_DETAILS_WEBSITE';

	/** the column name for the USER_DETAILS_ZIP field */
	const USER_DETAILS_ZIP = 'web_user_details.USER_DETAILS_ZIP';

	/** the column name for the USER_DETAILS_STREET field */
	const USER_DETAILS_STREET = 'web_user_details.USER_DETAILS_STREET';

	/** the column name for the USER_DETAILS_CITY field */
	const USER_DETAILS_CITY = 'web_user_details.USER_DETAILS_CITY';

	/** the column name for the USER_DETAILS_PHONE field */
	const USER_DETAILS_PHONE = 'web_user_details.USER_DETAILS_PHONE';

	/** the column name for the USER_DETAILS_FFB_FAVOURITE_TEAM field */
	const USER_DETAILS_FFB_FAVOURITE_TEAM = 'web_user_details.USER_DETAILS_FFB_FAVOURITE_TEAM';

	/** the column name for the USER_DETAILS_FFB_OWN_TEAM field */
	const USER_DETAILS_FFB_OWN_TEAM = 'web_user_details.USER_DETAILS_FFB_OWN_TEAM';

	/** the column name for the USER_DETAILS_FFB_OWN_PLAYER field */
	const USER_DETAILS_FFB_OWN_PLAYER = 'web_user_details.USER_DETAILS_FFB_OWN_PLAYER';

	/** the column name for the USER_DETAILS_FFB_SELECTED_GAME field */
	const USER_DETAILS_FFB_SELECTED_GAME = 'web_user_details.USER_DETAILS_FFB_SELECTED_GAME';

	/** the column name for the USER_DETAILS_LAST_UPDATE field */
	const USER_DETAILS_LAST_UPDATE = 'web_user_details.USER_DETAILS_LAST_UPDATE';

	/**
	 * An identiy map to hold any loaded instances of WebUserDetails objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array WebUserDetails[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('UserId', 'UserDetailsAvatar', 'UserDetailsPhoto', 'UserDetailsWebsite', 'UserDetailsZip', 'UserDetailsStreet', 'UserDetailsCity', 'UserDetailsPhone', 'UserDetailsFfbFavouriteTeam', 'UserDetailsFfbOwnTeam', 'UserDetailsFfbOwnPlayer', 'UserDetailsFfbSelectedGame', 'UserDetailsLastUpdate', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userId', 'userDetailsAvatar', 'userDetailsPhoto', 'userDetailsWebsite', 'userDetailsZip', 'userDetailsStreet', 'userDetailsCity', 'userDetailsPhone', 'userDetailsFfbFavouriteTeam', 'userDetailsFfbOwnTeam', 'userDetailsFfbOwnPlayer', 'userDetailsFfbSelectedGame', 'userDetailsLastUpdate', ),
		BasePeer::TYPE_COLNAME => array (self::USER_ID, self::USER_DETAILS_AVATAR, self::USER_DETAILS_PHOTO, self::USER_DETAILS_WEBSITE, self::USER_DETAILS_ZIP, self::USER_DETAILS_STREET, self::USER_DETAILS_CITY, self::USER_DETAILS_PHONE, self::USER_DETAILS_FFB_FAVOURITE_TEAM, self::USER_DETAILS_FFB_OWN_TEAM, self::USER_DETAILS_FFB_OWN_PLAYER, self::USER_DETAILS_FFB_SELECTED_GAME, self::USER_DETAILS_LAST_UPDATE, ),
		BasePeer::TYPE_FIELDNAME => array ('user_id', 'user_details_avatar', 'user_details_photo', 'user_details_website', 'user_details_zip', 'user_details_street', 'user_details_city', 'user_details_phone', 'user_details_ffb_favourite_team', 'user_details_ffb_own_team', 'user_details_ffb_own_player', 'user_details_ffb_selected_game', 'user_details_last_update', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('UserId' => 0, 'UserDetailsAvatar' => 1, 'UserDetailsPhoto' => 2, 'UserDetailsWebsite' => 3, 'UserDetailsZip' => 4, 'UserDetailsStreet' => 5, 'UserDetailsCity' => 6, 'UserDetailsPhone' => 7, 'UserDetailsFfbFavouriteTeam' => 8, 'UserDetailsFfbOwnTeam' => 9, 'UserDetailsFfbOwnPlayer' => 10, 'UserDetailsFfbSelectedGame' => 11, 'UserDetailsLastUpdate' => 12, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userId' => 0, 'userDetailsAvatar' => 1, 'userDetailsPhoto' => 2, 'userDetailsWebsite' => 3, 'userDetailsZip' => 4, 'userDetailsStreet' => 5, 'userDetailsCity' => 6, 'userDetailsPhone' => 7, 'userDetailsFfbFavouriteTeam' => 8, 'userDetailsFfbOwnTeam' => 9, 'userDetailsFfbOwnPlayer' => 10, 'userDetailsFfbSelectedGame' => 11, 'userDetailsLastUpdate' => 12, ),
		BasePeer::TYPE_COLNAME => array (self::USER_ID => 0, self::USER_DETAILS_AVATAR => 1, self::USER_DETAILS_PHOTO => 2, self::USER_DETAILS_WEBSITE => 3, self::USER_DETAILS_ZIP => 4, self::USER_DETAILS_STREET => 5, self::USER_DETAILS_CITY => 6, self::USER_DETAILS_PHONE => 7, self::USER_DETAILS_FFB_FAVOURITE_TEAM => 8, self::USER_DETAILS_FFB_OWN_TEAM => 9, self::USER_DETAILS_FFB_OWN_PLAYER => 10, self::USER_DETAILS_FFB_SELECTED_GAME => 11, self::USER_DETAILS_LAST_UPDATE => 12, ),
		BasePeer::TYPE_FIELDNAME => array ('user_id' => 0, 'user_details_avatar' => 1, 'user_details_photo' => 2, 'user_details_website' => 3, 'user_details_zip' => 4, 'user_details_street' => 5, 'user_details_city' => 6, 'user_details_phone' => 7, 'user_details_ffb_favourite_team' => 8, 'user_details_ffb_own_team' => 9, 'user_details_ffb_own_player' => 10, 'user_details_ffb_selected_game' => 11, 'user_details_last_update' => 12, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
	 * @param      string $column The column name for current table. (i.e. WebUserDetailsPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(WebUserDetailsPeer::TABLE_NAME.'.', $alias.'.', $column);
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
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_ID);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_AVATAR);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_PHOTO);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_WEBSITE);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_ZIP);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_STREET);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_CITY);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_PHONE);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME);
		$criteria->addSelectColumn(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE);
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
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     WebUserDetails
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = WebUserDetailsPeer::doSelect($critcopy, $con);
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
		return WebUserDetailsPeer::populateObjects(WebUserDetailsPeer::doSelectStmt($criteria, $con));
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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			WebUserDetailsPeer::addSelectColumns($criteria);
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
	 * @param      WebUserDetails $value A WebUserDetails object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(WebUserDetails $obj, $key = null)
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
	 * @param      mixed $value A WebUserDetails object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof WebUserDetails) {
				$key = (string) $value->getUserId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or WebUserDetails object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     WebUserDetails Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to web_user_details
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
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
		$cls = WebUserDetailsPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = WebUserDetailsPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				WebUserDetailsPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related WebUser table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinWebUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByUserDetailsFfbFavouriteTeam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByUserDetailsFfbOwnTeam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbTeamRelatedByUserDetailsFfbOwnTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayer table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbGame table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbGame(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Selects a collection of WebUserDetails objects pre-filled with their WebUser objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinWebUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);
		WebUserPeer::addSelectColumns($criteria);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = WebUserPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (WebUserDetails) to $obj2 (WebUser)
				$obj2->setWebUserDetails($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with their FfbTeam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbTeamPeer::addSelectColumns($criteria);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbTeamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbTeamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbTeamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (WebUserDetails) to $obj2 (FfbTeam)
				$obj2->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with their FfbTeam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbTeamRelatedByUserDetailsFfbOwnTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbTeamPeer::addSelectColumns($criteria);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbTeamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbTeamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbTeamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (WebUserDetails) to $obj2 (FfbTeam)
				$obj2->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with their FfbPlayer objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayer(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerPeer::addSelectColumns($criteria);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (WebUserDetails) to $obj2 (FfbPlayer)
				$obj2->addWebUserDetails($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with their FfbGame objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbGame(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbGamePeer::addSelectColumns($criteria);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbGamePeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbGamePeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbGamePeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (WebUserDetails) to $obj2 (FfbGame)
				$obj2->addWebUserDetails($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol7 = $startcol6 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined WebUser rows

			$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = WebUserPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (WebUser)
				$obj1->setWebUser($obj2);
			} // if joined row not null

			// Add objects for joined FfbTeam rows

			$key3 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = FfbTeamPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$cls = FfbTeamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbTeamPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbTeam)
				$obj3->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($obj1);
			} // if joined row not null

			// Add objects for joined FfbTeam rows

			$key4 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = FfbTeamPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$cls = FfbTeamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbTeamPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbTeam)
				$obj4->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayer rows

			$key5 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = FfbPlayerPeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$cls = FfbPlayerPeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbPlayerPeer::addInstanceToPool($obj5, $key5);
				} // if obj5 loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj5 (FfbPlayer)
				$obj5->addWebUserDetails($obj1);
			} // if joined row not null

			// Add objects for joined FfbGame rows

			$key6 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = FfbGamePeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$cls = FfbGamePeer::getOMClass(false);

					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					FfbGamePeer::addInstanceToPool($obj6, $key6);
				} // if obj6 loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj6 (FfbGame)
				$obj6->addWebUserDetails($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related WebUser table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptWebUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByUserDetailsFfbFavouriteTeam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbTeamRelatedByUserDetailsFfbFavouriteTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByUserDetailsFfbOwnTeam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbTeamRelatedByUserDetailsFfbOwnTeam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayer table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbGame table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbGame(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(WebUserDetailsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			WebUserDetailsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

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
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects except WebUser.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptWebUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbTeam rows

				$key2 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbTeamPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbTeamPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (FfbTeam)
				$obj2->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbTeam rows

				$key3 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbTeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbTeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbTeam)
				$obj3->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayer rows

				$key4 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbPlayerPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbPlayerPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbPlayerPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbPlayer)
				$obj4->addWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key5 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = FfbGamePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbGamePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj5 (FfbGame)
				$obj5->addWebUserDetails($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects except FfbTeamRelatedByUserDetailsFfbFavouriteTeam.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbTeamRelatedByUserDetailsFfbFavouriteTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined WebUser rows

				$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = WebUserPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (WebUser)
				$obj2->setWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayer rows

				$key3 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbPlayerPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbPlayerPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbPlayerPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbPlayer)
				$obj3->addWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key4 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbGamePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbGamePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbGame)
				$obj4->addWebUserDetails($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects except FfbTeamRelatedByUserDetailsFfbOwnTeam.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbTeamRelatedByUserDetailsFfbOwnTeam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined WebUser rows

				$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = WebUserPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (WebUser)
				$obj2->setWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayer rows

				$key3 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbPlayerPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbPlayerPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbPlayerPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbPlayer)
				$obj3->addWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key4 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbGamePeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbGamePeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbGame)
				$obj4->addWebUserDetails($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects except FfbPlayer.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayer(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined WebUser rows

				$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = WebUserPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (WebUser)
				$obj2->setWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbTeam rows

				$key3 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbTeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbTeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbTeam)
				$obj3->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbTeam rows

				$key4 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbTeamPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbTeamPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbTeam)
				$obj4->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key5 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = FfbGamePeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbGamePeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj5 (FfbGame)
				$obj5->addWebUserDetails($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of WebUserDetails objects pre-filled with all related objects except FfbGame.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of WebUserDetails objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbGame(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		WebUserDetailsPeer::addSelectColumns($criteria);
		$startcol2 = (WebUserDetailsPeer::NUM_COLUMNS - WebUserDetailsPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbPlayerPeer::NUM_COLUMNS - FfbPlayerPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(WebUserDetailsPeer::USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, FfbPlayerPeer::PLAYER_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = WebUserDetailsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = WebUserDetailsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = WebUserDetailsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				WebUserDetailsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined WebUser rows

				$key2 = WebUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = WebUserPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = WebUserPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					WebUserPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj2 (WebUser)
				$obj2->setWebUserDetails($obj1);

			} // if joined row is not null

				// Add objects for joined FfbTeam rows

				$key3 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbTeamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbTeamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj3 (FfbTeam)
				$obj3->addWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbTeam rows

				$key4 = FfbTeamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbTeamPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbTeamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbTeamPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj4 (FfbTeam)
				$obj4->addWebUserDetailsRelatedByUserDetailsFfbOwnTeam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayer rows

				$key5 = FfbPlayerPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = FfbPlayerPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = FfbPlayerPeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbPlayerPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (WebUserDetails) to the collection in $obj5 (FfbPlayer)
				$obj5->addWebUserDetails($obj1);

			} // if joined row is not null

			$results[] = $obj1;
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
	  $dbMap = Propel::getDatabaseMap(BaseWebUserDetailsPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseWebUserDetailsPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new WebUserDetailsTableMap());
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
		return $withPrefix ? WebUserDetailsPeer::CLASS_DEFAULT : WebUserDetailsPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a WebUserDetails or Criteria object.
	 *
	 * @param      mixed $values Criteria or WebUserDetails object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from WebUserDetails object
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
	 * Method perform an UPDATE on the database, given a WebUserDetails or Criteria object.
	 *
	 * @param      mixed $values Criteria or WebUserDetails object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(WebUserDetailsPeer::USER_ID);
			$selectCriteria->add(WebUserDetailsPeer::USER_ID, $criteria->remove(WebUserDetailsPeer::USER_ID), $comparison);

		} else { // $values is WebUserDetails object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the web_user_details table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(WebUserDetailsPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			WebUserDetailsPeer::clearInstancePool();
			WebUserDetailsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a WebUserDetails or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or WebUserDetails object or primary key or array of primary keys
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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			WebUserDetailsPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof WebUserDetails) {
			// invalidate the cache for this single object
			WebUserDetailsPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(WebUserDetailsPeer::USER_ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				WebUserDetailsPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			WebUserDetailsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given WebUserDetails object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      WebUserDetails $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(WebUserDetails $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(WebUserDetailsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(WebUserDetailsPeer::TABLE_NAME);

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

		return BasePeer::doValidate(WebUserDetailsPeer::DATABASE_NAME, WebUserDetailsPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     WebUserDetails
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = WebUserDetailsPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(WebUserDetailsPeer::DATABASE_NAME);
		$criteria->add(WebUserDetailsPeer::USER_ID, $pk);

		$v = WebUserDetailsPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(WebUserDetailsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(WebUserDetailsPeer::DATABASE_NAME);
			$criteria->add(WebUserDetailsPeer::USER_ID, $pks, Criteria::IN);
			$objs = WebUserDetailsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseWebUserDetailsPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseWebUserDetailsPeer::buildTableMap();

