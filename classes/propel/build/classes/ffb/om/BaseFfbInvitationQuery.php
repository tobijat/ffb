<?php


/**
 * Base class that represents a query for the 'ffb_invitation' table.
 *
 * 
 *
 * @method     FfbInvitationQuery orderByInvitationId($order = Criteria::ASC) Order by the invitation_id column
 * @method     FfbInvitationQuery orderByInvitationSenderId($order = Criteria::ASC) Order by the invitation_sender_id column
 * @method     FfbInvitationQuery orderByInvitationEmail($order = Criteria::ASC) Order by the invitation_email column
 * @method     FfbInvitationQuery orderByInvitationDate($order = Criteria::ASC) Order by the invitation_date column
 *
 * @method     FfbInvitationQuery groupByInvitationId() Group by the invitation_id column
 * @method     FfbInvitationQuery groupByInvitationSenderId() Group by the invitation_sender_id column
 * @method     FfbInvitationQuery groupByInvitationEmail() Group by the invitation_email column
 * @method     FfbInvitationQuery groupByInvitationDate() Group by the invitation_date column
 *
 * @method     FfbInvitationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbInvitationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbInvitationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbInvitationQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbInvitationQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbInvitationQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbInvitation findOne(PropelPDO $con = null) Return the first FfbInvitation matching the query
 * @method     FfbInvitation findOneOrCreate(PropelPDO $con = null) Return the first FfbInvitation matching the query, or a new FfbInvitation object populated from the query conditions when no match is found
 *
 * @method     FfbInvitation findOneByInvitationId(int $invitation_id) Return the first FfbInvitation filtered by the invitation_id column
 * @method     FfbInvitation findOneByInvitationSenderId(int $invitation_sender_id) Return the first FfbInvitation filtered by the invitation_sender_id column
 * @method     FfbInvitation findOneByInvitationEmail(string $invitation_email) Return the first FfbInvitation filtered by the invitation_email column
 * @method     FfbInvitation findOneByInvitationDate(string $invitation_date) Return the first FfbInvitation filtered by the invitation_date column
 *
 * @method     array findByInvitationId(int $invitation_id) Return FfbInvitation objects filtered by the invitation_id column
 * @method     array findByInvitationSenderId(int $invitation_sender_id) Return FfbInvitation objects filtered by the invitation_sender_id column
 * @method     array findByInvitationEmail(string $invitation_email) Return FfbInvitation objects filtered by the invitation_email column
 * @method     array findByInvitationDate(string $invitation_date) Return FfbInvitation objects filtered by the invitation_date column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbInvitationQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbInvitationQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbInvitation', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbInvitationQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbInvitationQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbInvitationQuery) {
			return $criteria;
		}
		$query = new FfbInvitationQuery();
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
	 * @return    FfbInvitation|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbInvitationPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the invitation_id column
	 * 
	 * @param     int|array $invitationId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByInvitationId($invitationId = null, $comparison = null)
	{
		if (is_array($invitationId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_ID, $invitationId, $comparison);
	}

	/**
	 * Filter the query on the invitation_sender_id column
	 * 
	 * @param     int|array $invitationSenderId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByInvitationSenderId($invitationSenderId = null, $comparison = null)
	{
		if (is_array($invitationSenderId)) {
			$useMinMax = false;
			if (isset($invitationSenderId['min'])) {
				$this->addUsingAlias(FfbInvitationPeer::INVITATION_SENDER_ID, $invitationSenderId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($invitationSenderId['max'])) {
				$this->addUsingAlias(FfbInvitationPeer::INVITATION_SENDER_ID, $invitationSenderId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_SENDER_ID, $invitationSenderId, $comparison);
	}

	/**
	 * Filter the query on the invitation_email column
	 * 
	 * @param     string $invitationEmail The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByInvitationEmail($invitationEmail = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($invitationEmail)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $invitationEmail)) {
				$invitationEmail = str_replace('*', '%', $invitationEmail);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_EMAIL, $invitationEmail, $comparison);
	}

	/**
	 * Filter the query on the invitation_date column
	 * 
	 * @param     string|array $invitationDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByInvitationDate($invitationDate = null, $comparison = null)
	{
		if (is_array($invitationDate)) {
			$useMinMax = false;
			if (isset($invitationDate['min'])) {
				$this->addUsingAlias(FfbInvitationPeer::INVITATION_DATE, $invitationDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($invitationDate['max'])) {
				$this->addUsingAlias(FfbInvitationPeer::INVITATION_DATE, $invitationDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbInvitationPeer::INVITATION_DATE, $invitationDate, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbInvitationPeer::INVITATION_SENDER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
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
	 * @param     FfbInvitation $ffbInvitation Object to remove from the list of results
	 *
	 * @return    FfbInvitationQuery The current query, for fluid interface
	 */
	public function prune($ffbInvitation = null)
	{
		if ($ffbInvitation) {
			$this->addUsingAlias(FfbInvitationPeer::INVITATION_ID, $ffbInvitation->getInvitationId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbInvitationQuery
