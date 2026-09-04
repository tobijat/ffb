<?php


/**
 * Base static class for performing query and update operations on the 'ffb_game' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbGamePeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_game';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbGame';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbGame';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbGameTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 8;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the GAME_ID field */
	const GAME_ID = 'ffb_game.GAME_ID';

	/** the column name for the GAME_TITLE field */
	const GAME_TITLE = 'ffb_game.GAME_TITLE';

	/** the column name for the GAME_VISIBLE field */
	const GAME_VISIBLE = 'ffb_game.GAME_VISIBLE';

	/** the column name for the GAME_ARCHIVE field */
	const GAME_ARCHIVE = 'ffb_game.GAME_ARCHIVE';

	/** the column name for the GAME_COUNTDOWN field */
	const GAME_COUNTDOWN = 'ffb_game.GAME_COUNTDOWN';

	/** the column name for the GAME_STATUS field */
	const GAME_STATUS = 'ffb_game.GAME_STATUS';

	/** the column name for the GAME_DESCRIPTION field */
	const GAME_DESCRIPTION = 'ffb_game.GAME_DESCRIPTION';

	/** the column name for the GAME_SYMBOL field */
	const GAME_SYMBOL = 'ffb_game.GAME_SYMBOL';

	/**
	 * An identiy map to hold any loaded instances of FfbGame objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbGame[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('GameId', 'GameTitle', 'GameVisible', 'GameArchive', 'GameCountdown', 'GameStatus', 'GameDescription', 'GameSymbol', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('gameId', 'gameTitle', 'gameVisible', 'gameArchive', 'gameCountdown', 'gameStatus', 'gameDescription', 'gameSymbol', ),
		BasePeer::TYPE_COLNAME => array (self::GAME_ID, self::GAME_TITLE, self::GAME_VISIBLE, self::GAME_ARCHIVE, self::GAME_COUNTDOWN, self::GAME_STATUS, self::GAME_DESCRIPTION, self::GAME_SYMBOL, ),
		BasePeer::TYPE_RAW_COLNAME => array ('GAME_ID', 'GAME_TITLE', 'GAME_VISIBLE', 'GAME_ARCHIVE', 'GAME_COUNTDOWN', 'GAME_STATUS', 'GAME_DESCRIPTION', 'GAME_SYMBOL', ),
		BasePeer::TYPE_FIELDNAME => array ('game_id', 'game_title', 'game_visible', 'game_archive', 'game_countdown', 'game_status', 'game_description', 'game_symbol', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('GameId' => 0, 'GameTitle' => 1, 'GameVisible' => 2, 'GameArchive' => 3, 'GameCountdown' => 4, 'GameStatus' => 5, 'GameDescription' => 6, 'GameSymbol' => 7, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('gameId' => 0, 'gameTitle' => 1, 'gameVisible' => 2, 'gameArchive' => 3, 'gameCountdown' => 4, 'gameStatus' => 5, 'gameDescription' => 6, 'gameSymbol' => 7, ),
		BasePeer::TYPE_COLNAME => array (self::GAME_ID => 0, self::GAME_TITLE => 1, self::GAME_VISIBLE => 2, self::GAME_ARCHIVE => 3, self::GAME_COUNTDOWN => 4, self::GAME_STATUS => 5, self::GAME_DESCRIPTION => 6, self::GAME_SYMBOL => 7, ),
		BasePeer::TYPE_RAW_COLNAME => array ('GAME_ID' => 0, 'GAME_TITLE' => 1, 'GAME_VISIBLE' => 2, 'GAME_ARCHIVE' => 3, 'GAME_COUNTDOWN' => 4, 'GAME_STATUS' => 5, 'GAME_DESCRIPTION' => 6, 'GAME_SYMBOL' => 7, ),
		BasePeer::TYPE_FIELDNAME => array ('game_id' => 0, 'game_title' => 1, 'game_visible' => 2, 'game_archive' => 3, 'game_countdown' => 4, 'game_status' => 5, 'game_description' => 6, 'game_symbol' => 7, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, )
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
	 * @param      string $column The column name for current table. (i.e. FfbGamePeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbGamePeer::TABLE_NAME.'.', $alias.'.', $column);
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
			$criteria->addSelectColumn(FfbGamePeer::GAME_ID);
			$criteria->addSelectColumn(FfbGamePeer::GAME_TITLE);
			$criteria->addSelectColumn(FfbGamePeer::GAME_VISIBLE);
			$criteria->addSelectColumn(FfbGamePeer::GAME_ARCHIVE);
			$criteria->addSelectColumn(FfbGamePeer::GAME_COUNTDOWN);
			$criteria->addSelectColumn(FfbGamePeer::GAME_STATUS);
			$criteria->addSelectColumn(FfbGamePeer::GAME_DESCRIPTION);
			$criteria->addSelectColumn(FfbGamePeer::GAME_SYMBOL);
		} else {
			$criteria->addSelectColumn($alias . '.GAME_ID');
			$criteria->addSelectColumn($alias . '.GAME_TITLE');
			$criteria->addSelectColumn($alias . '.GAME_VISIBLE');
			$criteria->addSelectColumn($alias . '.GAME_ARCHIVE');
			$criteria->addSelectColumn($alias . '.GAME_COUNTDOWN');
			$criteria->addSelectColumn($alias . '.GAME_STATUS');
			$criteria->addSelectColumn($alias . '.GAME_DESCRIPTION');
			$criteria->addSelectColumn($alias . '.GAME_SYMBOL');
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
		$criteria->setPrimaryTableName(FfbGamePeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbGamePeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     FfbGame
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, ?PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbGamePeer::doSelect($critcopy, $con);
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
		return FfbGamePeer::populateObjects(FfbGamePeer::doSelectStmt($criteria, $con));
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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbGamePeer::addSelectColumns($criteria);
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
	 * @param      FfbGame $value A FfbGame object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbGame $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getGameId();
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
	 * @param      mixed $value A FfbGame object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbGame) {
				$key = (string) $value->getGameId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbGame object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     FfbGame Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to ffb_game
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
		// Invalidate objects in FfbCommentsPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbCommentsPeer::clearInstancePool();
		// Invalidate objects in FfbMatchroundPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbMatchroundPeer::clearInstancePool();
		// Invalidate objects in FfbNewsPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbNewsPeer::clearInstancePool();
		// Invalidate objects in FfbUserscorePeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbUserscorePeer::clearInstancePool();
		// Invalidate objects in FfbAdminPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbAdminPeer::clearInstancePool();
		// Invalidate objects in FfbOptionsPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbOptionsPeer::clearInstancePool();
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
		$cls = FfbGamePeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbGamePeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbGamePeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbGamePeer::addInstanceToPool($obj, $key);
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
	 * @return     array (FfbGame object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = FfbGamePeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = FfbGamePeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + FfbGamePeer::NUM_COLUMNS;
		} else {
			$cls = FfbGamePeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			FfbGamePeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
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
	  $dbMap = Propel::getDatabaseMap(BaseFfbGamePeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbGamePeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbGameTableMap());
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
		return $withPrefix ? FfbGamePeer::CLASS_DEFAULT : FfbGamePeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbGame or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbGame object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbGame object
		}

		if ($criteria->containsKey(FfbGamePeer::GAME_ID) && $criteria->keyContainsValue(FfbGamePeer::GAME_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbGamePeer::GAME_ID.')');
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
	 * Method perform an UPDATE on the database, given a FfbGame or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbGame object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbGamePeer::GAME_ID);
			$value = $criteria->remove(FfbGamePeer::GAME_ID);
			if ($value) {
				$selectCriteria->add(FfbGamePeer::GAME_ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(FfbGamePeer::TABLE_NAME);
			}

		} else { // $values is FfbGame object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_game table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += FfbGamePeer::doOnDeleteCascade(new Criteria(FfbGamePeer::DATABASE_NAME), $con);
			$affectedRows += BasePeer::doDeleteAll(FfbGamePeer::TABLE_NAME, $con, FfbGamePeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbGamePeer::clearInstancePool();
			FfbGamePeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbGame or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbGame object or primary key or array of primary keys
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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbGame) { // it's a model object
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbGamePeer::GAME_ID, (array) $values, Criteria::IN);
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
			$affectedRows += FfbGamePeer::doOnDeleteCascade($c, $con);
			
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			if ($values instanceof Criteria) {
				FfbGamePeer::clearInstancePool();
			} elseif ($values instanceof FfbGame) { // it's a model object
				FfbGamePeer::removeInstanceFromPool($values);
			} else { // it's a primary key, or an array of pks
				foreach ((array) $values as $singleval) {
					FfbGamePeer::removeInstanceFromPool($singleval);
				}
			}
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			FfbGamePeer::clearRelatedInstancePool();
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
		$objects = FfbGamePeer::doSelect($criteria, $con);
		foreach ($objects as $obj) {


			// delete related FfbComments objects
			$criteria = new Criteria(FfbCommentsPeer::DATABASE_NAME);
			
			$criteria->add(FfbCommentsPeer::COMMENTS_GAME_ID, $obj->getGameId());
			$affectedRows += FfbCommentsPeer::doDelete($criteria, $con);

			// delete related FfbMatchround objects
			$criteria = new Criteria(FfbMatchroundPeer::DATABASE_NAME);
			
			$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $obj->getGameId());
			$affectedRows += FfbMatchroundPeer::doDelete($criteria, $con);

			// delete related FfbNews objects
			$criteria = new Criteria(FfbNewsPeer::DATABASE_NAME);
			
			$criteria->add(FfbNewsPeer::NEWS_GAME_ID, $obj->getGameId());
			$affectedRows += FfbNewsPeer::doDelete($criteria, $con);

			// delete related FfbUserscore objects
			$criteria = new Criteria(FfbUserscorePeer::DATABASE_NAME);
			
			$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $obj->getGameId());
			$affectedRows += FfbUserscorePeer::doDelete($criteria, $con);

			// delete related FfbAdmin objects
			$criteria = new Criteria(FfbAdminPeer::DATABASE_NAME);
			
			$criteria->add(FfbAdminPeer::ADMIN_GAME_ID, $obj->getGameId());
			$affectedRows += FfbAdminPeer::doDelete($criteria, $con);

			// delete related FfbOptions objects
			$criteria = new Criteria(FfbOptionsPeer::DATABASE_NAME);
			
			$criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $obj->getGameId());
			$affectedRows += FfbOptionsPeer::doDelete($criteria, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given FfbGame object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbGame $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbGame $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbGamePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbGamePeer::TABLE_NAME);

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

		return BasePeer::doValidate(FfbGamePeer::DATABASE_NAME, FfbGamePeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbGame
	 */
	public static function retrieveByPK($pk, ?PropelPDO $con = null)
	{

		if (null !== ($obj = FfbGamePeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
		$criteria->add(FfbGamePeer::GAME_ID, $pk);

		$v = FfbGamePeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(FfbGamePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbGamePeer::DATABASE_NAME);
			$criteria->add(FfbGamePeer::GAME_ID, $pks, Criteria::IN);
			$objs = FfbGamePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbGamePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbGamePeer::buildTableMap();

