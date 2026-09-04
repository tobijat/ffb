<?php


/**
 * This class defines the structure of the 'ffb_playerfid' table.
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
class FfbPlayerfidTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPlayerfidTableMap';

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
		$this->setName('ffb_playerfid');
		$this->setPhpName('FfbPlayerfid');
		$this->setClassname('FfbPlayerfid');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PLAYERFID_ID', 'PlayerfidId', 'INTEGER', true, null, null);
		$this->addForeignKey('PLAYERFID_PLAYERTEAM_ID', 'PlayerfidPlayerteamId', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('PLAYERFID_TEAM_ID', 'PlayerfidTeamId', 'INTEGER', 'ffb_team', 'TEAM_ID', true, null, null);
		$this->addColumn('PLAYERFID_FID_FOE', 'PlayerfidFidFoe', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_FID_FIFA', 'PlayerfidFidFifa', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_FID_TM', 'PlayerfidFidTm', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_FID_UEFA', 'PlayerfidFidUefa', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_FID_WF', 'PlayerfidFidWf', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_NAME_FOE', 'PlayerfidNameFoe', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_NAME_FIFA', 'PlayerfidNameFifa', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_NAME_TM', 'PlayerfidNameTm', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_NAME_UEFA', 'PlayerfidNameUefa', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYERFID_NAME_WF', 'PlayerfidNameWf', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('playerfid_playerteam_id' => 'playerteam_id', ), null, null);
    $this->addRelation('FfbTeam', 'FfbTeam', RelationMap::MANY_TO_ONE, array('playerfid_team_id' => 'team_id', ), null, null);
	} // buildRelations()

} // FfbPlayerfidTableMap
