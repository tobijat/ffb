<?php


/**
 * Base class that represents a query for the 'ffb_psgoal' table.
 *
 * 
 *
 * @method     FfbPsgoalQuery orderByPsgoalId($order = Criteria::ASC) Order by the psgoal_id column
 * @method     FfbPsgoalQuery orderByPsgoalMatchId($order = Criteria::ASC) Order by the psgoal_match_id column
 * @method     FfbPsgoalQuery orderByPsgoalPlayerteamId($order = Criteria::ASC) Order by the psgoal_playerteam_id column
 * @method     FfbPsgoalQuery orderByPsgoalMinute($order = Criteria::ASC) Order by the psgoal_minute column
 * @method     FfbPsgoalQuery orderByPsgoalHit($order = Criteria::ASC) Order by the psgoal_hit column
 * @method     FfbPsgoalQuery orderByPsgoalFail($order = Criteria::ASC) Order by the psgoal_fail column
 *
 * @method     FfbPsgoalQuery groupByPsgoalId() Group by the psgoal_id column
 * @method     FfbPsgoalQuery groupByPsgoalMatchId() Group by the psgoal_match_id column
 * @method     FfbPsgoalQuery groupByPsgoalPlayerteamId() Group by the psgoal_playerteam_id column
 * @method     FfbPsgoalQuery groupByPsgoalMinute() Group by the psgoal_minute column
 * @method     FfbPsgoalQuery groupByPsgoalHit() Group by the psgoal_hit column
 * @method     FfbPsgoalQuery groupByPsgoalFail() Group by the psgoal_fail column
 *
 * @method     FfbPsgoalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPsgoalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPsgoalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPsgoalQuery leftJoinFfbMatch($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatch relation
 * @method     FfbPsgoalQuery rightJoinFfbMatch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatch relation
 * @method     FfbPsgoalQuery innerJoinFfbMatch($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatch relation
 *
 * @method     FfbPsgoalQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPsgoalQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPsgoalQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbPsgoal findOne(PropelPDO $con = null) Return the first FfbPsgoal matching the query
 * @method     FfbPsgoal findOneOrCreate(PropelPDO $con = null) Return the first FfbPsgoal matching the query, or a new FfbPsgoal object populated from the query conditions when no match is found
 *
 * @method     FfbPsgoal findOneByPsgoalId(int $psgoal_id) Return the first FfbPsgoal filtered by the psgoal_id column
 * @method     FfbPsgoal findOneByPsgoalMatchId(int $psgoal_match_id) Return the first FfbPsgoal filtered by the psgoal_match_id column
 * @method     FfbPsgoal findOneByPsgoalPlayerteamId(int $psgoal_playerteam_id) Return the first FfbPsgoal filtered by the psgoal_playerteam_id column
 * @method     FfbPsgoal findOneByPsgoalMinute(int $psgoal_minute) Return the first FfbPsgoal filtered by the psgoal_minute column
 * @method     FfbPsgoal findOneByPsgoalHit(boolean $psgoal_hit) Return the first FfbPsgoal filtered by the psgoal_hit column
 * @method     FfbPsgoal findOneByPsgoalFail(boolean $psgoal_fail) Return the first FfbPsgoal filtered by the psgoal_fail column
 *
 * @method     array findByPsgoalId(int $psgoal_id) Return FfbPsgoal objects filtered by the psgoal_id column
 * @method     array findByPsgoalMatchId(int $psgoal_match_id) Return FfbPsgoal objects filtered by the psgoal_match_id column
 * @method     array findByPsgoalPlayerteamId(int $psgoal_playerteam_id) Return FfbPsgoal objects filtered by the psgoal_playerteam_id column
 * @method     array findByPsgoalMinute(int $psgoal_minute) Return FfbPsgoal objects filtered by the psgoal_minute column
 * @method     array findByPsgoalHit(boolean $psgoal_hit) Return FfbPsgoal objects filtered by the psgoal_hit column
 * @method     array findByPsgoalFail(boolean $psgoal_fail) Return FfbPsgoal objects filtered by the psgoal_fail column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPsgoalQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPsgoalQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPsgoal', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPsgoalQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPsgoalQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPsgoalQuery) {
			return $criteria;
		}
		$query = new FfbPsgoalQuery();
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
	 * @return    FfbPsgoal|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPsgoalPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the psgoal_id column
	 * 
	 * @param     int|array $psgoalId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalId($psgoalId = null, $comparison = null)
	{
		if (is_array($psgoalId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_ID, $psgoalId, $comparison);
	}

	/**
	 * Filter the query on the psgoal_match_id column
	 * 
	 * @param     int|array $psgoalMatchId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalMatchId($psgoalMatchId = null, $comparison = null)
	{
		if (is_array($psgoalMatchId)) {
			$useMinMax = false;
			if (isset($psgoalMatchId['min'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MATCH_ID, $psgoalMatchId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($psgoalMatchId['max'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MATCH_ID, $psgoalMatchId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MATCH_ID, $psgoalMatchId, $comparison);
	}

	/**
	 * Filter the query on the psgoal_playerteam_id column
	 * 
	 * @param     int|array $psgoalPlayerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalPlayerteamId($psgoalPlayerteamId = null, $comparison = null)
	{
		if (is_array($psgoalPlayerteamId)) {
			$useMinMax = false;
			if (isset($psgoalPlayerteamId['min'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, $psgoalPlayerteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($psgoalPlayerteamId['max'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, $psgoalPlayerteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, $psgoalPlayerteamId, $comparison);
	}

	/**
	 * Filter the query on the psgoal_minute column
	 * 
	 * @param     int|array $psgoalMinute The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalMinute($psgoalMinute = null, $comparison = null)
	{
		if (is_array($psgoalMinute)) {
			$useMinMax = false;
			if (isset($psgoalMinute['min'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MINUTE, $psgoalMinute['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($psgoalMinute['max'])) {
				$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MINUTE, $psgoalMinute['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_MINUTE, $psgoalMinute, $comparison);
	}

	/**
	 * Filter the query on the psgoal_hit column
	 * 
	 * @param     boolean|string $psgoalHit The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalHit($psgoalHit = null, $comparison = null)
	{
		if (is_string($psgoalHit)) {
			$psgoal_hit = in_array(strtolower($psgoalHit), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_HIT, $psgoalHit, $comparison);
	}

	/**
	 * Filter the query on the psgoal_fail column
	 * 
	 * @param     boolean|string $psgoalFail The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByPsgoalFail($psgoalFail = null, $comparison = null)
	{
		if (is_string($psgoalFail)) {
			$psgoal_fail = in_array(strtolower($psgoalFail), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbPsgoalPeer::PSGOAL_FAIL, $psgoalFail, $comparison);
	}

	/**
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByFfbMatch($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPsgoalPeer::PSGOAL_MATCH_ID, $ffbMatch->getMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatch relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function joinFfbMatch($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatch');
		
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
			$this->addJoinObject($join, 'FfbMatch');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatch relation FfbMatch object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatch($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatch', 'FfbMatchQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
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
	 * @param     FfbPsgoal $ffbPsgoal Object to remove from the list of results
	 *
	 * @return    FfbPsgoalQuery The current query, for fluid interface
	 */
	public function prune($ffbPsgoal = null)
	{
		if ($ffbPsgoal) {
			$this->addUsingAlias(FfbPsgoalPeer::PSGOAL_ID, $ffbPsgoal->getPsgoalId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPsgoalQuery
