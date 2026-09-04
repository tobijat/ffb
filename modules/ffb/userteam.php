<?php

/**
 * FFB-Module - USERTEAM-Class;
 *
 * @author Gritschacher, Musser
 * @copyright 09/2008
 * @version 0.4
 *
 */

class userteam extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();

        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }

    //returns list of all userteams
    public function getList() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbUserteamPeer::USERTEAM_ID);
        $this->getUserteamByCriteria($criteria);
    }

    //returns the userteam for the given matchround and the given user
    //if no user is given the userteam for the current (logged in) user is shown
    //used by lineup.js; myteam.js
    public function getUserteamForRound() {
        if($_REQUEST['userteam_user_id'])
            { $user_id = $_POST['userteam_user_id']; }
        else
            { $user_id = $this->session->user_id; }

        $this->user_id = $user_id;

        if($_POST['matchround_id'])
            { $matchround_id = $_POST['matchround_id']; }
        else {
            $matchround_id = 0;
        }
        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_id);
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
        $criteria->setLimit(1);
        $this->getUserteamByCriteria($criteria);
    }

    //returns userteams by given criteria
    private function getUserteamByCriteria($criteria) {
        if($_POST['matchround_id'])
            { $matchround_id = $_POST['matchround_id']; }
        else {
            $matchround_id = 0;
        }
        $pricemode = $this->options->options_game_pricemode;
        $list = FfbUserteamPeer::doSelect($criteria);
        $userteams = array();

        if($list) {
            $i=0;
            foreach($list as $item) {
                $userteams[$i]['userteam_id'] = $item->getUserteamId();
                $userteams[$i]['userteam_matchround_id'] = $match_id = $item->getUserteamMatchroundId();
                $userteams[$i]['userteam_score'] = $item->getUserteamScore();
                $userteams[$i]['userteam_price'] = $item->getUserteamPrice();
                $userteams[$i]['userteam_username'] = $item->getWebUser()->getUserNickname();

                $criteria2 = new Criteria();
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId1());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId2());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId3());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId4());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId5());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId6());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId7());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId8());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId9());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId10());
                $criteria2->addOr(FfbPlayerteamPeer::PLAYERTEAM_ID, $item->getUserteamPlayerId11());

                $player_items = FfbPlayerteamPeer::doSelectJoinAll($criteria2);

                $players = array();
                $j=0;
                foreach($player_items as $player_item) {
                    $players[$j]['player_id'] = $player_item->getFfbPlayer()->getPlayerId();
                    $players[$j]['player_fname'] = $player_item->getFfbPlayer()->getPlayerFname();
                    $players[$j]['player_lname'] = $player_item->getFfbPlayer()->getPlayerLname();
                    $players[$j]['player_nationality'] = $player_item->getFfbPlayer()->getPlayerNationality();
                    if($player_item->getFfbPlayer()->getPlayerStatus()) {
                        $players[$j]['player_status'] = $player_item->getFfbPlayer()->getPlayerStatus();
                    } else {
                        $players[$j]['player_status'] = 0;
                    }
                    if($player_item->getFfbPlayer()->getPlayerStatusDescription()) {
                        $players[$j]['player_status_description'] = $player_item->getFfbPlayer()->getPlayerStatusDescription();
                    } else {
                        $players[$j]['player_status_description'] = 0;
                    }
                    $players[$j]['playerteam_id'] = $player_item->getPlayerteamId();
                    $players[$j]['playerteam_team_id'] = $player_item->getPlayerteamTeamId();
                    $players[$j]['playerteam_team'] = $player_item->getFfbTeam()->getTeamName();
                    $players[$j]['playerteam_team_nationality'] = $player_item->getFfbTeam()->getTeamNationality();
                    $players[$j]['playerteam_player_position'] = $player_item->getPlayerteamPlayerPosition();
                    $players[$j]['playerteam_player_picture'] = $player_item->getPlayerteamPlayerPicture();
                    if($player_item->getPlayerteamStatus()) {
                        $players[$j]['playerteam_status'] = $player_item->getPlayerteamStatus();
                    } else {
                        $players[$j]['playerteam_status'] = 0;
                    }

                    $players[$j]['playerteam_player_price'] = $player_item->getPlayerteamPlayerPrice();

                    if($pricemode == 'dynamic') {
                        $criteria4 = new Criteria();
                        $criteria4->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $players[$j]['playerteam_id']);
                        $criteria4->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
                        $dyn_price = FfbPlayerpricePeer::doSelect($criteria4);
                        if($dyn_price) {
                            $players[$j]['playerteam_player_price'] = $dyn_price[0]->getPlayerpricePrice();
                        } else {
                            $players[$j]['playerteam_player_price'] = $player_item->getPlayerteamPlayerPrice();
                        }
                    } else if($pricemode == 'constant') {
                        $players[$j]['playerteam_player_price'] = $player_item->getPlayerteamPlayerPrice();
                    } else {
                        $players[$j]['playerteam_player_price'] = $player_item->getPlayerteamPlayerPrice();
                    }

                    require_once('playerRanking.php');
                    $playerRank = new playerRanking();
                    if($pricemode == 'dynamic') {
                        $players[$j]['player_grade'] = $playerRank->calculatePlayerPower($player_item->getPlayerteamId(), $player_item->getPlayerteamPlayerPosition());
                    } elseif($pricemode == 'constant') {
                        $players[$j]['player_grade'] = $playerRank->calculatePlayerGrade($player_item->getPlayerteamId());
                    } else {
                        $players[$j]['player_grade'] = $playerRank->calculatePlayerGrade($player_item->getPlayerteamId());
                    }
                    //$players[$j]['player_grade'] = $playerRank->calculatePlayerGrade($player_item->getPlayerteamId());

                    $criteria3 = new Criteria();
                    $criteria3->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $match_id);
                    $criteria3->setLimit(1);
                    $playerstats = $player_item->getFfbPlayerstatss($criteria3);

                    if($playerstats[0]) {
                        $players[$j]['playerstats_score'] = $playerstats[0]->getPlayerstatsScore();
                    } else {
                        $players[$j]['playerstats_score'] = 0;
                    }


                    $j++;
                }
                $userteams[$i]['players'] = $players;
                $i++;
            }
        }
        $this->numResults = $i;
        $this->userteams = $userteams;
    }
}

?>