<?php


/**
 * Base class that represents a query for the 'ffb_playerprice' table.
 *
 * 
 *
 * @method     FfbPlayerpriceQuery orderByPlayerpriceId($order = Criteria::ASC) Order by the playerprice_id column
 * @method     FfbPlayerpriceQuery orderByPlayerpricePlayerteamId($order = Criteria::ASC) Order by the playerprice_playerteam_id column
 * @method     FfbPlayerpriceQuery orderByPlayerpriceMatchroundId($order = Criteria::ASC) Order by the playerprice_matchround_id column
 * @method     FfbPlayerpriceQuery orderByPlayerpricePrice($order = Criteria::ASC) Order by the playerprice_price column
 * @method     FfbPlayerpriceQuery orderByPlayerpricePlayerPower($order = Criteria::ASC) Order by the playerprice_player_power column
 * @method     FfbPlayerpriceQuery orderByPlayerpriceAvPower($order = Criteria::ASC) Order by the playerprice_av_power column
 *
 * @method     FfbPlayerpriceQuery groupByPlayerpriceId() Group by the playerprice_id column
 * @method     FfbPlayerpriceQuery groupByPlayerpricePlayerteamId() Group by the playerprice_playerteam_id column
 * @method     FfbPlayerpriceQuery groupByPlayerpriceMatchroundId() Group by the playerprice_matchround_id column
 * @method     FfbPlayerpriceQuery groupByPlayerpricePrice() Group by the playerprice_price column
 * @method     FfbPlayerpriceQuery groupByPlayerpricePlayerPower() Group by the playerprice_player_power column
 * @method     FfbPlayerpriceQuery groupByPlayerpriceAvPower() Group by the playerprice_av_power column
 *
 * @method     FfbPlayerpriceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPlayerpriceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPlayerpriceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPlayerpriceQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerpriceQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerpriceQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbPlayerpriceQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbPlayerpriceQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbPlayerpriceQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbPlayerprice findOne(PropelPDO $con = null) Return the first FfbPlayerprice matching the query
 * @method     FfbPlayerprice findOneOrCreate(PropelPDO $con = null) Return the first FfbPlayerprice matching the query, or a new FfbPlayerprice object populated from the query conditions when no match is found
 *
 * @method     FfbPlayerprice findOneByPlayerpriceId(int $playerprice_id) Return the first FfbPlayerprice filtered by the playerprice_id column
 * @method     FfbPlayerprice findOneByPlayerpricePlayerteamId(int $playerprice_playerteam_id) Return the first FfbPlayerprice filtered by the playerprice_playerteam_id column
 * @method     FfbPlayerprice findOneByPlayerpriceMatchroundId(int $playerprice_matchround_id) Return the first FfbPlayerprice filtered by the playerprice_matchround_id column
 * @method     FfbPlayerprice findOneByPlayerpricePrice(double $playerprice_price) Return the first FfbPlayerprice filtered by the playerprice_price column
 * @method     FfbPlayerprice findOneByPlayerpricePlayerPower(double $playerprice_player_power) Return the first FfbPlayerprice filtered by the playerprice_player_power column
 * @method     FfbPlayerprice findOneByPlayerpriceAvPower(double $playerprice_av_power) Return the first FfbPlayerprice filtered by the playerprice_av_power column
 *
 * @method     array findByPlayerpriceId(int $playerprice_id) Return FfbPlayerprice objects filtered by the playerprice_id column
 * @method     array findByPlayerpricePlayerteamId(int $playerprice_playerteam_id) Return FfbPlayerprice objects filtered by the playerprice_playerteam_id column
 * @method     array findByPlayerpriceMatchroundId(int $playerprice_matchround_id) Return FfbPlayerprice objects filtered by the playerprice_matchround_id column
 * @method     array findByPlayerpricePrice(double $playerprice_price) Return FfbPlayerprice objects filtered by the playerprice_price column
 * @method     array findByPlayerpricePlayerPower(double $playerprice_player_power) Return FfbPlayerprice objects filtered by the playerprice_player_power column
 * @method     array findByPlayerpriceAvPower(double $playerprice_av_power) Return FfbPlayerprice objects filtered by the playerprice_av_power column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerpriceQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPlayerpriceQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPlayerprice', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPlayerpriceQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPlayerpriceQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPlayerpriceQuery) {
			return $criteria;
		}
		$query = new FfbPlayerpriceQuery();
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
	 * @return    FfbPlayerprice|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPlayerpricePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the playerprice_id column
	 * 
	 * @param     int|array $playerpriceId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpriceId($playerpriceId = null, $comparison = null)
	{
		if (is_array($playerpriceId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_ID, $playerpriceId, $comparison);
	}

	/**
	 * Filter the query on the playerprice_playerteam_id column
	 * 
	 * @param     int|array $playerpricePlayerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpricePlayerteamId($playerpricePlayerteamId = null, $comparison = null)
	{
		if (is_array($playerpricePlayerteamId)) {
			$useMinMax = false;
			if (isset($playerpricePlayerteamId['min'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerpricePlayerteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerpricePlayerteamId['max'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerpricePlayerteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerpricePlayerteamId, $comparison);
	}

	/**
	 * Filter the query on the playerprice_matchround_id column
	 * 
	 * @param     int|array $playerpriceMatchroundId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpriceMatchroundId($playerpriceMatchroundId = null, $comparison = null)
	{
		if (is_array($playerpriceMatchroundId)) {
			$useMinMax = false;
			if (isset($playerpriceMatchroundId['min'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $playerpriceMatchroundId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerpriceMatchroundId['max'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $playerpriceMatchroundId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $playerpriceMatchroundId, $comparison);
	}

	/**
	 * Filter the query on the playerprice_price column
	 * 
	 * @param     double|array $playerpricePrice The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpricePrice($playerpricePrice = null, $comparison = null)
	{
		if (is_array($playerpricePrice)) {
			$useMinMax = false;
			if (isset($playerpricePrice['min'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PRICE, $playerpricePrice['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerpricePrice['max'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PRICE, $playerpricePrice['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PRICE, $playerpricePrice, $comparison);
	}

	/**
	 * Filter the query on the playerprice_player_power column
	 * 
	 * @param     double|array $playerpricePlayerPower The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpricePlayerPower($playerpricePlayerPower = null, $comparison = null)
	{
		if (is_array($playerpricePlayerPower)) {
			$useMinMax = false;
			if (isset($playerpricePlayerPower['min'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER, $playerpricePlayerPower['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerpricePlayerPower['max'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER, $playerpricePlayerPower['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER, $playerpricePlayerPower, $comparison);
	}

	/**
	 * Filter the query on the playerprice_av_power column
	 * 
	 * @param     double|array $playerpriceAvPower The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByPlayerpriceAvPower($playerpriceAvPower = null, $comparison = null)
	{
		if (is_array($playerpriceAvPower)) {
			$useMinMax = false;
			if (isset($playerpriceAvPower['min'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_AV_POWER, $playerpriceAvPower['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerpriceAvPower['max'])) {
				$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_AV_POWER, $playerpriceAvPower['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_AV_POWER, $playerpriceAvPower, $comparison);
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $ffbMatchround->getMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
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
	 * @param     FfbPlayerprice $ffbPlayerprice Object to remove from the list of results
	 *
	 * @return    FfbPlayerpriceQuery The current query, for fluid interface
	 */
	public function prune($ffbPlayerprice = null)
	{
		if ($ffbPlayerprice) {
			$this->addUsingAlias(FfbPlayerpricePeer::PLAYERPRICE_ID, $ffbPlayerprice->getPlayerpriceId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPlayerpriceQuery
