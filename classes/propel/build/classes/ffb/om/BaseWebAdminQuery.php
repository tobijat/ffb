<?php


/**
 * Base class that represents a query for the 'web_admin' table.
 *
 * 
 *
 * @method     WebAdminQuery orderByAdminId($order = Criteria::ASC) Order by the admin_id column
 * @method     WebAdminQuery orderByAdminUserId($order = Criteria::ASC) Order by the admin_user_id column
 * @method     WebAdminQuery orderByAdminSection($order = Criteria::ASC) Order by the admin_section column
 * @method     WebAdminQuery orderByAdminLevel($order = Criteria::ASC) Order by the admin_level column
 * @method     WebAdminQuery orderByAdminStatus($order = Criteria::ASC) Order by the admin_status column
 *
 * @method     WebAdminQuery groupByAdminId() Group by the admin_id column
 * @method     WebAdminQuery groupByAdminUserId() Group by the admin_user_id column
 * @method     WebAdminQuery groupByAdminSection() Group by the admin_section column
 * @method     WebAdminQuery groupByAdminLevel() Group by the admin_level column
 * @method     WebAdminQuery groupByAdminStatus() Group by the admin_status column
 *
 * @method     WebAdminQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebAdminQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebAdminQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebAdminQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     WebAdminQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     WebAdminQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     WebAdmin findOne(PropelPDO $con = null) Return the first WebAdmin matching the query
 * @method     WebAdmin findOneOrCreate(PropelPDO $con = null) Return the first WebAdmin matching the query, or a new WebAdmin object populated from the query conditions when no match is found
 *
 * @method     WebAdmin findOneByAdminId(int $admin_id) Return the first WebAdmin filtered by the admin_id column
 * @method     WebAdmin findOneByAdminUserId(int $admin_user_id) Return the first WebAdmin filtered by the admin_user_id column
 * @method     WebAdmin findOneByAdminSection(string $admin_section) Return the first WebAdmin filtered by the admin_section column
 * @method     WebAdmin findOneByAdminLevel(int $admin_level) Return the first WebAdmin filtered by the admin_level column
 * @method     WebAdmin findOneByAdminStatus(string $admin_status) Return the first WebAdmin filtered by the admin_status column
 *
 * @method     array findByAdminId(int $admin_id) Return WebAdmin objects filtered by the admin_id column
 * @method     array findByAdminUserId(int $admin_user_id) Return WebAdmin objects filtered by the admin_user_id column
 * @method     array findByAdminSection(string $admin_section) Return WebAdmin objects filtered by the admin_section column
 * @method     array findByAdminLevel(int $admin_level) Return WebAdmin objects filtered by the admin_level column
 * @method     array findByAdminStatus(string $admin_status) Return WebAdmin objects filtered by the admin_status column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebAdminQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebAdminQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebAdmin', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebAdminQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebAdminQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebAdminQuery) {
			return $criteria;
		}
		$query = new WebAdminQuery();
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
	 * @return    WebAdmin|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebAdminPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebAdminPeer::ADMIN_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebAdminPeer::ADMIN_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the admin_id column
	 * 
	 * @param     int|array $adminId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByAdminId($adminId = null, $comparison = null)
	{
		if (is_array($adminId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebAdminPeer::ADMIN_ID, $adminId, $comparison);
	}

	/**
	 * Filter the query on the admin_user_id column
	 * 
	 * @param     int|array $adminUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByAdminUserId($adminUserId = null, $comparison = null)
	{
		if (is_array($adminUserId)) {
			$useMinMax = false;
			if (isset($adminUserId['min'])) {
				$this->addUsingAlias(WebAdminPeer::ADMIN_USER_ID, $adminUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adminUserId['max'])) {
				$this->addUsingAlias(WebAdminPeer::ADMIN_USER_ID, $adminUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebAdminPeer::ADMIN_USER_ID, $adminUserId, $comparison);
	}

	/**
	 * Filter the query on the admin_section column
	 * 
	 * @param     string $adminSection The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByAdminSection($adminSection = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adminSection)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adminSection)) {
				$adminSection = str_replace('*', '%', $adminSection);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebAdminPeer::ADMIN_SECTION, $adminSection, $comparison);
	}

	/**
	 * Filter the query on the admin_level column
	 * 
	 * @param     int|array $adminLevel The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByAdminLevel($adminLevel = null, $comparison = null)
	{
		if (is_array($adminLevel)) {
			$useMinMax = false;
			if (isset($adminLevel['min'])) {
				$this->addUsingAlias(WebAdminPeer::ADMIN_LEVEL, $adminLevel['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($adminLevel['max'])) {
				$this->addUsingAlias(WebAdminPeer::ADMIN_LEVEL, $adminLevel['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebAdminPeer::ADMIN_LEVEL, $adminLevel, $comparison);
	}

	/**
	 * Filter the query on the admin_status column
	 * 
	 * @param     string $adminStatus The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByAdminStatus($adminStatus = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($adminStatus)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $adminStatus)) {
				$adminStatus = str_replace('*', '%', $adminStatus);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebAdminPeer::ADMIN_STATUS, $adminStatus, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(WebAdminPeer::ADMIN_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
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
	 * @param     WebAdmin $webAdmin Object to remove from the list of results
	 *
	 * @return    WebAdminQuery The current query, for fluid interface
	 */
	public function prune($webAdmin = null)
	{
		if ($webAdmin) {
			$this->addUsingAlias(WebAdminPeer::ADMIN_ID, $webAdmin->getAdminId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebAdminQuery
