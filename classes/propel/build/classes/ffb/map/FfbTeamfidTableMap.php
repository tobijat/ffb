<?php



/**
 * This class defines the structure of the 'ffb_teamfid' table.
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
class FfbTeamfidTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbTeamfidTableMap';

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
		$this->setName('ffb_teamfid');
		$this->setPhpName('FfbTeamfid');
		$this->setClassname('FfbTeamfid');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('TEAMFID_ID', 'TeamfidId', 'INTEGER', true, null, null);
		$this->addForeignKey('TEAMFID_TEAM_ID', 'TeamfidTeamId', 'INTEGER', 'ffb_team', 'TEAM_ID', true, null, null);
		$this->addColumn('TEAMFID_FID_FOE', 'TeamfidFidFoe', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_FID_TM', 'TeamfidFidTm', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_FID_WF', 'TeamfidFidWf', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_NAME_FOE', 'TeamfidNameFoe', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_NAME_TM', 'TeamfidNameTm', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_NAME_WF', 'TeamfidNameWf', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_URL_FOE', 'TeamfidUrlFoe', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_URL_TM', 'TeamfidUrlTm', 'VARCHAR', true, 255, null);
		$this->addColumn('TEAMFID_URL_WF', 'TeamfidUrlWf', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbTeam', 'FfbTeam', RelationMap::MANY_TO_ONE, array('teamfid_team_id' => 'team_id', ), null, null);
	} // buildRelations()

} // FfbTeamfidTableMap
