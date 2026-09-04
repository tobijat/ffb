<?php


/**
 * Base class that represents a query for the 'ffb_playerstats' table.
 *
 * 
 *
 * @method     FfbPlayerstatsQuery orderByPlayerstatsId($order = Criteria::ASC) Order by the playerstats_id column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPlayerteamId($order = Criteria::ASC) Order by the playerstats_playerteam_id column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsMatchId($order = Criteria::ASC) Order by the playerstats_match_id column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsMatchroundId($order = Criteria::ASC) Order by the playerstats_matchround_id column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsGoals($order = Criteria::ASC) Order by the playerstats_goals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsAssists($order = Criteria::ASC) Order by the playerstats_assists column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsMinutes($order = Criteria::ASC) Order by the playerstats_minutes column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsMinuteIn($order = Criteria::ASC) Order by the playerstats_minute_in column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsMinuteOut($order = Criteria::ASC) Order by the playerstats_minute_out column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsCards($order = Criteria::ASC) Order by the playerstats_cards column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsOwngoals($order = Criteria::ASC) Order by the playerstats_owngoals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPenaltieslost($order = Criteria::ASC) Order by the playerstats_penaltieslost column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPenaltiessaved($order = Criteria::ASC) Order by the playerstats_penaltiessaved column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPenaltyshootoutSave($order = Criteria::ASC) Order by the playerstats_penaltyshootout_save column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPenaltyshootoutLost($order = Criteria::ASC) Order by the playerstats_penaltyshootout_lost column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsPenaltyshootoutHit($order = Criteria::ASC) Order by the playerstats_penaltyshootout_hit column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreGoals($order = Criteria::ASC) Order by the playerstats_score_goals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreAssists($order = Criteria::ASC) Order by the playerstats_score_assists column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreMinutes($order = Criteria::ASC) Order by the playerstats_score_minutes column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreCards($order = Criteria::ASC) Order by the playerstats_score_cards column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreOwngoals($order = Criteria::ASC) Order by the playerstats_score_owngoals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScorePenaltieslost($order = Criteria::ASC) Order by the playerstats_score_penaltieslost column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScorePenaltiessaved($order = Criteria::ASC) Order by the playerstats_score_penaltiessaved column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreOppgoals($order = Criteria::ASC) Order by the playerstats_score_oppgoals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreNooppgoals($order = Criteria::ASC) Order by the playerstats_score_nooppgoals column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreHighLoss($order = Criteria::ASC) Order by the playerstats_score_high_loss column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScoreHighWin($order = Criteria::ASC) Order by the playerstats_score_high_win column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScorePenaltyshootoutSave($order = Criteria::ASC) Order by the playerstats_score_penaltyshootout_save column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScorePenaltyshootoutLost($order = Criteria::ASC) Order by the playerstats_score_penaltyshootout_lost column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScorePenaltyshootoutHit($order = Criteria::ASC) Order by the playerstats_score_penaltyshootout_hit column
 * @method     FfbPlayerstatsQuery orderByPlayerstatsScore($order = Criteria::ASC) Order by the playerstats_score column
 *
 * @method     FfbPlayerstatsQuery groupByPlayerstatsId() Group by the playerstats_id column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPlayerteamId() Group by the playerstats_playerteam_id column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsMatchId() Group by the playerstats_match_id column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsMatchroundId() Group by the playerstats_matchround_id column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsGoals() Group by the playerstats_goals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsAssists() Group by the playerstats_assists column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsMinutes() Group by the playerstats_minutes column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsMinuteIn() Group by the playerstats_minute_in column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsMinuteOut() Group by the playerstats_minute_out column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsCards() Group by the playerstats_cards column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsOwngoals() Group by the playerstats_owngoals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPenaltieslost() Group by the playerstats_penaltieslost column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPenaltiessaved() Group by the playerstats_penaltiessaved column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPenaltyshootoutSave() Group by the playerstats_penaltyshootout_save column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPenaltyshootoutLost() Group by the playerstats_penaltyshootout_lost column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsPenaltyshootoutHit() Group by the playerstats_penaltyshootout_hit column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreGoals() Group by the playerstats_score_goals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreAssists() Group by the playerstats_score_assists column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreMinutes() Group by the playerstats_score_minutes column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreCards() Group by the playerstats_score_cards column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreOwngoals() Group by the playerstats_score_owngoals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScorePenaltieslost() Group by the playerstats_score_penaltieslost column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScorePenaltiessaved() Group by the playerstats_score_penaltiessaved column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreOppgoals() Group by the playerstats_score_oppgoals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreNooppgoals() Group by the playerstats_score_nooppgoals column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreHighLoss() Group by the playerstats_score_high_loss column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScoreHighWin() Group by the playerstats_score_high_win column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScorePenaltyshootoutSave() Group by the playerstats_score_penaltyshootout_save column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScorePenaltyshootoutLost() Group by the playerstats_score_penaltyshootout_lost column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScorePenaltyshootoutHit() Group by the playerstats_score_penaltyshootout_hit column
 * @method     FfbPlayerstatsQuery groupByPlayerstatsScore() Group by the playerstats_score column
 *
 * @method     FfbPlayerstatsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     FfbPlayerstatsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     FfbPlayerstatsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     FfbPlayerstatsQuery leftJoinFfbPlayerteam($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerstatsQuery rightJoinFfbPlayerteam($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbPlayerteam relation
 * @method     FfbPlayerstatsQuery innerJoinFfbPlayerteam($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbPlayerteam relation
 *
 * @method     FfbPlayerstatsQuery leftJoinFfbMatch($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatch relation
 * @method     FfbPlayerstatsQuery rightJoinFfbMatch($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatch relation
 * @method     FfbPlayerstatsQuery innerJoinFfbMatch($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatch relation
 *
 * @method     FfbPlayerstatsQuery leftJoinFfbMatchround($relationAlias = null) Adds a LEFT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbPlayerstatsQuery rightJoinFfbMatchround($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FfbMatchround relation
 * @method     FfbPlayerstatsQuery innerJoinFfbMatchround($relationAlias = null) Adds a INNER JOIN clause to the query using the FfbMatchround relation
 *
 * @method     FfbPlayerstats findOne(?PropelPDO $con = null) Return the first FfbPlayerstats matching the query
 * @method     FfbPlayerstats findOneOrCreate(?PropelPDO $con = null) Return the first FfbPlayerstats matching the query, or a new FfbPlayerstats object populated from the query conditions when no match is found
 *
 * @method     FfbPlayerstats findOneByPlayerstatsId(int $playerstats_id) Return the first FfbPlayerstats filtered by the playerstats_id column
 * @method     FfbPlayerstats findOneByPlayerstatsPlayerteamId(int $playerstats_playerteam_id) Return the first FfbPlayerstats filtered by the playerstats_playerteam_id column
 * @method     FfbPlayerstats findOneByPlayerstatsMatchId(int $playerstats_match_id) Return the first FfbPlayerstats filtered by the playerstats_match_id column
 * @method     FfbPlayerstats findOneByPlayerstatsMatchroundId(int $playerstats_matchround_id) Return the first FfbPlayerstats filtered by the playerstats_matchround_id column
 * @method     FfbPlayerstats findOneByPlayerstatsGoals(int $playerstats_goals) Return the first FfbPlayerstats filtered by the playerstats_goals column
 * @method     FfbPlayerstats findOneByPlayerstatsAssists(int $playerstats_assists) Return the first FfbPlayerstats filtered by the playerstats_assists column
 * @method     FfbPlayerstats findOneByPlayerstatsMinutes(int $playerstats_minutes) Return the first FfbPlayerstats filtered by the playerstats_minutes column
 * @method     FfbPlayerstats findOneByPlayerstatsMinuteIn(int $playerstats_minute_in) Return the first FfbPlayerstats filtered by the playerstats_minute_in column
 * @method     FfbPlayerstats findOneByPlayerstatsMinuteOut(int $playerstats_minute_out) Return the first FfbPlayerstats filtered by the playerstats_minute_out column
 * @method     FfbPlayerstats findOneByPlayerstatsCards(string $playerstats_cards) Return the first FfbPlayerstats filtered by the playerstats_cards column
 * @method     FfbPlayerstats findOneByPlayerstatsOwngoals(int $playerstats_owngoals) Return the first FfbPlayerstats filtered by the playerstats_owngoals column
 * @method     FfbPlayerstats findOneByPlayerstatsPenaltieslost(int $playerstats_penaltieslost) Return the first FfbPlayerstats filtered by the playerstats_penaltieslost column
 * @method     FfbPlayerstats findOneByPlayerstatsPenaltiessaved(int $playerstats_penaltiessaved) Return the first FfbPlayerstats filtered by the playerstats_penaltiessaved column
 * @method     FfbPlayerstats findOneByPlayerstatsPenaltyshootoutSave(int $playerstats_penaltyshootout_save) Return the first FfbPlayerstats filtered by the playerstats_penaltyshootout_save column
 * @method     FfbPlayerstats findOneByPlayerstatsPenaltyshootoutLost(int $playerstats_penaltyshootout_lost) Return the first FfbPlayerstats filtered by the playerstats_penaltyshootout_lost column
 * @method     FfbPlayerstats findOneByPlayerstatsPenaltyshootoutHit(int $playerstats_penaltyshootout_hit) Return the first FfbPlayerstats filtered by the playerstats_penaltyshootout_hit column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreGoals(int $playerstats_score_goals) Return the first FfbPlayerstats filtered by the playerstats_score_goals column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreAssists(int $playerstats_score_assists) Return the first FfbPlayerstats filtered by the playerstats_score_assists column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreMinutes(int $playerstats_score_minutes) Return the first FfbPlayerstats filtered by the playerstats_score_minutes column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreCards(int $playerstats_score_cards) Return the first FfbPlayerstats filtered by the playerstats_score_cards column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreOwngoals(int $playerstats_score_owngoals) Return the first FfbPlayerstats filtered by the playerstats_score_owngoals column
 * @method     FfbPlayerstats findOneByPlayerstatsScorePenaltieslost(int $playerstats_score_penaltieslost) Return the first FfbPlayerstats filtered by the playerstats_score_penaltieslost column
 * @method     FfbPlayerstats findOneByPlayerstatsScorePenaltiessaved(int $playerstats_score_penaltiessaved) Return the first FfbPlayerstats filtered by the playerstats_score_penaltiessaved column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreOppgoals(int $playerstats_score_oppgoals) Return the first FfbPlayerstats filtered by the playerstats_score_oppgoals column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreNooppgoals(int $playerstats_score_nooppgoals) Return the first FfbPlayerstats filtered by the playerstats_score_nooppgoals column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreHighLoss(int $playerstats_score_high_loss) Return the first FfbPlayerstats filtered by the playerstats_score_high_loss column
 * @method     FfbPlayerstats findOneByPlayerstatsScoreHighWin(int $playerstats_score_high_win) Return the first FfbPlayerstats filtered by the playerstats_score_high_win column
 * @method     FfbPlayerstats findOneByPlayerstatsScorePenaltyshootoutSave(int $playerstats_score_penaltyshootout_save) Return the first FfbPlayerstats filtered by the playerstats_score_penaltyshootout_save column
 * @method     FfbPlayerstats findOneByPlayerstatsScorePenaltyshootoutLost(int $playerstats_score_penaltyshootout_lost) Return the first FfbPlayerstats filtered by the playerstats_score_penaltyshootout_lost column
 * @method     FfbPlayerstats findOneByPlayerstatsScorePenaltyshootoutHit(int $playerstats_score_penaltyshootout_hit) Return the first FfbPlayerstats filtered by the playerstats_score_penaltyshootout_hit column
 * @method     FfbPlayerstats findOneByPlayerstatsScore(int $playerstats_score) Return the first FfbPlayerstats filtered by the playerstats_score column
 *
 * @method     array findByPlayerstatsId(int $playerstats_id) Return FfbPlayerstats objects filtered by the playerstats_id column
 * @method     array findByPlayerstatsPlayerteamId(int $playerstats_playerteam_id) Return FfbPlayerstats objects filtered by the playerstats_playerteam_id column
 * @method     array findByPlayerstatsMatchId(int $playerstats_match_id) Return FfbPlayerstats objects filtered by the playerstats_match_id column
 * @method     array findByPlayerstatsMatchroundId(int $playerstats_matchround_id) Return FfbPlayerstats objects filtered by the playerstats_matchround_id column
 * @method     array findByPlayerstatsGoals(int $playerstats_goals) Return FfbPlayerstats objects filtered by the playerstats_goals column
 * @method     array findByPlayerstatsAssists(int $playerstats_assists) Return FfbPlayerstats objects filtered by the playerstats_assists column
 * @method     array findByPlayerstatsMinutes(int $playerstats_minutes) Return FfbPlayerstats objects filtered by the playerstats_minutes column
 * @method     array findByPlayerstatsMinuteIn(int $playerstats_minute_in) Return FfbPlayerstats objects filtered by the playerstats_minute_in column
 * @method     array findByPlayerstatsMinuteOut(int $playerstats_minute_out) Return FfbPlayerstats objects filtered by the playerstats_minute_out column
 * @method     array findByPlayerstatsCards(string $playerstats_cards) Return FfbPlayerstats objects filtered by the playerstats_cards column
 * @method     array findByPlayerstatsOwngoals(int $playerstats_owngoals) Return FfbPlayerstats objects filtered by the playerstats_owngoals column
 * @method     array findByPlayerstatsPenaltieslost(int $playerstats_penaltieslost) Return FfbPlayerstats objects filtered by the playerstats_penaltieslost column
 * @method     array findByPlayerstatsPenaltiessaved(int $playerstats_penaltiessaved) Return FfbPlayerstats objects filtered by the playerstats_penaltiessaved column
 * @method     array findByPlayerstatsPenaltyshootoutSave(int $playerstats_penaltyshootout_save) Return FfbPlayerstats objects filtered by the playerstats_penaltyshootout_save column
 * @method     array findByPlayerstatsPenaltyshootoutLost(int $playerstats_penaltyshootout_lost) Return FfbPlayerstats objects filtered by the playerstats_penaltyshootout_lost column
 * @method     array findByPlayerstatsPenaltyshootoutHit(int $playerstats_penaltyshootout_hit) Return FfbPlayerstats objects filtered by the playerstats_penaltyshootout_hit column
 * @method     array findByPlayerstatsScoreGoals(int $playerstats_score_goals) Return FfbPlayerstats objects filtered by the playerstats_score_goals column
 * @method     array findByPlayerstatsScoreAssists(int $playerstats_score_assists) Return FfbPlayerstats objects filtered by the playerstats_score_assists column
 * @method     array findByPlayerstatsScoreMinutes(int $playerstats_score_minutes) Return FfbPlayerstats objects filtered by the playerstats_score_minutes column
 * @method     array findByPlayerstatsScoreCards(int $playerstats_score_cards) Return FfbPlayerstats objects filtered by the playerstats_score_cards column
 * @method     array findByPlayerstatsScoreOwngoals(int $playerstats_score_owngoals) Return FfbPlayerstats objects filtered by the playerstats_score_owngoals column
 * @method     array findByPlayerstatsScorePenaltieslost(int $playerstats_score_penaltieslost) Return FfbPlayerstats objects filtered by the playerstats_score_penaltieslost column
 * @method     array findByPlayerstatsScorePenaltiessaved(int $playerstats_score_penaltiessaved) Return FfbPlayerstats objects filtered by the playerstats_score_penaltiessaved column
 * @method     array findByPlayerstatsScoreOppgoals(int $playerstats_score_oppgoals) Return FfbPlayerstats objects filtered by the playerstats_score_oppgoals column
 * @method     array findByPlayerstatsScoreNooppgoals(int $playerstats_score_nooppgoals) Return FfbPlayerstats objects filtered by the playerstats_score_nooppgoals column
 * @method     array findByPlayerstatsScoreHighLoss(int $playerstats_score_high_loss) Return FfbPlayerstats objects filtered by the playerstats_score_high_loss column
 * @method     array findByPlayerstatsScoreHighWin(int $playerstats_score_high_win) Return FfbPlayerstats objects filtered by the playerstats_score_high_win column
 * @method     array findByPlayerstatsScorePenaltyshootoutSave(int $playerstats_score_penaltyshootout_save) Return FfbPlayerstats objects filtered by the playerstats_score_penaltyshootout_save column
 * @method     array findByPlayerstatsScorePenaltyshootoutLost(int $playerstats_score_penaltyshootout_lost) Return FfbPlayerstats objects filtered by the playerstats_score_penaltyshootout_lost column
 * @method     array findByPlayerstatsScorePenaltyshootoutHit(int $playerstats_score_penaltyshootout_hit) Return FfbPlayerstats objects filtered by the playerstats_score_penaltyshootout_hit column
 * @method     array findByPlayerstatsScore(int $playerstats_score) Return FfbPlayerstats objects filtered by the playerstats_score column
 *
 * @package    propel.generator.ffb.om
 */
abstract class BaseFfbPlayerstatsQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseFfbPlayerstatsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'd00817fb', $modelName = 'FfbPlayerstats', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new FfbPlayerstatsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    FfbPlayerstatsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof FfbPlayerstatsQuery) {
			return $criteria;
		}
		$query = new FfbPlayerstatsQuery();
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
	 * @return    FfbPlayerstats|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = FfbPlayerstatsPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the playerstats_id column
	 * 
	 * @param     int|array $playerstatsId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsId($playerstatsId = null, $comparison = null)
	{
		if (is_array($playerstatsId) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ID, $playerstatsId, $comparison);
	}

	/**
	 * Filter the query on the playerstats_playerteam_id column
	 * 
	 * @param     int|array $playerstatsPlayerteamId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPlayerteamId($playerstatsPlayerteamId = null, $comparison = null)
	{
		if (is_array($playerstatsPlayerteamId)) {
			$useMinMax = false;
			if (isset($playerstatsPlayerteamId['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerstatsPlayerteamId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPlayerteamId['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerstatsPlayerteamId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerstatsPlayerteamId, $comparison);
	}

	/**
	 * Filter the query on the playerstats_match_id column
	 * 
	 * @param     int|array $playerstatsMatchId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsMatchId($playerstatsMatchId = null, $comparison = null)
	{
		if (is_array($playerstatsMatchId)) {
			$useMinMax = false;
			if (isset($playerstatsMatchId['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $playerstatsMatchId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsMatchId['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $playerstatsMatchId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $playerstatsMatchId, $comparison);
	}

	/**
	 * Filter the query on the playerstats_matchround_id column
	 * 
	 * @param     int|array $playerstatsMatchroundId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsMatchroundId($playerstatsMatchroundId = null, $comparison = null)
	{
		if (is_array($playerstatsMatchroundId)) {
			$useMinMax = false;
			if (isset($playerstatsMatchroundId['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $playerstatsMatchroundId['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsMatchroundId['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $playerstatsMatchroundId['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $playerstatsMatchroundId, $comparison);
	}

	/**
	 * Filter the query on the playerstats_goals column
	 * 
	 * @param     int|array $playerstatsGoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsGoals($playerstatsGoals = null, $comparison = null)
	{
		if (is_array($playerstatsGoals)) {
			$useMinMax = false;
			if (isset($playerstatsGoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, $playerstatsGoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsGoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, $playerstatsGoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, $playerstatsGoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_assists column
	 * 
	 * @param     int|array $playerstatsAssists The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsAssists($playerstatsAssists = null, $comparison = null)
	{
		if (is_array($playerstatsAssists)) {
			$useMinMax = false;
			if (isset($playerstatsAssists['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS, $playerstatsAssists['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsAssists['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS, $playerstatsAssists['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS, $playerstatsAssists, $comparison);
	}

	/**
	 * Filter the query on the playerstats_minutes column
	 * 
	 * @param     int|array $playerstatsMinutes The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsMinutes($playerstatsMinutes = null, $comparison = null)
	{
		if (is_array($playerstatsMinutes)) {
			$useMinMax = false;
			if (isset($playerstatsMinutes['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES, $playerstatsMinutes['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsMinutes['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES, $playerstatsMinutes['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES, $playerstatsMinutes, $comparison);
	}

	/**
	 * Filter the query on the playerstats_minute_in column
	 * 
	 * @param     int|array $playerstatsMinuteIn The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsMinuteIn($playerstatsMinuteIn = null, $comparison = null)
	{
		if (is_array($playerstatsMinuteIn)) {
			$useMinMax = false;
			if (isset($playerstatsMinuteIn['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN, $playerstatsMinuteIn['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsMinuteIn['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN, $playerstatsMinuteIn['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN, $playerstatsMinuteIn, $comparison);
	}

	/**
	 * Filter the query on the playerstats_minute_out column
	 * 
	 * @param     int|array $playerstatsMinuteOut The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsMinuteOut($playerstatsMinuteOut = null, $comparison = null)
	{
		if (is_array($playerstatsMinuteOut)) {
			$useMinMax = false;
			if (isset($playerstatsMinuteOut['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT, $playerstatsMinuteOut['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsMinuteOut['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT, $playerstatsMinuteOut['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT, $playerstatsMinuteOut, $comparison);
	}

	/**
	 * Filter the query on the playerstats_cards column
	 * 
	 * @param     string $playerstatsCards The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsCards($playerstatsCards = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($playerstatsCards)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $playerstatsCards)) {
				$playerstatsCards = str_replace('*', '%', $playerstatsCards);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_CARDS, $playerstatsCards, $comparison);
	}

	/**
	 * Filter the query on the playerstats_owngoals column
	 * 
	 * @param     int|array $playerstatsOwngoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsOwngoals($playerstatsOwngoals = null, $comparison = null)
	{
		if (is_array($playerstatsOwngoals)) {
			$useMinMax = false;
			if (isset($playerstatsOwngoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS, $playerstatsOwngoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsOwngoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS, $playerstatsOwngoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS, $playerstatsOwngoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_penaltieslost column
	 * 
	 * @param     int|array $playerstatsPenaltieslost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPenaltieslost($playerstatsPenaltieslost = null, $comparison = null)
	{
		if (is_array($playerstatsPenaltieslost)) {
			$useMinMax = false;
			if (isset($playerstatsPenaltieslost['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST, $playerstatsPenaltieslost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPenaltieslost['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST, $playerstatsPenaltieslost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST, $playerstatsPenaltieslost, $comparison);
	}

	/**
	 * Filter the query on the playerstats_penaltiessaved column
	 * 
	 * @param     int|array $playerstatsPenaltiessaved The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPenaltiessaved($playerstatsPenaltiessaved = null, $comparison = null)
	{
		if (is_array($playerstatsPenaltiessaved)) {
			$useMinMax = false;
			if (isset($playerstatsPenaltiessaved['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED, $playerstatsPenaltiessaved['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPenaltiessaved['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED, $playerstatsPenaltiessaved['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED, $playerstatsPenaltiessaved, $comparison);
	}

	/**
	 * Filter the query on the playerstats_penaltyshootout_save column
	 * 
	 * @param     int|array $playerstatsPenaltyshootoutSave The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPenaltyshootoutSave($playerstatsPenaltyshootoutSave = null, $comparison = null)
	{
		if (is_array($playerstatsPenaltyshootoutSave)) {
			$useMinMax = false;
			if (isset($playerstatsPenaltyshootoutSave['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE, $playerstatsPenaltyshootoutSave['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPenaltyshootoutSave['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE, $playerstatsPenaltyshootoutSave['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE, $playerstatsPenaltyshootoutSave, $comparison);
	}

	/**
	 * Filter the query on the playerstats_penaltyshootout_lost column
	 * 
	 * @param     int|array $playerstatsPenaltyshootoutLost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPenaltyshootoutLost($playerstatsPenaltyshootoutLost = null, $comparison = null)
	{
		if (is_array($playerstatsPenaltyshootoutLost)) {
			$useMinMax = false;
			if (isset($playerstatsPenaltyshootoutLost['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST, $playerstatsPenaltyshootoutLost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPenaltyshootoutLost['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST, $playerstatsPenaltyshootoutLost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST, $playerstatsPenaltyshootoutLost, $comparison);
	}

	/**
	 * Filter the query on the playerstats_penaltyshootout_hit column
	 * 
	 * @param     int|array $playerstatsPenaltyshootoutHit The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsPenaltyshootoutHit($playerstatsPenaltyshootoutHit = null, $comparison = null)
	{
		if (is_array($playerstatsPenaltyshootoutHit)) {
			$useMinMax = false;
			if (isset($playerstatsPenaltyshootoutHit['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT, $playerstatsPenaltyshootoutHit['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsPenaltyshootoutHit['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT, $playerstatsPenaltyshootoutHit['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT, $playerstatsPenaltyshootoutHit, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_goals column
	 * 
	 * @param     int|array $playerstatsScoreGoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreGoals($playerstatsScoreGoals = null, $comparison = null)
	{
		if (is_array($playerstatsScoreGoals)) {
			$useMinMax = false;
			if (isset($playerstatsScoreGoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS, $playerstatsScoreGoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreGoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS, $playerstatsScoreGoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS, $playerstatsScoreGoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_assists column
	 * 
	 * @param     int|array $playerstatsScoreAssists The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreAssists($playerstatsScoreAssists = null, $comparison = null)
	{
		if (is_array($playerstatsScoreAssists)) {
			$useMinMax = false;
			if (isset($playerstatsScoreAssists['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS, $playerstatsScoreAssists['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreAssists['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS, $playerstatsScoreAssists['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS, $playerstatsScoreAssists, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_minutes column
	 * 
	 * @param     int|array $playerstatsScoreMinutes The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreMinutes($playerstatsScoreMinutes = null, $comparison = null)
	{
		if (is_array($playerstatsScoreMinutes)) {
			$useMinMax = false;
			if (isset($playerstatsScoreMinutes['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES, $playerstatsScoreMinutes['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreMinutes['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES, $playerstatsScoreMinutes['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES, $playerstatsScoreMinutes, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_cards column
	 * 
	 * @param     int|array $playerstatsScoreCards The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreCards($playerstatsScoreCards = null, $comparison = null)
	{
		if (is_array($playerstatsScoreCards)) {
			$useMinMax = false;
			if (isset($playerstatsScoreCards['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS, $playerstatsScoreCards['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreCards['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS, $playerstatsScoreCards['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS, $playerstatsScoreCards, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_owngoals column
	 * 
	 * @param     int|array $playerstatsScoreOwngoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreOwngoals($playerstatsScoreOwngoals = null, $comparison = null)
	{
		if (is_array($playerstatsScoreOwngoals)) {
			$useMinMax = false;
			if (isset($playerstatsScoreOwngoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS, $playerstatsScoreOwngoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreOwngoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS, $playerstatsScoreOwngoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS, $playerstatsScoreOwngoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_penaltieslost column
	 * 
	 * @param     int|array $playerstatsScorePenaltieslost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScorePenaltieslost($playerstatsScorePenaltieslost = null, $comparison = null)
	{
		if (is_array($playerstatsScorePenaltieslost)) {
			$useMinMax = false;
			if (isset($playerstatsScorePenaltieslost['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST, $playerstatsScorePenaltieslost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScorePenaltieslost['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST, $playerstatsScorePenaltieslost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST, $playerstatsScorePenaltieslost, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_penaltiessaved column
	 * 
	 * @param     int|array $playerstatsScorePenaltiessaved The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScorePenaltiessaved($playerstatsScorePenaltiessaved = null, $comparison = null)
	{
		if (is_array($playerstatsScorePenaltiessaved)) {
			$useMinMax = false;
			if (isset($playerstatsScorePenaltiessaved['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED, $playerstatsScorePenaltiessaved['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScorePenaltiessaved['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED, $playerstatsScorePenaltiessaved['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED, $playerstatsScorePenaltiessaved, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_oppgoals column
	 * 
	 * @param     int|array $playerstatsScoreOppgoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreOppgoals($playerstatsScoreOppgoals = null, $comparison = null)
	{
		if (is_array($playerstatsScoreOppgoals)) {
			$useMinMax = false;
			if (isset($playerstatsScoreOppgoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS, $playerstatsScoreOppgoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreOppgoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS, $playerstatsScoreOppgoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS, $playerstatsScoreOppgoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_nooppgoals column
	 * 
	 * @param     int|array $playerstatsScoreNooppgoals The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreNooppgoals($playerstatsScoreNooppgoals = null, $comparison = null)
	{
		if (is_array($playerstatsScoreNooppgoals)) {
			$useMinMax = false;
			if (isset($playerstatsScoreNooppgoals['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS, $playerstatsScoreNooppgoals['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreNooppgoals['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS, $playerstatsScoreNooppgoals['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS, $playerstatsScoreNooppgoals, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_high_loss column
	 * 
	 * @param     int|array $playerstatsScoreHighLoss The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreHighLoss($playerstatsScoreHighLoss = null, $comparison = null)
	{
		if (is_array($playerstatsScoreHighLoss)) {
			$useMinMax = false;
			if (isset($playerstatsScoreHighLoss['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS, $playerstatsScoreHighLoss['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreHighLoss['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS, $playerstatsScoreHighLoss['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS, $playerstatsScoreHighLoss, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_high_win column
	 * 
	 * @param     int|array $playerstatsScoreHighWin The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScoreHighWin($playerstatsScoreHighWin = null, $comparison = null)
	{
		if (is_array($playerstatsScoreHighWin)) {
			$useMinMax = false;
			if (isset($playerstatsScoreHighWin['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN, $playerstatsScoreHighWin['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScoreHighWin['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN, $playerstatsScoreHighWin['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN, $playerstatsScoreHighWin, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_penaltyshootout_save column
	 * 
	 * @param     int|array $playerstatsScorePenaltyshootoutSave The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScorePenaltyshootoutSave($playerstatsScorePenaltyshootoutSave = null, $comparison = null)
	{
		if (is_array($playerstatsScorePenaltyshootoutSave)) {
			$useMinMax = false;
			if (isset($playerstatsScorePenaltyshootoutSave['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE, $playerstatsScorePenaltyshootoutSave['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScorePenaltyshootoutSave['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE, $playerstatsScorePenaltyshootoutSave['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE, $playerstatsScorePenaltyshootoutSave, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_penaltyshootout_lost column
	 * 
	 * @param     int|array $playerstatsScorePenaltyshootoutLost The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScorePenaltyshootoutLost($playerstatsScorePenaltyshootoutLost = null, $comparison = null)
	{
		if (is_array($playerstatsScorePenaltyshootoutLost)) {
			$useMinMax = false;
			if (isset($playerstatsScorePenaltyshootoutLost['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST, $playerstatsScorePenaltyshootoutLost['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScorePenaltyshootoutLost['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST, $playerstatsScorePenaltyshootoutLost['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST, $playerstatsScorePenaltyshootoutLost, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score_penaltyshootout_hit column
	 * 
	 * @param     int|array $playerstatsScorePenaltyshootoutHit The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScorePenaltyshootoutHit($playerstatsScorePenaltyshootoutHit = null, $comparison = null)
	{
		if (is_array($playerstatsScorePenaltyshootoutHit)) {
			$useMinMax = false;
			if (isset($playerstatsScorePenaltyshootoutHit['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT, $playerstatsScorePenaltyshootoutHit['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScorePenaltyshootoutHit['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT, $playerstatsScorePenaltyshootoutHit['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT, $playerstatsScorePenaltyshootoutHit, $comparison);
	}

	/**
	 * Filter the query on the playerstats_score column
	 * 
	 * @param     int|array $playerstatsScore The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByPlayerstatsScore($playerstatsScore = null, $comparison = null)
	{
		if (is_array($playerstatsScore)) {
			$useMinMax = false;
			if (isset($playerstatsScore['min'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE, $playerstatsScore['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($playerstatsScore['max'])) {
				$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE, $playerstatsScore['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_SCORE, $playerstatsScore, $comparison);
	}

	/**
	 * Filter the query by a related FfbPlayerteam object
	 *
	 * @param     FfbPlayerteam $ffbPlayerteam  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByFfbPlayerteam($ffbPlayerteam, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $ffbPlayerteam->getPlayerteamId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbPlayerteam relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
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
	 * Filter the query by a related FfbMatch object
	 *
	 * @param     FfbMatch $ffbMatch  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByFfbMatch($ffbMatch, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $ffbMatch->getMatchId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatch relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function joinFfbMatch($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('FfbMatch');
		
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
			$this->addJoinObject($join, 'FfbMatch');
		}
		
		return $this;
	}

	/**
	 * Use the FfbMatch relation FfbMatch object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbMatchQuery A secondary query class using the current class as primary query
	 */
	public function useFfbMatchQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinFfbMatch($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'FfbMatch', 'FfbMatchQuery');
	}

	/**
	 * Filter the query by a related FfbMatchround object
	 *
	 * @param     FfbMatchround $ffbMatchround  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function filterByFfbMatchround($ffbMatchround, $comparison = null)
	{
		return $this
			->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $ffbMatchround->getMatchroundId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the FfbMatchround relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
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
	 * @param     FfbPlayerstats $ffbPlayerstats Object to remove from the list of results
	 *
	 * @return    FfbPlayerstatsQuery The current query, for fluid interface
	 */
	public function prune($ffbPlayerstats = null)
	{
		if ($ffbPlayerstats) {
			$this->addUsingAlias(FfbPlayerstatsPeer::PLAYERSTATS_ID, $ffbPlayerstats->getPlayerstatsId(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

} // BaseFfbPlayerstatsQuery
