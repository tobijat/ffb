<?php



/**
 * This class defines the structure of the 'ffb_invitation' table.
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
class FfbInvitationTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbInvitationTableMap';

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
		$this->setName('ffb_invitation');
		$this->setPhpName('FfbInvitation');
		$this->setClassname('FfbInvitation');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('INVITATION_ID', 'InvitationId', 'INTEGER', true, null, null);
		$this->addForeignKey('INVITATION_SENDER_ID', 'InvitationSenderId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('INVITATION_EMAIL', 'InvitationEmail', 'VARCHAR', true, 255, null);
		$this->addColumn('INVITATION_DATE', 'InvitationDate', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('invitation_sender_id' => 'user_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbInvitationTableMap
