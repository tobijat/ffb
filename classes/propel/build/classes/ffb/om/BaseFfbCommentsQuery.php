<?php


/**
 * Base class that represents a query for the 'ffb_comments' table.
 *
 * 
 *
 * @method     FfbCommentsQuery orderByCommentsId($order = Criteria::ASC) Order by the comments_id column
 * @method     FfbCommentsQuery orderByCommentsUserId($order = Criteria::ASC) Order by the comments_user_id column
 * @method     FfbCommentsQuery orderByCommentsGameId($order = Criteria::ASC) Order by the comments_game_id column
 * @method     FfbCommentsQuery orderByCommentsMatchroundId($order = Criteria::ASC) Order by the comments_matchround_id column
 * @method     FfbCommentsQuery orderByCommentsLocation($order = Criteria::ASC) Order by the comments_location column
 * @method     FfbCommentsQuery orderByCommentsText($order = Criteria::ASC) Order by the comments_text column
 * @method     FfbCommentsQuery orderByCommentsDate($order = Criteria::ASC) Order by the comments_date column
 *
 * @method     FfbCommentsQuery groupByCommentsId() Group by the comments_id column
 * @method     FfbCommentsQuery groupByCommentsUserId() Group by the comments_user_id column
 * @method     FfbCommentsQuery groupByCommentsGameId() Group by the comments_game_id column
 * @method     FfbCommentsQuery groupByCommentsMatchroundId() Group by the comments_matchround_id column
 * @method     FfbCommentsQuery groupByCommentsLocation() Group by the comments_location column
 * @method     FfbCommentsQuery groupByCommentsText() Group by the comments_text column
 * @method     FfbCommentsQuery groupByCommentsDate() Group by the comments_date column
 *
 * @method     FfbCommentsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbCommentsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbCommentsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbCommentsQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbCommentsQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbCommentsQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbCommentsQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbCommentsQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbCommentsQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbCommentsQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbCommentsQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbCommentsQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbComments findOne(?PropelPDO $con = null) Return the first FfbComments matching the query
 * @method     FfbComments findOneOrCreate(?PropelPDO $con = null) Return the first FfbComments matching the query, or a new FfbComments object populated from the query conditions when no match is found
 *
 * @method     FfbComments findOneByCommentsId(int $comments_id) Return the first FfbComments filtered by the comments_id column
 * @method     FfbComments findOneByCommentsUserId(int $comments_user_id) Return the first FfbComments filtered by the comments_user_id column
 * @method     FfbComments findOneByCommentsGameId(int $comments_game_id) Return the first FfbComments filtered by the comments_game_id column
 * @method     FfbComments findOneByCommentsMatchroundId(int $comments_matchround_id) Return the first FfbComments filtered by the comments_matchround_id column
 * @method     FfbComments findOneByCommentsLocation(string $comments_location) Return the first FfbComments filtered by the comments_location column
 * @method     FfbComments findOneByCommentsText(string $comments_text) Return the first FfbComments filtered by the comments_text column
 * @method     FfbComments findOneByCommentsDate(string $comments_date) Return the first FfbComments filtered by the comments_date column
 *
 * @method     array findByCommentsId(int $comments_id) Return FfbComments objects filtered by the comments_id column
 * @method     array findByCommentsUserId(int $comments_user_id) Return FfbComments objects filtered by the comments_user_id column
 * @method     array findByCommentsGameId(int $comments_game_id) Return FfbComments objects filtered by the comments_game_id column
 * @method     array findByCommentsMatchroundId(int $comments_matchround_id) Return FfbComments objects filtered by the comments_matchround_id column
 * @method     array findByCommentsLocation(string $comments_location) Return FfbComments objects filtered by the comments_location column
 * @method     array findByCommentsText(string $comments_text) Return FfbComments objects filtered by the comments_text column
 * @method     array findByCommentsDate(string $comments_date) Return FfbComments objects filtered by the comments_date column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbCommentsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbCommentsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbComments', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbCommentsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbCommentsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbCommentsQuery) {
			return $criteria;
		}
		$query = new FfbCommentsQuery();
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
	 * @return    FfbComments|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbCommentsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the comments_id column
	 * 
	 * @param     int|array $commentsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsId($commentsId = null, $comparison = null)
	{
		if (is_array($commentsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_ID, $commentsId, $comparison);
	}

	/**
	 * Filter the query on the comments_user_id column
	 * 
	 * @param     int|array $commentsUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsUserId($commentsUserId = null, $comparison = null)
	{
		if (is_array($commentsUserId)) {
			$useMinMax = false;
			if (isset($commentsUserId['min'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_USER_ID, $commentsUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($commentsUserId['max'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_USER_ID, $commentsUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_USER_ID, $commentsUserId, $comparison);
	}

	/**
	 * Filter the query on the comments_game_id column
	 * 
	 * @param     int|array $commentsGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsGameId($commentsGameId = null, $comparison = null)
	{
		if (is_array($commentsGameId)) {
			$useMinMax = false;
			if (isset($commentsGameId['min'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_GAME_ID, $commentsGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($commentsGameId['max'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_GAME_ID, $commentsGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_GAME_ID, $commentsGameId, $comparison);
	}

	/**
	 * Filter the query on the comments_matchround_id column
	 * 
	 * @param     int|array $commentsMatchroundId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsMatchroundId($commentsMatchroundId = null, $comparison = null)
	{
		if (is_array($commentsMatchroundId)) {
			$useMinMax = false;
			if (isset($commentsMatchroundId['min'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $commentsMatchroundId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($commentsMatchroundId['max'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $commentsMatchroundId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $commentsMatchroundId, $comparison);
	}

	/**
	 * Filter the query on the comments_location column
	 * 
	 * @param     string $commentsLocation The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsLocation($commentsLocation = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($commentsLocation)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $commentsLocation)) {
				$commentsLocation = str_replace('*', '%', $commentsLocation);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_LOCATION, $commentsLocation, $comparison);
	}

	/**
	 * Filter the query on the comments_text column
	 * 
	 * @param     string $commentsText The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsText($commentsText = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($commentsText)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $commentsText)) {
				$commentsText = str_replace('*', '%', $commentsText);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_TEXT, $commentsText, $comparison);
	}

	/**
	 * Filter the query on the comments_date column
	 * 
	 * @param     string|array $commentsDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByCommentsDate($commentsDate = null, $comparison = null)
	{
		if (is_array($commentsDate)) {
			$useMinMax = false;
			if (isset($commentsDate['min'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_DATE, $commentsDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($commentsDate['max'])) {
				$this->addUsingAlias(FfbCommentsPeer::COMMENTS_DATE, $commentsDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCommentsPeer::COMMENTS_DATE, $commentsDate, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbCommentsPeer::COMMENTS_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
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
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbCommentsPeer::COMMENTS_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbCommentsPeer::COMMENTS_MATCHROUND_ID, $ffbMatchround->getMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function joinFfbMatchround($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatchround');
		
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
			$this->addJoinObject($join, 'FfbMatchround');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatchround relation FfbMatchround object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchroundQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatchround($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatchround', 'FfbMatchroundQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbComments $ffbComments Object to remove from the list of results
	 *
	 * @return    FfbCommentsQuery The current query, for fluid interface
	 */
	public function prune($ffbComments = null)
	{
		if ($ffbComments) {
			$this->addUsingAlias(FfbCommentsPeer::COMMENTS_ID, $ffbComments->getCommentsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbCommentsQuery
