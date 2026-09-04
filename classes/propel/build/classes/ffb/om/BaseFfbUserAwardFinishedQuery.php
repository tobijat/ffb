<?php


/**
 * Base class that represents a query for the 'ffb_user_award_finished' table.
 *
 * 
 *
 * @method     FfbUserAwardFinishedQuery orderByUserAwardFinishedId($order = Criteria::ASC) Order by the user_award_finished_id column
 * @method     FfbUserAwardFinishedQuery orderByUserAwardFinishedUserId($order = Criteria::ASC) Order by the user_award_finished_user_id column
 * @method     FfbUserAwardFinishedQuery orderByUserAwardFinishedAwardDefinesId($order = Criteria::ASC) Order by the user_award_finished_award_defines_id column
 * @method     FfbUserAwardFinishedQuery orderByUserAwardFinishedDate($order = Criteria::ASC) Order by the user_award_finished_date column
 *
 * @method     FfbUserAwardFinishedQuery groupByUserAwardFinishedId() Group by the user_award_finished_id column
 * @method     FfbUserAwardFinishedQuery groupByUserAwardFinishedUserId() Group by the user_award_finished_user_id column
 * @method     FfbUserAwardFinishedQuery groupByUserAwardFinishedAwardDefinesId() Group by the user_award_finished_award_defines_id column
 * @method     FfbUserAwardFinishedQuery groupByUserAwardFinishedDate() Group by the user_award_finished_date column
 *
 * @method     FfbUserAwardFinishedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbUserAwardFinishedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbUserAwardFinishedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbUserAwardFinishedQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbUserAwardFinishedQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbUserAwardFinishedQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbUserAwardFinishedQuery leftJoinFfbUserAwardDefines($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserAwardDefines relation
 * @method     FfbUserAwardFinishedQuery rightJoinFfbUserAwardDefines($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserAwardDefines relation
 * @method     FfbUserAwardFinishedQuery innerJoinFfbUserAwardDefines($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserAwardDefines relation
 *
 * @method     FfbUserAwardFinished findOne(?PropelPDO $con = null) Return the first FfbUserAwardFinished matching the query
 * @method     FfbUserAwardFinished findOneOrCreate(?PropelPDO $con = null) Return the first FfbUserAwardFinished matching the query, or a new FfbUserAwardFinished object populated from the query conditions when no match is found
 *
 * @method     FfbUserAwardFinished findOneByUserAwardFinishedId(int $user_award_finished_id) Return the first FfbUserAwardFinished filtered by the user_award_finished_id column
 * @method     FfbUserAwardFinished findOneByUserAwardFinishedUserId(int $user_award_finished_user_id) Return the first FfbUserAwardFinished filtered by the user_award_finished_user_id column
 * @method     FfbUserAwardFinished findOneByUserAwardFinishedAwardDefinesId(int $user_award_finished_award_defines_id) Return the first FfbUserAwardFinished filtered by the user_award_finished_award_defines_id column
 * @method     FfbUserAwardFinished findOneByUserAwardFinishedDate(string $user_award_finished_date) Return the first FfbUserAwardFinished filtered by the user_award_finished_date column
 *
 * @method     array findByUserAwardFinishedId(int $user_award_finished_id) Return FfbUserAwardFinished objects filtered by the user_award_finished_id column
 * @method     array findByUserAwardFinishedUserId(int $user_award_finished_user_id) Return FfbUserAwardFinished objects filtered by the user_award_finished_user_id column
 * @method     array findByUserAwardFinishedAwardDefinesId(int $user_award_finished_award_defines_id) Return FfbUserAwardFinished objects filtered by the user_award_finished_award_defines_id column
 * @method     array findByUserAwardFinishedDate(string $user_award_finished_date) Return FfbUserAwardFinished objects filtered by the user_award_finished_date column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserAwardFinishedQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbUserAwardFinishedQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbUserAwardFinished', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbUserAwardFinishedQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbUserAwardFinishedQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbUserAwardFinishedQuery) {
			return $criteria;
		}
		$query = new FfbUserAwardFinishedQuery();
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
	 * @return    FfbUserAwardFinished|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbUserAwardFinishedPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_award_finished_id column
	 * 
	 * @param     int|array $userAwardFinishedId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByUserAwardFinishedId($userAwardFinishedId = null, $comparison = null)
	{
		if (is_array($userAwardFinishedId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_ID, $userAwardFinishedId, $comparison);
	}

	/**
	 * Filter the query on the user_award_finished_user_id column
	 * 
	 * @param     int|array $userAwardFinishedUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByUserAwardFinishedUserId($userAwardFinishedUserId = null, $comparison = null)
	{
		if (is_array($userAwardFinishedUserId)) {
			$useMinMax = false;
			if (isset($userAwardFinishedUserId['min'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $userAwardFinishedUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardFinishedUserId['max'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $userAwardFinishedUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $userAwardFinishedUserId, $comparison);
	}

	/**
	 * Filter the query on the user_award_finished_award_defines_id column
	 * 
	 * @param     int|array $userAwardFinishedAwardDefinesId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByUserAwardFinishedAwardDefinesId($userAwardFinishedAwardDefinesId = null, $comparison = null)
	{
		if (is_array($userAwardFinishedAwardDefinesId)) {
			$useMinMax = false;
			if (isset($userAwardFinishedAwardDefinesId['min'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $userAwardFinishedAwardDefinesId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardFinishedAwardDefinesId['max'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $userAwardFinishedAwardDefinesId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $userAwardFinishedAwardDefinesId, $comparison);
	}

	/**
	 * Filter the query on the user_award_finished_date column
	 * 
	 * @param     string|array $userAwardFinishedDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByUserAwardFinishedDate($userAwardFinishedDate = null, $comparison = null)
	{
		if (is_array($userAwardFinishedDate)) {
			$useMinMax = false;
			if (isset($userAwardFinishedDate['min'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_DATE, $userAwardFinishedDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardFinishedDate['max'])) {
				$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_DATE, $userAwardFinishedDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_DATE, $userAwardFinishedDate, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbUserAwardDefines object
	 *
	 * @param     FfbUserAwardDefines $ffbUserAwardDefines  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function filterByFfbUserAwardDefines($ffbUserAwardDefines, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_AWARD_DEFINES_ID, $ffbUserAwardDefines->getUserAwardDefinesId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserAwardDefines relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function joinFfbUserAwardDefines($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserAwardDefines');
		
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
			$this->addJoinObject($join, 'FfbUserAwardDefines');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserAwardDefines relation FfbUserAwardDefines object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardDefinesQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserAwardDefinesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserAwardDefines($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserAwardDefines', 'FfbUserAwardDefinesQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbUserAwardFinished $ffbUserAwardFinished Object to remove from the list of results
	 *
	 * @return    FfbUserAwardFinishedQuery The current query, for fluid interface
	 */
	public function prune($ffbUserAwardFinished = null)
	{
		if ($ffbUserAwardFinished) {
			$this->addUsingAlias(FfbUserAwardFinishedPeer::USER_AWARD_FINISHED_ID, $ffbUserAwardFinished->getUserAwardFinishedId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbUserAwardFinishedQuery
