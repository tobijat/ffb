<?php


/**
 * Base class that represents a query for the 'ffb_ads_slot' table.
 *
 * 
 *
 * @method     FfbAdsSlotQuery orderByAdsSlotId($order = Criteria::ASC) Order by the ads_slot_id column
 * @method     FfbAdsSlotQuery orderByAdsSlotName($order = Criteria::ASC) Order by the ads_slot_name column
 * @method     FfbAdsSlotQuery orderByAdsSlotCssClass($order = Criteria::ASC) Order by the ads_slot_css_class column
 *
 * @method     FfbAdsSlotQuery groupByAdsSlotId() Group by the ads_slot_id column
 * @method     FfbAdsSlotQuery groupByAdsSlotName() Group by the ads_slot_name column
 * @method     FfbAdsSlotQuery groupByAdsSlotCssClass() Group by the ads_slot_css_class column
 *
 * @method     FfbAdsSlotQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbAdsSlotQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbAdsSlotQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbAdsSlotQuery leftJoinFfbAdsAllocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdsAllocation relation
 * @method     FfbAdsSlotQuery rightJoinFfbAdsAllocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdsAllocation relation
 * @method     FfbAdsSlotQuery innerJoinFfbAdsAllocation($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdsAllocation relation
 *
 * @method     FfbAdsSlotQuery leftJoinFfbNoAds($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbNoAds relation
 * @method     FfbAdsSlotQuery rightJoinFfbNoAds($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbNoAds relation
 * @method     FfbAdsSlotQuery innerJoinFfbNoAds($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbNoAds relation
 *
 * @method     FfbAdsSlot findOne(PropelPDO $con = null) Return the first FfbAdsSlot matching the query
 * @method     FfbAdsSlot findOneOrCreate(PropelPDO $con = null) Return the first FfbAdsSlot matching the query, or a new FfbAdsSlot object populated from the query conditions when no match is found
 *
 * @method     FfbAdsSlot findOneByAdsSlotId(int $ads_slot_id) Return the first FfbAdsSlot filtered by the ads_slot_id column
 * @method     FfbAdsSlot findOneByAdsSlotName(string $ads_slot_name) Return the first FfbAdsSlot filtered by the ads_slot_name column
 * @method     FfbAdsSlot findOneByAdsSlotCssClass(string $ads_slot_css_class) Return the first FfbAdsSlot filtered by the ads_slot_css_class column
 *
 * @method     array findByAdsSlotId(int $ads_slot_id) Return FfbAdsSlot objects filtered by the ads_slot_id column
 * @method     array findByAdsSlotName(string $ads_slot_name) Return FfbAdsSlot objects filtered by the ads_slot_name column
 * @method     array findByAdsSlotCssClass(string $ads_slot_css_class) Return FfbAdsSlot objects filtered by the ads_slot_css_class column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbAdsSlotQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbAdsSlotQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbAdsSlot', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbAdsSlotQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbAdsSlotQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbAdsSlotQuery) {
			return $criteria;
		}
		$query = new FfbAdsSlotQuery();
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
	 * @return    FfbAdsSlot|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbAdsSlotPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the ads_slot_id column
	 * 
	 * @param     int|array $adsSlotId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByAdsSlotId($adsSlotId = null, $comparison = null)
	{
		if (is_array($adsSlotId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $adsSlotId, $comparison);
	}

	/**
	 * Filter the query on the ads_slot_name column
	 * 
	 * @param     string $adsSlotName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByAdsSlotName($adsSlotName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adsSlotName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adsSlotName)) {
				$adsSlotName = str_replace('*', '%', $adsSlotName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_NAME, $adsSlotName, $comparison);
	}

	/**
	 * Filter the query on the ads_slot_css_class column
	 * 
	 * @param     string $adsSlotCssClass The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByAdsSlotCssClass($adsSlotCssClass = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adsSlotCssClass)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adsSlotCssClass)) {
				$adsSlotCssClass = str_replace('*', '%', $adsSlotCssClass);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_CSS_CLASS, $adsSlotCssClass, $comparison);
	}

	/**
	 * Filter the query by a related FfbAdsAllocation object
	 *
	 * @param     FfbAdsAllocation $ffbAdsAllocation  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByFfbAdsAllocation($ffbAdsAllocation, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $ffbAdsAllocation->getAdsAllocationSlotId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdsAllocation relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbNoAds object
	 *
	 * @param     FfbNoAds $ffbNoAds  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function filterByFfbNoAds($ffbNoAds, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $ffbNoAds->getNoAdsSlotId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbNoAds relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function joinFfbNoAds($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbNoAds');
		
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
			$this->addJoinObject($join, 'FfbNoAds');
		}
		
		return $this;
	}

	/**
	 * Use the FfbNoAds relation FfbNoAds object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbNoAdsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbNoAdsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbNoAds($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbNoAds', 'FfbNoAdsQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbAdsSlot $ffbAdsSlot Object to remove from the list of results
	 *
	 * @return    FfbAdsSlotQuery The current query, for fluid interface
	 */
	public function prune($ffbAdsSlot = null)
	{
		if ($ffbAdsSlot) {
			$this->addUsingAlias(FfbAdsSlotPeer::ADS_SLOT_ID, $ffbAdsSlot->getAdsSlotId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbAdsSlotQuery
