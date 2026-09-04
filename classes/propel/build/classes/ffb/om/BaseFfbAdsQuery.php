<?php


/**
 * Base class that represents a query for the 'ffb_ads' table.
 *
 * 
 *
 * @method     FfbAdsQuery orderByAdsId($order = Criteria::ASC) Order by the ads_id column
 * @method     FfbAdsQuery orderByAdsName($order = Criteria::ASC) Order by the ads_name column
 * @method     FfbAdsQuery orderByAdsCode($order = Criteria::ASC) Order by the ads_code column
 *
 * @method     FfbAdsQuery groupByAdsId() Group by the ads_id column
 * @method     FfbAdsQuery groupByAdsName() Group by the ads_name column
 * @method     FfbAdsQuery groupByAdsCode() Group by the ads_code column
 *
 * @method     FfbAdsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbAdsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbAdsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbAdsQuery leftJoinFfbAdsAllocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdsAllocation relation
 * @method     FfbAdsQuery rightJoinFfbAdsAllocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdsAllocation relation
 * @method     FfbAdsQuery innerJoinFfbAdsAllocation($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdsAllocation relation
 *
 * @method     FfbAds findOne(PropelPDO $con = null) Return the first FfbAds matching the query
 * @method     FfbAds findOneOrCreate(PropelPDO $con = null) Return the first FfbAds matching the query, or a new FfbAds object populated from the query conditions when no match is found
 *
 * @method     FfbAds findOneByAdsId(int $ads_id) Return the first FfbAds filtered by the ads_id column
 * @method     FfbAds findOneByAdsName(string $ads_name) Return the first FfbAds filtered by the ads_name column
 * @method     FfbAds findOneByAdsCode(string $ads_code) Return the first FfbAds filtered by the ads_code column
 *
 * @method     array findByAdsId(int $ads_id) Return FfbAds objects filtered by the ads_id column
 * @method     array findByAdsName(string $ads_name) Return FfbAds objects filtered by the ads_name column
 * @method     array findByAdsCode(string $ads_code) Return FfbAds objects filtered by the ads_code column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbAdsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbAdsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbAds', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbAdsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbAdsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbAdsQuery) {
			return $criteria;
		}
		$query = new FfbAdsQuery();
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
	 * @return    FfbAds|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbAdsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbAdsPeer::ADS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbAdsPeer::ADS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the ads_id column
	 * 
	 * @param     int|array $adsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByAdsId($adsId = null, $comparison = null)
	{
		if (is_array($adsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbAdsPeer::ADS_ID, $adsId, $comparison);
	}

	/**
	 * Filter the query on the ads_name column
	 * 
	 * @param     string $adsName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByAdsName($adsName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adsName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adsName)) {
				$adsName = str_replace('*', '%', $adsName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbAdsPeer::ADS_NAME, $adsName, $comparison);
	}

	/**
	 * Filter the query on the ads_code column
	 * 
	 * @param     string $adsCode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByAdsCode($adsCode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adsCode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adsCode)) {
				$adsCode = str_replace('*', '%', $adsCode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbAdsPeer::ADS_CODE, $adsCode, $comparison);
	}

	/**
	 * Filter the query by a related FfbAdsAllocation object
	 *
	 * @param     FfbAdsAllocation $ffbAdsAllocation  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function filterByFfbAdsAllocation($ffbAdsAllocation, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsPeer::ADS_ID, $ffbAdsAllocation->getAdsAllocationAdsId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdsAllocation relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function joinFfbAdsAllocation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbAdsAllocation');
		
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
			$this->addJoinObject($join, 'FfbAdsAllocation');
		}
		
		return $this;
	}

	/**
	 * Use the FfbAdsAllocation relation FfbAdsAllocation object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsAllocationQuery A secondary query class using the current class as primary query
	 */
	public function useFfbAdsAllocationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbAdsAllocation($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbAdsAllocation', 'FfbAdsAllocationQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbAds $ffbAds Object to remove from the list of results
	 *
	 * @return    FfbAdsQuery The current query, for fluid interface
	 */
	public function prune($ffbAds = null)
	{
		if ($ffbAds) {
			$this->addUsingAlias(FfbAdsPeer::ADS_ID, $ffbAds->getAdsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbAdsQuery
