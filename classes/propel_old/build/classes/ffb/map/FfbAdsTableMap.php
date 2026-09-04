<?php



/**
 * This class defines the structure of the 'ffb_ads' table.
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
class FfbAdsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbAdsTableMap';

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
		$this->setName('ffb_ads');
		$this->setPhpName('FfbAds');
		$this->setClassname('FfbAds');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ADS_ID', 'AdsId', 'INTEGER', true, null, null);
		$this->addColumn('ADS_NAME', 'AdsName', 'VARCHAR', true, 255, null);
		$this->addColumn('ADS_CODE', 'AdsCode', 'LONGVARCHAR', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbAdsAllocation', 'FfbAdsAllocation', RelationMap::ONE_TO_MANY, array('ads_id' => 'ads_allocation_ads_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbAdsTableMap
