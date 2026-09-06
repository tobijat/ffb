<?php

/**
 * FFB - USERSCORE-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.2
 *
 */

class userscore extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();
        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
		$this->htmlFile = 'userscore.php';
		$this->htmlTitle = 'User Scores';

        /*
        $user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    	*/
    }

    public function getUserscoresForRound() {
        $game_id = $this->session->game_id_player;
        $game_rankmode = $this->options->options_game_rankmode;
        $win_array = $this->returnGetMatchroundWins();
        if (!is_array($win_array)) {
            $win_array = array();
        }
        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $_POST['matchround_id'] ?? 0);
        $userteams = FfbUserteamPeer::doSelect($criteria);

        $users = array();
        $i = 0;
        if($userteams) {
            foreach($userteams as $userteam) {
            	$user_details = WebUserDetailsPeer::retrieveByPK($userteam->getWebUser()->getUserId());
                $users[$i]['user_id'] = $userteam->getWebUser()->getUserId();
                $users[$i]['user_nickname'] = $userteam->getWebUser()->getUserNickname();
                if($userteam->getWebUser()->getUserNationality())
                    { $users[$i]['user_nationality'] = $userteam->getWebUser()->getUserNationality(); }
                else
                    { $users[$i]['user_nationality'] = 0; }
                $fav_team = $user_details ? $user_details->getFfbTeamRelatedByUserDetailsFfbFavouriteTeam() : null;
				if($fav_team) {
					$users[$i]['user_favourite_team_nationality'] = $fav_team->getTeamNationality();
				} else {
					$users[$i]['user_favourite_team_nationality'] = 0;
				}
				$users[$i]['user_favourite'] = 0;
                $users[$i]['user_score'] = $userteam->getUserteamScore();
                $users[$i]['user_wc_points'] = $userteam->getUserteamWcPoints();

                $now = time();
                $date = date('Y-n-j G:i:s', $now);//$date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
                $criteria = new Criteria();
                $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
                $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
                $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $userteam->getWebUser()->getUserId());
                $userteamsJoined = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria);
                $users[$i]['participations'] = count($userteamsJoined);

                $criteria = new Criteria();
                $criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $userteam->getWebUser()->getUserId());
                $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
                $userscoreitems = FfbUserscorePeer::doSelect($criteria);

                if(count($userteamsJoined) && $userscoreitems) {
                    $users[$i]['user_score_av'] = round(($userscoreitems[0]->getUserscoreTotal())/($users[$i]['participations']),0);
                } else {
                    $users[$i]['user_score_av'] = 0;
                }

                $nick = $userteam->getWebUser()->getUserNickname();
                if(!empty($win_array[$nick])) {
                    $users[$i]['matchround_wins'] = $win_array[$nick];
                } else {
                    $users[$i]['matchround_wins'] = 0;
                }

                $i++;
            }
        }

        //do the sortations
		if($users) {
			$this->numResults = $i;
	        foreach($users as $item) {
	            $points[] = $item['user_score'];
	            $names[] = strtolower($item['user_nickname']);
	        }
            array_multisort($points, SORT_DESC, $names, SORT_ASC, SORT_STRING, $users);

	        //calculate ranks
	        $iloop = 1; //rank
	        $jloop = 1;
	        //$last_score = -1;
	        $last_points_score = -1;
	        $i = 0;
	        unset($points);
	        unset($names);
	        foreach($users as $item) {
				if($item['user_score'] < $last_points_score) {
					$iloop = $jloop;
					$jloop++;
				} else {
					$jloop++;
				}
				$last_points_score = $item['user_score'];
				$users[$i]['user_rank'] = $iloop;
				$points[] = $item['user_score'];
	            $names[] = strtolower($item['user_nickname']);
	            $parts[] = $item['participations'];
	            $wins[] = $item['matchround_wins'];
				$ranks[] = $iloop;
				$i++;
			}
			// *****

	        if(($_POST['sort_dir'] ?? '') == "asc") {
				$sort_dir = SORT_ASC;
			} else {
				$sort_dir = SORT_DESC;
			}

	        if(($_POST['sort_flag'] ?? '') == "n") {
				array_multisort($names, $sort_dir, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "p") {
				array_multisort($parts, $sort_dir, $points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "w") {
				array_multisort($wins, $sort_dir, $points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "r") {
				array_multisort($ranks, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} else {
	            array_multisort($points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			}
        } else {
			$this->numResults = -1;
		}
        // *****

        $this->rankMode = $game_rankmode;
        $this->users = $users;
    }

    //Userranking
    public function getUserscore() {
		$game_id = $this->session->game_id_player;
        $game_rankmode = $this->options->options_game_rankmode;

		if(!($win_array = $this->returnGetMatchroundWins())) {
			$this->numResults = -1;
			//$this->users = $users;
			$this->rankMode = $game_rankmode;
			$win_array = array();
			//return;
			//no players in this list
		}

        $criteria = new Criteria();
        $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
        $scores = FfbUserscorePeer::doSelect($criteria);

        $users = array();
        $i = 0;
        if($scores) {
            foreach($scores as $scoreitem) {
            	$user_details = WebUserDetailsPeer::retrieveByPK($scoreitem->getWebUser()->getUserId());
				$users[$i]['user_id'] = $scoreitem->getWebUser()->getUserId();
                $users[$i]['user_nickname'] = $scoreitem->getWebUser()->getUserNickname();
                if($scoreitem->getWebUser()->getUserNationality())
                    { $users[$i]['user_nationality'] = $scoreitem->getWebUser()->getUserNationality(); }
                else
                    { $users[$i]['user_nationality'] = 0; }

                $fav_team = $user_details ? $user_details->getFfbTeamRelatedByUserDetailsFfbFavouriteTeam() : null;
				if($fav_team) {
					$users[$i]['user_favourite_team_nationality'] = $fav_team->getTeamNationality();
				} else {
					$users[$i]['user_favourite_team_nationality'] = 0;
				}

                $users[$i]['user_score'] = $scoreitem->getUserscoreTotal();
                $users[$i]['user_wc_points'] = $scoreitem->getUserscoreWcPoints();

                $now = time();
                $date = date('Y-n-j G:i:s', $now);//.'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
                $criteria = new Criteria();
                $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
                $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
                $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $scoreitem->getWebUser()->getUserId());
                $userteams = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria);
                $users[$i]['participations'] = count($userteams);
                if(count($userteams)>0) {
                    $users[$i]['user_score_av'] = round($scoreitem->getUserscoreTotal()/(count($userteams)),0);
                } else {
                    $users[$i]['user_score_av'] = 0;
                }
                if(!empty($win_array[$scoreitem->getWebUser()->getUserNickname()])) {
                    $users[$i]['matchround_wins'] = $win_array[$scoreitem->getWebUser()->getUserNickname()];
                } else {
                    $users[$i]['matchround_wins'] = 0;
                }

                $userteamlist = array();

                $i++;
            }
        }
		//do the sortations
		if($users) {
	        foreach($users as $item) {
	            $points[] = $item['user_score'];
	            $wc_points[] = $item['user_wc_points'];
	            $names[] = strtolower($item['user_nickname']);
	        }

	        //order with points
	        if($game_rankmode == 'points') {
	            array_multisort($points, SORT_DESC, $wc_points, SORT_DESC, $names, SORT_ASC, SORT_STRING, $users);
	        } else if($game_rankmode == 'wc') {
	            array_multisort($wc_points, SORT_DESC, $points, SORT_DESC, $names, SORT_ASC, SORT_STRING, $users);
	        }
	        //calculate ranks
	        $iloop = 1; //rank
	        $jloop = 1;
	        $last_score = -1;
	        $last_points_score = -1;
	        $i = 0;
	        unset($points);
	        unset($wc_points);
	        unset($names);
	        foreach($users as $item) {
				if($item['user_wc_points'] < $last_score || $item['user_score'] < $last_points_score) {
					$iloop = $jloop;
					$jloop++;
				} else {
					$jloop++;
				}
				$last_score = $item['user_wc_points'];
				$last_points_score = $item['user_score'];
				$users[$i]['user_rank'] = $iloop;
				$points[] = $item['user_score'];
	            $wc_points[] = $item['user_wc_points'];
	            $names[] = strtolower($item['user_nickname']);
	            $parts[] = $item['participations'];
	            $wins[] = $item['matchround_wins'];
				$ranks[] = $iloop;
				$i++;
			}
			// *****

	        if(($_POST['sort_dir'] ?? '') == "asc") {
				$sort_dir = SORT_ASC;
			} else {
				$sort_dir = SORT_DESC;
			}

	        if(($_POST['sort_flag'] ?? '') == "n") {
				array_multisort($names, $sort_dir, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "p") {
				array_multisort($parts, $sort_dir, $wc_points, $sort_dir, $points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "w") {
				array_multisort($wins, $sort_dir, $wc_points, $sort_dir, $points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} elseif(($_POST['sort_flag'] ?? '') == "r") {
				array_multisort($ranks, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
			} else {
				if($game_rankmode == 'points') {
	                array_multisort($points, $sort_dir, $wc_points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
	            } else if($game_rankmode == 'wc') {
	                array_multisort($wc_points, $sort_dir, $points, $sort_dir, $names, SORT_ASC, SORT_STRING, $users);
	            }
			}
        }
        // *****

        $this->numResults = $i;
        $this->rankMode = $game_rankmode;
        $this->users = $users;
    }

	public function testReturnGetMatchroundWins() {
		print_r($this->returnGetMatchroundWins_v2());
		die();
	}

	public function returnGetMatchroundWins() {
        $game_id = $this->session->game_id_player;
		$date = date("Y-m-d H:i:s", time());
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $rounds = FfbMatchroundPeer::doSelect($criteria);
        if(!$rounds) {
            return false;
        } else {
            $win_array = array();
            foreach($rounds as $round) {
                $criteria = new Criteria();
                $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $round->getMatchroundId());
                $criteria->add(FfbUserteamPeer::USERTEAM_SCORE, 0, Criteria::GREATER_THAN);
                $criteria->addDescendingOrderByColumn(FfbUserteamPeer::USERTEAM_SCORE);
                $userteam = FfbUserteamPeer::doSelect($criteria);
                if($userteam) {
                    $nick0 = $userteam[0]->getWebUser()->getUserNickname();
                    if(!empty($win_array[$nick0])) {
                        $win_array[$nick0]++;
                    } else {
                        $win_array[$nick0] = 1;
                    }
                    if(isset($userteam[1]) && ($userteam[0]->getUserteamScore() == $userteam[1]->getUserteamScore())) {
                        for($i=0; $i<count($userteam); $i++) {
                            if($userteam[$i] == $userteam[0]) {
                                if(!isset($userteam[$i+1])) {
                                    break;
                                }
                                $nickNext = $userteam[$i+1]->getWebUser()->getUserNickname();
                                if(!empty($win_array[$nickNext])) {
                                    $win_array[$nickNext]++;
                                } else {
                                    $win_array[$nickNext] = 1;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
            return $win_array;
        }
    }
/*
    public function returnGetMatchroundWins() {
        $game_id = $this->session->game_id_player;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $rounds = FfbMatchroundPeer::doSelect($criteria);
        if(!$rounds) {
            return false;
        } else {
            $win_array = array();
            foreach($rounds as $round) {
                $criteria = new Criteria();
                $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $round->getMatchroundId());
                $criteria->add(FfbUserteamPeer::USERTEAM_SCORE, 0, Criteria::GREATER_THAN);
                $criteria->addDescendingOrderByColumn(FfbUserteamPeer::USERTEAM_SCORE);
                $userteam = FfbUserteamPeer::doSelect($criteria);
                if($userteam) {
                    if($win_array[$userteam[0]->getWebUser()->getUserNickname()]) {
                        $win_array[$userteam[0]->getWebUser()->getUserNickname()]++;
                    } else {
                        $win_array[$userteam[0]->getWebUser()->getUserNickname()] = 1;
                    }
                    if($userteam[1] && ($userteam[0]->getUserteamScore() == $userteam[1]->getUserteamScore())) {
                        for($i=0; $i<count($userteam); $i++) {
                            if($userteam[$i] == $userteam[0]) {
                                if($win_array[$userteam[$i+1]->getWebUser()->getUserNickname()]) {
                                    $win_array[$userteam[$i+1]->getWebUser()->getUserNickname()]++;
                                } else {
                                    $win_array[$userteam[$i+1]->getWebUser()->getUserNickname()] = 1;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
            return $win_array;
        }
    }
*/
}
?>