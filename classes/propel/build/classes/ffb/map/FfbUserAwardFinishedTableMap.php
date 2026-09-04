<?php



/**
 * This class defines the structure of the 'ffb_user_award_finished' table.
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
class FfbUserAwardFinishedTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbUserAwardFinishedTableMap';

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
		$this->setName('ffb_user_award_finished');
		$this->setPhpName('FfbUserAwardFinished');
		$this->setClassname('FfbUserAwardFinished');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USER_AWARD_FINISHED_ID', 'UserAwardFinishedId', 'INTEGER', true, null, null);
		$this->addForeignKey('USER_AWARD_FINISHED_USER_ID', 'UserAwardFinishedUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addForeignKey('USER_AWARD_FINISHED_AWARD_DEFINES_ID', 'UserAwardFinishedAwardDefinesId', 'INTEGER', 'ffb_user_award_defines', 'USER_AWARD_DEFINES_ID', true, null, null);
		$this->addColumn('USER_AWARD_FINISHED_DATE', 'UserAwardFinishedDate', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('user_award_finished_user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserAwardDefines', 'FfbUserAwardDefines', RelationMap::MANY_TO_ONE, array('user_award_finished_award_defines_id' => 'user_award_defines_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbUserAwardFinishedTableMap
