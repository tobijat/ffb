<?php



/**
 * This class defines the structure of the 'ffb_cronjob' table.
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
class FfbCronjobTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbCronjobTableMap';

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
		$this->setName('ffb_cronjob');
		$this->setPhpName('FfbCronjob');
		$this->setClassname('FfbCronjob');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('CRONJOB_ID', 'CronjobId', 'INTEGER', true, null, null);
		$this->addColumn('CRONJOB_DESCRIPTION', 'CronjobDescription', 'VARCHAR', true, 255, null);
		$this->addColumn('CRONJOB_FUNCTION', 'CronjobFunction', 'VARCHAR', true, 255, null);
		$this->addColumn('CRONJOB_TIME_START', 'CronjobTimeStart', 'TIMESTAMP', true, null, null);
		$this->addColumn('CRONJOB_TIME_END', 'CronjobTimeEnd', 'TIMESTAMP', true, null, null);
		$this->addColumn('CRONJOB_TIME_LASTRUN', 'CronjobTimeLastrun', 'TIMESTAMP', true, null, null);
		$this->addColumn('CRONJOB_STATUS', 'CronjobStatus', 'BOOLEAN', true, null, true);
		$this->addColumn('CRONJOB_INTERVAL_HOURS', 'CronjobIntervalHours', 'INTEGER', true, null, 24);
		$this->addColumn('CRONJOB_RUNONCE', 'CronjobRunonce', 'BOOLEAN', true, null, false);
		$this->addColumn('CRONJOB_RUNHOUR', 'CronjobRunhour', 'INTEGER', true, null, 5);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

} // FfbCronjobTableMap
