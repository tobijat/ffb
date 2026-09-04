<?php

/**
 * Base static class for performing query and update operations on the 'ffb_playerstats' table.
 *
 * 
 *
 * @package    ffb.om
 */
abstract class BaseFfbPlayerstatsPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'd00817fb';

	/** the table name for this class */
	const TABLE_NAME = 'ffb_playerstats';

	/** the related Propel class for this table */
	const OM_CLASS = 'FfbPlayerstats';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'ffb.FfbPlayerstats';

	/** the related TableMap class for this table */
	const TM_CLASS = 'FfbPlayerstatsTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 31;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the PLAYERSTATS_ID field */
	const PLAYERSTATS_ID = 'ffb_playerstats.PLAYERSTATS_ID';

	/** the column name for the PLAYERSTATS_PLAYERTEAM_ID field */
	const PLAYERSTATS_PLAYERTEAM_ID = 'ffb_playerstats.PLAYERSTATS_PLAYERTEAM_ID';

	/** the column name for the PLAYERSTATS_MATCH_ID field */
	const PLAYERSTATS_MATCH_ID = 'ffb_playerstats.PLAYERSTATS_MATCH_ID';

	/** the column name for the PLAYERSTATS_MATCHROUND_ID field */
	const PLAYERSTATS_MATCHROUND_ID = 'ffb_playerstats.PLAYERSTATS_MATCHROUND_ID';

	/** the column name for the PLAYERSTATS_GOALS field */
	const PLAYERSTATS_GOALS = 'ffb_playerstats.PLAYERSTATS_GOALS';

	/** the column name for the PLAYERSTATS_ASSISTS field */
	const PLAYERSTATS_ASSISTS = 'ffb_playerstats.PLAYERSTATS_ASSISTS';

	/** the column name for the PLAYERSTATS_MINUTES field */
	const PLAYERSTATS_MINUTES = 'ffb_playerstats.PLAYERSTATS_MINUTES';

	/** the column name for the PLAYERSTATS_MINUTE_IN field */
	const PLAYERSTATS_MINUTE_IN = 'ffb_playerstats.PLAYERSTATS_MINUTE_IN';

	/** the column name for the PLAYERSTATS_MINUTE_OUT field */
	const PLAYERSTATS_MINUTE_OUT = 'ffb_playerstats.PLAYERSTATS_MINUTE_OUT';

	/** the column name for the PLAYERSTATS_CARDS field */
	const PLAYERSTATS_CARDS = 'ffb_playerstats.PLAYERSTATS_CARDS';

	/** the column name for the PLAYERSTATS_OWNGOALS field */
	const PLAYERSTATS_OWNGOALS = 'ffb_playerstats.PLAYERSTATS_OWNGOALS';

	/** the column name for the PLAYERSTATS_PENALTIESLOST field */
	const PLAYERSTATS_PENALTIESLOST = 'ffb_playerstats.PLAYERSTATS_PENALTIESLOST';

	/** the column name for the PLAYERSTATS_PENALTIESSAVED field */
	const PLAYERSTATS_PENALTIESSAVED = 'ffb_playerstats.PLAYERSTATS_PENALTIESSAVED';

	/** the column name for the PLAYERSTATS_PENALTYSHOOTOUT_SAVE field */
	const PLAYERSTATS_PENALTYSHOOTOUT_SAVE = 'ffb_playerstats.PLAYERSTATS_PENALTYSHOOTOUT_SAVE';

	/** the column name for the PLAYERSTATS_PENALTYSHOOTOUT_LOST field */
	const PLAYERSTATS_PENALTYSHOOTOUT_LOST = 'ffb_playerstats.PLAYERSTATS_PENALTYSHOOTOUT_LOST';

	/** the column name for the PLAYERSTATS_PENALTYSHOOTOUT_HIT field */
	const PLAYERSTATS_PENALTYSHOOTOUT_HIT = 'ffb_playerstats.PLAYERSTATS_PENALTYSHOOTOUT_HIT';

	/** the column name for the PLAYERSTATS_SCORE_GOALS field */
	const PLAYERSTATS_SCORE_GOALS = 'ffb_playerstats.PLAYERSTATS_SCORE_GOALS';

	/** the column name for the PLAYERSTATS_SCORE_ASSISTS field */
	const PLAYERSTATS_SCORE_ASSISTS = 'ffb_playerstats.PLAYERSTATS_SCORE_ASSISTS';

	/** the column name for the PLAYERSTATS_SCORE_MINUTES field */
	const PLAYERSTATS_SCORE_MINUTES = 'ffb_playerstats.PLAYERSTATS_SCORE_MINUTES';

	/** the column name for the PLAYERSTATS_SCORE_CARDS field */
	const PLAYERSTATS_SCORE_CARDS = 'ffb_playerstats.PLAYERSTATS_SCORE_CARDS';

	/** the column name for the PLAYERSTATS_SCORE_OWNGOALS field */
	const PLAYERSTATS_SCORE_OWNGOALS = 'ffb_playerstats.PLAYERSTATS_SCORE_OWNGOALS';

	/** the column name for the PLAYERSTATS_SCORE_PENALTIESLOST field */
	const PLAYERSTATS_SCORE_PENALTIESLOST = 'ffb_playerstats.PLAYERSTATS_SCORE_PENALTIESLOST';

	/** the column name for the PLAYERSTATS_SCORE_PENALTIESSAVED field */
	const PLAYERSTATS_SCORE_PENALTIESSAVED = 'ffb_playerstats.PLAYERSTATS_SCORE_PENALTIESSAVED';

	/** the column name for the PLAYERSTATS_SCORE_OPPGOALS field */
	const PLAYERSTATS_SCORE_OPPGOALS = 'ffb_playerstats.PLAYERSTATS_SCORE_OPPGOALS';

	/** the column name for the PLAYERSTATS_SCORE_NOOPPGOALS field */
	const PLAYERSTATS_SCORE_NOOPPGOALS = 'ffb_playerstats.PLAYERSTATS_SCORE_NOOPPGOALS';

	/** the column name for the PLAYERSTATS_SCORE_HIGH_LOSS field */
	const PLAYERSTATS_SCORE_HIGH_LOSS = 'ffb_playerstats.PLAYERSTATS_SCORE_HIGH_LOSS';

	/** the column name for the PLAYERSTATS_SCORE_HIGH_WIN field */
	const PLAYERSTATS_SCORE_HIGH_WIN = 'ffb_playerstats.PLAYERSTATS_SCORE_HIGH_WIN';

	/** the column name for the PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE field */
	const PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE = 'ffb_playerstats.PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE';

	/** the column name for the PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST field */
	const PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST = 'ffb_playerstats.PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST';

	/** the column name for the PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT field */
	const PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT = 'ffb_playerstats.PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT';

	/** the column name for the PLAYERSTATS_SCORE field */
	const PLAYERSTATS_SCORE = 'ffb_playerstats.PLAYERSTATS_SCORE';

	/**
	 * An identiy map to hold any loaded instances of FfbPlayerstats objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array FfbPlayerstats[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('PlayerstatsId', 'PlayerstatsPlayerteamId', 'PlayerstatsMatchId', 'PlayerstatsMatchroundId', 'PlayerstatsGoals', 'PlayerstatsAssists', 'PlayerstatsMinutes', 'PlayerstatsMinuteIn', 'PlayerstatsMinuteOut', 'PlayerstatsCards', 'PlayerstatsOwngoals', 'PlayerstatsPenaltieslost', 'PlayerstatsPenaltiessaved', 'PlayerstatsPenaltyshootoutSave', 'PlayerstatsPenaltyshootoutLost', 'PlayerstatsPenaltyshootoutHit', 'PlayerstatsScoreGoals', 'PlayerstatsScoreAssists', 'PlayerstatsScoreMinutes', 'PlayerstatsScoreCards', 'PlayerstatsScoreOwngoals', 'PlayerstatsScorePenaltieslost', 'PlayerstatsScorePenaltiessaved', 'PlayerstatsScoreOppgoals', 'PlayerstatsScoreNooppgoals', 'PlayerstatsScoreHighLoss', 'PlayerstatsScoreHighWin', 'PlayerstatsScorePenaltyshootoutSave', 'PlayerstatsScorePenaltyshootoutLost', 'PlayerstatsScorePenaltyshootoutHit', 'PlayerstatsScore', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('playerstatsId', 'playerstatsPlayerteamId', 'playerstatsMatchId', 'playerstatsMatchroundId', 'playerstatsGoals', 'playerstatsAssists', 'playerstatsMinutes', 'playerstatsMinuteIn', 'playerstatsMinuteOut', 'playerstatsCards', 'playerstatsOwngoals', 'playerstatsPenaltieslost', 'playerstatsPenaltiessaved', 'playerstatsPenaltyshootoutSave', 'playerstatsPenaltyshootoutLost', 'playerstatsPenaltyshootoutHit', 'playerstatsScoreGoals', 'playerstatsScoreAssists', 'playerstatsScoreMinutes', 'playerstatsScoreCards', 'playerstatsScoreOwngoals', 'playerstatsScorePenaltieslost', 'playerstatsScorePenaltiessaved', 'playerstatsScoreOppgoals', 'playerstatsScoreNooppgoals', 'playerstatsScoreHighLoss', 'playerstatsScoreHighWin', 'playerstatsScorePenaltyshootoutSave', 'playerstatsScorePenaltyshootoutLost', 'playerstatsScorePenaltyshootoutHit', 'playerstatsScore', ),
		BasePeer::TYPE_COLNAME => array (self::PLAYERSTATS_ID, self::PLAYERSTATS_PLAYERTEAM_ID, self::PLAYERSTATS_MATCH_ID, self::PLAYERSTATS_MATCHROUND_ID, self::PLAYERSTATS_GOALS, self::PLAYERSTATS_ASSISTS, self::PLAYERSTATS_MINUTES, self::PLAYERSTATS_MINUTE_IN, self::PLAYERSTATS_MINUTE_OUT, self::PLAYERSTATS_CARDS, self::PLAYERSTATS_OWNGOALS, self::PLAYERSTATS_PENALTIESLOST, self::PLAYERSTATS_PENALTIESSAVED, self::PLAYERSTATS_PENALTYSHOOTOUT_SAVE, self::PLAYERSTATS_PENALTYSHOOTOUT_LOST, self::PLAYERSTATS_PENALTYSHOOTOUT_HIT, self::PLAYERSTATS_SCORE_GOALS, self::PLAYERSTATS_SCORE_ASSISTS, self::PLAYERSTATS_SCORE_MINUTES, self::PLAYERSTATS_SCORE_CARDS, self::PLAYERSTATS_SCORE_OWNGOALS, self::PLAYERSTATS_SCORE_PENALTIESLOST, self::PLAYERSTATS_SCORE_PENALTIESSAVED, self::PLAYERSTATS_SCORE_OPPGOALS, self::PLAYERSTATS_SCORE_NOOPPGOALS, self::PLAYERSTATS_SCORE_HIGH_LOSS, self::PLAYERSTATS_SCORE_HIGH_WIN, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT, self::PLAYERSTATS_SCORE, ),
		BasePeer::TYPE_FIELDNAME => array ('playerstats_id', 'playerstats_playerteam_id', 'playerstats_match_id', 'playerstats_matchround_id', 'playerstats_goals', 'playerstats_assists', 'playerstats_minutes', 'playerstats_minute_in', 'playerstats_minute_out', 'playerstats_cards', 'playerstats_owngoals', 'playerstats_penaltieslost', 'playerstats_penaltiessaved', 'playerstats_penaltyshootout_save', 'playerstats_penaltyshootout_lost', 'playerstats_penaltyshootout_hit', 'playerstats_score_goals', 'playerstats_score_assists', 'playerstats_score_minutes', 'playerstats_score_cards', 'playerstats_score_owngoals', 'playerstats_score_penaltieslost', 'playerstats_score_penaltiessaved', 'playerstats_score_oppgoals', 'playerstats_score_nooppgoals', 'playerstats_score_high_loss', 'playerstats_score_high_win', 'playerstats_score_penaltyshootout_save', 'playerstats_score_penaltyshootout_lost', 'playerstats_score_penaltyshootout_hit', 'playerstats_score', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('PlayerstatsId' => 0, 'PlayerstatsPlayerteamId' => 1, 'PlayerstatsMatchId' => 2, 'PlayerstatsMatchroundId' => 3, 'PlayerstatsGoals' => 4, 'PlayerstatsAssists' => 5, 'PlayerstatsMinutes' => 6, 'PlayerstatsMinuteIn' => 7, 'PlayerstatsMinuteOut' => 8, 'PlayerstatsCards' => 9, 'PlayerstatsOwngoals' => 10, 'PlayerstatsPenaltieslost' => 11, 'PlayerstatsPenaltiessaved' => 12, 'PlayerstatsPenaltyshootoutSave' => 13, 'PlayerstatsPenaltyshootoutLost' => 14, 'PlayerstatsPenaltyshootoutHit' => 15, 'PlayerstatsScoreGoals' => 16, 'PlayerstatsScoreAssists' => 17, 'PlayerstatsScoreMinutes' => 18, 'PlayerstatsScoreCards' => 19, 'PlayerstatsScoreOwngoals' => 20, 'PlayerstatsScorePenaltieslost' => 21, 'PlayerstatsScorePenaltiessaved' => 22, 'PlayerstatsScoreOppgoals' => 23, 'PlayerstatsScoreNooppgoals' => 24, 'PlayerstatsScoreHighLoss' => 25, 'PlayerstatsScoreHighWin' => 26, 'PlayerstatsScorePenaltyshootoutSave' => 27, 'PlayerstatsScorePenaltyshootoutLost' => 28, 'PlayerstatsScorePenaltyshootoutHit' => 29, 'PlayerstatsScore' => 30, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('playerstatsId' => 0, 'playerstatsPlayerteamId' => 1, 'playerstatsMatchId' => 2, 'playerstatsMatchroundId' => 3, 'playerstatsGoals' => 4, 'playerstatsAssists' => 5, 'playerstatsMinutes' => 6, 'playerstatsMinuteIn' => 7, 'playerstatsMinuteOut' => 8, 'playerstatsCards' => 9, 'playerstatsOwngoals' => 10, 'playerstatsPenaltieslost' => 11, 'playerstatsPenaltiessaved' => 12, 'playerstatsPenaltyshootoutSave' => 13, 'playerstatsPenaltyshootoutLost' => 14, 'playerstatsPenaltyshootoutHit' => 15, 'playerstatsScoreGoals' => 16, 'playerstatsScoreAssists' => 17, 'playerstatsScoreMinutes' => 18, 'playerstatsScoreCards' => 19, 'playerstatsScoreOwngoals' => 20, 'playerstatsScorePenaltieslost' => 21, 'playerstatsScorePenaltiessaved' => 22, 'playerstatsScoreOppgoals' => 23, 'playerstatsScoreNooppgoals' => 24, 'playerstatsScoreHighLoss' => 25, 'playerstatsScoreHighWin' => 26, 'playerstatsScorePenaltyshootoutSave' => 27, 'playerstatsScorePenaltyshootoutLost' => 28, 'playerstatsScorePenaltyshootoutHit' => 29, 'playerstatsScore' => 30, ),
		BasePeer::TYPE_COLNAME => array (self::PLAYERSTATS_ID => 0, self::PLAYERSTATS_PLAYERTEAM_ID => 1, self::PLAYERSTATS_MATCH_ID => 2, self::PLAYERSTATS_MATCHROUND_ID => 3, self::PLAYERSTATS_GOALS => 4, self::PLAYERSTATS_ASSISTS => 5, self::PLAYERSTATS_MINUTES => 6, self::PLAYERSTATS_MINUTE_IN => 7, self::PLAYERSTATS_MINUTE_OUT => 8, self::PLAYERSTATS_CARDS => 9, self::PLAYERSTATS_OWNGOALS => 10, self::PLAYERSTATS_PENALTIESLOST => 11, self::PLAYERSTATS_PENALTIESSAVED => 12, self::PLAYERSTATS_PENALTYSHOOTOUT_SAVE => 13, self::PLAYERSTATS_PENALTYSHOOTOUT_LOST => 14, self::PLAYERSTATS_PENALTYSHOOTOUT_HIT => 15, self::PLAYERSTATS_SCORE_GOALS => 16, self::PLAYERSTATS_SCORE_ASSISTS => 17, self::PLAYERSTATS_SCORE_MINUTES => 18, self::PLAYERSTATS_SCORE_CARDS => 19, self::PLAYERSTATS_SCORE_OWNGOALS => 20, self::PLAYERSTATS_SCORE_PENALTIESLOST => 21, self::PLAYERSTATS_SCORE_PENALTIESSAVED => 22, self::PLAYERSTATS_SCORE_OPPGOALS => 23, self::PLAYERSTATS_SCORE_NOOPPGOALS => 24, self::PLAYERSTATS_SCORE_HIGH_LOSS => 25, self::PLAYERSTATS_SCORE_HIGH_WIN => 26, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE => 27, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST => 28, self::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT => 29, self::PLAYERSTATS_SCORE => 30, ),
		BasePeer::TYPE_FIELDNAME => array ('playerstats_id' => 0, 'playerstats_playerteam_id' => 1, 'playerstats_match_id' => 2, 'playerstats_matchround_id' => 3, 'playerstats_goals' => 4, 'playerstats_assists' => 5, 'playerstats_minutes' => 6, 'playerstats_minute_in' => 7, 'playerstats_minute_out' => 8, 'playerstats_cards' => 9, 'playerstats_owngoals' => 10, 'playerstats_penaltieslost' => 11, 'playerstats_penaltiessaved' => 12, 'playerstats_penaltyshootout_save' => 13, 'playerstats_penaltyshootout_lost' => 14, 'playerstats_penaltyshootout_hit' => 15, 'playerstats_score_goals' => 16, 'playerstats_score_assists' => 17, 'playerstats_score_minutes' => 18, 'playerstats_score_cards' => 19, 'playerstats_score_owngoals' => 20, 'playerstats_score_penaltieslost' => 21, 'playerstats_score_penaltiessaved' => 22, 'playerstats_score_oppgoals' => 23, 'playerstats_score_nooppgoals' => 24, 'playerstats_score_high_loss' => 25, 'playerstats_score_high_win' => 26, 'playerstats_score_penaltyshootout_save' => 27, 'playerstats_score_penaltyshootout_lost' => 28, 'playerstats_score_penaltyshootout_hit' => 29, 'playerstats_score' => 30, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. FfbPlayerstatsPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(FfbPlayerstatsPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_ID);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_GOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_MINUTES);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_IN);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_MINUTE_OUT);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_CARDS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESLOST);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PENALTIESSAVED);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_SAVE);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_LOST);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_PENALTYSHOOTOUT_HIT);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_GOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_ASSISTS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_MINUTES);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_CARDS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OWNGOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESLOST);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTIESSAVED);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_OPPGOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_NOOPPGOALS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_LOSS);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_HIGH_WIN);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT);
		$criteria->addSelectColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     FfbPlayerstats
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = FfbPlayerstatsPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return FfbPlayerstatsPeer::populateObjects(FfbPlayerstatsPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      FfbPlayerstats $value A FfbPlayerstats object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(FfbPlayerstats $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getPlayerstatsId();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A FfbPlayerstats object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof FfbPlayerstats) {
				$key = (string) $value->getPlayerstatsId();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or FfbPlayerstats object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     FfbPlayerstats Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to ffb_playerstats
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol] === null) {
			return null;
		}
		return (string) $row[$startcol];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = FfbPlayerstatsPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = FfbPlayerstatsPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				FfbPlayerstatsPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbPlayerteam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbMatch table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbMatch(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbMatchround table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinFfbMatchround(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with their FfbPlayerteam objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbPlayerteam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbPlayerteamPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to $obj2 (FfbPlayerteam)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with their FfbMatch objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbMatch(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbMatchPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbMatchPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbMatchPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbMatchPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to $obj2 (FfbMatch)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with their FfbMatchround objects.
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinFfbMatchround(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);
		FfbMatchroundPeer::addSelectColumns($criteria);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {

				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if $obj1 already loaded

			$key2 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol);
			if ($key2 !== null) {
				$obj2 = FfbMatchroundPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbMatchroundPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol);
					FfbMatchroundPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to $obj2 (FfbMatchround)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row was not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining all related tables
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}

	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with all related objects.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol2 = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol5 = $startcol4 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

			// Add objects for joined FfbPlayerteam rows

			$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
			if ($key2 !== null) {
				$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
				if (!$obj2) {

					$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if obj2 loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj2 (FfbPlayerteam)
				$obj2->addFfbPlayerstats($obj1);
			} // if joined row not null

			// Add objects for joined FfbMatch rows

			$key3 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, $startcol3);
			if ($key3 !== null) {
				$obj3 = FfbMatchPeer::getInstanceFromPool($key3);
				if (!$obj3) {

					$cls = FfbMatchPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchPeer::addInstanceToPool($obj3, $key3);
				} // if obj3 loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj3 (FfbMatch)
				$obj3->addFfbPlayerstats($obj1);
			} // if joined row not null

			// Add objects for joined FfbMatchround rows

			$key4 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol4);
			if ($key4 !== null) {
				$obj4 = FfbMatchroundPeer::getInstanceFromPool($key4);
				if (!$obj4) {

					$cls = FfbMatchroundPeer::getOMClass(false);

					$obj4 = new $cls();
					$obj4->hydrate($row, $startcol4);
					FfbMatchroundPeer::addInstanceToPool($obj4, $key4);
				} // if obj4 loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj4 (FfbMatchround)
				$obj4->addFfbPlayerstats($obj1);
			} // if joined row not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbPlayerteam table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbPlayerteam(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbMatch table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbMatch(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Returns the number of rows matching criteria, joining the related FfbMatchround table
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     int Number of matching rows.
	 */
	public static function doCountJoinAllExceptFfbMatchround(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		// we're going to modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(FfbPlayerstatsPeer::TABLE_NAME);
		
		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			FfbPlayerstatsPeer::addSelectColumns($criteria);
		}
		
		$criteria->clearOrderByColumns(); // ORDER BY should not affect count
		
		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
	
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with all related objects except FfbPlayerteam.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbPlayerteam(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol2 = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbMatch rows

				$key2 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbMatchPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbMatchPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbMatchPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj2 (FfbMatch)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbPlayerstats($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with all related objects except FfbMatch.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbMatch(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol2 = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchroundPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchroundPeer::NUM_COLUMNS - FfbMatchroundPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbPlayerteam rows

				$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj2 (FfbPlayerteam)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatchround rows

				$key3 = FfbMatchroundPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchroundPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchroundPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchroundPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj3 (FfbMatchround)
				$obj3->addFfbPlayerstats($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}


	/**
	 * Selects a collection of FfbPlayerstats objects pre-filled with all related objects except FfbMatchround.
	 *
	 * @param      Criteria  $criteria
	 * @param      PropelPDO $con
	 * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
	 * @return     array Array of FfbPlayerstats objects.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectJoinAllExceptFfbMatchround(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$criteria = clone $criteria;

		// Set the correct dbName if it has not been overridden
		// $criteria->getDbName() will return the same object if not set to another value
		// so == check is okay and faster
		if ($criteria->getDbName() == Propel::getDefaultDB()) {
			$criteria->setDbName(self::DATABASE_NAME);
		}

		FfbPlayerstatsPeer::addSelectColumns($criteria);
		$startcol2 = (FfbPlayerstatsPeer::NUM_COLUMNS - FfbPlayerstatsPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbPlayerteamPeer::addSelectColumns($criteria);
		$startcol3 = $startcol2 + (FfbPlayerteamPeer::NUM_COLUMNS - FfbPlayerteamPeer::NUM_LAZY_LOAD_COLUMNS);

		FfbMatchPeer::addSelectColumns($criteria);
		$startcol4 = $startcol3 + (FfbMatchPeer::NUM_COLUMNS - FfbMatchPeer::NUM_LAZY_LOAD_COLUMNS);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID, $join_behavior);

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID, $join_behavior);


		$stmt = BasePeer::doSelect($criteria, $con);
		$results = array();

		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key1 = FfbPlayerstatsPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj1 = FfbPlayerstatsPeer::getInstanceFromPool($key1))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj1->hydrate($row, 0, true); // rehydrate
			} else {
				$cls = FfbPlayerstatsPeer::getOMClass(false);

				$obj1 = new $cls();
				$obj1->hydrate($row);
				FfbPlayerstatsPeer::addInstanceToPool($obj1, $key1);
			} // if obj1 already loaded

				// Add objects for joined FfbPlayerteam rows

				$key2 = FfbPlayerteamPeer::getPrimaryKeyHashFromRow($row, $startcol2);
				if ($key2 !== null) {
					$obj2 = FfbPlayerteamPeer::getInstanceFromPool($key2);
					if (!$obj2) {
	
						$cls = FfbPlayerteamPeer::getOMClass(false);

					$obj2 = new $cls();
					$obj2->hydrate($row, $startcol2);
					FfbPlayerteamPeer::addInstanceToPool($obj2, $key2);
				} // if $obj2 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj2 (FfbPlayerteam)
				$obj2->addFfbPlayerstats($obj1);

			} // if joined row is not null

				// Add objects for joined FfbMatch rows

				$key3 = FfbMatchPeer::getPrimaryKeyHashFromRow($row, $startcol3);
				if ($key3 !== null) {
					$obj3 = FfbMatchPeer::getInstanceFromPool($key3);
					if (!$obj3) {
	
						$cls = FfbMatchPeer::getOMClass(false);

					$obj3 = new $cls();
					$obj3->hydrate($row, $startcol3);
					FfbMatchPeer::addInstanceToPool($obj3, $key3);
				} // if $obj3 already loaded

				// Add the $obj1 (FfbPlayerstats) to the collection in $obj3 (FfbMatch)
				$obj3->addFfbPlayerstats($obj1);

			} // if joined row is not null

			$results[] = $obj1;
		}
		$stmt->closeCursor();
		return $results;
	}

	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseFfbPlayerstatsPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseFfbPlayerstatsPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new FfbPlayerstatsTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * If $withPrefix is true, the returned path
	 * uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @param      boolean  Whether or not to return the path wit hthe class name 
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? FfbPlayerstatsPeer::CLASS_DEFAULT : FfbPlayerstatsPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a FfbPlayerstats or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbPlayerstats object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from FfbPlayerstats object
		}

		if ($criteria->containsKey(FfbPlayerstatsPeer::PLAYERSTATS_ID) && $criteria->keyContainsValue(FfbPlayerstatsPeer::PLAYERSTATS_ID) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.FfbPlayerstatsPeer::PLAYERSTATS_ID.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a FfbPlayerstats or Criteria object.
	 *
	 * @param      mixed $values Criteria or FfbPlayerstats object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(FfbPlayerstatsPeer::PLAYERSTATS_ID);
			$selectCriteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, $criteria->remove(FfbPlayerstatsPeer::PLAYERSTATS_ID), $comparison);

		} else { // $values is FfbPlayerstats object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the ffb_playerstats table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(FfbPlayerstatsPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			FfbPlayerstatsPeer::clearInstancePool();
			FfbPlayerstatsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a FfbPlayerstats or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or FfbPlayerstats object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			FfbPlayerstatsPeer::clearInstancePool();

			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof FfbPlayerstats) {
			// invalidate the cache for this single object
			FfbPlayerstatsPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else {
			// it must be the primary key



			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, (array) $values, Criteria::IN);

			foreach ((array) $values as $singleval) {
				// we can invalidate the cache for this single object
				FfbPlayerstatsPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			FfbPlayerstatsPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given FfbPlayerstats object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      FfbPlayerstats $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(FfbPlayerstats $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(FfbPlayerstatsPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(FfbPlayerstatsPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(FfbPlayerstatsPeer::DATABASE_NAME, FfbPlayerstatsPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     FfbPlayerstats
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = FfbPlayerstatsPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(FfbPlayerstatsPeer::DATABASE_NAME);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, $pk);

		$v = FfbPlayerstatsPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(FfbPlayerstatsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(FfbPlayerstatsPeer::DATABASE_NAME);
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_ID, $pks, Criteria::IN);
			$objs = FfbPlayerstatsPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseFfbPlayerstatsPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFfbPlayerstatsPeer::buildTableMap();

