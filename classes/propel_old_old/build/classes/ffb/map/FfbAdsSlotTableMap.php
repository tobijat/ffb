<?php


/**
 * This class defines the structure of the 'ffb_ads_slot' table.
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
class FfbAdsSlotTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbAdsSlotTableMap';

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
		$this->setName('ffb_ads_slot');
		$this->setPhpName('FfbAdsSlot');
		$this->setClassname('FfbAdsSlot');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ADS_SLOT_ID', 'AdsSlotId', 'INTEGER', true, null, null);
		$this->addColumn('ADS_SLOT_NAME', 'AdsSlotName', 'VARCHAR', true, 255, null);
		$this->addColumn('ADS_SLOT_CSS_CLASS', 'AdsSlotCssClass', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbAdsAllocation', 'FfbAdsAllocation', RelationMap::ONE_TO_MANY, array('ads_slot_id' => 'ads_allocation_slot_id', ), 'CASCADE', null);
    $this->addRelation('FfbNoAds', 'FfbNoAds', RelationMap::ONE_TO_MANY, array('ads_slot_id' => 'no_ads_slot_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbAdsSlotTableMap
