<?php

/**
 * FFB-API-Module - TABLE-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 07/2009
 * @version 0.1
 *
 */

class table extends FFB_Auth_Api {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    //returns actual table for given games
    public function getTableForGame() {
        $game_id = $_REQUEST['game_id'];
        $game_ids = explode(',', trim($_REQUEST['game_id']));
        $type = $_REQUEST['type'];

        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::NOT_EQUAL);
        $criteria->add(FfbMatchPeer::MATCH_GUESTSCORE, -1, Criteria::NOT_EQUAL);
        $c1 = $criteria->getNewCriterion(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_ids[0]);
        if(count($game_ids) > 1) {
            for($i=1;$i<count($game_ids);$i++) {
                $c1->addOr($criteria->getNewCriterion(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_ids[$i]));
            }
        }
        $criteria->add($c1);

        $matchitems = FfbMatchPeer::doSelectJoinFfbMatchround($criteria);

        if($matchitems) {
            $this->actualDate = date("d.m.Y", strtotime($matchitems[0]->getMatchDate()));
            $matches = array();
            $teams = array();
            $i = 0;
            foreach($matchitems as $item) {
                $hometeam = $item->getFfbTeamRelatedByMatchHometeamId();
                $guestteam = $item->getFfbTeamRelatedByMatchGuestteamId();
                $hometeam_id = $hometeam->getTeamId();
                $guestteam_id = $guestteam->getTeamId();
                $teams[$hometeam_id]['team_name'] = $hometeam->getTeamName();
                $teams[$guestteam_id]['team_name'] = $guestteam->getTeamName();
                if(!$teams[$hometeam_id]['team_points']) {
                    $teams[$hometeam_id]['team_points'] = 0;
                }
                if(!$teams[$guestteam_id]['team_points']) {
                    $teams[$guestteam_id]['team_points'] = 0;
                }
                if(!$teams[$hometeam_id]['team_goals_shot']) {
                    $teams[$hometeam_id]['team_goals_shot'] = 0;
                }
                if(!$teams[$guestteam_id]['team_goals_shot']) {
                    $teams[$guestteam_id]['team_goals_shot'] = 0;
                }
                if(!$teams[$hometeam_id]['team_goals_got']) {
                    $teams[$hometeam_id]['team_goals_got'] = 0;
                }
                if(!$teams[$guestteam_id]['team_goals_got']) {
                    $teams[$guestteam_id]['team_goals_got'] = 0;
                }
                if(!$teams[$hometeam_id]['team_num_matches']) {
                    $teams[$hometeam_id]['team_num_matches'] = 0;
                }
                if(!$teams[$guestteam_id]['team_num_matches']) {
                    $teams[$guestteam_id]['team_num_matches'] = 0;
                }
                if(!$teams[$hometeam_id]['team_wins']) {
                    $teams[$hometeam_id]['team_wins'] = 0;
                }
                if(!$teams[$guestteam_id]['team_wins']) {
                    $teams[$guestteam_id]['team_wins'] = 0;
                }
                if(!$teams[$hometeam_id]['team_fails']) {
                    $teams[$hometeam_id]['team_fails'] = 0;
                }
                if(!$teams[$guestteam_id]['team_fails']) {
                    $teams[$guestteam_id]['team_fails'] = 0;
                }
                if(!$teams[$hometeam_id]['team_equals']) {
                    $teams[$hometeam_id]['team_equals'] = 0;
                }
                if(!$teams[$guestteam_id]['team_equals']) {
                    $teams[$guestteam_id]['team_equals'] = 0;
                }
                if($item->getMatchHomescore() > $item->getMatchGuestscore()) {
                    if($type=='a') {
                        $teams[$guestteam_id]['team_fails']++;
                    } elseif($type=='h') {
                        $teams[$hometeam_id]['team_points'] += 3;
                        $teams[$hometeam_id]['team_wins']++;
                    } else {
                        $teams[$hometeam_id]['team_points'] += 3;
                        $teams[$hometeam_id]['team_wins']++;
                        $teams[$guestteam_id]['team_fails']++;
                    }
                } elseif($item->getMatchGuestscore() > $item->getMatchHomescore()) {
                    if($type=='a') {
                        $teams[$guestteam_id]['team_points'] += 3;
                        $teams[$guestteam_id]['team_wins']++;
                    } elseif($type=='h') {
                        $teams[$hometeam_id]['team_fails']++;
                    } else {
                        $teams[$guestteam_id]['team_points'] += 3;
                        $teams[$guestteam_id]['team_wins']++;
                        $teams[$hometeam_id]['team_fails']++;
                    }
                } elseif($item->getMatchGuestscore() == $item->getMatchHomescore()) {
                    if($type=='a') {
                        $teams[$guestteam_id]['team_points'] += 1;
                        $teams[$guestteam_id]['team_equals']++;
                    } elseif($type=='h') {
                        $teams[$hometeam_id]['team_points'] += 1;
                        $teams[$hometeam_id]['team_equals']++;
                    } else {
                        $teams[$hometeam_id]['team_points'] += 1;
                        $teams[$guestteam_id]['team_points'] += 1;
                        $teams[$hometeam_id]['team_equals']++;
                        $teams[$guestteam_id]['team_equals']++;
                    }
                }
                if($type=='a') {
                    $teams[$guestteam_id]['team_goals_shot'] += $item->getMatchGuestscore();
                    $teams[$guestteam_id]['team_goals_got'] += $item->getMatchHomescore();
                    $teams[$guestteam_id]['team_num_matches']++;
                } elseif($type=='h') {
                    $teams[$hometeam_id]['team_goals_shot'] += $item->getMatchHomescore();
                    $teams[$hometeam_id]['team_goals_got'] += $item->getMatchGuestscore();
                    $teams[$hometeam_id]['team_num_matches']++;
                } else {
                    $teams[$hometeam_id]['team_goals_shot'] += $item->getMatchHomescore();
                    $teams[$guestteam_id]['team_goals_shot'] += $item->getMatchGuestscore();
                    $teams[$hometeam_id]['team_goals_got'] += $item->getMatchGuestscore();
                    $teams[$guestteam_id]['team_goals_got'] += $item->getMatchHomescore();
                    $teams[$hometeam_id]['team_num_matches']++;
                    $teams[$guestteam_id]['team_num_matches']++;
                }

                $matches[$i]['match_id'] = $item->getMatchId();
                $i++;
            }
        } else {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
            $c1 = $criteria->getNewCriterion(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_ids[0]);
            if(count($game_ids) > 1) {
                for($i=1;$i<count($game_ids);$i++) {
                    $c1->addOr($criteria->getNewCriterion(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_ids[$i]));
                }
            }
            $criteria->add($c1);
            $matchitems = FfbMatchPeer::doSelectJoinFfbMatchround($criteria);
            if($matchitems) {
                $this->actualDate = date("d.m.Y", time());
                $matches = array();
                $teams = array();
                $i = 0;
                foreach($matchitems as $item) {
                    $hometeam = $item->getFfbTeamRelatedByMatchHometeamId();
                    $guestteam = $item->getFfbTeamRelatedByMatchGuestteamId();
                    $hometeam_id = $hometeam->getTeamId();
                    $guestteam_id = $guestteam->getTeamId();
                    $teams[$hometeam_id]['team_name'] = $hometeam->getTeamName();
                    $teams[$guestteam_id]['team_name'] = $guestteam->getTeamName();
                    if(!$teams[$hometeam_id]['team_points']) {
                        $teams[$hometeam_id]['team_points'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_points']) {
                        $teams[$guestteam_id]['team_points'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_goals_shot']) {
                        $teams[$hometeam_id]['team_goals_shot'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_goals_shot']) {
                        $teams[$guestteam_id]['team_goals_shot'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_goals_got']) {
                        $teams[$hometeam_id]['team_goals_got'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_goals_got']) {
                        $teams[$guestteam_id]['team_goals_got'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_num_matches']) {
                        $teams[$hometeam_id]['team_num_matches'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_num_matches']) {
                        $teams[$guestteam_id]['team_num_matches'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_wins']) {
                        $teams[$hometeam_id]['team_wins'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_wins']) {
                        $teams[$guestteam_id]['team_wins'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_fails']) {
                        $teams[$hometeam_id]['team_fails'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_fails']) {
                        $teams[$guestteam_id]['team_fails'] = 0;
                    }
                    if(!$teams[$hometeam_id]['team_equals']) {
                        $teams[$hometeam_id]['team_equals'] = 0;
                    }
                    if(!$teams[$guestteam_id]['team_equals']) {
                        $teams[$guestteam_id]['team_equals'] = 0;
                    }
                    $matches[$i]['match_id'] = $item->getMatchId();
                    $i++;
                }
            }
        }
        $table = array();
        $points = array();
        $names = array();
        $balance = array();
        $goals_shot = array();
        $goals_got = array();
        $i=0;
        foreach($teams as $team_id=>$value) {
            //echo $team_id.': '.$teams[$team_id]['team_name'].'<br>';
            $table[$i]['team_name'] = $teams[$team_id]['team_name'];
            $names[] = strtolower($teams[$team_id]['team_name']);
            $table[$i]['team_id'] = $team_id;
            $table[$i]['team_points'] = $points[] = $teams[$team_id]['team_points'];
            $table[$i]['team_goals_shot'] = $goals_shot[] = $teams[$team_id]['team_goals_shot'];
            $table[$i]['team_goals_got'] = $goals_got[] = $teams[$team_id]['team_goals_got'];
            $table[$i]['team_num_matches'] = $teams[$team_id]['team_num_matches'];
            $table[$i]['team_wins'] = $teams[$team_id]['team_wins'];
            $table[$i]['team_fails'] = $teams[$team_id]['team_fails'];
            $table[$i]['team_equals'] = $teams[$team_id]['team_equals'];
            $table[$i]['team_balance'] = $balance[] = $teams[$team_id]['team_goals_shot']-$teams[$team_id]['team_goals_got'];

            $i++;
        }
        array_multisort($points, SORT_DESC, $balance, SORT_DESC, $goals_shot, SORT_DESC, $goals_got, SORT_DESC, $names, SORT_ASC, SORT_STRING, $table);
        /*
        $i=1;
        for($i=1;$i<=count($table);$i++) {
            echo $i.'. '.$table[$i-1]['team_name'].' '.$table[$i-1]['team_points'].' '.$table[$i-1]['team_balance'].'<br>';
        }
        exit();
        */
        $this->numResults = $i;
        $this->table = $table;
    }
}

?>
