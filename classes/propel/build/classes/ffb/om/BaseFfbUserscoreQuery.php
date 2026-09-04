<?php


/**
 * Base class that represents a query for the 'ffb_userscore' table.
 *
 * 
 *
 * @method     FfbUserscoreQuery orderByUserscoreId($order = Criteria::ASC) Order by the userscore_id column
 * @method     FfbUserscoreQuery orderByUserscoreUserId($order = Criteria::ASC) Order by the userscore_user_id column
 * @method     FfbUserscoreQuery orderByUserscoreGameId($order = Criteria::ASC) Order by the userscore_game_id column
 * @method     FfbUserscoreQuery orderByUserscoreTotal($order = Criteria::ASC) Order by the userscore_total column
 * @method     FfbUserscoreQuery orderByUserscoreWcPoints($order = Criteria::ASC) Order by the userscore_wc_points column
 *
 * @method     FfbUserscoreQuery groupByUserscoreId() Group by the userscore_id column
 * @method     FfbUserscoreQuery groupByUserscoreUserId() Group by the userscore_user_id column
 * @method     FfbUserscoreQuery groupByUserscoreGameId() Group by the userscore_game_id column
 * @method     FfbUserscoreQuery groupByUserscoreTotal() Group by the userscore_total column
 * @method     FfbUserscoreQuery groupByUserscoreWcPoints() Group by the userscore_wc_points column
 *
 * @method     FfbUserscoreQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbUserscoreQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbUserscoreQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbUserscoreQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbUserscoreQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbUserscoreQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbUserscoreQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbUserscoreQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbUserscoreQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbUserscore findOne(?PropelPDO $con = null) Return the first FfbUserscore matching the query
 * @method     FfbUserscore findOneOrCreate(?PropelPDO $con = null) Return the first FfbUserscore matching the query, or a new FfbUserscore object populated from the query conditions when no match is found
 *
 * @method     FfbUserscore findOneByUserscoreId(int $userscore_id) Return the first FfbUserscore filtered by the userscore_id column
 * @method     FfbUserscore findOneByUserscoreUserId(int $userscore_user_id) Return the first FfbUserscore filtered by the userscore_user_id column
 * @method     FfbUserscore findOneByUserscoreGameId(int $userscore_game_id) Return the first FfbUserscore filtered by the userscore_game_id column
 * @method     FfbUserscore findOneByUserscoreTotal(int $userscore_total) Return the first FfbUserscore filtered by the userscore_total column
 * @method     FfbUserscore findOneByUserscoreWcPoints(int $userscore_wc_points) Return the first FfbUserscore filtered by the userscore_wc_points column
 *
 * @method     array findByUserscoreId(int $userscore_id) Return FfbUserscore objects filtered by the userscore_id column
 * @method     array findByUserscoreUserId(int $userscore_user_id) Return FfbUserscore objects filtered by the userscore_user_id column
 * @method     array findByUserscoreGameId(int $userscore_game_id) Return FfbUserscore objects filtered by the userscore_game_id column
 * @method     array findByUserscoreTotal(int $userscore_total) Return FfbUserscore objects filtered by the userscore_total column
 * @method     array findByUserscoreWcPoints(int $userscore_wc_points) Return FfbUserscore objects filtered by the userscore_wc_points column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserscoreQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbUserscoreQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbUserscore', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbUserscoreQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbUserscoreQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbUserscoreQuery) {
			return $criteria;
		}
		$query = new FfbUserscoreQuery();
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
	 * @return    FfbUserscore|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbUserscorePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the userscore_id column
	 * 
	 * @param     int|array $userscoreId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByUserscoreId($userscoreId = null, $comparison = null)
	{
		if (is_array($userscoreId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_ID, $userscoreId, $comparison);
	}

	/**
	 * Filter the query on the userscore_user_id column
	 * 
	 * @param     int|array $userscoreUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByUserscoreUserId($userscoreUserId = null, $comparison = null)
	{
		if (is_array($userscoreUserId)) {
			$useMinMax = false;
			if (isset($userscoreUserId['min'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_USER_ID, $userscoreUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userscoreUserId['max'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_USER_ID, $userscoreUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_USER_ID, $userscoreUserId, $comparison);
	}

	/**
	 * Filter the query on the userscore_game_id column
	 * 
	 * @param     int|array $userscoreGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByUserscoreGameId($userscoreGameId = null, $comparison = null)
	{
		if (is_array($userscoreGameId)) {
			$useMinMax = false;
			if (isset($userscoreGameId['min'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_GAME_ID, $userscoreGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userscoreGameId['max'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_GAME_ID, $userscoreGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_GAME_ID, $userscoreGameId, $comparison);
	}

	/**
	 * Filter the query on the userscore_total column
	 * 
	 * @param     int|array $userscoreTotal The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByUserscoreTotal($userscoreTotal = null, $comparison = null)
	{
		if (is_array($userscoreTotal)) {
			$useMinMax = false;
			if (isset($userscoreTotal['min'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_TOTAL, $userscoreTotal['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userscoreTotal['max'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_TOTAL, $userscoreTotal['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_TOTAL, $userscoreTotal, $comparison);
	}

	/**
	 * Filter the query on the userscore_wc_points column
	 * 
	 * @param     int|array $userscoreWcPoints The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByUserscoreWcPoints($userscoreWcPoints = null, $comparison = null)
	{
		if (is_array($userscoreWcPoints)) {
			$useMinMax = false;
			if (isset($userscoreWcPoints['min'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_WC_POINTS, $userscoreWcPoints['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userscoreWcPoints['max'])) {
				$this->addUsingAlias(FfbUserscorePeer::USERSCORE_WC_POINTS, $userscoreWcPoints['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserscorePeer::USERSCORE_WC_POINTS, $userscoreWcPoints, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserscorePeer::USERSCORE_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function joinWebUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUser');
		
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
			$this->addJoinObject($join, 'WebUser');
		}
		
		return $this;
	}

	/**
	 * Use the WebUser relation WebUser object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUser', 'WebUserQuery');
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserscorePeer::USERSCORE_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function joinFfbGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbGame');
		
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
			$this->addJoinObject($join, 'FfbGame');
		}
		
		return $this;
	}

	/**
	 * Use the FfbGame relation FfbGame object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery A secondary query class using the current class as primary query
	 */
	public function useFfbGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbGame($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbGame', 'FfbGameQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbUserscore $ffbUserscore Object to remove from the list of results
	 *
	 * @return    FfbUserscoreQuery The current query, for fluid interface
	 */
	public function prune($ffbUserscore = null)
	{
		if ($ffbUserscore) {
			$this->addUsingAlias(FfbUserscorePeer::USERSCORE_ID, $ffbUserscore->getUserscoreId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbUserscoreQuery
