<?php


/**
 * Base static class for performing query and update operations on the 'ffb_userteam' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserteamPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_userteam';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbUserteam';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbUserteam';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbUserteamTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 18;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the USERTEAM_ID field */
	const USERTEAM_ID = 'ffb_userteam.USERTEAM_ID';

	/** the column name for the USERTEAM_USER_ID field */
	const USERTEAM_USER_ID = 'ffb_userteam.USERTEAM_USER_ID';

	/** the column name for the USERTEAM_DATE field */
	const USERTEAM_DATE = 'ffb_userteam.USERTEAM_DATE';

	/** the column name for the USERTEAM_PLAYER_ID1 field */
	const USERTEAM_PLAYER_ID1 = 'ffb_userteam.USERTEAM_PLAYER_ID1';

	/** the column name for the USERTEAM_PLAYER_ID2 field */
	const USERTEAM_PLAYER_ID2 = 'ffb_userteam.USERTEAM_PLAYER_ID2';

	/** the column name for the USERTEAM_PLAYER_ID3 field */
	const USERTEAM_PLAYER_ID3 = 'ffb_userteam.USERTEAM_PLAYER_ID3';

	/** the column name for the USERTEAM_PLAYER_ID4 field */
	const USERTEAM_PLAYER_ID4 = 'ffb_userteam.USERTEAM_PLAYER_ID4';

	/** the column name for the USERTEAM_PLAYER_ID5 field */
	const USERTEAM_PLAYER_ID5 = 'ffb_userteam.USERTEAM_PLAYER_ID5';

	/** the column name for the USERTEAM_PLAYER_ID6 field */
	const USERTEAM_PLAYER_ID6 = 'ffb_userteam.USERTEAM_PLAYER_ID6';

	/** the column name for the USERTEAM_PLAYER_ID7 field */
	const USERTEAM_PLAYER_ID7 = 'ffb_userteam.USERTEAM_PLAYER_ID7';

	/** the column name for the USERTEAM_PLAYER_ID8 field */
	const USERTEAM_PLAYER_ID8 = 'ffb_userteam.USERTEAM_PLAYER_ID8';

	/** the column name for the USERTEAM_PLAYER_ID9 field */
	const USERTEAM_PLAYER_ID9 = 'ffb_userteam.USERTEAM_PLAYER_ID9';

	/** the column name for the USERTEAM_PLAYER_ID10 field */
	const USERTEAM_PLAYER_ID10 = 'ffb_userteam.USERTEAM_PLAYER_ID10';

	/** the column name for the USERTEAM_PLAYER_ID11 field */
	const USERTEAM_PLAYER_ID11 = 'ffb_userteam.USERTEAM_PLAYER_ID11';

	/** the column name for the USERTEAM_PRICE field */
	const USERTEAM_PRICE = 'ffb_userteam.USERTEAM_PRICE';

	/** the column name for the USERTEAM_MATCHROUND_ID field */
	const USERTEAM_MATCHROUND_ID = 'ffb_userteam.USERTEAM_MATCHROUND_ID';

	/** the column name for the USERTEAM_SCORE field */
	const USERTEAM_SCORE = 'ffb_userteam.USERTEAM_SCORE';

	/** the column name for the USERTEAM_WC_POINTS field */
	const USERTEAM_WC_POINTS = 'ffb_userteam.USERTEAM_WC_POINTS';

	/**
	 * An identiy map to hold any loaded instances of FfbUserteam objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbUserteam[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('UserteamId', 'UserteamUserId', 'UserteamDate', 'UserteamPlayerId1', 'UserteamPlayerId2', 'UserteamPlayerId3', 'UserteamPlayerId4', 'UserteamPlayerId5', 'UserteamPlayerId6', 'UserteamPlayerId7', 'UserteamPlayerId8', 'UserteamPlayerId9', 'UserteamPlayerId10', 'UserteamPlayerId11', 'UserteamPrice', 'UserteamMatchroundId', 'UserteamScore', 'UserteamWcPoints', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userteamId', 'userteamUserId', 'userteamDate', 'userteamPlayerId1', 'userteamPlayerId2', 'userteamPlayerId3', 'userteamPlayerId4', 'userteamPlayerId5', 'userteamPlayerId6', 'userteamPlayerId7', 'userteamPlayerId8', 'userteamPlayerId9', 'userteamPlayerId10', 'userteamPlayerId11', 'userteamPrice', 'userteamMatchroundId', 'userteamScore', 'userteamWcPoints', ),
		BasePeer::TYPE_COLNAME => array (self::USERTEAM_ID, self::USERTEAM_USER_ID, self::USERTEAM_DATE, self::USERTEAM_PLAYER_ID1, self::USERTEAM_PLAYER_ID2, self::USERTEAM_PLAYER_ID3, self::USERTEAM_PLAYER_ID4, self::USERTEAM_PLAYER_ID5, self::USERTEAM_PLAYER_ID6, self::USERTEAM_PLAYER_ID7, self::USERTEAM_PLAYER_ID8, self::USERTEAM_PLAYER_ID9, self::USERTEAM_PLAYER_ID10, self::USERTEAM_PLAYER_ID11, self::USERTEAM_PRICE, self::USERTEAM_MATCHROUND_ID, self::USERTEAM_SCORE, self::USERTEAM_WC_POINTS, ),
		BasePeer::TYPE_RAW_COLNAME => array ('USERTEAM_ID', 'USERTEAM_USER_ID', 'USERTEAM_DATE', 'USERTEAM_PLAYER_ID1', 'USERTEAM_PLAYER_ID2', 'USERTEAM_PLAYER_ID3', 'USERTEAM_PLAYER_ID4', 'USERTEAM_PLAYER_ID5', 'USERTEAM_PLAYER_ID6', 'USERTEAM_PLAYER_ID7', 'USERTEAM_PLAYER_ID8', 'USERTEAM_PLAYER_ID9', 'USERTEAM_PLAYER_ID10', 'USERTEAM_PLAYER_ID11', 'USERTEAM_PRICE', 'USERTEAM_MATCHROUND_ID', 'USERTEAM_SCORE', 'USERTEAM_WC_POINTS', ),
		BasePeer::TYPE_FIELDNAME => array ('userteam_id', 'userteam_user_id', 'userteam_date', 'userteam_player_id1', 'userteam_player_id2', 'userteam_player_id3', 'userteam_player_id4', 'userteam_player_id5', 'userteam_player_id6', 'userteam_player_id7', 'userteam_player_id8', 'userteam_player_id9', 'userteam_player_id10', 'userteam_player_id11', 'userteam_price', 'userteam_matchround_id', 'userteam_score', 'userteam_wc_points', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('UserteamId' => 0, 'UserteamUserId' => 1, 'UserteamDate' => 2, 'UserteamPlayerId1' => 3, 'UserteamPlayerId2' => 4, 'UserteamPlayerId3' => 5, 'UserteamPlayerId4' => 6, 'UserteamPlayerId5' => 7, 'UserteamPlayerId6' => 8, 'UserteamPlayerId7' => 9, 'UserteamPlayerId8' => 10, 'UserteamPlayerId9' => 11, 'UserteamPlayerId10' => 12, 'UserteamPlayerId11' => 13, 'UserteamPrice' => 14, 'UserteamMatchroundId' => 15, 'UserteamScore' => 16, 'UserteamWcPoints' => 17, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userteamId' => 0, 'userteamUserId' => 1, 'userteamDate' => 2, 'userteamPlayerId1' => 3, 'userteamPlayerId2' => 4, 'userteamPlayerId3' => 5, 'userteamPlayerId4' => 6, 'userteamPlayerId5' => 7, 'userteamPlayerId6' => 8, 'userteamPlayerId7' => 9, 'userteamPlayerId8' => 10, 'userteamPlayerId9' => 11, 'userteamPlayerId10' => 12, 'userteamPlayerId11' => 13, 'userteamPrice' => 14, 'userteamMatchroundId' => 15, 'userteamScore' => 16, 'userteamWcPoints' => 17, ),
		BasePeer::TYPE_COLNAME => array (self::USERTEAM_ID => 0, self::USERTEAM_USER_ID => 1, self::USERTEAM_DATE => 2, self::USERTEAM_PLAYER_ID1 => 3, self::USERTEAM_PLAYER_ID2 => 4, self::USERTEAM_PLAYER_ID3 => 5, self::USERTEAM_PLAYER_ID4 => 6, self::USERTEAM_PLAYER_ID5 => 7, self::USERTEAM_PLAYER_ID6 => 8, self::USERTEAM_PLAYER_ID7 => 9, self::USERTEAM_PLAYER_ID8 => 10, self::USERTEAM_PLAYER_ID9 => 11, self::USERTEAM_PLAYER_ID10 => 12, self::USERTEAM_PLAYER_ID11 => 13, self::USERTEAM_PRICE => 14, self::USERTEAM_MATCHROUND_ID => 15, self::USERTEAM_SCORE => 16, self::USERTEAM_WC_POINTS => 17, ),
		BasePeer::TYPE_RAW_COLNAME => array ('USERTEAM_ID' => 0, 'USERTEAM_USER_ID' => 1, 'USERTEAM_DATE' => 2, 'USERTEAM_PLAYER_ID1' => 3, 'USERTEAM_PLAYER_ID2' => 4, 'USERTEAM_PLAYER_ID3' => 5, 'USERTEAM_PLAYER_ID4' => 6, 'USERTEAM_PLAYER_ID5' => 7, 'USERTEAM_PLAYER_ID6' => 8, 'USERTEAM_PLAYER_ID7' => 9, 'USERTEAM_PLAYER_ID8' => 10, 'USERTEAM_PLAYER_ID9' => 11, 'USERTEAM_PLAYER_ID10' => 12, 'USERTEAM_PLAYER_ID11' => 13, 'USERTEAM_PRICE' => 14, 'USERTEAM_MATCHROUND_ID' => 15, 'USERTEAM_SCORE' => 16, 'USERTEAM_WC_POINTS' => 17, ),
		BasePeer::TYPE_FIELDNAME => array ('userteam_id' => 0, 'userteam_user_id' => 1, 'userteam_date' => 2, 'userteam_player_id1' => 3, 'userteam_player_id2' => 4, 'userteam_player_id3' => 5, 'userteam_player_id4' => 6, 'userteam_player_id5' => 7, 'userteam_player_id6' => 8, 'userteam_player_id7' => 9, 'userteam_player_id8' => 10, 'userteam_player_id9' => 11, 'userteam_player_id10' => 12, 'userteam_player_id11' => 13, 'userteam_price' => 14, 'userteam_matchround_id' => 15, 'userteam_score' => 16, 'userteam_wc_points' => 17, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
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
	 * @param      string $column The column name for current table. (i.e. FfbUserteamPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbUserteamPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      Criteria $criteria object containing the columns to add.
	 * @param      string   $alias    optional table alias
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria, $alias = null)
	{
		if (null === $alias) {
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_ID);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_USER_ID);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_DATE);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID1);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID2);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID3);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID4);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID5);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID6);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID7);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID8);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID9);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID10);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PLAYER_ID11);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_PRICE);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_MATCHROUND_ID);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_SCORE);
			$criteria->addSelectColumn(FfbUserteamPeer::USERTEAM_WC_POINTS);
		} else {
			$criteria->addSelectColumn($alias . '.USERTEAM_ID');
			$criteria->addSelectColumn($alias . '.USERTEAM_USER_ID');
			$criteria->addSelectColumn($alias . '.USERTEAM_DATE');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID1');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID2');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID3');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID4');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID5');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID6');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID7');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID8');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID9');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID10');
			$criteria->addSelectColumn($alias . '.USERTEAM_PLAYER_ID11');
			$criteria->addSelectColumn($alias . '.USERTEAM_PRICE');
			$criteria->addSelectColumn($alias . '.USERTEAM_MATCHROUND_ID');
			$criteria->addSelectColumn($alias . '.USERTEAM_SCORE');
			$criteria->addSelectColumn($alias . '.USERTEAM_WC_POINTS');
		}
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
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     FfbUserteam
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbUserteamPeer::doSelect($critcopy, $con);
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
		return FfbUserteamPeer::populateObjects(FfbUserteamPeer::doSelectStmt($criteria, $con));
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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbUserteamPeer::addSelectColumns($criteria);
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
	 * @param      FfbUserteam $value A FfbUserteam object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbUserteam $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getUserteamId();
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
	 * @param      mixed $value A FfbUserteam object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbUserteam) {
				$key = (string) $value->getUserteamId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbUserteam object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     FfbUserteam Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to ffb_userteam
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
	 * Retrieves the primary key from the DB resultset row 
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, an array of the primary key columns will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     mixed The primary key of the row
	 */
	public static function getPrimaryKeyFromRow($row, $startcol = 0)
	{
		return (int) $row[$startcol];
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
		$cls = FfbUserteamPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbUserteamPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbUserteamPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Populates an object of the default type or an object that inherit from the default.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     array (FfbUserteam object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = FfbUserteamPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + FfbUserteamPeer::NUM_COLUMNS;
		} else {
			$cls = FfbUserteamPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			FfbUserteamPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
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
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId1 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId2 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId3 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId4 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId4(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId5 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId5(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId6 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId6(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId7 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId7(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId8 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId8(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId9 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId9(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId10 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId10(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId11 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteamRelatedByUserteamPlayerId11(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbMatchround table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbMatchround(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Selects a collection of FfbUserteam objects pre-filled with their WebUser objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
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

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		WebUserPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId1($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId2($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId3($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId4(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId4($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId5(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId5($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId6(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId6($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId7(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId7($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId8(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId8($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId9(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId9($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId10(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId10($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteamRelatedByUserteamPlayerId11(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId11($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with their FfbMatchround objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbMatchround(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbMatchroundPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbMatchroundPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbMatchroundPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbMatchroundPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserteam) to $obj2 (FfbMatchround)
				$obj2->addFfbUserteam($obj1);

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
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
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

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol7 = $startcol6 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol8 = $startcol7 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol9 = $startcol8 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol10 = $startcol9 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol11 = $startcol10 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol12 = $startcol11 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol13 = $startcol12 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol14 = $startcol13 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol15 = $startcol14 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key3 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = FfbPlayerteamPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbPlayerteamPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbPlayerteam)
				$obj3->addFfbUserteamRelatedByUserteamPlayerId1($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key4 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = FfbPlayerteamPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbPlayerteamPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj4 (FfbPlayerteam)
				$obj4->addFfbUserteamRelatedByUserteamPlayerId2($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key5 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol5);
			if ($key5 !== null) {
				$obj5 = FfbPlayerteamPeer::getInstanceFromPool($key5);
				if (!$obj5) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbPlayerteamPeer::addInstanceToPool($obj5, $key5);
				} // if obj5 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj5 (FfbPlayerteam)
				$obj5->addFfbUserteamRelatedByUserteamPlayerId3($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key6 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol6);
			if ($key6 !== null) {
				$obj6 = FfbPlayerteamPeer::getInstanceFromPool($key6);
				if (!$obj6) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					FfbPlayerteamPeer::addInstanceToPool($obj6, $key6);
				} // if obj6 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj6 (FfbPlayerteam)
				$obj6->addFfbUserteamRelatedByUserteamPlayerId4($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key7 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol7);
			if ($key7 !== null) {
				$obj7 = FfbPlayerteamPeer::getInstanceFromPool($key7);
				if (!$obj7) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj7 = new $cls();
					$obj7->hydrate($row, $startcol7);
					FfbPlayerteamPeer::addInstanceToPool($obj7, $key7);
				} // if obj7 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj7 (FfbPlayerteam)
				$obj7->addFfbUserteamRelatedByUserteamPlayerId5($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key8 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol8);
			if ($key8 !== null) {
				$obj8 = FfbPlayerteamPeer::getInstanceFromPool($key8);
				if (!$obj8) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj8 = new $cls();
					$obj8->hydrate($row, $startcol8);
					FfbPlayerteamPeer::addInstanceToPool($obj8, $key8);
				} // if obj8 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj8 (FfbPlayerteam)
				$obj8->addFfbUserteamRelatedByUserteamPlayerId6($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key9 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol9);
			if ($key9 !== null) {
				$obj9 = FfbPlayerteamPeer::getInstanceFromPool($key9);
				if (!$obj9) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj9 = new $cls();
					$obj9->hydrate($row, $startcol9);
					FfbPlayerteamPeer::addInstanceToPool($obj9, $key9);
				} // if obj9 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj9 (FfbPlayerteam)
				$obj9->addFfbUserteamRelatedByUserteamPlayerId7($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key10 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol10);
			if ($key10 !== null) {
				$obj10 = FfbPlayerteamPeer::getInstanceFromPool($key10);
				if (!$obj10) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj10 = new $cls();
					$obj10->hydrate($row, $startcol10);
					FfbPlayerteamPeer::addInstanceToPool($obj10, $key10);
				} // if obj10 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj10 (FfbPlayerteam)
				$obj10->addFfbUserteamRelatedByUserteamPlayerId8($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key11 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol11);
			if ($key11 !== null) {
				$obj11 = FfbPlayerteamPeer::getInstanceFromPool($key11);
				if (!$obj11) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj11 = new $cls();
					$obj11->hydrate($row, $startcol11);
					FfbPlayerteamPeer::addInstanceToPool($obj11, $key11);
				} // if obj11 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj11 (FfbPlayerteam)
				$obj11->addFfbUserteamRelatedByUserteamPlayerId9($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key12 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol12);
			if ($key12 !== null) {
				$obj12 = FfbPlayerteamPeer::getInstanceFromPool($key12);
				if (!$obj12) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj12 = new $cls();
					$obj12->hydrate($row, $startcol12);
					FfbPlayerteamPeer::addInstanceToPool($obj12, $key12);
				} // if obj12 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj12 (FfbPlayerteam)
				$obj12->addFfbUserteamRelatedByUserteamPlayerId10($obj1);
			} // if joined row not null

			// Add objects for joined FfbPlayerteam rows

			$key13 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol13);
			if ($key13 !== null) {
				$obj13 = FfbPlayerteamPeer::getInstanceFromPool($key13);
				if (!$obj13) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj13 = new $cls();
					$obj13->hydrate($row, $startcol13);
					FfbPlayerteamPeer::addInstanceToPool($obj13, $key13);
				} // if obj13 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj13 (FfbPlayerteam)
				$obj13->addFfbUserteamRelatedByUserteamPlayerId11($obj1);
			} // if joined row not null

			// Add objects for joined FfbMatchround rows

			$key14 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol14);
			if ($key14 !== null) {
				$obj14 = FfbMatchroundPeer::getInstanceFromPool($key14);
				if (!$obj14) {

					$cls = FfbMatchroundPeer::getOMClass(false);

					$obj14 = new $cls();
					$obj14->hydrate($row, $startcol14);
					FfbMatchroundPeer::addInstanceToPool($obj14, $key14);
				} // if obj14 loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj14 (FfbMatchround)
				$obj14->addFfbUserteam($obj1);
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
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId1 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId2 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId3 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId4 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId4(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId5 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId5(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId6 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId6(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId7 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId7(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId8 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId8(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId9 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId9(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId10 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId10(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteamRelatedByUserteamPlayerId11 table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId11(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbMatchround table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbMatchround(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserteamPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

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
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except WebUser.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
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

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol7 = $startcol6 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol8 = $startcol7 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol9 = $startcol8 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol10 = $startcol9 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol11 = $startcol10 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol12 = $startcol11 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol13 = $startcol12 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol14 = $startcol13 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbPlayerteam rows

				$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (FfbPlayerteam)
				$obj2->addFfbUserteamRelatedByUserteamPlayerId1($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key3 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbPlayerteamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbPlayerteamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbPlayerteam)
				$obj3->addFfbUserteamRelatedByUserteamPlayerId2($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key4 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbPlayerteamPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbPlayerteamPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj4 (FfbPlayerteam)
				$obj4->addFfbUserteamRelatedByUserteamPlayerId3($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key5 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = FfbPlayerteamPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbPlayerteamPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj5 (FfbPlayerteam)
				$obj5->addFfbUserteamRelatedByUserteamPlayerId4($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key6 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol6);
				if ($key6 !== null) {
					$obj6 = FfbPlayerteamPeer::getInstanceFromPool($key6);
					if (!$obj6) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					FfbPlayerteamPeer::addInstanceToPool($obj6, $key6);
				} // if $obj6 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj6 (FfbPlayerteam)
				$obj6->addFfbUserteamRelatedByUserteamPlayerId5($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key7 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol7);
				if ($key7 !== null) {
					$obj7 = FfbPlayerteamPeer::getInstanceFromPool($key7);
					if (!$obj7) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj7 = new $cls();
					$obj7->hydrate($row, $startcol7);
					FfbPlayerteamPeer::addInstanceToPool($obj7, $key7);
				} // if $obj7 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj7 (FfbPlayerteam)
				$obj7->addFfbUserteamRelatedByUserteamPlayerId6($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key8 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol8);
				if ($key8 !== null) {
					$obj8 = FfbPlayerteamPeer::getInstanceFromPool($key8);
					if (!$obj8) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj8 = new $cls();
					$obj8->hydrate($row, $startcol8);
					FfbPlayerteamPeer::addInstanceToPool($obj8, $key8);
				} // if $obj8 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj8 (FfbPlayerteam)
				$obj8->addFfbUserteamRelatedByUserteamPlayerId7($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key9 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol9);
				if ($key9 !== null) {
					$obj9 = FfbPlayerteamPeer::getInstanceFromPool($key9);
					if (!$obj9) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj9 = new $cls();
					$obj9->hydrate($row, $startcol9);
					FfbPlayerteamPeer::addInstanceToPool($obj9, $key9);
				} // if $obj9 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj9 (FfbPlayerteam)
				$obj9->addFfbUserteamRelatedByUserteamPlayerId8($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key10 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol10);
				if ($key10 !== null) {
					$obj10 = FfbPlayerteamPeer::getInstanceFromPool($key10);
					if (!$obj10) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj10 = new $cls();
					$obj10->hydrate($row, $startcol10);
					FfbPlayerteamPeer::addInstanceToPool($obj10, $key10);
				} // if $obj10 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj10 (FfbPlayerteam)
				$obj10->addFfbUserteamRelatedByUserteamPlayerId9($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key11 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol11);
				if ($key11 !== null) {
					$obj11 = FfbPlayerteamPeer::getInstanceFromPool($key11);
					if (!$obj11) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj11 = new $cls();
					$obj11->hydrate($row, $startcol11);
					FfbPlayerteamPeer::addInstanceToPool($obj11, $key11);
				} // if $obj11 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj11 (FfbPlayerteam)
				$obj11->addFfbUserteamRelatedByUserteamPlayerId10($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key12 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol12);
				if ($key12 !== null) {
					$obj12 = FfbPlayerteamPeer::getInstanceFromPool($key12);
					if (!$obj12) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj12 = new $cls();
					$obj12->hydrate($row, $startcol12);
					FfbPlayerteamPeer::addInstanceToPool($obj12, $key12);
				} // if $obj12 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj12 (FfbPlayerteam)
				$obj12->addFfbUserteamRelatedByUserteamPlayerId11($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key13 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol13);
				if ($key13 !== null) {
					$obj13 = FfbMatchroundPeer::getInstanceFromPool($key13);
					if (!$obj13) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj13 = new $cls();
					$obj13->hydrate($row, $startcol13);
					FfbMatchroundPeer::addInstanceToPool($obj13, $key13);
				} // if $obj13 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj13 (FfbMatchround)
				$obj13->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId1.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId2.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId3.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId4.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId4(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId5.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId5(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId6.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId6(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId7.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId7(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId8.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId8(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId9.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId9(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId10.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId10(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbPlayerteamRelatedByUserteamPlayerId11.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteamRelatedByUserteamPlayerId11(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbUserteam($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbUserteam objects pre-filled with all related objects except FfbMatchround.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserteam objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbMatchround(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserteamPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserteamPeer::NUM_COLUMNS - FfbUserteamPeer::NUM_LAZY_LOAD_COLUMNS);

		WebUserPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (WebUserPeer::NUM_COLUMNS - WebUserPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol6 = $startcol5 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol7 = $startcol6 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol8 = $startcol7 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol9 = $startcol8 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol10 = $startcol9 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol11 = $startcol10 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol12 = $startcol11 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol13 = $startcol12 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol14 = $startcol13 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_USER_ID, WebUserPeer::USER_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID1, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID2, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID3, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID4, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID5, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID6, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID7, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID8, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID9, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID10, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbUserteamPeer::USERTEAM_PLAYER_ID11, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserteamPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserteamPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserteamPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserteamPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbUserteam) to the collection in $obj2 (WebUser)
				$obj2->addFfbUserteam($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key3 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbPlayerteamPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbPlayerteamPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj3 (FfbPlayerteam)
				$obj3->addFfbUserteamRelatedByUserteamPlayerId1($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key4 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol4);
				if ($key4 !== null) {
					$obj4 = FfbPlayerteamPeer::getInstanceFromPool($key4);
					if (!$obj4) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbPlayerteamPeer::addInstanceToPool($obj4, $key4);
				} // if $obj4 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj4 (FfbPlayerteam)
				$obj4->addFfbUserteamRelatedByUserteamPlayerId2($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key5 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol5);
				if ($key5 !== null) {
					$obj5 = FfbPlayerteamPeer::getInstanceFromPool($key5);
					if (!$obj5) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj5 = new $cls();
					$obj5->hydrate($row, $startcol5);
					FfbPlayerteamPeer::addInstanceToPool($obj5, $key5);
				} // if $obj5 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj5 (FfbPlayerteam)
				$obj5->addFfbUserteamRelatedByUserteamPlayerId3($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key6 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol6);
				if ($key6 !== null) {
					$obj6 = FfbPlayerteamPeer::getInstanceFromPool($key6);
					if (!$obj6) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj6 = new $cls();
					$obj6->hydrate($row, $startcol6);
					FfbPlayerteamPeer::addInstanceToPool($obj6, $key6);
				} // if $obj6 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj6 (FfbPlayerteam)
				$obj6->addFfbUserteamRelatedByUserteamPlayerId4($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key7 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol7);
				if ($key7 !== null) {
					$obj7 = FfbPlayerteamPeer::getInstanceFromPool($key7);
					if (!$obj7) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj7 = new $cls();
					$obj7->hydrate($row, $startcol7);
					FfbPlayerteamPeer::addInstanceToPool($obj7, $key7);
				} // if $obj7 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj7 (FfbPlayerteam)
				$obj7->addFfbUserteamRelatedByUserteamPlayerId5($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key8 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol8);
				if ($key8 !== null) {
					$obj8 = FfbPlayerteamPeer::getInstanceFromPool($key8);
					if (!$obj8) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj8 = new $cls();
					$obj8->hydrate($row, $startcol8);
					FfbPlayerteamPeer::addInstanceToPool($obj8, $key8);
				} // if $obj8 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj8 (FfbPlayerteam)
				$obj8->addFfbUserteamRelatedByUserteamPlayerId6($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key9 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol9);
				if ($key9 !== null) {
					$obj9 = FfbPlayerteamPeer::getInstanceFromPool($key9);
					if (!$obj9) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj9 = new $cls();
					$obj9->hydrate($row, $startcol9);
					FfbPlayerteamPeer::addInstanceToPool($obj9, $key9);
				} // if $obj9 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj9 (FfbPlayerteam)
				$obj9->addFfbUserteamRelatedByUserteamPlayerId7($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key10 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol10);
				if ($key10 !== null) {
					$obj10 = FfbPlayerteamPeer::getInstanceFromPool($key10);
					if (!$obj10) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj10 = new $cls();
					$obj10->hydrate($row, $startcol10);
					FfbPlayerteamPeer::addInstanceToPool($obj10, $key10);
				} // if $obj10 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj10 (FfbPlayerteam)
				$obj10->addFfbUserteamRelatedByUserteamPlayerId8($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key11 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol11);
				if ($key11 !== null) {
					$obj11 = FfbPlayerteamPeer::getInstanceFromPool($key11);
					if (!$obj11) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj11 = new $cls();
					$obj11->hydrate($row, $startcol11);
					FfbPlayerteamPeer::addInstanceToPool($obj11, $key11);
				} // if $obj11 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj11 (FfbPlayerteam)
				$obj11->addFfbUserteamRelatedByUserteamPlayerId9($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key12 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol12);
				if ($key12 !== null) {
					$obj12 = FfbPlayerteamPeer::getInstanceFromPool($key12);
					if (!$obj12) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj12 = new $cls();
					$obj12->hydrate($row, $startcol12);
					FfbPlayerteamPeer::addInstanceToPool($obj12, $key12);
				} // if $obj12 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj12 (FfbPlayerteam)
				$obj12->addFfbUserteamRelatedByUserteamPlayerId10($obj1);

			} // if joined row is not null

				// Add objects for joined FfbPlayerteam rows

				$key13 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol13);
				if ($key13 !== null) {
					$obj13 = FfbPlayerteamPeer::getInstanceFromPool($key13);
					if (!$obj13) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj13 = new $cls();
					$obj13->hydrate($row, $startcol13);
					FfbPlayerteamPeer::addInstanceToPool($obj13, $key13);
				} // if $obj13 already loaded

				// Add the $obj1 (FfbUserteam) to the collection in $obj13 (FfbPlayerteam)
				$obj13->addFfbUserteamRelatedByUserteamPlayerId11($obj1);

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
	  $dbMap = Propel::getDatabaseMap(BaseFfbUserteamPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbUserteamPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbUserteamTableMap());
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
	 * @param      boolean $withPrefix Whether or not to return the path with the class name
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? FfbUserteamPeer::CLASS_DEFAULT : FfbUserteamPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbUserteam or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbUserteam object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbUserteam object
		}

		if ($criteria->containsKey(FfbUserteamPeer::USERTEAM_ID) && $criteria->keyContainsValue(FfbUserteamPeer::USERTEAM_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbUserteamPeer::USERTEAM_ID.')');
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
	 * Method perform an UPDATE on the database, given a FfbUserteam or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbUserteam object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbUserteamPeer::USERTEAM_ID);
			$value = $criteria->remove(FfbUserteamPeer::USERTEAM_ID);
			if ($value) {
				$selectCriteria->add(FfbUserteamPeer::USERTEAM_ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(FfbUserteamPeer::TABLE_NAME);
			}

		} else { // $values is FfbUserteam object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_userteam table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(FfbUserteamPeer::TABLE_NAME, $con, FfbUserteamPeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbUserteamPeer::clearInstancePool();
			FfbUserteamPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbUserteam or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbUserteam object or primary key or array of primary keys
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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			FfbUserteamPeer::clearInstancePool();
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbUserteam) { // it's a model object
			// invalidate the cache for this single object
			FfbUserteamPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbUserteamPeer::USERTEAM_ID, (array) $values, Criteria::IN);
			// invalidate the cache for this object(s)
			foreach ((array) $values as $singleval) {
				FfbUserteamPeer::removeInstanceFromPool($singleval);
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
			FfbUserteamPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given FfbUserteam object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbUserteam $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbUserteam $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbUserteamPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbUserteamPeer::TABLE_NAME);

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

		return BasePeer::doValidate(FfbUserteamPeer::DATABASE_NAME, FfbUserteamPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbUserteam
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = FfbUserteamPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbUserteamPeer::DATABASE_NAME);
		$criteria->add(FfbUserteamPeer::USERTEAM_ID, $pk);

		$v = FfbUserteamPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(FfbUserteamPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbUserteamPeer::DATABASE_NAME);
			$criteria->add(FfbUserteamPeer::USERTEAM_ID, $pks, Criteria::IN);
			$objs = FfbUserteamPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbUserteamPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbUserteamPeer::buildTableMap();

