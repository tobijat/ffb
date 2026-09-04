<?php


/**
 * Base class that represents a query for the 'ffb_user_award' table.
 *
 * 
 *
 * @method     FfbUserAwardQuery orderByUserAwardId($order = Criteria::ASC) Order by the user_award_id column
 * @method     FfbUserAwardQuery orderByUserAwardName($order = Criteria::ASC) Order by the user_award_name column
 * @method     FfbUserAwardQuery orderByUserAwardImage($order = Criteria::ASC) Order by the user_award_image column
 * @method     FfbUserAwardQuery orderByUserAwardDescription($order = Criteria::ASC) Order by the user_award_description column
 * @method     FfbUserAwardQuery orderByUserAwardSortflag($order = Criteria::ASC) Order by the user_award_sortflag column
 *
 * @method     FfbUserAwardQuery groupByUserAwardId() Group by the user_award_id column
 * @method     FfbUserAwardQuery groupByUserAwardName() Group by the user_award_name column
 * @method     FfbUserAwardQuery groupByUserAwardImage() Group by the user_award_image column
 * @method     FfbUserAwardQuery groupByUserAwardDescription() Group by the user_award_description column
 * @method     FfbUserAwardQuery groupByUserAwardSortflag() Group by the user_award_sortflag column
 *
 * @method     FfbUserAwardQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbUserAwardQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbUserAwardQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbUserAwardQuery leftJoinFfbUserAwardDefines($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserAwardDefines relation
 * @method     FfbUserAwardQuery rightJoinFfbUserAwardDefines($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserAwardDefines relation
 * @method     FfbUserAwardQuery innerJoinFfbUserAwardDefines($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserAwardDefines relation
 *
 * @method     FfbUserAward findOne(PropelPDO $con = null) Return the first FfbUserAward matching the query
 * @method     FfbUserAward findOneOrCreate(PropelPDO $con = null) Return the first FfbUserAward matching the query, or a new FfbUserAward object populated from the query conditions when no match is found
 *
 * @method     FfbUserAward findOneByUserAwardId(int $user_award_id) Return the first FfbUserAward filtered by the user_award_id column
 * @method     FfbUserAward findOneByUserAwardName(string $user_award_name) Return the first FfbUserAward filtered by the user_award_name column
 * @method     FfbUserAward findOneByUserAwardImage(string $user_award_image) Return the first FfbUserAward filtered by the user_award_image column
 * @method     FfbUserAward findOneByUserAwardDescription(string $user_award_description) Return the first FfbUserAward filtered by the user_award_description column
 * @method     FfbUserAward findOneByUserAwardSortflag(int $user_award_sortflag) Return the first FfbUserAward filtered by the user_award_sortflag column
 *
 * @method     array findByUserAwardId(int $user_award_id) Return FfbUserAward objects filtered by the user_award_id column
 * @method     array findByUserAwardName(string $user_award_name) Return FfbUserAward objects filtered by the user_award_name column
 * @method     array findByUserAwardImage(string $user_award_image) Return FfbUserAward objects filtered by the user_award_image column
 * @method     array findByUserAwardDescription(string $user_award_description) Return FfbUserAward objects filtered by the user_award_description column
 * @method     array findByUserAwardSortflag(int $user_award_sortflag) Return FfbUserAward objects filtered by the user_award_sortflag column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserAwardQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbUserAwardQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbUserAward', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbUserAwardQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbUserAwardQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbUserAwardQuery) {
			return $criteria;
		}
		$query = new FfbUserAwardQuery();
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
	 * @return    FfbUserAward|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbUserAwardPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_award_id column
	 * 
	 * @param     int|array $userAwardId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByUserAwardId($userAwardId = null, $comparison = null)
	{
		if (is_array($userAwardId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_ID, $userAwardId, $comparison);
	}

	/**
	 * Filter the query on the user_award_name column
	 * 
	 * @param     string $userAwardName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByUserAwardName($userAwardName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardName)) {
				$userAwardName = str_replace('*', '%', $userAwardName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_NAME, $userAwardName, $comparison);
	}

	/**
	 * Filter the query on the user_award_image column
	 * 
	 * @param     string $userAwardImage The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByUserAwardImage($userAwardImage = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardImage)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardImage)) {
				$userAwardImage = str_replace('*', '%', $userAwardImage);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_IMAGE, $userAwardImage, $comparison);
	}

	/**
	 * Filter the query on the user_award_description column
	 * 
	 * @param     string $userAwardDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDescription($userAwardDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDescription)) {
				$userAwardDescription = str_replace('*', '%', $userAwardDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_DESCRIPTION, $userAwardDescription, $comparison);
	}

	/**
	 * Filter the query on the user_award_sortflag column
	 * 
	 * @param     int|array $userAwardSortflag The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByUserAwardSortflag($userAwardSortflag = null, $comparison = null)
	{
		if (is_array($userAwardSortflag)) {
			$useMinMax = false;
			if (isset($userAwardSortflag['min'])) {
				$this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_SORTFLAG, $userAwardSortflag['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardSortflag['max'])) {
				$this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_SORTFLAG, $userAwardSortflag['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_SORTFLAG, $userAwardSortflag, $comparison);
	}

	/**
	 * Filter the query by a related FfbUserAwardDefines object
	 *
	 * @param     FfbUserAwardDefines $ffbUserAwardDefines  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function filterByFfbUserAwardDefines($ffbUserAwardDefines, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserAwardPeer::USER_AWARD_ID, $ffbUserAwardDefines->getUserAwardDefinesAwardId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserAwardDefines relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function joinFfbUserAwardDefines($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserAwardDefines');
		
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
			$this->addJoinObject($join, 'FfbUserAwardDefines');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserAwardDefines relation FfbUserAwardDefines object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardDefinesQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserAwardDefinesQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserAwardDefines($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserAwardDefines', 'FfbUserAwardDefinesQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbUserAward $ffbUserAward Object to remove from the list of results
	 *
	 * @return    FfbUserAwardQuery The current query, for fluid interface
	 */
	public function prune($ffbUserAward = null)
	{
		if ($ffbUserAward) {
			$this->addUsingAlias(FfbUserAwardPeer::USER_AWARD_ID, $ffbUserAward->getUserAwardId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbUserAwardQuery
