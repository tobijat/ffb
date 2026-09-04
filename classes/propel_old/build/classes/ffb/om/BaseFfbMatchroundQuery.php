<?php


/**
 * Base class that represents a query for the 'ffb_matchround' table.
 *
 * 
 *
 * @method     FfbMatchroundQuery orderByMatchroundId($order = Criteria::ASC) Order by the matchround_id column
 * @method     FfbMatchroundQuery orderByMatchroundGameId($order = Criteria::ASC) Order by the matchround_game_id column
 * @method     FfbMatchroundQuery orderByMatchroundTitle($order = Criteria::ASC) Order by the matchround_title column
 * @method     FfbMatchroundQuery orderByMatchroundStartdate($order = Criteria::ASC) Order by the matchround_startdate column
 * @method     FfbMatchroundQuery orderByMatchroundEnddate($order = Criteria::ASC) Order by the matchround_enddate column
 * @method     FfbMatchroundQuery orderByMatchroundStatus($order = Criteria::ASC) Order by the matchround_status column
 * @method     FfbMatchroundQuery orderByMatchroundCredits($order = Criteria::ASC) Order by the matchround_credits column
 * @method     FfbMatchroundQuery orderByMatchroundMaxPlayersFromTeam($order = Criteria::ASC) Order by the matchround_max_players_from_team column
 *
 * @method     FfbMatchroundQuery groupByMatchroundId() Group by the matchround_id column
 * @method     FfbMatchroundQuery groupByMatchroundGameId() Group by the matchround_game_id column
 * @method     FfbMatchroundQuery groupByMatchroundTitle() Group by the matchround_title column
 * @method     FfbMatchroundQuery groupByMatchroundStartdate() Group by the matchround_startdate column
 * @method     FfbMatchroundQuery groupByMatchroundEnddate() Group by the matchround_enddate column
 * @method     FfbMatchroundQuery groupByMatchroundStatus() Group by the matchround_status column
 * @method     FfbMatchroundQuery groupByMatchroundCredits() Group by the matchround_credits column
 * @method     FfbMatchroundQuery groupByMatchroundMaxPlayersFromTeam() Group by the matchround_max_players_from_team column
 *
 * @method     FfbMatchroundQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbMatchroundQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbMatchroundQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbMatchroundQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbMatchroundQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbMatchroundQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbMatchroundQuery leftJoinFfbComments($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbComments relation
 * @method     FfbMatchroundQuery rightJoinFfbComments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbComments relation
 * @method     FfbMatchroundQuery innerJoinFfbComments($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbComments relation
 *
 * @method     FfbMatchroundQuery leftJoinFfbPlayerprice($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerprice relation
 * @method     FfbMatchroundQuery rightJoinFfbPlayerprice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerprice relation
 * @method     FfbMatchroundQuery innerJoinFfbPlayerprice($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerprice relation
 *
 * @method     FfbMatchroundQuery leftJoinFfbMatch($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatch relation
 * @method     FfbMatchroundQuery rightJoinFfbMatch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatch relation
 * @method     FfbMatchroundQuery innerJoinFfbMatch($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatch relation
 *
 * @method     FfbMatchroundQuery leftJoinFfbPlayerstats($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbMatchroundQuery rightJoinFfbPlayerstats($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbMatchroundQuery innerJoinFfbPlayerstats($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerstats relation
 *
 * @method     FfbMatchroundQuery leftJoinFfbUserteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteam relation
 * @method     FfbMatchroundQuery rightJoinFfbUserteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteam relation
 * @method     FfbMatchroundQuery innerJoinFfbUserteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteam relation
 *
 * @method     FfbMatchround findOne(PropelPDO $con = null) Return the first FfbMatchround matching the query
 * @method     FfbMatchround findOneOrCreate(PropelPDO $con = null) Return the first FfbMatchround matching the query, or a new FfbMatchround object populated from the query conditions when no match is found
 *
 * @method     FfbMatchround findOneByMatchroundId(int $matchround_id) Return the first FfbMatchround filtered by the matchround_id column
 * @method     FfbMatchround findOneByMatchroundGameId(int $matchround_game_id) Return the first FfbMatchround filtered by the matchround_game_id column
 * @method     FfbMatchround findOneByMatchroundTitle(string $matchround_title) Return the first FfbMatchround filtered by the matchround_title column
 * @method     FfbMatchround findOneByMatchroundStartdate(string $matchround_startdate) Return the first FfbMatchround filtered by the matchround_startdate column
 * @method     FfbMatchround findOneByMatchroundEnddate(string $matchround_enddate) Return the first FfbMatchround filtered by the matchround_enddate column
 * @method     FfbMatchround findOneByMatchroundStatus(int $matchround_status) Return the first FfbMatchround filtered by the matchround_status column
 * @method     FfbMatchround findOneByMatchroundCredits(double $matchround_credits) Return the first FfbMatchround filtered by the matchround_credits column
 * @method     FfbMatchround findOneByMatchroundMaxPlayersFromTeam(int $matchround_max_players_from_team) Return the first FfbMatchround filtered by the matchround_max_players_from_team column
 *
 * @method     array findByMatchroundId(int $matchround_id) Return FfbMatchround objects filtered by the matchround_id column
 * @method     array findByMatchroundGameId(int $matchround_game_id) Return FfbMatchround objects filtered by the matchround_game_id column
 * @method     array findByMatchroundTitle(string $matchround_title) Return FfbMatchround objects filtered by the matchround_title column
 * @method     array findByMatchroundStartdate(string $matchround_startdate) Return FfbMatchround objects filtered by the matchround_startdate column
 * @method     array findByMatchroundEnddate(string $matchround_enddate) Return FfbMatchround objects filtered by the matchround_enddate column
 * @method     array findByMatchroundStatus(int $matchround_status) Return FfbMatchround objects filtered by the matchround_status column
 * @method     array findByMatchroundCredits(double $matchround_credits) Return FfbMatchround objects filtered by the matchround_credits column
 * @method     array findByMatchroundMaxPlayersFromTeam(int $matchround_max_players_from_team) Return FfbMatchround objects filtered by the matchround_max_players_from_team column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbMatchroundQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbMatchroundQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbMatchround', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbMatchroundQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbMatchroundQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbMatchroundQuery) {
			return $criteria;
		}
		$query = new FfbMatchroundQuery();
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
	 * @return    FfbMatchround|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbMatchroundPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the matchround_id column
	 * 
	 * @param     int|array $matchroundId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundId($matchroundId = null, $comparison = null)
	{
		if (is_array($matchroundId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $matchroundId, $comparison);
	}

	/**
	 * Filter the query on the matchround_game_id column
	 * 
	 * @param     int|array $matchroundGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundGameId($matchroundGameId = null, $comparison = null)
	{
		if (is_array($matchroundGameId)) {
			$useMinMax = false;
			if (isset($matchroundGameId['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_GAME_ID, $matchroundGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundGameId['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_GAME_ID, $matchroundGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_GAME_ID, $matchroundGameId, $comparison);
	}

	/**
	 * Filter the query on the matchround_title column
	 * 
	 * @param     string $matchroundTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundTitle($matchroundTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchroundTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchroundTitle)) {
				$matchroundTitle = str_replace('*', '%', $matchroundTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_TITLE, $matchroundTitle, $comparison);
	}

	/**
	 * Filter the query on the matchround_startdate column
	 * 
	 * @param     string|array $matchroundStartdate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundStartdate($matchroundStartdate = null, $comparison = null)
	{
		if (is_array($matchroundStartdate)) {
			$useMinMax = false;
			if (isset($matchroundStartdate['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STARTDATE, $matchroundStartdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundStartdate['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STARTDATE, $matchroundStartdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STARTDATE, $matchroundStartdate, $comparison);
	}

	/**
	 * Filter the query on the matchround_enddate column
	 * 
	 * @param     string|array $matchroundEnddate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundEnddate($matchroundEnddate = null, $comparison = null)
	{
		if (is_array($matchroundEnddate)) {
			$useMinMax = false;
			if (isset($matchroundEnddate['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ENDDATE, $matchroundEnddate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundEnddate['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ENDDATE, $matchroundEnddate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ENDDATE, $matchroundEnddate, $comparison);
	}

	/**
	 * Filter the query on the matchround_status column
	 * 
	 * @param     int|array $matchroundStatus The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundStatus($matchroundStatus = null, $comparison = null)
	{
		if (is_array($matchroundStatus)) {
			$useMinMax = false;
			if (isset($matchroundStatus['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STATUS, $matchroundStatus['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundStatus['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STATUS, $matchroundStatus['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_STATUS, $matchroundStatus, $comparison);
	}

	/**
	 * Filter the query on the matchround_credits column
	 * 
	 * @param     double|array $matchroundCredits The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundCredits($matchroundCredits = null, $comparison = null)
	{
		if (is_array($matchroundCredits)) {
			$useMinMax = false;
			if (isset($matchroundCredits['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_CREDITS, $matchroundCredits['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundCredits['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_CREDITS, $matchroundCredits['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_CREDITS, $matchroundCredits, $comparison);
	}

	/**
	 * Filter the query on the matchround_max_players_from_team column
	 * 
	 * @param     int|array $matchroundMaxPlayersFromTeam The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByMatchroundMaxPlayersFromTeam($matchroundMaxPlayersFromTeam = null, $comparison = null)
	{
		if (is_array($matchroundMaxPlayersFromTeam)) {
			$useMinMax = false;
			if (isset($matchroundMaxPlayersFromTeam['min'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM, $matchroundMaxPlayersFromTeam['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchroundMaxPlayersFromTeam['max'])) {
				$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM, $matchroundMaxPlayersFromTeam['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_MAX_PLAYERS_FROM_TEAM, $matchroundMaxPlayersFromTeam, $comparison);
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbComments object
	 *
	 * @param     FfbComments $ffbComments  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbComments($ffbComments, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbComments->getCommentsMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbComments relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function joinFfbComments($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbComments');
		
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
			$this->addJoinObject($join, 'FfbComments');
		}
		
		return $this;
	}

	/**
	 * Use the FfbComments relation FfbComments object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbCommentsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbCommentsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbComments($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbComments', 'FfbCommentsQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerprice object
	 *
	 * @param     FfbPlayerprice $ffbPlayerprice  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerprice($ffbPlayerprice, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbPlayerprice->getPlayerpriceMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerprice relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerprice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerprice');
		
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
			$this->addJoinObject($join, 'FfbPlayerprice');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerprice relation FfbPlayerprice object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerpriceQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerpriceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerprice($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerprice', 'FfbPlayerpriceQuery');
	}

	/**
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbMatch($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbMatch->getMatchRound(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatch relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPlayerstats object
	 *
	 * @param     FfbPlayerstats $ffbPlayerstats  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerstats($ffbPlayerstats, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbPlayerstats->getPlayerstatsMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerstats relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerstats($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerstats');
		
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
			$this->addJoinObject($join, 'FfbPlayerstats');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerstats relation FfbPlayerstats object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerstatsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerstatsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerstats($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerstats', 'FfbPlayerstatsQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteam($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbUserteam->getUserteamMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function joinFfbUserteam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteam');
		
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
			$this->addJoinObject($join, 'FfbUserteam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteam relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteam', 'FfbUserteamQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbMatchround $ffbMatchround Object to remove from the list of results
	 *
	 * @return    FfbMatchroundQuery The current query, for fluid interface
	 */
	public function prune($ffbMatchround = null)
	{
		if ($ffbMatchround) {
			$this->addUsingAlias(FfbMatchroundPeer::MATCHROUND_ID, $ffbMatchround->getMatchroundId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbMatchroundQuery
