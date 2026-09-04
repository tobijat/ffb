<?php


/**
 * This class defines the structure of the 'web_user' table.
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
class WebUserTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebUserTableMap';

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
		$this->setName('web_user');
		$this->setPhpName('WebUser');
		$this->setClassname('WebUser');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USER_ID', 'UserId', 'INTEGER', true, null, null);
		$this->addColumn('USER_NICKNAME', 'UserNickname', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_PASSWORD', 'UserPassword', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_EMAIL', 'UserEmail', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_FNAME', 'UserFname', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_LNAME', 'UserLname', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_GENDER', 'UserGender', 'VARCHAR', false, 255, '');
		$this->addColumn('USER_STATUS', 'UserStatus', 'VARCHAR', true, 255, 'active');
		$this->addColumn('USER_ADMIN', 'UserAdmin', 'BOOLEAN', true, null, false);
		$this->addColumn('USER_FACEBOOK_ID', 'UserFacebookId', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_NATIONALITY', 'UserNationality', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DATE_BIRTH', 'UserDateBirth', 'TIMESTAMP', false, null, null);
		$this->addColumn('USER_IP', 'UserIp', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_LIP', 'UserLip', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_DATE_REGISTER', 'UserDateRegister', 'TIMESTAMP', false, null, null);
		$this->addColumn('USER_DATE_LLOGIN', 'UserDateLlogin', 'TIMESTAMP', false, null, null);
		$this->addColumn('USER_DATE_LACTION', 'UserDateLaction', 'TIMESTAMP', false, null, null);
		$this->addColumn('USER_ACTIVATION_CODE', 'UserActivationCode', 'VARCHAR', true, 255, null);
		$this->addColumn('USER_MAILSERVICE', 'UserMailservice', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUserDetails', 'WebUserDetails', RelationMap::ONE_TO_ONE, array('user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('WebUserPermissions', 'WebUserPermissions', RelationMap::ONE_TO_ONE, array('user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('FfbComments', 'FfbComments', RelationMap::ONE_TO_MANY, array('user_id' => 'comments_user_id', ), 'CASCADE', null);
    $this->addRelation('FfbPollResult', 'FfbPollResult', RelationMap::ONE_TO_MANY, array('user_id' => 'poll_result_user_id', ), null, null);
    $this->addRelation('FfbInvitation', 'FfbInvitation', RelationMap::ONE_TO_MANY, array('user_id' => 'invitation_sender_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserteam', 'FfbUserteam', RelationMap::ONE_TO_MANY, array('user_id' => 'userteam_user_id', ), null, null);
    $this->addRelation('FfbUserscore', 'FfbUserscore', RelationMap::ONE_TO_MANY, array('user_id' => 'userscore_user_id', ), 'CASCADE', null);
    $this->addRelation('FfbAdmin', 'FfbAdmin', RelationMap::ONE_TO_MANY, array('user_id' => 'admin_user_id', ), 'CASCADE', null);
    $this->addRelation('WebLog', 'WebLog', RelationMap::ONE_TO_MANY, array('user_id' => 'log_user_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserAwardFinished', 'FfbUserAwardFinished', RelationMap::ONE_TO_MANY, array('user_id' => 'user_award_finished_user_id', ), 'CASCADE', null);
    $this->addRelation('WebAdmin', 'WebAdmin', RelationMap::ONE_TO_MANY, array('user_id' => 'admin_user_id', ), 'CASCADE', null);
	} // buildRelations()

} // WebUserTableMap
