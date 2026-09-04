<?php



/**
 * This class defines the structure of the 'ffb_player' table.
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
class FfbPlayerTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPlayerTableMap';

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
		$this->setName('ffb_player');
		$this->setPhpName('FfbPlayer');
		$this->setClassname('FfbPlayer');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PLAYER_ID', 'PlayerId', 'INTEGER', true, null, null);
		$this->addColumn('PLAYER_FOREIGN_ID', 'PlayerForeignId', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYER_FNAME', 'PlayerFname', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYER_LNAME', 'PlayerLname', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYER_NATIONALITY', 'PlayerNationality', 'VARCHAR', true, 255, null);
		$this->addColumn('PLAYER_STATUS', 'PlayerStatus', 'INTEGER', true, null, 0);
		$this->addColumn('PLAYER_STATUS_DESCRIPTION', 'PlayerStatusDescription', 'VARCHAR', false, 255, '');
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUserDetails', 'WebUserDetails', RelationMap::ONE_TO_MANY, array('player_id' => 'user_details_ffb_own_player', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::ONE_TO_MANY, array('player_id' => 'playerteam_player_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbPlayerTableMap
