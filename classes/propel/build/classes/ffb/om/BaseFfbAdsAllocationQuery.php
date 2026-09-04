<?php


/**
 * Base class that represents a query for the 'ffb_ads_allocation' table.
 *
 * 
 *
 * @method     FfbAdsAllocationQuery orderByAdsAllocationId($order = Criteria::ASC) Order by the ads_allocation_id column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationAdsId($order = Criteria::ASC) Order by the ads_allocation_ads_id column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationSlotId($order = Criteria::ASC) Order by the ads_allocation_slot_id column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationGameId($order = Criteria::ASC) Order by the ads_allocation_game_id column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationAdCount($order = Criteria::ASC) Order by the ads_allocation_ad_count column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationAdMax($order = Criteria::ASC) Order by the ads_allocation_ad_max column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationAdPriority($order = Criteria::ASC) Order by the ads_allocation_ad_priority column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationStart($order = Criteria::ASC) Order by the ads_allocation_start column
 * @method     FfbAdsAllocationQuery orderByAdsAllocationEnd($order = Criteria::ASC) Order by the ads_allocation_end column
 *
 * @method     FfbAdsAllocationQuery groupByAdsAllocationId() Group by the ads_allocation_id column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationAdsId() Group by the ads_allocation_ads_id column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationSlotId() Group by the ads_allocation_slot_id column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationGameId() Group by the ads_allocation_game_id column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationAdCount() Group by the ads_allocation_ad_count column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationAdMax() Group by the ads_allocation_ad_max column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationAdPriority() Group by the ads_allocation_ad_priority column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationStart() Group by the ads_allocation_start column
 * @method     FfbAdsAllocationQuery groupByAdsAllocationEnd() Group by the ads_allocation_end column
 *
 * @method     FfbAdsAllocationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbAdsAllocationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbAdsAllocationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbAdsAllocationQuery leftJoinFfbAds($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAds relation
 * @method     FfbAdsAllocationQuery rightJoinFfbAds($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAds relation
 * @method     FfbAdsAllocationQuery innerJoinFfbAds($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAds relation
 *
 * @method     FfbAdsAllocationQuery leftJoinFfbAdsSlot($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdsSlot relation
 * @method     FfbAdsAllocationQuery rightJoinFfbAdsSlot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdsSlot relation
 * @method     FfbAdsAllocationQuery innerJoinFfbAdsSlot($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdsSlot relation
 *
 * @method     FfbAdsAllocationQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     FfbAdsAllocationQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     FfbAdsAllocationQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     FfbAdsAllocation findOne(PropelPDO $con = null) Return the first FfbAdsAllocation matching the query
 * @method     FfbAdsAllocation findOneOrCreate(PropelPDO $con = null) Return the first FfbAdsAllocation matching the query, or a new FfbAdsAllocation object populated from the query conditions when no match is found
 *
 * @method     FfbAdsAllocation findOneByAdsAllocationId(int $ads_allocation_id) Return the first FfbAdsAllocation filtered by the ads_allocation_id column
 * @method     FfbAdsAllocation findOneByAdsAllocationAdsId(int $ads_allocation_ads_id) Return the first FfbAdsAllocation filtered by the ads_allocation_ads_id column
 * @method     FfbAdsAllocation findOneByAdsAllocationSlotId(int $ads_allocation_slot_id) Return the first FfbAdsAllocation filtered by the ads_allocation_slot_id column
 * @method     FfbAdsAllocation findOneByAdsAllocationGameId(int $ads_allocation_game_id) Return the first FfbAdsAllocation filtered by the ads_allocation_game_id column
 * @method     FfbAdsAllocation findOneByAdsAllocationAdCount(int $ads_allocation_ad_count) Return the first FfbAdsAllocation filtered by the ads_allocation_ad_count column
 * @method     FfbAdsAllocation findOneByAdsAllocationAdMax(int $ads_allocation_ad_max) Return the first FfbAdsAllocation filtered by the ads_allocation_ad_max column
 * @method     FfbAdsAllocation findOneByAdsAllocationAdPriority(int $ads_allocation_ad_priority) Return the first FfbAdsAllocation filtered by the ads_allocation_ad_priority column
 * @method     FfbAdsAllocation findOneByAdsAllocationStart(string $ads_allocation_start) Return the first FfbAdsAllocation filtered by the ads_allocation_start column
 * @method     FfbAdsAllocation findOneByAdsAllocationEnd(string $ads_allocation_end) Return the first FfbAdsAllocation filtered by the ads_allocation_end column
 *
 * @method     array findByAdsAllocationId(int $ads_allocation_id) Return FfbAdsAllocation objects filtered by the ads_allocation_id column
 * @method     array findByAdsAllocationAdsId(int $ads_allocation_ads_id) Return FfbAdsAllocation objects filtered by the ads_allocation_ads_id column
 * @method     array findByAdsAllocationSlotId(int $ads_allocation_slot_id) Return FfbAdsAllocation objects filtered by the ads_allocation_slot_id column
 * @method     array findByAdsAllocationGameId(int $ads_allocation_game_id) Return FfbAdsAllocation objects filtered by the ads_allocation_game_id column
 * @method     array findByAdsAllocationAdCount(int $ads_allocation_ad_count) Return FfbAdsAllocation objects filtered by the ads_allocation_ad_count column
 * @method     array findByAdsAllocationAdMax(int $ads_allocation_ad_max) Return FfbAdsAllocation objects filtered by the ads_allocation_ad_max column
 * @method     array findByAdsAllocationAdPriority(int $ads_allocation_ad_priority) Return FfbAdsAllocation objects filtered by the ads_allocation_ad_priority column
 * @method     array findByAdsAllocationStart(string $ads_allocation_start) Return FfbAdsAllocation objects filtered by the ads_allocation_start column
 * @method     array findByAdsAllocationEnd(string $ads_allocation_end) Return FfbAdsAllocation objects filtered by the ads_allocation_end column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbAdsAllocationQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbAdsAllocationQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbAdsAllocation', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbAdsAllocationQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbAdsAllocationQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbAdsAllocationQuery) {
			return $criteria;
		}
		$query = new FfbAdsAllocationQuery();
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
	 * @return    FfbAdsAllocation|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbAdsAllocationPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the ads_allocation_id column
	 * 
	 * @param     int|array $adsAllocationId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationId($adsAllocationId = null, $comparison = null)
	{
		if (is_array($adsAllocationId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $adsAllocationId, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_ads_id column
	 * 
	 * @param     int|array $adsAllocationAdsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationAdsId($adsAllocationAdsId = null, $comparison = null)
	{
		if (is_array($adsAllocationAdsId)) {
			$useMinMax = false;
			if (isset($adsAllocationAdsId['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, $adsAllocationAdsId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationAdsId['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, $adsAllocationAdsId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, $adsAllocationAdsId, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_slot_id column
	 * 
	 * @param     int|array $adsAllocationSlotId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationSlotId($adsAllocationSlotId = null, $comparison = null)
	{
		if (is_array($adsAllocationSlotId)) {
			$useMinMax = false;
			if (isset($adsAllocationSlotId['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $adsAllocationSlotId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationSlotId['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $adsAllocationSlotId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $adsAllocationSlotId, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_game_id column
	 * 
	 * @param     int|array $adsAllocationGameId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationGameId($adsAllocationGameId = null, $comparison = null)
	{
		if (is_array($adsAllocationGameId)) {
			$useMinMax = false;
			if (isset($adsAllocationGameId['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $adsAllocationGameId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationGameId['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $adsAllocationGameId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $adsAllocationGameId, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_ad_count column
	 * 
	 * @param     int|array $adsAllocationAdCount The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationAdCount($adsAllocationAdCount = null, $comparison = null)
	{
		if (is_array($adsAllocationAdCount)) {
			$useMinMax = false;
			if (isset($adsAllocationAdCount['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_COUNT, $adsAllocationAdCount['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationAdCount['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_COUNT, $adsAllocationAdCount['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_COUNT, $adsAllocationAdCount, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_ad_max column
	 * 
	 * @param     int|array $adsAllocationAdMax The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationAdMax($adsAllocationAdMax = null, $comparison = null)
	{
		if (is_array($adsAllocationAdMax)) {
			$useMinMax = false;
			if (isset($adsAllocationAdMax['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_MAX, $adsAllocationAdMax['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationAdMax['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_MAX, $adsAllocationAdMax['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_MAX, $adsAllocationAdMax, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_ad_priority column
	 * 
	 * @param     int|array $adsAllocationAdPriority The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationAdPriority($adsAllocationAdPriority = null, $comparison = null)
	{
		if (is_array($adsAllocationAdPriority)) {
			$useMinMax = false;
			if (isset($adsAllocationAdPriority['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_PRIORITY, $adsAllocationAdPriority['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationAdPriority['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_PRIORITY, $adsAllocationAdPriority['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_AD_PRIORITY, $adsAllocationAdPriority, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_start column
	 * 
	 * @param     string|array $adsAllocationStart The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationStart($adsAllocationStart = null, $comparison = null)
	{
		if (is_array($adsAllocationStart)) {
			$useMinMax = false;
			if (isset($adsAllocationStart['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_START, $adsAllocationStart['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationStart['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_START, $adsAllocationStart['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_START, $adsAllocationStart, $comparison);
	}

	/**
	 * Filter the query on the ads_allocation_end column
	 * 
	 * @param     string|array $adsAllocationEnd The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByAdsAllocationEnd($adsAllocationEnd = null, $comparison = null)
	{
		if (is_array($adsAllocationEnd)) {
			$useMinMax = false;
			if (isset($adsAllocationEnd['min'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_END, $adsAllocationEnd['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adsAllocationEnd['max'])) {
				$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_END, $adsAllocationEnd['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_END, $adsAllocationEnd, $comparison);
	}

	/**
	 * Filter the query by a related FfbAds object
	 *
	 * @param     FfbAds $ffbAds  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByFfbAds($ffbAds, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ADS_ID, $ffbAds->getAdsId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAds relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function joinFfbAds($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbAds');
		
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
			$this->addJoinObject($join, 'FfbAds');
		}
		
		return $this;
	}

	/**
	 * Use the FfbAds relation FfbAds object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbAdsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbAds($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbAds', 'FfbAdsQuery');
	}

	/**
	 * Filter the query by a related FfbAdsSlot object
	 *
	 * @param     FfbAdsSlot $ffbAdsSlot  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByFfbAdsSlot($ffbAdsSlot, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_SLOT_ID, $ffbAdsSlot->getAdsSlotId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdsSlot relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function joinFfbAdsSlot($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbAdsSlot');
		
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
			$this->addJoinObject($join, 'FfbAdsSlot');
		}
		
		return $this;
	}

	/**
	 * Use the FfbAdsSlot relation FfbAdsSlot object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsSlotQuery A secondary query class using the current class as primary query
	 */
	public function useFfbAdsSlotQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbAdsSlot($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbAdsSlot', 'FfbAdsSlotQuery');
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_GAME_ID, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
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
	 * @param     FfbAdsAllocation $ffbAdsAllocation Object to remove from the list of results
	 *
	 * @return    FfbAdsAllocationQuery The current query, for fluid interface
	 */
	public function prune($ffbAdsAllocation = null)
	{
		if ($ffbAdsAllocation) {
			$this->addUsingAlias(FfbAdsAllocationPeer::ADS_ALLOCATION_ID, $ffbAdsAllocation->getAdsAllocationId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbAdsAllocationQuery
