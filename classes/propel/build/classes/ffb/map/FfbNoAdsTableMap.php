<?php



/**
 * This class defines the structure of the 'ffb_no_ads' table.
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
class FfbNoAdsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbNoAdsTableMap';

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
		$this->setName('ffb_no_ads');
		$this->setPhpName('FfbNoAds');
		$this->setClassname('FfbNoAds');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('NO_ADS_ID', 'NoAdsId', 'INTEGER', true, null, null);
		$this->addColumn('NO_ADS_USER_ID_IP', 'NoAdsUserIdIp', 'VARCHAR', true, 255, null);
		$this->addForeignKey('NO_ADS_SLOT_ID', 'NoAdsSlotId', 'INTEGER', 'ffb_ads_slot', 'ADS_SLOT_ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbAdsSlot', 'FfbAdsSlot', RelationMap::MANY_TO_ONE, array('no_ads_slot_id' => 'ads_slot_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbNoAdsTableMap
