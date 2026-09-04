<?php


/**
 * Base class that represents a query for the 'web_user_permissions' table.
 *
 * 
 *
 * @method     WebUserPermissionsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     WebUserPermissionsQuery orderByUserPermissionsFfbMailserviceReminder($order = Criteria::ASC) Order by the user_permissions_ffb_mailservice_reminder column
 * @method     WebUserPermissionsQuery orderByUserPermissionsFfbMailserviceInfo($order = Criteria::ASC) Order by the user_permissions_ffb_mailservice_info column
 * @method     WebUserPermissionsQuery orderByUserPermissionsFfbVisibleProfile($order = Criteria::ASC) Order by the user_permissions_ffb_visible_profile column
 * @method     WebUserPermissionsQuery orderByUserPermissionsPictoryVisibleProfile($order = Criteria::ASC) Order by the user_permissions_pictory_visible_profile column
 *
 * @method     WebUserPermissionsQuery groupByUserId() Group by the user_id column
 * @method     WebUserPermissionsQuery groupByUserPermissionsFfbMailserviceReminder() Group by the user_permissions_ffb_mailservice_reminder column
 * @method     WebUserPermissionsQuery groupByUserPermissionsFfbMailserviceInfo() Group by the user_permissions_ffb_mailservice_info column
 * @method     WebUserPermissionsQuery groupByUserPermissionsFfbVisibleProfile() Group by the user_permissions_ffb_visible_profile column
 * @method     WebUserPermissionsQuery groupByUserPermissionsPictoryVisibleProfile() Group by the user_permissions_pictory_visible_profile column
 *
 * @method     WebUserPermissionsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebUserPermissionsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebUserPermissionsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebUserPermissionsQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     WebUserPermissionsQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     WebUserPermissionsQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     WebUserPermissions findOne(?PropelPDO $con = null) Return the first WebUserPermissions matching the query
 * @method     WebUserPermissions findOneOrCreate(?PropelPDO $con = null) Return the first WebUserPermissions matching the query, or a new WebUserPermissions object populated from the query conditions when no match is found
 *
 * @method     WebUserPermissions findOneByUserId(int $user_id) Return the first WebUserPermissions filtered by the user_id column
 * @method     WebUserPermissions findOneByUserPermissionsFfbMailserviceReminder(string $user_permissions_ffb_mailservice_reminder) Return the first WebUserPermissions filtered by the user_permissions_ffb_mailservice_reminder column
 * @method     WebUserPermissions findOneByUserPermissionsFfbMailserviceInfo(string $user_permissions_ffb_mailservice_info) Return the first WebUserPermissions filtered by the user_permissions_ffb_mailservice_info column
 * @method     WebUserPermissions findOneByUserPermissionsFfbVisibleProfile(boolean $user_permissions_ffb_visible_profile) Return the first WebUserPermissions filtered by the user_permissions_ffb_visible_profile column
 * @method     WebUserPermissions findOneByUserPermissionsPictoryVisibleProfile(boolean $user_permissions_pictory_visible_profile) Return the first WebUserPermissions filtered by the user_permissions_pictory_visible_profile column
 *
 * @method     array findByUserId(int $user_id) Return WebUserPermissions objects filtered by the user_id column
 * @method     array findByUserPermissionsFfbMailserviceReminder(string $user_permissions_ffb_mailservice_reminder) Return WebUserPermissions objects filtered by the user_permissions_ffb_mailservice_reminder column
 * @method     array findByUserPermissionsFfbMailserviceInfo(string $user_permissions_ffb_mailservice_info) Return WebUserPermissions objects filtered by the user_permissions_ffb_mailservice_info column
 * @method     array findByUserPermissionsFfbVisibleProfile(boolean $user_permissions_ffb_visible_profile) Return WebUserPermissions objects filtered by the user_permissions_ffb_visible_profile column
 * @method     array findByUserPermissionsPictoryVisibleProfile(boolean $user_permissions_pictory_visible_profile) Return WebUserPermissions objects filtered by the user_permissions_pictory_visible_profile column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebUserPermissionsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebUserPermissionsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebUserPermissions', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebUserPermissionsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebUserPermissionsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebUserPermissionsQuery) {
			return $criteria;
		}
		$query = new WebUserPermissionsQuery();
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
	 * @return    WebUserPermissions|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebUserPermissionsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_id column
	 * 
	 * @param     int|array $userId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the user_permissions_ffb_mailservice_reminder column
	 * 
	 * @param     string $userPermissionsFfbMailserviceReminder The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByUserPermissionsFfbMailserviceReminder($userPermissionsFfbMailserviceReminder = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userPermissionsFfbMailserviceReminder)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userPermissionsFfbMailserviceReminder)) {
				$userPermissionsFfbMailserviceReminder = str_replace('*', '%', $userPermissionsFfbMailserviceReminder);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER, $userPermissionsFfbMailserviceReminder, $comparison);
	}

	/**
	 * Filter the query on the user_permissions_ffb_mailservice_info column
	 * 
	 * @param     string $userPermissionsFfbMailserviceInfo The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByUserPermissionsFfbMailserviceInfo($userPermissionsFfbMailserviceInfo = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userPermissionsFfbMailserviceInfo)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userPermissionsFfbMailserviceInfo)) {
				$userPermissionsFfbMailserviceInfo = str_replace('*', '%', $userPermissionsFfbMailserviceInfo);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO, $userPermissionsFfbMailserviceInfo, $comparison);
	}

	/**
	 * Filter the query on the user_permissions_ffb_visible_profile column
	 * 
	 * @param     boolean|string $userPermissionsFfbVisibleProfile The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByUserPermissionsFfbVisibleProfile($userPermissionsFfbVisibleProfile = null, $comparison = null)
	{
		if (is_string($userPermissionsFfbVisibleProfile)) {
			$user_permissions_ffb_visible_profile = in_array(strtolower($userPermissionsFfbVisibleProfile), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_VISIBLE_PROFILE, $userPermissionsFfbVisibleProfile, $comparison);
	}

	/**
	 * Filter the query on the user_permissions_pictory_visible_profile column
	 * 
	 * @param     boolean|string $userPermissionsPictoryVisibleProfile The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByUserPermissionsPictoryVisibleProfile($userPermissionsPictoryVisibleProfile = null, $comparison = null)
	{
		if (is_string($userPermissionsPictoryVisibleProfile)) {
			$user_permissions_pictory_visible_profile = in_array(strtolower($userPermissionsPictoryVisibleProfile), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(WebUserPermissionsPeer::USER_PERMISSIONS_PICTORY_VISIBLE_PROFILE, $userPermissionsPictoryVisibleProfile, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPermissionsPeer::USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
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
	 * @param     WebUserPermissions $webUserPermissions Object to remove from the list of results
	 *
	 * @return    WebUserPermissionsQuery The current query, for fluid interface
	 */
	public function prune($webUserPermissions = null)
	{
		if ($webUserPermissions) {
			$this->addUsingAlias(WebUserPermissionsPeer::USER_ID, $webUserPermissions->getUserId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebUserPermissionsQuery
