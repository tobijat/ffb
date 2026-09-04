<?php



/**
 * This class defines the structure of the 'ffb_rss' table.
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
class FfbRssTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbRssTableMap';

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
		$this->setName('ffb_rss');
		$this->setPhpName('FfbRss');
		$this->setClassname('FfbRss');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('FFB_RSS_ID', 'FfbRssId', 'INTEGER', true, null, null);
		$this->addColumn('FFB_RSS_TITLE', 'FfbRssTitle', 'LONGVARCHAR', true, null, null);
		$this->addColumn('FFB_RSS_DESCRIPTION', 'FfbRssDescription', 'LONGVARCHAR', true, null, null);
		$this->addColumn('FFB_RSS_CATEGORY', 'FfbRssCategory', 'LONGVARCHAR', true, null, null);
		$this->addColumn('FFB_RSS_GUID', 'FfbRssGuid', 'VARCHAR', true, 255, null);
		$this->addColumn('FFB_RSS_AUTHOR', 'FfbRssAuthor', 'VARCHAR', true, 255, null);
		$this->addColumn('FFB_RSS_ORIGIN_ID', 'FfbRssOriginId', 'INTEGER', true, null, null);
		$this->addColumn('FFB_RSS_PUBDATE', 'FfbRssPubdate', 'TIMESTAMP', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

} // FfbRssTableMap
