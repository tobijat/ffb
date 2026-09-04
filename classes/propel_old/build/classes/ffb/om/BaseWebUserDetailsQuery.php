<?php


/**
 * Base class that represents a query for the 'web_user_details' table.
 *
 * 
 *
 * @method     WebUserDetailsQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     WebUserDetailsQuery orderByUserDetailsAvatar($order = Criteria::ASC) Order by the user_details_avatar column
 * @method     WebUserDetailsQuery orderByUserDetailsPhoto($order = Criteria::ASC) Order by the user_details_photo column
 * @method     WebUserDetailsQuery orderByUserDetailsWebsite($order = Criteria::ASC) Order by the user_details_website column
 * @method     WebUserDetailsQuery orderByUserDetailsZip($order = Criteria::ASC) Order by the user_details_zip column
 * @method     WebUserDetailsQuery orderByUserDetailsStreet($order = Criteria::ASC) Order by the user_details_street column
 * @method     WebUserDetailsQuery orderByUserDetailsCity($order = Criteria::ASC) Order by the user_details_city column
 * @method     WebUserDetailsQuery orderByUserDetailsPhone($order = Criteria::ASC) Order by the user_details_phone column
 * @method     WebUserDetailsQuery orderByUserDetailsFfbFavouriteTeam($order = Criteria::ASC) Order by the user_details_ffb_favourite_team column
 * @method     WebUserDetailsQuery orderByUserDetailsFfbOwnTeam($order = Criteria::ASC) Order by the user_details_ffb_own_team column
 * @method     WebUserDetailsQuery orderByUserDetailsFfbOwnPlayer($order = Criteria::ASC) Order by the user_details_ffb_own_player column
 * @method     WebUserDetailsQuery orderByUserDetailsFfbSelectedGame($order = Criteria::ASC) Order by the user_details_ffb_selected_game column
 * @method     WebUserDetailsQuery orderByUserDetailsLastUpdate($order = Criteria::ASC) Order by the user_details_last_update column
 *
 * @method     WebUserDetailsQuery groupByUserId() Group by the user_id column
 * @method     WebUserDetailsQuery groupByUserDetailsAvatar() Group by the user_details_avatar column
 * @method     WebUserDetailsQuery groupByUserDetailsPhoto() Group by the user_details_photo column
 * @method     WebUserDetailsQuery groupByUserDetailsWebsite() Group by the user_details_website column
 * @method     WebUserDetailsQuery groupByUserDetailsZip() Group by the user_details_zip column
 * @method     WebUserDetailsQuery groupByUserDetailsStreet() Group by the user_details_street column
 * @method     WebUserDetailsQuery groupByUserDetailsCity() Group by the user_details_city column
 * @method     WebUserDetailsQuery groupByUserDetailsPhone() Group by the user_details_phone column
 * @method     WebUserDetailsQuery groupByUserDetailsFfbFavouriteTeam() Group by the user_details_ffb_favourite_team column
 * @method     WebUserDetailsQuery groupByUserDetailsFfbOwnTeam() Group by the user_details_ffb_own_team column
 * @method     WebUserDetailsQuery groupByUserDetailsFfbOwnPlayer() Group by the user_details_ffb_own_player column
 * @method     WebUserDetailsQuery groupByUserDetailsFfbSelectedGame() Group by the user_details_ffb_selected_game column
 * @method     WebUserDetailsQuery groupByUserDetailsLastUpdate() Group by the user_details_last_update column
 *
 * @method     WebUserDetailsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     WebUserDetailsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     WebUserDetailsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     WebUserDetailsQuery leftJoinWebUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the WebUser relation
 * @method     WebUserDetailsQuery rightJoinWebUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WebUser relation
 * @method     WebUserDetailsQuery innerJoinWebUser($relationAlias = null) Adds a INNER JOIN clause to the query using the WebUser relation
 *
 * @method     WebUserDetailsQuery leftJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbFavouriteTeam relation
 * @method     WebUserDetailsQuery rightJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbFavouriteTeam relation
 * @method     WebUserDetailsQuery innerJoinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbFavouriteTeam relation
 *
 * @method     WebUserDetailsQuery leftJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbOwnTeam relation
 * @method     WebUserDetailsQuery rightJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbOwnTeam relation
 * @method     WebUserDetailsQuery innerJoinFfbTeamRelatedByUserDetailsFfbOwnTeam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbOwnTeam relation
 *
 * @method     WebUserDetailsQuery leftJoinFfbPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayer relation
 * @method     WebUserDetailsQuery rightJoinFfbPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayer relation
 * @method     WebUserDetailsQuery innerJoinFfbPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayer relation
 *
 * @method     WebUserDetailsQuery leftJoinFfbGame($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbGame relation
 * @method     WebUserDetailsQuery rightJoinFfbGame($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbGame relation
 * @method     WebUserDetailsQuery innerJoinFfbGame($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbGame relation
 *
 * @method     WebUserDetails findOne(PropelPDO $con = null) Return the first WebUserDetails matching the query
 * @method     WebUserDetails findOneOrCreate(PropelPDO $con = null) Return the first WebUserDetails matching the query, or a new WebUserDetails object populated from the query conditions when no match is found
 *
 * @method     WebUserDetails findOneByUserId(int $user_id) Return the first WebUserDetails filtered by the user_id column
 * @method     WebUserDetails findOneByUserDetailsAvatar(string $user_details_avatar) Return the first WebUserDetails filtered by the user_details_avatar column
 * @method     WebUserDetails findOneByUserDetailsPhoto(string $user_details_photo) Return the first WebUserDetails filtered by the user_details_photo column
 * @method     WebUserDetails findOneByUserDetailsWebsite(string $user_details_website) Return the first WebUserDetails filtered by the user_details_website column
 * @method     WebUserDetails findOneByUserDetailsZip(string $user_details_zip) Return the first WebUserDetails filtered by the user_details_zip column
 * @method     WebUserDetails findOneByUserDetailsStreet(string $user_details_street) Return the first WebUserDetails filtered by the user_details_street column
 * @method     WebUserDetails findOneByUserDetailsCity(string $user_details_city) Return the first WebUserDetails filtered by the user_details_city column
 * @method     WebUserDetails findOneByUserDetailsPhone(string $user_details_phone) Return the first WebUserDetails filtered by the user_details_phone column
 * @method     WebUserDetails findOneByUserDetailsFfbFavouriteTeam(int $user_details_ffb_favourite_team) Return the first WebUserDetails filtered by the user_details_ffb_favourite_team column
 * @method     WebUserDetails findOneByUserDetailsFfbOwnTeam(int $user_details_ffb_own_team) Return the first WebUserDetails filtered by the user_details_ffb_own_team column
 * @method     WebUserDetails findOneByUserDetailsFfbOwnPlayer(int $user_details_ffb_own_player) Return the first WebUserDetails filtered by the user_details_ffb_own_player column
 * @method     WebUserDetails findOneByUserDetailsFfbSelectedGame(int $user_details_ffb_selected_game) Return the first WebUserDetails filtered by the user_details_ffb_selected_game column
 * @method     WebUserDetails findOneByUserDetailsLastUpdate(string $user_details_last_update) Return the first WebUserDetails filtered by the user_details_last_update column
 *
 * @method     array findByUserId(int $user_id) Return WebUserDetails objects filtered by the user_id column
 * @method     array findByUserDetailsAvatar(string $user_details_avatar) Return WebUserDetails objects filtered by the user_details_avatar column
 * @method     array findByUserDetailsPhoto(string $user_details_photo) Return WebUserDetails objects filtered by the user_details_photo column
 * @method     array findByUserDetailsWebsite(string $user_details_website) Return WebUserDetails objects filtered by the user_details_website column
 * @method     array findByUserDetailsZip(string $user_details_zip) Return WebUserDetails objects filtered by the user_details_zip column
 * @method     array findByUserDetailsStreet(string $user_details_street) Return WebUserDetails objects filtered by the user_details_street column
 * @method     array findByUserDetailsCity(string $user_details_city) Return WebUserDetails objects filtered by the user_details_city column
 * @method     array findByUserDetailsPhone(string $user_details_phone) Return WebUserDetails objects filtered by the user_details_phone column
 * @method     array findByUserDetailsFfbFavouriteTeam(int $user_details_ffb_favourite_team) Return WebUserDetails objects filtered by the user_details_ffb_favourite_team column
 * @method     array findByUserDetailsFfbOwnTeam(int $user_details_ffb_own_team) Return WebUserDetails objects filtered by the user_details_ffb_own_team column
 * @method     array findByUserDetailsFfbOwnPlayer(int $user_details_ffb_own_player) Return WebUserDetails objects filtered by the user_details_ffb_own_player column
 * @method     array findByUserDetailsFfbSelectedGame(int $user_details_ffb_selected_game) Return WebUserDetails objects filtered by the user_details_ffb_selected_game column
 * @method     array findByUserDetailsLastUpdate(string $user_details_last_update) Return WebUserDetails objects filtered by the user_details_last_update column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseWebUserDetailsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseWebUserDetailsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'WebUserDetails', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new WebUserDetailsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    WebUserDetailsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof WebUserDetailsQuery) {
			return $criteria;
		}
		$query = new WebUserDetailsQuery();
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
	 * @return    WebUserDetails|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = WebUserDetailsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(WebUserDetailsPeer::USER_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(WebUserDetailsPeer::USER_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the user_id column
	 * 
	 * @param     int|array $userId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserId($userId = null, $comparison = null)
	{
		if (is_array($userId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_ID, $userId, $comparison);
	}

	/**
	 * Filter the query on the user_details_avatar column
	 * 
	 * @param     string $userDetailsAvatar The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsAvatar($userDetailsAvatar = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsAvatar)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsAvatar)) {
				$userDetailsAvatar = str_replace('*', '%', $userDetailsAvatar);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_AVATAR, $userDetailsAvatar, $comparison);
	}

	/**
	 * Filter the query on the user_details_photo column
	 * 
	 * @param     string $userDetailsPhoto The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsPhoto($userDetailsPhoto = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsPhoto)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsPhoto)) {
				$userDetailsPhoto = str_replace('*', '%', $userDetailsPhoto);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_PHOTO, $userDetailsPhoto, $comparison);
	}

	/**
	 * Filter the query on the user_details_website column
	 * 
	 * @param     string $userDetailsWebsite The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsWebsite($userDetailsWebsite = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsWebsite)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsWebsite)) {
				$userDetailsWebsite = str_replace('*', '%', $userDetailsWebsite);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_WEBSITE, $userDetailsWebsite, $comparison);
	}

	/**
	 * Filter the query on the user_details_zip column
	 * 
	 * @param     string $userDetailsZip The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsZip($userDetailsZip = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsZip)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsZip)) {
				$userDetailsZip = str_replace('*', '%', $userDetailsZip);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_ZIP, $userDetailsZip, $comparison);
	}

	/**
	 * Filter the query on the user_details_street column
	 * 
	 * @param     string $userDetailsStreet The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsStreet($userDetailsStreet = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsStreet)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsStreet)) {
				$userDetailsStreet = str_replace('*', '%', $userDetailsStreet);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_STREET, $userDetailsStreet, $comparison);
	}

	/**
	 * Filter the query on the user_details_city column
	 * 
	 * @param     string $userDetailsCity The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsCity($userDetailsCity = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsCity)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsCity)) {
				$userDetailsCity = str_replace('*', '%', $userDetailsCity);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_CITY, $userDetailsCity, $comparison);
	}

	/**
	 * Filter the query on the user_details_phone column
	 * 
	 * @param     string $userDetailsPhone The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsPhone($userDetailsPhone = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($userDetailsPhone)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $userDetailsPhone)) {
				$userDetailsPhone = str_replace('*', '%', $userDetailsPhone);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_PHONE, $userDetailsPhone, $comparison);
	}

	/**
	 * Filter the query on the user_details_ffb_favourite_team column
	 * 
	 * @param     int|array $userDetailsFfbFavouriteTeam The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsFfbFavouriteTeam($userDetailsFfbFavouriteTeam = null, $comparison = null)
	{
		if (is_array($userDetailsFfbFavouriteTeam)) {
			$useMinMax = false;
			if (isset($userDetailsFfbFavouriteTeam['min'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, $userDetailsFfbFavouriteTeam['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDetailsFfbFavouriteTeam['max'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, $userDetailsFfbFavouriteTeam['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, $userDetailsFfbFavouriteTeam, $comparison);
	}

	/**
	 * Filter the query on the user_details_ffb_own_team column
	 * 
	 * @param     int|array $userDetailsFfbOwnTeam The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsFfbOwnTeam($userDetailsFfbOwnTeam = null, $comparison = null)
	{
		if (is_array($userDetailsFfbOwnTeam)) {
			$useMinMax = false;
			if (isset($userDetailsFfbOwnTeam['min'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, $userDetailsFfbOwnTeam['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDetailsFfbOwnTeam['max'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, $userDetailsFfbOwnTeam['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, $userDetailsFfbOwnTeam, $comparison);
	}

	/**
	 * Filter the query on the user_details_ffb_own_player column
	 * 
	 * @param     int|array $userDetailsFfbOwnPlayer The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsFfbOwnPlayer($userDetailsFfbOwnPlayer = null, $comparison = null)
	{
		if (is_array($userDetailsFfbOwnPlayer)) {
			$useMinMax = false;
			if (isset($userDetailsFfbOwnPlayer['min'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $userDetailsFfbOwnPlayer['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDetailsFfbOwnPlayer['max'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $userDetailsFfbOwnPlayer['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $userDetailsFfbOwnPlayer, $comparison);
	}

	/**
	 * Filter the query on the user_details_ffb_selected_game column
	 * 
	 * @param     int|array $userDetailsFfbSelectedGame The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsFfbSelectedGame($userDetailsFfbSelectedGame = null, $comparison = null)
	{
		if (is_array($userDetailsFfbSelectedGame)) {
			$useMinMax = false;
			if (isset($userDetailsFfbSelectedGame['min'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $userDetailsFfbSelectedGame['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDetailsFfbSelectedGame['max'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $userDetailsFfbSelectedGame['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $userDetailsFfbSelectedGame, $comparison);
	}

	/**
	 * Filter the query on the user_details_last_update column
	 * 
	 * @param     string|array $userDetailsLastUpdate The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByUserDetailsLastUpdate($userDetailsLastUpdate = null, $comparison = null)
	{
		if (is_array($userDetailsLastUpdate)) {
			$useMinMax = false;
			if (isset($userDetailsLastUpdate['min'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE, $userDetailsLastUpdate['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userDetailsLastUpdate['max'])) {
				$this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE, $userDetailsLastUpdate['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_LAST_UPDATE, $userDetailsLastUpdate, $comparison);
	}

	/**
	 * Filter the query by a related WebUser object
	 *
	 * @param     WebUser $webUser  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByWebUser($webUser, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserDetailsPeer::USER_ID, $webUser->getUserId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the WebUser relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByFfbTeamRelatedByUserDetailsFfbFavouriteTeam($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_FAVOURITE_TEAM, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbFavouriteTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function joinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeamRelatedByUserDetailsFfbFavouriteTeam');
		
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
			$this->addJoinObject($join, 'FfbTeamRelatedByUserDetailsFfbFavouriteTeam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeamRelatedByUserDetailsFfbFavouriteTeam relation FfbTeam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamRelatedByUserDetailsFfbFavouriteTeamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinFfbTeamRelatedByUserDetailsFfbFavouriteTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeamRelatedByUserDetailsFfbFavouriteTeam', 'FfbTeamQuery');
	}

	/**
	 * Filter the query by a related FfbTeam object
	 *
	 * @param     FfbTeam $ffbTeam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByFfbTeamRelatedByUserDetailsFfbOwnTeam($ffbTeam, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_TEAM, $ffbTeam->getTeamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbTeamRelatedByUserDetailsFfbOwnTeam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function joinFfbTeamRelatedByUserDetailsFfbOwnTeam($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbTeamRelatedByUserDetailsFfbOwnTeam');
		
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
			$this->addJoinObject($join, 'FfbTeamRelatedByUserDetailsFfbOwnTeam');
		}
		
		return $this;
	}

	/**
	 * Use the FfbTeamRelatedByUserDetailsFfbOwnTeam relation FfbTeam object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbTeamQuery A secondary query class using the current class as primary query
	 */
	public function useFfbTeamRelatedByUserDetailsFfbOwnTeamQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinFfbTeamRelatedByUserDetailsFfbOwnTeam($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbTeamRelatedByUserDetailsFfbOwnTeam', 'FfbTeamQuery');
	}

	/**
	 * Filter the query by a related FfbPlayer object
	 *
	 * @param     FfbPlayer $ffbPlayer  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayer($ffbPlayer, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_OWN_PLAYER, $ffbPlayer->getPlayerId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayer relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function joinFfbPlayer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
	public function useFfbPlayerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinFfbPlayer($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbPlayer', 'FfbPlayerQuery');
	}

	/**
	 * Filter the query by a related FfbGame object
	 *
	 * @param     FfbGame $ffbGame  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function filterByFfbGame($ffbGame, $comparison = null)
	{
		return $this
			->addUsingAlias(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $ffbGame->getGameId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbGame relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function joinFfbGame($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbGame');
		
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
			$this->addJoinObject($join, 'FfbGame');
		}
		
		return $this;
	}

	/**
	 * Use the FfbGame relation FfbGame object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbGameQuery A secondary query class using the current class as primary query
	 */
	public function useFfbGameQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbGame($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbGame', 'FfbGameQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     WebUserDetails $webUserDetails Object to remove from the list of results
	 *
	 * @return    WebUserDetailsQuery The current query, for fluid interface
	 */
	public function prune($webUserDetails = null)
	{
		if ($webUserDetails) {
			$this->addUsingAlias(WebUserDetailsPeer::USER_ID, $webUserDetails->getUserId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseWebUserDetailsQuery
