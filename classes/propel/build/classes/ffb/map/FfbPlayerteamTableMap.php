<?php



/**
 * This class defines the structure of the 'ffb_playerteam' table.
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
class FfbPlayerteamTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPlayerteamTableMap';

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
		$this->setName('ffb_playerteam');
		$this->setPhpName('FfbPlayerteam');
		$this->setClassname('FfbPlayerteam');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PLAYERTEAM_ID', 'PlayerteamId', 'INTEGER', true, null, null);
		$this->addForeignKey('PLAYERTEAM_PLAYER_ID', 'PlayerteamPlayerId', 'INTEGER', 'ffb_player', 'PLAYER_ID', true, null, null);
		$this->addForeignKey('PLAYERTEAM_TEAM_ID', 'PlayerteamTeamId', 'INTEGER', 'ffb_team', 'TEAM_ID', true, null, null);
		$this->addColumn('PLAYERTEAM_PLAYER_PICTURE', 'PlayerteamPlayerPicture', 'VARCHAR', false, 255, '');
		$this->addColumn('PLAYERTEAM_STATUS', 'PlayerteamStatus', 'BOOLEAN', true, null, true);
		$this->addColumn('PLAYERTEAM_PLAYER_PRICE', 'PlayerteamPlayerPrice', 'DOUBLE', true, null, 0);
		$this->addColumn('PLAYERTEAM_PLAYER_POSITION', 'PlayerteamPlayerPosition', 'VARCHAR', true, 255, 'd');
		$this->addColumn('PLAYERTEAM_DATE_TRANSFER', 'PlayerteamDateTransfer', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPlayer', 'FfbPlayer', RelationMap::MANY_TO_ONE, array('playerteam_player_id' => 'player_id', ), 'CASCADE', null);
    $this->addRelation('FfbTeam', 'FfbTeam', RelationMap::MANY_TO_ONE, array('playerteam_team_id' => 'team_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerprice', 'FfbPlayerprice', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'playerprice_playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbGoal', 'FfbGoal', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'goal_playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbPsgoal', 'FfbPsgoal', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'psgoal_playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerstats', 'FfbPlayerstats', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'playerstats_playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerfid', 'FfbPlayerfid', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'playerfid_playerteam_id', ), null, null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId1', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id1', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId2', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id2', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId3', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id3', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId4', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id4', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId5', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id5', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId6', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id6', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId7', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id7', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId8', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id8', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId9', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id9', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId10', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id10', ), 'SET NULL', null);
    $this->addRelation('FfbUserteamRelatedByUserteamPlayerId11', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('playerteam_id' => 'userteam_player_id11', ), 'SET NULL', null);
	} // buildRelations()

} // FfbPlayerteamTableMap
