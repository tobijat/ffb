<?php


/**
 * This class defines the structure of the 'ffb_playerstats' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    ffb.map
 */
class FfbPlayerstatsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPlayerstatsTableMap';

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
		$this->setName('ffb_playerstats');
		$this->setPhpName('FfbPlayerstats');
		$this->setClassname('FfbPlayerstats');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PLAYERSTATS_ID', 'PlayerstatsId', 'INTEGER', true, null, null);
		$this->addForeignKey('PLAYERSTATS_PLAYERTEAM_ID', 'PlayerstatsPlayerteamId', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('PLAYERSTATS_MATCH_ID', 'PlayerstatsMatchId', 'INTEGER', 'ffb_match', 'MATCH_ID', true, null, null);
		$this->addForeignKey('PLAYERSTATS_MATCHROUND_ID', 'PlayerstatsMatchroundId', 'INTEGER', 'ffb_matchround', 'MATCHROUND_ID', true, null, null);
		$this->addColumn('PLAYERSTATS_GOALS', 'PlayerstatsGoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_ASSISTS', 'PlayerstatsAssists', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_MINUTES', 'PlayerstatsMinutes', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_MINUTE_IN', 'PlayerstatsMinuteIn', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_MINUTE_OUT', 'PlayerstatsMinuteOut', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_CARDS', 'PlayerstatsCards', 'VARCHAR', true, 255, '');
		$this->addColumn('PLAYERSTATS_OWNGOALS', 'PlayerstatsOwngoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_PENALTIESLOST', 'PlayerstatsPenaltieslost', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_PENALTIESSAVED', 'PlayerstatsPenaltiessaved', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_PENALTYSHOOTOUT_SAVE', 'PlayerstatsPenaltyshootoutSave', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_PENALTYSHOOTOUT_LOST', 'PlayerstatsPenaltyshootoutLost', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_PENALTYSHOOTOUT_HIT', 'PlayerstatsPenaltyshootoutHit', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_GOALS', 'PlayerstatsScoreGoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_ASSISTS', 'PlayerstatsScoreAssists', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_MINUTES', 'PlayerstatsScoreMinutes', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_CARDS', 'PlayerstatsScoreCards', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_OWNGOALS', 'PlayerstatsScoreOwngoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_PENALTIESLOST', 'PlayerstatsScorePenaltieslost', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_PENALTIESSAVED', 'PlayerstatsScorePenaltiessaved', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_OPPGOALS', 'PlayerstatsScoreOppgoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_NOOPPGOALS', 'PlayerstatsScoreNooppgoals', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_HIGH_LOSS', 'PlayerstatsScoreHighLoss', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_HIGH_WIN', 'PlayerstatsScoreHighWin', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_PENALTYSHOOTOUT_SAVE', 'PlayerstatsScorePenaltyshootoutSave', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_PENALTYSHOOTOUT_LOST', 'PlayerstatsScorePenaltyshootoutLost', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE_PENALTYSHOOTOUT_HIT', 'PlayerstatsScorePenaltyshootoutHit', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYERSTATS_SCORE', 'PlayerstatsScore', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('playerstats_playerteam_id' => 'playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatch', 'FfbMatch', RelationMap::MANY_TO_ONE, array('playerstats_match_id' => 'match_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::MANY_TO_ONE, array('playerstats_matchround_id' => 'matchround_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbPlayerstatsTableMap
