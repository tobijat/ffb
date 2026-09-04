<?php



/**
 * This class defines the structure of the 'ffb_options' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.ffb.map
 */
class FfbOptionsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbOptionsTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('ffb_options');
		$this->setPhpName('FfbOptions');
		$this->setClassname('FfbOptions');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('OPTIONS_ID', 'OptionsId', 'INTEGER', true, null, null);
		$this->addForeignKey('OPTIONS_GAME_ID', 'OptionsGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('OPTIONS_SCORE_MINUTES', 'OptionsScoreMinutes', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_MINUTES_TRESHOLD', 'OptionsScoreMinutesTreshold', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_MINUTES_GT', 'OptionsScoreMinutesGt', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_MINUTES_LT', 'OptionsScoreMinutesLt', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_MINUTES_LT30', 'OptionsScoreMinutesLt30', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_GOALS_G', 'OptionsScoreGoalsG', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_GOALS_D', 'OptionsScoreGoalsD', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_GOALS_M', 'OptionsScoreGoalsM', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_GOALS_S', 'OptionsScoreGoalsS', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_ASSISTS', 'OptionsScoreAssists', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_NO_OPPGOALS_G', 'OptionsScoreNoOppgoalsG', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_NO_OPPGOALS_D', 'OptionsScoreNoOppgoalsD', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_NO_OPPGOALS_M', 'OptionsScoreNoOppgoalsM', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_OPPGOALS_G', 'OptionsScoreOppgoalsG', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_OPPGOALS_D', 'OptionsScoreOppgoalsD', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_OWNGOALS', 'OptionsScoreOwngoals', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_CARD_Y', 'OptionsScoreCardY', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_CARD_R', 'OptionsScoreCardR', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_CARD_YR', 'OptionsScoreCardYr', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_PENALTY_SAVED', 'OptionsScorePenaltySaved', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_PENALTY_LOST', 'OptionsScorePenaltyLost', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_PENALTYSHOOTOUT_SAVE', 'OptionsScorePenaltyshootoutSave', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_PENALTYSHOOTOUT_LOST', 'OptionsScorePenaltyshootoutLost', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_PENALTYSHOOTOUT_HIT', 'OptionsScorePenaltyshootoutHit', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_HIGH_LOSS', 'OptionsScoreHighLoss', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_HIGH_WIN', 'OptionsScoreHighWin', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_SCORE_HIGH_WIN_LOSS_TRESHOLD', 'OptionsScoreHighWinLossTreshold', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_ERROR', 'OptionsStatusError', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_ERROR_VALIDATION', 'OptionsStatusErrorValidation', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_SUCCESS', 'OptionsStatusSuccess', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_SUCCESS_INSERT', 'OptionsStatusSuccessInsert', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_SUCCESS_UPDATE', 'OptionsStatusSuccessUpdate', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_STATUS_SUCCESS_DELETE', 'OptionsStatusSuccessDelete', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_PLAYERS', 'OptionsLineupMaxPlayers', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_CREDITS', 'OptionsLineupMaxCredits', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_PLAYERS_TEAM', 'OptionsLineupMaxPlayersTeam', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MIN_G', 'OptionsLineupMinG', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MIN_D', 'OptionsLineupMinD', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MIN_M', 'OptionsLineupMinM', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MIN_S', 'OptionsLineupMinS', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_G', 'OptionsLineupMaxG', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_D', 'OptionsLineupMaxD', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_M', 'OptionsLineupMaxM', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_LINEUP_MAX_S', 'OptionsLineupMaxS', 'INTEGER', true, null, null);
		$this->addColumn('OPTIONS_GAME_RANKMODE', 'OptionsGameRankmode', 'VARCHAR', true, 255, 'wc');
		$this->addColumn('OPTIONS_GAME_PRICEMODE', 'OptionsGamePricemode', 'VARCHAR', true, 255, 'dynamic');
		$this->addColumn('OPTIONS_GAME_POINTSMODE', 'OptionsGamePointsmode', 'VARCHAR', true, 255, 'new');
		$this->addColumn('OPTIONS_GAME_WCPOINTS', 'OptionsGameWcpoints', 'VARCHAR', true, 255, 'new');
		$this->addColumn('OPTIONS_GAME_REMIND_HOURS_BEFORE', 'OptionsGameRemindHoursBefore', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('options_game_id' => 'game_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbOptionsTableMap
