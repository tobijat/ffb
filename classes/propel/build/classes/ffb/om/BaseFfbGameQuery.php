<?php


/**
 * Base class that represents a query for the 'ffb_game' table.
 *
 * 
 *
 * @method     FfbGameQuery orderByGameId($order = Criteria::ASC) Order by the game_id column
 * @method     FfbGameQuery orderByGameTitle($order = Criteria::ASC) Order by the game_title column
 * @method     FfbGameQuery orderByGameVisible($order = Criteria::ASC) Order by the game_visible column
 * @method     FfbGameQuery orderByGameArchive($order = Criteria::ASC) Order by the game_archive column
 * @method     FfbGameQuery orderByGameCountdown($order = Criteria::ASC) Order by the game_countdown column
 * @method     FfbGameQuery orderByGameStatus($order = Criteria::ASC) Order by the game_status column
 * @method     FfbGameQuery orderByGameDescription($order = Criteria::ASC) Order by the game_description column
 * @method     FfbGameQuery orderByGameSymbol($order = Criteria::ASC) Order by the game_symbol column
 *
 * @method     FfbGameQuery groupByGameId() Group by the game_id column
 * @method     FfbGameQuery groupByGameTitle() Group by the game_title column
 * @method     FfbGameQuery groupByGameVisible() Group by the game_visible column
 * @method     FfbGameQuery groupByGameArchive() Group by the game_archive column
 * @method     FfbGameQuery groupByGameCountdown() Group by the game_countdown column
 * @method     FfbGameQuery groupByGameStatus() Group by the game_status column
 * @method     FfbGameQuery groupByGameDescription() Group by the game_description column
 * @method     FfbGameQuery groupByGameSymbol() Group by the game_symbol column
 *
 * @method     FfbGameQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbGameQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbGameQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbGameQuery leftJoinWebUserDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserDetails relation
 * @method     FfbGameQuery rightJoinWebUserDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserDetails relation
 * @method     FfbGameQuery innerJoinWebUserDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserDetails relation
 *
 * @method     FfbGameQuery leftJoinFfbPoll($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPoll relation
 * @method     FfbGameQuery rightJoinFfbPoll($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPoll relation
 * @method     FfbGameQuery innerJoinFfbPoll($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPoll relation
 *
 * @method     FfbGameQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbGameQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbGameQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbGameQuery leftJoinFfbNews($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbNews relation
 * @method     FfbGameQuery rightJoinFfbNews($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbNews relation
 * @method     FfbGameQuery innerJoinFfbNews($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbNews relation
 *
 * @method     FfbGameQuery leftJoinFfbUserscore($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserscore relation
 * @method     FfbGameQuery rightJoinFfbUserscore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserscore relation
 * @method     FfbGameQuery innerJoinFfbUserscore($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserscore relation
 *
 * @method     FfbGameQuery leftJoinFfbAdmin($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdmin relation
 * @method     FfbGameQuery rightJoinFfbAdmin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdmin relation
 * @method     FfbGameQuery innerJoinFfbAdmin($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdmin relation
 *
 * @method     FfbGameQuery leftJoinFfbOptions($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbOptions relation
 * @method     FfbGameQuery rightJoinFfbOptions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbOptions relation
 * @method     FfbGameQuery innerJoinFfbOptions($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbOptions relation
 *
 * @method     FfbGame findOne(?PropelPDO $con = null) Return the first FfbGame matching the query
 * @method     FfbGame findOneOrCreate(?PropelPDO $con = null) Return the first FfbGame matching the query, or a new FfbGame object populated from the query conditions when no match is found
 *
 * @method     FfbGame findOneByGameId(int $game_id) Return the first FfbGame filtered by the game_id column
 * @method     FfbGame findOneByGameTitle(string $game_title) Return the first FfbGame filtered by the game_title column
 * @method     FfbGame findOneByGameVisible(boolean $game_visible) Return the first FfbGame filtered by the game_visible column
 * @method     FfbGame findOneByGameArchive(boolean $game_archive) Return the first FfbGame filtered by the game_archive column
 * @method     FfbGame findOneByGameCountdown(boolean $game_countdown) Return the first FfbGame filtered by the game_countdown column
 * @method     FfbGame findOneByGameStatus(boolean $game_status) Return the first FfbGame filtered by the game_status column
 * @method     FfbGame findOneByGameDescription(string $game_description) Return the first FfbGame filtered by the game_description column
 * @method     FfbGame findOneByGameSymbol(string $game_symbol) Return the first FfbGame filtered by the game_symbol column
 *
 * @method     array findByGameId(int $game_id) Return FfbGame objects filtered by the game_id column
 * @method     array findByGameTitle(string $game_title) Return FfbGame objects filtered by the game_title column
 * @method     array findByGameVisible(boolean $game_visible) Return FfbGame objects filtered by the game_visible column
 * @method     array findByGameArchive(boolean $game_archive) Return FfbGame objects filtered by the game_archive column
 * @method     array findByGameCountdown(boolean $game_countdown) Return FfbGame objects filtered by the game_countdown column
 * @method     array findByGameStatus(boolean $game_status) Return FfbGame objects filtered by the game_status column
 * @method     array findByGameDescription(string $game_description) Return FfbGame objects filtered by the game_description column
 * @method     array findByGameSymbol(string $game_symbol) Return FfbGame objects filtered by the game_symbol column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbGameQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbGameQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbGame', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbGameQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbGameQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbGameQuery) {
			return $criteria;
		}
		$query = new FfbGameQuery();
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
	 * @return    FfbGame|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbGamePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbGamePeer::GAME_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbGamePeer::GAME_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the game_id column
	 * 
	 * @param     int|array $gameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameId($gameId = null, $comparison = null)
	{
		if (is_array($gameId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_ID, $gameId, $comparison);
	}

	/**
	 * Filter the query on the game_title column
	 * 
	 * @param     string $gameTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameTitle($gameTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($gameTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $gameTitle)) {
				$gameTitle = str_replace('*', '%', $gameTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_TITLE, $gameTitle, $comparison);
	}

	/**
	 * Filter the query on the game_visible column
	 * 
	 * @param     boolean|string $gameVisible The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameVisible($gameVisible = null, $comparison = null)
	{
		if (is_string($gameVisible)) {
			$game_visible = in_array(strtolower($gameVisible), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_VISIBLE, $gameVisible, $comparison);
	}

	/**
	 * Filter the query on the game_archive column
	 * 
	 * @param     boolean|string $gameArchive The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameArchive($gameArchive = null, $comparison = null)
	{
		if (is_string($gameArchive)) {
			$game_archive = in_array(strtolower($gameArchive), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_ARCHIVE, $gameArchive, $comparison);
	}

	/**
	 * Filter the query on the game_countdown column
	 * 
	 * @param     boolean|string $gameCountdown The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameCountdown($gameCountdown = null, $comparison = null)
	{
		if (is_string($gameCountdown)) {
			$game_countdown = in_array(strtolower($gameCountdown), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_COUNTDOWN, $gameCountdown, $comparison);
	}

	/**
	 * Filter the query on the game_status column
	 * 
	 * @param     boolean|string $gameStatus The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameStatus($gameStatus = null, $comparison = null)
	{
		if (is_string($gameStatus)) {
			$game_status = in_array(strtolower($gameStatus), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_STATUS, $gameStatus, $comparison);
	}

	/**
	 * Filter the query on the game_description column
	 * 
	 * @param     string $gameDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameDescription($gameDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($gameDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $gameDescription)) {
				$gameDescription = str_replace('*', '%', $gameDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_DESCRIPTION, $gameDescription, $comparison);
	}

	/**
	 * Filter the query on the game_symbol column
	 * 
	 * @param     string $gameSymbol The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByGameSymbol($gameSymbol = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($gameSymbol)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $gameSymbol)) {
				$gameSymbol = str_replace('*', '%', $gameSymbol);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbGamePeer::GAME_SYMBOL, $gameSymbol, $comparison);
	}

	/**
	 * Filter the query by a related WebUserDetails object
	 *
	 * @param     WebUserDetails $webUserDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByWebUserDetails($webUserDetails, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $webUserDetails->getUserDetailsFfbSelectedGame(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserDetails relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function joinWebUserDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserDetails');
		
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
			$this->addJoinObject($join, 'WebUserDetails');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserDetails relation WebUserDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebUserDetails($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserDetails', 'WebUserDetailsQuery');
	}


	/**
	 * Filter the query by a related FfbPoll object
	 *
	 * @param     FfbPoll $ffbPoll  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbPoll($ffbPoll, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbPoll->getPollGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPoll relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbMatchround->getMatchroundGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbNews object
	 *
	 * @param     FfbNews $ffbNews  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbNews($ffbNews, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbNews->getNewsGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbNews relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function joinFfbNews($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbNews');
		
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
			$this->addJoinObject($join, 'FfbNews');
		}
		
		return $this;
	}

	/**
	 * Use the FfbNews relation FfbNews object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbNewsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbNewsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbNews($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbNews', 'FfbNewsQuery');
	}

	/**
	 * Filter the query by a related FfbUserscore object
	 *
	 * @param     FfbUserscore $ffbUserscore  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbUserscore($ffbUserscore, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbUserscore->getUserscoreGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserscore relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function joinFfbUserscore($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserscore');
		
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
			$this->addJoinObject($join, 'FfbUserscore');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserscore relation FfbUserscore object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserscoreQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserscoreQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserscore($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserscore', 'FfbUserscoreQuery');
	}

	/**
	 * Filter the query by a related FfbAdmin object
	 *
	 * @param     FfbAdmin $ffbAdmin  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbAdmin($ffbAdmin, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbAdmin->getAdminGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdmin relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function joinFfbAdmin($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbAdmin');
		
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
			$this->addJoinObject($join, 'FfbAdmin');
		}
		
		return $this;
	}

	/**
	 * Use the FfbAdmin relation FfbAdmin object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdminQuery A secondary query class using the current class as primary query
	 */
	public function useFfbAdminQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbAdmin($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbAdmin', 'FfbAdminQuery');
	}

	/**
	 * Filter the query by a related FfbOptions object
	 *
	 * @param     FfbOptions $ffbOptions  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function filterByFfbOptions($ffbOptions, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbGamePeer::GAME_ID, $ffbOptions->getOptionsGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbOptions relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function joinFfbOptions($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbOptions');
		
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
			$this->addJoinObject($join, 'FfbOptions');
		}
		
		return $this;
	}

	/**
	 * Use the FfbOptions relation FfbOptions object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbOptionsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbOptionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbOptions($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbOptions', 'FfbOptionsQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbGame $ffbGame Object to remove from the list of results
	 *
	 * @return    FfbGameQuery The current query, for fluid interface
	 */
	public function prune($ffbGame = null)
	{
		if ($ffbGame) {
			$this->addUsingAlias(FfbGamePeer::GAME_ID, $ffbGame->getGameId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbGameQuery
