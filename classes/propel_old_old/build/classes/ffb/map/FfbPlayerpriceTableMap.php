<?php


/**
 * This class defines the structure of the 'ffb_playerprice' table.
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
class FfbPlayerpriceTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPlayerpriceTableMap';

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
		$this->setName('ffb_playerprice');
		$this->setPhpName('FfbPlayerprice');
		$this->setClassname('FfbPlayerprice');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('PLAYERPRICE_ID', 'PlayerpriceId', 'INTEGER', true, null, null);
		$this->addForeignKey('PLAYERPRICE_PLAYERTEAM_ID', 'PlayerpricePlayerteamId', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addForeignKey('PLAYERPRICE_MATCHROUND_ID', 'PlayerpriceMatchroundId', 'INTEGER', 'ffb_matchround', 'MATCHROUND_ID', true, null, null);
		$this->addColumn('PLAYERPRICE_PRICE', 'PlayerpricePrice', 'DOUBLE', true, null, 0);
		$this->addColumn('PLAYERPRICE_PLAYER_POWER', 'PlayerpricePlayerPower', 'DOUBLE', true, null, 0);
		$this->addColumn('PLAYERPRICE_AV_POWER', 'PlayerpriceAvPower', 'DOUBLE', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('playerprice_playerteam_id' => 'playerteam_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::MANY_TO_ONE, array('playerprice_matchround_id' => 'matchround_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbPlayerpriceTableMap
