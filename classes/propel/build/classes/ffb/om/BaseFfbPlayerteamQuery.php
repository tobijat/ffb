<?php


/**
 * Base class that represents a query for the 'ffb_playerteam' table.
 *
 * 
 *
 * @method     FfbPlayerteamQuery orderByPlayerteamId($order = Criteria::ASC) Order by the playerteam_id column
 * @method     FfbPlayerteamQuery orderByPlayerteamPlayerId($order = Criteria::ASC) Order by the playerteam_player_id column
 * @method     FfbPlayerteamQuery orderByPlayerteamTeamId($order = Criteria::ASC) Order by the playerteam_team_id column
 * @method     FfbPlayerteamQuery orderByPlayerteamPlayerPicture($order = Criteria::ASC) Order by the playerteam_player_picture column
 * @method     FfbPlayerteamQuery orderByPlayerteamStatus($order = Criteria::ASC) Order by the playerteam_status column
 * @method     FfbPlayerteamQuery orderByPlayerteamPlayerPrice($order = Criteria::ASC) Order by the playerteam_player_price column
 * @method     FfbPlayerteamQuery orderByPlayerteamPlayerPosition($order = Criteria::ASC) Order by the playerteam_player_position column
 * @method     FfbPlayerteamQuery orderByPlayerteamDateTransfer($order = Criteria::ASC) Order by the playerteam_date_transfer column
 *
 * @method     FfbPlayerteamQuery groupByPlayerteamId() Group by the playerteam_id column
 * @method     FfbPlayerteamQuery groupByPlayerteamPlayerId() Group by the playerteam_player_id column
 * @method     FfbPlayerteamQuery groupByPlayerteamTeamId() Group by the playerteam_team_id column
 * @method     FfbPlayerteamQuery groupByPlayerteamPlayerPicture() Group by the playerteam_player_picture column
 * @method     FfbPlayerteamQuery groupByPlayerteamStatus() Group by the playerteam_status column
 * @method     FfbPlayerteamQuery groupByPlayerteamPlayerPrice() Group by the playerteam_player_price column
 * @method     FfbPlayerteamQuery groupByPlayerteamPlayerPosition() Group by the playerteam_player_position column
 * @method     FfbPlayerteamQuery groupByPlayerteamDateTransfer() Group by the playerteam_date_transfer column
 *
 * @method     FfbPlayerteamQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPlayerteamQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPlayerteamQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPlayerteamQuery leftJoinFfbPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayer relation
 * @method     FfbPlayerteamQuery rightJoinFfbPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayer relation
 * @method     FfbPlayerteamQuery innerJoinFfbPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayer relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeam relation
 * @method     FfbPlayerteamQuery rightJoinFfbTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeam relation
 * @method     FfbPlayerteamQuery innerJoinFfbTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeam relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbPlayerprice($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerprice relation
 * @method     FfbPlayerteamQuery rightJoinFfbPlayerprice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerprice relation
 * @method     FfbPlayerteamQuery innerJoinFfbPlayerprice($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerprice relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbGoal($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGoal relation
 * @method     FfbPlayerteamQuery rightJoinFfbGoal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGoal relation
 * @method     FfbPlayerteamQuery innerJoinFfbGoal($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGoal relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbPsgoal($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPsgoal relation
 * @method     FfbPlayerteamQuery rightJoinFfbPsgoal($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPsgoal relation
 * @method     FfbPlayerteamQuery innerJoinFfbPsgoal($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPsgoal relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbPlayerstats($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbPlayerteamQuery rightJoinFfbPlayerstats($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerstats relation
 * @method     FfbPlayerteamQuery innerJoinFfbPlayerstats($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerstats relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbPlayerfid($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerfid relation
 * @method     FfbPlayerteamQuery rightJoinFfbPlayerfid($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerfid relation
 * @method     FfbPlayerteamQuery innerJoinFfbPlayerfid($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerfid relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId1 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId1 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId1 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId2 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId2 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId2 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId3 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId3 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId3 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId4 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId4 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId4 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId5 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId5 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId5 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId6 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId6 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId6 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId7 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId7 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId7 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId8 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId8 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId8 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId9 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId9 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId9 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId10 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId10 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId10 relation
 *
 * @method     FfbPlayerteamQuery leftJoinFfbUserteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId11 relation
 * @method     FfbPlayerteamQuery rightJoinFfbUserteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId11 relation
 * @method     FfbPlayerteamQuery innerJoinFfbUserteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId11 relation
 *
 * @method     FfbPlayerteam findOne(?PropelPDO $con = null) Return the first FfbPlayerteam matching the query
 * @method     FfbPlayerteam findOneOrCreate(?PropelPDO $con = null) Return the first FfbPlayerteam matching the query, or a new FfbPlayerteam object populated from the query conditions when no match is found
 *
 * @method     FfbPlayerteam findOneByPlayerteamId(int $playerteam_id) Return the first FfbPlayerteam filtered by the playerteam_id column
 * @method     FfbPlayerteam findOneByPlayerteamPlayerId(int $playerteam_player_id) Return the first FfbPlayerteam filtered by the playerteam_player_id column
 * @method     FfbPlayerteam findOneByPlayerteamTeamId(int $playerteam_team_id) Return the first FfbPlayerteam filtered by the playerteam_team_id column
 * @method     FfbPlayerteam findOneByPlayerteamPlayerPicture(string $playerteam_player_picture) Return the first FfbPlayerteam filtered by the playerteam_player_picture column
 * @method     FfbPlayerteam findOneByPlayerteamStatus(boolean $playerteam_status) Return the first FfbPlayerteam filtered by the playerteam_status column
 * @method     FfbPlayerteam findOneByPlayerteamPlayerPrice(double $playerteam_player_price) Return the first FfbPlayerteam filtered by the playerteam_player_price column
 * @method     FfbPlayerteam findOneByPlayerteamPlayerPosition(string $playerteam_player_position) Return the first FfbPlayerteam filtered by the playerteam_player_position column
 * @method     FfbPlayerteam findOneByPlayerteamDateTransfer(string $playerteam_date_transfer) Return the first FfbPlayerteam filtered by the playerteam_date_transfer column
 *
 * @method     array findByPlayerteamId(int $playerteam_id) Return FfbPlayerteam objects filtered by the playerteam_id column
 * @method     array findByPlayerteamPlayerId(int $playerteam_player_id) Return FfbPlayerteam objects filtered by the playerteam_player_id column
 * @method     array findByPlayerteamTeamId(int $playerteam_team_id) Return FfbPlayerteam objects filtered by the playerteam_team_id column
 * @method     array findByPlayerteamPlayerPicture(string $playerteam_player_picture) Return FfbPlayerteam objects filtered by the playerteam_player_picture column
 * @method     array findByPlayerteamStatus(boolean $playerteam_status) Return FfbPlayerteam objects filtered by the playerteam_status column
 * @method     array findByPlayerteamPlayerPrice(double $playerteam_player_price) Return FfbPlayerteam objects filtered by the playerteam_player_price column
 * @method     array findByPlayerteamPlayerPosition(string $playerteam_player_position) Return FfbPlayerteam objects filtered by the playerteam_player_position column
 * @method     array findByPlayerteamDateTransfer(string $playerteam_date_transfer) Return FfbPlayerteam objects filtered by the playerteam_date_transfer column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerteamQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPlayerteamQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPlayerteam', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPlayerteamQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPlayerteamQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPlayerteamQuery) {
			return $criteria;
		}
		$query = new FfbPlayerteamQuery();
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
	 * @return    FfbPlayerteam|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPlayerteamPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the playerteam_id column
	 * 
	 * @param     int|array $playerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamId($playerteamId = null, $comparison = null)
	{
		if (is_array($playerteamId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $playerteamId, $comparison);
	}

	/**
	 * Filter the query on the playerteam_player_id column
	 * 
	 * @param     int|array $playerteamPlayerId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamPlayerId($playerteamPlayerId = null, $comparison = null)
	{
		if (is_array($playerteamPlayerId)) {
			$useMinMax = false;
			if (isset($playerteamPlayerId['min'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $playerteamPlayerId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerteamPlayerId['max'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $playerteamPlayerId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $playerteamPlayerId, $comparison);
	}

	/**
	 * Filter the query on the playerteam_team_id column
	 * 
	 * @param     int|array $playerteamTeamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamTeamId($playerteamTeamId = null, $comparison = null)
	{
		if (is_array($playerteamTeamId)) {
			$useMinMax = false;
			if (isset($playerteamTeamId['min'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $playerteamTeamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerteamTeamId['max'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $playerteamTeamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $playerteamTeamId, $comparison);
	}

	/**
	 * Filter the query on the playerteam_player_picture column
	 * 
	 * @param     string $playerteamPlayerPicture The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamPlayerPicture($playerteamPlayerPicture = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerteamPlayerPicture)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerteamPlayerPicture)) {
				$playerteamPlayerPicture = str_replace('*', '%', $playerteamPlayerPicture);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PICTURE, $playerteamPlayerPicture, $comparison);
	}

	/**
	 * Filter the query on the playerteam_status column
	 * 
	 * @param     boolean|string $playerteamStatus The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamStatus($playerteamStatus = null, $comparison = null)
	{
		if (is_string($playerteamStatus)) {
			$playerteam_status = in_array(strtolower($playerteamStatus), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_STATUS, $playerteamStatus, $comparison);
	}

	/**
	 * Filter the query on the playerteam_player_price column
	 * 
	 * @param     double|array $playerteamPlayerPrice The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamPlayerPrice($playerteamPlayerPrice = null, $comparison = null)
	{
		if (is_array($playerteamPlayerPrice)) {
			$useMinMax = false;
			if (isset($playerteamPlayerPrice['min'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE, $playerteamPlayerPrice['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerteamPlayerPrice['max'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE, $playerteamPlayerPrice['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE, $playerteamPlayerPrice, $comparison);
	}

	/**
	 * Filter the query on the playerteam_player_position column
	 * 
	 * @param     string $playerteamPlayerPosition The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamPlayerPosition($playerteamPlayerPosition = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerteamPlayerPosition)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerteamPlayerPosition)) {
				$playerteamPlayerPosition = str_replace('*', '%', $playerteamPlayerPosition);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $playerteamPlayerPosition, $comparison);
	}

	/**
	 * Filter the query on the playerteam_date_transfer column
	 * 
	 * @param     string|array $playerteamDateTransfer The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByPlayerteamDateTransfer($playerteamDateTransfer = null, $comparison = null)
	{
		if (is_array($playerteamDateTransfer)) {
			$useMinMax = false;
			if (isset($playerteamDateTransfer['min'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER, $playerteamDateTransfer['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerteamDateTransfer['max'])) {
				$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER, $playerteamDateTransfer['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER, $playerteamDateTransfer, $comparison);
	}

	/**
	 * Filter the query by a related FfbPlayer object
	 *
	 * @param     FfbPlayer $ffbPlayer  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayer($ffbPlayer, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, $ffbPlayer->getPlayerId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayer relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayer');
		
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
			$this->addJoinObject($join, 'FfbPlayer');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayer relation FfbPlayer object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayer($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayer', 'FfbPlayerQuery');
	}

	/**
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbTeam($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPlayerprice object
	 *
	 * @param     FfbPlayerprice $ffbPlayerprice  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerprice($ffbPlayerprice, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbPlayerprice->getPlayerpricePlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerprice relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerprice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerprice');
		
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
			$this->addJoinObject($join, 'FfbPlayerprice');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerprice relation FfbPlayerprice object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerpriceQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerpriceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerprice($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerprice', 'FfbPlayerpriceQuery');
	}

	/**
	 * Filter the query by a related FfbGoal object
	 *
	 * @param     FfbGoal $ffbGoal  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbGoal($ffbGoal, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbGoal->getGoalPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGoal relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbGoal($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbGoal');
		
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
			$this->addJoinObject($join, 'FfbGoal');
		}
		
		return $this;
	}

	/**
	 * Use the FfbGoal relation FfbGoal object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGoalQuery A secondary query class using the current class as primary query
	 */
	public function useFfbGoalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbGoal($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbGoal', 'FfbGoalQuery');
	}

	/**
	 * Filter the query by a related FfbPsgoal object
	 *
	 * @param     FfbPsgoal $ffbPsgoal  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPsgoal($ffbPsgoal, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbPsgoal->getPsgoalPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPsgoal relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbPsgoal($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPsgoal');
		
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
			$this->addJoinObject($join, 'FfbPsgoal');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPsgoal relation FfbPsgoal object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPsgoalQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPsgoalQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPsgoal($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPsgoal', 'FfbPsgoalQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerstats object
	 *
	 * @param     FfbPlayerstats $ffbPlayerstats  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerstats($ffbPlayerstats, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbPlayerstats->getPlayerstatsPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerstats relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerstats($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerstats');
		
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
			$this->addJoinObject($join, 'FfbPlayerstats');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerstats relation FfbPlayerstats object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerstatsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerstatsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerstats($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerstats', 'FfbPlayerstatsQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerfid object
	 *
	 * @param     FfbPlayerfid $ffbPlayerfid  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerfid($ffbPlayerfid, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbPlayerfid->getPlayerfidPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerfid relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerfid($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerfid');
		
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
			$this->addJoinObject($join, 'FfbPlayerfid');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerfid relation FfbPlayerfid object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerfidQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerfidQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerfid($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerfid', 'FfbPlayerfidQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId1($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId1(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId1 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId1($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId1');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId1');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId1 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId1Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId1($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId1', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId2($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId2(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId2 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId2($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId2');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId2');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId2 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId2Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId2($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId2', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId3($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId3(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId3 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId3($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId3');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId3');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId3 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId3Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId3($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId3', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId4($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId4(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId4 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId4($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId4');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId4');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId4 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId4Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId4($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId4', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId5($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId5(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId5 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId5($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId5');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId5');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId5 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId5Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId5($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId5', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId6($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId6(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId6 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId6($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId6');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId6');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId6 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId6Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId6($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId6', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId7($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId7(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId7 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId7($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId7');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId7');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId7 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId7Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId7($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId7', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId8($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId8(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId8 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId8($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId8');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId8');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId8 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId8Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId8($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId8', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId9($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId9(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId9 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId9($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId9');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId9');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId9 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId9Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId9($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId9', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId10($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId10(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId10 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId10($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId10');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId10');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId10 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId10Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId10($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId10', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteamRelatedByUserteamPlayerId11($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbUserteam->getUserteamPlayerId11(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteamRelatedByUserteamPlayerId11 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function joinFfbUserteamRelatedByUserteamPlayerId11($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteamRelatedByUserteamPlayerId11');
		
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
			$this->addJoinObject($join, 'FfbUserteamRelatedByUserteamPlayerId11');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteamRelatedByUserteamPlayerId11 relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamRelatedByUserteamPlayerId11Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteamRelatedByUserteamPlayerId11($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteamRelatedByUserteamPlayerId11', 'FfbUserteamQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam Object to remove from the list of results
	 *
	 * @return    FfbPlayerteamQuery The current query, for fluid interface
	 */
	public function prune($ffbPlayerteam = null)
	{
		if ($ffbPlayerteam) {
			$this->addUsingAlias(FfbPlayerteamPeer::PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPlayerteamQuery
