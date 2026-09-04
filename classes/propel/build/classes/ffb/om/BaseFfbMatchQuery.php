<?php


/**
 * Base class that represents a query for the 'ffb_match' table.
 *
 * 
 *
 * @method     FfbMatchQuery orderByMatchId($order = Criteria::ASC) Order by the match_id column
 * @method     FfbMatchQuery orderByMatchRound($order = Criteria::ASC) Order by the match_round column
 * @method     FfbMatchQuery orderByMatchHometeamId($order = Criteria::ASC) Order by the match_hometeam_id column
 * @method     FfbMatchQuery orderByMatchGuestteamId($order = Criteria::ASC) Order by the match_guestteam_id column
 * @method     FfbMatchQuery orderByMatchHomescore($order = Criteria::ASC) Order by the match_homescore column
 * @method     FfbMatchQuery orderByMatchGuestscore($order = Criteria::ASC) Order by the match_guestscore column
 * @method     FfbMatchQuery orderByMatchHomescorePenalty($order = Criteria::ASC) Order by the match_homescore_penalty column
 * @method     FfbMatchQuery orderByMatchGuestscorePenalty($order = Criteria::ASC) Order by the match_guestscore_penalty column
 * @method     FfbMatchQuery orderByMatchDate($order = Criteria::ASC) Order by the match_date column
 * @method     FfbMatchQuery orderByMatchMinutes($order = Criteria::ASC) Order by the match_minutes column
 * @method     FfbMatchQuery orderByMatchStatus($order = Criteria::ASC) Order by the match_status column
 * @method     FfbMatchQuery orderByMatchUrl($order = Criteria::ASC) Order by the match_url column
 *
 * @method     FfbMatchQuery groupByMatchId() Group by the match_id column
 * @method     FfbMatchQuery groupByMatchRound() Group by the match_round column
 * @method     FfbMatchQuery groupByMatchHometeamId() Group by the match_hometeam_id column
 * @method     FfbMatchQuery groupByMatchGuestteamId() Group by the match_guestteam_id column
 * @method     FfbMatchQuery groupByMatchHomescore() Group by the match_homescore column
 * @method     FfbMatchQuery groupByMatchGuestscore() Group by the match_guestscore column
 * @method     FfbMatchQuery groupByMatchHomescorePenalty() Group by the match_homescore_penalty column
 * @method     FfbMatchQuery groupByMatchGuestscorePenalty() Group by the match_guestscore_penalty column
 * @method     FfbMatchQuery groupByMatchDate() Group by the match_date column
 * @method     FfbMatchQuery groupByMatchMinutes() Group by the match_minutes column
 * @method     FfbMatchQuery groupByMatchStatus() Group by the match_status column
 * @method     FfbMatchQuery groupByMatchUrl() Group by the match_url column
 *
 * @method     FfbMatchQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbMatchQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbMatchQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbMatchQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbMatchQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbMatchQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbMatchQuery leftJoinFfbTeamRelatedByMatchHometeamId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeamRelatedByMatchHometeamId relation
 * @method     FfbMatchQuery rightJoinFfbTeamRelatedByMatchHometeamId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeamRelatedByMatchHometeamId relation
 * @method     FfbMatchQuery innerJoinFfbTeamRelatedByMatchHometeamId($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeamRelatedByMatchHometeamId relation
 *
 * @method     FfbMatchQuery leftJoinFfbTeamRelatedByMatchGuestteamId($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeamRelatedByMatchGuestteamId relation
 * @method     FfbMatchQuery rightJoinFfbTeamRelatedByMatchGuestteamId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeamRelatedByMatchGuestteamId relation
 * @method     FfbMatchQuery innerJoinFfbTeamRelatedByMatchGuestteamId($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeamRelatedByMatchGuestteamId relation
 *
 * @method     FfbMatchQuery leftJoinFfbGoal($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGoal relation
 * @method     FfbMatchQuery rightJoinFfbGoal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGoal relation
 * @method     FfbMatchQuery innerJoinFfbGoal($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGoal relation
 *
 * @method     FfbMatchQuery leftJoinFfbPsgoal($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPsgoal relation
 * @method     FfbMatchQuery rightJoinFfbPsgoal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPsgoal relation
 * @method     FfbMatchQuery innerJoinFfbPsgoal($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPsgoal relation
 *
 * @method     FfbMatchQuery leftJoinFfbPlayerstats($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbMatchQuery rightJoinFfbPlayerstats($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbMatchQuery innerJoinFfbPlayerstats($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerstats relation
 *
 * @method     FfbMatch findOne(?PropelPDO $con = null) Return the first FfbMatch matching the query
 * @method     FfbMatch findOneOrCreate(?PropelPDO $con = null) Return the first FfbMatch matching the query, or a new FfbMatch object populated from the query conditions when no match is found
 *
 * @method     FfbMatch findOneByMatchId(int $match_id) Return the first FfbMatch filtered by the match_id column
 * @method     FfbMatch findOneByMatchRound(int $match_round) Return the first FfbMatch filtered by the match_round column
 * @method     FfbMatch findOneByMatchHometeamId(int $match_hometeam_id) Return the first FfbMatch filtered by the match_hometeam_id column
 * @method     FfbMatch findOneByMatchGuestteamId(int $match_guestteam_id) Return the first FfbMatch filtered by the match_guestteam_id column
 * @method     FfbMatch findOneByMatchHomescore(string $match_homescore) Return the first FfbMatch filtered by the match_homescore column
 * @method     FfbMatch findOneByMatchGuestscore(string $match_guestscore) Return the first FfbMatch filtered by the match_guestscore column
 * @method     FfbMatch findOneByMatchHomescorePenalty(string $match_homescore_penalty) Return the first FfbMatch filtered by the match_homescore_penalty column
 * @method     FfbMatch findOneByMatchGuestscorePenalty(string $match_guestscore_penalty) Return the first FfbMatch filtered by the match_guestscore_penalty column
 * @method     FfbMatch findOneByMatchDate(string $match_date) Return the first FfbMatch filtered by the match_date column
 * @method     FfbMatch findOneByMatchMinutes(int $match_minutes) Return the first FfbMatch filtered by the match_minutes column
 * @method     FfbMatch findOneByMatchStatus(string $match_status) Return the first FfbMatch filtered by the match_status column
 * @method     FfbMatch findOneByMatchUrl(string $match_url) Return the first FfbMatch filtered by the match_url column
 *
 * @method     array findByMatchId(int $match_id) Return FfbMatch objects filtered by the match_id column
 * @method     array findByMatchRound(int $match_round) Return FfbMatch objects filtered by the match_round column
 * @method     array findByMatchHometeamId(int $match_hometeam_id) Return FfbMatch objects filtered by the match_hometeam_id column
 * @method     array findByMatchGuestteamId(int $match_guestteam_id) Return FfbMatch objects filtered by the match_guestteam_id column
 * @method     array findByMatchHomescore(string $match_homescore) Return FfbMatch objects filtered by the match_homescore column
 * @method     array findByMatchGuestscore(string $match_guestscore) Return FfbMatch objects filtered by the match_guestscore column
 * @method     array findByMatchHomescorePenalty(string $match_homescore_penalty) Return FfbMatch objects filtered by the match_homescore_penalty column
 * @method     array findByMatchGuestscorePenalty(string $match_guestscore_penalty) Return FfbMatch objects filtered by the match_guestscore_penalty column
 * @method     array findByMatchDate(string $match_date) Return FfbMatch objects filtered by the match_date column
 * @method     array findByMatchMinutes(int $match_minutes) Return FfbMatch objects filtered by the match_minutes column
 * @method     array findByMatchStatus(string $match_status) Return FfbMatch objects filtered by the match_status column
 * @method     array findByMatchUrl(string $match_url) Return FfbMatch objects filtered by the match_url column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbMatchQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbMatchQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbMatch', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbMatchQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbMatchQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbMatchQuery) {
			return $criteria;
		}
		$query = new FfbMatchQuery();
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
	 * @return    FfbMatch|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbMatchPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbMatchPeer::MATCH_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbMatchPeer::MATCH_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the match_id column
	 * 
	 * @param     int|array $matchId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchId($matchId = null, $comparison = null)
	{
		if (is_array($matchId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_ID, $matchId, $comparison);
	}

	/**
	 * Filter the query on the match_round column
	 * 
	 * @param     int|array $matchRound The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchRound($matchRound = null, $comparison = null)
	{
		if (is_array($matchRound)) {
			$useMinMax = false;
			if (isset($matchRound['min'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_ROUND, $matchRound['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchRound['max'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_ROUND, $matchRound['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_ROUND, $matchRound, $comparison);
	}

	/**
	 * Filter the query on the match_hometeam_id column
	 * 
	 * @param     int|array $matchHometeamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchHometeamId($matchHometeamId = null, $comparison = null)
	{
		if (is_array($matchHometeamId)) {
			$useMinMax = false;
			if (isset($matchHometeamId['min'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_HOMETEAM_ID, $matchHometeamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchHometeamId['max'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_HOMETEAM_ID, $matchHometeamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_HOMETEAM_ID, $matchHometeamId, $comparison);
	}

	/**
	 * Filter the query on the match_guestteam_id column
	 * 
	 * @param     int|array $matchGuestteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchGuestteamId($matchGuestteamId = null, $comparison = null)
	{
		if (is_array($matchGuestteamId)) {
			$useMinMax = false;
			if (isset($matchGuestteamId['min'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_GUESTTEAM_ID, $matchGuestteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchGuestteamId['max'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_GUESTTEAM_ID, $matchGuestteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_GUESTTEAM_ID, $matchGuestteamId, $comparison);
	}

	/**
	 * Filter the query on the match_homescore column
	 * 
	 * @param     string $matchHomescore The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchHomescore($matchHomescore = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchHomescore)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchHomescore)) {
				$matchHomescore = str_replace('*', '%', $matchHomescore);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_HOMESCORE, $matchHomescore, $comparison);
	}

	/**
	 * Filter the query on the match_guestscore column
	 * 
	 * @param     string $matchGuestscore The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchGuestscore($matchGuestscore = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchGuestscore)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchGuestscore)) {
				$matchGuestscore = str_replace('*', '%', $matchGuestscore);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_GUESTSCORE, $matchGuestscore, $comparison);
	}

	/**
	 * Filter the query on the match_homescore_penalty column
	 * 
	 * @param     string $matchHomescorePenalty The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchHomescorePenalty($matchHomescorePenalty = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchHomescorePenalty)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchHomescorePenalty)) {
				$matchHomescorePenalty = str_replace('*', '%', $matchHomescorePenalty);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_HOMESCORE_PENALTY, $matchHomescorePenalty, $comparison);
	}

	/**
	 * Filter the query on the match_guestscore_penalty column
	 * 
	 * @param     string $matchGuestscorePenalty The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchGuestscorePenalty($matchGuestscorePenalty = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchGuestscorePenalty)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchGuestscorePenalty)) {
				$matchGuestscorePenalty = str_replace('*', '%', $matchGuestscorePenalty);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_GUESTSCORE_PENALTY, $matchGuestscorePenalty, $comparison);
	}

	/**
	 * Filter the query on the match_date column
	 * 
	 * @param     string|array $matchDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchDate($matchDate = null, $comparison = null)
	{
		if (is_array($matchDate)) {
			$useMinMax = false;
			if (isset($matchDate['min'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_DATE, $matchDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchDate['max'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_DATE, $matchDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_DATE, $matchDate, $comparison);
	}

	/**
	 * Filter the query on the match_minutes column
	 * 
	 * @param     int|array $matchMinutes The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchMinutes($matchMinutes = null, $comparison = null)
	{
		if (is_array($matchMinutes)) {
			$useMinMax = false;
			if (isset($matchMinutes['min'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_MINUTES, $matchMinutes['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($matchMinutes['max'])) {
				$this->addUsingAlias(FfbMatchPeer::MATCH_MINUTES, $matchMinutes['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_MINUTES, $matchMinutes, $comparison);
	}

	/**
	 * Filter the query on the match_status column
	 * 
	 * @param     string $matchStatus The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchStatus($matchStatus = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchStatus)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchStatus)) {
				$matchStatus = str_replace('*', '%', $matchStatus);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_STATUS, $matchStatus, $comparison);
	}

	/**
	 * Filter the query on the match_url column
	 * 
	 * @param     string $matchUrl The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByMatchUrl($matchUrl = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($matchUrl)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $matchUrl)) {
				$matchUrl = str_replace('*', '%', $matchUrl);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbMatchPeer::MATCH_URL, $matchUrl, $comparison);
	}

	/**
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_ROUND, $ffbMatchround->getMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbTeamRelatedByMatchHometeamId($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_HOMETEAM_ID, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeamRelatedByMatchHometeamId relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function joinFfbTeamRelatedByMatchHometeamId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeamRelatedByMatchHometeamId');
		
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
			$this->addJoinObject($join, 'FfbTeamRelatedByMatchHometeamId');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeamRelatedByMatchHometeamId relation FfbTeam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamRelatedByMatchHometeamIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbTeamRelatedByMatchHometeamId($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeamRelatedByMatchHometeamId', 'FfbTeamQuery');
	}

	/**
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbTeamRelatedByMatchGuestteamId($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_GUESTTEAM_ID, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeamRelatedByMatchGuestteamId relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function joinFfbTeamRelatedByMatchGuestteamId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeamRelatedByMatchGuestteamId');
		
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
			$this->addJoinObject($join, 'FfbTeamRelatedByMatchGuestteamId');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeamRelatedByMatchGuestteamId relation FfbTeam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamRelatedByMatchGuestteamIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbTeamRelatedByMatchGuestteamId($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeamRelatedByMatchGuestteamId', 'FfbTeamQuery');
	}

	/**
	 * Filter the query by a related FfbGoal object
	 *
	 * @param     FfbGoal $ffbGoal  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbGoal($ffbGoal, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_ID, $ffbGoal->getGoalMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGoal relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function joinFfbGoal($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbGoal');
		
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
			$this->addJoinObject($join, 'FfbGoal');
		}
		
		return $this;
	}

	/**
	 * Use the FfbGoal relation FfbGoal object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGoalQuery A secondary query class using the current class as primary query
	 */
	public function useFfbGoalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbGoal($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbGoal', 'FfbGoalQuery');
	}

	/**
	 * Filter the query by a related FfbPsgoal object
	 *
	 * @param     FfbPsgoal $ffbPsgoal  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbPsgoal($ffbPsgoal, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_ID, $ffbPsgoal->getPsgoalMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPsgoal relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function joinFfbPsgoal($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPsgoal');
		
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
			$this->addJoinObject($join, 'FfbPsgoal');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPsgoal relation FfbPsgoal object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPsgoalQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPsgoalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPsgoal($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPsgoal', 'FfbPsgoalQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerstats object
	 *
	 * @param     FfbPlayerstats $ffbPlayerstats  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerstats($ffbPlayerstats, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbMatchPeer::MATCH_ID, $ffbPlayerstats->getPlayerstatsMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerstats relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     FfbMatch $ffbMatch Object to remove from the list of results
	 *
	 * @return    FfbMatchQuery The current query, for fluid interface
	 */
	public function prune($ffbMatch = null)
	{
		if ($ffbMatch) {
			$this->addUsingAlias(FfbMatchPeer::MATCH_ID, $ffbMatch->getMatchId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbMatchQuery
