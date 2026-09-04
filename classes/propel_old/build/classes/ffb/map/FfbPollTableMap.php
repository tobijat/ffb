<?php



/**
 * This class defines the structure of the 'ffb_poll' table.
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
class FfbPollTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPollTableMap';

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
		$this->setName('ffb_poll');
		$this->setPhpName('FfbPoll');
		$this->setClassname('FfbPoll');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('POLL_ID', 'PollId', 'INTEGER', true, null, null);
		$this->addColumn('POLL_TITLE', 'PollTitle', 'VARCHAR', true, 255, null);
		$this->addColumn('POLL_START', 'PollStart', 'TIMESTAMP', true, null, null);
		$this->addColumn('POLL_END', 'PollEnd', 'TIMESTAMP', true, null, null);
		$this->addForeignKey('POLL_GAME_ID', 'PollGameId', 'INTEGER', 'ffb_game', 'GAME_ID', true, null, null);
		$this->addColumn('POLL_LOCATION', 'PollLocation', 'VARCHAR', true, 255, null);
		$this->addColumn('POLL_TYPE', 'PollType', 'VARCHAR', true, 255, null);
		$this->addColumn('POLL_VISIBLE', 'PollVisible', 'BOOLEAN', true, null, true);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbGame', 'FfbGame', RelationMap::MANY_TO_ONE, array('poll_game_id' => 'game_id', ), null, null);
    $this->addRelation('FfbPollResult', 'FfbPollResult', RelationMap::ONE_TO_MANY, array('poll_id' => 'poll_result_poll_id', ), null, null);
    $this->addRelation('FfbPollAnswer', 'FfbPollAnswer', RelationMap::ONE_TO_MANY, array('poll_id' => 'poll_answer_poll_id', ), null, null);
	} // buildRelations()

} // FfbPollTableMap
