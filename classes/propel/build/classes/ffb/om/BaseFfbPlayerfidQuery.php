<?php


/**
 * Base class that represents a query for the 'ffb_playerfid' table.
 *
 * 
 *
 * @method     FfbPlayerfidQuery orderByPlayerfidId($order = Criteria::ASC) Order by the playerfid_id column
 * @method     FfbPlayerfidQuery orderByPlayerfidPlayerteamId($order = Criteria::ASC) Order by the playerfid_playerteam_id column
 * @method     FfbPlayerfidQuery orderByPlayerfidTeamId($order = Criteria::ASC) Order by the playerfid_team_id column
 * @method     FfbPlayerfidQuery orderByPlayerfidFidFoe($order = Criteria::ASC) Order by the playerfid_fid_foe column
 * @method     FfbPlayerfidQuery orderByPlayerfidFidFifa($order = Criteria::ASC) Order by the playerfid_fid_fifa column
 * @method     FfbPlayerfidQuery orderByPlayerfidFidTm($order = Criteria::ASC) Order by the playerfid_fid_tm column
 * @method     FfbPlayerfidQuery orderByPlayerfidFidUefa($order = Criteria::ASC) Order by the playerfid_fid_uefa column
 * @method     FfbPlayerfidQuery orderByPlayerfidFidWf($order = Criteria::ASC) Order by the playerfid_fid_wf column
 * @method     FfbPlayerfidQuery orderByPlayerfidNameFoe($order = Criteria::ASC) Order by the playerfid_name_foe column
 * @method     FfbPlayerfidQuery orderByPlayerfidNameFifa($order = Criteria::ASC) Order by the playerfid_name_fifa column
 * @method     FfbPlayerfidQuery orderByPlayerfidNameTm($order = Criteria::ASC) Order by the playerfid_name_tm column
 * @method     FfbPlayerfidQuery orderByPlayerfidNameUefa($order = Criteria::ASC) Order by the playerfid_name_uefa column
 * @method     FfbPlayerfidQuery orderByPlayerfidNameWf($order = Criteria::ASC) Order by the playerfid_name_wf column
 *
 * @method     FfbPlayerfidQuery groupByPlayerfidId() Group by the playerfid_id column
 * @method     FfbPlayerfidQuery groupByPlayerfidPlayerteamId() Group by the playerfid_playerteam_id column
 * @method     FfbPlayerfidQuery groupByPlayerfidTeamId() Group by the playerfid_team_id column
 * @method     FfbPlayerfidQuery groupByPlayerfidFidFoe() Group by the playerfid_fid_foe column
 * @method     FfbPlayerfidQuery groupByPlayerfidFidFifa() Group by the playerfid_fid_fifa column
 * @method     FfbPlayerfidQuery groupByPlayerfidFidTm() Group by the playerfid_fid_tm column
 * @method     FfbPlayerfidQuery groupByPlayerfidFidUefa() Group by the playerfid_fid_uefa column
 * @method     FfbPlayerfidQuery groupByPlayerfidFidWf() Group by the playerfid_fid_wf column
 * @method     FfbPlayerfidQuery groupByPlayerfidNameFoe() Group by the playerfid_name_foe column
 * @method     FfbPlayerfidQuery groupByPlayerfidNameFifa() Group by the playerfid_name_fifa column
 * @method     FfbPlayerfidQuery groupByPlayerfidNameTm() Group by the playerfid_name_tm column
 * @method     FfbPlayerfidQuery groupByPlayerfidNameUefa() Group by the playerfid_name_uefa column
 * @method     FfbPlayerfidQuery groupByPlayerfidNameWf() Group by the playerfid_name_wf column
 *
 * @method     FfbPlayerfidQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPlayerfidQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPlayerfidQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPlayerfidQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerfidQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerfidQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbPlayerfidQuery leftJoinFfbTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeam relation
 * @method     FfbPlayerfidQuery rightJoinFfbTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeam relation
 * @method     FfbPlayerfidQuery innerJoinFfbTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeam relation
 *
 * @method     FfbPlayerfid findOne(?PropelPDO $con = null) Return the first FfbPlayerfid matching the query
 * @method     FfbPlayerfid findOneOrCreate(?PropelPDO $con = null) Return the first FfbPlayerfid matching the query, or a new FfbPlayerfid object populated from the query conditions when no match is found
 *
 * @method     FfbPlayerfid findOneByPlayerfidId(int $playerfid_id) Return the first FfbPlayerfid filtered by the playerfid_id column
 * @method     FfbPlayerfid findOneByPlayerfidPlayerteamId(int $playerfid_playerteam_id) Return the first FfbPlayerfid filtered by the playerfid_playerteam_id column
 * @method     FfbPlayerfid findOneByPlayerfidTeamId(int $playerfid_team_id) Return the first FfbPlayerfid filtered by the playerfid_team_id column
 * @method     FfbPlayerfid findOneByPlayerfidFidFoe(string $playerfid_fid_foe) Return the first FfbPlayerfid filtered by the playerfid_fid_foe column
 * @method     FfbPlayerfid findOneByPlayerfidFidFifa(string $playerfid_fid_fifa) Return the first FfbPlayerfid filtered by the playerfid_fid_fifa column
 * @method     FfbPlayerfid findOneByPlayerfidFidTm(string $playerfid_fid_tm) Return the first FfbPlayerfid filtered by the playerfid_fid_tm column
 * @method     FfbPlayerfid findOneByPlayerfidFidUefa(string $playerfid_fid_uefa) Return the first FfbPlayerfid filtered by the playerfid_fid_uefa column
 * @method     FfbPlayerfid findOneByPlayerfidFidWf(string $playerfid_fid_wf) Return the first FfbPlayerfid filtered by the playerfid_fid_wf column
 * @method     FfbPlayerfid findOneByPlayerfidNameFoe(string $playerfid_name_foe) Return the first FfbPlayerfid filtered by the playerfid_name_foe column
 * @method     FfbPlayerfid findOneByPlayerfidNameFifa(string $playerfid_name_fifa) Return the first FfbPlayerfid filtered by the playerfid_name_fifa column
 * @method     FfbPlayerfid findOneByPlayerfidNameTm(string $playerfid_name_tm) Return the first FfbPlayerfid filtered by the playerfid_name_tm column
 * @method     FfbPlayerfid findOneByPlayerfidNameUefa(string $playerfid_name_uefa) Return the first FfbPlayerfid filtered by the playerfid_name_uefa column
 * @method     FfbPlayerfid findOneByPlayerfidNameWf(string $playerfid_name_wf) Return the first FfbPlayerfid filtered by the playerfid_name_wf column
 *
 * @method     array findByPlayerfidId(int $playerfid_id) Return FfbPlayerfid objects filtered by the playerfid_id column
 * @method     array findByPlayerfidPlayerteamId(int $playerfid_playerteam_id) Return FfbPlayerfid objects filtered by the playerfid_playerteam_id column
 * @method     array findByPlayerfidTeamId(int $playerfid_team_id) Return FfbPlayerfid objects filtered by the playerfid_team_id column
 * @method     array findByPlayerfidFidFoe(string $playerfid_fid_foe) Return FfbPlayerfid objects filtered by the playerfid_fid_foe column
 * @method     array findByPlayerfidFidFifa(string $playerfid_fid_fifa) Return FfbPlayerfid objects filtered by the playerfid_fid_fifa column
 * @method     array findByPlayerfidFidTm(string $playerfid_fid_tm) Return FfbPlayerfid objects filtered by the playerfid_fid_tm column
 * @method     array findByPlayerfidFidUefa(string $playerfid_fid_uefa) Return FfbPlayerfid objects filtered by the playerfid_fid_uefa column
 * @method     array findByPlayerfidFidWf(string $playerfid_fid_wf) Return FfbPlayerfid objects filtered by the playerfid_fid_wf column
 * @method     array findByPlayerfidNameFoe(string $playerfid_name_foe) Return FfbPlayerfid objects filtered by the playerfid_name_foe column
 * @method     array findByPlayerfidNameFifa(string $playerfid_name_fifa) Return FfbPlayerfid objects filtered by the playerfid_name_fifa column
 * @method     array findByPlayerfidNameTm(string $playerfid_name_tm) Return FfbPlayerfid objects filtered by the playerfid_name_tm column
 * @method     array findByPlayerfidNameUefa(string $playerfid_name_uefa) Return FfbPlayerfid objects filtered by the playerfid_name_uefa column
 * @method     array findByPlayerfidNameWf(string $playerfid_name_wf) Return FfbPlayerfid objects filtered by the playerfid_name_wf column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerfidQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPlayerfidQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPlayerfid', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPlayerfidQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPlayerfidQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPlayerfidQuery) {
			return $criteria;
		}
		$query = new FfbPlayerfidQuery();
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
	 * @return    FfbPlayerfid|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPlayerfidPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the playerfid_id column
	 * 
	 * @param     int|array $playerfidId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidId($playerfidId = null, $comparison = null)
	{
		if (is_array($playerfidId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_ID, $playerfidId, $comparison);
	}

	/**
	 * Filter the query on the playerfid_playerteam_id column
	 * 
	 * @param     int|array $playerfidPlayerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidPlayerteamId($playerfidPlayerteamId = null, $comparison = null)
	{
		if (is_array($playerfidPlayerteamId)) {
			$useMinMax = false;
			if (isset($playerfidPlayerteamId['min'])) {
				$this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerfidPlayerteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerfidPlayerteamId['max'])) {
				$this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerfidPlayerteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerfidPlayerteamId, $comparison);
	}

	/**
	 * Filter the query on the playerfid_team_id column
	 * 
	 * @param     int|array $playerfidTeamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidTeamId($playerfidTeamId = null, $comparison = null)
	{
		if (is_array($playerfidTeamId)) {
			$useMinMax = false;
			if (isset($playerfidTeamId['min'])) {
				$this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_TEAM_ID, $playerfidTeamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerfidTeamId['max'])) {
				$this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_TEAM_ID, $playerfidTeamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_TEAM_ID, $playerfidTeamId, $comparison);
	}

	/**
	 * Filter the query on the playerfid_fid_foe column
	 * 
	 * @param     string $playerfidFidFoe The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidFidFoe($playerfidFidFoe = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidFidFoe)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidFidFoe)) {
				$playerfidFidFoe = str_replace('*', '%', $playerfidFidFoe);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_FID_FOE, $playerfidFidFoe, $comparison);
	}

	/**
	 * Filter the query on the playerfid_fid_fifa column
	 * 
	 * @param     string $playerfidFidFifa The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidFidFifa($playerfidFidFifa = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidFidFifa)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidFidFifa)) {
				$playerfidFidFifa = str_replace('*', '%', $playerfidFidFifa);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_FID_FIFA, $playerfidFidFifa, $comparison);
	}

	/**
	 * Filter the query on the playerfid_fid_tm column
	 * 
	 * @param     string $playerfidFidTm The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidFidTm($playerfidFidTm = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidFidTm)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidFidTm)) {
				$playerfidFidTm = str_replace('*', '%', $playerfidFidTm);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_FID_TM, $playerfidFidTm, $comparison);
	}

	/**
	 * Filter the query on the playerfid_fid_uefa column
	 * 
	 * @param     string $playerfidFidUefa The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidFidUefa($playerfidFidUefa = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidFidUefa)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidFidUefa)) {
				$playerfidFidUefa = str_replace('*', '%', $playerfidFidUefa);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_FID_UEFA, $playerfidFidUefa, $comparison);
	}

	/**
	 * Filter the query on the playerfid_fid_wf column
	 * 
	 * @param     string $playerfidFidWf The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidFidWf($playerfidFidWf = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidFidWf)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidFidWf)) {
				$playerfidFidWf = str_replace('*', '%', $playerfidFidWf);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_FID_WF, $playerfidFidWf, $comparison);
	}

	/**
	 * Filter the query on the playerfid_name_foe column
	 * 
	 * @param     string $playerfidNameFoe The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidNameFoe($playerfidNameFoe = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidNameFoe)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidNameFoe)) {
				$playerfidNameFoe = str_replace('*', '%', $playerfidNameFoe);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $playerfidNameFoe, $comparison);
	}

	/**
	 * Filter the query on the playerfid_name_fifa column
	 * 
	 * @param     string $playerfidNameFifa The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidNameFifa($playerfidNameFifa = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidNameFifa)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidNameFifa)) {
				$playerfidNameFifa = str_replace('*', '%', $playerfidNameFifa);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_NAME_FIFA, $playerfidNameFifa, $comparison);
	}

	/**
	 * Filter the query on the playerfid_name_tm column
	 * 
	 * @param     string $playerfidNameTm The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidNameTm($playerfidNameTm = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidNameTm)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidNameTm)) {
				$playerfidNameTm = str_replace('*', '%', $playerfidNameTm);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_NAME_TM, $playerfidNameTm, $comparison);
	}

	/**
	 * Filter the query on the playerfid_name_uefa column
	 * 
	 * @param     string $playerfidNameUefa The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidNameUefa($playerfidNameUefa = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidNameUefa)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidNameUefa)) {
				$playerfidNameUefa = str_replace('*', '%', $playerfidNameUefa);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_NAME_UEFA, $playerfidNameUefa, $comparison);
	}

	/**
	 * Filter the query on the playerfid_name_wf column
	 * 
	 * @param     string $playerfidNameWf The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByPlayerfidNameWf($playerfidNameWf = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerfidNameWf)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerfidNameWf)) {
				$playerfidNameWf = str_replace('*', '%', $playerfidNameWf);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_NAME_WF, $playerfidNameWf, $comparison);
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteam');
		
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
			$this->addJoinObject($join, 'FfbPlayerteam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteam relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteam', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function filterByFfbTeam($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_TEAM_ID, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
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
	 * @param     FfbPlayerfid $ffbPlayerfid Object to remove from the list of results
	 *
	 * @return    FfbPlayerfidQuery The current query, for fluid interface
	 */
	public function prune($ffbPlayerfid = null)
	{
		if ($ffbPlayerfid) {
			$this->addUsingAlias(FfbPlayerfidPeer::PLAYERFID_ID, $ffbPlayerfid->getPlayerfidId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPlayerfidQuery
