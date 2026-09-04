<?php



/**
 * This class defines the structure of the 'ffb_team' table.
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
class FfbTeamTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbTeamTableMap';

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
		$this->setName('ffb_team');
		$this->setPhpName('FfbTeam');
		$this->setClassname('FfbTeam');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('TEAM_ID', 'TeamId', 'INTEGER', true, null, null);
		$this->addColumn('TEAM_FOREIGN_ID', 'TeamForeignId', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAM_NAME', 'TeamName', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAM_NATIONALITY', 'TeamNationality', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAM_AVG_PRICE', 'TeamAvgPrice', 'DOUBLE', true, null, null);
		$this->addColumn('TEAM_NUM_PLAYERS', 'TeamNumPlayers', 'INTEGER', true, null, null);
		$this->addColumn('TEAM_STATUS', 'TeamStatus', 'BOOLEAN', true, null, true);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUserDetailsRelatedByUserDetailsFfbFavouriteTeam', 'WebUserDetails', RelationMap::ONE_TO_MANY, array('team_id' => 'user_details_ffb_favourite_team', ), 'CASCADE', null);
    $this->addRelation('WebUserDetailsRelatedByUserDetailsFfbOwnTeam', 'WebUserDetails', RelationMap::ONE_TO_MANY, array('team_id' => 'user_details_ffb_own_team', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::ONE_TO_MANY, array('team_id' => 'playerteam_team_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatchRelatedByMatchHometeamId', 'FfbMatch', RelationMap::ONE_TO_MANY, array('team_id' => 'match_hometeam_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatchRelatedByMatchGuestteamId', 'FfbMatch', RelationMap::ONE_TO_MANY, array('team_id' => 'match_guestteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerfid', 'FfbPlayerfid', RelationMap::ONE_TO_MANY, array('team_id' => 'playerfid_team_id', ), null, null);
    $this->addRelation('FfbTeamfid', 'FfbTeamfid', RelationMap::ONE_TO_MANY, array('team_id' => 'teamfid_team_id', ), null, null);
	} // buildRelations()

} // FfbTeamTableMap
