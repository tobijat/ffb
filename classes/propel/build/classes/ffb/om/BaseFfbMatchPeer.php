<?php


/**
 * Base static class for performing query and update operations on the 'ffb_match' table.
 *
 * 
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbMatchPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_match';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbMatch';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbMatch';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbMatchTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 12;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the MATCH_ID field */
	const MATCH_ID = 'ffb_match.MATCH_ID';

	/** the column name for the MATCH_ROUND field */
	const MATCH_ROUND = 'ffb_match.MATCH_ROUND';

	/** the column name for the MATCH_HOMETEAM_ID field */
	const MATCH_HOMETEAM_ID = 'ffb_match.MATCH_HOMETEAM_ID';

	/** the column name for the MATCH_GUESTTEAM_ID field */
	const MATCH_GUESTTEAM_ID = 'ffb_match.MATCH_GUESTTEAM_ID';

	/** the column name for the MATCH_HOMESCORE field */
	const MATCH_HOMESCORE = 'ffb_match.MATCH_HOMESCORE';

	/** the column name for the MATCH_GUESTSCORE field */
	const MATCH_GUESTSCORE = 'ffb_match.MATCH_GUESTSCORE';

	/** the column name for the MATCH_HOMESCORE_PENALTY field */
	const MATCH_HOMESCORE_PENALTY = 'ffb_match.MATCH_HOMESCORE_PENALTY';

	/** the column name for the MATCH_GUESTSCORE_PENALTY field */
	const MATCH_GUESTSCORE_PENALTY = 'ffb_match.MATCH_GUESTSCORE_PENALTY';

	/** the column name for the MATCH_DATE field */
	const MATCH_DATE = 'ffb_match.MATCH_DATE';

	/** the column name for the MATCH_MINUTES field */
	const MATCH_MINUTES = 'ffb_match.MATCH_MINUTES';

	/** the column name for the MATCH_STATUS field */
	const MATCH_STATUS = 'ffb_match.MATCH_STATUS';

	/** the column name for the MATCH_URL field */
	const MATCH_URL = 'ffb_match.MATCH_URL';

	/**
	 * An identiy map to hold any loaded instances of FfbMatch objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbMatch[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('MatchId', 'MatchRound', 'MatchHometeamId', 'MatchGuestteamId', 'MatchHomescore', 'MatchGuestscore', 'MatchHomescorePenalty', 'MatchGuestscorePenalty', 'MatchDate', 'MatchMinutes', 'MatchStatus', 'MatchUrl', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('matchId', 'matchRound', 'matchHometeamId', 'matchGuestteamId', 'matchHomescore', 'matchGuestscore', 'matchHomescorePenalty', 'matchGuestscorePenalty', 'matchDate', 'matchMinutes', 'matchStatus', 'matchUrl', ),
		BasePeer::TYPE_COLNAME => array (self::MATCH_ID, self::MATCH_ROUND, self::MATCH_HOMETEAM_ID, self::MATCH_GUESTTEAM_ID, self::MATCH_HOMESCORE, self::MATCH_GUESTSCORE, self::MATCH_HOMESCORE_PENALTY, self::MATCH_GUESTSCORE_PENALTY, self::MATCH_DATE, self::MATCH_MINUTES, self::MATCH_STATUS, self::MATCH_URL, ),
		BasePeer::TYPE_RAW_COLNAME => array ('MATCH_ID', 'MATCH_ROUND', 'MATCH_HOMETEAM_ID', 'MATCH_GUESTTEAM_ID', 'MATCH_HOMESCORE', 'MATCH_GUESTSCORE', 'MATCH_HOMESCORE_PENALTY', 'MATCH_GUESTSCORE_PENALTY', 'MATCH_DATE', 'MATCH_MINUTES', 'MATCH_STATUS', 'MATCH_URL', ),
		BasePeer::TYPE_FIELDNAME => array ('match_id', 'match_round', 'match_hometeam_id', 'match_guestteam_id', 'match_homescore', 'match_guestscore', 'match_homescore_penalty', 'match_guestscore_penalty', 'match_date', 'match_minutes', 'match_status', 'match_url', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('MatchId' => 0, 'MatchRound' => 1, 'MatchHometeamId' => 2, 'MatchGuestteamId' => 3, 'MatchHomescore' => 4, 'MatchGuestscore' => 5, 'MatchHomescorePenalty' => 6, 'MatchGuestscorePenalty' => 7, 'MatchDate' => 8, 'MatchMinutes' => 9, 'MatchStatus' => 10, 'MatchUrl' => 11, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('matchId' => 0, 'matchRound' => 1, 'matchHometeamId' => 2, 'matchGuestteamId' => 3, 'matchHomescore' => 4, 'matchGuestscore' => 5, 'matchHomescorePenalty' => 6, 'matchGuestscorePenalty' => 7, 'matchDate' => 8, 'matchMinutes' => 9, 'matchStatus' => 10, 'matchUrl' => 11, ),
		BasePeer::TYPE_COLNAME => array (self::MATCH_ID => 0, self::MATCH_ROUND => 1, self::MATCH_HOMETEAM_ID => 2, self::MATCH_GUESTTEAM_ID => 3, self::MATCH_HOMESCORE => 4, self::MATCH_GUESTSCORE => 5, self::MATCH_HOMESCORE_PENALTY => 6, self::MATCH_GUESTSCORE_PENALTY => 7, self::MATCH_DATE => 8, self::MATCH_MINUTES => 9, self::MATCH_STATUS => 10, self::MATCH_URL => 11, ),
		BasePeer::TYPE_RAW_COLNAME => array ('MATCH_ID' => 0, 'MATCH_ROUND' => 1, 'MATCH_HOMETEAM_ID' => 2, 'MATCH_GUESTTEAM_ID' => 3, 'MATCH_HOMESCORE' => 4, 'MATCH_GUESTSCORE' => 5, 'MATCH_HOMESCORE_PENALTY' => 6, 'MATCH_GUESTSCORE_PENALTY' => 7, 'MATCH_DATE' => 8, 'MATCH_MINUTES' => 9, 'MATCH_STATUS' => 10, 'MATCH_URL' => 11, ),
		BasePeer::TYPE_FIELDNAME => array ('match_id' => 0, 'match_round' => 1, 'match_hometeam_id' => 2, 'match_guestteam_id' => 3, 'match_homescore' => 4, 'match_guestscore' => 5, 'match_homescore_penalty' => 6, 'match_guestscore_penalty' => 7, 'match_date' => 8, 'match_minutes' => 9, 'match_status' => 10, 'match_url' => 11, ),
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
	 * @param      string $column The column name for current table. (i.e. FfbMatchPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbMatchPeer::TABLE_NAME.'.', $alias.'.', $column);
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
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_ID);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_ROUND);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_HOMETEAM_ID);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_GUESTTEAM_ID);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_HOMESCORE);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_GUESTSCORE);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_HOMESCORE_PENALTY);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_GUESTSCORE_PENALTY);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_DATE);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_MINUTES);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_STATUS);
			$criteria->addSelectColumn(FfbMatchPeer::MATCH_URL);
		} else {
			$criteria->addSelectColumn($alias . '.MATCH_ID');
			$criteria->addSelectColumn($alias . '.MATCH_ROUND');
			$criteria->addSelectColumn($alias . '.MATCH_HOMETEAM_ID');
			$criteria->addSelectColumn($alias . '.MATCH_GUESTTEAM_ID');
			$criteria->addSelectColumn($alias . '.MATCH_HOMESCORE');
			$criteria->addSelectColumn($alias . '.MATCH_GUESTSCORE');
			$criteria->addSelectColumn($alias . '.MATCH_HOMESCORE_PENALTY');
			$criteria->addSelectColumn($alias . '.MATCH_GUESTSCORE_PENALTY');
			$criteria->addSelectColumn($alias . '.MATCH_DATE');
			$criteria->addSelectColumn($alias . '.MATCH_MINUTES');
			$criteria->addSelectColumn($alias . '.MATCH_STATUS');
			$criteria->addSelectColumn($alias . '.MATCH_URL');
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
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return     FfbMatch
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, ?PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbMatchPeer::doSelect($critcopy, $con);
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
		return FfbMatchPeer::populateObjects(FfbMatchPeer::doSelectStmt($criteria, $con));
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
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbMatchPeer::addSelectColumns($criteria);
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
	 * @param      FfbMatch $value A FfbMatch object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbMatch $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getMatchId();
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
	 * @param      mixed $value A FfbMatch object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbMatch) {
				$key = (string) $value->getMatchId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbMatch object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
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
	 * @return     FfbMatch Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
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
	 * Method to invalidate the instance pool of all tables related to ffb_match
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
		// Invalidate objects in FfbGoalPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbGoalPeer::clearInstancePool();
		// Invalidate objects in FfbPsgoalPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbPsgoalPeer::clearInstancePool();
		// Invalidate objects in FfbPlayerstatsPeer instance pool, 
		// since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
		FfbPlayerstatsPeer::clearInstancePool();
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
		$cls = FfbMatchPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbMatchPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbMatchPeer::addInstanceToPool($obj, $key);
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
	 * @return     array (FfbMatch object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = FfbMatchPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = FfbMatchPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://www.propelorm.org/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + FfbMatchPeer::NUM_COLUMNS;
		} else {
			$cls = FfbMatchPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			FfbMatchPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
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
	public static function doCountJoinFfbMatchround(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByMatchHometeamId table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbTeamRelatedByMatchHometeamId(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByMatchGuestteamId table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbTeamRelatedByMatchGuestteamId(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Selects a collection of FfbMatch objects pre-filled with their FfbMatchround objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
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

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbMatchroundPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbMatch) to $obj2 (FfbMatchround)
				$obj2->addFfbMatch($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbMatch objects pre-filled with their FfbTeam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbTeamRelatedByMatchHometeamId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbTeamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbMatch) to $obj2 (FfbTeam)
				$obj2->addFfbMatchRelatedByMatchHometeamId($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbMatch objects pre-filled with their FfbTeam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbTeamRelatedByMatchGuestteamId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbTeamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbMatch) to $obj2 (FfbTeam)
				$obj2->addFfbMatchRelatedByMatchGuestteamId($obj1);

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
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Selects a collection of FfbMatch objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
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

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol2 = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined FfbMatchround rows

			$key2 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = FfbMatchroundPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbMatchroundPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbMatchroundPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (FfbMatch) to the collection in $obj2 (FfbMatchround)
				$obj2->addFfbMatch($obj1);
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

				// Add the $obj1 (FfbMatch) to the collection in $obj3 (FfbTeam)
				$obj3->addFfbMatchRelatedByMatchHometeamId($obj1);
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

				// Add the $obj1 (FfbMatch) to the collection in $obj4 (FfbTeam)
				$obj4->addFfbMatchRelatedByMatchGuestteamId($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
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
	public static function doCountJoinAllExceptFfbMatchround(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByMatchHometeamId table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbTeamRelatedByMatchHometeamId(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Returns the number of rows matching criteria, joining the related FfbTeamRelatedByMatchGuestteamId table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbTeamRelatedByMatchGuestteamId(Criteria $criteria, $distinct = false, ?PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbMatchPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

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
	 * Selects a collection of FfbMatch objects pre-filled with all related objects except FfbMatchround.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
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

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol2 = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbTeamPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbTeamPeer::NUM_COLUMNS - FfbTeamPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);

		$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
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

				// Add the $obj1 (FfbMatch) to the collection in $obj2 (FfbTeam)
				$obj2->addFfbMatchRelatedByMatchHometeamId($obj1);

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

				// Add the $obj1 (FfbMatch) to the collection in $obj3 (FfbTeam)
				$obj3->addFfbMatchRelatedByMatchGuestteamId($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbMatch objects pre-filled with all related objects except FfbTeamRelatedByMatchHometeamId.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbTeamRelatedByMatchHometeamId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol2 = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbMatchround rows

				$key2 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbMatchroundPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbMatchroundPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbMatch) to the collection in $obj2 (FfbMatchround)
				$obj2->addFfbMatch($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbMatch objects pre-filled with all related objects except FfbTeamRelatedByMatchGuestteamId.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbMatch objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbTeamRelatedByMatchGuestteamId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol2 = (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbMatchPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://www.propelorm.org/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbMatchPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbMatchPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbMatchround rows

				$key2 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbMatchroundPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbMatchroundPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbMatch) to the collection in $obj2 (FfbMatchround)
				$obj2->addFfbMatch($obj1);

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
	  $dbMap = Propel::getDatabaseMap(BaseFfbMatchPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbMatchPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbMatchTableMap());
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
		return $withPrefix ? FfbMatchPeer::CLASS_DEFAULT : FfbMatchPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbMatch or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbMatch object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbMatch object
		}

		if ($criteria->containsKey(FfbMatchPeer::MATCH_ID) && $criteria->keyContainsValue(FfbMatchPeer::MATCH_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbMatchPeer::MATCH_ID.')');
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
	 * Method perform an UPDATE on the database, given a FfbMatch or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbMatch object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, ?PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbMatchPeer::MATCH_ID);
			$value = $criteria->remove(FfbMatchPeer::MATCH_ID);
			if ($value) {
				$selectCriteria->add(FfbMatchPeer::MATCH_ID, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(FfbMatchPeer::TABLE_NAME);
			}

		} else { // $values is FfbMatch object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_match table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += FfbMatchPeer::doOnDeleteCascade(new Criteria(FfbMatchPeer::DATABASE_NAME), $con);
			$affectedRows += BasePeer::doDeleteAll(FfbMatchPeer::TABLE_NAME, $con, FfbMatchPeer::DATABASE_NAME);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbMatchPeer::clearInstancePool();
			FfbMatchPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbMatch or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbMatch object or primary key or array of primary keys
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
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbMatch) { // it's a model object
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbMatchPeer::MATCH_ID, (array) $values, Criteria::IN);
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
			$affectedRows += FfbMatchPeer::doOnDeleteCascade($c, $con);
			
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			if ($values instanceof Criteria) {
				FfbMatchPeer::clearInstancePool();
			} elseif ($values instanceof FfbMatch) { // it's a model object
				FfbMatchPeer::removeInstanceFromPool($values);
			} else { // it's a primary key, or an array of pks
				foreach ((array) $values as $singleval) {
					FfbMatchPeer::removeInstanceFromPool($singleval);
				}
			}
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			FfbMatchPeer::clearRelatedInstancePool();
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
		$objects = FfbMatchPeer::doSelect($criteria, $con);
		foreach ($objects as $obj) {


			// delete related FfbGoal objects
			$criteria = new Criteria(FfbGoalPeer::DATABASE_NAME);
			
			$criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $obj->getMatchId());
			$affectedRows += FfbGoalPeer::doDelete($criteria, $con);

			// delete related FfbPsgoal objects
			$criteria = new Criteria(FfbPsgoalPeer::DATABASE_NAME);
			
			$criteria->add(FfbPsgoalPeer::PSGOAL_MATCH_ID, $obj->getMatchId());
			$affectedRows += FfbPsgoalPeer::doDelete($criteria, $con);

			// delete related FfbPlayerstats objects
			$criteria = new Criteria(FfbPlayerstatsPeer::DATABASE_NAME);
			
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $obj->getMatchId());
			$affectedRows += FfbPlayerstatsPeer::doDelete($criteria, $con);
		}
		return $affectedRows;
	}

	/**
	 * Validates all modified columns of given FfbMatch object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbMatch $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbMatch $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbMatchPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbMatchPeer::TABLE_NAME);

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

		return BasePeer::doValidate(FfbMatchPeer::DATABASE_NAME, FfbMatchPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbMatch
	 */
	public static function retrieveByPK($pk, ?PropelPDO $con = null)
	{

		if (null !== ($obj = FfbMatchPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbMatchPeer::DATABASE_NAME);
		$criteria->add(FfbMatchPeer::MATCH_ID, $pk);

		$v = FfbMatchPeer::doSelect($criteria, $con);

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
			$con = Propel::getConnection(FfbMatchPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbMatchPeer::DATABASE_NAME);
			$criteria->add(FfbMatchPeer::MATCH_ID, $pks, Criteria::IN);
			$objs = FfbMatchPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbMatchPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbMatchPeer::buildTableMap();

