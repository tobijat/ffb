<?php



/**
 * This class defines the structure of the 'ffb_userteam' table.
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
class FfbUserteamTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbUserteamTableMap';

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
		$this->setName('ffb_userteam');
		$this->setPhpName('FfbUserteam');
		$this->setClassname('FfbUserteam');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USERTEAM_ID', 'UserteamId', 'INTEGER', true, null, null);
		$this->addForeignKey('USERTEAM_USER_ID', 'UserteamUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('USERTEAM_DATE', 'UserteamDate', 'TIMESTAMP', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID1', 'UserteamPlayerId1', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID2', 'UserteamPlayerId2', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID3', 'UserteamPlayerId3', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID4', 'UserteamPlayerId4', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID5', 'UserteamPlayerId5', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID6', 'UserteamPlayerId6', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID7', 'UserteamPlayerId7', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID8', 'UserteamPlayerId8', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID9', 'UserteamPlayerId9', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID10', 'UserteamPlayerId10', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('USERTEAM_PLAYER_ID11', 'UserteamPlayerId11', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addColumn('USERTEAM_PRICE', 'UserteamPrice', 'DECIMAL', true, null, 0);
		$this->addForeignKey('USERTEAM_MATCHROUND_ID', 'UserteamMatchroundId', 'INTEGER', 'ffb_matchround', 'MATCHROUND_ID', true, null, null);
		$this->addColumn('USERTEAM_SCORE', 'UserteamScore', 'INTEGER', true, null, -1);
		$this->addColumn('USERTEAM_WC_POINTS', 'UserteamWcPoints', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('userteam_user_id' => 'user_id', ), null, null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId1', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id1' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId2', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id2' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId3', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id3' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId4', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id4' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId5', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id5' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId6', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id6' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId7', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id7' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId8', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id8' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId9', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id9' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId10', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id10' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbPlayerteamRelatedByUserteamPlayerId11', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('userteam_player_id11' => 'playerteam_id', ), 'SET NULL', null);
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::MANY_TO_ONE, array('userteam_matchround_id' => 'matchround_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbUserteamTableMap
