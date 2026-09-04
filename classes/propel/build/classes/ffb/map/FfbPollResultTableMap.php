<?php



/**
 * This class defines the structure of the 'ffb_poll_result' table.
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
class FfbPollResultTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbPollResultTableMap';

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
		$this->setName('ffb_poll_result');
		$this->setPhpName('FfbPollResult');
		$this->setClassname('FfbPollResult');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('POLL_RESULT_ID', 'PollResultId', 'INTEGER', true, null, null);
		$this->addForeignKey('POLL_RESULT_POLL_ID', 'PollResultPollId', 'INTEGER', 'ffb_poll', 'POLL_ID', true, null, null);
		$this->addForeignKey('POLL_RESULT_USER_ID', 'PollResultUserId', 'INTEGER', 'web_user', 'USER_ID', true, null, null);
		$this->addForeignKey('POLL_RESULT_POLL_ANSWER_ID', 'PollResultPollAnswerId', 'INTEGER', 'ffb_poll_answer', 'POLL_ANSWER_ID', true, null, null);
		$this->addColumn('POLL_RESULT_TEXT', 'PollResultText', 'LONGVARCHAR', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbPoll', 'FfbPoll', RelationMap::MANY_TO_ONE, array('poll_result_poll_id' => 'poll_id', ), null, null);
    $this->addRelation('FfbPollAnswer', 'FfbPollAnswer', RelationMap::MANY_TO_ONE, array('poll_result_poll_answer_id' => 'poll_answer_id', ), null, null);
    $this->addRelation('WebUser', 'WebUser', RelationMap::MANY_TO_ONE, array('poll_result_user_id' => 'user_id', ), null, null);
	} // buildRelations()

} // FfbPollResultTableMap
