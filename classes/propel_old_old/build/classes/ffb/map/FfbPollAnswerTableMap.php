<?php


/**
 * This class defines the structure of the 'ffb_poll_answer' table.
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
class FfbPollAnswerTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPollAnswerTableMap';

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
		$this->setName('ffb_poll_answer');
		$this->setPhpName('FfbPollAnswer');
		$this->setClassname('FfbPollAnswer');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('POLL_ANSWER_ID', 'PollAnswerId', 'INTEGER', true, null, null);
		$this->addForeignKey('POLL_ANSWER_POLL_ID', 'PollAnswerPollId', 'INTEGER', 'ffb_poll', 'POLL_ID', true, null, null);
		$this->addColumn('POLL_ANSWER_TITLE', 'PollAnswerTitle', 'VARCHAR', true, 255, null);
		$this->addColumn('POLL_ANSWER_COUNT', 'PollAnswerCount', 'INTEGER', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPoll', 'FfbPoll', RelationMap::MANY_TO_ONE, array('poll_answer_poll_id' => 'poll_id', ), null, null);
    $this->addRelation('FfbPollResult', 'FfbPollResult', RelationMap::ONE_TO_MANY, array('poll_answer_id' => 'poll_result_poll_answer_id', ), null, null);
	} // buildRelations()

} // FfbPollAnswerTableMap
