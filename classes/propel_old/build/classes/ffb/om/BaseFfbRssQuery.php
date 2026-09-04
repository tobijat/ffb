<?php


/**
 * Base class that represents a query for the 'ffb_rss' table.
 *
 * 
 *
 * @method     FfbRssQuery orderByFfbRssId($order = Criteria::ASC) Order by the ffb_rss_id column
 * @method     FfbRssQuery orderByFfbRssTitle($order = Criteria::ASC) Order by the ffb_rss_title column
 * @method     FfbRssQuery orderByFfbRssDescription($order = Criteria::ASC) Order by the ffb_rss_description column
 * @method     FfbRssQuery orderByFfbRssCategory($order = Criteria::ASC) Order by the ffb_rss_category column
 * @method     FfbRssQuery orderByFfbRssGuid($order = Criteria::ASC) Order by the ffb_rss_guid column
 * @method     FfbRssQuery orderByFfbRssAuthor($order = Criteria::ASC) Order by the ffb_rss_author column
 * @method     FfbRssQuery orderByFfbRssOriginId($order = Criteria::ASC) Order by the ffb_rss_origin_id column
 * @method     FfbRssQuery orderByFfbRssPubdate($order = Criteria::ASC) Order by the ffb_rss_pubdate column
 *
 * @method     FfbRssQuery groupByFfbRssId() Group by the ffb_rss_id column
 * @method     FfbRssQuery groupByFfbRssTitle() Group by the ffb_rss_title column
 * @method     FfbRssQuery groupByFfbRssDescription() Group by the ffb_rss_description column
 * @method     FfbRssQuery groupByFfbRssCategory() Group by the ffb_rss_category column
 * @method     FfbRssQuery groupByFfbRssGuid() Group by the ffb_rss_guid column
 * @method     FfbRssQuery groupByFfbRssAuthor() Group by the ffb_rss_author column
 * @method     FfbRssQuery groupByFfbRssOriginId() Group by the ffb_rss_origin_id column
 * @method     FfbRssQuery groupByFfbRssPubdate() Group by the ffb_rss_pubdate column
 *
 * @method     FfbRssQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbRssQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbRssQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbRss findOne(PropelPDO $con = null) Return the first FfbRss matching the query
 * @method     FfbRss findOneOrCreate(PropelPDO $con = null) Return the first FfbRss matching the query, or a new FfbRss object populated from the query conditions when no match is found
 *
 * @method     FfbRss findOneByFfbRssId(int $ffb_rss_id) Return the first FfbRss filtered by the ffb_rss_id column
 * @method     FfbRss findOneByFfbRssTitle(string $ffb_rss_title) Return the first FfbRss filtered by the ffb_rss_title column
 * @method     FfbRss findOneByFfbRssDescription(string $ffb_rss_description) Return the first FfbRss filtered by the ffb_rss_description column
 * @method     FfbRss findOneByFfbRssCategory(string $ffb_rss_category) Return the first FfbRss filtered by the ffb_rss_category column
 * @method     FfbRss findOneByFfbRssGuid(string $ffb_rss_guid) Return the first FfbRss filtered by the ffb_rss_guid column
 * @method     FfbRss findOneByFfbRssAuthor(string $ffb_rss_author) Return the first FfbRss filtered by the ffb_rss_author column
 * @method     FfbRss findOneByFfbRssOriginId(int $ffb_rss_origin_id) Return the first FfbRss filtered by the ffb_rss_origin_id column
 * @method     FfbRss findOneByFfbRssPubdate(string $ffb_rss_pubdate) Return the first FfbRss filtered by the ffb_rss_pubdate column
 *
 * @method     array findByFfbRssId(int $ffb_rss_id) Return FfbRss objects filtered by the ffb_rss_id column
 * @method     array findByFfbRssTitle(string $ffb_rss_title) Return FfbRss objects filtered by the ffb_rss_title column
 * @method     array findByFfbRssDescription(string $ffb_rss_description) Return FfbRss objects filtered by the ffb_rss_description column
 * @method     array findByFfbRssCategory(string $ffb_rss_category) Return FfbRss objects filtered by the ffb_rss_category column
 * @method     array findByFfbRssGuid(string $ffb_rss_guid) Return FfbRss objects filtered by the ffb_rss_guid column
 * @method     array findByFfbRssAuthor(string $ffb_rss_author) Return FfbRss objects filtered by the ffb_rss_author column
 * @method     array findByFfbRssOriginId(int $ffb_rss_origin_id) Return FfbRss objects filtered by the ffb_rss_origin_id column
 * @method     array findByFfbRssPubdate(string $ffb_rss_pubdate) Return FfbRss objects filtered by the ffb_rss_pubdate column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbRssQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbRssQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbRss', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbRssQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbRssQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbRssQuery) {
			return $criteria;
		}
		$query = new FfbRssQuery();
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
	 * @return    FfbRss|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbRssPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the ffb_rss_id column
	 * 
	 * @param     int|array $ffbRssId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssId($ffbRssId = null, $comparison = null)
	{
		if (is_array($ffbRssId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_ID, $ffbRssId, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_title column
	 * 
	 * @param     string $ffbRssTitle The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssTitle($ffbRssTitle = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssTitle)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssTitle)) {
				$ffbRssTitle = str_replace('*', '%', $ffbRssTitle);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_TITLE, $ffbRssTitle, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_description column
	 * 
	 * @param     string $ffbRssDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssDescription($ffbRssDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssDescription)) {
				$ffbRssDescription = str_replace('*', '%', $ffbRssDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_DESCRIPTION, $ffbRssDescription, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_category column
	 * 
	 * @param     string $ffbRssCategory The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssCategory($ffbRssCategory = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssCategory)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssCategory)) {
				$ffbRssCategory = str_replace('*', '%', $ffbRssCategory);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_CATEGORY, $ffbRssCategory, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_guid column
	 * 
	 * @param     string $ffbRssGuid The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssGuid($ffbRssGuid = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssGuid)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssGuid)) {
				$ffbRssGuid = str_replace('*', '%', $ffbRssGuid);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_GUID, $ffbRssGuid, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_author column
	 * 
	 * @param     string $ffbRssAuthor The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssAuthor($ffbRssAuthor = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($ffbRssAuthor)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $ffbRssAuthor)) {
				$ffbRssAuthor = str_replace('*', '%', $ffbRssAuthor);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_AUTHOR, $ffbRssAuthor, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_origin_id column
	 * 
	 * @param     int|array $ffbRssOriginId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssOriginId($ffbRssOriginId = null, $comparison = null)
	{
		if (is_array($ffbRssOriginId)) {
			$useMinMax = false;
			if (isset($ffbRssOriginId['min'])) {
				$this->addUsingAlias(FfbRssPeer::FFB_RSS_ORIGIN_ID, $ffbRssOriginId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($ffbRssOriginId['max'])) {
				$this->addUsingAlias(FfbRssPeer::FFB_RSS_ORIGIN_ID, $ffbRssOriginId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_ORIGIN_ID, $ffbRssOriginId, $comparison);
	}

	/**
	 * Filter the query on the ffb_rss_pubdate column
	 * 
	 * @param     string|array $ffbRssPubdate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function filterByFfbRssPubdate($ffbRssPubdate = null, $comparison = null)
	{
		if (is_array($ffbRssPubdate)) {
			$useMinMax = false;
			if (isset($ffbRssPubdate['min'])) {
				$this->addUsingAlias(FfbRssPeer::FFB_RSS_PUBDATE, $ffbRssPubdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($ffbRssPubdate['max'])) {
				$this->addUsingAlias(FfbRssPeer::FFB_RSS_PUBDATE, $ffbRssPubdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbRssPeer::FFB_RSS_PUBDATE, $ffbRssPubdate, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbRss $ffbRss Object to remove from the list of results
	 *
	 * @return    FfbRssQuery The current query, for fluid interface
	 */
	public function prune($ffbRss = null)
	{
		if ($ffbRss) {
			$this->addUsingAlias(FfbRssPeer::FFB_RSS_ID, $ffbRss->getFfbRssId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbRssQuery
