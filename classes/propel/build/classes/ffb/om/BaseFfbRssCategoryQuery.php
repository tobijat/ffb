<?php


/**
 * Base class that represents a query for the 'ffb_rss_category' table.
 *
 * 
 *
 * @method     FfbRssCategoryQuery orderByFfbRssCategoryId($order = Criteria::ASC) Order by the ffb_rss_category_id column
 * @method     FfbRssCategoryQuery orderByFfbRssCategoryName($order = Criteria::ASC) Order by the ffb_rss_category_name column
 *
 * @method     FfbRssCategoryQuery groupByFfbRssCategoryId() Group by the ffb_rss_category_id column
 * @method     FfbRssCategoryQuery groupByFfbRssCategoryName() Group by the ffb_rss_category_name column
 *
 * @method     FfbRssCategoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbRssCategoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbRssCategoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbRssCategory findOne(?PropelPDO $con = null) Return the first FfbRssCategory matching the query
 * @method     FfbRssCategory findOneOrCreate(?PropelPDO $con = null) Return the first FfbRssCategory matching the query, or a new FfbRssCategory object populated from the query conditions when no match is found
 *
 * @method     FfbRssCategory findOneByFfbRssCategoryId(int $ffb_rss_category_id) Return the first FfbRssCategory filtered by the ffb_rss_category_id column
 * @method     FfbRssCategory findOneByFfbRssCategoryName(string $ffb_rss_category_name) Return the first FfbRssCategory filtered by the ffb_rss_category_name column
 *
 * @method     array findByFfbRssCategoryId(int $ffb_rss_category_id) Return FfbRssCategory objects filtered by the ffb_rss_category_id column
 * @method     array findByFfbRssCategoryName(string $ffb_rss_category_name) Return FfbRssCategory objects filtered by the ffb_rss_category_name column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbRssCategoryQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbRssCategoryQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbRssCategory', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbRssCategoryQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbRssCategoryQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbRssCategoryQuery) {
			return $criteria;
		}
		$query = new FfbRssCategoryQuery();
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
	 * @return    FfbRssCategory|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbRssCategoryPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbRssCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbRssCategoryPeer::FFB_RSS_CATEGORY_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbRssCategoryQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbRssCategoryPeer::FFB_RSS_CATEGORY_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the ffb_rss_category_id column
	 * 
	 * @param     int|array $ffbRssCategoryId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssCategoryQuery The current query, for fluid interface
	 */
	public function filterByFfbRssCategoryId($ffbRssCategoryId = null, $comparison = null)
	{
		if (is_array($ffbRssCategoryId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbRssCategoryPeer::FFB_RSS_CATEGORY_ID, $ffbRssCategoryId, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_category_name column
	 * 
	 * @param     string $ffbRssCategoryName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssCategoryQuery The current query, for fluid interface
	 */
	public function filterByFfbRssCategoryName($ffbRssCategoryName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssCategoryName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssCategoryName)) {
				$ffbRssCategoryName = str_replace('*', '%', $ffbRssCategoryName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssCategoryPeer::FFB_RSS_CATEGORY_NAME, $ffbRssCategoryName, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbRssCategory $ffbRssCategory Object to remove from the list of results
	 *
	 * @return    FfbRssCategoryQuery The current query, for fluid interface
	 */
	public function prune($ffbRssCategory = null)
	{
		if ($ffbRssCategory) {
			$this->addUsingAlias(FfbRssCategoryPeer::FFB_RSS_CATEGORY_ID, $ffbRssCategory->getFfbRssCategoryId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbRssCategoryQuery
