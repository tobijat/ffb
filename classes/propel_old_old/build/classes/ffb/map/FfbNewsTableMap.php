<?php


/**
 * This class defines the structure of the 'ffb_news' table.
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
class FfbNewsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbNewsTableMap';

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
		$this->setName('ffb_news');
		$this->setPhpName('FfbNews');
		$this->setClassname('FfbNews');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('NEWS_ID', 'NewsId', 'INTEGER', true, null, null);
		$this->addColumn('NEWS_TITLE', 'NewsTitle', 'VARCHAR', true, 255, null);
		$this->addColumn('NEWS_TEXT', 'NewsText', 'LONGVARCHAR', true, null, null);
		$this->addColumn('NEWS_DATE', 'NewsDate', 'TIMESTAMP', true, null, null);
		$this->addColumn('NEWS_PRIORITY', 'NewsPriority', 'INTEGER', true, null, 0);
		$this->addForeignKey('NEWS_GAME_ID', 'NewsGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, 0);
		$this->addColumn('NEWS_SYMBOL', 'NewsSymbol', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('news_game_id' => 'game_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbNewsTableMap
