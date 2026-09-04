<?php



/**
 * This class defines the structure of the 'ffb_userscore' table.
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
class FfbUserscoreTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbUserscoreTableMap';

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
		$this->setName('ffb_userscore');
		$this->setPhpName('FfbUserscore');
		$this->setClassname('FfbUserscore');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USERSCORE_ID', 'UserscoreId', 'INTEGER', true, null, null);
		$this->addForeignKey('USERSCORE_USER_ID', 'UserscoreUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addForeignKey('USERSCORE_GAME_ID', 'UserscoreGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('USERSCORE_TOTAL', 'UserscoreTotal', 'INTEGER', true, null, 0);
		$this->addColumn('USERSCORE_WC_POINTS', 'UserscoreWcPoints', 'INTEGER', true, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('userscore_user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('userscore_game_id' => 'game_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbUserscoreTableMap
