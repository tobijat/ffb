<?php


/**
 * This class defines the structure of the 'ffb_ads_allocation' table.
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
class FfbAdsAllocationTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbAdsAllocationTableMap';

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
		$this->setName('ffb_ads_allocation');
		$this->setPhpName('FfbAdsAllocation');
		$this->setClassname('FfbAdsAllocation');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ADS_ALLOCATION_ID', 'AdsAllocationId', 'INTEGER', true, null, null);
		$this->addForeignKey('ADS_ALLOCATION_ADS_ID', 'AdsAllocationAdsId', 'INTEGER', 'ffb_ads', 'ADS_ID', true, null, null);
		$this->addForeignKey('ADS_ALLOCATION_SLOT_ID', 'AdsAllocationSlotId', 'INTEGER', 'ffb_ads_slot', 'ADS_SLOT_ID', true, null, null);
		$this->addForeignKey('ADS_ALLOCATION_GAME_ID', 'AdsAllocationGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('ADS_ALLOCATION_AD_COUNT', 'AdsAllocationAdCount', 'INTEGER', true, null, null);
		$this->addColumn('ADS_ALLOCATION_AD_MAX', 'AdsAllocationAdMax', 'INTEGER', true, null, null);
		$this->addColumn('ADS_ALLOCATION_AD_PRIORITY', 'AdsAllocationAdPriority', 'INTEGER', true, null, null);
		$this->addColumn('ADS_ALLOCATION_START', 'AdsAllocationStart', 'TIMESTAMP', false, null, null);
		$this->addColumn('ADS_ALLOCATION_END', 'AdsAllocationEnd', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbAds', 'FfbAds', RelationMap::MANY_TO_ONE, array('ads_allocation_ads_id' => 'ads_id', ), 'CASCADE', null);
    $this->addRelation('FfbAdsSlot', 'FfbAdsSlot', RelationMap::MANY_TO_ONE, array('ads_allocation_slot_id' => 'ads_slot_id', ), 'CASCADE', null);
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('ads_allocation_game_id' => 'game_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbAdsAllocationTableMap
