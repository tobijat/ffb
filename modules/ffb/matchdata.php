<?php

/**
 * FFB-Module - MATCHDATA-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 09/2009
 * @version 0.1
 *
 */

class matchdata extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    public function getMatchData() {
        $match_data = array();
        $match_id = $_REQUEST['match_id'];
        $match_item = FfbMatchPeer::retrieveByPK($match_id);
        $match_data['match_id'] = $match_id;
        $match_data['match_hometeam_id'] = $match_item->getMatchHometeamId();
        $match_data['match_guestteam_id'] = $match_item->getMatchGuestteamId();
        $match_data['match_hometeam_name'] = $match_item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
        $match_data['match_guestteam_name'] = $match_item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
        $match_data['match_hometeam_nationality'] = $match_item->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
        $match_data['match_guestteam_nationality'] = $match_item->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();
        $match_data['match_hometeam_score'] = $match_item->getMatchHomescore();
        $match_data['match_guestteam_score'] = $match_item->getMatchGuestscore();
        $match_data['match_hometeam_score_penalty'] = $match_item->getMatchHomescorePenalty();
        $match_data['match_guestteam_score_penalty'] = $match_item->getMatchGuestscorePenalty();
        $match_data['match_minutes'] = $match_item->getMatchMinutes();
        $match_data['match_date'] = date('d.m.Y', strtotime($match_item->getMatchDate()));
        $match_data['match_matchround_name'] = $match_item->getFfbMatchround()->getMatchroundTitle();
        $match_data['match_matchround_id'] = $match_item->getMatchRound();
        $match_data['match_game_title'] = $match_item->getFfbMatchround()->getFfbGame()->getGameTitle();
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbGoalPeer::GOAL_MINUTE);
        $goal_items = $match_item->getFfbGoals($criteria);
        $i=0;
        $goal_data = array();
        foreach($goal_items as $item) {
            $goal_data[$i]['goal_minute'] = $item->getGoalMinute();
            $goal_data[$i]['goal_playerteam_id'] = $item->getGoalPlayerteamId();
            $goal_data[$i]['goal_team_id'] = $item->getFfbPlayerteam()->getPlayerteamTeamId();
            $goal_data[$i]['goal_team_name'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $goal_data[$i]['goal_player_name'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            if($item->getGoalOwngoal()) {
                $goal_data[$i]['goal_owngoal'] = $item->getGoalOwngoal();
            } else {
                $goal_data[$i]['goal_owngoal'] = 0;
            }
            if($item->getGoalPenalty()) {
                $goal_data[$i]['goal_penalty'] = $item->getGoalPenalty();
            } else {
                $goal_data[$i]['goal_penalty'] = 0;
            }
            if($item->getGoalPenaltyshootout()) {
                $goal_data[$i]['goal_penaltyshootout'] = $item->getGoalPenaltyshootout();
            } else {
                $goal_data[$i]['goal_penaltyshootout'] = 0;
            }
            $i++;
        }
        if(count($goal_data)>0) {
            $match_data['goal_data'] = $goal_data;
        } else {
            $match_data['goal_data'] = 0;
        }

        $criteria = new Criteria();
        $criteria->addJoin(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
        $criteria->addJoin(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, FfbTeamPeer::TEAM_ID);
        $criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_ID);
        $psgoal_items = $match_item->getFfbPsgoals($criteria);
        $i=0;
        $psgoal_data = array();
        foreach($psgoal_items as $item) {
            $psgoal_data[$i]['psgoal_minute'] = $item->getPsgoalMinute();
            $psgoal_data[$i]['psgoal_playerteam_id'] = $item->getPsgoalPlayerteamId();
            $psgoal_data[$i]['psgoal_team_id'] = $item->getFfbPlayerteam()->getPlayerteamTeamId();
            $psgoal_data[$i]['psgoal_team_name'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $psgoal_data[$i]['psgoal_team_nationality'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamNationality();
            $psgoal_data[$i]['psgoal_player_name'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            if($item->getPsgoalFail()) {
                $psgoal_data[$i]['psgoal_fail'] = $item->getPsgoalFail();
            } else {
                $psgoal_data[$i]['psgoal_fail'] = 0;
            }
            if($item->getPsgoalHit()) {
                $psgoal_data[$i]['psgoal_hit'] = $item->getPsgoalHit();
            } else {
                $psgoal_data[$i]['psgoal_hit'] = 0;
            }
            $i++;
        }
        if(count($psgoal_data)>0) {
            $match_data['psgoal_data'] = $psgoal_data;
        } else {
            $match_data['psgoal_data'] = 0;
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_CARDS, 'n', Criteria::NOT_EQUAL);
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID);
        $card_items = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
        $i=0;
        $card_data = array();
        foreach($card_items as $item) {
            $card_data[$i]['card_playerteam_id'] = $item->getPlayerstatsPlayerteamId();
            $card_data[$i]['card_team_id'] = $item->getFfbPlayerteam()->getPlayerteamTeamId();
            $card_data[$i]['card_team_name'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $card_data[$i]['card_player_name'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $card_data[$i]['card_type'] = $item->getPlayerstatsCards();
            $i++;
        }
        if(count($card_data)>0) {
            $match_data['card_data'] = $card_data;
        } else {
            $match_data['card_data'] = 0;
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $match_item->getMatchHometeamId());
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
        $player_items = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
        $i=0;
        $hometeam_players = array();
        if($player_items) {
            foreach($player_items as $item) {
                $hometeam_players[$i]['player_playerteam_id'] = $item->getPlayerstatsPlayerteamId();
                $hometeam_players[$i]['player_name'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                $hometeam_players[$i]['player_playerteam_position'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
                $hometeam_players[$i]['player_playerstats_minute_in'] = $item->getPlayerstatsMinuteIn();
                $hometeam_players[$i]['player_playerstats_minute_out'] = $item->getPlayerstatsMinuteOut();
                $hometeam_players[$i]['player_playerstats_minutes'] = $item->getPlayerstatsMinutes();
                $hometeam_players[$i]['player_playerstats_cards'] = $item->getPlayerstatsCards();
                $hometeam_players[$i]['player_playerstats_goals'] = $item->getPlayerstatsGoals();
                $hometeam_players[$i]['player_playerstats_owngoals'] = $item->getPlayerstatsOwngoals();
                $i++;
            }
            $match_data['hometeam_players'] = $hometeam_players;
        } else {
            $match_data['hometeam_players'] = 0;
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $match_item->getMatchGuestteamId());
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
        $player_items = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
        $i=0;
        $guestteam_players = array();
        if($player_items) {
            foreach($player_items as $item) {
                $guestteam_players[$i]['player_playerteam_id'] = $item->getPlayerstatsPlayerteamId();
                $guestteam_players[$i]['player_name'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                $guestteam_players[$i]['player_playerteam_position'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
                $guestteam_players[$i]['player_playerstats_minute_in'] = $item->getPlayerstatsMinuteIn();
                $guestteam_players[$i]['player_playerstats_minute_out'] = $item->getPlayerstatsMinuteOut();
                $guestteam_players[$i]['player_playerstats_minutes'] = $item->getPlayerstatsMinutes();
                $guestteam_players[$i]['player_playerstats_cards'] = $item->getPlayerstatsCards();
                $guestteam_players[$i]['player_playerstats_goals'] = $item->getPlayerstatsGoals();
                $guestteam_players[$i]['player_playerstats_owngoals'] = $item->getPlayerstatsOwngoals();
                $i++;
            }
            $match_data['guestteam_players'] = $guestteam_players;
        } else {
            $match_data['guestteam_players'] = 0;
        }

        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ID, $match_id, Criteria::NOT_EQUAL);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, 0, Criteria::GREATER_EQUAL);
        $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $match_data['match_hometeam_id']);
        $c1->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $match_data['match_guestteam_id']));
        $c2 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $match_data['match_hometeam_id']);
        $c2->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $match_data['match_guestteam_id']));
        $c2->addOr($c1);
        $criteria->add($c2);
        $criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);

        $previous_matches = FfbMatchPeer::doSelect($criteria);
        $prev_matches = array();
        //$this->num_pm = count($previous_matches);
        if($previous_matches) {
            $i=0;
            foreach($previous_matches as $item) {
                $prev_matches[$i]['match_id'] = $item->getMatchId();
                $prev_matches[$i]['match_date'] = date('d.m.Y', strtotime($item->getMatchDate()));
                $prev_matches[$i]['match_hometeam_id'] = $item->getMatchHometeamId();
                $prev_matches[$i]['match_guestteam_id'] = $item->getMatchGuestteamId();
                $prev_matches[$i]['match_hometeam_name'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                $prev_matches[$i]['match_guestteam_name'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
                $prev_matches[$i]['match_hometeam_nationality'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
                $prev_matches[$i]['match_guestteam_nationality'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();
                $prev_matches[$i]['match_hometeam_score'] = $item->getMatchHomescore();
                $prev_matches[$i]['match_guestteam_score'] = $item->getMatchGuestscore();
                $prev_matches[$i]['match_hometeam_score_penalty'] = $item->getMatchHomescorePenalty();
                $prev_matches[$i]['match_guestteam_score_penalty'] = $item->getMatchGuestscorePenalty();
                $prev_matches[$i]['match_matchround_name'] = $item->getFfbMatchround()->getMatchroundTitle();
                $prev_matches[$i]['match_matchround_id'] = $item->getMatchRound();
                $prev_matches[$i]['match_game_title'] = $item->getFfbMatchround()->getFfbGame()->getGameTitle();
                $i++;
            }
            $match_data['prev_matches'] = $prev_matches;
        } else {
            $match_data['prev_matches'] = 0;
        }

        $this->match_data = $match_data;
        //echo 'players home: '.count($hometeam_players).'<br>';
        //exit();
    }
}

?>
