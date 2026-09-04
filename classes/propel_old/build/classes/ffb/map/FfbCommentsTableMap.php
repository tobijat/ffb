<?php



/**
 * This class defines the structure of the 'ffb_comments' table.
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
class FfbCommentsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbCommentsTableMap';

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
		$this->setName('ffb_comments');
		$this->setPhpName('FfbComments');
		$this->setClassname('FfbComments');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('COMMENTS_ID', 'CommentsId', 'INTEGER', true, null, null);
		$this->addForeignKey('COMMENTS_USER_ID', 'CommentsUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addForeignKey('COMMENTS_GAME_ID', 'CommentsGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addForeignKey('COMMENTS_MATCHROUND_ID', 'CommentsMatchroundId', 'INTEGER', 'ffb_matchround', 'MATCHROUND_ID', true, null, null);
		$this->addColumn('COMMENTS_LOCATION', 'CommentsLocation', 'VARCHAR', true, 255, null);
		$this->addColumn('COMMENTS_TEXT', 'CommentsText', 'LONGVARCHAR', true, null, null);
		$this->addColumn('COMMENTS_DATE', 'CommentsDate', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('comments_user_id' => 'user_id', ), 'CASCADE', null);
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('comments_game_id' => 'game_id', ), 'CASCADE', null);
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::MANY_TO_ONE, array('comments_matchround_id' => 'matchround_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbCommentsTableMap
