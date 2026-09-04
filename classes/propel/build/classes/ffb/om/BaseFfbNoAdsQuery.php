<?php


/**
 * Base class that represents a query for the 'ffb_no_ads' table.
 *
 * 
 *
 * @method     FfbNoAdsQuery orderByNoAdsId($order = Criteria::ASC) Order by the no_ads_id column
 * @method     FfbNoAdsQuery orderByNoAdsUserIdIp($order = Criteria::ASC) Order by the no_ads_user_id_ip column
 * @method     FfbNoAdsQuery orderByNoAdsSlotId($order = Criteria::ASC) Order by the no_ads_slot_id column
 *
 * @method     FfbNoAdsQuery groupByNoAdsId() Group by the no_ads_id column
 * @method     FfbNoAdsQuery groupByNoAdsUserIdIp() Group by the no_ads_user_id_ip column
 * @method     FfbNoAdsQuery groupByNoAdsSlotId() Group by the no_ads_slot_id column
 *
 * @method     FfbNoAdsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbNoAdsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbNoAdsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbNoAdsQuery leftJoinFfbAdsSlot($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdsSlot relation
 * @method     FfbNoAdsQuery rightJoinFfbAdsSlot($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdsSlot relation
 * @method     FfbNoAdsQuery innerJoinFfbAdsSlot($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdsSlot relation
 *
 * @method     FfbNoAds findOne(PropelPDO $con = null) Return the first FfbNoAds matching the query
 * @method     FfbNoAds findOneOrCreate(PropelPDO $con = null) Return the first FfbNoAds matching the query, or a new FfbNoAds object populated from the query conditions when no match is found
 *
 * @method     FfbNoAds findOneByNoAdsId(int $no_ads_id) Return the first FfbNoAds filtered by the no_ads_id column
 * @method     FfbNoAds findOneByNoAdsUserIdIp(string $no_ads_user_id_ip) Return the first FfbNoAds filtered by the no_ads_user_id_ip column
 * @method     FfbNoAds findOneByNoAdsSlotId(int $no_ads_slot_id) Return the first FfbNoAds filtered by the no_ads_slot_id column
 *
 * @method     array findByNoAdsId(int $no_ads_id) Return FfbNoAds objects filtered by the no_ads_id column
 * @method     array findByNoAdsUserIdIp(string $no_ads_user_id_ip) Return FfbNoAds objects filtered by the no_ads_user_id_ip column
 * @method     array findByNoAdsSlotId(int $no_ads_slot_id) Return FfbNoAds objects filtered by the no_ads_slot_id column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbNoAdsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbNoAdsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbNoAds', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbNoAdsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbNoAdsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbNoAdsQuery) {
			return $criteria;
		}
		$query = new FfbNoAdsQuery();
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
	 * @return    FfbNoAds|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbNoAdsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbNoAdsPeer::NO_ADS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbNoAdsPeer::NO_ADS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the no_ads_id column
	 * 
	 * @param     int|array $noAdsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByNoAdsId($noAdsId = null, $comparison = null)
	{
		if (is_array($noAdsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbNoAdsPeer::NO_ADS_ID, $noAdsId, $comparison);
	}

	/**
	 * Filter the query on the no_ads_user_id_ip column
	 * 
	 * @param     string $noAdsUserIdIp The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByNoAdsUserIdIp($noAdsUserIdIp = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($noAdsUserIdIp)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $noAdsUserIdIp)) {
				$noAdsUserIdIp = str_replace('*', '%', $noAdsUserIdIp);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbNoAdsPeer::NO_ADS_USER_ID_IP, $noAdsUserIdIp, $comparison);
	}

	/**
	 * Filter the query on the no_ads_slot_id column
	 * 
	 * @param     int|array $noAdsSlotId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByNoAdsSlotId($noAdsSlotId = null, $comparison = null)
	{
		if (is_array($noAdsSlotId)) {
			$useMinMax = false;
			if (isset($noAdsSlotId['min'])) {
				$this->addUsingAlias(FfbNoAdsPeer::NO_ADS_SLOT_ID, $noAdsSlotId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($noAdsSlotId['max'])) {
				$this->addUsingAlias(FfbNoAdsPeer::NO_ADS_SLOT_ID, $noAdsSlotId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbNoAdsPeer::NO_ADS_SLOT_ID, $noAdsSlotId, $comparison);
	}

	/**
	 * Filter the query by a related FfbAdsSlot object
	 *
	 * @param     FfbAdsSlot $ffbAdsSlot  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function filterByFfbAdsSlot($ffbAdsSlot, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbNoAdsPeer::NO_ADS_SLOT_ID, $ffbAdsSlot->getAdsSlotId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdsSlot relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
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
	 * Exclude object from result
	 *
	 * @param     FfbNoAds $ffbNoAds Object to remove from the list of results
	 *
	 * @return    FfbNoAdsQuery The current query, for fluid interface
	 */
	public function prune($ffbNoAds = null)
	{
		if ($ffbNoAds) {
			$this->addUsingAlias(FfbNoAdsPeer::NO_ADS_ID, $ffbNoAds->getNoAdsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbNoAdsQuery
