<?php

/**
 * FFB-Module - PLAYER-Klasse;
 * gibt Infos über player zurück
 *
 * @author Gritschacher Tobias, Musser
 * @copyright 09/2008
 * @version 0.4
 *
 */

class player extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();

        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }

    public function getBestTeam() {
        $matchround_id = $_POST['matchround_id'];
        $userteam_flag = $_POST['userteam'];
        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'g');
        if($userteam_flag) {
            $criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        } else {
            $criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        }
        $criteria->setLimit(1);
        $top_goalie = $this->returnGetBestPlayerByCriteria($criteria);

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'd');
        if($userteam_flag) {
            $criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        } else {
            $criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        }
        $criteria->setLimit(4);
        $top_defence = $this->returnGetBestPlayerByCriteria($criteria);

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'm');
        if($userteam_flag) {
            $criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        } else {
            $criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        }
        $criteria->setLimit(4);
        $top_midfield = $this->returnGetBestPlayerByCriteria($criteria);

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 's');
        if($userteam_flag) {
            $criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        } else {
            $criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
        }
        $criteria->setLimit(2);
        $top_striker = $this->returnGetBestPlayerByCriteria($criteria);

        $full_array = array_merge($top_goalie, $top_defence, $top_midfield, $top_striker);
        $sum_score = 0;
        foreach($full_array as $item) {
            $sum_score += $item['playerstats_score'];
        }

        $this->userteam_score = $sum_score;

        $this->numResults = count($full_array);
        $this->userteams = $full_array;
    }

    public function getAlltimeTeam() {
        //$matchround_id = $_POST['matchround_id'];
        //$userteam_flag = $_POST['userteam'];
        $game_id = $this->session->game_id_player;


        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'g');
        $stats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        if($stats) {
            $player_score = array();
            foreach($stats as $item) {
                if($player_score[$item->getPlayerstatsPlayerteamId()]) {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] += $item->getPlayerstatsScore();
                } else {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] = $item->getPlayerstatsScore();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_price'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                }
            }
            $score = array();
            $price = array();
            foreach($player_score as $item) {
                $score[] = $item['player_score'];
                $price[] = $item['player_price'];
            }
            array_multisort($score, SORT_DESC, $price, SORT_ASC, $player_score);
        }
        $i=0;
        foreach($player_score as $item) {
            echo $item['player_fname'].' '.$item['player_lname'].' - '.$item['player_score'].'<br>';
            $i++;
            if($i>0)
                break;
        }


        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'd');
        $stats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        if($stats) {
            $player_score = array();
            foreach($stats as $item) {
                if($player_score[$item->getPlayerstatsPlayerteamId()]) {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] += $item->getPlayerstatsScore();
                } else {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] = $item->getPlayerstatsScore();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_price'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                }
            }
            $score = array();
            $price = array();
            foreach($player_score as $item) {
                $score[] = $item['player_score'];
                $price[] = $item['player_price'];
            }
            array_multisort($score, SORT_DESC, $price, SORT_ASC, $player_score);
        }
        $i=0;
        foreach($player_score as $item) {
            echo $item['player_fname'].' '.$item['player_lname'].' - '.$item['player_score'].'<br>';
            $i++;
            if($i>3)
                break;
        }


        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 'm');
        $stats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        if($stats) {
            $player_score = array();
            foreach($stats as $item) {
                if($player_score[$item->getPlayerstatsPlayerteamId()]) {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] += $item->getPlayerstatsScore();
                } else {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] = $item->getPlayerstatsScore();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_price'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                }
            }
            $score = array();
            $price = array();
            foreach($player_score as $item) {
                $score[] = $item['player_score'];
                $price[] = $item['player_price'];
            }
            array_multisort($score, SORT_DESC, $price, SORT_ASC, $player_score);
        }
        $i=0;
        foreach($player_score as $item) {
            echo $item['player_fname'].' '.$item['player_lname'].' - '.$item['player_score'].'<br>';
            $i++;
            if($i>3)
                break;
        }


        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, 's');
        $stats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        if($stats) {
            $player_score = array();
            foreach($stats as $item) {
                if($player_score[$item->getPlayerstatsPlayerteamId()]) {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] += $item->getPlayerstatsScore();
                } else {
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_score'] = $item->getPlayerstatsScore();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_fname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_lname'] = $item->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                    $player_score[$item->getPlayerstatsPlayerteamId()]['player_price'] = $item->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                }
            }
            $score = array();
            $price = array();
            foreach($player_score as $item) {
                $score[] = $item['player_score'];
                $price[] = $item['player_price'];
            }
            array_multisort($score, SORT_DESC, $price, SORT_ASC, $player_score);
        }
        $i=0;
        foreach($player_score as $item) {
            echo $item['player_fname'].' '.$item['player_lname'].' - '.$item['player_score'].'<br>';
            $i++;
            if($i>1)
                break;
        }

        exit();
    }

    //returns player details for given player as XML
    //used by playerinfo.js
    public function getPlayerInfo() {
    	$this->playerinfos = $this->retrievePlayerInfos_v2();
    	return;
    }

	//just for testing
    public function getPlayerInfo_v2() {
		$_REQUEST['playerteam_id'] = 3281;
    	//$this->playerinfos = $this->retrievePlayerInfos_v2();
		$playerinfo = $this->retrievePlayerInfos_v2();
		print_r($playerinfo[0]["pastmatches"]);
		die();
    	return;
    }

    //returns player details for given player as IMAGE!
    //used by playerinfo.js
    public function getPlayerInfoImg() {
    	$type = $_REQUEST['type'] ?? '';
    	switch ($type) {
			case "dynamic" :
				$playerinfos = $this->retrievePlayerInfos_v2("ASC", "dynamic");
				$this->generatePlayerstatsImageDynamicPrices($playerinfos);
				break;
			default:
    			$playerinfos = $this->retrievePlayerInfos_v2("ASC");
    			$this->generatePlayerstatsImage($playerinfos);
    			break;
		}
    	return;
    }


    //returns player details for given player
    //used by playerstats.js
    public function getPlayerStats() {
        $playerteam_id = $_REQUEST['playerteam_id'];
        $player = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $pm = $this->options->options_game_pointsmode;

        $team_id = $player->getFfbTeam()->getTeamId();
        $team_name = $player->getFfbTeam()->getTeamName();
        $team_nationality = $player->getFfbTeam()->getTeamNationality();
        $player_fname = $player->getFfbPlayer()->getPlayerFname();
        $player_lname = $player->getFfbPlayer()->getPlayerLname();
        $player_nationality = $player->getFfbPlayer()->getPlayerNationality();
        if(!$player->getPlayerteamPlayerPicture()) {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
        } else {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$team_id.'/'.$playerteam_id.'.jpg';
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $_REQUEST['playerteam_id']);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $_REQUEST['matchround_id']);
        $criteria->setLimit(1);
        $statsitems = FfbPlayerstatsPeer::doSelect($criteria);
        $playerstats = array();
        $i=0;
        if($statsitems) {
            foreach($statsitems as $statsitem) {
                $playerstats[$i]['playerstats_goals'] = $statsitem->getPlayerstatsGoals();
                $playerstats[$i]['playerstats_assists'] = $statsitem->getPlayerstatsAssists();
                $playerstats[$i]['playerstats_minutes'] = $statsitem->getPlayerstatsMinutes();
                $playerstats[$i]['playerstats_minute_in'] = $statsitem->getPlayerstatsMinuteIn();
                $playerstats[$i]['playerstats_minute_out'] = $statsitem->getPlayerstatsMinuteOut();
                $playerstats[$i]['playerstats_cards'] = $statsitem->getPlayerstatsCards();
                $playerstats[$i]['playerstats_owngoals'] = $statsitem->getPlayerstatsOwngoals();
                $playerstats[$i]['playerstats_penaltiessaved'] = $statsitem->getPlayerstatsPenaltiessaved();
                $playerstats[$i]['playerstats_penaltieslost'] = $statsitem->getPlayerstatsPenaltieslost();
                $playerstats[$i]['playerstats_penaltyshootout_lost'] = $statsitem->getPlayerstatsPenaltyshootoutLost();
                $playerstats[$i]['playerstats_penaltyshootout_hit'] = $statsitem->getPlayerstatsPenaltyshootoutHit();
                $playerstats[$i]['playerstats_penaltyshootout_save'] = $statsitem->getPlayerstatsPenaltyshootoutSave();

                if($statsitem->getFfbPlayerteam()->getPlayerteamTeamId() == FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchHometeamId()) {
                    $playerstats[$i]['playerstats_oppgoals'] = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchGuestscore();
                    //$playerstats[$i]['playerstats_player_oppgoals'] = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchGuestscore();
                    $opposite_team_id = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchGuestteamId();
                    $own_team_id = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchHometeamId();
                } elseif($statsitem->getFfbPlayerteam()->getPlayerteamTeamId() == FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchGuestteamId()) {
                    $playerstats[$i]['playerstats_oppgoals'] = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchHomescore();
                    //$playerstats[$i]['playerstats_player_oppgoals'] = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchHomescore();
                    $opposite_team_id = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchHometeamId();
                    $own_team_id = FfbMatchPeer::retrieveByPK($statsitem->getPlayerstatsMatchId())->getMatchGuestteamId();
                } else {
                    $playerstats[$i]['playerstats_oppgoals'] = 0;
                    $playerstats[$i]['playerstats_player_oppgoals'] = 0;
                }

                if($pm == 'new') {
                    $goal_items = $this->getGoalsList($opposite_team_id, $own_team_id, $statsitem->getPlayerstatsMatchId());
                    $num_opposite_goals = 0;
                    $opposite_goals_string = '';
                    if(count($goal_items) > 0) {
                        foreach($goal_items as $item) {
                            if(($item->getGoalMinute()>=$statsitem->getPlayerstatsMinuteIn() && $item->getGoalMinute()<=$statsitem->getPlayerstatsMinuteOut()) || ($item->getGoalMinute()>=$statsitem->getFfbMatch()->getMatchMinutes() && $statsitem->getPlayerstatsMinuteOut()>=$statsitem->getFfbMatch()->getMatchMinutes())) {
                                $num_opposite_goals++;
                                $opposite_goals_string .= $item->getGoalMinute().'.; ';
                            }
                        }
                    }
                    $playerstats[$i]['playerstats_player_oppgoals'] = $num_opposite_goals;
                    if($opposite_goals_string) {
                        $playerstats[$i]['playerstats_player_oppgoals_string'] = $opposite_goals_string;
                    } else {
                        $playerstats[$i]['playerstats_player_oppgoals_string'] = 0;
                    }
                } else {
                    $playerstats[$i]['playerstats_player_oppgoals'] = $playerstats[$i]['playerstats_oppgoals'];
                    $playerstats[$i]['playerstats_player_oppgoals_string'] = 0;
                }

                $playerstats[$i]['playerstats_score_goals'] = $statsitem->getPlayerstatsScoreGoals();
                $playerstats[$i]['playerstats_score_assists'] = $statsitem->getPlayerstatsScoreAssists();
                $playerstats[$i]['playerstats_score_minutes'] = $statsitem->getPlayerstatsScoreMinutes();
                $playerstats[$i]['playerstats_score_cards'] = $statsitem->getPlayerstatsScoreCards();
                $playerstats[$i]['playerstats_score_owngoals'] = $statsitem->getPlayerstatsScoreOwngoals();
                $playerstats[$i]['playerstats_score_penaltiessaved'] = $statsitem->getPlayerstatsScorePenaltiessaved();
                $playerstats[$i]['playerstats_score_penaltieslost'] = $statsitem->getPlayerstatsScorePenaltieslost();
                $playerstats[$i]['playerstats_score_penaltyshootout_lost'] = $statsitem->getPlayerstatsScorePenaltyshootoutLost();
                $playerstats[$i]['playerstats_score_penaltyshootout_hit'] = $statsitem->getPlayerstatsScorePenaltyshootoutHit();
                $playerstats[$i]['playerstats_score_penaltyshootout_save'] = $statsitem->getPlayerstatsScorePenaltyshootoutSave();
                $playerstats[$i]['playerstats_score_oppgoals'] = $statsitem->getPlayerstatsScoreOppgoals();
                $playerstats[$i]['playerstats_score_nooppgoals'] = $statsitem->getPlayerstatsScoreNooppgoals();
                $playerstats[$i]['playerstats_score'] = $statsitem->getPlayerstatsScore();

                $playerstats[$i]['player_fname'] = $player_fname;
                $playerstats[$i]['player_lname'] = $player_lname;
                $playerstats[$i]['player_nationality'] = $player_nationality;

                $playerstats[$i]['player_picture'] = $picture_file;
                $playerstats[$i]['player_team_name'] = $team_name;
                $playerstats[$i]['player_team_nationality'] = $team_nationality;
                $i++;
                $this->played = 1;
            }
        } else {
            $this->played = 0;
            $playerstats[$i]['playerstats_goals'] = 0;
            $playerstats[$i]['playerstats_assists'] = 0;
            $playerstats[$i]['playerstats_minutes'] = 0;
            $playerstats[$i]['playerstats_minute_in'] = 0;
            $playerstats[$i]['playerstats_minute_out'] = 0;
            $playerstats[$i]['playerstats_cards'] = 'n';
            $playerstats[$i]['playerstats_owngoals'] = 0;
            $playerstats[$i]['playerstats_penaltiessaved'] = 0;
            $playerstats[$i]['playerstats_penaltieslost'] = 0;
            $playerstats[$i]['playerstats_penaltyshootout_lost'] = 0;
            $playerstats[$i]['playerstats_penaltyshootout_hit'] = 0;
            $playerstats[$i]['playerstats_penaltyshootout_save'] = 0;
            $playerstats[$i]['playerstats_oppgoals'] = 0;

            $playerstats[$i]['playerstats_score_goals'] = 0;
            $playerstats[$i]['playerstats_score_assists'] = 0;
            $playerstats[$i]['playerstats_score_minutes'] = 0;
            $playerstats[$i]['playerstats_score_cards'] = 0;
            $playerstats[$i]['playerstats_score_owngoals'] = 0;
            $playerstats[$i]['playerstats_score_penaltiessaved'] = 0;
            $playerstats[$i]['playerstats_score_penaltieslost'] = 0;
            $playerstats[$i]['playerstats_score_penaltyshootout_lost'] = 0;
            $playerstats[$i]['playerstats_score_penaltyshootout_hit'] = 0;
            $playerstats[$i]['playerstats_score_penaltyshootout_save'] = 0;
            $playerstats[$i]['playerstats_score_oppgoals'] = 0;
            $playerstats[$i]['playerstats_score_nooppgoals'] = 0;
            $playerstats[$i]['playerstats_score'] = 0;

            $playerstats[$i]['player_picture'] = $picture_file;
            $playerstats[$i]['player_team_name'] = $team_name;
            $playerstats[$i]['player_team_nationality'] = $team_nationality;
            $playerstats[$i]['player_fname'] = $player_fname;
            $playerstats[$i]['player_lname'] = $player_lname;
            $playerstats[$i]['player_nationality'] = $player_nationality;
        }
        $this->num_results = $i;
        $this->playerstats = $playerstats;
    }

    private function returnGetBestPlayerByCriteria($criteria) {
        $statsitems = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        $pricemode = $this->options->options_game_pricemode;
        $players = array();
        $i=0;
        if($statsitems) {
            foreach($statsitems as $statsitem) {
                $players[$i]['player_id'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerId();
                $players[$i]['player_fname'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
                $players[$i]['player_lname'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
                $players[$i]['player_nationality'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerNationality();
                $players[$i]['player_status'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatus();

                require_once('playerRanking.php');
                $playerRank = new playerRanking();
                if($pricemode == 'dynamic') {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerPower($statsitem->getFfbPlayerteam()->getPlayerteamId(), $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPosition());
                } elseif($pricemode == 'constant') {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerGrade($statsitem->getFfbPlayerteam()->getPlayerteamId());
                } else {
                    $players[$i]['player_grade'] = $playerRank->calculatePlayerGrade($statsitem->getFfbPlayerteam()->getPlayerteamId());
                }
                $players[$i]['player_status_description'] = $statsitem->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatusDescription();
                $players[$i]['playerteam_id'] = $statsitem->getFfbPlayerteam()->getPlayerteamId();
                $players[$i]['playerteam_team_id'] = $statsitem->getFfbPlayerteam()->getPlayerteamTeamId();
                $players[$i]['playerteam_team'] = $statsitem->getFfbPlayerteam()->getFfbTeam()->getTeamName();
                $players[$i]['playerteam_team_nationality'] = $statsitem->getFfbPlayerteam()->getFfbTeam()->getTeamNationality();
                //$players[$i]['playerteam_player_price'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                $players[$i]['playerteam_player_position'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPosition();
                $players[$i]['playerteam_player_picture'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPicture();
                $players[$i]['playerteam_status'] = $statsitem->getFfbPlayerteam()->getPlayerteamStatus();

                if($pricemode == 'dynamic') {
                    $criteria = new Criteria();
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $players[$i]['playerteam_id']);
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $_POST['matchround_id']);
                    $dyn_price = FfbPlayerpricePeer::doSelect($criteria);
                    if($dyn_price) {
                        $players[$i]['playerteam_player_price'] = $dyn_price[0]->getPlayerpricePrice();
                    } else {
                        $players[$i]['playerteam_player_price'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                    }
                } else if($pricemode == 'constant') {
                    $players[$i]['playerteam_player_price'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                } else {
                    $players[$i]['playerteam_player_price'] = $statsitem->getFfbPlayerteam()->getPlayerteamPlayerPrice();
                }

                $players[$i]['playerstats_goals'] = $statsitem->getPlayerstatsGoals();
                $players[$i]['playerstats_assists'] = $statsitem->getPlayerstatsAssists();
                $players[$i]['playerstats_minutes'] = $statsitem->getPlayerstatsMinutes();
                $players[$i]['playerstats_minute_in'] = $statsitem->getPlayerstatsMinuteIn();
                $players[$i]['playerstats_minute_out'] = $statsitem->getPlayerstatsMinuteOut();
                $players[$i]['playerstats_cards'] = $statsitem->getPlayerstatsCards();
                $players[$i]['playerstats_owngoals'] = $statsitem->getPlayerstatsOwngoals();
                $players[$i]['playerstats_penaltieslost'] = $statsitem->getPlayerstatsPenaltieslost();
                $players[$i]['playerstats_penaltiessaved'] = $statsitem->getPlayerstatsPenaltiessaved();
                $players[$i]['playerstats_score_goals'] = $statsitem->getPlayerstatsScoreGoals();
                $players[$i]['playerstats_score_assists'] = $statsitem->getPlayerstatsScoreAssists();
                $players[$i]['playerstats_score_minutes'] = $statsitem->getPlayerstatsScoreMinutes();
                $players[$i]['playerstats_score_cards'] = $statsitem->getPlayerstatsScoreCards();
                $players[$i]['playerstats_score_owngoals'] = $statsitem->getPlayerstatsScoreOwngoals();
                $players[$i]['playerstats_score_penaltieslost'] = $statsitem->getPlayerstatsScorePenaltieslost();
                $players[$i]['playerstats_score_penaltiessaved'] = $statsitem->getPlayerstatsPenaltiessaved();
                $players[$i]['playerstats_score_oppgoals'] = $statsitem->getPlayerstatsScoreOppgoals();
                $players[$i]['playerstats_score_nooppgoals'] = $statsitem->getPlayerstatsScoreNooppgoals();
                $players[$i]['playerstats_score'] = $statsitem->getPlayerstatsScore();
                $i++;
            }
        }
        //$this->num_results = $i;
        //$this->playerstats = $playerstats;
        return $players;
    }


    public function retrievePlayerInfos ($sort_order="DESC", $type="standard") {
        $playerteam_id = $_REQUEST['playerteam_id'];
        $player = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $player_fname = $player->getFfbPlayer()->getPlayerFname();
        $player_lname = $player->getFfbPlayer()->getPlayerLname();
        $player_price = $player->getPlayerteamPlayerPrice();
        $player_nationality = $player->getFfbPlayer()->getPlayerNationality();
        $team_id = $player->getFfbTeam()->getTeamId();
        $team_name = $player->getFfbTeam()->getTeamName();
        $team_nationality = $player->getFfbTeam()->getTeamNationality();
        if(!$player->getPlayerteamPlayerPicture()) {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
        } else {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$team_id.'/'.$playerteam_id.'.jpg';
        }

		//***** count matches *****

		$dateTime = date("Y-m-d H:i:s");
		$criteria = new Criteria();
		$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $dateTime, Criteria::LESS_THAN);
		$matches = FfbMatchroundPeer::doSelect($criteria);
		$match_count_total = 0;
		foreach($matches AS $elem){
			$match_count_total++;
		}

        //***** count lineups *****
        $criteria = new Criteria();
        $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $playerteam_id);
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $playerteam_id));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $playerteam_id));
        $criteria->add($c1);

        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);

        $num_lineups = FfbUserteamPeer::doCountJoinFfbMatchround($criteria);
        $playerinfos[0]['num_lineups'] = $num_lineups;

        //to do: calculate lineups for current round
        //$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, 28);
        //$num_lineups_this_round = FfbUserteamPeer::doCountJoinFfbMatchround($criteria);
        //$playerinfos[0]['num_lineups_round'] = $num_lineups_this_round;
        //*****

        //***** sum up player scores/minutes/goals/etc.. *****
        $score = 0;
        $goals = 0;
        $minutes = 0;
        $assists = 0;
        $score_av = 0;
        $goals_av = 0;
        $minutes_av = 0;
        $assists_av = 0;
        $cards_y = 0;
        $cards_r = 0;
        $cards_yr = 0;
        $match_count_played = 0;
        $match_count_percent = 0;
        $matchrounds = array();
        $last_player_price = 0;
        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $playerstats = FfbPlayerstatsPeer::doSelectJoinFfbMatchround($criteria);
        $now = time();
        $now_string = date('Y-m-d H:i:s');
        $last_match_date = 0;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $now_string, Criteria::LESS_THAN);

    	if($sort_order == "DESC") {
    		$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	} elseif($sort_order == "ASC") {
    		$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	}
        $all_matchrounds = FfbMatchroundPeer::doSelect($criteria);
        //------

        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $players_team_id = $playerteam->getPlayerteamTeamId();
        if($all_matchrounds) {
            $i=0;
            foreach($all_matchrounds as $matchround) {
                //$matchround = $playerstat->getFfbMatchround();
                $last_match_date = $matchround->getMatchroundStartdate();
                $criteria = new Criteria();
                $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $players_team_id);
                $c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $players_team_id));
                $criteria->add($c1);
                $match_item = $matchround->getFfbMatchs($criteria);
                if($match_item) {
                    $matchround_id = $matchround->getMatchroundId();

                    $criteria = new Criteria();
                    $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $playerteam_id);
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $playerteam_id));
                    $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $playerteam_id));
                    $criteria->add($c1);

                    $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);

                    $num_lineups_in_round = FfbUserteamPeer::doCountJoinFfbMatchround($criteria);


                    //echo 'matchitem: '.count($match_item).'<br>';
                    if($match_item) {
                        $home_team_id = $match_item[0]->getMatchHometeamId();
                        $guest_team_id = $match_item[0]->getMatchGuestteamId();
                        $home_team_score = $match_item[0]->getMatchHomescore();
                        $guest_team_score = $match_item[0]->getMatchGuestscore();
                        $home_team_score_penalty = $match_item[0]->getMatchHomescorePenalty();
                        $guest_team_score_penalty = $match_item[0]->getMatchGuestscorePenalty();
                        $match_id = $match_item[0]->getMatchId();
                    }
                    if($players_team_id == $home_team_id) {
                        $players_opponent_id = $guest_team_id;
                    } else {
                        $players_opponent_id = $home_team_id;
                    }

                    $home_team_name = FfbTeamPeer::retrieveByPK($home_team_id)->getTeamName();
                    $guest_team_name = FfbTeamPeer::retrieveByPK($guest_team_id)->getTeamName();
                    $opposite_team_name = FfbTeamPeer::retrieveByPK($guest_team_id)->getTeamName();

                    $matchrounds[$i]['matchround_id'] = $matchround->getMatchroundId();
                    $matchrounds[$i]['matchround_title'] = $matchround->getMatchroundTitle();
                    $matchrounds[$i]['match_id'] = $match_id;

					if($type=="standard") {
                    	$criteria = new Criteria();
                    	$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
                    	$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
                    	$playerstats = FfbPlayerstatsPeer::doSelect($criteria);

                    	if($playerstats) {
                        	$playerstat = $playerstats[0];

                        	$score += $playerstat->getPlayerstatsScore();
	                        $goals += $playerstat->getPlayerstatsGoals();
    	                    $assists += $playerstat->getPlayerstatsAssists();
        	                if($playerstat->getPlayerstatsCards() == 'y') {
            	                $cards_y++;
                	        } elseif ($playerstat->getPlayerstatsCards() == 'r') {
                    	        $cards_r++;
                        	} elseif ($playerstat->getPlayerstatsCards() == 'yr') {
                    	        $cards_yr++;
                        	}
                        	$minutes += $playerstat->getPlayerstatsMinutes();
	                        $matchrounds[$i]['matchround_minutes_played'] = $playerstat->getPlayerstatsMinutes();
    	                    $matchrounds[$i]['matchround_score'] = $playerstat->getPlayerstatsScore();
        	                $matchrounds[$i]['matchround_goals'] = $playerstat->getPlayerstatsGoals();
            	            $matchrounds[$i]['matchround_assists'] = $playerstat->getPlayerstatsAssists();
                	        $matchrounds[$i]['matchround_cards'] = $playerstat->getPlayerstatsCards();
                    	    $matchrounds[$i]['matchround_opponent_name'] = $opposite_team_name;
                        	$matchrounds[$i]['matchround_hometeam_name'] = $home_team_name;
	                        $matchrounds[$i]['matchround_guestteam_name'] = $guest_team_name;
    	                    $matchrounds[$i]['matchround_hometeam_score'] = $home_team_score;
        	                $matchrounds[$i]['matchround_guestteam_score'] = $guest_team_score;
        	                $matchrounds[$i]['matchround_hometeam_score_penalty'] = $home_team_score_penalty;
        	                $matchrounds[$i]['matchround_guestteam_score_penalty'] = $guest_team_score_penalty;
            	            $matchrounds[$i]['matchround_opponent_id'] = $players_opponent_id;
                	        $matchrounds[$i]['matchround_num_lineups'] = $num_lineups_in_round;

                    	} else {
	                        $matchrounds[$i]['matchround_minutes_played'] = '-';
    	                    $matchrounds[$i]['matchround_score'] = '-';
        	                $matchrounds[$i]['matchround_goals'] = '-';
            	            $matchrounds[$i]['matchround_assists'] = '-';
                	        $matchrounds[$i]['matchround_cards'] = 'n';
                    	    $matchrounds[$i]['matchround_opponent_name'] = $opposite_team_name;
                        	$matchrounds[$i]['matchround_hometeam_name'] = $home_team_name;
	                        $matchrounds[$i]['matchround_guestteam_name'] = $guest_team_name;
    	                    $matchrounds[$i]['matchround_hometeam_score'] = $home_team_score;
        	                $matchrounds[$i]['matchround_guestteam_score'] = $guest_team_score;
        	                $matchrounds[$i]['matchround_hometeam_score_penalty'] = $home_team_score_penalty;
        	                $matchrounds[$i]['matchround_guestteam_score_penalty'] = $guest_team_score_penalty;
            	            $matchrounds[$i]['matchround_opponent_id'] = $players_opponent_id;
                	        //$matchrounds[$i]['matchround_num_lineups'] = '-';
                	        $matchrounds[$i]['matchround_num_lineups'] = $num_lineups_in_round;

                    	}
                    }


                    if($type=="dynamic") {
						if($i==0) {
   	                		$prices[$i]['matchround_playerprice'] = $player_price;
                    		$prices[$i]['matchround_playerpower'] = 0;
                    		$prices[$i]['matchround_av_playerpower'] = 0;
                    		$last_player_price = $player_price;
						} else {

                    		$criteria = new Criteria();
                    		$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
							$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
    	                	$dynPlayerstats = FfbPlayerpricePeer::doSelect($criteria);
    	                	//print_r($dynPlayerstats);
    	                	//echo $matchround_id . "<br>";
    	                	//echo $playerteam_id;
        	            	if($dynPlayerstats) {
            	        		$dynPlayerstat = $dynPlayerstats[0];
                	    		$prices[$i]['matchround_playerprice'] = $dynPlayerstat->getPlayerpricePrice();
                    			$prices[$i]['matchround_playerpower'] = $dynPlayerstat->getPlayerpricePlayerPower();
                    			$prices[$i]['matchround_av_playerpower'] = $dynPlayerstat->getPlayerpriceAvPower();
                    			$last_player_price = $dynPlayerstat->getPlayerpricePrice();
	                   		} else {
    	               			$prices[$i]['matchround_playerprice'] = $last_player_price;
        	            		$prices[$i]['matchround_playerpower'] = 0;
        	            		$prices[$i]['matchround_av_playerpower'] = 0;
            	       		}
     					}
                    }
                    $i++;

                }
            }
            if($i>0) {
                $score_av = round($score/$i, 2);
                $goals_av = round($goals/$i, 2);
                $assists_av = round($assists/$i, 2);
                $cards_y_av = round($cards_y/$i, 2);
                $cards_r_av = round($cards_r/$i, 2);
                $cards_yr_av = round($cards_yr/$i, 2);
                $minutes_av = round($minutes/$i, 2);
                $match_count_played = $i;
                $match_count_percent = round($match_count_played/$match_count_total* 100.0, 2);

				if($type=="dynamic") {//current round needed for player prices ... START
        			$criteria = new Criteria();
        			$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
       				$criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $last_match_date, Criteria::GREATER_THAN);
        			$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
					$nextRounds = FfbMatchroundPeer::doSelect($criteria);
					if($nextRounds) {
						$matchround_id = $nextRounds[0]->getMatchroundId();
						$criteria = new Criteria();
    					$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
						$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
    	                $dynPlayerstats = FfbPlayerpricePeer::doSelect($criteria);
    	                //print_r($dynPlayerstats);
    	                //	echo "<br>".$matchround_id . "<br>";
    	                //	echo $playerteam_id;
    	                if($dynPlayerstats) {
            	        	$dynPlayerstat = $dynPlayerstats[0];
                	    	$prices[$i]['matchround_playerprice'] = $dynPlayerstat->getPlayerpricePrice();
                    		$prices[$i]['matchround_playerpower'] = $dynPlayerstat->getPlayerpricePlayerPower();
                    		$prices[$i]['matchround_av_playerpower'] = $dynPlayerstat->getPlayerpriceAvPower();
                    		$last_player_price = $dynPlayerstat->getPlayerpricePrice();
	                   	} else {
    	               		$prices[$i]['matchround_playerprice'] = $last_player_price;
        	            	$prices[$i]['matchround_playerpower'] = 0;
        	            	$prices[$i]['matchround_av_playerpower'] = 0;
            	       	}
					}
       			} // END


            }

        }



        $playerinfos[0]['sum_score'] = $score;
        $playerinfos[0]['sum_goals'] = $goals;
        $playerinfos[0]['sum_assists'] = $assists;
        $playerinfos[0]['sum_cards_y'] = $cards_y;
        $playerinfos[0]['sum_cards_r'] = $cards_r;
        $playerinfos[0]['sum_cards_yr'] = $cards_yr;
        $playerinfos[0]['sum_minutes'] = $minutes;
        $playerinfos[0]['matchrounds'] = $matchrounds;
        $playerinfos[0]['all_matchrounds'] = $all_matchrounds;
        $playerinfos[0]['av_score'] = $score_av;
        $playerinfos[0]['av_goals'] = $goals_av;
        $playerinfos[0]['av_assists'] = $assists_av;
        $playerinfos[0]['av_minutes'] = $minutes_av;
        $playerinfos[0]['player_picture'] = $picture_file;
        $playerinfos[0]['player_team_name'] = $team_name;
        $playerinfos[0]['player_team_nationality'] = $team_nationality;
        $playerinfos[0]['player_fname'] = $player_fname;
        $playerinfos[0]['player_lname'] = $player_lname;
        $playerinfos[0]['player_nationality'] = $player_nationality;
        $playerinfos[0]['match_count_total'] = $match_count_total;
        $playerinfos[0]['match_count_played'] = $match_count_played;
        $playerinfos[0]['match_count_percent'] = $match_count_percent;
        $playerinfos[0]['prices'] = $prices;
        //*****
		return $playerinfos;
    }

    public function retrievePlayerInfos_v2 ($sort_order="DESC", $type="standard") {
        $playerteam_id = $_REQUEST['playerteam_id'];
        $playerinfos = array();
        $playerinfos[0]['playerteam_id'] = $playerteam_id;
        $player = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        //get FFB_Player and actual FFB_Team
		$ffb_player = $player->getFfbPlayer();
		$ffb_team = $player->getFfbTeam();
		//get all FFB_Playerteams for this player and store playerteam_ids into array
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
        $pt_ids = array();
        foreach($ffb_playerteams as $ffbpt) {
        	$pt_ids[] = $ffbpt->getPlayerteamId();
		}
		//get all FFB_Teams for this player and store team_ids into array
		$criteria = new Criteria();
		$criteria->addJoin(FfbTeamPeer::TEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, Criteria::INNER_JOIN);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$ffb_teams = FfbTeamPeer::doSelect($criteria);
		$team_ids = array();
		foreach($ffb_teams as $ffbt) {
        	$team_ids[] = $ffbt->getTeamId();
		}
        //echo count($ffb_teams);
        //print_r($pt_ids);

        $player_fname = $ffb_player->getPlayerFname();
        $player_lname = $ffb_player->getPlayerLname();
        $player_price = $player->getPlayerteamPlayerPrice();
        $player_nationality = $ffb_player->getPlayerNationality();
        $team_id = $ffb_team->getTeamId();
        $team_name = $ffb_team->getTeamName();
        $team_nationality = $ffb_team->getTeamNationality();
        if(!$player->getPlayerteamPlayerPicture()) {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/image_na.gif';
        } else {
            $picture_file = FFB_BASE_PATH.FFB_IMAGE_PATH.'players/'.$team_id.'/'.$playerteam_id.'.jpg';
        }

		//***** count matches *****
		$dateTime = date("Y-m-d H:i:s");
		$criteria = new Criteria();
		$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $dateTime, Criteria::LESS_THAN);
		$matches = FfbMatchroundPeer::doSelect($criteria);
		$match_count_total = count($matches);
		// -----

        //***** count lineups *****
        $criteria = new Criteria();
        $criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbUserteamPeer::USERTEAM_MATCHROUND_ID, Criteria::INNER_JOIN);
        $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $pt_ids, Criteria::IN);
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $pt_ids, Criteria::IN));
        $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $pt_ids, Criteria::IN));
        $criteria->add($c1);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $num_lineups = FfbUserteamPeer::doCount($criteria);
        $playerinfos[0]['num_lineups'] = $num_lineups;
        //-----

		//count how many matches the player played (needed for average)
		$criteria = new Criteria();
        $criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, Criteria::INNER_JOIN);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $match_count_played = FfbPlayerstatsPeer::doCount($criteria);
        //-----

		//get all past matchrounds for this game
        $now = time();
        $now_string = date('Y-m-d H:i:s');
        $last_match_date = 0;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        //$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $now_string, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
    	if($sort_order == "DESC") {
    		$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	} elseif($sort_order == "ASC") {
    		$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	}
        $all_matchrounds = FfbMatchroundPeer::doSelect($criteria);
        //------

		$matchrounds = array();
		$minutes = 0;
		$score = 0;
		$assists = 0;
		$goals = 0;
		$cards_y = 0;
		$cards_r = 0;
		$cards_yr = 0;

		//echo 'all_matchrounds<br>';
		if($all_matchrounds) {
            $i=0;
            foreach($all_matchrounds as $matchround) {
                $last_match_date = $matchround->getMatchroundStartdate();
                $matchround_id = $matchround->getMatchroundId();

				//count lineups for round
                $criteria = new Criteria();
                $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $pt_ids, Criteria::IN);
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $pt_ids, Criteria::IN));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $pt_ids, Criteria::IN));
                $criteria->add($c1);
                $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
                $num_lineups_in_round = FfbUserteamPeer::doCount($criteria);
                $matchrounds[$i]['matchround_num_lineups'] = $num_lineups_in_round;
                $matchrounds[$i]['matchround_id'] = $matchround_id;
	            $matchrounds[$i]['matchround_title'] = $matchround->getMatchroundTitle();
                //-----

                if(strtotime($matchround->getMatchroundStartdate())>time()) {
					$matchrounds[$i]['matchround_running'] = 1;
				} else {
					$matchrounds[$i]['matchround_running'] = 0;
				}

				//echo 'num lineups for round: '.$num_lineups_in_round.'<br>';

				//get playerstats for round
                $criteria = new Criteria();
		        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
		        $criteria->setLimit(1);
		        $playerstats = FfbPlayerstatsPeer::doSelect($criteria);
		        //-----

		        //if player did play in this round
		        if($playerstats) {
					$playerstat = $playerstats[0];
                    $score += $playerstat->getPlayerstatsScore();
	                $goals += $playerstat->getPlayerstatsGoals();
    	            $assists += $playerstat->getPlayerstatsAssists();
        	        if($playerstat->getPlayerstatsCards() == 'y') {
            	        $cards_y++;
                	} elseif ($playerstat->getPlayerstatsCards() == 'r') {
                    	$cards_r++;
                    } elseif ($playerstat->getPlayerstatsCards() == 'yr') {
                    	$cards_yr++;
                    }
                    $minutes += $playerstat->getPlayerstatsMinutes();
	                $matchrounds[$i]['matchround_minutes_played'] = $playerstat->getPlayerstatsMinutes();
    	            $matchrounds[$i]['matchround_score'] = $playerstat->getPlayerstatsScore();
        	        $matchrounds[$i]['matchround_goals'] = $playerstat->getPlayerstatsGoals();
            	    $matchrounds[$i]['matchround_assists'] = $playerstat->getPlayerstatsAssists();
                	$matchrounds[$i]['matchround_cards'] = $playerstat->getPlayerstatsCards();

                	//get the match in which the player has played
                	$player_team = $playerstat->getFfbPlayerteam()->getFfbTeam();
					$player_team_id = $player_team->getTeamId();
					$matcharr = $this->getMatchdataForPlayerandRound($player_team_id, $matchround_id);
					if($matcharr) {
						$matchrounds[$i]['matchround_opponent_name'] = $matcharr['matchround_opponent_name'];
	          			$matchrounds[$i]['matchround_hometeam_name'] = $matcharr['matchround_hometeam_name'];
	          			$matchrounds[$i]['matchround_guestteam_name'] = $matcharr['matchround_guestteam_name'];
	          			$matchrounds[$i]['matchround_hometeam_score'] = $matcharr['matchround_hometeam_score'];
	        			$matchrounds[$i]['matchround_guestteam_score'] = $matcharr['matchround_guestteam_score'];
	        			$matchrounds[$i]['matchround_hometeam_score_penalty'] = $matcharr['matchround_hometeam_score_penalty'];
	        			$matchrounds[$i]['matchround_guestteam_score_penalty'] = $matcharr['matchround_guestteam_score_penalty'];
	  	    			$matchrounds[$i]['matchround_opponent_id'] = $matcharr['matchround_opponent_id'];
	            		$matchrounds[$i]['match_id'] = $matcharr['match_id'];
	            		$matchrounds[$i]['match_date'] = $matcharr['match_date'];
					} else {
						$matchrounds[$i]['matchround_opponent_name'] = 0;
	                    $matchrounds[$i]['matchround_hometeam_name'] = 0;
		                $matchrounds[$i]['matchround_guestteam_name'] = 0;
	    	            $matchrounds[$i]['matchround_hometeam_score'] = 0;
	        	        $matchrounds[$i]['matchround_guestteam_score'] = 0;
	        	        $matchrounds[$i]['matchround_hometeam_score_penalty'] = 0;
	        	        $matchrounds[$i]['matchround_guestteam_score_penalty'] = 0;
	            	    $matchrounds[$i]['matchround_opponent_id'] = 0;
		                $matchrounds[$i]['match_id'] = 0;
		                $matchrounds[$i]['match_date'] = 0;
					}
                	//-----
                //if player did not play in this round
				} else {
					$matchrounds[$i]['matchround_minutes_played'] = '-';
    	            $matchrounds[$i]['matchround_score'] = '-';
        	        $matchrounds[$i]['matchround_goals'] = '-';
            	    $matchrounds[$i]['matchround_assists'] = '-';
                	$matchrounds[$i]['matchround_cards'] = 'n';

					//find out in which team the player played in this round
					$pt_in_round = $this->getTeamForPlayerAndRound($matchround_id, $pt_ids);
					if($pt_in_round) {
						$player_team_id = $pt_in_round->getPlayerteamTeamId();
						$matcharr = $this->getMatchdataForPlayerandRound($player_team_id, $matchround_id);
						if($matcharr) {
							$matchrounds[$i]['matchround_opponent_name'] = $matcharr['matchround_opponent_name'];
		          			$matchrounds[$i]['matchround_hometeam_name'] = $matcharr['matchround_hometeam_name'];
		          			$matchrounds[$i]['matchround_guestteam_name'] = $matcharr['matchround_guestteam_name'];
		          			$matchrounds[$i]['matchround_hometeam_score'] = $matcharr['matchround_hometeam_score'];
		        			$matchrounds[$i]['matchround_guestteam_score'] = $matcharr['matchround_guestteam_score'];
		        			$matchrounds[$i]['matchround_hometeam_score_penalty'] = $matcharr['matchround_hometeam_score_penalty'];
		        			$matchrounds[$i]['matchround_guestteam_score_penalty'] = $matcharr['matchround_guestteam_score_penalty'];
		  	    			$matchrounds[$i]['matchround_opponent_id'] = $matcharr['matchround_opponent_id'];
		            		$matchrounds[$i]['match_id'] = $matcharr['match_id'];
		            		$matchrounds[$i]['match_date'] = $matcharr['match_date'];
						} else {
							$matchrounds[$i]['matchround_opponent_name'] = 0;
		                    $matchrounds[$i]['matchround_hometeam_name'] = 0;
			                $matchrounds[$i]['matchround_guestteam_name'] = 0;
		    	            $matchrounds[$i]['matchround_hometeam_score'] = 0;
		        	        $matchrounds[$i]['matchround_guestteam_score'] = 0;
		        	        $matchrounds[$i]['matchround_hometeam_score_penalty'] = 0;
		        	        $matchrounds[$i]['matchround_guestteam_score_penalty'] = 0;
		            	    $matchrounds[$i]['matchround_opponent_id'] = 0;
			                $matchrounds[$i]['match_id'] = 0;
			                $matchrounds[$i]['match_date'] = 0;
						}
            		} else {
						$matchrounds[$i]['matchround_opponent_name'] = 0;
	                    $matchrounds[$i]['matchround_hometeam_name'] = 0;
		                $matchrounds[$i]['matchround_guestteam_name'] = 0;
	    	            $matchrounds[$i]['matchround_hometeam_score'] = 0;
	        	        $matchrounds[$i]['matchround_guestteam_score'] = 0;
	        	        $matchrounds[$i]['matchround_hometeam_score_penalty'] = 0;
	        	        $matchrounds[$i]['matchround_guestteam_score_penalty'] = 0;
	            	    $matchrounds[$i]['matchround_opponent_id'] = 0;
		                $matchrounds[$i]['match_id'] = 0;
		                $matchrounds[$i]['match_date'] = 0;
		            }
				}
				if(!$matchrounds[$i]['matchround_opponent_name']) {
					$matchrounds[$i]['matchround_opponent_name'] = 0;
				}
				if(!$matchrounds[$i]['matchround_hometeam_name']) {
					$matchrounds[$i]['matchround_hometeam_name'] = 0;
				}
				if(!$matchrounds[$i]['matchround_guestteam_name']) {
					$matchrounds[$i]['matchround_guestteam_name'] = 0;
				}
				if(!$matchrounds[$i]['matchround_hometeam_score']) {
					$matchrounds[$i]['matchround_hometeam_score'] = 0;
				}
				if(!$matchrounds[$i]['matchround_guestteam_score']) {
					$matchrounds[$i]['matchround_guestteam_score'] = 0;
				}
				if(!$matchrounds[$i]['matchround_hometeam_score_penalty']) {
					$matchrounds[$i]['matchround_hometeam_score_penalty'] = 0;
				}
				if(!$matchrounds[$i]['matchround_guestteam_score_penalty']) {
					$matchrounds[$i]['matchround_guestteam_score_penalty'] = 0;
				}
				if(!$matchrounds[$i]['matchround_opponent_id']) {
					$matchrounds[$i]['matchround_opponent_id'] = 0;
				}
				if(!$matchrounds[$i]['match_id']) {
					$matchrounds[$i]['match_id'] = 0;
				}
				if(!$matchrounds[$i]['match_date']) {
					$matchrounds[$i]['match_date'] = 0;
				}
				//-----
				$i++;
			}

			//calculate the averages
            $playerinfos[0]['match_count_total'] = $match_count_total;
			$playerinfos[0]['match_count_played'] = $match_count_played;
			if($match_count_played > 0) {
	            $playerinfos[0]['av_score'] = round($score/$match_count_played, 2);
			    $playerinfos[0]['av_goals'] = round($goals/$match_count_played, 2);
			    $playerinfos[0]['av_assists'] = round($assists/$match_count_played, 2);
			    $playerinfos[0]['av_minutes'] = round($minutes/$match_count_played, 2);
		    } else {
			    $playerinfos[0]['av_score'] = 0;
			    $playerinfos[0]['av_goals'] = 0;
			    $playerinfos[0]['av_assists'] = 0;
			    $playerinfos[0]['av_minutes'] = 0;
			}
		    if($match_count_total > 0) {
		       	$playerinfos[0]['match_count_percent'] = round($match_count_played/$match_count_total* 100.0, 2);
		    } else {
				$playerinfos[0]['match_count_percent'] = 0;
			}
			//-----

			//store to array
			$playerinfos[0]['sum_score'] = $score;
	        $playerinfos[0]['sum_goals'] = $goals;
	        $playerinfos[0]['sum_assists'] = $assists;
	        $playerinfos[0]['sum_cards_y'] = $cards_y;
	        $playerinfos[0]['sum_cards_r'] = $cards_r;
	        $playerinfos[0]['sum_cards_yr'] = $cards_yr;
	        $playerinfos[0]['sum_minutes'] = $minutes;

			$playerinfos[0]['player_picture'] = $picture_file;
	        $playerinfos[0]['player_team_name'] = $team_name;
	        $playerinfos[0]['player_team_nationality'] = $team_nationality;
	        $playerinfos[0]['player_fname'] = $player_fname;
	        $playerinfos[0]['player_lname'] = $player_lname;
	        $playerinfos[0]['player_nationality'] = $player_nationality;

			$playerinfos[0]['matchrounds'] = $matchrounds;
			//-----

			if(count($matchrounds) < 10) {
				//if(!($playerinfos[0]['pastmatches'] = $this->getPastMatches($playerteam_id, 10-count($matchrounds)))) {
				if(!($playerinfos[0]['pastmatches'] = $this->getPastMatches_v2($playerteam_id, 10-count($matchrounds)))) {
					$playerinfos[0]['pastmatches'] = 0;
				}
			} else {
				$playerinfos[0]['pastmatches'] = 0;
			}
			//print_r($playerinfos[0]['pastmatches']);
			//die();

       		return $playerinfos;
       	}
    }

	private function getPastMatches_v2($player_team_id, $num_matches) {
		$game_id = $this->session->game_id_player;
		$team = FfbPlayerteamPeer::retrieveByPK($player_team_id)->getFfbTeam();
		$criteria = new Criteria();
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, FfbMatchPeer::MATCH_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $player_team_id);
		$criteria->add(FfbMatchPeer::MATCH_DATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::GREATER_THAN);

		$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id, Criteria::NOT_EQUAL);

		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_ID);
		$criteria->addGroupByColumn(FfbMatchPeer::MATCH_ROUND);
		$criteria->setLimit($num_matches);

		$playerstats_items = FfbPlayerstatsPeer::doSelect($criteria);
		$pm_array = array();
		$i=0;

		if($playerstats_items) {
			foreach($playerstats_items as $item) {
				$match = $item->getFfbMatch();
				$matchround = $item->getFfbMatchround();
				$hometeam = $match->getFfbTeamRelatedByMatchHometeamId();
				$guestteam = $match->getFfbTeamRelatedByMatchGuestteamId();

				if($hometeam->getTeamId() == $team->getTeamId()) {
					$pm_array[$i]['matchround_opponent_id'] = $guestteam->getTeamId();
					$pm_array[$i]['matchround_opponent_name'] = $guestteam->getTeamName();
				} else {
					$pm_array[$i]['matchround_opponent_id'] = $hometeam->getTeamId();
					$pm_array[$i]['matchround_opponent_name'] = $hometeam->getTeamName();
				}

				$pm_array[$i]['matchround_hometeam_name'] = $hometeam->getTeamName();
				$pm_array[$i]['matchround_guestteam_name'] = $guestteam->getTeamName();
				$pm_array[$i]['matchround_hometeam_score'] = $match->getMatchHomescore();
				$pm_array[$i]['matchround_guestteam_score'] = $match->getMatchGuestscore();
				$pm_array[$i]['matchround_hometeam_score_penalty'] = $match->getMatchHomescorePenalty();;
				$pm_array[$i]['matchround_guestteam_score_penalty'] = $match->getMatchGuestscorePenalty();
				$pm_array[$i]['match_id'] = $match->getMatchId();
				$pm_array[$i]['match_date'] = date('d.m.Y', strtotime($match->getMatchDate()));

				$pm_array[$i]['matchround_num_lineups'] = $this->getNumberOfLineupsForMatchround($player_team_id, $matchround->getMatchroundId());
				$pm_array[$i]['matchround_id'] = $matchround->getMatchroundId();
				$pm_array[$i]['matchround_title'] = mb_convert_encoding((string)$matchround->getMatchroundTitle(), 'ISO-8859-1', 'UTF-8');
				$pm_array[$i]['matchround_running'] = 0;

				$pm_array[$i]['matchround_minutes_played'] = $item->getPlayerstatsMinutes();
				$pm_array[$i]['matchround_score'] = $item->getPlayerstatsScore();
				$pm_array[$i]['matchround_goals'] = $item->getPlayerstatsGoals();
				$pm_array[$i]['matchround_assists'] = $item->getPlayerstatsAssists();
				$pm_array[$i]['matchround_cards'] = $item->getPlayerstatsCards();

				$i++;
			}
		}

		//var_dump($playerstats_items);
		//print_r($pm_array);
		//die();

		return $pm_array;
	}

	private function getNumberOfLineupsForMatchround($player_team_id, $matchround_id) {
		$criteria = new Criteria();
		$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
		$c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $player_team_id);
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $player_team_id));
		$c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $player_team_id));
		$criteria->add($c1);

		return FfbUserteamPeer::doCount($criteria);
	}

	/* //not used anymore (buggy) -> getPastMatches_v2 used instead
    private function getPastMatches($player_team_id, $num_matches) {
		//$num_matches = 10;
		$game_id = $this->session->game_id_player;
    	$criteria = new Criteria();
    	$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_DATE_TRANSFER);
		$pt_id_items = FfbPlayerteamPeer::retrieveByPK($player_team_id)->getFfbPlayer()->getFfbPlayerteams($criteria);
    	$pt_ids = array();
    	$team_ids = array();
    	$criteria = new Criteria();
    	$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);
    	$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_GAME_ID, FfbGamePeer::GAME_ID);
    	$criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
    	$criteria->add(FfbGamePeer::GAME_ID, $game_id, Criteria::NOT_EQUAL);
    	$criteria->add(FfbMatchPeer::MATCH_DATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
    	$criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::GREATER_THAN);
		$last_item = false;
    	if($pt_id_items) {
			$i=0;
			foreach($pt_id_items as $item) {
				$pt_ids[] = $item->getPlayerteamId();
				$pt_status = $item->getPlayerteamStatus();
				$team_ids[] = $item->getFfbTeam()->getTeamId();
				$c2 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $item->getFfbTeam()->getTeamId());
				$c2->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $item->getFfbTeam()->getTeamId()));
				if($last_item !== false) {
					$c2->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_DATE, $last_item->getPlayerteamDateTransfer(), Criteria::LESS_THAN));
				} else {
					$c2->addAnd($criteria->getNewCriterion(FfbMatchPeer::MATCH_DATE, $item->getPlayerteamDateTransfer(), Criteria::GREATER_THAN));
				}
				$criteria->addOr($c2);
				$last_item = $item;
				$i++;
			}
		}
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_ID);
		$criteria->addGroupByColumn(FfbMatchPeer::MATCH_ROUND);
		$criteria->setLimit($num_matches);

		$match_items = FfbMatchPeer::doSelect($criteria);
		$pm_array = array();
		if($match_items) {
			$i=0;
			foreach($match_items as $item) {
				if($item->getMatchHometeamId() == $team_ids[$i]) {
					$pm_array[$i]['matchround_opponent_id'] = $item->getMatchGuestteamId();
					$pm_array[$i]['matchround_opponent_name'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
				} else {
					$pm_array[$i]['matchround_opponent_id'] = $item->getMatchHometeamId();
					$pm_array[$i]['matchround_opponent_name'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
				}
                $pm_array[$i]['matchround_hometeam_name'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                $pm_array[$i]['matchround_guestteam_name'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
   	            $pm_array[$i]['matchround_hometeam_score'] = $item->getMatchHomescore();
       	        $pm_array[$i]['matchround_guestteam_score'] = $item->getMatchGuestscore();
       	        $pm_array[$i]['matchround_hometeam_score_penalty'] = $item->getMatchHomescorePenalty();;
       	        $pm_array[$i]['matchround_guestteam_score_penalty'] = $item->getMatchGuestscorePenalty();
                $pm_array[$i]['match_id'] = $item->getMatchId();
                $pm_array[$i]['match_date'] = date('d.m.Y', strtotime($item->getMatchDate()));
                $matchround_id = $item->getMatchRound();
                $criteria = new Criteria();
                $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
                $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID1, $player_team_id);
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID2, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID3, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID4, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID5, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID6, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID7, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID8, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID9, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID10, $player_team_id));
                $c1->addOr($criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_PLAYER_ID11, $player_team_id));
                $criteria->add($c1);
                $num_lineups = FfbUserteamPeer::doCount($criteria);
                $criteria = new Criteria();
                $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $item->getMatchId());
                $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $player_team_id);
                $criteria->setLimit(1);
                $ps_items = FfbPlayerstatsPeer::doSelect($criteria);
                $pm_array[$i]['matchround_num_lineups'] = $num_lineups;
                $pm_array[$i]['matchround_id'] = $matchround_id;
	            $pm_array[$i]['matchround_title'] = mb_convert_encoding((string)$item->getFfbMatchround()->getMatchroundTitle(), 'ISO-8859-1', 'UTF-8');
				$pm_array[$i]['matchround_running'] = 0;
                if($ps_items) {
					$pm_array[$i]['matchround_minutes_played'] = $ps_items[0]->getPlayerstatsMinutes();
    	            $pm_array[$i]['matchround_score'] = $ps_items[0]->getPlayerstatsScore();
        	        $pm_array[$i]['matchround_goals'] = $ps_items[0]->getPlayerstatsGoals();
            	    $pm_array[$i]['matchround_assists'] = $ps_items[0]->getPlayerstatsAssists();
                	$pm_array[$i]['matchround_cards'] = $ps_items[0]->getPlayerstatsCards();
				} else {
					$pm_array[$i]['matchround_minutes_played'] = '-';
    	            $pm_array[$i]['matchround_score'] = '-';
        	        $pm_array[$i]['matchround_goals'] = '-';
            	    $pm_array[$i]['matchround_assists'] = '-';
                	$pm_array[$i]['matchround_cards'] = 'n';
				}

				$i++;
			}
		}
		return $pm_array;
	}
	*/

    private function getMatchdataForPlayerandRound($player_team_id, $matchround_id) {
		$criteria = new Criteria();
		$c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $player_team_id);
        $c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $player_team_id));
        $criteria->add($c1);
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $matchround_id);
        $criteria->setLimit(1);
        $match_item = FfbMatchPeer::doSelect($criteria);
        //the team of the player had a match in this round
        $matcharr = array();
        if($match_item) {
	       	$match = $match_item[0];
 	      	$home_team_name = $match->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
			$guest_team_name = $match->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
			$home_team_id = $match->getMatchHometeamId();
			$guest_team_id = $match->getMatchGuestteamId();
			$home_team_score = $match->getMatchHomeScore();
			$guest_team_score = $match->getMatchGuestScore();
			$home_team_score_penalty = $match->getMatchHomescorePenalty();
			$guest_team_score_penalty = $match->getMatchGuestscorePenalty();
			if($player_team_id == $home_team_id) {
				$opposite_team_name = $guest_team_name;
				$players_opponent_id = $guest_team_id;
			} else {
				$opposite_team_name = $home_team_name;
				$players_opponent_id = $home_team_id;
			}
           	$matcharr['matchround_opponent_name'] = $opposite_team_name;
          	$matcharr['matchround_hometeam_name'] = $home_team_name;
          	$matcharr['matchround_guestteam_name'] = $guest_team_name;
          	$matcharr['matchround_hometeam_score'] = $home_team_score;
        	$matcharr['matchround_guestteam_score'] = $guest_team_score;
        	$matcharr['matchround_hometeam_score_penalty'] = $home_team_score_penalty;
        	$matcharr['matchround_guestteam_score_penalty'] = $guest_team_score_penalty;
  	    	$matcharr['matchround_opponent_id'] = $players_opponent_id;
            $matcharr['match_id'] = $match->getMatchId();
            $matcharr['match_date'] = date('d.m.Y', strtotime($match->getMatchDate()));
		}
		return $matcharr;
	}

    private function getTeamForPlayerAndRound($matchround_id, $pt_ids) {
		$criteria = new Criteria();
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$playerteams = FfbPlayerteamPeer::doSelect($criteria);
		$matchround = FfbMatchroundPeer::retrieveByPK($matchround_id);

		$dist = 1000000000;
		$pt_near = null;
		foreach($playerteams as $pt) {
			$pt_time = strtotime($pt->getPlayerteamDateTransfer());
			$mr_time = strtotime($matchround->getMatchroundStartdate());
			$d = $mr_time-$pt_time;
			if($d < $dist && $d >= 0) {
				$pt_near = $pt;
				$dist = $d;
			}
		}

		if($pt_near === null) {
			foreach($playerteams as $pt) {
				$pt_time = strtotime($pt->getPlayerteamDateTransfer());
				$mr_time = strtotime($matchround->getMatchroundStartdate());
				$d = (-1)*($mr_time-$pt_time);
				if($d < $dist) {
					$pt_near = $pt;
					$dist = $d;
				}
			}
		}

		return $pt_near;
	}

    public function testDynPrice() {
		$this->prices = $this->getDynamicPrices($_REQUEST['playerteam_id']);
	}

    private function getDynamicPrices($playerteam_id, $sort_order="ASC") {
    	//echo 'ptid: '.$playerteam_id;
    	$player = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
		$ffb_player = $player->getFfbPlayer();
		//get all FFB_Playerteams for this player and store playerteam_ids into array
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
        $pt_ids = array();
        foreach($ffb_playerteams as $ffbpt) {
        	$pt_ids[] = $ffbpt->getPlayerteamId();
		}
        //-----

        //get all past and current matchrounds for this game
        $now = time();
        $now_string = date('Y-m-d H:i:s');
        $last_match_date = 0;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $now_string, Criteria::LESS_THAN);
    	if($sort_order == "DESC") {
    		$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	} elseif($sort_order == "ASC") {
    		$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    	}
        $past_matchrounds = FfbMatchroundPeer::doSelect($criteria);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $now_string, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->setLimit(1);
        $running_matchrounds = FfbMatchroundPeer::doSelect($criteria);

        $all_matchrounds = array_merge($past_matchrounds, $running_matchrounds);
        //------


		$prices = array();
		if($all_matchrounds) {
			//FIXX ME! get the player-standard-price for that matchround
	        $player = $this->getTeamForPlayerAndRound($all_matchrounds[0]->getMatchroundId(), $pt_ids);
			$player_price = $player->getPlayerteamPlayerPrice();
			//**

			$last_player_price = $player_price;
            $i=0;
            foreach($all_matchrounds as $matchround) {
                $last_match_date = $matchround->getMatchroundStartdate();
                $matchround_id = $matchround->getMatchroundId();
                $criteria = new Criteria();
                $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
                $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
                $criteria->setLimit(1);
                $playerprices = FfbPlayerpricePeer::doSelect($criteria);

                if($playerprices) {
					$playerprice = $playerprices[0];
					$prices[$i]['matchround_playerprice'] = $playerprice->getPlayerpricePrice();
           			$prices[$i]['matchround_playerpower'] = $playerprice->getPlayerpricePlayerPower();
          			$prices[$i]['matchround_av_playerpower'] = $playerprice->getPlayerpriceAvPower();
           			$last_player_price = $playerprice->getPlayerpricePrice();
				} else {
					$prices[$i]['matchround_playerprice'] = $last_player_price;
           			$prices[$i]['matchround_playerpower'] = 0;
          			$prices[$i]['matchround_av_playerpower'] = 0;
				}
				$prices[$i]['matchround_id'] = $matchround_id;
				$prices[$i]['matchround_title'] = $matchround->getMatchroundTitle();
				$i++;
            }
        }
        return $prices;
	}

    private function getGoalsList($opposite_team_id, $own_team_id, $match_id) {
        $criteria = new Criteria();
        $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);
        $criteria->addAscendingOrderByColumn(FfbGoalPeer::GOAL_MINUTE);

        $c1 = $criteria->getNewCriterion(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $opposite_team_id);
        $c1->addAnd($criteria->getNewCriterion(FfbGoalPeer::GOAL_OWNGOAL, 0));
        $c2 = $criteria->getNewCriterion(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $own_team_id);
        $c2->addAnd($criteria->getNewCriterion(FfbGoalPeer::GOAL_OWNGOAL, 1));

        $c1->addOr($c2);
        $criteria->add($c1);

        $goal_items = FfbGoalPeer::doSelectJoinAll($criteria);
        return $goal_items;
    }

    private function generatePlayerstatsImage(&$playerinfos) {
    	//return;
		if (!function_exists('imagecreatetruecolor')) {
			header('HTTP/1.1 503 Service Unavailable');
			error_log('[FFB] PHP GD extension is required for player chart images');
			return;
		}
		$count = count($playerinfos[0]['matchrounds'] ?? []);
    	$scoreDelimeter = 30; // x Punkte sind das quasi Maximum auf das wird auf 100px gerechner 100px == 30 ffbPunkte
    	$imgLength = max(400, $count*20);
    	$imgHeight = 151;
    	$posPlayerChartSize = 100;
    	$img = imagecreatetruecolor ($imgLength+1,$imgHeight);
  		$gruenBG =  imagecolorallocate ( $img ,204 , 255 , 204);
  		$dunkelgruenBG =  imagecolorallocate ( $img ,35 , 110 , 35);
  		$yellowBG = imagecolorallocate ( $img ,240 , 240 , 20);
  		$redBg = imagecolorallocate ( $img ,240 , 0 , 0);
  		$schwarzText = imagecolorallocate ( $img ,0 , 0 , 0);
  		$rotText = imagecolorallocate ( $img ,210 , 0 , 0);
  		$darkblueBG = imagecolorallocate ( $img ,25 , 25 , 225);
  		$grayBG = imagecolorallocate ( $img ,230 , 225 , 225);
  		$darkGrayBG = imagecolorallocate ( $img ,100 , 100 , 100);
  		$someOrangeBG = imagecolorallocate ( $img ,255 , 128 , 0);
  		$whiteBG = imagecolorallocate ( $img ,255 , 255 , 255);
  		$filled = imagefill ( $img , 0 , 0 , $gruenBG );
  		$index = 0;


		if($count)
  			$colLen = (int)round(($imgLength / $count), 0);
		else
			$colLen = 1;

		$normalizedLineup = 0;
		if ($count > 0) {
			$normalizedLineup = (int)round(((float)($playerinfos[0]['matchrounds'][0]['matchround_minutes_played'] ?? 0))/1.2/100.0*$posPlayerChartSize/2.0,0);
		}
  		foreach($playerinfos[0]['matchrounds'] as $elem) {
  			imagedashedline ( $img , (int)($index*$colLen) , 0 , (int)($index*$colLen) , $imgHeight , $darkGrayBG );
  			$fillColor = $dunkelgruenBG;
  			if($elem['matchround_cards']=='y') {
  				//$fillColor = $yellowBG;
  				imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , 1 , (int)((($index+1)*$colLen)-1) , $imgHeight-2 , $yellowBG );
  			} elseif ($elem['matchround_cards']=='r') {
  				//$fillColor = $redBg;
  				imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , 1 , (int)((($index+1)*$colLen)-1) , $imgHeight-2 , $redBg );
  			} elseif ($elem['matchround_cards']=='yr') {
  				imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , 1 , (int)((($index+1)*$colLen)-1) , $imgHeight-2 , $redBg );
  			}
  			if($elem['matchround_minutes_played']!='-') {
  				$normalizedFFBPoints = (int)round(((float)($elem['matchround_score'] ?? 0))/(((float)$scoreDelimeter)/100), 0);
  				$upDownStart = $posPlayerChartSize;
  				$upDownEnd = $posPlayerChartSize-$normalizedFFBPoints;
  				if($normalizedFFBPoints<0){
  					$upDownStart = 101;
  					$upDownEnd = 102+(-1*$normalizedFFBPoints);
  					imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , $upDownStart , (int)((($index+1)*$colLen)-1) , $upDownEnd , $someOrangeBG );
				} else {
  					imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , $upDownStart , (int)((($index+1)*$colLen)-1) , $upDownEnd , $dunkelgruenBG );
				}
				$statsImgWidth = 15;
				if($statsImgWidth > $colLen)
					$statsImgWidth = $colLen;
  				if($elem['matchround_goals']) {
  					$statsImgHeight = $statsImgWidth / (55.0/100.0) / 100.0;
  					$statsImgHeight = (int)round($statsImgHeight * 55);
  					$cntGoals = 0;
  					$goal = imagecreatefromgif("images/ffb/symbols/stats_goal.gif");
  					while($cntGoals++<$elem['matchround_goals']) {
  						imagecopyresized($img, $goal, (int)(($index*$colLen)+2), (int)($upDownStart-2-$cntGoals*12), 0,0,$statsImgWidth,$statsImgHeight,55,55);
  					}
  					imagedestroy($goal);
  				}
  				if($elem['matchround_assists']) {
  					$statsImgHeight = $statsImgWidth / (38.0/100.0) / 100.0;
  					$statsImgHeight = (int)round($statsImgHeight * 42);
  					$cntAssists = 0;
  					$assist = imagecreatefromgif("images/ffb/symbols/stats_assist.gif");
  					while($cntAssists++<$elem['matchround_assists']) {
  						imagecopyresized($img, $assist, (int)(($index*$colLen)+2), (int)($upDownStart-5+$cntAssists*12), 0,0,$statsImgWidth,$statsImgHeight,38,42);
  					}
  					imagedestroy($assist);
  				}

  				imagestring($img,3,(int)($index*$colLen+$colLen/2-1), (int)($upDownEnd-2), $elem['matchround_score'], $schwarzText);

			} else {
				imagefilledrectangle ( $img , (int)(($index*$colLen)+1) , 1 , (int)((($index+1)*$colLen)-1) , $imgHeight-2 , $grayBG );
			}


	 		if($elem['matchround_minutes_played']=='-')
	 			$normalizedLineup2 = 0;
 			else {
 				$normalizedLineup2 = (int)round((((float)($elem['matchround_minutes_played'] ?? 0))/1.2)/100.0*$posPlayerChartSize, 0);	//120 minutes match duration -> 120/100 = 1.2
 				imagestring($img,3,(int)($index*$colLen+($colLen/2)-1), (int)($posPlayerChartSize-$normalizedLineup2+2), $elem['matchround_minutes_played'], $darkblueBG);
			}
			if($index) {
 				imageline ( $img , (int)($index*$colLen-($colLen/2)) , (int)($posPlayerChartSize-$normalizedLineup) , (int)($index*$colLen+$colLen/2) , (int)($posPlayerChartSize-$normalizedLineup2) , $schwarzText );
			}
 			$normalizedLineup = $normalizedLineup2;
		  	$index++;
  		}

  		imageline ( $img , 0 , 0 , $imgLength , 0 , $darkGrayBG );
  		imageline ( $img , 0 , $imgHeight-1 , $imgLength , $imgHeight-1 , $darkGrayBG );
		imageline ( $img , 0 , 101 , $imgLength , 101 , $darkGrayBG );
  		imagedashedline ( $img , $imgLength , 0 , $imgLength , $imgHeight , $darkGrayBG );

		//Ersatz imagedashedline BUG in Horizontale
		for($i=1;$i<$imgLength-4;$i+=10) {
			imageline ( $img , $i , 25 , $i+1 , 25 , $darkGrayBG );
		}
		imagestring($img, 1, 2, 17, "90min", $darkGrayBG);
		imagestring($img, 6, $imgLength-10, $posPlayerChartSize-16 , "+", $darkblueBG);
		imagestring($img, 6, $imgLength-10, $posPlayerChartSize , "-", $darkblueBG);

  		$this->img = $img;
    	return;
    }


    function generatePlayerstatsImageDynamicPrices(&$playerinfos) {
		$playerinfo_prices = $this->getDynamicPrices($playerinfos[0]['playerteam_id']);

    	$backslashFONT = "images/ffb/ttffont/Backslash.ttf";
    	//$count = count($playerinfos[0]['prices']);
    	$count = count($playerinfo_prices);
		$scoreDelimeter = 22; // x Punkte sind das quasi Maximum auf das wird auf 100px gerechner 100px == 30 ffbPunkte
    	$imgLength = 400;//max(400, $count*20);
    	$imgHeight = 121;
    	$imgZeroLine	= 101;
    	$posPlayerChartSize = 100;
		$colLen = $imgLength;
		if($count>1)
		$colLen = (int)round(($imgLength / ($count-1)), 0);
		else
			$colLen = $imgLength;
		//if(!$colLen)
		//	$colLen	= $imgLength;
		$roundSizeText = (int)min(round($colLen/2, 0), 14);
		//if($roundSizeText<6)
		//	$roundSizeText=6;
		$lastPriceTitle = ($count > 0) ? (string)($playerinfo_prices[$count-1]['matchround_title'] ?? '') : '';
		$roundLenText = (int)round(($roundSizeText/2)*strlen($lastPriceTitle)/11, 0);
		//if($roundLenText<5 ||  $roundLenText==NaN)
		//	$roundLenText=5;
		$extraLen = 40;
		if (!function_exists('imagecreatetruecolor')) {
			header('HTTP/1.1 503 Service Unavailable');
			error_log('[FFB] PHP GD extension is required for player chart images');
			return;
		}
    	$img = imagecreatetruecolor ( (int)($imgLength+$extraLen), (int)($imgHeight+$roundLenText*10));
    	$roundLenText-=4;
  		$gruenBG =  imagecolorallocate ( $img ,204 , 255 , 204);
  		$dunkelgruenBG =  imagecolorallocate ( $img ,35 , 110 , 35);
  		$yellowBG = imagecolorallocate ( $img ,240 , 240 , 20);
  		$redBg = imagecolorallocate ( $img ,240 , 0 , 0);
  		$schwarzText = imagecolorallocate ( $img ,0 , 0 , 0);
  		$rotText = imagecolorallocate ( $img ,210 , 0 , 0);
  		$darkblueBG = imagecolorallocate ( $img ,25 , 25 , 225);
  		$grayBG = imagecolorallocate ( $img ,230 , 225 , 225);
  		$darkGrayBG = imagecolorallocate ( $img ,100 , 100 , 100);
  		$someOrangeBG = imagecolorallocate ( $img ,255 , 128 , 0);
  		$whiteBG = imagecolorallocate ( $img ,255 , 255 , 255);
  		//$lightGreenBG = imagecolorallocate($img, 215,245,215);
  		$someKindBlueBG = imagecolorallocatealpha ( $img ,175 , 240 , 240, 70);
  		$filled = imagefill ( $img , 0 , 0 , $gruenBG );
  		$index = 0;

		$avPlayerPower = 1;
		$avPlayerPrice = 0;
		//$playerPrice = round($playerinfos[0]['prices'][0]['matchround_playerprice']/($scoreDelimeter/100),0);
		$playerPrice = 0;
		if ($count > 0 && isset($playerinfo_prices[0]['matchround_playerprice'])) {
			$playerPrice = round(((float)$playerinfo_prices[0]['matchround_playerprice'])/(((float)$scoreDelimeter)/100),0);
		}
		$playerPower = 0;
		$playerPrice2 = $playerPrice;
		$playerPower2 = $playerPower;

		$kubsplineXYPlayerPrice = array();
		$kubsplineXYPlayerPower = array();

		//echo "<pre>";
		//print_r($playerinfos[0]['prices']);
		//echo "</pre>";
		//foreach($playerinfos[0]['prices'] as $elem) {

		foreach($playerinfo_prices as $elem) {
			//print_r($elem);
			imagesetthickness($img,1);
			$avNormalizedPower = (int)round($elem['matchround_av_playerpower']/($scoreDelimeter/100),0);
			imagefilledrectangle($img,(int)($index*$colLen-$colLen+1), $posPlayerChartSize, (int)($index*$colLen-1), $posPlayerChartSize-$avNormalizedPower, $someKindBlueBG);
  			imagedashedline ( $img , (int)($index*$colLen) , 0 , (int)($index*$colLen) , $imgZeroLine/*$imgHeight-1+$roundLenText*10 */, $darkGrayBG );
			$avPlayerPrice += $elem['matchround_playerprice'];
		 	$avPlayerPower += $elem['matchround_playerpower'];

			imagesetthickness($img,2);
			$playerPrice2 = (int)round($elem['matchround_playerprice']/($scoreDelimeter/100),0);
			$playerPower2 = (int)round($elem['matchround_playerpower']/($scoreDelimeter/100),0);


			//linear player price drawing
			//imageline($img, $index*$colLen-$colLen, $posPlayerChartSize-$playerPrice, $index*$colLen, $posPlayerChartSize-$playerPrice2, $schwarzText);

			//linear player power drawing
			//imageline($img, $index*$colLen-$colLen, $posPlayerChartSize-$playerPower, $index*$colLen, $posPlayerChartSize-$playerPower2, $rotText);
			$textMarginBlack	= 2;
			$textMarginRed		= -10;
			if( ($index % 2)==0 ) {
				$textMarginBlack 	= -10;
				$textMarginRed		= 2;

			}


			imagestring($img, 1, (int)($index*$colLen+2),   (int)($posPlayerChartSize-$playerPrice2+$textMarginBlack), number_format($elem['matchround_playerprice'],1,'.','')  , $schwarzText );
			imagestring($img, 1, (int)($index*$colLen+2),   (int)($posPlayerChartSize-$playerPower2+$textMarginRed), number_format($elem['matchround_playerpower'],1,'.','') , $rotText );
/*
				imagettftext($img, $roundSizeText, 270, $index*$colLen+2, $imgHeight+1, $schwarzText, $backslashFONT, strrev(strtok(strrev($elem['matchround_title']), " ")));

				if(strlen($elem['matchround_title'])>$roundLenText && $index<$count-1) {
					imagettftext($img, $roundSizeText, 270, $index*$colLen+$colLen-1-$roundSizeText, $imgHeight+1, $schwarzText, $backslashFONT, substr($elem['matchround_title'],0,$roundLenText));
					$tmp = substr($elem['matchround_title'], $roundLenText, strlen($elem['matchround_title'])-$roundLenText);
					if(strlen($tmp)>$roundLenText) {
						imagettftext($img, $roundSizeText, 270, $index*$colLen+$colLen-round($colLen/2, 0), $imgHeight+1, $schwarzText, $backslashFONT, substr($tmp,0,$roundLenText));
						$tmp = substr($tmp, $roundLenText, strlen($tmp)-$roundLenText);
						imagettftext($img, $roundSizeText, 270, $index*$colLen+$roundSizeText, $imgHeight+1, $schwarzText, $backslashFONT, $tmp);
					} else {
						imagettftext($img, $roundSizeText, 270, $index*$colLen+$colLen/2-$roundSizeText-1, $imgHeight+1, $schwarzText, $backslashFONT, $tmp);
					}
				} else {
					imagettftext($img, $roundSizeText, 270, $index*$colLen+$colLen-round($colLen/2, 0), $imgHeight+1, $schwarzText, $backslashFONT, $elem['matchround_title']);
				}*/

  			$playerPrice = $playerPrice2;
  			$playerPower = $playerPower2;
			$kubsplineXYPlayerPrice[$index][0] = $colLen*$index;
			$kubsplineXYPlayerPrice[$index][1] = $playerPrice;
			$kubsplineXYPlayerPower[$index][0] = $colLen*$index;
			$kubsplineXYPlayerPower[$index][1] = $playerPower;
  			$index++;

		}

		if($count>1) {
		//kubic spline player price drawing
		require_once('modules/ffbapi/kubspline.php');
		$kubspline = new KUBSPLINE($kubsplineXYPlayerPrice, $colLen);
		$yVals = $kubspline->getY();
		for($i=1;$i<count($yVals);$i++) {
			imageline($img, $i-1, (int)round($posPlayerChartSize-$yVals[$i-1]), $i, (int)round($posPlayerChartSize-$yVals[$i]), $schwarzText);
		}
		//kubic spline player power drawing
		$kubspline = new KUBSPLINE($kubsplineXYPlayerPower, $colLen);
		$yVals = $kubspline->getY();
		for($i=1;$i<count($yVals);$i++) {
			imageline($img, $i-1, (int)round($posPlayerChartSize-$yVals[$i-1]), $i, (int)round($posPlayerChartSize-$yVals[$i]), $rotText);
		}


		imagesetthickness($img,1);
		if($count) {
			$avPlayerPrice = (int)round($posPlayerChartSize-($avPlayerPrice/$count/($scoreDelimeter/100)));
			$avPlayerPower = (int)round($posPlayerChartSize-($avPlayerPower/$count/($scoreDelimeter/100)));
			for($i=0;$i<$imgLength;$i+=10) {
				imageline ( $img , $i , $avPlayerPrice , $i+2 , $avPlayerPrice , $schwarzText );
				imageline ( $img , $i , $avPlayerPower , $i+2 , $avPlayerPower , $rotText );
			}
		}
		}

		imageline ( $img , 0 , 0 , $imgLength+40 , 0 , $darkGrayBG );
  		imageline ( $img , 0 , $imgHeight-1 , $imgLength+40 , $imgHeight-1 , $darkGrayBG );
		imageline ( $img , 0 , $imgZeroLine , (int)round($imgLength+$colLen/2) , $imgZeroLine , $darkGrayBG );
		imagestring($img, 6, $imgLength+10, $posPlayerChartSize-14 , "+", $darkblueBG);
		imagestring($img, 6, $imgLength+10, $posPlayerChartSize , "-", $darkblueBG);

  		$this->img = $img;
    }

}

?>