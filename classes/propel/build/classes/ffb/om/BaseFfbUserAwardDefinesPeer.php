<?php


/**
 * Base static class for performing query and update operations on the 'ffb_user_award_defines' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserAwardDefinesPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_user_award_defines';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbUserAwardDefines';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbUserAwardDefines';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbUserAwardDefinesTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 12;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the USER_AWARD_DEFINES_ID field */
	const USER_AWARD_DEFINES_ID = 'ffb_user_award_defines.USER_AWARD_DEFINES_ID';

	/** the column name for the USER_AWARD_DEFINES_AWARD_ID field */
	const USER_AWARD_DEFINES_AWARD_ID = 'ffb_user_award_defines.USER_AWARD_DEFINES_AWARD_ID';

	/** the column name for the USER_AWARD_DEFINES_RANK field */
	const USER_AWARD_DEFINES_RANK = 'ffb_user_award_defines.USER_AWARD_DEFINES_RANK';

	/** the column name for the USER_AWARD_DEFINES_RANK_NAME field */
	const USER_AWARD_DEFINES_RANK_NAME = 'ffb_user_award_defines.USER_AWARD_DEFINES_RANK_NAME';

	/** the column name for the USER_AWARD_DEFINES_AIM field */
	const USER_AWARD_DEFINES_AIM = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM';

	/** the column name for the USER_AWARD_DEFINES_AIM_DBTABLE field */
	const USER_AWARD_DEFINES_AIM_DBTABLE = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM_DBTABLE';

	/** the column name for the USER_AWARD_DEFINES_AIM_OPERATOR field */
	const USER_AWARD_DEFINES_AIM_OPERATOR = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM_OPERATOR';

	/** the column name for the USER_AWARD_DEFINES_AIM_COUNT field */
	const USER_AWARD_DEFINES_AIM_COUNT = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM_COUNT';

	/** the column name for the USER_AWARD_DEFINES_AIM_AUTOMATIC field */
	const USER_AWARD_DEFINES_AIM_AUTOMATIC = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM_AUTOMATIC';

	/** the column name for the USER_AWARD_DEFINES_AIM_FUNCTION_NAME field */
	const USER_AWARD_DEFINES_AIM_FUNCTION_NAME = 'ffb_user_award_defines.USER_AWARD_DEFINES_AIM_FUNCTION_NAME';

	/** the column name for the USER_AWARD_DEFINES_IMAGE field */
	const USER_AWARD_DEFINES_IMAGE = 'ffb_user_award_defines.USER_AWARD_DEFINES_IMAGE';

	/** the column name for the USER_AWARD_DEFINES_DESCRIPTION field */
	const USER_AWARD_DEFINES_DESCRIPTION = 'ffb_user_award_defines.USER_AWARD_DEFINES_DESCRIPTION';

	/**
	 * An identiy map to hold any loaded instances of FfbUserAwardDefines objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbUserAwardDefines[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('UserAwardDefinesId', 'UserAwardDefinesAwardId', 'UserAwardDefinesRank', 'UserAwardDefinesRankName', 'UserAwardDefinesAim', 'UserAwardDefinesAimDbtable', 'UserAwardDefinesAimOperator', 'UserAwardDefinesAimCount', 'UserAwardDefinesAimAutomatic', 'UserAwardDefinesAimFunctionName', 'UserAwardDefinesImage', 'UserAwardDefinesDescription', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userAwardDefinesId', 'userAwardDefinesAwardId', 'userAwardDefinesRank', 'userAwardDefinesRankName', 'userAwardDefinesAim', 'userAwardDefinesAimDbtable', 'userAwardDefinesAimOperator', 'userAwardDefinesAimCount', 'userAwardDefinesAimAutomatic', 'userAwardDefinesAimFunctionName', 'userAwardDefinesImage', 'userAwardDefinesDescription', ),
		BasePeer::TYPE_COLNAME => array (self::USER_AWARD_DEFINES_ID, self::USER_AWARD_DEFINES_AWARD_ID, self::USER_AWARD_DEFINES_RANK, self::USER_AWARD_DEFINES_RANK_NAME, self::USER_AWARD_DEFINES_AIM, self::USER_AWARD_DEFINES_AIM_DBTABLE, self::USER_AWARD_DEFINES_AIM_OPERATOR, self::USER_AWARD_DEFINES_AIM_COUNT, self::USER_AWARD_DEFINES_AIM_AUTOMATIC, self::USER_AWARD_DEFINES_AIM_FUNCTION_NAME, self::USER_AWARD_DEFINES_IMAGE, self::USER_AWARD_DEFINES_DESCRIPTION, ),
		BasePeer::TYPE_RAW_COLNAME => array ('USER_AWARD_DEFINES_ID', 'USER_AWARD_DEFINES_AWARD_ID', 'USER_AWARD_DEFINES_RANK', 'USER_AWARD_DEFINES_RANK_NAME', 'USER_AWARD_DEFINES_AIM', 'USER_AWARD_DEFINES_AIM_DBTABLE', 'USER_AWARD_DEFINES_AIM_OPERATOR', 'USER_AWARD_DEFINES_AIM_COUNT', 'USER_AWARD_DEFINES_AIM_AUTOMATIC', 'USER_AWARD_DEFINES_AIM_FUNCTION_NAME', 'USER_AWARD_DEFINES_IMAGE', 'USER_AWARD_DEFINES_DESCRIPTION', ),
		BasePeer::TYPE_FIELDNAME => array ('user_award_defines_id', 'user_award_defines_award_id', 'user_award_defines_rank', 'user_award_defines_rank_name', 'user_award_defines_aim', 'user_award_defines_aim_dbtable', 'user_award_defines_aim_operator', 'user_award_defines_aim_count', 'user_award_defines_aim_automatic', 'user_award_defines_aim_function_name', 'user_award_defines_image', 'user_award_defines_description', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('UserAwardDefinesId' => 0, 'UserAwardDefinesAwardId' => 1, 'UserAwardDefinesRank' => 2, 'UserAwardDefinesRankName' => 3, 'UserAwardDefinesAim' => 4, 'UserAwardDefinesAimDbtable' => 5, 'UserAwardDefinesAimOperator' => 6, 'UserAwardDefinesAimCount' => 7, 'UserAwardDefinesAimAutomatic' => 8, 'UserAwardDefinesAimFunctionName' => 9, 'UserAwardDefinesImage' => 10, 'UserAwardDefinesDescription' => 11, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('userAwardDefinesId' => 0, 'userAwardDefinesAwardId' => 1, 'userAwardDefinesRank' => 2, 'userAwardDefinesRankName' => 3, 'userAwardDefinesAim' => 4, 'userAwardDefinesAimDbtable' => 5, 'userAwardDefinesAimOperator' => 6, 'userAwardDefinesAimCount' => 7, 'userAwardDefinesAimAutomatic' => 8, 'userAwardDefinesAimFunctionName' => 9, 'userAwardDefinesImage' => 10, 'userAwardDefinesDescription' => 11, ),
		BasePeer::TYPE_COLNAME => array (self::USER_AWARD_DEFINES_ID => 0, self::USER_AWARD_DEFINES_AWARD_ID => 1, self::USER_AWARD_DEFINES_RANK => 2, self::USER_AWARD_DEFINES_RANK_NAME => 3, self::USER_AWARD_DEFINES_AIM => 4, self::USER_AWARD_DEFINES_AIM_DBTABLE => 5, self::USER_AWARD_DEFINES_AIM_OPERATOR => 6, self::USER_AWARD_DEFINES_AIM_COUNT => 7, self::USER_AWARD_DEFINES_AIM_AUTOMATIC => 8, self::USER_AWARD_DEFINES_AIM_FUNCTION_NAME => 9, self::USER_AWARD_DEFINES_IMAGE => 10, self::USER_AWARD_DEFINES_DESCRIPTION => 11, ),
		BasePeer::TYPE_RAW_COLNAME => array ('USER_AWARD_DEFINES_ID' => 0, 'USER_AWARD_DEFINES_AWARD_ID' => 1, 'USER_AWARD_DEFINES_RANK' => 2, 'USER_AWARD_DEFINES_RANK_NAME' => 3, 'USER_AWARD_DEFINES_AIM' => 4, 'USER_AWARD_DEFINES_AIM_DBTABLE' => 5, 'USER_AWARD_DEFINES_AIM_OPERATOR' => 6, 'USER_AWARD_DEFINES_AIM_COUNT' => 7, 'USER_AWARD_DEFINES_AIM_AUTOMATIC' => 8, 'USER_AWARD_DEFINES_AIM_FUNCTION_NAME' => 9, 'USER_AWARD_DEFINES_IMAGE' => 10, 'USER_AWARD_DEFINES_DESCRIPTION' => 11, ),
		BasePeer::TYPE_FIELDNAME => array ('user_award_defines_id' => 0, 'user_award_defines_award_id' => 1, 'user_award_defines_rank' => 2, 'user_award_defines_rank_name' => 3, 'user_award_defines_aim' => 4, 'user_award_defines_aim_dbtable' => 5, 'user_award_defines_aim_operator' => 6, 'user_award_defines_aim_count' => 7, 'user_award_defines_aim_automatic' => 8, 'user_award_defines_aim_function_name' => 9, 'user_award_defines_image' => 10, 'user_award_defines_description' => 11, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
	 * @param      string $column The column name for current table. (i.e. FfbUserAwardDefinesPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbUserAwardDefinesPeer::TABLE_NAME.'.', $alias.'.', $column);
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
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_DBTABLE);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_OPERATOR);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_AUTOMATIC);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_FUNCTION_NAME);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_IMAGE);
			$criteria->addSelectColumn(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_DESCRIPTION);
		} else {
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_ID');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AWARD_ID');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_RANK');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_RANK_NAME');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM_DBTABLE');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM_OPERATOR');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM_COUNT');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM_AUTOMATIC');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_AIM_FUNCTION_NAME');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_IMAGE');
			$criteria->addSelectColumn($alias . '.USER_AWARD_DEFINES_DESCRIPTION');
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
	public static function doCount(Criteria $criteria, $distinct = false, ?PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserAwardDefinesPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserAwardDefinesPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     FfbUserAwardDefines
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, ?PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbUserAwardDefinesPeer::doSelect($critcopy, $con);
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
	public static function doSelect(Criteria $criteria, ?PropelPDO $con = null)
	{
		return FfbUserAwardDefinesPeer::populateObjects(FfbUserAwardDefinesPeer::doSelectStmt($criteria, $con));
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
	public static function doSelectStmt(Criteria $criteria, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbUserAwardDefinesPeer::addSelectColumns($criteria);
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
	 * @param      FfbUserAwardDefines $value A FfbUserAwardDefines object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbUserAwardDefines $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getUserAwardDefinesId();
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
	 * @param      mixed $value A FfbUserAwardDefines object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbUserAwardDefines) {
				$key = (string) $value->getUserAwardDefinesId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbUserAwardDefines object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     FfbUserAwardDefines Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to ffb_user_award_defines
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
		// Invalidate objects in FfbUserAwardFinishedPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbUserAwardFinishedPeer::clearInstancePool();
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
		$cls = FfbUserAwardDefinesPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbUserAwardDefinesPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbUserAwardDefinesPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbUserAwardDefinesPeer::addInstanceToPool($obj, $key);
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
	 * @return     array (FfbUserAwardDefines object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = FfbUserAwardDefinesPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = FfbUserAwardDefinesPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + FfbUserAwardDefinesPeer::NUM_COLUMNS;
		} else {
			$cls = FfbUserAwardDefinesPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			FfbUserAwardDefinesPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
	}

	/**
	 * Returns the number of rows matching criteria, joining the related FfbUserAward table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbUserAward(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserAwardDefinesPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserAwardDefinesPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, FfbUserAwardPeer::USER_AWARD_ID, $join_behavior);

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
	 * Selects a collection of FfbUserAwardDefines objects pre-filled with their FfbUserAward objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserAwardDefines objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbUserAward(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbUserAwardDefinesPeer::addSelectColumns($criteria);
		$startcol = (FfbUserAwardDefinesPeer::NUM_COLUMNS - FfbUserAwardDefinesPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbUserAwardPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, FfbUserAwardPeer::USER_AWARD_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserAwardDefinesPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserAwardDefinesPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbUserAwardDefinesPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserAwardDefinesPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbUserAwardPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbUserAwardPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbUserAwardPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbUserAwardPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbUserAwardDefines) to $obj2 (FfbUserAward)
				$obj2->addFfbUserAwardDefines($obj1);

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
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbUserAwardDefinesPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbUserAwardDefinesPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, FfbUserAwardPeer::USER_AWARD_ID, $join_behavior);

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
	 * Selects a collection of FfbUserAwardDefines objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbUserAwardDefines objects.
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

		FfbUserAwardDefinesPeer::addSelectColumns($criteria);
		$startcol2 = (FfbUserAwardDefinesPeer::NUM_COLUMNS - FfbUserAwardDefinesPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbUserAwardPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbUserAwardPeer::NUM_COLUMNS - FfbUserAwardPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, FfbUserAwardPeer::USER_AWARD_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbUserAwardDefinesPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbUserAwardDefinesPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbUserAwardDefinesPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbUserAwardDefinesPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined FfbUserAward rows

			$key2 = FfbUserAwardPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = FfbUserAwardPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbUserAwardPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbUserAwardPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (FfbUserAwardDefines) to the collection in $obj2 (FfbUserAward)
				$obj2->addFfbUserAwardDefines($obj1);
			} // if joined row not null

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
	  $dbMap = Propel::getDatabaseMap(BaseFfbUserAwardDefinesPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbUserAwardDefinesPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbUserAwardDefinesTableMap());
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
		return $withPrefix ? FfbUserAwardDefinesPeer::CLASS_DEFAULT : FfbUserAwardDefinesPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbUserAwardDefines or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbUserAwardDefines object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbUserAwardDefines object
		}

		if ($criteria->containsKey(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID) && $criteria->keyContainsValue(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID.')');
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
	 * Method perform an UPDATE on the database, given a FfbUserAwardDefines or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbUserAwardDefines object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID);
			$value = $criteria->remove(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID);
			if ($value) {
				$selectCriteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(FfbUserAwardDefinesPeer::TABLE_NAME);
			}

		} else { // $values is FfbUserAwardDefines object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_user_award_defines table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += FfbUserAwardDefinesPeer::doOnDeleteCascade(new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME), $con);
			$affectedRows += BasePeer::doDeleteAll(FfbUserAwardDefinesPeer::TABLE_NAME, $con, FfbUserAwardDefinesPeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbUserAwardDefinesPeer::clearInstancePool();
			FfbUserAwardDefinesPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbUserAwardDefines or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbUserAwardDefines object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, ?PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbUserAwardDefines) { // it's a model object
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, (array) $values, Criteria::IN);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			// cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
			$c = clone $criteria;
			$affectedRows += FfbUserAwardDefinesPeer::doOnDeleteCascade($c, $con);
			
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			if ($values instanceof Criteria) {
				FfbUserAwardDefinesPeer::clearInstancePool();
			} elseif ($values instanceof FfbUserAwardDefines) { // it's a model object
				FfbUserAwardDefinesPeer::removeInstanceFromPool($values);
			} else { // it's a primary key, or an array of pks
				foreach ((array) $values as $singleval) {
					FfbUserAwardDefinesPeer::removeInstanceFromPool($singleval);
				}
			}
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			FfbUserAwardDefinesPeer::clearRelatedInstancePool();
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
		$objects = FfbUserAwardDefinesPeer::doSelect($criteria, $con);
		foreach ($objects as $obj) {


			// delete related FfbUserAwardFinished objects
			$criteria = new Criteria(FfbUserAwardFinishedPeer::DATABASE_NAME);
			
			$criteria->add(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $obj->getUserAwardDefinesId());
			$affectedRows += FfbUserAwardFinishedPeer::doDelete($criteria, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given FfbUserAwardDefines object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbUserAwardDefines $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbUserAwardDefines $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbUserAwardDefinesPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbUserAwardDefinesPeer::TABLE_NAME);

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

		return BasePeer::doValidate(FfbUserAwardDefinesPeer::DATABASE_NAME, FfbUserAwardDefinesPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbUserAwardDefines
	 */
	public static function retrieveByPK($pk, ?PropelPDO $con = null)
	{

		if (null !== ($obj = FfbUserAwardDefinesPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);
		$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $pk);

		$v = FfbUserAwardDefinesPeer::doSelect($criteria, $con);

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
	public static function retrieveByPKs($pks, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbUserAwardDefinesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbUserAwardDefinesPeer::DATABASE_NAME);
			$criteria->add(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $pks, Criteria::IN);
			$objs = FfbUserAwardDefinesPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbUserAwardDefinesPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbUserAwardDefinesPeer::buildTableMap();

