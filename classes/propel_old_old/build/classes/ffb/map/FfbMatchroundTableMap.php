<?php


/**
 * This class defines the structure of the 'ffb_matchround' table.
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
class FfbMatchroundTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbMatchroundTableMap';

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
		$this->setName('ffb_matchround');
		$this->setPhpName('FfbMatchround');
		$this->setClassname('FfbMatchround');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('MATCHROUND_ID', 'MatchroundId', 'INTEGER', true, null, null);
		$this->addForeignKey('MATCHROUND_GAME_ID', 'MatchroundGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('MATCHROUND_TITLE', 'MatchroundTitle', 'VARCHAR', true, 255, 'Round');
		$this->addColumn('MATCHROUND_STARTDATE', 'MatchroundStartdate', 'TIMESTAMP', true, null, null);
		$this->addColumn('MATCHROUND_ENDDATE', 'MatchroundEnddate', 'TIMESTAMP', true, null, null);
		$this->addColumn('MATCHROUND_STATUS', 'MatchroundStatus', 'INTEGER', true, null, 1);
		$this->addColumn('MATCHROUND_CREDITS', 'MatchroundCredits', 'DOUBLE', true, null, 0);
		$this->addColumn('MATCHROUND_MAX_PLAYERS_FROM_TEAM', 'MatchroundMaxPlayersFromTeam', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('matchround_game_id' => 'game_id', ), 'CASCADE', null);
    $this->addRelation('FfbComments', 'FfbComments', RelationMap::ONE_TO_MANY, array('matchround_id' => 'comments_matchround_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerprice', 'FfbPlayerprice', RelationMap::ONE_TO_MANY, array('matchround_id' => 'playerprice_matchround_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatch', 'FfbMatch', RelationMap::ONE_TO_MANY, array('matchround_id' => 'match_round', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerstats', 'FfbPlayerstats', RelationMap::ONE_TO_MANY, array('matchround_id' => 'playerstats_matchround_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserteam', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('matchround_id' => 'userteam_matchround_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbMatchroundTableMap
