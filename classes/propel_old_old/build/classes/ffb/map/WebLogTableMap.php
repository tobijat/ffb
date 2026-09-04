<?php


/**
 * This class defines the structure of the 'web_log' table.
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
class WebLogTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebLogTableMap';

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
		$this->setName('web_log');
		$this->setPhpName('WebLog');
		$this->setClassname('WebLog');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('LOG_ID', 'LogId', 'INTEGER', true, null, null);
		$this->addForeignKey('LOG_USER_ID', 'LogUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('LOG_USER_NICKNAME', 'LogUserNickname', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_USER_IP', 'LogUserIp', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_MODULE', 'LogModule', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_CLASS', 'LogClass', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_EVENT', 'LogEvent', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_PRESENTER', 'LogPresenter', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_SUBDOMAIN', 'LogSubdomain', 'VARCHAR', true, 255, null);
		$this->addColumn('LOG_DATE', 'LogDate', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('log_user_id' => 'user_id', ), 'CASCADE', null);
	} // buildRelations()

} // WebLogTableMap
