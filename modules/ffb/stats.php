<?php

/**
 * FFB-Module - statistics class
 *
 * @author Gritschacher, Musser
 * @copyright 06/2008
 * @version 0.3
 *
 */

class stats extends FFB_Auth_No {

	private $startTime = 0;

    public function __construct() {
        parent::__construct();
		
		$this->startTime = microtime(TRUE);
		$this->adStatsSlot	=	$this->advert->getAd('StatsSlot');
    }
	
	public function __destruct() {
    }
	
	
	private function duration() {
		$this->duration = round((microtime(TRUE) - $this->startTime), 5);
	}
	

    public function __default() {
        $this->htmlFile = 'stats.php';
		$this->htmlTitle = 'Statistics Corner';
    }
	
	
	public function getTeams() {
		$criteria = new Criteria();
		$criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
		$teams = FfbTeamPeer::doSelect($criteria);
		$tms = array();
		foreach($teams as $team)
		{
			$tmpTeam = array();
			$tmpTeam['team_id'] 		= $team->getTeamId();
			$tmpTeam['team_name'] 		= $team->getTeamName();
			$tmpTeam['team_nationality']= $team->getTeamNationality();
			$tms[] = $tmpTeam;
		}
		$this->teams = $tms;
		$this->duration();
	}
	
	public function getLeagues() {
		$criteria = new Criteria();
		$criteria->addAscendingOrderByColumn(FfbGamePeer::GAME_TITLE);
		$criteria->add(FfbGamePeer::GAME_VISIBLE, 1, Criteria::EQUAL);
		$leagues = FfbGamePeer::doSelect($criteria);
		$lgs = array();
		foreach($leagues as $league)
		{
			$tmpLeague = array();
			$tmpLeague['game_id'] 	= $league->getGameId();
			$tmpLeague['game_name'] = $league->getGameTitle();
			$lgs[] = $tmpLeague;
		}
		$this->leagues = $lgs;
		$this->duration();
		
	}

	public function getTeamPlayers() {
		$team_id = isset($_REQUEST['team_id']) ? $_REQUEST['team_id'] : null;
		if (!$team_id) {
			$this->players = array();
			$this->duration();
			return;
		}
        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $playerteam_items = FfbPlayerteamPeer::doSelectJoinFfbPlayer($criteria);
		//print_r($playerteam_items);
		$players = array();
		$i=0;
		foreach ($playerteam_items AS $item) {
			$player = array();
			$players[$i]['player_id'] 					= $item->getFfbPlayer()->getPlayerId();
            $players[$i]['player_fname'] 				= $item->getFfbPlayer()->getPlayerFname();
            $players[$i]['player_lname'] 				= $item->getFfbPlayer()->getPlayerLname();
            $players[$i]['player_nationality'] 			= $item->getFfbPlayer()->getPlayerNationality();
			$players[$i]['playerteam_player_position'] 	= $item->getPlayerteamPlayerPosition();
            $players[$i]['playerteam_player_picture'] 	= $item->getPlayerteamPlayerPicture();
			$players[$i]['playerteam_id'] 				= $item->getPlayerteamId();
			if(!$players[$i]['playerteam_player_picture'])
				$players[$i]['playerteam_player_picture'] = "../image_na.gif";
			
			$i++;			
		}

		$this->players = $players;
		$this->duration();	
	}

	public function getLeagueMatches() {
		$criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $_REQUEST['game_id']);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
		$matches_items = FfbMatchroundPeer::doSelect($criteria);
		//print_r($matches_items);
		$matches = array();
		$i=0;
		foreach($matches_items as $item)
		{
			$matches[$i]['round']['mr_title'] = $item->getMatchroundTitle();
			$date = new DateTime($item->getMatchroundStartdate());
			$matches[$i]['round']['mr_sdate'] = $date->format("d.m.Y");
			$criteria = new Criteria();
			$criteria->add(FfbMatchPeer::MATCH_ROUND, $item->getMatchroundId());
			$criteria->addAscendingOrderByColumn(FfbMatchPeer::MATCH_DATE); 
			$matchround_items = FfbMatchPeer::doSelect($criteria);
			$j=0;
			foreach($matchround_items as $mr_items)
			{
				$matches[$i]['round']['match'][$j]['h_id'] 		= $mr_items->getMatchHometeamId();
				$matches[$i]['round']['match'][$j]['g_id']		= $mr_items->getMatchGuestteamId();
				$matches[$i]['round']['match'][$j]['h_score'] 	= $mr_items->getMatchHomescore();
				$matches[$i]['round']['match'][$j]['g_score'] 	= $mr_items->getMatchGuestscore();
				$matches[$i]['round']['match'][$j]['h_penalty'] = $mr_items->getMatchHomescorePenalty();
				$matches[$i]['round']['match'][$j]['g_penalty'] = $mr_items->getMatchGuestscorePenalty();
				$matches[$i]['round']['match'][$j]['match_minutes'] = $mr_items->getMatchMinutes();
				$matches[$i]['round']['match'][$j]['h_name'] 	= $mr_items->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
				$matches[$i]['round']['match'][$j]['g_name'] 	= $mr_items->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
				$matches[$i]['round']['match'][$j]['match_id'] 	= $mr_items->getMatchId();
				$date = new DateTime($mr_items->getMatchDate());
				$matches[$i]['round']['match'][$j]['m_date'] 	= $date->format("d.m.Y");
				$j++; 
			}

			$i++;
		}
		$this->matches = $matches;
		$this->duration();
	}
	
	public function getPlayerOverallStats() { 
		$criteria = new Criteria();
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, intval($_REQUEST['player_id']));
		$playerteam_ids = FfbPlayerteamPeer::doSelect($criteria);
		
		$playerOverallStats = array();
		$i=0;
		$playerOverallStats['player_id'] = intval($_REQUEST['player_id']);
		$playerOverallStats['playedMatches'] = 0;
		$playerOverallStats['playedMinutes'] = 0;
		$playerOverallStats['cards'] = array();
		$playerOverallStats['cards']['y'] = 0;
		$playerOverallStats['cards']['r'] = 0;
		$playerOverallStats['cards']['yr'] = 0;
		$playerOverallStats['goals'] = 0;
		$playerOverallStats['owngoals'] = 0;
		//$playerOverallStats['lineups'] = 0;
		$playerOverallStats['penaltiesLost'] = 0;
		$playerOverallStats['penaltiesSaved'] = 0;
		$playerOverallStats['assists'] = 0;
		$playerOverallStats['score'] = 0;
		
		
		foreach($playerteam_ids as $ids)
		{
			//$playerOverallStats[$i]['team_name'] = $ids->getFfbTeamRelatedByPlayerteamTeamId()->getTeamName();
			//print_r($ids);
			$criteria = new Criteria();
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $ids->getPlayerteamId());
			//$criteria->addOr(FfbMatchPeer::MATCH_GUESTTEAM_ID, $ids->getPlayerteamTeamId());
			//$criteria->addAscendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
			$playerstats = FfbPlayerstatsPeer::doSelect($criteria);
			foreach($playerstats as $stats)
			{
				$playerOverallStats['playedMatches'] += 1;
				$playerOverallStats['playedMinutes'] += $stats->getPlayerstatsMinutes();
				switch ($stats->getPlayerstatsCards())
				{
					case 'n':
						break;
					case 'y':
						$playerOverallStats['cards']['y'] += 1;
						break;
					case 'yr':
						$playerOverallStats['cards']['yr'] += 1;
						break;
					case 'r':
						$playerOverallStats['cards']['r'] += 1;
						break;
					default:
						break;
				}
				
				$playerOverallStats['goals'] += $stats->getPlayerstatsGoals();
				$playerOverallStats['owngoals'] += $stats->getPlayerstatsOwngoals();
				$playerOverallStats['penaltiesLost'] += $stats->getPlayerstatsPenaltieslost();
				$playerOverallStats['penaltiesSaved'] += $stats->getPlayerstatsPenaltiessaved();
				$playerOverallStats['assists'] += $stats->getPlayerstatsAssists();
				$playerOverallStats['score'] += $stats->getPlayerstatsScore();
			}
			//print_r($matches);
			//$i++;
		}
		
		//print_r($playerOverallStats);
		$this->playerOverallStats = $playerOverallStats;
		
		
		//print_r($playerteam_ids);
	}
	
    public function getBestPlayers() {
    	$criteria = new Criteria();

    	$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
    	//$criteria->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
    	//$c1 = $criteria

    	$players = FfbPlayerstatsPeer::doSelect($criteria);
    	$playerteamids = array();
    	$i=0;
    	foreach($players as $player){
    		$playerteamids[$i]['playerteam_id'] = $player->getPlayerstatsPlayerteamId();
			//echo $playerteamids[$i];
			$i++;
		}
		//echo $playerteamids;
		$this->playerteamids = $playerteamids;
	}


}

?>