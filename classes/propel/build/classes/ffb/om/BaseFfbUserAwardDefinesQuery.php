<?php


/**
 * Base class that represents a query for the 'ffb_user_award_defines' table.
 *
 * 
 *
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesId($order = Criteria::ASC) Order by the user_award_defines_id column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAwardId($order = Criteria::ASC) Order by the user_award_defines_award_id column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesRank($order = Criteria::ASC) Order by the user_award_defines_rank column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesRankName($order = Criteria::ASC) Order by the user_award_defines_rank_name column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAim($order = Criteria::ASC) Order by the user_award_defines_aim column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAimDbtable($order = Criteria::ASC) Order by the user_award_defines_aim_dbtable column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAimOperator($order = Criteria::ASC) Order by the user_award_defines_aim_operator column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAimCount($order = Criteria::ASC) Order by the user_award_defines_aim_count column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAimAutomatic($order = Criteria::ASC) Order by the user_award_defines_aim_automatic column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesAimFunctionName($order = Criteria::ASC) Order by the user_award_defines_aim_function_name column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesImage($order = Criteria::ASC) Order by the user_award_defines_image column
 * @method     FfbUserAwardDefinesQuery orderByUserAwardDefinesDescription($order = Criteria::ASC) Order by the user_award_defines_description column
 *
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesId() Group by the user_award_defines_id column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAwardId() Group by the user_award_defines_award_id column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesRank() Group by the user_award_defines_rank column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesRankName() Group by the user_award_defines_rank_name column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAim() Group by the user_award_defines_aim column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAimDbtable() Group by the user_award_defines_aim_dbtable column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAimOperator() Group by the user_award_defines_aim_operator column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAimCount() Group by the user_award_defines_aim_count column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAimAutomatic() Group by the user_award_defines_aim_automatic column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesAimFunctionName() Group by the user_award_defines_aim_function_name column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesImage() Group by the user_award_defines_image column
 * @method     FfbUserAwardDefinesQuery groupByUserAwardDefinesDescription() Group by the user_award_defines_description column
 *
 * @method     FfbUserAwardDefinesQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbUserAwardDefinesQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbUserAwardDefinesQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbUserAwardDefinesQuery leftJoinFfbUserAward($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserAward relation
 * @method     FfbUserAwardDefinesQuery rightJoinFfbUserAward($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserAward relation
 * @method     FfbUserAwardDefinesQuery innerJoinFfbUserAward($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserAward relation
 *
 * @method     FfbUserAwardDefinesQuery leftJoinFfbUserAwardFinished($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserAwardFinished relation
 * @method     FfbUserAwardDefinesQuery rightJoinFfbUserAwardFinished($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserAwardFinished relation
 * @method     FfbUserAwardDefinesQuery innerJoinFfbUserAwardFinished($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserAwardFinished relation
 *
 * @method     FfbUserAwardDefines findOne(PropelPDO $con = null) Return the first FfbUserAwardDefines matching the query
 * @method     FfbUserAwardDefines findOneOrCreate(PropelPDO $con = null) Return the first FfbUserAwardDefines matching the query, or a new FfbUserAwardDefines object populated from the query conditions when no match is found
 *
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesId(int $user_award_defines_id) Return the first FfbUserAwardDefines filtered by the user_award_defines_id column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAwardId(int $user_award_defines_award_id) Return the first FfbUserAwardDefines filtered by the user_award_defines_award_id column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesRank(int $user_award_defines_rank) Return the first FfbUserAwardDefines filtered by the user_award_defines_rank column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesRankName(string $user_award_defines_rank_name) Return the first FfbUserAwardDefines filtered by the user_award_defines_rank_name column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAim(string $user_award_defines_aim) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAimDbtable(string $user_award_defines_aim_dbtable) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim_dbtable column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAimOperator(string $user_award_defines_aim_operator) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim_operator column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAimCount(int $user_award_defines_aim_count) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim_count column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAimAutomatic(boolean $user_award_defines_aim_automatic) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim_automatic column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesAimFunctionName(string $user_award_defines_aim_function_name) Return the first FfbUserAwardDefines filtered by the user_award_defines_aim_function_name column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesImage(string $user_award_defines_image) Return the first FfbUserAwardDefines filtered by the user_award_defines_image column
 * @method     FfbUserAwardDefines findOneByUserAwardDefinesDescription(string $user_award_defines_description) Return the first FfbUserAwardDefines filtered by the user_award_defines_description column
 *
 * @method     array findByUserAwardDefinesId(int $user_award_defines_id) Return FfbUserAwardDefines objects filtered by the user_award_defines_id column
 * @method     array findByUserAwardDefinesAwardId(int $user_award_defines_award_id) Return FfbUserAwardDefines objects filtered by the user_award_defines_award_id column
 * @method     array findByUserAwardDefinesRank(int $user_award_defines_rank) Return FfbUserAwardDefines objects filtered by the user_award_defines_rank column
 * @method     array findByUserAwardDefinesRankName(string $user_award_defines_rank_name) Return FfbUserAwardDefines objects filtered by the user_award_defines_rank_name column
 * @method     array findByUserAwardDefinesAim(string $user_award_defines_aim) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim column
 * @method     array findByUserAwardDefinesAimDbtable(string $user_award_defines_aim_dbtable) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim_dbtable column
 * @method     array findByUserAwardDefinesAimOperator(string $user_award_defines_aim_operator) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim_operator column
 * @method     array findByUserAwardDefinesAimCount(int $user_award_defines_aim_count) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim_count column
 * @method     array findByUserAwardDefinesAimAutomatic(boolean $user_award_defines_aim_automatic) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim_automatic column
 * @method     array findByUserAwardDefinesAimFunctionName(string $user_award_defines_aim_function_name) Return FfbUserAwardDefines objects filtered by the user_award_defines_aim_function_name column
 * @method     array findByUserAwardDefinesImage(string $user_award_defines_image) Return FfbUserAwardDefines objects filtered by the user_award_defines_image column
 * @method     array findByUserAwardDefinesDescription(string $user_award_defines_description) Return FfbUserAwardDefines objects filtered by the user_award_defines_description column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbUserAwardDefinesQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbUserAwardDefinesQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbUserAwardDefines', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbUserAwardDefinesQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbUserAwardDefinesQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbUserAwardDefinesQuery) {
			return $criteria;
		}
		$query = new FfbUserAwardDefinesQuery();
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
	 * @return    FfbUserAwardDefines|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbUserAwardDefinesPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_award_defines_id column
	 * 
	 * @param     int|array $userAwardDefinesId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesId($userAwardDefinesId = null, $comparison = null)
	{
		if (is_array($userAwardDefinesId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $userAwardDefinesId, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_award_id column
	 * 
	 * @param     int|array $userAwardDefinesAwardId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAwardId($userAwardDefinesAwardId = null, $comparison = null)
	{
		if (is_array($userAwardDefinesAwardId)) {
			$useMinMax = false;
			if (isset($userAwardDefinesAwardId['min'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $userAwardDefinesAwardId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardDefinesAwardId['max'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $userAwardDefinesAwardId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $userAwardDefinesAwardId, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_rank column
	 * 
	 * @param     int|array $userAwardDefinesRank The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesRank($userAwardDefinesRank = null, $comparison = null)
	{
		if (is_array($userAwardDefinesRank)) {
			$useMinMax = false;
			if (isset($userAwardDefinesRank['min'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK, $userAwardDefinesRank['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardDefinesRank['max'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK, $userAwardDefinesRank['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK, $userAwardDefinesRank, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_rank_name column
	 * 
	 * @param     string $userAwardDefinesRankName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesRankName($userAwardDefinesRankName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesRankName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesRankName)) {
				$userAwardDefinesRankName = str_replace('*', '%', $userAwardDefinesRankName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_RANK_NAME, $userAwardDefinesRankName, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim column
	 * 
	 * @param     string $userAwardDefinesAim The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAim($userAwardDefinesAim = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesAim)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesAim)) {
				$userAwardDefinesAim = str_replace('*', '%', $userAwardDefinesAim);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM, $userAwardDefinesAim, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim_dbtable column
	 * 
	 * @param     string $userAwardDefinesAimDbtable The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAimDbtable($userAwardDefinesAimDbtable = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesAimDbtable)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesAimDbtable)) {
				$userAwardDefinesAimDbtable = str_replace('*', '%', $userAwardDefinesAimDbtable);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_DBTABLE, $userAwardDefinesAimDbtable, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim_operator column
	 * 
	 * @param     string $userAwardDefinesAimOperator The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAimOperator($userAwardDefinesAimOperator = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesAimOperator)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesAimOperator)) {
				$userAwardDefinesAimOperator = str_replace('*', '%', $userAwardDefinesAimOperator);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_OPERATOR, $userAwardDefinesAimOperator, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim_count column
	 * 
	 * @param     int|array $userAwardDefinesAimCount The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAimCount($userAwardDefinesAimCount = null, $comparison = null)
	{
		if (is_array($userAwardDefinesAimCount)) {
			$useMinMax = false;
			if (isset($userAwardDefinesAimCount['min'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT, $userAwardDefinesAimCount['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userAwardDefinesAimCount['max'])) {
				$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT, $userAwardDefinesAimCount['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_COUNT, $userAwardDefinesAimCount, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim_automatic column
	 * 
	 * @param     boolean|string $userAwardDefinesAimAutomatic The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAimAutomatic($userAwardDefinesAimAutomatic = null, $comparison = null)
	{
		if (is_string($userAwardDefinesAimAutomatic)) {
			$user_award_defines_aim_automatic = in_array(strtolower($userAwardDefinesAimAutomatic), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_AUTOMATIC, $userAwardDefinesAimAutomatic, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_aim_function_name column
	 * 
	 * @param     string $userAwardDefinesAimFunctionName The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesAimFunctionName($userAwardDefinesAimFunctionName = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesAimFunctionName)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesAimFunctionName)) {
				$userAwardDefinesAimFunctionName = str_replace('*', '%', $userAwardDefinesAimFunctionName);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AIM_FUNCTION_NAME, $userAwardDefinesAimFunctionName, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_image column
	 * 
	 * @param     string $userAwardDefinesImage The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesImage($userAwardDefinesImage = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesImage)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesImage)) {
				$userAwardDefinesImage = str_replace('*', '%', $userAwardDefinesImage);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_IMAGE, $userAwardDefinesImage, $comparison);
	}

	/**
	 * Filter the query on the user_award_defines_description column
	 * 
	 * @param     string $userAwardDefinesDescription The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByUserAwardDefinesDescription($userAwardDefinesDescription = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userAwardDefinesDescription)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userAwardDefinesDescription)) {
				$userAwardDefinesDescription = str_replace('*', '%', $userAwardDefinesDescription);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_DESCRIPTION, $userAwardDefinesDescription, $comparison);
	}

	/**
	 * Filter the query by a related FfbUserAward object
	 *
	 * @param     FfbUserAward $ffbUserAward  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByFfbUserAward($ffbUserAward, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_AWARD_ID, $ffbUserAward->getUserAwardId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserAward relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function joinFfbUserAward($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserAward');
		
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
			$this->addJoinObject($join, 'FfbUserAward');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserAward relation FfbUserAward object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserAwardQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserAward($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserAward', 'FfbUserAwardQuery');
	}

	/**
	 * Filter the query by a related FfbUserAwardFinished object
	 *
	 * @param     FfbUserAwardFinished $ffbUserAwardFinished  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function filterByFfbUserAwardFinished($ffbUserAwardFinished, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $ffbUserAwardFinished->getUserAwardFinishedAwardDefinesId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserAwardFinished relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function joinFfbUserAwardFinished($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserAwardFinished');
		
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
			$this->addJoinObject($join, 'FfbUserAwardFinished');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserAwardFinished relation FfbUserAwardFinished object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserAwardFinishedQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserAwardFinishedQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserAwardFinished($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserAwardFinished', 'FfbUserAwardFinishedQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     FfbUserAwardDefines $ffbUserAwardDefines Object to remove from the list of results
	 *
	 * @return    FfbUserAwardDefinesQuery The current query, for fluid interface
	 */
	public function prune($ffbUserAwardDefines = null)
	{
		if ($ffbUserAwardDefines) {
			$this->addUsingAlias(FfbUserAwardDefinesPeer::USER_AWARD_DEFINES_ID, $ffbUserAwardDefines->getUserAwardDefinesId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbUserAwardDefinesQuery
