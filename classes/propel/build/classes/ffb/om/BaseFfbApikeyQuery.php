<?php


/**
 * Base class that represents a query for the 'ffb_apikey' table.
 *
 * 
 *
 * @method     FfbApikeyQuery orderByApikeyId($order = Criteria::ASC) Order by the apikey_id column
 * @method     FfbApikeyQuery orderByApikeyKey($order = Criteria::ASC) Order by the apikey_key column
 * @method     FfbApikeyQuery orderByApikeyIp($order = Criteria::ASC) Order by the apikey_ip column
 * @method     FfbApikeyQuery orderByApikeyDescription($order = Criteria::ASC) Order by the apikey_description column
 * @method     FfbApikeyQuery orderByApikeyLastcall($order = Criteria::ASC) Order by the apikey_lastcall column
 * @method     FfbApikeyQuery orderByApikeyStatus($order = Criteria::ASC) Order by the apikey_status column
 *
 * @method     FfbApikeyQuery groupByApikeyId() Group by the apikey_id column
 * @method     FfbApikeyQuery groupByApikeyKey() Group by the apikey_key column
 * @method     FfbApikeyQuery groupByApikeyIp() Group by the apikey_ip column
 * @method     FfbApikeyQuery groupByApikeyDescription() Group by the apikey_description column
 * @method     FfbApikeyQuery groupByApikeyLastcall() Group by the apikey_lastcall column
 * @method     FfbApikeyQuery groupByApikeyStatus() Group by the apikey_status column
 *
 * @method     FfbApikeyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbApikeyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbApikeyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbApikey findOne(?PropelPDO $con = null) Return the first FfbApikey matching the query
 * @method     FfbApikey findOneOrCreate(?PropelPDO $con = null) Return the first FfbApikey matching the query, or a new FfbApikey object populated from the query conditions when no match is found
 *
 * @method     FfbApikey findOneByApikeyId(int $apikey_id) Return the first FfbApikey filtered by the apikey_id column
 * @method     FfbApikey findOneByApikeyKey(string $apikey_key) Return the first FfbApikey filtered by the apikey_key column
 * @method     FfbApikey findOneByApikeyIp(string $apikey_ip) Return the first FfbApikey filtered by the apikey_ip column
 * @method     FfbApikey findOneByApikeyDescription(string $apikey_description) Return the first FfbApikey filtered by the apikey_description column
 * @method     FfbApikey findOneByApikeyLastcall(string $apikey_lastcall) Return the first FfbApikey filtered by the apikey_lastcall column
 * @method     FfbApikey findOneByApikeyStatus(boolean $apikey_status) Return the first FfbApikey filtered by the apikey_status column
 *
 * @method     array findByApikeyId(int $apikey_id) Return FfbApikey objects filtered by the apikey_id column
 * @method     array findByApikeyKey(string $apikey_key) Return FfbApikey objects filtered by the apikey_key column
 * @method     array findByApikeyIp(string $apikey_ip) Return FfbApikey objects filtered by the apikey_ip column
 * @method     array findByApikeyDescription(string $apikey_description) Return FfbApikey objects filtered by the apikey_description column
 * @method     array findByApikeyLastcall(string $apikey_lastcall) Return FfbApikey objects filtered by the apikey_lastcall column
 * @method     array findByApikeyStatus(boolean $apikey_status) Return FfbApikey objects filtered by the apikey_status column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbApikeyQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbApikeyQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbApikey', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbApikeyQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbApikeyQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbApikeyQuery) {
			return $criteria;
		}
		$query = new FfbApikeyQuery();
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
	 * @return    FfbApikey|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbApikeyPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the apikey_id column
	 * 
	 * @param     int|array $apikeyId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyId($apikeyId = null, $comparison = null)
	{
		if (is_array($apikeyId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_ID, $apikeyId, $comparison);
	}

	/**
	 * Filter the query on the apikey_key column
	 * 
	 * @param     string $apikeyKey The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyKey($apikeyKey = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($apikeyKey)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $apikeyKey)) {
				$apikeyKey = str_replace('*', '%', $apikeyKey);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_KEY, $apikeyKey, $comparison);
	}

	/**
	 * Filter the query on the apikey_ip column
	 * 
	 * @param     string $apikeyIp The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyIp($apikeyIp = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($apikeyIp)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $apikeyIp)) {
				$apikeyIp = str_replace('*', '%', $apikeyIp);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_IP, $apikeyIp, $comparison);
	}

	/**
	 * Filter the query on the apikey_description column
	 * 
	 * @param     string $apikeyDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyDescription($apikeyDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($apikeyDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $apikeyDescription)) {
				$apikeyDescription = str_replace('*', '%', $apikeyDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_DESCRIPTION, $apikeyDescription, $comparison);
	}

	/**
	 * Filter the query on the apikey_lastcall column
	 * 
	 * @param     string|array $apikeyLastcall The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyLastcall($apikeyLastcall = null, $comparison = null)
	{
		if (is_array($apikeyLastcall)) {
			$useMinMax = false;
			if (isset($apikeyLastcall['min'])) {
				$this->addUsingAlias(FfbApikeyPeer::APIKEY_LASTCALL, $apikeyLastcall['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($apikeyLastcall['max'])) {
				$this->addUsingAlias(FfbApikeyPeer::APIKEY_LASTCALL, $apikeyLastcall['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_LASTCALL, $apikeyLastcall, $comparison);
	}

	/**
	 * Filter the query on the apikey_status column
	 * 
	 * @param     boolean|string $apikeyStatus The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function filterByApikeyStatus($apikeyStatus = null, $comparison = null)
	{
		if (is_string($apikeyStatus)) {
			$apikey_status = in_array(strtolower($apikeyStatus), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbApikeyPeer::APIKEY_STATUS, $apikeyStatus, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbApikey $ffbApikey Object to remove from the list of results
	 *
	 * @return    FfbApikeyQuery The current query, for fluid interface
	 */
	public function prune($ffbApikey = null)
	{
		if ($ffbApikey) {
			$this->addUsingAlias(FfbApikeyPeer::APIKEY_ID, $ffbApikey->getApikeyId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbApikeyQuery
