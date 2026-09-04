<?php


/**
 * Base class that represents a query for the 'ffb_teamfid' table.
 *
 * 
 *
 * @method     FfbTeamfidQuery orderByTeamfidId($order = Criteria::ASC) Order by the teamfid_id column
 * @method     FfbTeamfidQuery orderByTeamfidTeamId($order = Criteria::ASC) Order by the teamfid_team_id column
 * @method     FfbTeamfidQuery orderByTeamfidFidFoe($order = Criteria::ASC) Order by the teamfid_fid_foe column
 * @method     FfbTeamfidQuery orderByTeamfidFidTm($order = Criteria::ASC) Order by the teamfid_fid_tm column
 * @method     FfbTeamfidQuery orderByTeamfidFidWf($order = Criteria::ASC) Order by the teamfid_fid_wf column
 * @method     FfbTeamfidQuery orderByTeamfidNameFoe($order = Criteria::ASC) Order by the teamfid_name_foe column
 * @method     FfbTeamfidQuery orderByTeamfidNameTm($order = Criteria::ASC) Order by the teamfid_name_tm column
 * @method     FfbTeamfidQuery orderByTeamfidNameWf($order = Criteria::ASC) Order by the teamfid_name_wf column
 * @method     FfbTeamfidQuery orderByTeamfidUrlFoe($order = Criteria::ASC) Order by the teamfid_url_foe column
 * @method     FfbTeamfidQuery orderByTeamfidUrlTm($order = Criteria::ASC) Order by the teamfid_url_tm column
 * @method     FfbTeamfidQuery orderByTeamfidUrlWf($order = Criteria::ASC) Order by the teamfid_url_wf column
 *
 * @method     FfbTeamfidQuery groupByTeamfidId() Group by the teamfid_id column
 * @method     FfbTeamfidQuery groupByTeamfidTeamId() Group by the teamfid_team_id column
 * @method     FfbTeamfidQuery groupByTeamfidFidFoe() Group by the teamfid_fid_foe column
 * @method     FfbTeamfidQuery groupByTeamfidFidTm() Group by the teamfid_fid_tm column
 * @method     FfbTeamfidQuery groupByTeamfidFidWf() Group by the teamfid_fid_wf column
 * @method     FfbTeamfidQuery groupByTeamfidNameFoe() Group by the teamfid_name_foe column
 * @method     FfbTeamfidQuery groupByTeamfidNameTm() Group by the teamfid_name_tm column
 * @method     FfbTeamfidQuery groupByTeamfidNameWf() Group by the teamfid_name_wf column
 * @method     FfbTeamfidQuery groupByTeamfidUrlFoe() Group by the teamfid_url_foe column
 * @method     FfbTeamfidQuery groupByTeamfidUrlTm() Group by the teamfid_url_tm column
 * @method     FfbTeamfidQuery groupByTeamfidUrlWf() Group by the teamfid_url_wf column
 *
 * @method     FfbTeamfidQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbTeamfidQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbTeamfidQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbTeamfidQuery leftJoinFfbTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeam relation
 * @method     FfbTeamfidQuery rightJoinFfbTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeam relation
 * @method     FfbTeamfidQuery innerJoinFfbTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeam relation
 *
 * @method     FfbTeamfid findOne(PropelPDO $con = null) Return the first FfbTeamfid matching the query
 * @method     FfbTeamfid findOneOrCreate(PropelPDO $con = null) Return the first FfbTeamfid matching the query, or a new FfbTeamfid object populated from the query conditions when no match is found
 *
 * @method     FfbTeamfid findOneByTeamfidId(int $teamfid_id) Return the first FfbTeamfid filtered by the teamfid_id column
 * @method     FfbTeamfid findOneByTeamfidTeamId(int $teamfid_team_id) Return the first FfbTeamfid filtered by the teamfid_team_id column
 * @method     FfbTeamfid findOneByTeamfidFidFoe(string $teamfid_fid_foe) Return the first FfbTeamfid filtered by the teamfid_fid_foe column
 * @method     FfbTeamfid findOneByTeamfidFidTm(string $teamfid_fid_tm) Return the first FfbTeamfid filtered by the teamfid_fid_tm column
 * @method     FfbTeamfid findOneByTeamfidFidWf(string $teamfid_fid_wf) Return the first FfbTeamfid filtered by the teamfid_fid_wf column
 * @method     FfbTeamfid findOneByTeamfidNameFoe(string $teamfid_name_foe) Return the first FfbTeamfid filtered by the teamfid_name_foe column
 * @method     FfbTeamfid findOneByTeamfidNameTm(string $teamfid_name_tm) Return the first FfbTeamfid filtered by the teamfid_name_tm column
 * @method     FfbTeamfid findOneByTeamfidNameWf(string $teamfid_name_wf) Return the first FfbTeamfid filtered by the teamfid_name_wf column
 * @method     FfbTeamfid findOneByTeamfidUrlFoe(string $teamfid_url_foe) Return the first FfbTeamfid filtered by the teamfid_url_foe column
 * @method     FfbTeamfid findOneByTeamfidUrlTm(string $teamfid_url_tm) Return the first FfbTeamfid filtered by the teamfid_url_tm column
 * @method     FfbTeamfid findOneByTeamfidUrlWf(string $teamfid_url_wf) Return the first FfbTeamfid filtered by the teamfid_url_wf column
 *
 * @method     array findByTeamfidId(int $teamfid_id) Return FfbTeamfid objects filtered by the teamfid_id column
 * @method     array findByTeamfidTeamId(int $teamfid_team_id) Return FfbTeamfid objects filtered by the teamfid_team_id column
 * @method     array findByTeamfidFidFoe(string $teamfid_fid_foe) Return FfbTeamfid objects filtered by the teamfid_fid_foe column
 * @method     array findByTeamfidFidTm(string $teamfid_fid_tm) Return FfbTeamfid objects filtered by the teamfid_fid_tm column
 * @method     array findByTeamfidFidWf(string $teamfid_fid_wf) Return FfbTeamfid objects filtered by the teamfid_fid_wf column
 * @method     array findByTeamfidNameFoe(string $teamfid_name_foe) Return FfbTeamfid objects filtered by the teamfid_name_foe column
 * @method     array findByTeamfidNameTm(string $teamfid_name_tm) Return FfbTeamfid objects filtered by the teamfid_name_tm column
 * @method     array findByTeamfidNameWf(string $teamfid_name_wf) Return FfbTeamfid objects filtered by the teamfid_name_wf column
 * @method     array findByTeamfidUrlFoe(string $teamfid_url_foe) Return FfbTeamfid objects filtered by the teamfid_url_foe column
 * @method     array findByTeamfidUrlTm(string $teamfid_url_tm) Return FfbTeamfid objects filtered by the teamfid_url_tm column
 * @method     array findByTeamfidUrlWf(string $teamfid_url_wf) Return FfbTeamfid objects filtered by the teamfid_url_wf column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbTeamfidQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbTeamfidQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbTeamfid', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbTeamfidQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbTeamfidQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbTeamfidQuery) {
			return $criteria;
		}
		$query = new FfbTeamfidQuery();
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
	 * @return    FfbTeamfid|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbTeamfidPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the teamfid_id column
	 * 
	 * @param     int|array $teamfidId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidId($teamfidId = null, $comparison = null)
	{
		if (is_array($teamfidId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_ID, $teamfidId, $comparison);
	}

	/**
	 * Filter the query on the teamfid_team_id column
	 * 
	 * @param     int|array $teamfidTeamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidTeamId($teamfidTeamId = null, $comparison = null)
	{
		if (is_array($teamfidTeamId)) {
			$useMinMax = false;
			if (isset($teamfidTeamId['min'])) {
				$this->addUsingAlias(FfbTeamfidPeer::TEAMFID_TEAM_ID, $teamfidTeamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($teamfidTeamId['max'])) {
				$this->addUsingAlias(FfbTeamfidPeer::TEAMFID_TEAM_ID, $teamfidTeamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_TEAM_ID, $teamfidTeamId, $comparison);
	}

	/**
	 * Filter the query on the teamfid_fid_foe column
	 * 
	 * @param     string $teamfidFidFoe The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidFidFoe($teamfidFidFoe = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidFidFoe)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidFidFoe)) {
				$teamfidFidFoe = str_replace('*', '%', $teamfidFidFoe);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_FID_FOE, $teamfidFidFoe, $comparison);
	}

	/**
	 * Filter the query on the teamfid_fid_tm column
	 * 
	 * @param     string $teamfidFidTm The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidFidTm($teamfidFidTm = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidFidTm)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidFidTm)) {
				$teamfidFidTm = str_replace('*', '%', $teamfidFidTm);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_FID_TM, $teamfidFidTm, $comparison);
	}

	/**
	 * Filter the query on the teamfid_fid_wf column
	 * 
	 * @param     string $teamfidFidWf The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidFidWf($teamfidFidWf = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidFidWf)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidFidWf)) {
				$teamfidFidWf = str_replace('*', '%', $teamfidFidWf);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_FID_WF, $teamfidFidWf, $comparison);
	}

	/**
	 * Filter the query on the teamfid_name_foe column
	 * 
	 * @param     string $teamfidNameFoe The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidNameFoe($teamfidNameFoe = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidNameFoe)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidNameFoe)) {
				$teamfidNameFoe = str_replace('*', '%', $teamfidNameFoe);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_NAME_FOE, $teamfidNameFoe, $comparison);
	}

	/**
	 * Filter the query on the teamfid_name_tm column
	 * 
	 * @param     string $teamfidNameTm The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidNameTm($teamfidNameTm = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidNameTm)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidNameTm)) {
				$teamfidNameTm = str_replace('*', '%', $teamfidNameTm);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_NAME_TM, $teamfidNameTm, $comparison);
	}

	/**
	 * Filter the query on the teamfid_name_wf column
	 * 
	 * @param     string $teamfidNameWf The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidNameWf($teamfidNameWf = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidNameWf)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidNameWf)) {
				$teamfidNameWf = str_replace('*', '%', $teamfidNameWf);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_NAME_WF, $teamfidNameWf, $comparison);
	}

	/**
	 * Filter the query on the teamfid_url_foe column
	 * 
	 * @param     string $teamfidUrlFoe The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidUrlFoe($teamfidUrlFoe = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidUrlFoe)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidUrlFoe)) {
				$teamfidUrlFoe = str_replace('*', '%', $teamfidUrlFoe);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_URL_FOE, $teamfidUrlFoe, $comparison);
	}

	/**
	 * Filter the query on the teamfid_url_tm column
	 * 
	 * @param     string $teamfidUrlTm The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidUrlTm($teamfidUrlTm = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidUrlTm)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidUrlTm)) {
				$teamfidUrlTm = str_replace('*', '%', $teamfidUrlTm);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_URL_TM, $teamfidUrlTm, $comparison);
	}

	/**
	 * Filter the query on the teamfid_url_wf column
	 * 
	 * @param     string $teamfidUrlWf The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByTeamfidUrlWf($teamfidUrlWf = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($teamfidUrlWf)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $teamfidUrlWf)) {
				$teamfidUrlWf = str_replace('*', '%', $teamfidUrlWf);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbTeamfidPeer::TEAMFID_URL_WF, $teamfidUrlWf, $comparison);
	}

	/**
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function filterByFfbTeam($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbTeamfidPeer::TEAMFID_TEAM_ID, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function joinFfbTeam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeam');
		
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
			$this->addJoinObject($join, 'FfbTeam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeam relation FfbTeam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeam', 'FfbTeamQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbTeamfid $ffbTeamfid Object to remove from the list of results
	 *
	 * @return    FfbTeamfidQuery The current query, for fluid interface
	 */
	public function prune($ffbTeamfid = null)
	{
		if ($ffbTeamfid) {
			$this->addUsingAlias(FfbTeamfidPeer::TEAMFID_ID, $ffbTeamfid->getTeamfidId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbTeamfidQuery
