<?php

/**
 * FFB-Module - PRINT-Class;
 *
 * @author Musser
 * @copyright 09/2008
 * @version 0.4
 *
 */

class printme extends FFB_Auth_User {

 	public function __construct() {
        parent::__construct();
        $this->htmlFile = "printme.php";

    }

    public function __default() {
    	$this->getUserteamForRound();
    }


    //returns the userteam for the given matchround and the given user
    //if no user is given the userteam for the current (logged in) user is shown
    //used by lineup.js; myteam.js
    public function getUserteamForRound() {
        if($_GET['uid'])
            { $user_id = $_GET['uid']; }
        else
            { $user_id = $this->session->user_id; }

		if($user_id != $this->session->user_id) {
			echo "you can only print your own lineup";
			die();
		}
        $this->user_id = $user_id;

        if($_GET['mid'])
            { $matchround_id = $_GET['mid']; }
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
        $list = FfbUserteamPeer::doSelect($criteria);
        $userteams = array();

        if($list) {
            $i=0;
            foreach($list as $item) {
                $userteams[$i]['userteam_id'] = $item->getUserteamId();
                $userteams[$i]['userteam_matchround_id'] = $match_id = $item->getUserteamMatchroundId();
                $userteams[$i]['userteam_score'] = $item->getUserteamScore();
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
                    $players[$j]['player_status'] = $player_item->getFfbPlayer()->getPlayerStatus();
                    $players[$j]['player_status_description'] = $player_item->getFfbPlayer()->getPlayerStatusDescription();
                    $players[$j]['playerteam_id'] = $player_item->getPlayerteamId();
                    $players[$j]['playerteam_team_id'] = $player_item->getPlayerteamTeamId();
                    $players[$j]['playerteam_team'] = $player_item->getFfbTeam()->getTeamName();
                    $players[$j]['playerteam_team_nationality'] = $player_item->getFfbTeam()->getTeamNationality();
                    $players[$j]['playerteam_player_price'] = $player_item->getPlayerteamPlayerPrice();
                    $players[$j]['playerteam_player_position'] = $player_item->getPlayerteamPlayerPosition();
                    $players[$j]['playerteam_player_picture'] = $player_item->getPlayerteamPlayerPicture();
                    $players[$j]['playerteam_status'] = $player_item->getPlayerteamStatus();

                    require_once('playerRanking.php');
                    $playerRank = new playerRanking();
                    $players[$j]['player_grade'] = $playerRank->calculatePlayerGrade($player_item->getPlayerteamId());

                    $criteria3 = new Criteria();
                    $criteria3->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $match_id);
                    $criteria3->setLimit(1);
                    $playerstats = $player_item->getFfbPlayerstatss($criteria3);

                    if(count($playerstats) > 0 && $playerstats[0]) {
                    /*
                        $players[$j]['playerstats_goals'] = $playerstats[0]->getPlayerstatsGoals();
                        $players[$j]['playerstats_assists'] = $playerstats[0]->getPlayerstatsAssists();
                        $players[$j]['playerstats_minutes'] = $playerstats[0]->getPlayerstatsMinutes();
                        $players[$j]['playerstats_cards'] = $playerstats[0]->getPlayerstatsCards();
                        $players[$j]['playerstats_owngoals'] = $playerstats[0]->getPlayerstatsOwngoals();
                        $players[$j]['playerstats_penaltieslost'] = $playerstats[0]->getPlayerstatsPenaltieslost();
                        $players[$j]['playerstats_penaltiessaved'] = $playerstats[0]->getPlayerstatsPenaltiessaved();
                        $players[$j]['playerstats_score_goals'] = $playerstats[0]->getPlayerstatsScoreGoals();
                        $players[$j]['playerstats_score_assists'] = $playerstats[0]->getPlayerstatsScoreAssists();
                        $players[$j]['playerstats_score_minutes'] = $playerstats[0]->getPlayerstatsScoreMinutes();
                        $players[$j]['playerstats_score_cards'] = $playerstats[0]->getPlayerstatsScoreCards();
                        $players[$j]['playerstats_score_owngoals'] = $playerstats[0]->getPlayerstatsScoreOwngoals();
                        $players[$j]['playerstats_score_penaltieslost'] = $playerstats[0]->getPlayerstatsScorePenaltieslost();
                        $players[$j]['playerstats_score_penaltiessaved'] = $playerstats[0]->getPlayerstatsPenaltiessaved();
                        $players[$j]['playerstats_score_oppgoals'] = $playerstats[0]->getPlayerstatsScoreOppgoals();
                        $players[$j]['playerstats_score_nooppgoals'] = $playerstats[0]->getPlayerstatsScoreNooppgoals();
                    */
                        $players[$j]['playerstats_score'] = $playerstats[0]->getPlayerstatsScore();
                    } else {
                        $players[$j]['playerstats_score'] = 0;
                    /*
                        $players[$j]['playerstats_goals'] = 0;
                        $players[$j]['playerstats_assists'] = 0;
                        $players[$j]['playerstats_minutes'] = 0;
                        $players[$j]['playerstats_cards'] = 0;
                        $players[$j]['playerstats_owngoals'] = 0;
                        $players[$j]['playerstats_penaltieslost'] = 0;
                        $players[$j]['playerstats_penaltiessaved'] = 0;
                        $players[$j]['playerstats_score_goals'] = 0;
                        $players[$j]['playerstats_score_assists'] = 0;
                        $players[$j]['playerstats_score_minutes'] = 0;
                        $players[$j]['playerstats_score_cards'] = 0;
                        $players[$j]['playerstats_score_owngoals'] = 0;
                        $players[$j]['playerstats_score_penaltieslost'] = 0;
                        $players[$j]['playerstats_score_penaltiessaved'] = 0;
                        $players[$j]['playerstats_score_oppgoals'] = 0;
                        $players[$j]['playerstats_score_nooppgoals'] = 0;
                    */
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