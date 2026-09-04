<?php


/**
 * Base class that represents a query for the 'ffb_poll_answer' table.
 *
 * 
 *
 * @method     FfbPollAnswerQuery orderByPollAnswerId($order = Criteria::ASC) Order by the poll_answer_id column
 * @method     FfbPollAnswerQuery orderByPollAnswerPollId($order = Criteria::ASC) Order by the poll_answer_poll_id column
 * @method     FfbPollAnswerQuery orderByPollAnswerTitle($order = Criteria::ASC) Order by the poll_answer_title column
 * @method     FfbPollAnswerQuery orderByPollAnswerCount($order = Criteria::ASC) Order by the poll_answer_count column
 *
 * @method     FfbPollAnswerQuery groupByPollAnswerId() Group by the poll_answer_id column
 * @method     FfbPollAnswerQuery groupByPollAnswerPollId() Group by the poll_answer_poll_id column
 * @method     FfbPollAnswerQuery groupByPollAnswerTitle() Group by the poll_answer_title column
 * @method     FfbPollAnswerQuery groupByPollAnswerCount() Group by the poll_answer_count column
 *
 * @method     FfbPollAnswerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPollAnswerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPollAnswerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPollAnswerQuery leftJoinFfbPoll($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPoll relation
 * @method     FfbPollAnswerQuery rightJoinFfbPoll($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPoll relation
 * @method     FfbPollAnswerQuery innerJoinFfbPoll($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPoll relation
 *
 * @method     FfbPollAnswerQuery leftJoinFfbPollResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPollResult relation
 * @method     FfbPollAnswerQuery rightJoinFfbPollResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPollResult relation
 * @method     FfbPollAnswerQuery innerJoinFfbPollResult($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPollResult relation
 *
 * @method     FfbPollAnswer findOne(PropelPDO $con = null) Return the first FfbPollAnswer matching the query
 * @method     FfbPollAnswer findOneOrCreate(PropelPDO $con = null) Return the first FfbPollAnswer matching the query, or a new FfbPollAnswer object populated from the query conditions when no match is found
 *
 * @method     FfbPollAnswer findOneByPollAnswerId(int $poll_answer_id) Return the first FfbPollAnswer filtered by the poll_answer_id column
 * @method     FfbPollAnswer findOneByPollAnswerPollId(int $poll_answer_poll_id) Return the first FfbPollAnswer filtered by the poll_answer_poll_id column
 * @method     FfbPollAnswer findOneByPollAnswerTitle(string $poll_answer_title) Return the first FfbPollAnswer filtered by the poll_answer_title column
 * @method     FfbPollAnswer findOneByPollAnswerCount(int $poll_answer_count) Return the first FfbPollAnswer filtered by the poll_answer_count column
 *
 * @method     array findByPollAnswerId(int $poll_answer_id) Return FfbPollAnswer objects filtered by the poll_answer_id column
 * @method     array findByPollAnswerPollId(int $poll_answer_poll_id) Return FfbPollAnswer objects filtered by the poll_answer_poll_id column
 * @method     array findByPollAnswerTitle(string $poll_answer_title) Return FfbPollAnswer objects filtered by the poll_answer_title column
 * @method     array findByPollAnswerCount(int $poll_answer_count) Return FfbPollAnswer objects filtered by the poll_answer_count column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPollAnswerQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPollAnswerQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPollAnswer', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPollAnswerQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPollAnswerQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPollAnswerQuery) {
			return $criteria;
		}
		$query = new FfbPollAnswerQuery();
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
	 * @return    FfbPollAnswer|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPollAnswerPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the poll_answer_id column
	 * 
	 * @param     int|array $pollAnswerId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPollAnswerId($pollAnswerId = null, $comparison = null)
	{
		if (is_array($pollAnswerId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_ID, $pollAnswerId, $comparison);
	}

	/**
	 * Filter the query on the poll_answer_poll_id column
	 * 
	 * @param     int|array $pollAnswerPollId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPollAnswerPollId($pollAnswerPollId = null, $comparison = null)
	{
		if (is_array($pollAnswerPollId)) {
			$useMinMax = false;
			if (isset($pollAnswerPollId['min'])) {
				$this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $pollAnswerPollId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollAnswerPollId['max'])) {
				$this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $pollAnswerPollId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $pollAnswerPollId, $comparison);
	}

	/**
	 * Filter the query on the poll_answer_title column
	 * 
	 * @param     string $pollAnswerTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPollAnswerTitle($pollAnswerTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($pollAnswerTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $pollAnswerTitle)) {
				$pollAnswerTitle = str_replace('*', '%', $pollAnswerTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_TITLE, $pollAnswerTitle, $comparison);
	}

	/**
	 * Filter the query on the poll_answer_count column
	 * 
	 * @param     int|array $pollAnswerCount The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByPollAnswerCount($pollAnswerCount = null, $comparison = null)
	{
		if (is_array($pollAnswerCount)) {
			$useMinMax = false;
			if (isset($pollAnswerCount['min'])) {
				$this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_COUNT, $pollAnswerCount['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollAnswerCount['max'])) {
				$this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_COUNT, $pollAnswerCount['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_COUNT, $pollAnswerCount, $comparison);
	}

	/**
	 * Filter the query by a related FfbPoll object
	 *
	 * @param     FfbPoll $ffbPoll  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByFfbPoll($ffbPoll, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_POLL_ID, $ffbPoll->getPollId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPoll relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPollResult object
	 *
	 * @param     FfbPollResult $ffbPollResult  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function filterByFfbPollResult($ffbPollResult, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_ID, $ffbPollResult->getPollResultPollAnswerId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPollResult relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function joinFfbPollResult($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPollResult');
		
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
			$this->addJoinObject($join, 'FfbPollResult');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPollResult relation FfbPollResult object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollResultQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPollResultQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPollResult($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPollResult', 'FfbPollResultQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbPollAnswer $ffbPollAnswer Object to remove from the list of results
	 *
	 * @return    FfbPollAnswerQuery The current query, for fluid interface
	 */
	public function prune($ffbPollAnswer = null)
	{
		if ($ffbPollAnswer) {
			$this->addUsingAlias(FfbPollAnswerPeer::POLL_ANSWER_ID, $ffbPollAnswer->getPollAnswerId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPollAnswerQuery
