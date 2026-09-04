<?php


/**
 * This class defines the structure of the 'ffb_game' table.
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
class FfbGameTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbGameTableMap';

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
		$this->setName('ffb_game');
		$this->setPhpName('FfbGame');
		$this->setClassname('FfbGame');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('GAME_ID', 'GameId', 'INTEGER', true, null, null);
		$this->addColumn('GAME_TITLE', 'GameTitle', 'VARCHAR', true, 255, 'Round');
		$this->addColumn('GAME_VISIBLE', 'GameVisible', 'BOOLEAN', true, null, false);
		$this->addColumn('GAME_ARCHIVE', 'GameArchive', 'BOOLEAN', true, null, false);
		$this->addColumn('GAME_COUNTDOWN', 'GameCountdown', 'BOOLEAN', true, null, false);
		$this->addColumn('GAME_STATUS', 'GameStatus', 'BOOLEAN', true, null, false);
		$this->addColumn('GAME_DESCRIPTION', 'GameDescription', 'LONGVARCHAR', false, null, null);
		$this->addColumn('GAME_SYMBOL', 'GameSymbol', 'VARCHAR', true, 255, 'game_symbol_na.png');
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUserDetails', 'WebUserDetails', RelationMap::ONE_TO_MANY, array('game_id' => 'user_details_ffb_selected_game', ), null, null);
    $this->addRelation('FfbComments', 'FfbComments', RelationMap::ONE_TO_MANY, array('game_id' => 'comments_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbPoll', 'FfbPoll', RelationMap::ONE_TO_MANY, array('game_id' => 'poll_game_id', ), null, null);
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::ONE_TO_MANY, array('game_id' => 'matchround_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbNews', 'FfbNews', RelationMap::ONE_TO_MANY, array('game_id' => 'news_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbUserscore', 'FfbUserscore', RelationMap::ONE_TO_MANY, array('game_id' => 'userscore_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbAdmin', 'FfbAdmin', RelationMap::ONE_TO_MANY, array('game_id' => 'admin_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbOptions', 'FfbOptions', RelationMap::ONE_TO_MANY, array('game_id' => 'options_game_id', ), 'CASCADE', null);
    $this->addRelation('FfbAdsAllocation', 'FfbAdsAllocation', RelationMap::ONE_TO_MANY, array('game_id' => 'ads_allocation_game_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbGameTableMap
