<?php

/**
 * ADMIN - MATCH POINTS-Klasse;
 * Punkte fuer Spieler der Begegnungen hinzufuegen
 *
 * @author Gritschacher, Musser
 * @copyright 07/2008
 * @version 0.2
 *
 */

class matchpoints extends FFB_Auth_AdminFfb {

    private $options;
    private $counts = array('plus'=>0, 'minus'=>0);

    //array for the WC-Points (ranks 1-5)
    //private $wc_scores = array(10, 7, 4, 2, 1);
    //private $wc_scores = array(10, 8, 6, 5, 4, 3, 2, 1);
    private $dynamic_price_settings = array('margin'=>2);

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'matchpoints.php';
        $this->options = new FFB_Options($this->session->game_id_admin);
        //ini_set("memory_limit","128M");
    }

    public function __default() {
    }

    public function testMr() {
        $game_id = $this->session->game_id_admin;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $matchrounds = FfbMatchroundPeer::doSelect($criteria);
        echo 'MR: '.count($matchrounds).'<br>';
        exit();
    }

    public function config() {
        $this->getMatchrounds();
        $this->htmlFile = 'matchpoints_config.php';
    }

    private function getMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $game_id = $this->session->game_id_admin;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $items = FfbMatchroundPeer::doSelect($criteria);
        $matchrounds = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $matchrounds[$i]['matchround_id'] = $item->getMatchroundId();
                $matchrounds[$i]['matchround_title'] = $item->getMatchroundTitle();
                $matchrounds[$i]['matchround_status'] = $item->getMatchroundStatus();
                $matchrounds[$i]['matchround_game_id'] = $item->getMatchroundGameId();
                $matchrounds[$i]['matchround_game_title'] = $item->getFfbGame()->getGameTitle();
                $matchrounds[$i]['matchround_startdate'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $matchrounds[$i]['matchround_enddate'] = date('j.n.Y G:i',strtotime($item->getMatchroundEnddate()));
                $matchrounds[$i]['matchround_deadline'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $i++;
            }
        }
        $this->numResults = $i;
        $this->matchrounds = $matchrounds;
        return;
    }

    //set all userteam-scores to the sum of all player-scores in the lineup of the specific user
    public function setUserteamScore() {
        $this->htmlFile = 'matchpoints_config.php';
        $userteamscore = array();
        $game_id = $this->session->game_id_admin;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $matchrounds = FfbMatchroundPeer::doSelect($criteria);
        $userteam_items = array();
        //print_r($matchrounds);
        //exit();
        $i = 0;
        if($matchrounds) {
            foreach($matchrounds as $matchround) {
                //print_r($matchround->getFfbUserteams());
                //exit();
                //echo '<br><br>';
                //echo "round: $i<br>";
                $i++;
                $userteam_items = array_merge($userteam_items,(array)$matchround->getFfbUserteams());
                //echo $matchround->getMatchroundTitle().'/'.count($userteam_items).'<br>';
            }
        }

        if($userteam_items) {
            foreach($userteam_items as $userteam_item) {
                $score = 0;
                //echo "mrid: ".$userteam_item->getUserteamMatchroundId()."<br>";
                /*
                $criteria = new Criteria();
                $criteria->addAnd(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $userteam_item->getUserteamMatchroundId());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId1());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId2());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId3());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId4());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId5());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId6());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId7());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId8());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId9());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId10());
                $criteria->addOr(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId11());
                */
                    $criteria = new Criteria();
                    $c1 = $criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId1());
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId2()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId3()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId4()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId5()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId6()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId7()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId8()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId9()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId10()));
                    $c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $userteam_item->getUserteamPlayerId11()));
                    $criteria->add($c1);
                    $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $userteam_item->getUserteamMatchroundId());

                $playerstatsitems = FfbPlayerstatsPeer::doSelect($criteria);
                //echo "num players: ".count($playerstatsitems)."<br>";
                //exit();
                $score = 0;
                if($playerstatsitems) {
                    foreach($playerstatsitems as $playerstatsitem) {
                        $score += $playerstatsitem->getPlayerstatsScore();
                    }
                }
                $userteamscore[$userteam_item->getUserteamId()] = $score;
            }
        }

        $answer = '<b>Userteam Scores successfully updated!</b><br>';

        foreach($userteamscore as $userteam_id=>$userteam_score) {
            $userteamitem = FfbUserteamPeer::retrieveByPK($userteam_id);
            $userteamitem->setUserteamScore($userteam_score);
            $userteamitem->save();

            $answer .= 'userteam_id: '.$userteam_id;
            $answer .= ' score: '.$userteam_score.'<br>';
        }
        $this->administration_answer = $answer;

        $this->setWCPoints();
    }

    //set all userscores to the sum of all userteam-scores of the specific user
    public function setUserScore() {
        $this->htmlFile = 'matchpoints_config.php';

        $game_id = $this->session->game_id_admin;

        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);

        $rounds = FfbMatchroundPeer::doSelect($criteria);
        $userscore = array();
        $wc_score = array();
        $criteria = new Criteria();
        //echo count($rounds).'<br>';
        //exit();
        if($rounds) {
            foreach($rounds as $round) {
                $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $round->getMatchroundId());
                $criteria->addOr($c1);
            }
            $criteria->addGroupByColumn(FfbUserteamPeer::USERTEAM_USER_ID);
            $userteams = FfbUserteamPeer::doSelect($criteria);

            if($userteams) {
                foreach($userteams as $team) {
                    $score = 0;
                    $wcscore = 0;
                    $criteria = new Criteria();;
                    foreach($rounds as $round) {
                        $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $round->getMatchroundId());
                        $criteria->addOr($c1);
                    }
                    $criteria->addAnd(FfbUserteamPeer::USERTEAM_USER_ID, $team->getWebUser()->getUserId());
                    $userteamitems = FfbUserteamPeer::doSelect($criteria);
                    if($userteamitems) {
                        foreach($userteamitems as $userteamitem) {
                            $score += $userteamitem->getUserteamScore();
                            $wcscore += $userteamitem->getUserteamWcPoints();
                            //echo $userteamitem->getWebUser()->getUserNickname().'/'.$userteamitem->getFfbMatchround()->getMatchroundTitle().'/'.$userteamitem->getUserteamScore().'<br>';
                            //echo $userteamitem->getWebUser()->getUserNickname().':'.count($userteamitems).'<br>';
                        }
                        $userscore[$team->getWebUser()->getUserId()] = $score;
                        $wc_score[$team->getWebUser()->getUserId()] = $wcscore;
                    }
                }
            }

        }

        $answer = '<b>Userteam Scores successfully updated!</b><br>';

        foreach($userscore as $user_id=>$user_score) {
            $criteria = new Criteria();
            $criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $user_id);
            $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
            $criteria->setLimit(1);
            $userscoreitem = FfbUserscorePeer::doSelect($criteria);
            if($userscoreitem) {
                $userscoreitem[0]->setUserscoreTotal($user_score);
                $userscoreitem[0]->save();
                $answer .= 'user_id: '.$user_id;
                $answer .= ' score: '.$user_score.'<br>';
            } else {
                if($game_id>0) {
                    $userscoreitem = new FfbUserscore();
                    $userscoreitem->setUserscoreUserId($user_id);
                    $userscoreitem->setUserscoreTotal($user_score);
                    $userscoreitem->setUserscoreGameId($game_id);
                    $userscoreitem->save();
                    $answer .= 'user_id: '.$user_id;
                    $answer .= ' score: '.$user_score.' (new entry created!)<br>';
                } else {
                    $answer = 'Error: Go to the Startsite and choose a League first!';
                }
            }
        }
        foreach($wc_score as $user_id=>$wcscore) {
            $criteria = new Criteria();
            $criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $user_id);
            $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
            $criteria->setLimit(1);
            $userscoreitem = FfbUserscorePeer::doSelect($criteria);
            if($userscoreitem) {
                $userscoreitem[0]->setUserscoreWcPoints($wcscore);
                $userscoreitem[0]->save();
                $answer .= 'user_id: '.$user_id;
                $answer .= ' wc_score: '.$wcscore.'<br>';
            } else {
                if($game_id>0) {
                    $userscoreitem = new FfbUserscore();
                    $userscoreitem->setUserscoreUserId($user_id);
                    $userscoreitem->setUserscoreWcPoints($wcscore);
                    $userscoreitem->setUserscoreGameId($game_id);
                    $userscoreitem->save();
                    $answer .= 'user_id: '.$user_id;
                    $answer .= ' wc_score: '.$wcscore.' (new entry created!)<br>';
                } else {
                    $answer = 'Error: Go to the Startsite and choose a League first!';
                }
            }
        }
        $this->administration_answer = $answer;
    }

    public function setWCPoints() {
        $game_id = $this->session->game_id_admin;
        $wc_points = explode(',', $this->options->options_game_wcpoints);

        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $matchrounds = FfbMatchroundPeer::doSelect($criteria);

        if($matchrounds) {
            foreach($matchrounds as $matchround) {
                $criteria = new Criteria();
                $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround->getMatchroundId());

                $userteams = FfbUserteamPeer::doSelect($criteria);
                //echo count($userteams).'<br>';

                unset($users);
                $users = array();
                if($userteams) {
                    $i=0;
                    foreach($userteams as $userteam) {
                        $users[$i]['user_id'] = $userteam->getWebUser()->getUserId();
                        $users[$i]['user_nickname'] = $userteam->getWebUser()->getUserNickname();
                        $users[$i]['user_userteam_id'] = $userteam->getUserteamId();
                        $users[$i]['user_score'] = $userteam->getUserteamScore();

                        $i++;
                    }
                }
                unset($values);
                unset($names);
                foreach($users as $item) {
                    $values[] = $item['user_score'];
                    $names[] = strtolower($item['user_nickname']);
                }
                //echo 'users: '.count($users).'<br>';
                //echo 'values: '.count($values).'<br>';
                //echo 'names: '.count($names).'<br>';
                if($userteams && $i) {
                    array_multisort($values, SORT_DESC, $names, SORT_ASC, SORT_STRING, $users);
                }
                $i = 0;
                $k = 1;
                $last_score = 100000;
                for($j=0;$j<count($users);$j++) {
                    $curr_score = $users[$j]['user_score'];
                    if($curr_score < $last_score) {
                        $i = $i + $k;
                        $k = 1;
                    } else {
                        $k++;
                    }
                    /*
                    if($i <= count($this->wc_scores)) {
                        $users[$j]['user_wc_points'] = $this->wc_scores[$i-1];
                    }
                    */
                    if($i < count($wc_points)) {
                        $users[$j]['user_wc_points'] = $wc_points[$i-1];
                    } else {
						$users[$j]['user_wc_points'] = $wc_points[count($wc_points)-1];
					}
                    $users[$j]['user_rank'] = $i;
                    $last_score = $curr_score;
                }

                //echo '---Matchround: '.$matchround->getMatchroundId().'<br>';
                foreach($users as $item) {
                    //echo $item['user_rank'].'. '.$item['user_nickname'].'('.$item['user_userteam_id'].')--'.$item['user_score'].'--'.$item['user_wc_points'].'<br>';
                    $userteamitem = FfbUserteamPeer::retrieveByPK($item['user_userteam_id']);
                    $userteamitem->setUserteamWcPoints($item['user_wc_points']);
                    $userteamitem->save();
                }
                //echo '<br>---<br>';
            }
        }
    }

    public function setGoalData() {
        $playerteam_id = $_POST['playerteam_id'];
        $match_id = $_POST['playerstats_match_id'];
        $pm = $this->options->options_game_pointsmode;

        if(!$playerteam_id || !$match_id) {
            $this->administration_error = 'No player/match ID given!';
            $this->administration_status = $this->options->options_status_error;
            return;
        }

        if($pm == 'new') {
            $answer = '';
            if($_POST['playerstats_goals'] != 0) {
                $num_goals = count(explode(';', trim($_POST['playerstats_goals'])));
                $this->deleteGoals($match_id, $playerteam_id, 0);
                $this->insertGoals($_POST['playerstats_goals'], $match_id, $playerteam_id, 0);
                $answer .= 'Goal inserted for ptID: '.$playerteam_id.'! ';
            } else {
                $this->deleteGoals($match_id, $playerteam_id, 0);
                $answer .= 'No goals to insert for ptID: '.$playerteam_id.'! ';
            }
            if($_POST['playerstats_owngoals'] != 0) {
                $num_owngoals = count(explode(';', trim($_POST['playerstats_owngoals'])));
                $this->deleteGoals($match_id, $playerteam_id, 1);
                $this->insertGoals($_POST['playerstats_owngoals'], $match_id, $playerteam_id, 1);
                $answer .= 'OwnGoal inserted for ptID: '.$playerteam_id.'! ';
            } else {
                $this->deleteGoals($match_id, $playerteam_id, 1);
                $answer .= 'No owngoals to insert for ptID: '.$playerteam_id.'! ';
            }

            if($_POST['playerstats_penaltyshootout_hit'] != 0) {
                $num_penaltyshootout_hit = $_POST['playerstats_penaltyshootout_hit'];
                $this->deletePSgoals($match_id, $playerteam_id, 0);
                $this->insertPSgoals($num_penaltyshootout_hit, $match_id, $playerteam_id, 0);
                $answer .= 'PS hits inserted for ptID: '.$playerteam_id.'! ';
            } else {
                $this->deletePSgoals($match_id, $playerteam_id, 0);
                $answer .= 'No PS hits to insert for ptID: '.$playerteam_id.'! ';
            }
            if($_POST['playerstats_penaltyshootout_lost'] != 0) {
                $num_penaltyshootout_lost = $_POST['playerstats_penaltyshootout_lost'];
                $this->deletePSgoals($match_id, $playerteam_id, 1);
                $this->insertPSgoals($num_penaltyshootout_lost, $match_id, $playerteam_id, 1);
                $answer .= 'PS losts inserted for ptID: '.$playerteam_id.'! ';
            } else {
                $this->deletePSgoals($match_id, $playerteam_id, 1);
                $answer .= 'No PS losts to insert for ptID: '.$playerteam_id.'! ';
            }

            $this->administration_answer = $answer;
            $this->administration_status = $this->options->options_status_success_insert;
        } else {
            $this->administration_answer = 'options_game_pointsmode set to "old" - no action taken!';
            $this->administration_status = $this->options->options_status_success_insert;
        }
    }

    //stores playerstats for given match_id and given playerteam_id to DB
    //used by matchpoints.js
    public function setPlayerStats() {
        $fid_mode = $_REQUEST['playerfid_mode'];
        $fid_name = $_REQUEST['playerfid_name'];
        $playerteam_id = $_POST['playerteam_id'];
        $match_id = $_POST['playerstats_match_id'];
        $pm = $this->options->options_game_pointsmode;
        if(!$playerteam_id || !$match_id) {
            $this->administration_error = 'No player/match ID given!';
            $this->administration_status = $this->options->options_status_error;
            return;
        }

        $playerteamitem = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $matchitem = FfbMatchPeer::retrieveByPK($match_id);
        $team_id = $playerteamitem->getPlayerteamTeamId();

        $this->updatePlayerfid($playerteam_id, $team_id, $fid_mode, $fid_name);

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);

        $exist_item = FfbPlayerstatsPeer::doSelect($criteria);

        if($exist_item) {
            $item = $exist_item[0];
            $answer = 'Existing Playerstats successfully updated!';
            //delete entry when minutes set to zero
            if($_POST['playerstats_minutes'] == 0) {
                FfbPlayerstatsPeer::doDelete($item);
                $this->administration_answer = $answer;
                $this->administration_status = $this->options->options_status_success_insert;
                return;
            }
        } else {
            $item = new FfbPlayerstats();
            $item->setPlayerstatsMatchId($match_id);
            $item->setPlayerstatsPlayerteamId($playerteam_id);
            $matchitem = FfbMatchPeer::retrieveByPK($match_id);
            $item->setPlayerstatsMatchroundId($matchitem->getMatchRound());
            $answer = 'New Playerstats successfully added!';
        }
        if($pm == 'new') {
            if($_POST['playerstats_goals'] != 0) {
                $num_goals = count(explode(';', trim($_POST['playerstats_goals'])));
                //$this->deleteGoals($match_id, $playerteam_id, 0);
                //$this->insertGoals($_POST['playerstats_goals'], $match_id, $playerteam_id, 0);
            } else {
                //$this->deleteGoals($match_id, $playerteam_id, 0);
                $num_goals = 0;
            }
            if($_POST['playerstats_owngoals'] != 0) {
                $num_owngoals = count(explode(';', trim($_POST['playerstats_owngoals'])));
                //$this->deleteGoals($match_id, $playerteam_id, 1);
                //$this->insertGoals($_POST['playerstats_owngoals'], $match_id, $playerteam_id, 1);
            } else {
                //$this->deleteGoals($match_id, $playerteam_id, 1);
                $num_owngoals = 0;
            }
        } else {
            if(is_numeric($_POST['playerstats_goals'])) {
                $num_goals = $_POST['playerstats_goals'];
            } else {
                $num_goals = 0;
            }
            if(is_numeric($_POST['playerstats_owngoals'])) {
                $num_owngoals = $_POST['playerstats_owngoals'];
            } else {
                $num_owngoals = 0;
            }
        }

        //$item->setPlayerstatsGoals($_POST['playerstats_goals']);
        $item->setPlayerstatsGoals($num_goals);
        $item->setPlayerstatsAssists($_POST['playerstats_assists']);
        $item->setPlayerstatsMinutes($_POST['playerstats_minutes']);
        $item->setPlayerstatsMinuteIn($_POST['playerstats_minute_in']);
        $item->setPlayerstatsMinuteOut($_POST['playerstats_minute_out']);
        if($_POST['playerstats_cards'] == 'y' || $_POST['playerstats_cards'] == 'yr' || $_POST['playerstats_cards'] == 'r') {
            $item->setPlayerstatsCards($_POST['playerstats_cards']);
        } else {
            $item->setPlayerstatsCards('n');
        }
        //$item->setPlayerstatsOwngoals($_POST['playerstats_owngoals']);
        $item->setPlayerstatsOwngoals($num_owngoals);
        $item->setPlayerstatsPenaltieslost($_POST['playerstats_penaltieslost']);
        $item->setPlayerstatsPenaltiessaved($_POST['playerstats_penaltiessaved']);

        $item->setPlayerstatsPenaltyshootoutSave($_POST['playerstats_penaltyshootout_save']);
        $item->setPlayerstatsPenaltyshootoutLost($_POST['playerstats_penaltyshootout_lost']);
        $item->setPlayerstatsPenaltyshootoutHit($_POST['playerstats_penaltyshootout_hit']);

        $score_goals = $this->calcScoreGoals($num_goals, $playerteamitem->getPlayerteamPlayerPosition());
        $score_assists = $this->calcScoreAssists($_POST['playerstats_assists']);
        $score_minutes = $this->calcScoreMinutes($_POST['playerstats_minutes']);
        $score_cards = $this->calcScoreCards($_POST['playerstats_cards']);
        //$score_owngoals = $this->calcScoreOwngoals($_POST['playerstats_owngoals']);
        $score_owngoals = $this->calcScoreOwngoals($num_owngoals);
        $score_penaltylost = $this->calcScorePenaltyLost($_POST['playerstats_penaltieslost']);
        $score_penaltysaved = $this->calcScorePenaltySaved($_POST['playerstats_penaltiessaved']);

        $score_penaltyshootout_save = $this->calcScorePenaltyshootoutSave($_POST['playerstats_penaltyshootout_save']);
        $score_penaltyshootout_lost = $this->calcScorePenaltyshootoutLost($_POST['playerstats_penaltyshootout_lost']);
        $score_penaltyshootout_hit = $this->calcScorePenaltyshootoutHit($_POST['playerstats_penaltyshootout_hit']);

        if($matchitem->getMatchHomescore() >= 0 && $matchitem->getMatchGuestscore() >= 0) {
            if($team_id == $matchitem->getMatchHometeamId()) {
                if($pm == 'new') {
                    $score_oppgoals = $this->calcScoreOppGoalsNew($this->getGoalsList($matchitem->getMatchGuestteamId(), $matchitem->getMatchHometeamId(), $match_id), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes'], $_POST['playerstats_minute_in'], $_POST['playerstats_minute_out'], $matchitem->getMatchMinutes());
                } else {
                    $score_oppgoals = $this->calcScoreOppGoals($matchitem->getMatchGuestscore(), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes']);
                }
                $score_nooppgoals = $this->calcScoreOppGoalsNo($matchitem->getMatchGuestscore(), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes']);
                $score_high_win = $this->calcScoreHighWin($matchitem->getMatchHomescore() - $matchitem->getMatchGuestscore(), $_POST['playerstats_minutes']);
                $score_high_loss = $this->calcScoreHighLoss($matchitem->getMatchGuestscore() - $matchitem->getMatchHomescore(), $_POST['playerstats_minutes']);
            } elseif($team_id == $matchitem->getMatchGuestteamId()) {
                if($pm == 'new') {
                    $score_oppgoals = $this->calcScoreOppGoalsNew($this->getGoalsList($matchitem->getMatchHometeamId(), $matchitem->getMatchGuestteamId(), $match_id), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes'], $_POST['playerstats_minute_in'], $_POST['playerstats_minute_out'], $matchitem->getMatchMinutes());
                } else {
                    $score_oppgoals = $this->calcScoreOppGoals($matchitem->getMatchHomescore(), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes']);
                }
                $score_nooppgoals = $this->calcScoreOppGoalsNo($matchitem->getMatchHomescore(), $playerteamitem->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes']);
                $score_high_win = $this->calcScoreHighWin($matchitem->getMatchGuestscore() - $matchitem->getMatchHomescore(), $_POST['playerstats_minutes']);
                $score_high_loss = $this->calcScoreHighLoss($matchitem->getMatchHomescore() - $matchitem->getMatchGuestscore(), $_POST['playerstats_minutes']);
            }
        } else {
            $score_nooppgoals = 0;
            $score_oppgoals = 0;
            $score_high_win = 0;
            $score_high_loss = 0;
        }

        //$score_total = $score_goals + $score_assists + $score_minutes + $score_cards + $score_owngoals + $score_penaltylost +
        //               $score_penaltysaved + $score_oppgoals + $score_nooppgoals;
        /*
        $score_total = $score_goals + $score_assists + $score_minutes + $score_cards + $score_owngoals + $score_penaltylost +
                       $score_penaltysaved + $score_oppgoals + $score_nooppgoals + $score_penaltyshootout_save + $score_penaltyshootout_lost +
                       $score_penaltyshootout_hit + $score_high_win + $score_high_loss;
        */
        $score_total = $score_goals + $score_assists + $score_minutes + $score_cards + $score_owngoals + $score_penaltylost +
                       $score_penaltysaved + $score_oppgoals + $score_nooppgoals + $score_penaltyshootout_lost + $score_penaltyshootout_save + $score_penaltyshootout_hit;

        $item->setPlayerstatsScoreGoals($score_goals);
        $item->setPlayerstatsScoreAssists($score_assists);
        $item->setPlayerstatsScoreMinutes($score_minutes);
        $item->setPlayerstatsScoreCards($score_cards);
        $item->setPlayerstatsScoreOwngoals($score_owngoals);
        $item->setPlayerstatsScorePenaltieslost($score_penaltylost);
        $item->setPlayerstatsScorePenaltiessaved($score_penaltysaved);

        $item->setPlayerstatsScorePenaltyshootoutSave($score_penaltyshootout_save);
        $item->setPlayerstatsScorePenaltyshootoutLost($score_penaltyshootout_lost);
        $item->setPlayerstatsScorePenaltyshootoutHit($score_penaltyshootout_hit);

        $item->setPlayerstatsScoreOppgoals($score_oppgoals);
        $item->setPlayerstatsScoreNooppgoals($score_nooppgoals);

        $item->setPlayerstatsScore($score_total);

        $item->save(); //TODO: uncomment when finished

        $this->administration_answer = $answer;
        $this->administration_status = $this->options->options_status_success_insert;
    }

    //deletes all goals of given match and player
    private function deleteGoals($match_id, $playerteam_id, $own) {
        $criteria = new Criteria();
        $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);
        $criteria->add(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $playerteam_id);
        if($own) {
            $criteria->add(FfbGoalPeer::GOAL_OWNGOAL, 1);
        } else {
            $criteria->add(FfbGoalPeer::GOAL_OWNGOAL, 0);
        }

        FfbGoalPeer::doDelete($criteria);
    }

    //deletes all penaltyshootout goals of given match and player
    private function deletePSGoals($match_id, $playerteam_id, $fail) {
        $criteria = new Criteria();
        $criteria->add(FfbPsgoalPeer::PSGOAL_MATCH_ID, $match_id);
        $criteria->add(FfbPsgoalPeer::PSGOAL_PLAYERTEAM_ID, $playerteam_id);
        if($fail) {
            $criteria->add(FfbPsgoalPeer::PSGOAL_FAIL, 1);
        } else {
            $criteria->add(FfbPsgoalPeer::PSGOAL_HIT, 1);
        }

        FfbPsgoalPeer::doDelete($criteria);
    }

    //inserts goals for given match_id and playerteam_id
    private function insertGoals($goals_list, $match_id, $playerteam_id, $own) {
        $goals = explode(';', trim($goals_list));
        if(count($goals) > 0) {
            foreach($goals as $minute) {
                if(is_numeric($minute)) {
                    $new_goal = new FfbGoal();
                    $new_goal->setGoalMatchId($match_id);
                    $new_goal->setGoalPlayerteamId($playerteam_id);
                    $new_goal->setGoalMinute($minute);
                    if($own) {
                        $new_goal->setGoalOwngoal(1);
                    }
                    $new_goal->save(); //uncomment when ready
                }
            }
        }
    }

    //inserts penaltyshootout goals for given match_id and playerteam_id
    private function insertPSGoals($num_goals, $match_id, $playerteam_id, $fail) {
        if($num_goals > 0) {
            for($i=0; $i<$num_goals; $i++) {
            	$minute = 120;
                $new_psgoal = new FfbPsgoal();
                $new_psgoal->setPsgoalMatchId($match_id);
                $new_psgoal->setPsgoalPlayerteamId($playerteam_id);
                $new_psgoal->setPsgoalMinute($minute);
                if($fail) {
					$new_psgoal->setPsgoalFail(1);
				} else {
					$new_psgoal->setPsgoalHit(1);
				}
                $new_psgoal->save();
            }
        }
    }

    public function testGoalsList() {
        $goal_items = $this->getGoalsList($_REQUEST['opposite_team_id'], $_REQUEST['own_team_id'], $_REQUEST['match_id']);
        //echo $goal_items[0]->getGoalMinute();
        echo count($goal_items);
        exit();
    }

    private function getGoalsList($opposite_team_id, $own_team_id, $match_id) {
        $criteria = new Criteria();
        $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);

        $c1 = $criteria->getNewCriterion(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $opposite_team_id);
        $c1->addAnd($criteria->getNewCriterion(FfbGoalPeer::GOAL_OWNGOAL, 0));
        $c2 = $criteria->getNewCriterion(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $own_team_id);
        $c2->addAnd($criteria->getNewCriterion(FfbGoalPeer::GOAL_OWNGOAL, 1));

        $c1->addOr($c2);
        $criteria->add($c1);

        $goal_items = FfbGoalPeer::doSelectJoinAll($criteria);
        return $goal_items;
    }

    private function updateAllScoresOppgoals($opp_team_id) {
        return;
    }

    //stores the matchresult for the given Match
    //used by matchpoints.js
    //used by fifa_playermanagement.js
    public function setMatchresult() {
        $match_id = $_POST['playerstats_match_id'];
        $match_url = $_POST['match_url'];
        $pm = $this->options->options_game_pointsmode;
        if($match_id) {
            $matchitem = FfbMatchPeer::retrieveByPK($match_id);
            if($matchitem) {
                $matchitem->setMatchHomescore($_POST['match_homescore']);
                $matchitem->setMatchGuestscore($_POST['match_guestscore']);
				if($_POST['match_minutes']) {
                	$matchitem->setMatchMinutes($_POST['match_minutes']);
                }

                $matchitem->setMatchHomescorePenalty($_POST['match_homescore_penalty']);
                $matchitem->setMatchGuestscorePenalty($_POST['match_guestscore_penalty']);

				if($match_url) {
                	$matchitem->setMatchUrl($match_url);
                }

                $criteria = new Criteria();
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $matchitem->getMatchHometeamId());
                $criteria->addor(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $matchitem->getMatchGuestteamId());
                $playerteamitems = FfbPlayerteamPeer::doSelect($criteria);

                foreach($playerteamitems as $item) {
                    $criteria = new Criteria();
                    $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $item->getPlayerteamId());
                    $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
                    $criteria->setLimit(1);
                    $statsitem = FfbPlayerstatsPeer::doSelect($criteria);
                    if($statsitem) {
                        if($item->getPlayerteamTeamId() == $matchitem->getMatchHometeamId()) {
                            if($pm == 'new') {
                                //$score_oppgoals = $this->calcScoreOppGoalsNew($this->getGoalsList($matchitem->getMatchGuestteamId(), $matchitem->getMatchHometeamId(), $match_id), $item->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes'], $_POST['playerstats_minute_in'], $_POST['playerstats_minute_out']);
                            } else {
                                $score_oppgoals = $this->calcScoreOppGoals($matchitem->getMatchGuestscore(), $item->getPlayerteamPlayerPosition(), $statsitem[0]->getPlayerstatsMinutes());
                                //$this->updateAllScoresOppgoals($matchitem->getMatchGuestteamId());
                            }
                            $score_nooppgoals = $this->calcScoreOppGoalsNo($matchitem->getMatchGuestscore(), $item->getPlayerteamPlayerPosition(), $statsitem[0]->getPlayerstatsMinutes());
                            $score_high_win = $this->calcScoreHighWin($matchitem->getMatchHomescore() - $matchitem->getMatchGuestscore(), $statsitem[0]->getPlayerstatsMinutes());
                            $score_high_loss = $this->calcScoreHighLoss($matchitem->getMatchGuestscore() - $matchitem->getMatchHomescore(), $statsitem[0]->getPlayerstatsMinutes());
                        } elseif($item->getPlayerteamTeamId() == $matchitem->getMatchGuestteamId()) {
                            if($pm == 'new') {
                                //$score_oppgoals = $this->calcScoreOppGoalsNew($this->getGoalsList($matchitem->getMatchHometeamId(), $matchitem->getMatchGuestteamId(), $match_id), $item->getPlayerteamPlayerPosition(), $_POST['playerstats_minutes'], $_POST['playerstats_minute_in'], $_POST['playerstats_minute_out']);
                            } else {
                                $score_oppgoals = $this->calcScoreOppGoals($matchitem->getMatchHomescore(), $item->getPlayerteamPlayerPosition(), $statsitem[0]->getPlayerstatsMinutes());
                                //$this->updateAllScoresOppgoals($matchitem->getMatchHometeamId());
                            }

                            $score_nooppgoals = $this->calcScoreOppGoalsNo($matchitem->getMatchHomescore(), $item->getPlayerteamPlayerPosition(), $statsitem[0]->getPlayerstatsMinutes());
                            $score_high_win = $this->calcScoreHighWin($matchitem->getMatchGuestscore() - $matchitem->getMatchHomescore(), $statsitem[0]->getPlayerstatsMinutes());
                            $score_high_loss = $this->calcScoreHighLoss($matchitem->getMatchHomescore() - $matchitem->getMatchGuestscore(), $statsitem[0]->getPlayerstatsMinutes());
                        }

                        //$score = $statsitem[0]->getPlayerstatsScore()-$statsitem[0]->getPlayerstatsScoreOppgoals()-$statsitem[0]->getPlayerstatsScoreNooppgoals()+$score_oppgoals+$score_nooppgoals;
                        if($pm == 'new') {
                            $score = $statsitem[0]->getPlayerstatsScore() - $statsitem[0]->getPlayerstatsScoreNooppgoals() -
                                     $statsitem[0]->getPlayerstatsScoreHighLoss() - $statsitem[0]->getPlayerstatsScoreHighWin() +
                                     $score_nooppgoals;
                        } else {
                            $score = $statsitem[0]->getPlayerstatsScore() - $statsitem[0]->getPlayerstatsScoreOppgoals() - $statsitem[0]->getPlayerstatsScoreNooppgoals() -
                                     $statsitem[0]->getPlayerstatsScoreHighLoss() - $statsitem[0]->getPlayerstatsScoreHighWin() +
                                     $score_oppgoals + $score_nooppgoals;
                            $statsitem[0]->setPlayerstatsScoreOppgoals($score_oppgoals);
                        }
                        $statsitem[0]->setPlayerstatsScoreNooppgoals($score_nooppgoals);
                        $statsitem[0]->setPlayerstatsScoreHighWin($score_high_win);
                        $statsitem[0]->setPlayerstatsScoreHighLoss($score_high_loss);
                        $statsitem[0]->setPlayerstatsScore($score);

                        $statsitem[0]->save();
                    }
                }

                $matchitem->save();
                $this->administration_answer = 'Match: Result successfully updated!';
                $this->administration_status = $this->options->options_status_success_insert;
            } else {
                $this->administration_error = 'No Match for this ID was found!';
                $this->administration_status = $this->options->options_status_error;
            }
        } else {
            $this->administration_error = 'No matchID given!';
            $this->administration_status = $this->options->options_status_error;
        }
    }

    //returns the PlayerStats for the given Player, Match and Matchround
    public function getPlayerStats() {
        $criteria = new Criteria();

        if(isset($_POST['player_id'])) {
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $_POST['player_id']);
        }
        if(isset($_POST['match_id'])) {
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $_POST['match_id']);
        }
        if(isset($_POST['matchround_id'])) {
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $_POST['matchround_id']);
        }
        $this->getPlayerstatsByCriteria($criteria);
    }

    //returns list of players with playerstats for the given team and the given match
    public function getPlayerStatsForTeam() {

        //$team = FfbTeamPeer::retrieveByPK($_POST['id']);
        $team = $_POST['id'];
        $match_id = $_POST['match_id'];
        $pm = $this->options->options_game_pointsmode;
        $all_players = $_REQUEST['all_players'];
        //echo 'tid: '.$team.' mid: '.$match_id;

        if($team) {
            $criteria = new Criteria();
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team);
            //nur aktive Spieler anzeigen
            if(!$all_players) {
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
            }
            //$criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
            //$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);

            //$playeritems = $team->getFfbPlayerteams($criteria);
            $playeritems = FfbPlayerteamPeer::doSelectJoinAll($criteria);

            $players = array();
            $i=0;
            if($playeritems) {
                foreach($playeritems as $playeritem) {
                    $playerfid = $playeritem->getFfbPlayerfids();
                    $pfid_name_fifa = '';
                    $pfid_name_foe = '';
                    $pfid_name_tm = '';
                    $pfid_name_uefa = '';
                    $pfid_name_wf = '';
                    if(count($playerfid)) {
                        if(!$playerfid[0]) {
                          echo 'ha: '.count($playerfid);
                        }
                        $pfid_name_fifa = $playerfid[0]->getPlayerfidNameFifa();
                        $pfid_name_foe = $playerfid[0]->getPlayerfidNameFoe();
                        $pfid_name_tm = $playerfid[0]->getPlayerfidNameTm();
                        $pfid_name_uefa = $playerfid[0]->getPlayerfidNameUefa();
                        $pfid_name_wf = $playerfid[0]->getPlayerfidNameWf();
                        //$pfid_fid_fifa = $playerfid[0]->getPlayerfidFidFifa();
                        //$pfid_fid_foe = $playerfid[0]->getPlayerfidFidFoe();
                        //$pfid_fid_tm = $playerfid[0]->getPlayerfidFidTm();
                        //$pfid_fid_uefa = $playerfid[0]->getPlayerfidFidUefa();
                        //$pfid_fid_wf = $playerfid[0]->getPlayerfidFidWf();
                    }
                    if($pfid_name_fifa) {
                        $players[$i]['player_name_fid_fifa'] = $pfid_name_fifa;
                    } else {
                        $players[$i]['player_name_fid_fifa'] = 0;
                    }
                    if($pfid_name_foe) {
                        $players[$i]['player_name_fid_foe'] = $pfid_name_foe;
                    } else {
                        $players[$i]['player_name_fid_foe'] = 0;
                    }
                    if($pfid_name_uefa) {
                        $players[$i]['player_name_fid_uefa'] = $pfid_name_uefa;
                    } else {
                        $players[$i]['player_name_fid_uefa'] = 0;
                    }
                    if($pfid_name_tm) {
                        $players[$i]['player_name_fid_tm'] = $pfid_name_tm;
                    } else {
                        $players[$i]['player_name_fid_tm'] = 0;
                    }
                    if($pfid_name_wf) {
                        $players[$i]['player_name_fid_wf'] = $pfid_name_wf;
                    } else {
                        $players[$i]['player_name_fid_wf'] = 0;
                    }

                    $players[$i]['player_id'] = $playeritem->getFfbPlayer()->getPlayerId();
                    $players[$i]['player_fname'] = $playeritem->getFfbPlayer()->getPlayerFname();
                    $players[$i]['player_lname'] = $playeritem->getFfbPlayer()->getPlayerLname();
                    $players[$i]['player_nationality'] = $playeritem->getFfbPlayer()->getPlayerNationality();
                    $players[$i]['player_status'] = $playeritem->getFfbPlayer()->getPlayerStatus();
                    $players[$i]['player_status_description'] = $playeritem->getFfbPlayer()->getPlayerStatusDescription();
                    $players[$i]['playerteam_id'] = $playeritem->getPlayerteamId();
                    $players[$i]['playerteam_player_price'] = $playeritem->getPlayerteamPlayerPrice();
                    $players[$i]['playerteam_player_position'] = $playeritem->getPlayerteamPlayerPosition();
                    $players[$i]['playerteam_player_picture'] = $playeritem->getPlayerteamPlayerPicture();
                    if($playeritem->getPlayerteamStatus()) {
                        $players[$i]['playerteam_status'] = $playeritem->getPlayerteamStatus();
                    } else {
                        $players[$i]['playerteam_status'] = 0;
                    }

                    $criteria = new Criteria();
                    $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match_id);
                    $criteria->setLimit(1);

                    $playerstats = $playeritem->getFfbPlayerstatss($criteria);

                    if($playerstats[0]) {
                        // get the string for the goals
                        if($pm == 'new') {
                            $num_goals = $playerstats[0]->getPlayerstatsGoals();
                            if($num_goals > 0) {
                                $criteria = new Criteria();
                                $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);
                                $criteria->add(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $players[$i]['playerteam_id']);
                                $criteria->add(FfbGoalPeer::GOAL_OWNGOAL, 0);
                                $goal_items = FfbGoalPeer::doSelect($criteria);
                                $goal_string = '';
                                if($goal_items) {
                                    foreach($goal_items as $goal_item) {
                                        $goal_string .= $goal_item->getGoalMinute();
                                        $goal_string .= ';';
                                    }
                                    $goal_string = substr($goal_string, 0, strlen($goal_string)-1);
                                } else {
                                    $goal_string = '0';
                                }
                                $players[$i]['playerstats_goals'] = $goal_string;
                            } else {
                                $players[$i]['playerstats_goals'] = 0;
                            }
                        } else {
                            $players[$i]['playerstats_goals'] = $playerstats[0]->getPlayerstatsGoals();
                        }
                        // get the string for the owngoals
                        if($pm == 'new') {
                            $num_owngoals = $playerstats[0]->getPlayerstatsOwngoals();
                            if($num_owngoals > 0) {
                                $criteria = new Criteria();
                                $criteria->add(FfbGoalPeer::GOAL_MATCH_ID, $match_id);
                                $criteria->add(FfbGoalPeer::GOAL_PLAYERTEAM_ID, $players[$i]['playerteam_id']);
                                $criteria->add(FfbGoalPeer::GOAL_OWNGOAL, 1);
                                $goal_items = FfbGoalPeer::doSelect($criteria);
                                $goal_string = '';
                                if($goal_items) {
                                    foreach($goal_items as $goal_item) {
                                        $goal_string .= $goal_item->getGoalMinute();
                                        $goal_string .= ';';
                                    }
                                    $goal_string = substr($goal_string, 0, strlen($goal_string)-1);
                                } else {
                                    $goal_string = '0';
                                }
                                $players[$i]['playerstats_owngoals'] = $goal_string;
                            } else {
                                $players[$i]['playerstats_owngoals'] = 0;
                            }
                        } else {
                            $players[$i]['playerstats_owngoals'] = $playerstats[0]->getPlayerstatsOwngoals();
                        }

                        $players[$i]['playerstats_assists'] = $playerstats[0]->getPlayerstatsAssists();
                        $players[$i]['playerstats_minutes'] = $playerstats[0]->getPlayerstatsMinutes();
                        $players[$i]['playerstats_minute_in'] = $playerstats[0]->getPlayerstatsMinuteIn();
                        $players[$i]['playerstats_minute_out'] = $playerstats[0]->getPlayerstatsMinuteOut();
                        $players[$i]['playerstats_cards'] = $playerstats[0]->getPlayerstatsCards();
                        //$players[$i]['playerstats_owngoals'] = $playerstats[0]->getPlayerstatsOwngoals();
                        $players[$i]['playerstats_penaltieslost'] = $playerstats[0]->getPlayerstatsPenaltieslost();
                        $players[$i]['playerstats_penaltyshootout_save'] = $playerstats[0]->getPlayerstatsPenaltyshootoutSave();
                        $players[$i]['playerstats_penaltyshootout_lost'] = $playerstats[0]->getPlayerstatsPenaltyshootoutLost();
                        $players[$i]['playerstats_penaltyshootout_hit'] = $playerstats[0]->getPlayerstatsPenaltyshootoutHit();
                        $players[$i]['playerstats_penaltiessaved'] = $playerstats[0]->getPlayerstatsPenaltiessaved();
                        $players[$i]['playerstats_score_goals'] = $playerstats[0]->getPlayerstatsScoreGoals();
                        $players[$i]['playerstats_score_assists'] = $playerstats[0]->getPlayerstatsScoreAssists();
                        $players[$i]['playerstats_score_minutes'] = $playerstats[0]->getPlayerstatsScoreMinutes();
                        $players[$i]['playerstats_score_cards'] = $playerstats[0]->getPlayerstatsScoreCards();
                        $players[$i]['playerstats_score_owngoals'] = $playerstats[0]->getPlayerstatsScoreOwngoals();
                        $players[$i]['playerstats_score_penaltieslost'] = $playerstats[0]->getPlayerstatsScorePenaltieslost();
                        $players[$i]['playerstats_score_penaltiessaved'] = $playerstats[0]->getPlayerstatsPenaltiessaved();
                        $players[$i]['playerstats_score_oppgoals'] = $playerstats[0]->getPlayerstatsScoreOppgoals();
                        $players[$i]['playerstats_score_nooppgoals'] = $playerstats[0]->getPlayerstatsScoreNooppgoals();
                        $players[$i]['playerstats_score'] = $playerstats[0]->getPlayerstatsScore();
                    } else {
                        $players[$i]['playerstats_goals'] = 0;
                        $players[$i]['playerstats_assists'] = 0;
                        $players[$i]['playerstats_minutes'] = 0;
                        $players[$i]['playerstats_minute_in'] = 0;
                        $players[$i]['playerstats_minute_out'] = 0;
                        $players[$i]['playerstats_cards'] = 'n';
                        $players[$i]['playerstats_owngoals'] = 0;
                        $players[$i]['playerstats_penaltieslost'] = 0;
                        $players[$i]['playerstats_penaltiessaved'] = 0;
                        $players[$i]['playerstats_penaltyshootout_save'] = 0;
                        $players[$i]['playerstats_penaltyshootout_lost'] = 0;
                        $players[$i]['playerstats_penaltyshootout_hit'] = 0;
                        $players[$i]['playerstats_score_goals'] = 0;
                        $players[$i]['playerstats_score_assists'] = 0;
                        $players[$i]['playerstats_score_minutes'] = 0;
                        $players[$i]['playerstats_score_cards'] = 0;
                        $players[$i]['playerstats_score_owngoals'] = 0;
                        $players[$i]['playerstats_score_penaltieslost'] = 0;
                        $players[$i]['playerstats_score_penaltiessaved'] = 0;
                        $players[$i]['playerstats_score_oppgoals'] = 0;
                        $players[$i]['playerstats_score_nooppgoals'] = 0;
                        $players[$i]['playerstats_score'] = 0;
                    }
                    $i++;
                }
            }
            $this->numResults = $i;
            $this->players = $players;
        }
    }

    //returns playerstats by given criteria
    private function getPlayerstatsByCriteria($criteria) {
        $pm = $this->options->options_game_pointsmode;
        $items = FfbPlayerstatsPeer::doSelect($criteria);
        $playerstats = array();
        $i=0;
        if($items) {
            foreach($items as $item) {
                $playerstats[$i]['playerstats_playerteam_id'] = $item->getPlayerstatsPlayerteamId();
                $playerstats[$i]['playerstats_match_id'] = $item->getPlayerstatsMatchId();
                $playerstats[$i]['playerstats_matchround_id'] = $item->getPlayerstatsMatchroundId();
                if($pm == 'new') {
                    $playerstats[$i]['playerstats_goals'] = $item->getPlayerstatsGoals();
                } else {
                    $playerstats[$i]['playerstats_goals'] = $item->getPlayerstatsGoals();
                }
                //$playerstats[$i]['playerstats_goals'] = $item->getPlayerstatsGoals();
                $playerstats[$i]['playerstats_assists'] = $item->getPlayerstatsAssists();
                $playerstats[$i]['playerstats_minutes'] = $item->getPlayerstatsMinutes();
                $playerstats[$i]['playerstats_minute_in'] = $item->getPlayerstatsMinuteIn();
                $playerstats[$i]['playerstats_minute_out'] = $item->getPlayerstatsMinuteOut();
                $playerstats[$i]['playerstats_cards'] = $item->getPlayerstatsCards();
                $playerstats[$i]['playerstats_owngoals'] = $item->getPlayerstatsOwngoals();
                $playerstats[$i]['playerstats_penaltieslost'] = $item->getPlayerstatsPenaltieslost();
                $playerstats[$i]['playerstats_score'] = $item->getPlayerstatsScore();
                $i++;
            }
        } else {
            $this->administration_error = 'No entry for was found!';
            $this->administration_status = $this->options->options_status_error;
        }
        $this->numResults = $i;
        $this->playerstats = $playerstats;
    }

    //***** Methods for calculating Playerscores *****
    //calc score for goals
    private function calcScoreGoals($num, $pos) {
        if($pos == 'g') {
            return $this->options->options_score_goals_g * $num;
        } elseif($pos == 'd') {
            return $this->options->options_score_goals_d * $num;
        } elseif($pos == 'm') {
            return $this->options->options_score_goals_m * $num;
        } elseif($pos == 's') {
            return $this->options->options_score_goals_s * $num;
        }
        return 0;
    }

    //calc score for owngoals
    private function calcScoreOwngoals($num) {
        return $this->options->options_score_owngoals * $num;
    }

    //calc score for assists
    private function calcScoreAssists($num) {
        return $this->options->options_score_assists * $num;
    }

    //calc score for played minutes
    private function calcScoreMinutes($num) {
        $pm = $this->options->options_game_pointsmode;
        if($pm == 'new') {
            if($num == 0)
                { return 0; }
            if($num < $this->options->options_score_minutes_treshold) {
                return $this->options->options_score_minutes_lt30;
            } elseif ($num >= $this->options->options_score_minutes_treshold && $num < $this->options->options_score_minutes) {
                return $this->options->options_score_minutes_lt;
            } elseif ($num >= $this->options->options_score_minutes) {
                return $this->options->options_score_minutes_gt;
            }
            return 0;
        } else {
            if($num == 0)
                { return 0; }
            if($num < $this->options->options_score_minutes) {
                return $this->options->options_score_minutes_lt;
            } elseif ($num >= $this->options->options_score_minutes) {
                return $this->options->options_score_minutes_gt;
            }
            return 0;
        }
    }

    //calc score for cards
    private function calcScoreCards($type) {
        if($type == 'y') {
            return $this->options->options_score_card_y;
        } elseif($type == 'r') {
            return $this->options->options_score_card_r;
        } elseif($type == 'yr') {
            return $this->options->options_score_card_yr;
        }
        return 0;
    }

    //for options_game_pointsmode=="old"
    //calc score for opposite goals
    //result dependent
    private function calcScoreOppGoals($num, $pos, $minutes) {
        if($pos == 'g') {
            return $this->options->options_score_oppgoals_g * floor($num / 2);
        } elseif($pos == 'd') {
            return $this->options->options_score_oppgoals_d * floor($num / 2);
        }
        return 0;
    }

    //for options_game_pointsmode=="new"
    //goal minute dependent
    private function calcScoreOppGoalsNew($goal_items, $pos, $minutes, $minute_in, $minute_out, $match_minutes) {
        $num_goals = 0;
        if(count($goal_items) > 0 && ($pos == 'g' || $pos == 'd')) {
            foreach($goal_items as $item) {
                if(($item->getGoalMinute() >= $minute_in && $item->getGoalMinute() <= $minute_out) || ($item->getGoalMinute() >= $match_minutes && $minute_out >= $match_minutes)) {
                    $num_goals++;
                }
            }
            if($pos == 'g') {
                return $this->options->options_score_oppgoals_g * floor($num_goals / 2);
            } elseif($pos == 'd') {
                return $this->options->options_score_oppgoals_d * floor($num_goals / 2);
            }
        }
        return 0;
    }


    /*
    //calc score for no opposite goals
    //result dependent
    private function calcScoreOppGoalsNo($num, $pos) {
        if($num == 0) {
            if($pos == 'g') {
                return $this->options->options_score_no_oppgoals_g;
            } elseif($pos == 'd') {
                return $this->options->options_score_no_oppgoals_d;
            } elseif($pos == 'm') {
                return $this->options->options_score_no_oppgoals_m;
            }
        }
        return 0;
    }
    */

    //NEW: with treshold - uncomment when DB is ready
    private function calcScoreOppGoalsNo($num, $pos, $minutes) {
        $pm = $this->options->options_game_pointsmode;
        if(($pm == 'new' && $num == 0 && $minutes >= $this->options->options_score_minutes_treshold) ||
           ($pm == 'old' && $num == 0)) {
            if($pos == 'g') {
                return $this->options->options_score_no_oppgoals_g;
            } elseif($pos == 'd') {
                return $this->options->options_score_no_oppgoals_d;
            } elseif($pos == 'm') {
                return $this->options->options_score_no_oppgoals_m;
            }
        }
        return 0;
    }

    //calc score for misplayed penalties
    private function calcScorePenaltyLost($num) {
        return $this->options->options_score_penalty_lost * $num;
    }

    //calc score for saved penalties
    private function calcScorePenaltySaved($num) {
        return $this->options->options_score_penalty_saved * $num;
    }

    //calc score for high loss
    private function calcScoreHighLoss($num, $minutes) {
        if($minutes >= $this->options->options_score_minutes_treshold) {
            if($num >= $this->options->options_score_high_win_loss_treshold) {
                return $this->options->options_score_high_loss;
            }
        }
        return 0;
    }

    //calc score for high win
    private function calcScoreHighWin($num, $minutes) {
        if($minutes >= $this->options->options_score_minutes_treshold) {
            if($num >= $this->options->options_score_high_win_loss_treshold) {
                return $this->options->options_score_high_win;
            }
        }
        return 0;
    }

    //calc score for penaltyshootout: saved penalties
    private function calcScorePenaltyshootoutSave($num) {
        return $this->options->options_score_penaltyshootout_save * $num;
    }

    //calc score for penaltyshootout: lost penalties
    private function calcScorePenaltyshootoutLost($num) {
        return $this->options->options_score_penaltyshootout_lost * $num;
    }

    //calc score for penaltyshootout: hit penalties
    private function calcScorePenaltyshootoutHit($num) {
        return $this->options->options_score_penaltyshootout_hit * $num;
    }

    private function updatePlayerfid($playerteam_id, $team_id, $fid_mode, $fid_name) {
        $criteria = new Criteria();
        $criteria->add(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerteam_id);
        $criteria->setLimit(1);
        $exist_item = FfbPlayerfidPeer::doSelect($criteria);

        if($exist_item) {
            $update_item = $exist_item[0];
        } else {
            $update_item = new FfbPlayerfid();
            $update_item->setPlayerfidPlayerteamId($playerteam_id);
            $update_item->setPlayerfidTeamId($team_id);
        }
        if($fid_mode == "fifa") {
            $update_item->setPlayerfidNameFifa($fid_name);
        } elseif($fid_mode == "foe") {
            $update_item->setPlayerfidNameFoe($fid_name);
        } elseif($fid_mode == "tm") {
            $update_item->setPlayerfidNameTm($fid_name);
        } elseif($fid_mode == "uefa") {
            $update_item->setPlayerfidNameUefa($fid_name);
        } elseif($fid_mode == "wf") {
            $update_item->setPlayerfidNameWf($fid_name);
        }
        $update_item->save();
    }
}
?>