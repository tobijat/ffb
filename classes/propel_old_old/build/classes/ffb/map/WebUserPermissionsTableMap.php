<?php


/**
 * This class defines the structure of the 'web_user_permissions' table.
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
class WebUserPermissionsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebUserPermissionsTableMap';

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
		$this->setName('web_user_permissions');
		$this->setPhpName('WebUserPermissions');
		$this->setClassname('WebUserPermissions');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER', 'UserPermissionsFfbMailserviceReminder', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_PERMISSIONS_FFB_MAILSERVICE_INFO', 'UserPermissionsFfbMailserviceInfo', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_PERMISSIONS_FFB_FACEBOOK', 'UserPermissionsFfbFacebook', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_PERMISSIONS_PICTORY_FACEBOOK', 'UserPermissionsPictoryFacebook', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_PERMISSIONS_FACEBOOK_CONNECTED', 'UserPermissionsFacebookConnected', 'BOOLEAN', true, null, false);
		$this->addColumn('USER_PERMISSIONS_FFB_VISIBLE_PROFILE', 'UserPermissionsFfbVisibleProfile', 'BOOLEAN', true, null, false);
		$this->addColumn('USER_PERMISSIONS_PICTORY_VISIBLE_PROFILE', 'UserPermissionsPictoryVisibleProfile', 'BOOLEAN', true, null, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('user_id' => 'user_id', ), 'CASCADE', null);
	} // buildRelations()

} // WebUserPermissionsTableMap
