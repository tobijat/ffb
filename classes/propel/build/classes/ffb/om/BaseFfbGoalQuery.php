<?php


/**
 * Base class that represents a query for the 'ffb_goal' table.
 *
 * 
 *
 * @method     FfbGoalQuery orderByGoalId($order = Criteria::ASC) Order by the goal_id column
 * @method     FfbGoalQuery orderByGoalMatchId($order = Criteria::ASC) Order by the goal_match_id column
 * @method     FfbGoalQuery orderByGoalPlayerteamId($order = Criteria::ASC) Order by the goal_playerteam_id column
 * @method     FfbGoalQuery orderByGoalMinute($order = Criteria::ASC) Order by the goal_minute column
 * @method     FfbGoalQuery orderByGoalOwngoal($order = Criteria::ASC) Order by the goal_owngoal column
 * @method     FfbGoalQuery orderByGoalPenalty($order = Criteria::ASC) Order by the goal_penalty column
 * @method     FfbGoalQuery orderByGoalPenaltyshootout($order = Criteria::ASC) Order by the goal_penaltyshootout column
 *
 * @method     FfbGoalQuery groupByGoalId() Group by the goal_id column
 * @method     FfbGoalQuery groupByGoalMatchId() Group by the goal_match_id column
 * @method     FfbGoalQuery groupByGoalPlayerteamId() Group by the goal_playerteam_id column
 * @method     FfbGoalQuery groupByGoalMinute() Group by the goal_minute column
 * @method     FfbGoalQuery groupByGoalOwngoal() Group by the goal_owngoal column
 * @method     FfbGoalQuery groupByGoalPenalty() Group by the goal_penalty column
 * @method     FfbGoalQuery groupByGoalPenaltyshootout() Group by the goal_penaltyshootout column
 *
 * @method     FfbGoalQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbGoalQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbGoalQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbGoalQuery leftJoinFfbMatch($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatch relation
 * @method     FfbGoalQuery rightJoinFfbMatch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatch relation
 * @method     FfbGoalQuery innerJoinFfbMatch($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatch relation
 *
 * @method     FfbGoalQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbGoalQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbGoalQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbGoal findOne(PropelPDO $con = null) Return the first FfbGoal matching the query
 * @method     FfbGoal findOneOrCreate(PropelPDO $con = null) Return the first FfbGoal matching the query, or a new FfbGoal object populated from the query conditions when no match is found
 *
 * @method     FfbGoal findOneByGoalId(int $goal_id) Return the first FfbGoal filtered by the goal_id column
 * @method     FfbGoal findOneByGoalMatchId(int $goal_match_id) Return the first FfbGoal filtered by the goal_match_id column
 * @method     FfbGoal findOneByGoalPlayerteamId(int $goal_playerteam_id) Return the first FfbGoal filtered by the goal_playerteam_id column
 * @method     FfbGoal findOneByGoalMinute(int $goal_minute) Return the first FfbGoal filtered by the goal_minute column
 * @method     FfbGoal findOneByGoalOwngoal(boolean $goal_owngoal) Return the first FfbGoal filtered by the goal_owngoal column
 * @method     FfbGoal findOneByGoalPenalty(boolean $goal_penalty) Return the first FfbGoal filtered by the goal_penalty column
 * @method     FfbGoal findOneByGoalPenaltyshootout(boolean $goal_penaltyshootout) Return the first FfbGoal filtered by the goal_penaltyshootout column
 *
 * @method     array findByGoalId(int $goal_id) Return FfbGoal objects filtered by the goal_id column
 * @method     array findByGoalMatchId(int $goal_match_id) Return FfbGoal objects filtered by the goal_match_id column
 * @method     array findByGoalPlayerteamId(int $goal_playerteam_id) Return FfbGoal objects filtered by the goal_playerteam_id column
 * @method     array findByGoalMinute(int $goal_minute) Return FfbGoal objects filtered by the goal_minute column
 * @method     array findByGoalOwngoal(boolean $goal_owngoal) Return FfbGoal objects filtered by the goal_owngoal column
 * @method     array findByGoalPenalty(boolean $goal_penalty) Return FfbGoal objects filtered by the goal_penalty column
 * @method     array findByGoalPenaltyshootout(boolean $goal_penaltyshootout) Return FfbGoal objects filtered by the goal_penaltyshootout column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbGoalQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbGoalQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbGoal', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbGoalQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbGoalQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbGoalQuery) {
			return $criteria;
		}
		$query = new FfbGoalQuery();
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
	 * @return    FfbGoal|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbGoalPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbGoalPeer::GOAL_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbGoalPeer::GOAL_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the goal_id column
	 * 
	 * @param     int|array $goalId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalId($goalId = null, $comparison = null)
	{
		if (is_array($goalId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_ID, $goalId, $comparison);
	}

	/**
	 * Filter the query on the goal_match_id column
	 * 
	 * @param     int|array $goalMatchId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalMatchId($goalMatchId = null, $comparison = null)
	{
		if (is_array($goalMatchId)) {
			$useMinMax = false;
			if (isset($goalMatchId['min'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_MATCH_ID, $goalMatchId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($goalMatchId['max'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_MATCH_ID, $goalMatchId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_MATCH_ID, $goalMatchId, $comparison);
	}

	/**
	 * Filter the query on the goal_playerteam_id column
	 * 
	 * @param     int|array $goalPlayerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalPlayerteamId($goalPlayerteamId = null, $comparison = null)
	{
		if (is_array($goalPlayerteamId)) {
			$useMinMax = false;
			if (isset($goalPlayerteamId['min'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $goalPlayerteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($goalPlayerteamId['max'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $goalPlayerteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $goalPlayerteamId, $comparison);
	}

	/**
	 * Filter the query on the goal_minute column
	 * 
	 * @param     int|array $goalMinute The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalMinute($goalMinute = null, $comparison = null)
	{
		if (is_array($goalMinute)) {
			$useMinMax = false;
			if (isset($goalMinute['min'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_MINUTE, $goalMinute['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($goalMinute['max'])) {
				$this->addUsingAlias(FfbGoalPeer::GOAL_MINUTE, $goalMinute['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_MINUTE, $goalMinute, $comparison);
	}

	/**
	 * Filter the query on the goal_owngoal column
	 * 
	 * @param     boolean|string $goalOwngoal The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalOwngoal($goalOwngoal = null, $comparison = null)
	{
		if (is_string($goalOwngoal)) {
			$goal_owngoal = in_array(strtolower($goalOwngoal), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_OWNGOAL, $goalOwngoal, $comparison);
	}

	/**
	 * Filter the query on the goal_penalty column
	 * 
	 * @param     boolean|string $goalPenalty The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalPenalty($goalPenalty = null, $comparison = null)
	{
		if (is_string($goalPenalty)) {
			$goal_penalty = in_array(strtolower($goalPenalty), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_PENALTY, $goalPenalty, $comparison);
	}

	/**
	 * Filter the query on the goal_penaltyshootout column
	 * 
	 * @param     boolean|string $goalPenaltyshootout The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByGoalPenaltyshootout($goalPenaltyshootout = null, $comparison = null)
	{
		if (is_string($goalPenaltyshootout)) {
			$goal_penaltyshootout = in_array(strtolower($goalPenaltyshootout), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGoalPeer::GOAL_PENALTYSHOOTOUT, $goalPenaltyshootout, $comparison);
	}

	/**
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByFfbMatch($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGoalPeer::GOAL_MATCH_ID, $ffbMatch->getMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatch relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
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
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
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
	 * @param     FfbGoal $ffbGoal Object to remove from the list of results
	 *
	 * @return    FfbGoalQuery The current query, for fluid interface
	 */
	public function prune($ffbGoal = null)
	{
		if ($ffbGoal) {
			$this->addUsingAlias(FfbGoalPeer::GOAL_ID, $ffbGoal->getGoalId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbGoalQuery
