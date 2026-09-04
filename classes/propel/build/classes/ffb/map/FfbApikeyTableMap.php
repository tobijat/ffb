<?php



/**
 * This class defines the structure of the 'ffb_apikey' table.
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
class FfbApikeyTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbApikeyTableMap';

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
		$this->setName('ffb_apikey');
		$this->setPhpName('FfbApikey');
		$this->setClassname('FfbApikey');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('APIKEY_ID', 'ApikeyId', 'INTEGER', true, null, null);
		$this->addColumn('APIKEY_KEY', 'ApikeyKey', 'VARCHAR', true, 255, null);
		$this->addColumn('APIKEY_IP', 'ApikeyIp', 'VARCHAR', true, 255, null);
		$this->addColumn('APIKEY_DESCRIPTION', 'ApikeyDescription', 'VARCHAR', true, 255, null);
		$this->addColumn('APIKEY_LASTCALL', 'ApikeyLastcall', 'TIMESTAMP', true, null, null);
		$this->addColumn('APIKEY_STATUS', 'ApikeyStatus', 'BOOLEAN', true, null, true);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

} // FfbApikeyTableMap
