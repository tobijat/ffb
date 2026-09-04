<?php


/**
 * Base class that represents a query for the 'ffb_team' table.
 *
 * 
 *
 * @method     FfbTeamQuery orderByTeamId($order = Criteria::ASC) Order by the team_id column
 * @method     FfbTeamQuery orderByTeamForeignId($order = Criteria::ASC) Order by the team_foreign_id column
 * @method     FfbTeamQuery orderByTeamName($order = Criteria::ASC) Order by the team_name column
 * @method     FfbTeamQuery orderByTeamNationality($order = Criteria::ASC) Order by the team_nationality column
 * @method     FfbTeamQuery orderByTeamAvgPrice($order = Criteria::ASC) Order by the team_avg_price column
 * @method     FfbTeamQuery orderByTeamNumPlayers($order = Criteria::ASC) Order by the team_num_players column
 * @method     FfbTeamQuery orderByTeamStatus($order = Criteria::ASC) Order by the team_status column
 *
 * @method     FfbTeamQuery groupByTeamId() Group by the team_id column
 * @method     FfbTeamQuery groupByTeamForeignId() Group by the team_foreign_id column
 * @method     FfbTeamQuery groupByTeamName() Group by the team_name column
 * @method     FfbTeamQuery groupByTeamNationality() Group by the team_nationality column
 * @method     FfbTeamQuery groupByTeamAvgPrice() Group by the team_avg_price column
 * @method     FfbTeamQuery groupByTeamNumPlayers() Group by the team_num_players column
 * @method     FfbTeamQuery groupByTeamStatus() Group by the team_status column
 *
 * @method     FfbTeamQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbTeamQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbTeamQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbTeamQuery leftJoinWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam relation
 * @method     FfbTeamQuery rightJoinWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam relation
 * @method     FfbTeamQuery innerJoinWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam relation
 *
 * @method     FfbTeamQuery leftJoinWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbOwnTeam relation
 * @method     FfbTeamQuery rightJoinWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbOwnTeam relation
 * @method     FfbTeamQuery innerJoinWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbOwnTeam relation
 *
 * @method     FfbTeamQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbTeamQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbTeamQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbTeamQuery leftJoinFfbMatchRelatedByMatchHometeamId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchRelatedByMatchHometeamId relation
 * @method     FfbTeamQuery rightJoinFfbMatchRelatedByMatchHometeamId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchRelatedByMatchHometeamId relation
 * @method     FfbTeamQuery innerJoinFfbMatchRelatedByMatchHometeamId($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchRelatedByMatchHometeamId relation
 *
 * @method     FfbTeamQuery leftJoinFfbMatchRelatedByMatchGuestteamId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchRelatedByMatchGuestteamId relation
 * @method     FfbTeamQuery rightJoinFfbMatchRelatedByMatchGuestteamId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchRelatedByMatchGuestteamId relation
 * @method     FfbTeamQuery innerJoinFfbMatchRelatedByMatchGuestteamId($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchRelatedByMatchGuestteamId relation
 *
 * @method     FfbTeamQuery leftJoinFfbPlayerfid($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerfid relation
 * @method     FfbTeamQuery rightJoinFfbPlayerfid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerfid relation
 * @method     FfbTeamQuery innerJoinFfbPlayerfid($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerfid relation
 *
 * @method     FfbTeamQuery leftJoinFfbTeamfid($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeamfid relation
 * @method     FfbTeamQuery rightJoinFfbTeamfid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeamfid relation
 * @method     FfbTeamQuery innerJoinFfbTeamfid($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeamfid relation
 *
 * @method     FfbTeam findOne(?PropelPDO $con = null) Return the first FfbTeam matching the query
 * @method     FfbTeam findOneOrCreate(?PropelPDO $con = null) Return the first FfbTeam matching the query, or a new FfbTeam object populated from the query conditions when no match is found
 *
 * @method     FfbTeam findOneByTeamId(int $team_id) Return the first FfbTeam filtered by the team_id column
 * @method     FfbTeam findOneByTeamForeignId(string $team_foreign_id) Return the first FfbTeam filtered by the team_foreign_id column
 * @method     FfbTeam findOneByTeamName(string $team_name) Return the first FfbTeam filtered by the team_name column
 * @method     FfbTeam findOneByTeamNationality(string $team_nationality) Return the first FfbTeam filtered by the team_nationality column
 * @method     FfbTeam findOneByTeamAvgPrice(double $team_avg_price) Return the first FfbTeam filtered by the team_avg_price column
 * @method     FfbTeam findOneByTeamNumPlayers(int $team_num_players) Return the first FfbTeam filtered by the team_num_players column
 * @method     FfbTeam findOneByTeamStatus(boolean $team_status) Return the first FfbTeam filtered by the team_status column
 *
 * @method     array findByTeamId(int $team_id) Return FfbTeam objects filtered by the team_id column
 * @method     array findByTeamForeignId(string $team_foreign_id) Return FfbTeam objects filtered by the team_foreign_id column
 * @method     array findByTeamName(string $team_name) Return FfbTeam objects filtered by the team_name column
 * @method     array findByTeamNationality(string $team_nationality) Return FfbTeam objects filtered by the team_nationality column
 * @method     array findByTeamAvgPrice(double $team_avg_price) Return FfbTeam objects filtered by the team_avg_price column
 * @method     array findByTeamNumPlayers(int $team_num_players) Return FfbTeam objects filtered by the team_num_players column
 * @method     array findByTeamStatus(boolean $team_status) Return FfbTeam objects filtered by the team_status column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbTeamQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbTeamQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbTeam', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbTeamQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbTeamQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbTeamQuery) {
			return $criteria;
		}
		$query = new FfbTeamQuery();
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
	 * @return    FfbTeam|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbTeamPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbTeamPeer::TEAM_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbTeamPeer::TEAM_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the team_id column
	 * 
	 * @param     int|array $teamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamId($teamId = null, $comparison = null)
	{
		if (is_array($teamId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_ID, $teamId, $comparison);
	}

	/**
	 * Filter the query on the team_foreign_id column
	 * 
	 * @param     string $teamForeignId The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamForeignId($teamForeignId = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamForeignId)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamForeignId)) {
				$teamForeignId = str_replace('*', '%', $teamForeignId);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_FOREIGN_ID, $teamForeignId, $comparison);
	}

	/**
	 * Filter the query on the team_name column
	 * 
	 * @param     string $teamName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamName($teamName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamName)) {
				$teamName = str_replace('*', '%', $teamName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_NAME, $teamName, $comparison);
	}

	/**
	 * Filter the query on the team_nationality column
	 * 
	 * @param     string $teamNationality The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamNationality($teamNationality = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamNationality)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamNationality)) {
				$teamNationality = str_replace('*', '%', $teamNationality);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_NATIONALITY, $teamNationality, $comparison);
	}

	/**
	 * Filter the query on the team_avg_price column
	 * 
	 * @param     double|array $teamAvgPrice The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamAvgPrice($teamAvgPrice = null, $comparison = null)
	{
		if (is_array($teamAvgPrice)) {
			$useMinMax = false;
			if (isset($teamAvgPrice['min'])) {
				$this->addUsingAlias(FfbTeamPeer::TEAM_AVG_PRICE, $teamAvgPrice['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($teamAvgPrice['max'])) {
				$this->addUsingAlias(FfbTeamPeer::TEAM_AVG_PRICE, $teamAvgPrice['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_AVG_PRICE, $teamAvgPrice, $comparison);
	}

	/**
	 * Filter the query on the team_num_players column
	 * 
	 * @param     int|array $teamNumPlayers The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamNumPlayers($teamNumPlayers = null, $comparison = null)
	{
		if (is_array($teamNumPlayers)) {
			$useMinMax = false;
			if (isset($teamNumPlayers['min'])) {
				$this->addUsingAlias(FfbTeamPeer::TEAM_NUM_PLAYERS, $teamNumPlayers['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($teamNumPlayers['max'])) {
				$this->addUsingAlias(FfbTeamPeer::TEAM_NUM_PLAYERS, $teamNumPlayers['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_NUM_PLAYERS, $teamNumPlayers, $comparison);
	}

	/**
	 * Filter the query on the team_status column
	 * 
	 * @param     boolean|string $teamStatus The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByTeamStatus($teamStatus = null, $comparison = null)
	{
		if (is_string($teamStatus)) {
			$team_status = in_array(strtolower($teamStatus), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbTeamPeer::TEAM_STATUS, $teamStatus, $comparison);
	}

	/**
	 * Filter the query by a related WebUserDetails object
	 *
	 * @param     WebUserDetails $webUserDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($webUserDetails, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $webUserDetails->getUserDetailsFfbFavouriteTeam(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam');
		
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
			$this->addJoinObject($join, 'WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam relation WebUserDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserDetailsRelatedByUserDetailsFfbFavouriteTeamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinWebUserDetailsRelatedByUserDetailsFfbFavouriteTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam', 'WebUserDetailsQuery');
	}

	/**
	 * Filter the query by a related WebUserDetails object
	 *
	 * @param     WebUserDetails $webUserDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByWebUserDetailsRelatedByUserDetailsFfbOwnTeam($webUserDetails, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $webUserDetails->getUserDetailsFfbOwnTeam(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserDetailsRelatedByUserDetailsFfbOwnTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserDetailsRelatedByUserDetailsFfbOwnTeam');
		
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
			$this->addJoinObject($join, 'WebUserDetailsRelatedByUserDetailsFfbOwnTeam');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserDetailsRelatedByUserDetailsFfbOwnTeam relation WebUserDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserDetailsRelatedByUserDetailsFfbOwnTeamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinWebUserDetailsRelatedByUserDetailsFfbOwnTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserDetailsRelatedByUserDetailsFfbOwnTeam', 'WebUserDetailsQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbPlayerteam->getPlayerteamTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchRelatedByMatchHometeamId($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbMatch->getMatchHometeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchRelatedByMatchHometeamId relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinFfbMatchRelatedByMatchHometeamId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatchRelatedByMatchHometeamId');
		
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
			$this->addJoinObject($join, 'FfbMatchRelatedByMatchHometeamId');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatchRelatedByMatchHometeamId relation FfbMatch object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchRelatedByMatchHometeamIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatchRelatedByMatchHometeamId($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatchRelatedByMatchHometeamId', 'FfbMatchQuery');
	}

	/**
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchRelatedByMatchGuestteamId($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbMatch->getMatchGuestteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchRelatedByMatchGuestteamId relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinFfbMatchRelatedByMatchGuestteamId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatchRelatedByMatchGuestteamId');
		
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
			$this->addJoinObject($join, 'FfbMatchRelatedByMatchGuestteamId');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatchRelatedByMatchGuestteamId relation FfbMatch object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchRelatedByMatchGuestteamIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatchRelatedByMatchGuestteamId($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatchRelatedByMatchGuestteamId', 'FfbMatchQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerfid object
	 *
	 * @param     FfbPlayerfid $ffbPlayerfid  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerfid($ffbPlayerfid, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbPlayerfid->getPlayerfidTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerfid relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerfid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerfid');
		
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
			$this->addJoinObject($join, 'FfbPlayerfid');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerfid relation FfbPlayerfid object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerfidQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerfidQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerfid($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerfid', 'FfbPlayerfidQuery');
	}

	/**
	 * Filter the query by a related FfbTeamfid object
	 *
	 * @param     FfbTeamfid $ffbTeamfid  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function filterByFfbTeamfid($ffbTeamfid, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbTeamfid->getTeamfidTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeamfid relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function joinFfbTeamfid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeamfid');
		
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
			$this->addJoinObject($join, 'FfbTeamfid');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeamfid relation FfbTeamfid object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamfidQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamfidQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbTeamfid($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeamfid', 'FfbTeamfidQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbTeam $ffbTeam Object to remove from the list of results
	 *
	 * @return    FfbTeamQuery The current query, for fluid interface
	 */
	public function prune($ffbTeam = null)
	{
		if ($ffbTeam) {
			$this->addUsingAlias(FfbTeamPeer::TEAM_ID, $ffbTeam->getTeamId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbTeamQuery
