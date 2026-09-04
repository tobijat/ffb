<?php



/**
 * This class defines the structure of the 'ffb_psgoal' table.
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
class FfbPsgoalTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPsgoalTableMap';

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
		$this->setName('ffb_psgoal');
		$this->setPhpName('FfbPsgoal');
		$this->setClassname('FfbPsgoal');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PSGOAL_ID', 'PsgoalId', 'INTEGER', true, null, null);
		$this->addForeignKey('PSGOAL_MATCH_ID', 'PsgoalMatchId', 'INTEGER', 'ffb_match', 'MATCH_ID', true, null, null);
		$this->addForeignKey('PSGOAL_PLAYERTEAM_ID', 'PsgoalPlayerteamId', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addColumn('PSGOAL_MINUTE', 'PsgoalMinute', 'INTEGER', true, null, null);
		$this->addColumn('PSGOAL_HIT', 'PsgoalHit', 'BOOLEAN', true, null, false);
		$this->addColumn('PSGOAL_FAIL', 'PsgoalFail', 'BOOLEAN', true, null, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbMatch', 'FfbMatch', RelationMap::MANY_TO_ONE, array('psgoal_match_id' => 'match_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('psgoal_playerteam_id' => 'playerteam_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbPsgoalTableMap
