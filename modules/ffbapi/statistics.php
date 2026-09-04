<?php

/**
 * FFB-API-Module - STATISTICS-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 07/2009
 * @version 0.1
 *
 */

class statistics extends FFB_Auth_Api {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    //returns cards statistic for given games
    public function getCards() {
        $game_id = $_REQUEST['game_id'];
        $team_id = $_REQUEST['team_id'];
        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_CARDS, 'n', Criteria::NOT_EQUAL);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        if($team_id) {
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        }
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $playerstats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        $player = array();
        foreach($playerstats as $item) {
        	$criteria = new Criteria();
        	$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER);
        	$pts = $item->getFfbPlayerteam()->getFfbPlayer()->getFfbPlayerteams($criteria);
			$pt = $pts[0];
            $playerteam_id = $pt->getPlayerteamId();
            $player[$playerteam_id]['playerteam_id'] = $playerteam_id;
            $player_name = $pt->getFfbPlayer()->getPlayerFname().' '.$pt->getFfbPlayer()->getPlayerLname();
            $player[$playerteam_id]['player_name'] = $player_name;
            $player[$playerteam_id]['player_fname'] = $pt->getFfbPlayer()->getPlayerFname();
            $player[$playerteam_id]['player_lname'] = $pt->getFfbPlayer()->getPlayerLname();
            $player[$playerteam_id]['playerteam_position'] = $pt->getPlayerteamPlayerPosition();
            $player[$playerteam_id]['player_teamname'] = $pt->getFfbTeam()->getTeamName();
            $playerteam_team_id = $pt->getPlayerteamTeamId();
            if($pt->getPlayerteamPlayerPicture()) {
                $player[$playerteam_id]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$playerteam_team_id.'/'.$pt->getPlayerteamPlayerPicture();
            } else {
                $player[$playerteam_id]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
            }
            $card = $item->getPlayerstatsCards();
            if(!$player[$playerteam_id]['cards_y']) {
                $player[$playerteam_id]['cards_y'] = 0;
            }
            if(!$player[$playerteam_id]['cards_yr']) {
                $player[$playerteam_id]['cards_yr'] = 0;
            }
            if(!$player[$playerteam_id]['cards_r']) {
                $player[$playerteam_id]['cards_r'] = 0;
            }
            if($card == 'y') {
                $player[$playerteam_id]['cards_y']++;
            } elseif($card == 'yr') {
                $player[$playerteam_id]['cards_yr']++;
            } elseif($card == 'r') {
                $player[$playerteam_id]['cards_r']++;
            }
        }
        $i=0;
        $stats = array();
        $fnames = array();
        $lnames = array();
        $num_cards_y = array();
        $num_cards_yr = array();
        $num_cards_r = array();
        foreach($player as $id=>$value) {
            $stats[$i]['playerteam_id'] = $value['playerteam_id'];
            $stats[$i]['player_fname'] = $fnames[] = $value['player_fname'];
            $stats[$i]['player_lname'] = $lnames[] = $value['player_lname'];
            $stats[$i]['playerteam_position'] = $value['playerteam_position'];
            $stats[$i]['playerteam_picture'] = $value['playerteam_picture'];
            $stats[$i]['player_teamname'] = $value['player_teamname'];
            $stats[$i]['player_name'] = $value['player_name'];
            $stats[$i]['num_cards_y'] = $num_cards_y[] = $value['cards_y'];
            $stats[$i]['num_cards_yr'] = $num_cards_yr[] = $value['cards_yr'];
            $stats[$i]['num_cards_r'] = $num_cards_r[] = $value['cards_r'];
            $i++;
        }
        array_multisort($num_cards_r, SORT_DESC, $num_cards_yr, SORT_DESC, $num_cards_y, SORT_DESC, $lnames, SORT_ASC, SORT_STRING, $fnames, SORT_ASC, SORT_STRING, $stats);
        /*
        $i=1;
        for($i=1;$i<=count($stats);$i++) {
            echo $i.'. '.$stats[$i-1]['player_name'].' '.$stats[$i-1]['num_cards_r'].' '.$stats[$i-1]['num_cards_yr'].' '.$stats[$i-1]['num_cards_y'].'<br>';
        }
        exit();
        */
        $this->numResults = $i;
        $this->stats = $stats;
    }

    //returns cards statistic for given team
    public function getCardsForTeam() {

    }

    //returns goals statistic for given games
    public function getGoals() {
        $game_id = $_REQUEST['game_id'];
        $team_id = $_REQUEST['team_id'];
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        if($team_id) {
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        }
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $c1 = $criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, 0, Criteria::GREATER_THAN);
        $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_ASSISTS, 0, Criteria::GREATER_THAN));
        $criteria->add($c1);
        $playerstats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        $player = array();
        foreach($playerstats as $item) {
        	$criteria = new Criteria();
        	$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER);
        	$pts = $item->getFfbPlayerteam()->getFfbPlayer()->getFfbPlayerteams($criteria);
			$pt = $pts[0];
            //$playerteam_id = $item->getPlayerstatsPlayerteamId();
            $playerteam_id = $pt->getPlayerteamId();
            //$player_name = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $player_name = $pt->getFfbPlayer()->getPlayerFname().' '.$pt->getFfbPlayer()->getPlayerLname();
            $player[$playerteam_id]['playerteam_id'] = $playerteam_id;
            $player[$playerteam_id]['player_name'] = $player_name;
            //$player[$playerteam_id]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
            $player[$playerteam_id]['player_fname'] = $pt->getFfbPlayer()->getPlayerFname();
            //$player[$playerteam_id]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $player[$playerteam_id]['player_lname'] = $pt->getFfbPlayer()->getPlayerLname();
            //$player[$playerteam_id]['playerteam_position'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
            $player[$playerteam_id]['playerteam_position'] = $pt->getPlayerteamPlayerPosition();
            //$player[$playerteam_id]['player_teamname'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $player[$playerteam_id]['player_teamname'] = $pt->getFfbTeam()->getTeamName();
            //$playerteam_team_id = $item->getFfbPlayerteam()->getPlayerteamTeamId();
            $playerteam_team_id = $pt->getPlayerteamTeamId();
            if($pt->getPlayerteamPlayerPicture()) {
                $player[$playerteam_id]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$playerteam_team_id.'/'.$pt->getPlayerteamPlayerPicture();
            } else {
                $player[$playerteam_id]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
            }
            $goals = $item->getPlayerstatsGoals();
            $assists = $item->getPlayerstatsAssists();
            if(!$player[$playerteam_id]['goals']) {
                $player[$playerteam_id]['goals'] = 0;
            }
            if(!$player[$playerteam_id]['assists']) {
                $player[$playerteam_id]['assists'] = 0;
            }
            $player[$playerteam_id]['goals'] += $goals;
            $player[$playerteam_id]['assists'] += $assists;
        }
        $i=0;
        $stats = array();
        $fnames = array();
        $lnames = array();
        $num_goals = array();
        $num_assists = array();
        foreach($player as $id=>$value) {
            $stats[$i]['playerteam_id'] = $value['playerteam_id'];
            $stats[$i]['player_fname'] = $fnames[] = $value['player_fname'];
            $stats[$i]['player_lname'] = $lnames[] = $value['player_lname'];
            $stats[$i]['playerteam_position'] = $value['playerteam_position'];
            $stats[$i]['playerteam_picture'] = $value['playerteam_picture'];
            $stats[$i]['player_teamname'] = $value['player_teamname'];
            $stats[$i]['player_name'] = $value['player_name'];
            $stats[$i]['num_goals'] = $num_goals[] = $value['goals'];
            $stats[$i]['num_assists'] = $num_assists[] = $value['assists'];
            $stats[$i]['num_scores'] = $value['goals'] + $value['assists'];
            $i++;
        }
        array_multisort($num_goals, SORT_DESC, $num_assists, SORT_DESC, $lnames, SORT_ASC, SORT_STRING, $fnames, SORT_ASC, SORT_STRING, $stats);
        /*
        $i=1;
        for($i=1;$i<=count($stats);$i++) {
            echo $i.'. '.$stats[$i-1]['player_name'].' '.$stats[$i-1]['num_goals'].' '.$stats[$i-1]['num_assists'].'<br>';
        }
        exit();
        */
        $this->numResults = $i;
        $this->stats = $stats;
    }
}

?>
