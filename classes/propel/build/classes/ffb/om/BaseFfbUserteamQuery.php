<?php


/**
 * Base class that represents a query for the 'ffb_userteam' table.
 *
 * 
 *
 * @method     FfbUserteamQuery orderByUserteamId($order = Criteria::ASC) Order by the userteam_id column
 * @method     FfbUserteamQuery orderByUserteamUserId($order = Criteria::ASC) Order by the userteam_user_id column
 * @method     FfbUserteamQuery orderByUserteamDate($order = Criteria::ASC) Order by the userteam_date column
 * @method     FfbUserteamQuery orderByUserteamPlayerId1($order = Criteria::ASC) Order by the userteam_player_id1 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId2($order = Criteria::ASC) Order by the userteam_player_id2 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId3($order = Criteria::ASC) Order by the userteam_player_id3 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId4($order = Criteria::ASC) Order by the userteam_player_id4 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId5($order = Criteria::ASC) Order by the userteam_player_id5 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId6($order = Criteria::ASC) Order by the userteam_player_id6 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId7($order = Criteria::ASC) Order by the userteam_player_id7 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId8($order = Criteria::ASC) Order by the userteam_player_id8 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId9($order = Criteria::ASC) Order by the userteam_player_id9 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId10($order = Criteria::ASC) Order by the userteam_player_id10 column
 * @method     FfbUserteamQuery orderByUserteamPlayerId11($order = Criteria::ASC) Order by the userteam_player_id11 column
 * @method     FfbUserteamQuery orderByUserteamPrice($order = Criteria::ASC) Order by the userteam_price column
 * @method     FfbUserteamQuery orderByUserteamMatchroundId($order = Criteria::ASC) Order by the userteam_matchround_id column
 * @method     FfbUserteamQuery orderByUserteamScore($order = Criteria::ASC) Order by the userteam_score column
 * @method     FfbUserteamQuery orderByUserteamWcPoints($order = Criteria::ASC) Order by the userteam_wc_points column
 *
 * @method     FfbUserteamQuery groupByUserteamId() Group by the userteam_id column
 * @method     FfbUserteamQuery groupByUserteamUserId() Group by the userteam_user_id column
 * @method     FfbUserteamQuery groupByUserteamDate() Group by the userteam_date column
 * @method     FfbUserteamQuery groupByUserteamPlayerId1() Group by the userteam_player_id1 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId2() Group by the userteam_player_id2 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId3() Group by the userteam_player_id3 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId4() Group by the userteam_player_id4 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId5() Group by the userteam_player_id5 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId6() Group by the userteam_player_id6 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId7() Group by the userteam_player_id7 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId8() Group by the userteam_player_id8 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId9() Group by the userteam_player_id9 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId10() Group by the userteam_player_id10 column
 * @method     FfbUserteamQuery groupByUserteamPlayerId11() Group by the userteam_player_id11 column
 * @method     FfbUserteamQuery groupByUserteamPrice() Group by the userteam_price column
 * @method     FfbUserteamQuery groupByUserteamMatchroundId() Group by the userteam_matchround_id column
 * @method     FfbUserteamQuery groupByUserteamScore() Group by the userteam_score column
 * @method     FfbUserteamQuery groupByUserteamWcPoints() Group by the userteam_wc_points column
 *
 * @method     FfbUserteamQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbUserteamQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbUserteamQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbUserteamQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     FfbUserteamQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     FfbUserteamQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId1 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId1 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId1($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId1 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId2 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId2 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId2($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId2 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId3 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId3 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId3($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId3 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId4 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId4 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId4($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId4 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId5 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId5 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId5($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId5 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId6 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId6 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId6($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId6 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId7 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId7 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId7($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId7 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId8 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId8 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId8($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId8 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId9 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId9 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId9($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId9 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId10 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId10 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId10($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId10 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbPlayerteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId11 relation
 * @method     FfbUserteamQuery rightJoinFfbPlayerteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId11 relation
 * @method     FfbUserteamQuery innerJoinFfbPlayerteamRelatedByUserteamPlayerId11($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId11 relation
 *
 * @method     FfbUserteamQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbUserteamQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbUserteamQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbUserteam findOne(?PropelPDO $con = null) Return the first FfbUserteam matching the query
 * @method     FfbUserteam findOneOrCreate(?PropelPDO $con = null) Return the first FfbUserteam matching the query, or a new FfbUserteam object populated from the query conditions when no match is found
 *
 * @method     FfbUserteam findOneByUserteamId(int $userteam_id) Return the first FfbUserteam filtered by the userteam_id column
 * @method     FfbUserteam findOneByUserteamUserId(int $userteam_user_id) Return the first FfbUserteam filtered by the userteam_user_id column
 * @method     FfbUserteam findOneByUserteamDate(string $userteam_date) Return the first FfbUserteam filtered by the userteam_date column
 * @method     FfbUserteam findOneByUserteamPlayerId1(int $userteam_player_id1) Return the first FfbUserteam filtered by the userteam_player_id1 column
 * @method     FfbUserteam findOneByUserteamPlayerId2(int $userteam_player_id2) Return the first FfbUserteam filtered by the userteam_player_id2 column
 * @method     FfbUserteam findOneByUserteamPlayerId3(int $userteam_player_id3) Return the first FfbUserteam filtered by the userteam_player_id3 column
 * @method     FfbUserteam findOneByUserteamPlayerId4(int $userteam_player_id4) Return the first FfbUserteam filtered by the userteam_player_id4 column
 * @method     FfbUserteam findOneByUserteamPlayerId5(int $userteam_player_id5) Return the first FfbUserteam filtered by the userteam_player_id5 column
 * @method     FfbUserteam findOneByUserteamPlayerId6(int $userteam_player_id6) Return the first FfbUserteam filtered by the userteam_player_id6 column
 * @method     FfbUserteam findOneByUserteamPlayerId7(int $userteam_player_id7) Return the first FfbUserteam filtered by the userteam_player_id7 column
 * @method     FfbUserteam findOneByUserteamPlayerId8(int $userteam_player_id8) Return the first FfbUserteam filtered by the userteam_player_id8 column
 * @method     FfbUserteam findOneByUserteamPlayerId9(int $userteam_player_id9) Return the first FfbUserteam filtered by the userteam_player_id9 column
 * @method     FfbUserteam findOneByUserteamPlayerId10(int $userteam_player_id10) Return the first FfbUserteam filtered by the userteam_player_id10 column
 * @method     FfbUserteam findOneByUserteamPlayerId11(int $userteam_player_id11) Return the first FfbUserteam filtered by the userteam_player_id11 column
 * @method     FfbUserteam findOneByUserteamPrice(string $userteam_price) Return the first FfbUserteam filtered by the userteam_price column
 * @method     FfbUserteam findOneByUserteamMatchroundId(int $userteam_matchround_id) Return the first FfbUserteam filtered by the userteam_matchround_id column
 * @method     FfbUserteam findOneByUserteamScore(int $userteam_score) Return the first FfbUserteam filtered by the userteam_score column
 * @method     FfbUserteam findOneByUserteamWcPoints(int $userteam_wc_points) Return the first FfbUserteam filtered by the userteam_wc_points column
 *
 * @method     array findByUserteamId(int $userteam_id) Return FfbUserteam objects filtered by the userteam_id column
 * @method     array findByUserteamUserId(int $userteam_user_id) Return FfbUserteam objects filtered by the userteam_user_id column
 * @method     array findByUserteamDate(string $userteam_date) Return FfbUserteam objects filtered by the userteam_date column
 * @method     array findByUserteamPlayerId1(int $userteam_player_id1) Return FfbUserteam objects filtered by the userteam_player_id1 column
 * @method     array findByUserteamPlayerId2(int $userteam_player_id2) Return FfbUserteam objects filtered by the userteam_player_id2 column
 * @method     array findByUserteamPlayerId3(int $userteam_player_id3) Return FfbUserteam objects filtered by the userteam_player_id3 column
 * @method     array findByUserteamPlayerId4(int $userteam_player_id4) Return FfbUserteam objects filtered by the userteam_player_id4 column
 * @method     array findByUserteamPlayerId5(int $userteam_player_id5) Return FfbUserteam objects filtered by the userteam_player_id5 column
 * @method     array findByUserteamPlayerId6(int $userteam_player_id6) Return FfbUserteam objects filtered by the userteam_player_id6 column
 * @method     array findByUserteamPlayerId7(int $userteam_player_id7) Return FfbUserteam objects filtered by the userteam_player_id7 column
 * @method     array findByUserteamPlayerId8(int $userteam_player_id8) Return FfbUserteam objects filtered by the userteam_player_id8 column
 * @method     array findByUserteamPlayerId9(int $userteam_player_id9) Return FfbUserteam objects filtered by the userteam_player_id9 column
 * @method     array findByUserteamPlayerId10(int $userteam_player_id10) Return FfbUserteam objects filtered by the userteam_player_id10 column
 * @method     array findByUserteamPlayerId11(int $userteam_player_id11) Return FfbUserteam objects filtered by the userteam_player_id11 column
 * @method     array findByUserteamPrice(string $userteam_price) Return FfbUserteam objects filtered by the userteam_price column
 * @method     array findByUserteamMatchroundId(int $userteam_matchround_id) Return FfbUserteam objects filtered by the userteam_matchround_id column
 * @method     array findByUserteamScore(int $userteam_score) Return FfbUserteam objects filtered by the userteam_score column
 * @method     array findByUserteamWcPoints(int $userteam_wc_points) Return FfbUserteam objects filtered by the userteam_wc_points column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserteamQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbUserteamQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbUserteam', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbUserteamQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbUserteamQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbUserteamQuery) {
			return $criteria;
		}
		$query = new FfbUserteamQuery();
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
	 * @return    FfbUserteam|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbUserteamPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the userteam_id column
	 * 
	 * @param     int|array $userteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamId($userteamId = null, $comparison = null)
	{
		if (is_array($userteamId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_ID, $userteamId, $comparison);
	}

	/**
	 * Filter the query on the userteam_user_id column
	 * 
	 * @param     int|array $userteamUserId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamUserId($userteamUserId = null, $comparison = null)
	{
		if (is_array($userteamUserId)) {
			$useMinMax = false;
			if (isset($userteamUserId['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_USER_ID, $userteamUserId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamUserId['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_USER_ID, $userteamUserId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_USER_ID, $userteamUserId, $comparison);
	}

	/**
	 * Filter the query on the userteam_date column
	 * 
	 * @param     string|array $userteamDate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamDate($userteamDate = null, $comparison = null)
	{
		if (is_array($userteamDate)) {
			$useMinMax = false;
			if (isset($userteamDate['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_DATE, $userteamDate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamDate['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_DATE, $userteamDate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_DATE, $userteamDate, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id1 column
	 * 
	 * @param     int|array $userteamPlayerId1 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId1($userteamPlayerId1 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId1)) {
			$useMinMax = false;
			if (isset($userteamPlayerId1['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $userteamPlayerId1['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId1['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $userteamPlayerId1['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $userteamPlayerId1, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id2 column
	 * 
	 * @param     int|array $userteamPlayerId2 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId2($userteamPlayerId2 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId2)) {
			$useMinMax = false;
			if (isset($userteamPlayerId2['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $userteamPlayerId2['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId2['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $userteamPlayerId2['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $userteamPlayerId2, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id3 column
	 * 
	 * @param     int|array $userteamPlayerId3 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId3($userteamPlayerId3 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId3)) {
			$useMinMax = false;
			if (isset($userteamPlayerId3['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $userteamPlayerId3['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId3['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $userteamPlayerId3['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $userteamPlayerId3, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id4 column
	 * 
	 * @param     int|array $userteamPlayerId4 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId4($userteamPlayerId4 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId4)) {
			$useMinMax = false;
			if (isset($userteamPlayerId4['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $userteamPlayerId4['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId4['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $userteamPlayerId4['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $userteamPlayerId4, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id5 column
	 * 
	 * @param     int|array $userteamPlayerId5 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId5($userteamPlayerId5 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId5)) {
			$useMinMax = false;
			if (isset($userteamPlayerId5['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $userteamPlayerId5['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId5['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $userteamPlayerId5['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $userteamPlayerId5, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id6 column
	 * 
	 * @param     int|array $userteamPlayerId6 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId6($userteamPlayerId6 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId6)) {
			$useMinMax = false;
			if (isset($userteamPlayerId6['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $userteamPlayerId6['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId6['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $userteamPlayerId6['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $userteamPlayerId6, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id7 column
	 * 
	 * @param     int|array $userteamPlayerId7 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId7($userteamPlayerId7 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId7)) {
			$useMinMax = false;
			if (isset($userteamPlayerId7['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $userteamPlayerId7['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId7['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $userteamPlayerId7['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $userteamPlayerId7, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id8 column
	 * 
	 * @param     int|array $userteamPlayerId8 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId8($userteamPlayerId8 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId8)) {
			$useMinMax = false;
			if (isset($userteamPlayerId8['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $userteamPlayerId8['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId8['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $userteamPlayerId8['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $userteamPlayerId8, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id9 column
	 * 
	 * @param     int|array $userteamPlayerId9 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId9($userteamPlayerId9 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId9)) {
			$useMinMax = false;
			if (isset($userteamPlayerId9['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $userteamPlayerId9['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId9['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $userteamPlayerId9['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $userteamPlayerId9, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id10 column
	 * 
	 * @param     int|array $userteamPlayerId10 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId10($userteamPlayerId10 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId10)) {
			$useMinMax = false;
			if (isset($userteamPlayerId10['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $userteamPlayerId10['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId10['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $userteamPlayerId10['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $userteamPlayerId10, $comparison);
	}

	/**
	 * Filter the query on the userteam_player_id11 column
	 * 
	 * @param     int|array $userteamPlayerId11 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPlayerId11($userteamPlayerId11 = null, $comparison = null)
	{
		if (is_array($userteamPlayerId11)) {
			$useMinMax = false;
			if (isset($userteamPlayerId11['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $userteamPlayerId11['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPlayerId11['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $userteamPlayerId11['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $userteamPlayerId11, $comparison);
	}

	/**
	 * Filter the query on the userteam_price column
	 * 
	 * @param     string|array $userteamPrice The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamPrice($userteamPrice = null, $comparison = null)
	{
		if (is_array($userteamPrice)) {
			$useMinMax = false;
			if (isset($userteamPrice['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PRICE, $userteamPrice['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamPrice['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_PRICE, $userteamPrice['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_PRICE, $userteamPrice, $comparison);
	}

	/**
	 * Filter the query on the userteam_matchround_id column
	 * 
	 * @param     int|array $userteamMatchroundId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamMatchroundId($userteamMatchroundId = null, $comparison = null)
	{
		if (is_array($userteamMatchroundId)) {
			$useMinMax = false;
			if (isset($userteamMatchroundId['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $userteamMatchroundId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamMatchroundId['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $userteamMatchroundId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $userteamMatchroundId, $comparison);
	}

	/**
	 * Filter the query on the userteam_score column
	 * 
	 * @param     int|array $userteamScore The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamScore($userteamScore = null, $comparison = null)
	{
		if (is_array($userteamScore)) {
			$useMinMax = false;
			if (isset($userteamScore['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_SCORE, $userteamScore['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamScore['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_SCORE, $userteamScore['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_SCORE, $userteamScore, $comparison);
	}

	/**
	 * Filter the query on the userteam_wc_points column
	 * 
	 * @param     int|array $userteamWcPoints The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByUserteamWcPoints($userteamWcPoints = null, $comparison = null)
	{
		if (is_array($userteamWcPoints)) {
			$useMinMax = false;
			if (isset($userteamWcPoints['min'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_WC_POINTS, $userteamWcPoints['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userteamWcPoints['max'])) {
				$this->addUsingAlias(FfbUserteamPeer::USERTEAM_WC_POINTS, $userteamWcPoints['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserteamPeer::USERTEAM_WC_POINTS, $userteamWcPoints, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId1($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId1 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId1($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId1');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId1');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId1 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId1Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId1($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId1', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId2($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId2 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId2($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId2');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId2');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId2 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId2Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId2($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId2', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId3($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId3 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId3($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId3');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId3');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId3 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId3Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId3($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId3', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId4($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId4 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId4($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId4');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId4');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId4 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId4Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId4($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId4', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId5($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId5 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId5($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId5');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId5');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId5 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId5Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId5($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId5', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId6($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId6 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId6($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId6');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId6');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId6 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId6Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId6($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId6', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId7($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId7 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId7($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId7');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId7');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId7 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId7Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId7($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId7', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId8($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId8 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId8($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId8');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId8');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId8 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId8Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId8($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId8', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId9($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId9 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId9($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId9');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId9');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId9 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId9Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId9($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId9', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId10($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId10 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId10($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId10');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId10');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId10 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId10Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId10($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId10', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteamRelatedByUserteamPlayerId11($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteamRelatedByUserteamPlayerId11 relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbPlayerteamRelatedByUserteamPlayerId11($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPlayerteamRelatedByUserteamPlayerId11');
		
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
			$this->addJoinObject($join, 'FfbPlayerteamRelatedByUserteamPlayerId11');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPlayerteamRelatedByUserteamPlayerId11 relation FfbPlayerteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPlayerteamRelatedByUserteamPlayerId11Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPlayerteamRelatedByUserteamPlayerId11($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayerteamRelatedByUserteamPlayerId11', 'FfbPlayerteamQuery');
	}

	/**
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $ffbMatchround->getMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function joinFfbMatchround($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatchround');
		
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
			$this->addJoinObject($join, 'FfbMatchround');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatchround relation FfbMatchround object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchroundQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchroundQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatchround($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatchround', 'FfbMatchroundQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbUserteam $ffbUserteam Object to remove from the list of results
	 *
	 * @return    FfbUserteamQuery The current query, for fluid interface
	 */
	public function prune($ffbUserteam = null)
	{
		if ($ffbUserteam) {
			$this->addUsingAlias(FfbUserteamPeer::USERTEAM_ID, $ffbUserteam->getUserteamId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbUserteamQuery
