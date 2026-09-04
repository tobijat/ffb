<?php



/**
 * This class defines the structure of the 'web_admin' table.
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
class WebAdminTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebAdminTableMap';

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
		$this->setName('web_admin');
		$this->setPhpName('WebAdmin');
		$this->setClassname('WebAdmin');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ADMIN_ID', 'AdminId', 'INTEGER', true, null, null);
		$this->addForeignKey('ADMIN_USER_ID', 'AdminUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('ADMIN_SECTION', 'AdminSection', 'VARCHAR', true, 255, null);
		$this->addColumn('ADMIN_LEVEL', 'AdminLevel', 'INTEGER', true, null, null);
		$this->addColumn('ADMIN_STATUS', 'AdminStatus', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('admin_user_id' => 'user_id', ), 'CASCADE', null);
	} // buildRelations()

} // WebAdminTableMap
