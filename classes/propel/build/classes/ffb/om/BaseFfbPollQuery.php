<?php


/**
 * Base class that represents a query for the 'ffb_poll' table.
 *
 * 
 *
 * @method     FfbPollQuery orderByPollId($order = Criteria::ASC) Order by the poll_id column
 * @method     FfbPollQuery orderByPollTitle($order = Criteria::ASC) Order by the poll_title column
 * @method     FfbPollQuery orderByPollStart($order = Criteria::ASC) Order by the poll_start column
 * @method     FfbPollQuery orderByPollEnd($order = Criteria::ASC) Order by the poll_end column
 * @method     FfbPollQuery orderByPollGameId($order = Criteria::ASC) Order by the poll_game_id column
 * @method     FfbPollQuery orderByPollLocation($order = Criteria::ASC) Order by the poll_location column
 * @method     FfbPollQuery orderByPollType($order = Criteria::ASC) Order by the poll_type column
 * @method     FfbPollQuery orderByPollVisible($order = Criteria::ASC) Order by the poll_visible column
 *
 * @method     FfbPollQuery groupByPollId() Group by the poll_id column
 * @method     FfbPollQuery groupByPollTitle() Group by the poll_title column
 * @method     FfbPollQuery groupByPollStart() Group by the poll_start column
 * @method     FfbPollQuery groupByPollEnd() Group by the poll_end column
 * @method     FfbPollQuery groupByPollGameId() Group by the poll_game_id column
 * @method     FfbPollQuery groupByPollLocation() Group by the poll_location column
 * @method     FfbPollQuery groupByPollType() Group by the poll_type column
 * @method     FfbPollQuery groupByPollVisible() Group by the poll_visible column
 *
 * @method     FfbPollQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPollQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPollQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPollQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbPollQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbPollQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbPollQuery leftJoinFfbPollResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPollResult relation
 * @method     FfbPollQuery rightJoinFfbPollResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPollResult relation
 * @method     FfbPollQuery innerJoinFfbPollResult($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPollResult relation
 *
 * @method     FfbPollQuery leftJoinFfbPollAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPollAnswer relation
 * @method     FfbPollQuery rightJoinFfbPollAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPollAnswer relation
 * @method     FfbPollQuery innerJoinFfbPollAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPollAnswer relation
 *
 * @method     FfbPoll findOne(?PropelPDO $con = null) Return the first FfbPoll matching the query
 * @method     FfbPoll findOneOrCreate(?PropelPDO $con = null) Return the first FfbPoll matching the query, or a new FfbPoll object populated from the query conditions when no match is found
 *
 * @method     FfbPoll findOneByPollId(int $poll_id) Return the first FfbPoll filtered by the poll_id column
 * @method     FfbPoll findOneByPollTitle(string $poll_title) Return the first FfbPoll filtered by the poll_title column
 * @method     FfbPoll findOneByPollStart(string $poll_start) Return the first FfbPoll filtered by the poll_start column
 * @method     FfbPoll findOneByPollEnd(string $poll_end) Return the first FfbPoll filtered by the poll_end column
 * @method     FfbPoll findOneByPollGameId(int $poll_game_id) Return the first FfbPoll filtered by the poll_game_id column
 * @method     FfbPoll findOneByPollLocation(string $poll_location) Return the first FfbPoll filtered by the poll_location column
 * @method     FfbPoll findOneByPollType(string $poll_type) Return the first FfbPoll filtered by the poll_type column
 * @method     FfbPoll findOneByPollVisible(boolean $poll_visible) Return the first FfbPoll filtered by the poll_visible column
 *
 * @method     array findByPollId(int $poll_id) Return FfbPoll objects filtered by the poll_id column
 * @method     array findByPollTitle(string $poll_title) Return FfbPoll objects filtered by the poll_title column
 * @method     array findByPollStart(string $poll_start) Return FfbPoll objects filtered by the poll_start column
 * @method     array findByPollEnd(string $poll_end) Return FfbPoll objects filtered by the poll_end column
 * @method     array findByPollGameId(int $poll_game_id) Return FfbPoll objects filtered by the poll_game_id column
 * @method     array findByPollLocation(string $poll_location) Return FfbPoll objects filtered by the poll_location column
 * @method     array findByPollType(string $poll_type) Return FfbPoll objects filtered by the poll_type column
 * @method     array findByPollVisible(boolean $poll_visible) Return FfbPoll objects filtered by the poll_visible column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPollQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPollQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPoll', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPollQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPollQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPollQuery) {
			return $criteria;
		}
		$query = new FfbPollQuery();
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
	 * @return    FfbPoll|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPollPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPollPeer::POLL_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPollPeer::POLL_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the poll_id column
	 * 
	 * @param     int|array $pollId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollId($pollId = null, $comparison = null)
	{
		if (is_array($pollId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_ID, $pollId, $comparison);
	}

	/**
	 * Filter the query on the poll_title column
	 * 
	 * @param     string $pollTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollTitle($pollTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($pollTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $pollTitle)) {
				$pollTitle = str_replace('*', '%', $pollTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_TITLE, $pollTitle, $comparison);
	}

	/**
	 * Filter the query on the poll_start column
	 * 
	 * @param     string|array $pollStart The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollStart($pollStart = null, $comparison = null)
	{
		if (is_array($pollStart)) {
			$useMinMax = false;
			if (isset($pollStart['min'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_START, $pollStart['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollStart['max'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_START, $pollStart['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_START, $pollStart, $comparison);
	}

	/**
	 * Filter the query on the poll_end column
	 * 
	 * @param     string|array $pollEnd The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollEnd($pollEnd = null, $comparison = null)
	{
		if (is_array($pollEnd)) {
			$useMinMax = false;
			if (isset($pollEnd['min'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_END, $pollEnd['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollEnd['max'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_END, $pollEnd['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_END, $pollEnd, $comparison);
	}

	/**
	 * Filter the query on the poll_game_id column
	 * 
	 * @param     int|array $pollGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollGameId($pollGameId = null, $comparison = null)
	{
		if (is_array($pollGameId)) {
			$useMinMax = false;
			if (isset($pollGameId['min'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_GAME_ID, $pollGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($pollGameId['max'])) {
				$this->addUsingAlias(FfbPollPeer::POLL_GAME_ID, $pollGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_GAME_ID, $pollGameId, $comparison);
	}

	/**
	 * Filter the query on the poll_location column
	 * 
	 * @param     string $pollLocation The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollLocation($pollLocation = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($pollLocation)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $pollLocation)) {
				$pollLocation = str_replace('*', '%', $pollLocation);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_LOCATION, $pollLocation, $comparison);
	}

	/**
	 * Filter the query on the poll_type column
	 * 
	 * @param     string $pollType The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollType($pollType = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($pollType)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $pollType)) {
				$pollType = str_replace('*', '%', $pollType);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_TYPE, $pollType, $comparison);
	}

	/**
	 * Filter the query on the poll_visible column
	 * 
	 * @param     boolean|string $pollVisible The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByPollVisible($pollVisible = null, $comparison = null)
	{
		if (is_string($pollVisible)) {
			$poll_visible = in_array(strtolower($pollVisible), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbPollPeer::POLL_VISIBLE, $pollVisible, $comparison);
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollPeer::POLL_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPollResult object
	 *
	 * @param     FfbPollResult $ffbPollResult  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByFfbPollResult($ffbPollResult, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollPeer::POLL_ID, $ffbPollResult->getPollResultPollId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPollResult relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPollAnswer object
	 *
	 * @param     FfbPollAnswer $ffbPollAnswer  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function filterByFfbPollAnswer($ffbPollAnswer, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPollPeer::POLL_ID, $ffbPollAnswer->getPollAnswerPollId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPollAnswer relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     FfbPoll $ffbPoll Object to remove from the list of results
	 *
	 * @return    FfbPollQuery The current query, for fluid interface
	 */
	public function prune($ffbPoll = null)
	{
		if ($ffbPoll) {
			$this->addUsingAlias(FfbPollPeer::POLL_ID, $ffbPoll->getPollId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPollQuery
