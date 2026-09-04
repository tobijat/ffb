<?php


/**
 * Base class that represents a query for the 'ffb_cronjob' table.
 *
 * 
 *
 * @method     FfbCronjobQuery orderByCronjobId($order = Criteria::ASC) Order by the cronjob_id column
 * @method     FfbCronjobQuery orderByCronjobDescription($order = Criteria::ASC) Order by the cronjob_description column
 * @method     FfbCronjobQuery orderByCronjobFunction($order = Criteria::ASC) Order by the cronjob_function column
 * @method     FfbCronjobQuery orderByCronjobTimeStart($order = Criteria::ASC) Order by the cronjob_time_start column
 * @method     FfbCronjobQuery orderByCronjobTimeEnd($order = Criteria::ASC) Order by the cronjob_time_end column
 * @method     FfbCronjobQuery orderByCronjobTimeLastrun($order = Criteria::ASC) Order by the cronjob_time_lastrun column
 * @method     FfbCronjobQuery orderByCronjobStatus($order = Criteria::ASC) Order by the cronjob_status column
 * @method     FfbCronjobQuery orderByCronjobIntervalHours($order = Criteria::ASC) Order by the cronjob_interval_hours column
 * @method     FfbCronjobQuery orderByCronjobRunonce($order = Criteria::ASC) Order by the cronjob_runonce column
 * @method     FfbCronjobQuery orderByCronjobRunhour($order = Criteria::ASC) Order by the cronjob_runhour column
 *
 * @method     FfbCronjobQuery groupByCronjobId() Group by the cronjob_id column
 * @method     FfbCronjobQuery groupByCronjobDescription() Group by the cronjob_description column
 * @method     FfbCronjobQuery groupByCronjobFunction() Group by the cronjob_function column
 * @method     FfbCronjobQuery groupByCronjobTimeStart() Group by the cronjob_time_start column
 * @method     FfbCronjobQuery groupByCronjobTimeEnd() Group by the cronjob_time_end column
 * @method     FfbCronjobQuery groupByCronjobTimeLastrun() Group by the cronjob_time_lastrun column
 * @method     FfbCronjobQuery groupByCronjobStatus() Group by the cronjob_status column
 * @method     FfbCronjobQuery groupByCronjobIntervalHours() Group by the cronjob_interval_hours column
 * @method     FfbCronjobQuery groupByCronjobRunonce() Group by the cronjob_runonce column
 * @method     FfbCronjobQuery groupByCronjobRunhour() Group by the cronjob_runhour column
 *
 * @method     FfbCronjobQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbCronjobQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbCronjobQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbCronjob findOne(PropelPDO $con = null) Return the first FfbCronjob matching the query
 * @method     FfbCronjob findOneOrCreate(PropelPDO $con = null) Return the first FfbCronjob matching the query, or a new FfbCronjob object populated from the query conditions when no match is found
 *
 * @method     FfbCronjob findOneByCronjobId(int $cronjob_id) Return the first FfbCronjob filtered by the cronjob_id column
 * @method     FfbCronjob findOneByCronjobDescription(string $cronjob_description) Return the first FfbCronjob filtered by the cronjob_description column
 * @method     FfbCronjob findOneByCronjobFunction(string $cronjob_function) Return the first FfbCronjob filtered by the cronjob_function column
 * @method     FfbCronjob findOneByCronjobTimeStart(string $cronjob_time_start) Return the first FfbCronjob filtered by the cronjob_time_start column
 * @method     FfbCronjob findOneByCronjobTimeEnd(string $cronjob_time_end) Return the first FfbCronjob filtered by the cronjob_time_end column
 * @method     FfbCronjob findOneByCronjobTimeLastrun(string $cronjob_time_lastrun) Return the first FfbCronjob filtered by the cronjob_time_lastrun column
 * @method     FfbCronjob findOneByCronjobStatus(boolean $cronjob_status) Return the first FfbCronjob filtered by the cronjob_status column
 * @method     FfbCronjob findOneByCronjobIntervalHours(int $cronjob_interval_hours) Return the first FfbCronjob filtered by the cronjob_interval_hours column
 * @method     FfbCronjob findOneByCronjobRunonce(boolean $cronjob_runonce) Return the first FfbCronjob filtered by the cronjob_runonce column
 * @method     FfbCronjob findOneByCronjobRunhour(int $cronjob_runhour) Return the first FfbCronjob filtered by the cronjob_runhour column
 *
 * @method     array findByCronjobId(int $cronjob_id) Return FfbCronjob objects filtered by the cronjob_id column
 * @method     array findByCronjobDescription(string $cronjob_description) Return FfbCronjob objects filtered by the cronjob_description column
 * @method     array findByCronjobFunction(string $cronjob_function) Return FfbCronjob objects filtered by the cronjob_function column
 * @method     array findByCronjobTimeStart(string $cronjob_time_start) Return FfbCronjob objects filtered by the cronjob_time_start column
 * @method     array findByCronjobTimeEnd(string $cronjob_time_end) Return FfbCronjob objects filtered by the cronjob_time_end column
 * @method     array findByCronjobTimeLastrun(string $cronjob_time_lastrun) Return FfbCronjob objects filtered by the cronjob_time_lastrun column
 * @method     array findByCronjobStatus(boolean $cronjob_status) Return FfbCronjob objects filtered by the cronjob_status column
 * @method     array findByCronjobIntervalHours(int $cronjob_interval_hours) Return FfbCronjob objects filtered by the cronjob_interval_hours column
 * @method     array findByCronjobRunonce(boolean $cronjob_runonce) Return FfbCronjob objects filtered by the cronjob_runonce column
 * @method     array findByCronjobRunhour(int $cronjob_runhour) Return FfbCronjob objects filtered by the cronjob_runhour column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbCronjobQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbCronjobQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbCronjob', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbCronjobQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbCronjobQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbCronjobQuery) {
			return $criteria;
		}
		$query = new FfbCronjobQuery();
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
	 * @return    FfbCronjob|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbCronjobPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the cronjob_id column
	 * 
	 * @param     int|array $cronjobId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobId($cronjobId = null, $comparison = null)
	{
		if (is_array($cronjobId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_ID, $cronjobId, $comparison);
	}

	/**
	 * Filter the query on the cronjob_description column
	 * 
	 * @param     string $cronjobDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobDescription($cronjobDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($cronjobDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $cronjobDescription)) {
				$cronjobDescription = str_replace('*', '%', $cronjobDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_DESCRIPTION, $cronjobDescription, $comparison);
	}

	/**
	 * Filter the query on the cronjob_function column
	 * 
	 * @param     string $cronjobFunction The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobFunction($cronjobFunction = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($cronjobFunction)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $cronjobFunction)) {
				$cronjobFunction = str_replace('*', '%', $cronjobFunction);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_FUNCTION, $cronjobFunction, $comparison);
	}

	/**
	 * Filter the query on the cronjob_time_start column
	 * 
	 * @param     string|array $cronjobTimeStart The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobTimeStart($cronjobTimeStart = null, $comparison = null)
	{
		if (is_array($cronjobTimeStart)) {
			$useMinMax = false;
			if (isset($cronjobTimeStart['min'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_START, $cronjobTimeStart['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($cronjobTimeStart['max'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_START, $cronjobTimeStart['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_START, $cronjobTimeStart, $comparison);
	}

	/**
	 * Filter the query on the cronjob_time_end column
	 * 
	 * @param     string|array $cronjobTimeEnd The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobTimeEnd($cronjobTimeEnd = null, $comparison = null)
	{
		if (is_array($cronjobTimeEnd)) {
			$useMinMax = false;
			if (isset($cronjobTimeEnd['min'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_END, $cronjobTimeEnd['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($cronjobTimeEnd['max'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_END, $cronjobTimeEnd['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_END, $cronjobTimeEnd, $comparison);
	}

	/**
	 * Filter the query on the cronjob_time_lastrun column
	 * 
	 * @param     string|array $cronjobTimeLastrun The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobTimeLastrun($cronjobTimeLastrun = null, $comparison = null)
	{
		if (is_array($cronjobTimeLastrun)) {
			$useMinMax = false;
			if (isset($cronjobTimeLastrun['min'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_LASTRUN, $cronjobTimeLastrun['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($cronjobTimeLastrun['max'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_LASTRUN, $cronjobTimeLastrun['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_TIME_LASTRUN, $cronjobTimeLastrun, $comparison);
	}

	/**
	 * Filter the query on the cronjob_status column
	 * 
	 * @param     boolean|string $cronjobStatus The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobStatus($cronjobStatus = null, $comparison = null)
	{
		if (is_string($cronjobStatus)) {
			$cronjob_status = in_array(strtolower($cronjobStatus), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_STATUS, $cronjobStatus, $comparison);
	}

	/**
	 * Filter the query on the cronjob_interval_hours column
	 * 
	 * @param     int|array $cronjobIntervalHours The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobIntervalHours($cronjobIntervalHours = null, $comparison = null)
	{
		if (is_array($cronjobIntervalHours)) {
			$useMinMax = false;
			if (isset($cronjobIntervalHours['min'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_INTERVAL_HOURS, $cronjobIntervalHours['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($cronjobIntervalHours['max'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_INTERVAL_HOURS, $cronjobIntervalHours['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_INTERVAL_HOURS, $cronjobIntervalHours, $comparison);
	}

	/**
	 * Filter the query on the cronjob_runonce column
	 * 
	 * @param     boolean|string $cronjobRunonce The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobRunonce($cronjobRunonce = null, $comparison = null)
	{
		if (is_string($cronjobRunonce)) {
			$cronjob_runonce = in_array(strtolower($cronjobRunonce), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_RUNONCE, $cronjobRunonce, $comparison);
	}

	/**
	 * Filter the query on the cronjob_runhour column
	 * 
	 * @param     int|array $cronjobRunhour The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function filterByCronjobRunhour($cronjobRunhour = null, $comparison = null)
	{
		if (is_array($cronjobRunhour)) {
			$useMinMax = false;
			if (isset($cronjobRunhour['min'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_RUNHOUR, $cronjobRunhour['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($cronjobRunhour['max'])) {
				$this->addUsingAlias(FfbCronjobPeer::CRONJOB_RUNHOUR, $cronjobRunhour['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbCronjobPeer::CRONJOB_RUNHOUR, $cronjobRunhour, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbCronjob $ffbCronjob Object to remove from the list of results
	 *
	 * @return    FfbCronjobQuery The current query, for fluid interface
	 */
	public function prune($ffbCronjob = null)
	{
		if ($ffbCronjob) {
			$this->addUsingAlias(FfbCronjobPeer::CRONJOB_ID, $ffbCronjob->getCronjobId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbCronjobQuery
