<?php

/**
 * FFB-API-Module - MATCHDATA-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 07/2009
 * @version 0.1
 *
 */

class matchdata extends FFB_Auth_Api {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    //returns cards statistic for given match
    public function getCards() {
        $game_id = $_REQUEST['game_id'];
        $match_id = $_REQUEST['match_id'];
        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_CARDS, 'n', Criteria::NOT_EQUAL);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID);
        $playerstats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        $player = array();
        $i=0;
        foreach($playerstats as $item) {
            $playerteam_id = $item->getPlayerstatsPlayerteamId();
            $player[$i]['playerteam_id'] = $playerteam_id;
            $player_name = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $player[$i]['player_name'] = $player_name;
            $player[$i]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
            $player[$i]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $player[$i]['playerteam_position'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
            $player[$i]['player_teamname'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $playerteam_team_id = $item->getFfbPlayerteam()->getPlayerteamTeamId();
            if($item->getFfbPlayerteam()->getPlayerteamPlayerPicture()) {
                $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$playerteam_team_id.'/'.$item->getFfbPlayerteam()->getPlayerteamPlayerPicture();
            } else {
                $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
            }
            $player[$i]['player_card'] = $item->getPlayerstatsCards();
            $i++;
        }
        $this->numResults = $i;
        $this->stats = $player;
    }
    //returns goals statistic for given match
    public function getGoals() {
        $game_id = $_REQUEST['game_id'];
        $match_id = $_REQUEST['match_id'];
        $criteria = new Criteria();
        $criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $game_id);
        $criteria->setLimit(1);
        $options_items = FfbOptionsPeer::doSelect($criteria);
        $pm = $options_items[0]->getOptionsGamePointsmode();
        if($pm == 'old') {
            $criteria = new Criteria();
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
            $c1 = $criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_GOALS, 0, Criteria::GREATER_THAN);
            $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_OWNGOALS, 0, Criteria::GREATER_THAN));
            $criteria->add($c1);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID);
            $playerstats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
            $player = array();
            $i=0;
            foreach($playerstats as $item) {
                $playerteam_id = $item->getPlayerstatsPlayerteamId();
                $player[$i]['playerteam_id'] = $playerteam_id;
                $player_name = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                $player[$i]['player_name'] = $player_name;
                $player[$i]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                $player[$i]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                $player[$i]['playerteam_position'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
                $player[$i]['player_teamname'] = $item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
                $playerteam_team_id = $item->getFfbPlayerteam()->getPlayerteamTeamId();
                if($item->getFfbPlayerteam()->getPlayerteamPlayerPicture()) {
                    $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$playerteam_team_id.'/'.$item->getFfbPlayerteam()->getPlayerteamPlayerPicture();
                } else {
                    $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
                }
                $player[$i]['player_goals'] = $item->getPlayerstatsGoals();
                $player[$i]['player_owngoals'] = $item->getPlayerstatsOwngoals();
                $i++;
            }
        } elseif($pm == 'new') {
            $criteria = new Criteria();
            $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);
            $criteria->addAscendingOrderByColumn(FfbGoalPeer::GOAL_MINUTE);
            $goal_items = FfbGoalPeer::doSelect($criteria);
            $i=0;
            if($goal_items) {
                foreach($goal_items as $goal_item) {
                    $goal_string = '';
                    $playerteam_id = $goal_item->getFfbPlayerteam()->getPlayerteamId();
                    $player[$i]['playerteam_id'] = $playerteam_id;
                    $player_name = $goal_item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$goal_item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player[$i]['player_name'] = $player_name;
                    $player[$i]['player_fname'] = $goal_item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                    $player[$i]['player_lname'] = $goal_item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player[$i]['playerteam_position'] = $goal_item->getFfbPlayerteam()->getPlayerteamPlayerPosition();
                    $player[$i]['player_teamname'] = $goal_item->getFfbPlayerteam()->getFfbTeam()->getTeamName();
                    $playerteam_team_id = $goal_item->getFfbPlayerteam()->getPlayerteamTeamId();
                    if($goal_item->getFfbPlayerteam()->getPlayerteamPlayerPicture()) {
                        $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$playerteam_team_id.'/'.$goal_item->getFfbPlayerteam()->getPlayerteamPlayerPicture();
                    } else {
                        $player[$i]['playerteam_picture'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
                    }
                    if($goal_item->getGoalOwngoal() == 0) {
                        $player[$i]['player_goals'] = 1;
                    } else {
                        $player[$i]['player_owngoals'] = 1;
                    }
                    $goal_string .= $goal_item->getGoalMinute().'.';
                    $player[$i]['player_goals_minutes'] = $goal_string;

                    $i++;
                }
            }
        }

        $this->numResults = $i;
        $this->stats = $player;
    }
}

?>
