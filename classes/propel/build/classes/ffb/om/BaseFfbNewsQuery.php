<?php


/**
 * Base class that represents a query for the 'ffb_news' table.
 *
 * 
 *
 * @method     FfbNewsQuery orderByNewsId($order = Criteria::ASC) Order by the news_id column
 * @method     FfbNewsQuery orderByNewsTitle($order = Criteria::ASC) Order by the news_title column
 * @method     FfbNewsQuery orderByNewsText($order = Criteria::ASC) Order by the news_text column
 * @method     FfbNewsQuery orderByNewsDate($order = Criteria::ASC) Order by the news_date column
 * @method     FfbNewsQuery orderByNewsPriority($order = Criteria::ASC) Order by the news_priority column
 * @method     FfbNewsQuery orderByNewsGameId($order = Criteria::ASC) Order by the news_game_id column
 * @method     FfbNewsQuery orderByNewsSymbol($order = Criteria::ASC) Order by the news_symbol column
 *
 * @method     FfbNewsQuery groupByNewsId() Group by the news_id column
 * @method     FfbNewsQuery groupByNewsTitle() Group by the news_title column
 * @method     FfbNewsQuery groupByNewsText() Group by the news_text column
 * @method     FfbNewsQuery groupByNewsDate() Group by the news_date column
 * @method     FfbNewsQuery groupByNewsPriority() Group by the news_priority column
 * @method     FfbNewsQuery groupByNewsGameId() Group by the news_game_id column
 * @method     FfbNewsQuery groupByNewsSymbol() Group by the news_symbol column
 *
 * @method     FfbNewsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbNewsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbNewsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbNewsQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbNewsQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbNewsQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbNews findOne(?PropelPDO $con = null) Return the first FfbNews matching the query
 * @method     FfbNews findOneOrCreate(?PropelPDO $con = null) Return the first FfbNews matching the query, or a new FfbNews object populated from the query conditions when no match is found
 *
 * @method     FfbNews findOneByNewsId(int $news_id) Return the first FfbNews filtered by the news_id column
 * @method     FfbNews findOneByNewsTitle(string $news_title) Return the first FfbNews filtered by the news_title column
 * @method     FfbNews findOneByNewsText(string $news_text) Return the first FfbNews filtered by the news_text column
 * @method     FfbNews findOneByNewsDate(string $news_date) Return the first FfbNews filtered by the news_date column
 * @method     FfbNews findOneByNewsPriority(int $news_priority) Return the first FfbNews filtered by the news_priority column
 * @method     FfbNews findOneByNewsGameId(int $news_game_id) Return the first FfbNews filtered by the news_game_id column
 * @method     FfbNews findOneByNewsSymbol(string $news_symbol) Return the first FfbNews filtered by the news_symbol column
 *
 * @method     array findByNewsId(int $news_id) Return FfbNews objects filtered by the news_id column
 * @method     array findByNewsTitle(string $news_title) Return FfbNews objects filtered by the news_title column
 * @method     array findByNewsText(string $news_text) Return FfbNews objects filtered by the news_text column
 * @method     array findByNewsDate(string $news_date) Return FfbNews objects filtered by the news_date column
 * @method     array findByNewsPriority(int $news_priority) Return FfbNews objects filtered by the news_priority column
 * @method     array findByNewsGameId(int $news_game_id) Return FfbNews objects filtered by the news_game_id column
 * @method     array findByNewsSymbol(string $news_symbol) Return FfbNews objects filtered by the news_symbol column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbNewsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbNewsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbNews', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbNewsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbNewsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbNewsQuery) {
			return $criteria;
		}
		$query = new FfbNewsQuery();
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
	 * @return    FfbNews|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbNewsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbNewsPeer::NEWS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbNewsPeer::NEWS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the news_id column
	 * 
	 * @param     int|array $newsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsId($newsId = null, $comparison = null)
	{
		if (is_array($newsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_ID, $newsId, $comparison);
	}

	/**
	 * Filter the query on the news_title column
	 * 
	 * @param     string $newsTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsTitle($newsTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($newsTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $newsTitle)) {
				$newsTitle = str_replace('*', '%', $newsTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_TITLE, $newsTitle, $comparison);
	}

	/**
	 * Filter the query on the news_text column
	 * 
	 * @param     string $newsText The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsText($newsText = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($newsText)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $newsText)) {
				$newsText = str_replace('*', '%', $newsText);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_TEXT, $newsText, $comparison);
	}

	/**
	 * Filter the query on the news_date column
	 * 
	 * @param     string|array $newsDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsDate($newsDate = null, $comparison = null)
	{
		if (is_array($newsDate)) {
			$useMinMax = false;
			if (isset($newsDate['min'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_DATE, $newsDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($newsDate['max'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_DATE, $newsDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_DATE, $newsDate, $comparison);
	}

	/**
	 * Filter the query on the news_priority column
	 * 
	 * @param     int|array $newsPriority The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsPriority($newsPriority = null, $comparison = null)
	{
		if (is_array($newsPriority)) {
			$useMinMax = false;
			if (isset($newsPriority['min'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_PRIORITY, $newsPriority['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($newsPriority['max'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_PRIORITY, $newsPriority['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_PRIORITY, $newsPriority, $comparison);
	}

	/**
	 * Filter the query on the news_game_id column
	 * 
	 * @param     int|array $newsGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsGameId($newsGameId = null, $comparison = null)
	{
		if (is_array($newsGameId)) {
			$useMinMax = false;
			if (isset($newsGameId['min'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_GAME_ID, $newsGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($newsGameId['max'])) {
				$this->addUsingAlias(FfbNewsPeer::NEWS_GAME_ID, $newsGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_GAME_ID, $newsGameId, $comparison);
	}

	/**
	 * Filter the query on the news_symbol column
	 * 
	 * @param     string $newsSymbol The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsSymbol($newsSymbol = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($newsSymbol)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $newsSymbol)) {
				$newsSymbol = str_replace('*', '%', $newsSymbol);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbNewsPeer::NEWS_SYMBOL, $newsSymbol, $comparison);
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbNewsPeer::NEWS_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     FfbNews $ffbNews Object to remove from the list of results
	 *
	 * @return    FfbNewsQuery The current query, for fluid interface
	 */
	public function prune($ffbNews = null)
	{
		if ($ffbNews) {
			$this->addUsingAlias(FfbNewsPeer::NEWS_ID, $ffbNews->getNewsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbNewsQuery
