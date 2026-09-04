<?php


/**
 * Base class that represents a query for the 'web_log' table.
 *
 * 
 *
 * @method     WebLogQuery orderByLogId($order = Criteria::ASC) Order by the log_id column
 * @method     WebLogQuery orderByLogUserId($order = Criteria::ASC) Order by the log_user_id column
 * @method     WebLogQuery orderByLogUserNickname($order = Criteria::ASC) Order by the log_user_nickname column
 * @method     WebLogQuery orderByLogUserIp($order = Criteria::ASC) Order by the log_user_ip column
 * @method     WebLogQuery orderByLogModule($order = Criteria::ASC) Order by the log_module column
 * @method     WebLogQuery orderByLogClass($order = Criteria::ASC) Order by the log_class column
 * @method     WebLogQuery orderByLogEvent($order = Criteria::ASC) Order by the log_event column
 * @method     WebLogQuery orderByLogPresenter($order = Criteria::ASC) Order by the log_presenter column
 * @method     WebLogQuery orderByLogSubdomain($order = Criteria::ASC) Order by the log_subdomain column
 * @method     WebLogQuery orderByLogDate($order = Criteria::ASC) Order by the log_date column
 *
 * @method     WebLogQuery groupByLogId() Group by the log_id column
 * @method     WebLogQuery groupByLogUserId() Group by the log_user_id column
 * @method     WebLogQuery groupByLogUserNickname() Group by the log_user_nickname column
 * @method     WebLogQuery groupByLogUserIp() Group by the log_user_ip column
 * @method     WebLogQuery groupByLogModule() Group by the log_module column
 * @method     WebLogQuery groupByLogClass() Group by the log_class column
 * @method     WebLogQuery groupByLogEvent() Group by the log_event column
 * @method     WebLogQuery groupByLogPresenter() Group by the log_presenter column
 * @method     WebLogQuery groupByLogSubdomain() Group by the log_subdomain column
 * @method     WebLogQuery groupByLogDate() Group by the log_date column
 *
 * @method     WebLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebLogQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     WebLogQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     WebLogQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     WebLog findOne(PropelPDO $con = null) Return the first WebLog matching the query
 * @method     WebLog findOneOrCreate(PropelPDO $con = null) Return the first WebLog matching the query, or a new WebLog object populated from the query conditions when no match is found
 *
 * @method     WebLog findOneByLogId(int $log_id) Return the first WebLog filtered by the log_id column
 * @method     WebLog findOneByLogUserId(int $log_user_id) Return the first WebLog filtered by the log_user_id column
 * @method     WebLog findOneByLogUserNickname(string $log_user_nickname) Return the first WebLog filtered by the log_user_nickname column
 * @method     WebLog findOneByLogUserIp(string $log_user_ip) Return the first WebLog filtered by the log_user_ip column
 * @method     WebLog findOneByLogModule(string $log_module) Return the first WebLog filtered by the log_module column
 * @method     WebLog findOneByLogClass(string $log_class) Return the first WebLog filtered by the log_class column
 * @method     WebLog findOneByLogEvent(string $log_event) Return the first WebLog filtered by the log_event column
 * @method     WebLog findOneByLogPresenter(string $log_presenter) Return the first WebLog filtered by the log_presenter column
 * @method     WebLog findOneByLogSubdomain(string $log_subdomain) Return the first WebLog filtered by the log_subdomain column
 * @method     WebLog findOneByLogDate(string $log_date) Return the first WebLog filtered by the log_date column
 *
 * @method     array findByLogId(int $log_id) Return WebLog objects filtered by the log_id column
 * @method     array findByLogUserId(int $log_user_id) Return WebLog objects filtered by the log_user_id column
 * @method     array findByLogUserNickname(string $log_user_nickname) Return WebLog objects filtered by the log_user_nickname column
 * @method     array findByLogUserIp(string $log_user_ip) Return WebLog objects filtered by the log_user_ip column
 * @method     array findByLogModule(string $log_module) Return WebLog objects filtered by the log_module column
 * @method     array findByLogClass(string $log_class) Return WebLog objects filtered by the log_class column
 * @method     array findByLogEvent(string $log_event) Return WebLog objects filtered by the log_event column
 * @method     array findByLogPresenter(string $log_presenter) Return WebLog objects filtered by the log_presenter column
 * @method     array findByLogSubdomain(string $log_subdomain) Return WebLog objects filtered by the log_subdomain column
 * @method     array findByLogDate(string $log_date) Return WebLog objects filtered by the log_date column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebLogQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebLogQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebLog', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebLogQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebLogQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebLogQuery) {
			return $criteria;
		}
		$query = new WebLogQuery();
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
	 * @return    WebLog|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebLogPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebLogPeer::LOG_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebLogPeer::LOG_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the log_id column
	 * 
	 * @param     int|array $logId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogId($logId = null, $comparison = null)
	{
		if (is_array($logId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebLogPeer::LOG_ID, $logId, $comparison);
	}

	/**
	 * Filter the query on the log_user_id column
	 * 
	 * @param     int|array $logUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogUserId($logUserId = null, $comparison = null)
	{
		if (is_array($logUserId)) {
			$useMinMax = false;
			if (isset($logUserId['min'])) {
				$this->addUsingAlias(WebLogPeer::LOG_USER_ID, $logUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($logUserId['max'])) {
				$this->addUsingAlias(WebLogPeer::LOG_USER_ID, $logUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_USER_ID, $logUserId, $comparison);
	}

	/**
	 * Filter the query on the log_user_nickname column
	 * 
	 * @param     string $logUserNickname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogUserNickname($logUserNickname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logUserNickname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logUserNickname)) {
				$logUserNickname = str_replace('*', '%', $logUserNickname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_USER_NICKNAME, $logUserNickname, $comparison);
	}

	/**
	 * Filter the query on the log_user_ip column
	 * 
	 * @param     string $logUserIp The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogUserIp($logUserIp = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logUserIp)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logUserIp)) {
				$logUserIp = str_replace('*', '%', $logUserIp);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_USER_IP, $logUserIp, $comparison);
	}

	/**
	 * Filter the query on the log_module column
	 * 
	 * @param     string $logModule The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogModule($logModule = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logModule)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logModule)) {
				$logModule = str_replace('*', '%', $logModule);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_MODULE, $logModule, $comparison);
	}

	/**
	 * Filter the query on the log_class column
	 * 
	 * @param     string $logClass The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogClass($logClass = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logClass)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logClass)) {
				$logClass = str_replace('*', '%', $logClass);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_CLASS, $logClass, $comparison);
	}

	/**
	 * Filter the query on the log_event column
	 * 
	 * @param     string $logEvent The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogEvent($logEvent = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logEvent)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logEvent)) {
				$logEvent = str_replace('*', '%', $logEvent);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_EVENT, $logEvent, $comparison);
	}

	/**
	 * Filter the query on the log_presenter column
	 * 
	 * @param     string $logPresenter The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogPresenter($logPresenter = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logPresenter)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logPresenter)) {
				$logPresenter = str_replace('*', '%', $logPresenter);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_PRESENTER, $logPresenter, $comparison);
	}

	/**
	 * Filter the query on the log_subdomain column
	 * 
	 * @param     string $logSubdomain The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogSubdomain($logSubdomain = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($logSubdomain)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $logSubdomain)) {
				$logSubdomain = str_replace('*', '%', $logSubdomain);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_SUBDOMAIN, $logSubdomain, $comparison);
	}

	/**
	 * Filter the query on the log_date column
	 * 
	 * @param     string|array $logDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByLogDate($logDate = null, $comparison = null)
	{
		if (is_array($logDate)) {
			$useMinMax = false;
			if (isset($logDate['min'])) {
				$this->addUsingAlias(WebLogPeer::LOG_DATE, $logDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($logDate['max'])) {
				$this->addUsingAlias(WebLogPeer::LOG_DATE, $logDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebLogPeer::LOG_DATE, $logDate, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(WebLogPeer::LOG_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function joinWebUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUser');
		
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
			$this->addJoinObject($join, 'WebUser');
		}
		
		return $this;
	}

	/**
	 * Use the WebUser relation WebUser object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUser', 'WebUserQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     WebLog $webLog Object to remove from the list of results
	 *
	 * @return    WebLogQuery The current query, for fluid interface
	 */
	public function prune($webLog = null)
	{
		if ($webLog) {
			$this->addUsingAlias(WebLogPeer::LOG_ID, $webLog->getLogId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebLogQuery
