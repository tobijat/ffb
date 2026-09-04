<?php

/**
 * Base static class for performing query and update operations on the 'ffb_ads_allocation' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbAdsAllocationPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_ads_allocation';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbAdsAllocation';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbAdsAllocation';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbAdsAllocationTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 9;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ADS_ALLOCATION_ID field */
	const ADS_ALLOCATION_ID = 'ffb_ads_allocation.ADS_ALLOCATION_ID';

	/** the column name for the ADS_ALLOCATION_ADS_ID field */
	const ADS_ALLOCATION_ADS_ID = 'ffb_ads_allocation.ADS_ALLOCATION_ADS_ID';

	/** the column name for the ADS_ALLOCATION_SLOT_ID field */
	const ADS_ALLOCATION_SLOT_ID = 'ffb_ads_allocation.ADS_ALLOCATION_SLOT_ID';

	/** the column name for the ADS_ALLOCATION_GAME_ID field */
	const ADS_ALLOCATION_GAME_ID = 'ffb_ads_allocation.ADS_ALLOCATION_GAME_ID';

	/** the column name for the ADS_ALLOCATION_AD_COUNT field */
	const ADS_ALLOCATION_AD_COUNT = 'ffb_ads_allocation.ADS_ALLOCATION_AD_COUNT';

	/** the column name for the ADS_ALLOCATION_AD_MAX field */
	const ADS_ALLOCATION_AD_MAX = 'ffb_ads_allocation.ADS_ALLOCATION_AD_MAX';

	/** the column name for the ADS_ALLOCATION_AD_PRIORITY field */
	const ADS_ALLOCATION_AD_PRIORITY = 'ffb_ads_allocation.ADS_ALLOCATION_AD_PRIORITY';

	/** the column name for the ADS_ALLOCATION_START field */
	const ADS_ALLOCATION_START = 'ffb_ads_allocation.ADS_ALLOCATION_START';

	/** the column name for the ADS_ALLOCATION_END field */
	const ADS_ALLOCATION_END = 'ffb_ads_allocation.ADS_ALLOCATION_END';

	/**
	 * An identiy map to hold any loaded instances of FfbAdsAllocation objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbAdsAllocation[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('AdsAllocationId', 'AdsAllocationAdsId', 'AdsAllocationSlotId', 'AdsAllocationGameId', 'AdsAllocationAdCount', 'AdsAllocationAdMax', 'AdsAllocationAdPriority', 'AdsAllocationStart', 'AdsAllocationEnd', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('adsAllocationId', 'adsAllocationAdsId', 'adsAllocationSlotId', 'adsAllocationGameId', 'adsAllocationAdCount', 'adsAllocationAdMax', 'adsAllocationAdPriority', 'adsAllocationStart', 'adsAllocationEnd', ),
		BasePeer::TYPE_COLNAME => array (self::ADS_ALLOCATION_ID, self::ADS_ALLOCATION_ADS_ID, self::ADS_ALLOCATION_SLOT_ID, self::ADS_ALLOCATION_GAME_ID, self::ADS_ALLOCATION_AD_COUNT, self::ADS_ALLOCATION_AD_MAX, self::ADS_ALLOCATION_AD_PRIORITY, self::ADS_ALLOCATION_START, self::ADS_ALLOCATION_END, ),
		BasePeer::TYPE_FIELDNAME => array ('ads_allocation_id', 'ads_allocation_ads_id', 'ads_allocation_slot_id', 'ads_allocation_game_id', 'ads_allocation_ad_count', 'ads_allocation_ad_max', 'ads_allocation_ad_priority', 'ads_allocation_start', 'ads_allocation_end', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('AdsAllocationId' => 0, 'AdsAllocationAdsId' => 1, 'AdsAllocationSlotId' => 2, 'AdsAllocationGameId' => 3, 'AdsAllocationAdCount' => 4, 'AdsAllocationAdMax' => 5, 'AdsAllocationAdPriority' => 6, 'AdsAllocationStart' => 7, 'AdsAllocationEnd' => 8, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('adsAllocationId' => 0, 'adsAllocationAdsId' => 1, 'adsAllocationSlotId' => 2, 'adsAllocationGameId' => 3, 'adsAllocationAdCount' => 4, 'adsAllocationAdMax' => 5, 'adsAllocationAdPriority' => 6, 'adsAllocationStart' => 7, 'adsAllocationEnd' => 8, ),
		BasePeer::TYPE_COLNAME => array (self::ADS_ALLOCATION_ID => 0, self::ADS_ALLOCATION_ADS_ID => 1, self::ADS_ALLOCATION_SLOT_ID => 2, self::ADS_ALLOCATION_GAME_ID => 3, self::ADS_ALLOCATION_AD_COUNT => 4, self::ADS_ALLOCATION_AD_MAX => 5, self::ADS_ALLOCATION_AD_PRIORITY => 6, self::ADS_ALLOCATION_START => 7, self::ADS_ALLOCATION_END => 8, ),
		BasePeer::TYPE_FIELDNAME => array ('ads_allocation_id' => 0, 'ads_allocation_ads_id' => 1, 'ads_allocation_slot_id' => 2, 'ads_allocation_game_id' => 3, 'ads_allocation_ad_count' => 4, 'ads_allocation_ad_max' => 5, 'ads_allocation_ad_priority' => 6, 'ads_allocation_start' => 7, 'ads_allocation_end' => 8, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
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
	 * @param      string $column The column name for current table. (i.e. FfbAdsAllocationPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbAdsAllocationPeer::TABLE_NAME.'.', $alias.'.', $column);
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
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_ID);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_COUNT);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_MAX);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_PRIORITY);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_START);
		$criteria->addSelectColumn(FfbAdsAllocationPeer::ADS_ALLOCATION_END);
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
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     FfbAdsAllocation
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbAdsAllocationPeer::doSelect($critcopy, $con);
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
		return FfbAdsAllocationPeer::populateObjects(FfbAdsAllocationPeer::doSelectStmt($criteria, $con));
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
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbAdsAllocationPeer::addSelectColumns($criteria);
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
	 * @param      FfbAdsAllocation $value A FfbAdsAllocation object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbAdsAllocation $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getAdsAllocationId();
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
	 * @param      mixed $value A FfbAdsAllocation object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbAdsAllocation) {
				$key = (string) $value->getAdsAllocationId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbAdsAllocation object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     FfbAdsAllocation Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to ffb_ads_allocation
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
		$cls = FfbAdsAllocationPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbAdsAllocationPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbAdsAllocationPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related FfbAds table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbAds(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbAdsSlot table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbAdsSlot(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

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
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Selects a collection of FfbAdsAllocation objects pre-filled with their FfbAds objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbAds(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbAdsPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbAdsPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbAdsPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbAdsPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbAdsPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbAdsAllocation) to $obj2 (FfbAds)
				$obj2->addFfbAdsAllocation($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbAdsAllocation objects pre-filled with their FfbAdsSlot objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbAdsSlot(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbAdsSlotPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbAdsSlotPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbAdsSlotPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbAdsSlotPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbAdsSlotPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbAdsAllocation) to $obj2 (FfbAdsSlot)
				$obj2->addFfbAdsAllocation($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbAdsAllocation objects pre-filled with their FfbGame objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
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

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbGamePeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbAdsAllocation) to $obj2 (FfbGame)
				$obj2->addFfbAdsAllocation($obj1);

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
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Selects a collection of FfbAdsAllocation objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
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

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol2 = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbAdsPeer::NUM_COLUMNS - FfbAdsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsSlotPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbAdsSlotPeer::NUM_COLUMNS - FfbAdsSlotPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined FfbAds rows

			$key2 = FfbAdsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = FfbAdsPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbAdsPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbAdsPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj2 (FfbAds)
				$obj2->addFfbAdsAllocation($obj1);
			} // if joined row not null

			// Add objects for joined FfbAdsSlot rows

			$key3 = FfbAdsSlotPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = FfbAdsSlotPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$cls = FfbAdsSlotPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbAdsSlotPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj3 (FfbAdsSlot)
				$obj3->addFfbAdsAllocation($obj1);
			} // if joined row not null

			// Add objects for joined FfbGame rows

			$key4 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = FfbGamePeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$cls = FfbGamePeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbGamePeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj4 (FfbGame)
				$obj4->addFfbAdsAllocation($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbAds table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbAds(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbAdsSlot table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbAdsSlot(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);

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
		$criteria->setPrimaryTableName(FfbAdsAllocationPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbAdsAllocationPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

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
	 * Selects a collection of FfbAdsAllocation objects pre-filled with all related objects except FfbAds.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbAds(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol2 = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsSlotPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbAdsSlotPeer::NUM_COLUMNS - FfbAdsSlotPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbAdsSlot rows

				$key2 = FfbAdsSlotPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbAdsSlotPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbAdsSlotPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbAdsSlotPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj2 (FfbAdsSlot)
				$obj2->addFfbAdsAllocation($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key3 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbGamePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbGamePeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj3 (FfbGame)
				$obj3->addFfbAdsAllocation($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbAdsAllocation objects pre-filled with all related objects except FfbAdsSlot.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbAdsSlot(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol2 = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbAdsPeer::NUM_COLUMNS - FfbAdsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbGamePeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbGamePeer::NUM_COLUMNS - FfbGamePeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, FfbGamePeer::GAME_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbAds rows

				$key2 = FfbAdsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbAdsPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbAdsPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbAdsPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj2 (FfbAds)
				$obj2->addFfbAdsAllocation($obj1);

			} // if joined row is not null

				// Add objects for joined FfbGame rows

				$key3 = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbGamePeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbGamePeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbGamePeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj3 (FfbGame)
				$obj3->addFfbAdsAllocation($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbAdsAllocation objects pre-filled with all related objects except FfbGame.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbAdsAllocation objects.
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

		FfbAdsAllocationPeer::addSelectColumns($criteria);
		$startcol2 = (FfbAdsAllocationPeer::NUM_COLUMNS - FfbAdsAllocationPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbAdsPeer::NUM_COLUMNS - FfbAdsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbAdsSlotPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbAdsSlotPeer::NUM_COLUMNS - FfbAdsSlotPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, FfbAdsPeer::ADS_ID, $join_behavior);

		$criteria->addJoin(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, FfbAdsSlotPeer::ADS_SLOT_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbAdsAllocationPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbAdsAllocationPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbAdsAllocationPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbAdsAllocationPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbAds rows

				$key2 = FfbAdsPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbAdsPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbAdsPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbAdsPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj2 (FfbAds)
				$obj2->addFfbAdsAllocation($obj1);

			} // if joined row is not null

				// Add objects for joined FfbAdsSlot rows

				$key3 = FfbAdsSlotPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbAdsSlotPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbAdsSlotPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbAdsSlotPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbAdsAllocation) to the collection in $obj3 (FfbAdsSlot)
				$obj3->addFfbAdsAllocation($obj1);

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
	  $dbMap = Propel::getDatabaseMap(BaseFfbAdsAllocationPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbAdsAllocationPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbAdsAllocationTableMap());
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
		return $withPrefix ? FfbAdsAllocationPeer::CLASS_DEFAULT : FfbAdsAllocationPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbAdsAllocation or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbAdsAllocation object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbAdsAllocation object
		}

		if ($criteria->containsKey(FfbAdsAllocationPeer::ADS_ALLOCATION_ID) && $criteria->keyContainsValue(FfbAdsAllocationPeer::ADS_ALLOCATION_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbAdsAllocationPeer::ADS_ALLOCATION_ID.')');
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
	 * Method perform an UPDATE on the database, given a FfbAdsAllocation or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbAdsAllocation object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbAdsAllocationPeer::ADS_ALLOCATION_ID);
			$selectCriteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $criteria->remove(FfbAdsAllocationPeer::ADS_ALLOCATION_ID), $comparison);

		} else { // $values is FfbAdsAllocation object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_ads_allocation table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(FfbAdsAllocationPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbAdsAllocationPeer::clearInstancePool();
			FfbAdsAllocationPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbAdsAllocation or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbAdsAllocation object or primary key or array of primary keys
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
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			FfbAdsAllocationPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbAdsAllocation) {
			// invalidate the cache for this single object
			FfbAdsAllocationPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				FfbAdsAllocationPeer::removeInstanceFromPool($singleval);
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
			FfbAdsAllocationPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given FfbAdsAllocation object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbAdsAllocation $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbAdsAllocation $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbAdsAllocationPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbAdsAllocationPeer::TABLE_NAME);

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

		return BasePeer::doValidate(FfbAdsAllocationPeer::DATABASE_NAME, FfbAdsAllocationPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbAdsAllocation
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = FfbAdsAllocationPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbAdsAllocationPeer::DATABASE_NAME);
		$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $pk);

		$v = FfbAdsAllocationPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(FfbAdsAllocationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbAdsAllocationPeer::DATABASE_NAME);
			$criteria->add(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $pks, Criteria::IN);
			$objs = FfbAdsAllocationPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbAdsAllocationPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbAdsAllocationPeer::buildTableMap();

