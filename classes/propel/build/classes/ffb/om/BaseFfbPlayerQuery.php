<?php


/**
 * Base class that represents a query for the 'ffb_player' table.
 *
 * 
 *
 * @method     FfbPlayerQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     FfbPlayerQuery orderByPlayerForeignId($order = Criteria::ASC) Order by the player_foreign_id column
 * @method     FfbPlayerQuery orderByPlayerFname($order = Criteria::ASC) Order by the player_fname column
 * @method     FfbPlayerQuery orderByPlayerLname($order = Criteria::ASC) Order by the player_lname column
 * @method     FfbPlayerQuery orderByPlayerNationality($order = Criteria::ASC) Order by the player_nationality column
 * @method     FfbPlayerQuery orderByPlayerStatus($order = Criteria::ASC) Order by the player_status column
 * @method     FfbPlayerQuery orderByPlayerStatusDescription($order = Criteria::ASC) Order by the player_status_description column
 *
 * @method     FfbPlayerQuery groupByPlayerId() Group by the player_id column
 * @method     FfbPlayerQuery groupByPlayerForeignId() Group by the player_foreign_id column
 * @method     FfbPlayerQuery groupByPlayerFname() Group by the player_fname column
 * @method     FfbPlayerQuery groupByPlayerLname() Group by the player_lname column
 * @method     FfbPlayerQuery groupByPlayerNationality() Group by the player_nationality column
 * @method     FfbPlayerQuery groupByPlayerStatus() Group by the player_status column
 * @method     FfbPlayerQuery groupByPlayerStatusDescription() Group by the player_status_description column
 *
 * @method     FfbPlayerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPlayerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPlayerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPlayerQuery leftJoinWebUserDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserDetails relation
 * @method     FfbPlayerQuery rightJoinWebUserDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserDetails relation
 * @method     FfbPlayerQuery innerJoinWebUserDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserDetails relation
 *
 * @method     FfbPlayerQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbPlayer findOne(?PropelPDO $con = null) Return the first FfbPlayer matching the query
 * @method     FfbPlayer findOneOrCreate(?PropelPDO $con = null) Return the first FfbPlayer matching the query, or a new FfbPlayer object populated from the query conditions when no match is found
 *
 * @method     FfbPlayer findOneByPlayerId(int $player_id) Return the first FfbPlayer filtered by the player_id column
 * @method     FfbPlayer findOneByPlayerForeignId(string $player_foreign_id) Return the first FfbPlayer filtered by the player_foreign_id column
 * @method     FfbPlayer findOneByPlayerFname(string $player_fname) Return the first FfbPlayer filtered by the player_fname column
 * @method     FfbPlayer findOneByPlayerLname(string $player_lname) Return the first FfbPlayer filtered by the player_lname column
 * @method     FfbPlayer findOneByPlayerNationality(string $player_nationality) Return the first FfbPlayer filtered by the player_nationality column
 * @method     FfbPlayer findOneByPlayerStatus(int $player_status) Return the first FfbPlayer filtered by the player_status column
 * @method     FfbPlayer findOneByPlayerStatusDescription(string $player_status_description) Return the first FfbPlayer filtered by the player_status_description column
 *
 * @method     array findByPlayerId(int $player_id) Return FfbPlayer objects filtered by the player_id column
 * @method     array findByPlayerForeignId(string $player_foreign_id) Return FfbPlayer objects filtered by the player_foreign_id column
 * @method     array findByPlayerFname(string $player_fname) Return FfbPlayer objects filtered by the player_fname column
 * @method     array findByPlayerLname(string $player_lname) Return FfbPlayer objects filtered by the player_lname column
 * @method     array findByPlayerNationality(string $player_nationality) Return FfbPlayer objects filtered by the player_nationality column
 * @method     array findByPlayerStatus(int $player_status) Return FfbPlayer objects filtered by the player_status column
 * @method     array findByPlayerStatusDescription(string $player_status_description) Return FfbPlayer objects filtered by the player_status_description column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPlayerQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPlayer', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPlayerQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPlayerQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPlayerQuery) {
			return $criteria;
		}
		$query = new FfbPlayerQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    FfbPlayer|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPlayerPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the player_id column
	 * 
	 * @param     int|array $playerId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerId($playerId = null, $comparison = null)
	{
		if (is_array($playerId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $playerId, $comparison);
	}

	/**
	 * Filter the query on the player_foreign_id column
	 * 
	 * @param     string $playerForeignId The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerForeignId($playerForeignId = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerForeignId)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerForeignId)) {
				$playerForeignId = str_replace('*', '%', $playerForeignId);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_FOREIGN_ID, $playerForeignId, $comparison);
	}

	/**
	 * Filter the query on the player_fname column
	 * 
	 * @param     string $playerFname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerFname($playerFname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerFname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerFname)) {
				$playerFname = str_replace('*', '%', $playerFname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_FNAME, $playerFname, $comparison);
	}

	/**
	 * Filter the query on the player_lname column
	 * 
	 * @param     string $playerLname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerLname($playerLname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerLname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerLname)) {
				$playerLname = str_replace('*', '%', $playerLname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_LNAME, $playerLname, $comparison);
	}

	/**
	 * Filter the query on the player_nationality column
	 * 
	 * @param     string $playerNationality The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerNationality($playerNationality = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerNationality)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerNationality)) {
				$playerNationality = str_replace('*', '%', $playerNationality);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_NATIONALITY, $playerNationality, $comparison);
	}

	/**
	 * Filter the query on the player_status column
	 * 
	 * @param     int|array $playerStatus The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerStatus($playerStatus = null, $comparison = null)
	{
		if (is_array($playerStatus)) {
			$useMinMax = false;
			if (isset($playerStatus['min'])) {
				$this->addUsingAlias(FfbPlayerPeer::PLAYER_STATUS, $playerStatus['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerStatus['max'])) {
				$this->addUsingAlias(FfbPlayerPeer::PLAYER_STATUS, $playerStatus['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_STATUS, $playerStatus, $comparison);
	}

	/**
	 * Filter the query on the player_status_description column
	 * 
	 * @param     string $playerStatusDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByPlayerStatusDescription($playerStatusDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerStatusDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerStatusDescription)) {
				$playerStatusDescription = str_replace('*', '%', $playerStatusDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerPeer::PLAYER_STATUS_DESCRIPTION, $playerStatusDescription, $comparison);
	}

	/**
	 * Filter the query by a related WebUserDetails object
	 *
	 * @param     WebUserDetails $webUserDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByWebUserDetails($webUserDetails, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $webUserDetails->getUserDetailsFfbOwnPlayer(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserDetails relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function joinWebUserDetails($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserDetails');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'WebUserDetails');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserDetails relation WebUserDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserDetailsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinWebUserDetails($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserDetails', 'WebUserDetailsQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $ffbPlayerteam->getPlayerteamPlayerId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteam');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'FfbPlayerteam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteam relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteam', 'FfbPlayerteamQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbPlayer $ffbPlayer Object to remove from the list of results
	 *
	 * @return    FfbPlayerQuery The current query, for fluid interface
	 */
	public function prune($ffbPlayer = null)
	{
		if ($ffbPlayer) {
			$this->addUsingAlias(FfbPlayerPeer::PLAYER_ID, $ffbPlayer->getPlayerId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPlayerQuery
