<?php


/**
 * This class defines the structure of the 'ffb_user_award' table.
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
class FfbUserAwardTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbUserAwardTableMap';

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
		$this->setName('ffb_user_award');
		$this->setPhpName('FfbUserAward');
		$this->setClassname('FfbUserAward');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USER_AWARD_ID', 'UserAwardId', 'INTEGER', true, null, null);
		$this->addColumn('USER_AWARD_NAME', 'UserAwardName', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_IMAGE', 'UserAwardImage', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DESCRIPTION', 'UserAwardDescription', 'LONGVARCHAR', true, null, null);
		$this->addColumn('USER_AWARD_SORTFLAG', 'UserAwardSortflag', 'INTEGER', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbUserAwardDefines', 'FfbUserAwardDefines', RelationMap::ONE_TO_MANY, array('user_award_id' => 'user_award_defines_award_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbUserAwardTableMap
