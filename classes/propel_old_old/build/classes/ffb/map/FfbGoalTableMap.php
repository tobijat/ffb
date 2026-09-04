<?php


/**
 * This class defines the structure of the 'ffb_goal' table.
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
class FfbGoalTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbGoalTableMap';

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
		$this->setName('ffb_goal');
		$this->setPhpName('FfbGoal');
		$this->setClassname('FfbGoal');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('GOAL_ID', 'GoalId', 'INTEGER', true, null, null);
		$this->addForeignKey('GOAL_MATCH_ID', 'GoalMatchId', 'INTEGER', 'ffb_match', 'MATCH_ID', true, null, null);
		$this->addForeignKey('GOAL_PLAYERTEAM_ID', 'GoalPlayerteamId', 'INTEGER', 'ffb_playerteam', 'PLAYERTEAM_ID', true, null, null);
		$this->addColumn('GOAL_MINUTE', 'GoalMinute', 'INTEGER', true, null, null);
		$this->addColumn('GOAL_OWNGOAL', 'GoalOwngoal', 'BOOLEAN', true, null, false);
		$this->addColumn('GOAL_PENALTY', 'GoalPenalty', 'BOOLEAN', true, null, false);
		$this->addColumn('GOAL_PENALTYSHOOTOUT', 'GoalPenaltyshootout', 'BOOLEAN', true, null, false);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbMatch', 'FfbMatch', RelationMap::MANY_TO_ONE, array('goal_match_id' => 'match_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerteam', 'FfbPlayerteam', RelationMap::MANY_TO_ONE, array('goal_playerteam_id' => 'playerteam_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbGoalTableMap
