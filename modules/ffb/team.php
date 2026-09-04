<?php

/**
 * FFB-Module - TEAM-Klasse;
 * kann alle Teams oder ein spezielles Team zurückgeben
 *
 * @author Gritschacher, Musser
 * @copyright 06/2008
 * @version 0.3
 *
 */

class team extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();

        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }


    //returns a list of teams for given matchround
    public function getTeamsForRound() {
        $matchround_id = $_POST['matchround_id'];
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $matchround_id);
        $criteria->add(FfbMatchPeer::MATCH_STATUS, '');
        $matches = FfbMatchPeer::doSelect($criteria);

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
        //$c1 = $criteria->getNewCriterion();
        if($matches) {
            foreach($matches as $match) {
                $c1 = $criteria->getNewCriterion(FfbTeamPeer::TEAM_ID, $match->getMatchHometeamId());
                $c1->addOr($criteria->getNewCriterion(FfbTeamPeer::TEAM_ID, $match->getMatchGuestteamId()));
                $criteria->addOr($c1);
            }
        } else {
            $criteria->add(FfbTeamPeer::TEAM_ID, 0); //unmögliches criterium, damit keine Teams gefunden werden
        }
        $this->getTeamsByCriteria($criteria);
    }

    //returns a list of all available teams
    public function getList() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
        $this->getTeamsByCriteria($criteria);
    }

    //returns teamdetails for given team
    public function getItem() {
        $teams = array();
        if($_POST['id']) {
            $item = FfbTeamPeer::retrieveByPK($_POST['id']);
            if($item) {
                $teams[0]['team_id'] = $item->getTeamId();
                $teams[0]['team_name'] = $item->getTeamName();
                $teams[0]['team_nationality'] = $item->getTeamNationality();
                $teams[0]['team_status'] = $item->getTeamStatus();
            }
        }
        $this->teams = $teams;
    }

    //returns list of players for selected team
    //used by lineup.js
    public function getTeamPlayers_test() {
        //$team = FfbTeamPeer::retrieveByPK($_POST['id']);
        $team = FfbTeamPeer::retrieveByPK($_GET['id']);
        if(!$team)
            return;
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
        $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $playerteam_items = $team->getFfbPlayerteamsJoinFfbPlayer($criteria);

        $players = array();
        $i=0;
        if($playerteam_items) {
            foreach($playerteam_items as $item) {
                //from table ffb_player
                $players[$i]['player_id'] = $item->getFfbPlayer()->getPlayerId();
                $players[$i]['player_fname'] = $item->getFfbPlayer()->getPlayerFname();
                $players[$i]['player_lname'] = $item->getFfbPlayer()->getPlayerLname();
                $players[$i]['player_nationality'] = $item->getFfbPlayer()->getPlayerNationality();
                $players[$i]['player_status'] = $item->getFfbPlayer()->getPlayerStatus();
                $players[$i]['player_status_description'] = $item->getFfbPlayer()->getPlayerStatusDescription();
                //from table ffb_playerteam
                $players[$i]['playerteam_id'] = $item->getPlayerteamId();
                $players[$i]['playerteam_team'] = $item->getFfbTeam()->getTeamName();
                $players[$i]['playerteam_team_nationality'] = $item->getFfbTeam()->getTeamNationality();
                $players[$i]['playerteam_player_price'] = $item->getPlayerteamPlayerPrice();
                $players[$i]['playerteam_player_position'] = $item->getPlayerteamPlayerPosition();
                $players[$i]['playerteam_player_picture'] = $item->getPlayerteamPlayerPicture();
                require_once('playerRanking.php');
                $playerRank = new playerRanking();
                //$players[$i]['player_grade'] = $this->calculatePlayerGrade($item->getPlayerteamId());
                $players[$i]['player_grade'] = $playerRank->calculatePlayerGrade($item->getPlayerteamId());
                $i++;
            }
        }
        $this->numResults = $i;
        $this->players = $players;
    }

    public function getTeamPlayers() {
    	require_once('playerRanking.php');
    	$playerRank = new playerRanking();
    	$team_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $matchround_id = isset($_POST['matchround_id']) ? $_POST['matchround_id'] : null;
        $pricemode = $this->options->options_game_pricemode;
        if (!$team_id) {
            $this->numResults = 0;
            $this->players = array();
            return;
        }
        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
        $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $playerteam_items = FfbPlayerteamPeer::doSelectJoinFfbPlayer($criteria);

        $players = array();
        $i=0;
        if($playerteam_items) {
            foreach($playerteam_items as $item) {
                //from table ffb_player
                $players[$i]['player_id'] = $item->getFfbPlayer()->getPlayerId();
                $players[$i]['player_fname'] = $item->getFfbPlayer()->getPlayerFname();
                $players[$i]['player_lname'] = $item->getFfbPlayer()->getPlayerLname();
                $players[$i]['player_nationality'] = $item->getFfbPlayer()->getPlayerNationality();
                $players[$i]['player_status'] = $item->getFfbPlayer()->getPlayerStatus();
                if($item->getFfbPlayer()->getPlayerStatusDescription()) {
                    $players[$i]['player_status_description'] = $item->getFfbPlayer()->getPlayerStatusDescription();
                } else {
                    $players[$i]['player_status_description'] = 0;
                }
                //from table ffb_playerteam
                $players[$i]['playerteam_id'] = $item->getPlayerteamId();
                $players[$i]['playerteam_team'] = $item->getFfbTeam()->getTeamName();
                $players[$i]['playerteam_team_nationality'] = $item->getFfbTeam()->getTeamNationality();
                $players[$i]['playerteam_player_position'] = $item->getPlayerteamPlayerPosition();
                $players[$i]['playerteam_player_picture'] = $item->getPlayerteamPlayerPicture();
                if($pricemode == 'dynamic') {
                    $criteria = new Criteria();
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $players[$i]['playerteam_id']);
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
                    $dyn_price = FfbPlayerpricePeer::doSelect($criteria);
                    if($dyn_price) {
                        $players[$i]['playerteam_player_price'] = $dyn_price[0]->getPlayerpricePrice();
                    } else {
                        $players[$i]['playerteam_player_price'] = $item->getPlayerteamPlayerPrice();
                    }
                } else if($pricemode == 'constant') {
                        $players[$i]['playerteam_player_price'] = $item->getPlayerteamPlayerPrice();
                } else {
                    $players[$i]['playerteam_player_price'] = $item->getPlayerteamPlayerPrice();
                }
                $players[$i]['player_grade'] = 0;
                $players[$i]['player_trend'] = 0;
/*
                if($pricemode == 'dynamic') {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerPower($item->getPlayerteamId(), $item->getPlayerteamPlayerPosition());
                } elseif($pricemode == 'constant') {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerGrade($item->getPlayerteamId());
                } else {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerGrade($item->getPlayerteamId());
                }
*/

                $player_values = $playerRank->calculatePlayerGrade_v2($item->getPlayerteamId());
                $players[$i]['player_grade'] = $player_values['player_grade'];
                $players[$i]['player_trend'] = $player_values['player_trend'];

                $i++;
            }
        }
        $this->numResults = $i;
        $this->players = $players;
    }

    private function getTeamsByCriteria($criteria) {
        $list = FfbTeamPeer::doSelect($criteria);
        $teams = array();
        $i=0;
        if($list) {
            foreach($list as $item) {
                $teams[$i]['team_id'] = $item->getTeamId();
                $teams[$i]['team_name'] = $item->getTeamName();
                $teams[$i]['team_nationality'] = $item->getTeamNationality();
                $teams[$i]['team_status'] = $item->getTeamStatus();
                $teams[$i]['team_avg_price'] = round($item->getTeamAvgPrice());
                $i++;
            }
        }
        $this->numResults = $i;
        $this->teams = $teams;
    }
}

?>