<?php


/**
 * This class defines the structure of the 'ffb_match' table.
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
class FfbMatchTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'ffb.map.FfbMatchTableMap';

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
		$this->setName('ffb_match');
		$this->setPhpName('FfbMatch');
		$this->setClassname('FfbMatch');
		$this->setPackage('ffb');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('MATCH_ID', 'MatchId', 'INTEGER', true, null, null);
		$this->addForeignKey('MATCH_ROUND', 'MatchRound', 'INTEGER', 'ffb_matchround', 'MATCHROUND_ID', true, null, null);
		$this->addForeignKey('MATCH_HOMETEAM_ID', 'MatchHometeamId', 'INTEGER', 'ffb_team', 'TEAM_ID', true, null, null);
		$this->addForeignKey('MATCH_GUESTTEAM_ID', 'MatchGuestteamId', 'INTEGER', 'ffb_team', 'TEAM_ID', true, null, null);
		$this->addColumn('MATCH_HOMESCORE', 'MatchHomescore', 'VARCHAR', false, 255, '');
		$this->addColumn('MATCH_GUESTSCORE', 'MatchGuestscore', 'VARCHAR', false, 255, '');
		$this->addColumn('MATCH_HOMESCORE_PENALTY', 'MatchHomescorePenalty', 'VARCHAR', false, 255, '');
		$this->addColumn('MATCH_GUESTSCORE_PENALTY', 'MatchGuestscorePenalty', 'VARCHAR', false, 255, '');
		$this->addColumn('MATCH_DATE', 'MatchDate', 'TIMESTAMP', true, null, null);
		$this->addColumn('MATCH_MINUTES', 'MatchMinutes', 'INTEGER', true, null, 0);
		$this->addColumn('MATCH_STATUS', 'MatchStatus', 'VARCHAR', false, 255, '');
		$this->addColumn('MATCH_URL', 'MatchUrl', 'VARCHAR', false, 255, '');
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('FfbMatchround', 'FfbMatchround', RelationMap::MANY_TO_ONE, array('match_round' => 'matchround_id', ), 'CASCADE', null);
    $this->addRelation('FfbTeamRelatedByMatchHometeamId', 'FfbTeam', RelationMap::MANY_TO_ONE, array('match_hometeam_id' => 'team_id', ), 'CASCADE', null);
    $this->addRelation('FfbTeamRelatedByMatchGuestteamId', 'FfbTeam', RelationMap::MANY_TO_ONE, array('match_guestteam_id' => 'team_id', ), 'CASCADE', null);
    $this->addRelation('FfbGoal', 'FfbGoal', RelationMap::ONE_TO_MANY, array('match_id' => 'goal_match_id', ), 'CASCADE', null);
    $this->addRelation('FfbPsgoal', 'FfbPsgoal', RelationMap::ONE_TO_MANY, array('match_id' => 'psgoal_match_id', ), 'CASCADE', null);
    $this->addRelation('FfbPlayerstats', 'FfbPlayerstats', RelationMap::ONE_TO_MANY, array('match_id' => 'playerstats_match_id', ), 'CASCADE', null);
	} // buildRelations()

} // FfbMatchTableMap
