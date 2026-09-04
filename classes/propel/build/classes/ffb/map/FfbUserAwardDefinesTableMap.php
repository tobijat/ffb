<?php



/**
 * This class defines the structure of the 'ffb_user_award_defines' table.
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
class FfbUserAwardDefinesTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbUserAwardDefinesTableMap';

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
		$this->setName('ffb_user_award_defines');
		$this->setPhpName('FfbUserAwardDefines');
		$this->setClassname('FfbUserAwardDefines');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USER_AWARD_DEFINES_ID', 'UserAwardDefinesId', 'INTEGER', true, null, null);
		$this->addForeignKey('USER_AWARD_DEFINES_AWARD_ID', 'UserAwardDefinesAwardId', 'INTEGER', 'ffb_user_award', 'USER_AWARD_ID', true, null, null);
		$this->addColumn('USER_AWARD_DEFINES_RANK', 'UserAwardDefinesRank', 'INTEGER', true, null, null);
		$this->addColumn('USER_AWARD_DEFINES_RANK_NAME', 'UserAwardDefinesRankName', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_AIM', 'UserAwardDefinesAim', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_AIM_DBTABLE', 'UserAwardDefinesAimDbtable', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_AIM_OPERATOR', 'UserAwardDefinesAimOperator', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_AIM_COUNT', 'UserAwardDefinesAimCount', 'INTEGER', true, null, null);
		$this->addColumn('USER_AWARD_DEFINES_AIM_AUTOMATIC', 'UserAwardDefinesAimAutomatic', 'BOOLEAN', true, null, true);
		$this->addColumn('USER_AWARD_DEFINES_AIM_FUNCTION_NAME', 'UserAwardDefinesAimFunctionName', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_IMAGE', 'UserAwardDefinesImage', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_AWARD_DEFINES_DESCRIPTION', 'UserAwardDefinesDescription', 'LONGVARCHAR', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbUserAward', 'FfbUserAward', RelationMap::MANY_TO_ONE, array('user_award_defines_award_id' => 'user_award_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserAwardFinished', 'FfbUserAwardFinished', RelationMap::ONE_TO_MANY, array('user_award_defines_id' => 'user_award_finished_award_defines_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbUserAwardDefinesTableMap
