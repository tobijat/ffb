<?php


/**
 * Base class that represents a query for the 'web_user' table.
 *
 * 
 *
 * @method     WebUserQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     WebUserQuery orderByUserNickname($order = Criteria::ASC) Order by the user_nickname column
 * @method     WebUserQuery orderByUserPassword($order = Criteria::ASC) Order by the user_password column
 * @method     WebUserQuery orderByUserEmail($order = Criteria::ASC) Order by the user_email column
 * @method     WebUserQuery orderByUserFname($order = Criteria::ASC) Order by the user_fname column
 * @method     WebUserQuery orderByUserLname($order = Criteria::ASC) Order by the user_lname column
 * @method     WebUserQuery orderByUserGender($order = Criteria::ASC) Order by the user_gender column
 * @method     WebUserQuery orderByUserStatus($order = Criteria::ASC) Order by the user_status column
 * @method     WebUserQuery orderByUserAdmin($order = Criteria::ASC) Order by the user_admin column
 * @method     WebUserQuery orderByUserNationality($order = Criteria::ASC) Order by the user_nationality column
 * @method     WebUserQuery orderByUserDateBirth($order = Criteria::ASC) Order by the user_date_birth column
 * @method     WebUserQuery orderByUserIp($order = Criteria::ASC) Order by the user_ip column
 * @method     WebUserQuery orderByUserLip($order = Criteria::ASC) Order by the user_lip column
 * @method     WebUserQuery orderByUserDateRegister($order = Criteria::ASC) Order by the user_date_register column
 * @method     WebUserQuery orderByUserDateLlogin($order = Criteria::ASC) Order by the user_date_llogin column
 * @method     WebUserQuery orderByUserDateLaction($order = Criteria::ASC) Order by the user_date_laction column
 * @method     WebUserQuery orderByUserActivationCode($order = Criteria::ASC) Order by the user_activation_code column
 * @method     WebUserQuery orderByUserMailservice($order = Criteria::ASC) Order by the user_mailservice column
 *
 * @method     WebUserQuery groupByUserId() Group by the user_id column
 * @method     WebUserQuery groupByUserNickname() Group by the user_nickname column
 * @method     WebUserQuery groupByUserPassword() Group by the user_password column
 * @method     WebUserQuery groupByUserEmail() Group by the user_email column
 * @method     WebUserQuery groupByUserFname() Group by the user_fname column
 * @method     WebUserQuery groupByUserLname() Group by the user_lname column
 * @method     WebUserQuery groupByUserGender() Group by the user_gender column
 * @method     WebUserQuery groupByUserStatus() Group by the user_status column
 * @method     WebUserQuery groupByUserAdmin() Group by the user_admin column
 * @method     WebUserQuery groupByUserNationality() Group by the user_nationality column
 * @method     WebUserQuery groupByUserDateBirth() Group by the user_date_birth column
 * @method     WebUserQuery groupByUserIp() Group by the user_ip column
 * @method     WebUserQuery groupByUserLip() Group by the user_lip column
 * @method     WebUserQuery groupByUserDateRegister() Group by the user_date_register column
 * @method     WebUserQuery groupByUserDateLlogin() Group by the user_date_llogin column
 * @method     WebUserQuery groupByUserDateLaction() Group by the user_date_laction column
 * @method     WebUserQuery groupByUserActivationCode() Group by the user_activation_code column
 * @method     WebUserQuery groupByUserMailservice() Group by the user_mailservice column
 *
 * @method     WebUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebUserQuery leftJoinWebUserDetails($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserDetails relation
 * @method     WebUserQuery rightJoinWebUserDetails($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserDetails relation
 * @method     WebUserQuery innerJoinWebUserDetails($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserDetails relation
 *
 * @method     WebUserQuery leftJoinWebUserPermissions($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUserPermissions relation
 * @method     WebUserQuery rightJoinWebUserPermissions($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUserPermissions relation
 * @method     WebUserQuery innerJoinWebUserPermissions($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUserPermissions relation
 *
 * @method     WebUserQuery leftJoinFfbComments($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbComments relation
 * @method     WebUserQuery rightJoinFfbComments($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbComments relation
 * @method     WebUserQuery innerJoinFfbComments($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbComments relation
 *
 * @method     WebUserQuery leftJoinFfbPollResult($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPollResult relation
 * @method     WebUserQuery rightJoinFfbPollResult($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPollResult relation
 * @method     WebUserQuery innerJoinFfbPollResult($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPollResult relation
 *
 * @method     WebUserQuery leftJoinFfbInvitation($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbInvitation relation
 * @method     WebUserQuery rightJoinFfbInvitation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbInvitation relation
 * @method     WebUserQuery innerJoinFfbInvitation($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbInvitation relation
 *
 * @method     WebUserQuery leftJoinFfbUserteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserteam relation
 * @method     WebUserQuery rightJoinFfbUserteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserteam relation
 * @method     WebUserQuery innerJoinFfbUserteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserteam relation
 *
 * @method     WebUserQuery leftJoinFfbUserscore($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserscore relation
 * @method     WebUserQuery rightJoinFfbUserscore($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserscore relation
 * @method     WebUserQuery innerJoinFfbUserscore($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserscore relation
 *
 * @method     WebUserQuery leftJoinFfbAdmin($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbAdmin relation
 * @method     WebUserQuery rightJoinFfbAdmin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbAdmin relation
 * @method     WebUserQuery innerJoinFfbAdmin($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbAdmin relation
 *
 * @method     WebUserQuery leftJoinWebLog($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebLog relation
 * @method     WebUserQuery rightJoinWebLog($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebLog relation
 * @method     WebUserQuery innerJoinWebLog($relationAlias = null) Adds a INNER JOIN clause to the query using the WebLog relation
 *
 * @method     WebUserQuery leftJoinFfbUserAwardFinished($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbUserAwardFinished relation
 * @method     WebUserQuery rightJoinFfbUserAwardFinished($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbUserAwardFinished relation
 * @method     WebUserQuery innerJoinFfbUserAwardFinished($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbUserAwardFinished relation
 *
 * @method     WebUserQuery leftJoinWebAdmin($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebAdmin relation
 * @method     WebUserQuery rightJoinWebAdmin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebAdmin relation
 * @method     WebUserQuery innerJoinWebAdmin($relationAlias = null) Adds a INNER JOIN clause to the query using the WebAdmin relation
 *
 * @method     WebUser findOne(?PropelPDO $con = null) Return the first WebUser matching the query
 * @method     WebUser findOneOrCreate(?PropelPDO $con = null) Return the first WebUser matching the query, or a new WebUser object populated from the query conditions when no match is found
 *
 * @method     WebUser findOneByUserId(int $user_id) Return the first WebUser filtered by the user_id column
 * @method     WebUser findOneByUserNickname(string $user_nickname) Return the first WebUser filtered by the user_nickname column
 * @method     WebUser findOneByUserPassword(string $user_password) Return the first WebUser filtered by the user_password column
 * @method     WebUser findOneByUserEmail(string $user_email) Return the first WebUser filtered by the user_email column
 * @method     WebUser findOneByUserFname(string $user_fname) Return the first WebUser filtered by the user_fname column
 * @method     WebUser findOneByUserLname(string $user_lname) Return the first WebUser filtered by the user_lname column
 * @method     WebUser findOneByUserGender(string $user_gender) Return the first WebUser filtered by the user_gender column
 * @method     WebUser findOneByUserStatus(string $user_status) Return the first WebUser filtered by the user_status column
 * @method     WebUser findOneByUserAdmin(boolean $user_admin) Return the first WebUser filtered by the user_admin column
 * @method     WebUser findOneByUserNationality(string $user_nationality) Return the first WebUser filtered by the user_nationality column
 * @method     WebUser findOneByUserDateBirth(string $user_date_birth) Return the first WebUser filtered by the user_date_birth column
 * @method     WebUser findOneByUserIp(string $user_ip) Return the first WebUser filtered by the user_ip column
 * @method     WebUser findOneByUserLip(string $user_lip) Return the first WebUser filtered by the user_lip column
 * @method     WebUser findOneByUserDateRegister(string $user_date_register) Return the first WebUser filtered by the user_date_register column
 * @method     WebUser findOneByUserDateLlogin(string $user_date_llogin) Return the first WebUser filtered by the user_date_llogin column
 * @method     WebUser findOneByUserDateLaction(string $user_date_laction) Return the first WebUser filtered by the user_date_laction column
 * @method     WebUser findOneByUserActivationCode(string $user_activation_code) Return the first WebUser filtered by the user_activation_code column
 * @method     WebUser findOneByUserMailservice(string $user_mailservice) Return the first WebUser filtered by the user_mailservice column
 *
 * @method     array findByUserId(int $user_id) Return WebUser objects filtered by the user_id column
 * @method     array findByUserNickname(string $user_nickname) Return WebUser objects filtered by the user_nickname column
 * @method     array findByUserPassword(string $user_password) Return WebUser objects filtered by the user_password column
 * @method     array findByUserEmail(string $user_email) Return WebUser objects filtered by the user_email column
 * @method     array findByUserFname(string $user_fname) Return WebUser objects filtered by the user_fname column
 * @method     array findByUserLname(string $user_lname) Return WebUser objects filtered by the user_lname column
 * @method     array findByUserGender(string $user_gender) Return WebUser objects filtered by the user_gender column
 * @method     array findByUserStatus(string $user_status) Return WebUser objects filtered by the user_status column
 * @method     array findByUserAdmin(boolean $user_admin) Return WebUser objects filtered by the user_admin column
 * @method     array findByUserNationality(string $user_nationality) Return WebUser objects filtered by the user_nationality column
 * @method     array findByUserDateBirth(string $user_date_birth) Return WebUser objects filtered by the user_date_birth column
 * @method     array findByUserIp(string $user_ip) Return WebUser objects filtered by the user_ip column
 * @method     array findByUserLip(string $user_lip) Return WebUser objects filtered by the user_lip column
 * @method     array findByUserDateRegister(string $user_date_register) Return WebUser objects filtered by the user_date_register column
 * @method     array findByUserDateLlogin(string $user_date_llogin) Return WebUser objects filtered by the user_date_llogin column
 * @method     array findByUserDateLaction(string $user_date_laction) Return WebUser objects filtered by the user_date_laction column
 * @method     array findByUserActivationCode(string $user_activation_code) Return WebUser objects filtered by the user_activation_code column
 * @method     array findByUserMailservice(string $user_mailservice) Return WebUser objects filtered by the user_mailservice column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebUserQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebUserQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebUser', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebUserQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebUserQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebUserQuery) {
			return $criteria;
		}
		$query = new WebUserQuery();
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
	 * @return    WebUser|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebUserPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebUserPeer::USER_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebUserPeer::USER_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_id column
	 * 
	 * @param     int|array $userId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebUserPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the user_nickname column
	 * 
	 * @param     string $userNickname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserNickname($userNickname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userNickname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userNickname)) {
				$userNickname = str_replace('*', '%', $userNickname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_NICKNAME, $userNickname, $comparison);
	}

	/**
	 * Filter the query on the user_password column
	 * 
	 * @param     string $userPassword The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserPassword($userPassword = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userPassword)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userPassword)) {
				$userPassword = str_replace('*', '%', $userPassword);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_PASSWORD, $userPassword, $comparison);
	}

	/**
	 * Filter the query on the user_email column
	 * 
	 * @param     string $userEmail The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserEmail($userEmail = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userEmail)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userEmail)) {
				$userEmail = str_replace('*', '%', $userEmail);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_EMAIL, $userEmail, $comparison);
	}

	/**
	 * Filter the query on the user_fname column
	 * 
	 * @param     string $userFname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserFname($userFname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userFname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userFname)) {
				$userFname = str_replace('*', '%', $userFname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_FNAME, $userFname, $comparison);
	}

	/**
	 * Filter the query on the user_lname column
	 * 
	 * @param     string $userLname The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserLname($userLname = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userLname)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userLname)) {
				$userLname = str_replace('*', '%', $userLname);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_LNAME, $userLname, $comparison);
	}

	/**
	 * Filter the query on the user_gender column
	 * 
	 * @param     string $userGender The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserGender($userGender = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userGender)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userGender)) {
				$userGender = str_replace('*', '%', $userGender);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_GENDER, $userGender, $comparison);
	}

	/**
	 * Filter the query on the user_status column
	 * 
	 * @param     string $userStatus The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserStatus($userStatus = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userStatus)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userStatus)) {
				$userStatus = str_replace('*', '%', $userStatus);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_STATUS, $userStatus, $comparison);
	}

	/**
	 * Filter the query on the user_admin column
	 * 
	 * @param     boolean|string $userAdmin The value to use as filter.
	 *            Accepts strings ('false', 'off', '-', 'no', 'n', and '0' are false, the rest is true)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserAdmin($userAdmin = null, $comparison = null)
	{
		if (is_string($userAdmin)) {
			$user_admin = in_array(strtolower($userAdmin), array('false', 'off', '-', 'no', 'n', '0')) ? false : true;
		}
		return $this->addUsingAlias(WebUserPeer::USER_ADMIN, $userAdmin, $comparison);
	}

	/**
	 * Filter the query on the user_nationality column
	 * 
	 * @param     string $userNationality The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserNationality($userNationality = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userNationality)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userNationality)) {
				$userNationality = str_replace('*', '%', $userNationality);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_NATIONALITY, $userNationality, $comparison);
	}

	/**
	 * Filter the query on the user_date_birth column
	 * 
	 * @param     string|array $userDateBirth The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserDateBirth($userDateBirth = null, $comparison = null)
	{
		if (is_array($userDateBirth)) {
			$useMinMax = false;
			if (isset($userDateBirth['min'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_BIRTH, $userDateBirth['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDateBirth['max'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_BIRTH, $userDateBirth['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_DATE_BIRTH, $userDateBirth, $comparison);
	}

	/**
	 * Filter the query on the user_ip column
	 * 
	 * @param     string $userIp The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserIp($userIp = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userIp)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userIp)) {
				$userIp = str_replace('*', '%', $userIp);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_IP, $userIp, $comparison);
	}

	/**
	 * Filter the query on the user_lip column
	 * 
	 * @param     string $userLip The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserLip($userLip = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userLip)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userLip)) {
				$userLip = str_replace('*', '%', $userLip);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_LIP, $userLip, $comparison);
	}

	/**
	 * Filter the query on the user_date_register column
	 * 
	 * @param     string|array $userDateRegister The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserDateRegister($userDateRegister = null, $comparison = null)
	{
		if (is_array($userDateRegister)) {
			$useMinMax = false;
			if (isset($userDateRegister['min'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_REGISTER, $userDateRegister['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDateRegister['max'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_REGISTER, $userDateRegister['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_DATE_REGISTER, $userDateRegister, $comparison);
	}

	/**
	 * Filter the query on the user_date_llogin column
	 * 
	 * @param     string|array $userDateLlogin The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserDateLlogin($userDateLlogin = null, $comparison = null)
	{
		if (is_array($userDateLlogin)) {
			$useMinMax = false;
			if (isset($userDateLlogin['min'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_LLOGIN, $userDateLlogin['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDateLlogin['max'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_LLOGIN, $userDateLlogin['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_DATE_LLOGIN, $userDateLlogin, $comparison);
	}

	/**
	 * Filter the query on the user_date_laction column
	 * 
	 * @param     string|array $userDateLaction The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserDateLaction($userDateLaction = null, $comparison = null)
	{
		if (is_array($userDateLaction)) {
			$useMinMax = false;
			if (isset($userDateLaction['min'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_LACTION, $userDateLaction['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDateLaction['max'])) {
				$this->addUsingAlias(WebUserPeer::USER_DATE_LACTION, $userDateLaction['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_DATE_LACTION, $userDateLaction, $comparison);
	}

	/**
	 * Filter the query on the user_activation_code column
	 * 
	 * @param     string $userActivationCode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserActivationCode($userActivationCode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userActivationCode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userActivationCode)) {
				$userActivationCode = str_replace('*', '%', $userActivationCode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_ACTIVATION_CODE, $userActivationCode, $comparison);
	}

	/**
	 * Filter the query on the user_mailservice column
	 * 
	 * @param     string $userMailservice The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByUserMailservice($userMailservice = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userMailservice)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userMailservice)) {
				$userMailservice = str_replace('*', '%', $userMailservice);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserPeer::USER_MAILSERVICE, $userMailservice, $comparison);
	}

	/**
	 * Filter the query by a related WebUserDetails object
	 *
	 * @param     WebUserDetails $webUserDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByWebUserDetails($webUserDetails, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $webUserDetails->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserDetails relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinWebUserDetails($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserDetails');
		
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
			$this->addJoinObject($join, 'WebUserDetails');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserDetails relation WebUserDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserDetailsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebUserDetails($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserDetails', 'WebUserDetailsQuery');
	}

	/**
	 * Filter the query by a related WebUserPermissions object
	 *
	 * @param     WebUserPermissions $webUserPermissions  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByWebUserPermissions($webUserPermissions, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $webUserPermissions->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUserPermissions relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinWebUserPermissions($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebUserPermissions');
		
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
			$this->addJoinObject($join, 'WebUserPermissions');
		}
		
		return $this;
	}

	/**
	 * Use the WebUserPermissions relation WebUserPermissions object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserPermissionsQuery A secondary query class using the current class as primary query
	 */
	public function useWebUserPermissionsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebUserPermissions($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebUserPermissions', 'WebUserPermissionsQuery');
	}

	/**
	 * Filter the query by a related FfbComments object
	 *
	 * @param     FfbComments $ffbComments  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbComments($ffbComments, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbComments->getCommentsUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbComments relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbComments($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbComments');
		
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
			$this->addJoinObject($join, 'FfbComments');
		}
		
		return $this;
	}

	/**
	 * Use the FfbComments relation FfbComments object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbCommentsQuery A secondary query class using the current class as primary query
	 */
	public function useFfbCommentsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbComments($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbComments', 'FfbCommentsQuery');
	}

	/**
	 * Filter the query by a related FfbPollResult object
	 *
	 * @param     FfbPollResult $ffbPollResult  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbPollResult($ffbPollResult, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbPollResult->getPollResultUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPollResult relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbPollResult($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbPollResult');
		
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
			$this->addJoinObject($join, 'FfbPollResult');
		}
		
		return $this;
	}

	/**
	 * Use the FfbPollResult relation FfbPollResult object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPollResultQuery A secondary query class using the current class as primary query
	 */
	public function useFfbPollResultQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbPollResult($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPollResult', 'FfbPollResultQuery');
	}

	/**
	 * Filter the query by a related FfbInvitation object
	 *
	 * @param     FfbInvitation $ffbInvitation  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbInvitation($ffbInvitation, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbInvitation->getInvitationSenderId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbInvitation relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbInvitation($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbInvitation');
		
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
			$this->addJoinObject($join, 'FfbInvitation');
		}
		
		return $this;
	}

	/**
	 * Use the FfbInvitation relation FfbInvitation object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbInvitationQuery A secondary query class using the current class as primary query
	 */
	public function useFfbInvitationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbInvitation($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbInvitation', 'FfbInvitationQuery');
	}

	/**
	 * Filter the query by a related FfbUserteam object
	 *
	 * @param     FfbUserteam $ffbUserteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbUserteam($ffbUserteam, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbUserteam->getUserteamUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbUserteam($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserteam');
		
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
			$this->addJoinObject($join, 'FfbUserteam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserteam relation FfbUserteam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserteamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserteamQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserteam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserteam', 'FfbUserteamQuery');
	}

	/**
	 * Filter the query by a related FfbUserscore object
	 *
	 * @param     FfbUserscore $ffbUserscore  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbUserscore($ffbUserscore, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbUserscore->getUserscoreUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserscore relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbUserscore($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbUserscore');
		
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
			$this->addJoinObject($join, 'FfbUserscore');
		}
		
		return $this;
	}

	/**
	 * Use the FfbUserscore relation FfbUserscore object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbUserscoreQuery A secondary query class using the current class as primary query
	 */
	public function useFfbUserscoreQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbUserscore($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbUserscore', 'FfbUserscoreQuery');
	}

	/**
	 * Filter the query by a related FfbAdmin object
	 *
	 * @param     FfbAdmin $ffbAdmin  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbAdmin($ffbAdmin, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbAdmin->getAdminUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbAdmin relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinFfbAdmin($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbAdmin');
		
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
			$this->addJoinObject($join, 'FfbAdmin');
		}
		
		return $this;
	}

	/**
	 * Use the FfbAdmin relation FfbAdmin object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbAdminQuery A secondary query class using the current class as primary query
	 */
	public function useFfbAdminQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbAdmin($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbAdmin', 'FfbAdminQuery');
	}

	/**
	 * Filter the query by a related WebLog object
	 *
	 * @param     WebLog $webLog  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByWebLog($webLog, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $webLog->getLogUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebLog relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinWebLog($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebLog');
		
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
			$this->addJoinObject($join, 'WebLog');
		}
		
		return $this;
	}

	/**
	 * Use the WebLog relation WebLog object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebLogQuery A secondary query class using the current class as primary query
	 */
	public function useWebLogQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebLog($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebLog', 'WebLogQuery');
	}

	/**
	 * Filter the query by a related FfbUserAwardFinished object
	 *
	 * @param     FfbUserAwardFinished $ffbUserAwardFinished  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByFfbUserAwardFinished($ffbUserAwardFinished, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $ffbUserAwardFinished->getUserAwardFinishedUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbUserAwardFinished relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
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
	 * Filter the query by a related WebAdmin object
	 *
	 * @param     WebAdmin $webAdmin  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function filterByWebAdmin($webAdmin, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserPeer::USER_ID, $webAdmin->getAdminUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebAdmin relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function joinWebAdmin($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('WebAdmin');
		
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
			$this->addJoinObject($join, 'WebAdmin');
		}
		
		return $this;
	}

	/**
	 * Use the WebAdmin relation WebAdmin object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebAdminQuery A secondary query class using the current class as primary query
	 */
	public function useWebAdminQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinWebAdmin($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'WebAdmin', 'WebAdminQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     WebUser $webUser Object to remove from the list of results
	 *
	 * @return    WebUserQuery The current query, for fluid interface
	 */
	public function prune($webUser = null)
	{
		if ($webUser) {
			$this->addUsingAlias(WebUserPeer::USER_ID, $webUser->getUserId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebUserQuery
