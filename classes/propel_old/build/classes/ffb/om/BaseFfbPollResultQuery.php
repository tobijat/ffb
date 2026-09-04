<?php


/**
 * Base class that represents a query for the 'ffb_poll_result' table.
 *
 * 
 *
 * @method     FfbPollResultQuery orderByPollResultId($order = Criteria::ASC) Order by the poll_result_id column
 * @method     FfbPollResultQuery orderByPollResultPollId($order = Criteria::ASC) Order by the poll_result_poll_id column
 * @method     FfbPollResultQuery orderByPollResultUserId($order = Criteria::ASC) Order by the poll_result_user_id column
 * @method     FfbPollResultQuery orderByPollResultPollAnswerId($order = Criteria::ASC) Order by the poll_result_poll_answer_id column
 * @method     FfbPollResultQuery orderByPollResultText($order = Criteria::ASC) Order by the poll_result_text column
 *
 * @method     FfbPollResultQuery groupByPollResultId() Group by the poll_result_id column
 * @method     FfbPollResultQuery groupByPollResultPollId() Group by the poll_result_poll_id column
 * @method     FfbPollResultQuery groupByPollResultUserId() Group by the poll_result_user_id column
 * @method     FfbPollResultQuery groupByPollResultPollAnswerId() Group by the poll_result_poll_answer_id column
 * @method     FfbPollResultQuery groupByPollResultText() Group by the poll_result_text column
 *
 * @method     FfbPollResultQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPollResultQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPollResultQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPollResultQuery leftJoinFfbPoll($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPoll relation
 * @method     FfbPollResultQuery rightJoinFfbPoll($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPoll relation
 * @method     FfbPollResultQuery innerJoinFfbPoll($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPoll relation
 *
 * @method     FfbPollResultQuery leftJoinFfbPollAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPollAnswer relation
 * @method     FfbPollResultQuery rightJoinFfbPollAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPollAnswer relation
 * @method     FfbPollResultQuery innerJoinFfbPollAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPollAnswer relation
 *
 * @method     FfbPollResultQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbPollResultQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbPollResultQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbPollResult findOne(PropelPDO $con = null) Return the first FfbPollResult matching the query
 * @method     FfbPollResult findOneOrCreate(PropelPDO $con = null) Return the first FfbPollResult matching the query, or a new FfbPollResult object populated from the query conditions when no match is found
 *
 * @method     FfbPollResult findOneByPollResultId(int $poll_result_id) Return the first FfbPollResult filtered by the poll_result_id column
 * @method     FfbPollResult findOneByPollResultPollId(int $poll_result_poll_id) Return the first FfbPollResult filtered by the poll_result_poll_id column
 * @method     FfbPollResult findOneByPollResultUserId(int $poll_result_user_id) Return the first FfbPollResult filtered by the poll_result_user_id column
 * @method     FfbPollResult findOneByPollResultPollAnswerId(int $poll_result_poll_answer_id) Return the first FfbPollResult filtered by the poll_result_poll_answer_id column
 * @method     FfbPollResult findOneByPollResultText(string $poll_result_text) Return the first FfbPollResult filtered by the poll_result_text column
 *
 * @method     array findByPollResultId(int $poll_result_id) Return FfbPollResult objects filtered by the poll_result_id column
 * @method     array findByPollResultPollId(int $poll_result_poll_id) Return FfbPollResult objects filtered by the poll_result_poll_id column
 * @method     array findByPollResultUserId(int $poll_result_user_id) Return FfbPollResult objects filtered by the poll_result_user_id column
 * @method     array findByPollResultPollAnswerId(int $poll_result_poll_answer_id) Return FfbPollResult objects filtered by the poll_result_poll_answer_id column
 * @method     array findByPollResultText(string $poll_result_text) Return FfbPollResult objects filtered by the poll_result_text column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPollResultQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPollResultQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPollResult', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPollResultQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPollResultQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPollResultQuery) {
			return $criteria;
		}
		$query = new FfbPollResultQuery();
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
	 * @return    FfbPollResult|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPollResultPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the poll_result_id column
	 * 
	 * @param     int|array $pollResultId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPollResultId($pollResultId = null, $comparison = null)
	{
		if (is_array($pollResultId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_ID, $pollResultId, $comparison);
	}

	/**
	 * Filter the query on the poll_result_poll_id column
	 * 
	 * @param     int|array $pollResultPollId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPollResultPollId($pollResultPollId = null, $comparison = null)
	{
		if (is_array($pollResultPollId)) {
			$useMinMax = false;
			if (isset($pollResultPollId['min'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ID, $pollResultPollId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollResultPollId['max'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ID, $pollResultPollId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ID, $pollResultPollId, $comparison);
	}

	/**
	 * Filter the query on the poll_result_user_id column
	 * 
	 * @param     int|array $pollResultUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPollResultUserId($pollResultUserId = null, $comparison = null)
	{
		if (is_array($pollResultUserId)) {
			$useMinMax = false;
			if (isset($pollResultUserId['min'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_USER_ID, $pollResultUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollResultUserId['max'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_USER_ID, $pollResultUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_USER_ID, $pollResultUserId, $comparison);
	}

	/**
	 * Filter the query on the poll_result_poll_answer_id column
	 * 
	 * @param     int|array $pollResultPollAnswerId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPollResultPollAnswerId($pollResultPollAnswerId = null, $comparison = null)
	{
		if (is_array($pollResultPollAnswerId)) {
			$useMinMax = false;
			if (isset($pollResultPollAnswerId['min'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $pollResultPollAnswerId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollResultPollAnswerId['max'])) {
				$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $pollResultPollAnswerId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $pollResultPollAnswerId, $comparison);
	}

	/**
	 * Filter the query on the poll_result_text column
	 * 
	 * @param     string $pollResultText The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByPollResultText($pollResultText = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($pollResultText)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $pollResultText)) {
				$pollResultText = str_replace('*', '%', $pollResultText);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_TEXT, $pollResultText, $comparison);
	}

	/**
	 * Filter the query by a related FfbPoll object
	 *
	 * @param     FfbPoll $ffbPoll  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByFfbPoll($ffbPoll, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ID, $ffbPoll->getPollId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPoll relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function joinFfbPoll($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPoll');
		
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
			$this->addJoinObject($join, 'FfbPoll');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPoll relation FfbPoll object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPollQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPoll($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPoll', 'FfbPollQuery');
	}

	/**
	 * Filter the query by a related FfbPollAnswer object
	 *
	 * @param     FfbPollAnswer $ffbPollAnswer  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByFfbPollAnswer($ffbPollAnswer, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollResultPeer::POLL_RESULT_POLL_ANSWER_ID, $ffbPollAnswer->getPollAnswerId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPollAnswer relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function joinFfbPollAnswer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPollAnswer');
		
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
			$this->addJoinObject($join, 'FfbPollAnswer');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPollAnswer relation FfbPollAnswer object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollAnswerQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPollAnswerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPollAnswer($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPollAnswer', 'FfbPollAnswerQuery');
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollResultPeer::POLL_RESULT_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     FfbPollResult $ffbPollResult Object to remove from the list of results
	 *
	 * @return    FfbPollResultQuery The current query, for fluid interface
	 */
	public function prune($ffbPollResult = null)
	{
		if ($ffbPollResult) {
			$this->addUsingAlias(FfbPollResultPeer::POLL_RESULT_ID, $ffbPollResult->getPollResultId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPollResultQuery
