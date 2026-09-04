<?php


/**
 * This class defines the structure of the 'web_user_details' table.
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
class WebUserDetailsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.WebUserDetailsTableMap';

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
		$this->setName('web_user_details');
		$this->setPhpName('WebUserDetails');
		$this->setClassname('WebUserDetails');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'web_user', 'USER_ID', true, null, null);
		$this->addColumn('USER_DETAILS_AVATAR', 'UserDetailsAvatar', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_PHOTO', 'UserDetailsPhoto', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_WEBSITE', 'UserDetailsWebsite', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_ZIP', 'UserDetailsZip', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_STREET', 'UserDetailsStreet', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_CITY', 'UserDetailsCity', 'VARCHAR', false, 255, null);
		$this->addColumn('USER_DETAILS_PHONE', 'UserDetailsPhone', 'VARCHAR', false, 255, null);
		$this->addForeignKey('USER_DETAILS_FFB_FAVOURITE_TEAM', 'UserDetailsFfbFavouriteTeam', 'INTEGER', 'ffb_team', 'TEAM_ID', false, null, null);
		$this->addForeignKey('USER_DETAILS_FFB_OWN_TEAM', 'UserDetailsFfbOwnTeam', 'INTEGER', 'ffb_team', 'TEAM_ID', false, null, null);
		$this->addForeignKey('USER_DETAILS_FFB_OWN_PLAYER', 'UserDetailsFfbOwnPlayer', 'INTEGER', 'ffb_player', 'PLAYER_ID', false, null, null);
		$this->addForeignKey('USER_DETAILS_FFB_SELECTED_GAME', 'UserDetailsFfbSelectedGame', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('USER_DETAILS_LAST_UPDATE', 'UserDetailsLastUpdate', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('FfbTeamRelatedByUserDetailsFfbFavouriteTeam', 'FfbTeam', RelationMap::MANY_TO_ONE, array('user_details_ffb_favourite_team' => 'team_id', ), 'CASCADE', null);
    $this->addRelation('FfbTeamRelatedByUserDetailsFfbOwnTeam', 'FfbTeam', RelationMap::MANY_TO_ONE, array('user_details_ffb_own_team' => 'team_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayer', 'FfbPlayer', RelationMap::MANY_TO_ONE, array('user_details_ffb_own_player' => 'player_id', ), 'CASCADE', null);
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('user_details_ffb_selected_game' => 'game_id', ), null, null);
	} // buildRelations()

} // WebUserDetailsTableMap
