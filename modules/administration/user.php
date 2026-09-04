<?php

/**
 * FFB - USER-Klasse;
 *
 * @author Gritschacher
 * @copyright 07/2008
 * @version 0.1
 *
 * liefert Liste der user zur Abfrage der Userteams
 *
 */

class user extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'userscore.php';
    }

    public function __default() {

    }

    public function score()
    {
        $this->getUserscore();
    }

    //gesamte User-Liste holen
    public function getList() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        $this->getUsersByCriteria($criteria);
    }

    public function getCurrentUsers() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $criteria->setLimit(1);
        $items = FfbMatchroundPeer::doSelect($criteria);
        if($items) {
            $current_matchround = $items[0]->getMatchroundId();
        }
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        $criteria->add(WebUserPeer::USER_STATUS, 'active');
        $criteria->add(WebUserPeer::USER_MAILSERVICE, '0', Criteria::NOT_EQUAL);
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $current_matchround);
        $userteams = FfbUserteamPeer::doSelectJoinWebUser($criteria);
        $users = array();
        $i=0;
        if($userteams) {
            foreach($userteams as $item) {
                $users[$i]['user_id'] = $item->getWebUser()->getUserId();
                $users[$i]['user_nickname'] = $item->getWebUser()->getUserNickname();
                $users[$i]['user_date_register'] = $item->getWebUser()->getUserDateRegister();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->users = $users;
    }

    public function getGameUsers() {
        $game_id = $this->session->game_id_admin;
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $criteria->setLimit(1);
        $items = FfbMatchroundPeer::doSelect($criteria);
        if($items) {
            $next_matchround = $items[0]->getMatchroundId();
        }
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->addGroupByColumn(FfbUserteamPeer::USERTEAM_USER_ID);
        //$criteria->add(WebUserPeer::USER_MAILSERVICE, 0, Criteria::NOT_EQUAL);
        //$criteria->add(WebUserPeer::USER_STATUS, 'active');

        $userteam_items = FfbUserteamPeer::doSelectJoinFfbMatchround($criteria);
        $i = 0;
        //echo 'next mr: '.$next_matchround.'<br>';
        foreach($userteam_items as $item) {
            $user_item = $item->getWebUser();
            $criteria = new Criteria();
            $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $next_matchround);
            $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_item->getUserId());
            $nm = FfbUserteamPeer::doSelect($criteria);
            //echo 'user: '.$user_item->getUserNickname().'<br>';
            if($user_item->getUserMailservice() && $user_item->getUserStatus() == 'active' && !$nm) {
            //if($user_item->getUserStatus() == 'active' && !$nm) {
                $users[$i]['user_id'] = $user_item->getUserId();
                $users[$i]['user_nickname'] = $user_item->getUserNickname();
                $users[$i]['user_date_register'] = $user_item->getUserDateRegister();
                $i++;

                //echo 'user mail: '.$user_item->getUserNickname().'<br>';
            }
        }
        //echo 'num users: '.count($users).'<br>';
        //exit();
        $this->num_results = $i;
        $this->users = $users;
    }

    public function getNextRoundReminder() {
        $game_id = $this->session->game_id_admin;
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $criteria->setLimit(1);
        $items = FfbMatchroundPeer::doSelect($criteria);
        if($items) {
            $next_matchround = $items[0]->getMatchroundId();
        }
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $items = FfbMatchroundPeer::doSelect($criteria);
        $i=0;
        if($items) {
            foreach($items as $item) {
                $past_matchrounds[$i] = $item->getMatchroundId();
            }
        }

        $criteria = new Criteria();
        $criteria->add(WebUserPeer::USER_STATUS, 'active');
        $criteria->add(WebUserPeer::USER_MAILSERVICE, '0', Criteria::NOT_EQUAL);
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $next_matchround, Criteria::EQUAL);
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        $userteams = FfbUserteamPeer::doSelectJoinWebUser($criteria);
        if($userteams) {
            foreach($userteams as $team) {
                $users_new[$team->getWebUser()->getUserId()]['id'] = $team->getWebUser()->getUserId();
                $users_new[$team->getWebUser()->getUserId()]['user'] = $team->getWebUser();
            }
        }
        $criteria = new Criteria();
        $criteria->add(WebUserPeer::USER_STATUS, 'active');
        $criteria->add(WebUserPeer::USER_MAILSERVICE, '0', Criteria::NOT_EQUAL);
        if($past_matchrounds) {
            foreach($past_matchrounds as $past_matchround) {
                $criteria->addOr(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $past_matchround, Criteria::EQUAL);
            }
        }
        //$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, 18, Criteria::EQUAL);
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        $criteria->addGroupByColumn(WebUserPeer::USER_ID);
        $userteams = FfbUserteamPeer::doSelectJoinWebUser($criteria);
        if($userteams) {
            foreach($userteams as $team) {
                $users_old[$team->getWebUser()->getUserId()]['id'] = $team->getWebUser()->getUserId();
                $users_old[$team->getWebUser()->getUserId()]['user'] = $team->getWebUser();
            }
        }
        $i=0;
        foreach($users_old as $item) {
            if(!$users_new[$item['id']]) {
                $users[$i]['user_id'] = $item['user']->getUserId();
                $users[$i]['user_nickname'] = $item['user']->getUserNickname();
                $users[$i]['user_date_register'] = $item['user']->getUserDateRegister();
                $i++;
            }
        }

        $this->num_results = $i;
        $this->users = $users;
    }

    public function getUsersWithoutUserteams() {
        $criteria = new Criteria();
        $criteria->add(WebUserPeer::USER_STATUS, 'active');
        $criteria->add(WebUserPeer::USER_MAILSERVICE, '0', Criteria::NOT_EQUAL);
        $users = WebUserPeer::doSelect($criteria);

        $game_id = $this->session->game_id_admin;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $rounds = FfbMatchroundPeer::doSelect($criteria);
        $userlist = array();

        if($users && $rounds) {
            $criteria = new Criteria();
            foreach($rounds as $round) {
                $c1 = $criteria->getNewCriterion(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $round->getMatchroundId());
                $criteria->addOr($c1);
                //echo 'adding or<br>';
            }
            $usercriteria = new Criteria();
            //get next round
            $now = time();
            $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
            $criteria->setLimit(1);
            $rounditems = FfbMatchroundPeer::doSelect($criteria);
            //***

            if($rounditems) {
                $nextround_id = $rounditems[0]->getMatchroundId();
                $i=0;
                foreach($users as $user) {
                    $userteams = $user->getFfbUserteams($criteria);
                    if(count($userteams)) {
                        $round_criteria = new Criteria();
                        $round_criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user->getUserId());
                        $round_criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $nextround_id);
                        $round_criteria->setLimit(1);
                        $next = FfbUserteamPeer::doSelect($round_criteria);
                        if(!$next) {
                            //echo $user->getUserNickname().': has no next team<br>';
                            $userlist[$i]['user_id'] = $user->getUserId();
                            $userlist[$i]['user_nickname'] = $user->getUserNickname();
                            $userlist[$i]['user_date_register'] = $user->getUserDateRegister();
                            $i++;
                        } else {
                            //echo $user->getUserNickname().': has a next team<br>';
                        }
                    }
                    //echo $user->getUserNickname().': '.count($userteams).'<br>';
                }
            } else {
                //echo 'no next round';
            }
        }
        $this->numResults = count($userlist);
        $this->users = $userlist;
    }

    public function getUsers() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        if($_POST['active'])
        {
            $this->answer .= 'active';
            $criteria->add(WebUserPeer::USER_STATUS, 'active');
        } elseif($_POST['inactive'])
        {
            $this->answer .= 'inactive';
            $criteria->add(WebUserPeer::USER_STATUS, 'active', Criteria::NOT_EQUAL);
        }
        if($_POST['mailservice'])
        {
            $this->answer .= 'mailservice';
            $criteria->add(WebUserPeer::USER_MAILSERVICE, '0', Criteria::NOT_EQUAL);
        }
        $this->getUsersByCriteria($criteria);
    }



    //returns list of users which have at least one userteam
    //used by myteam.js
    public function getUsersWithTeams() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
        $list = WebUserPeer::doSelect($criteria);
        $users = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $criteria_count = new Criteria();
                $criteria_count->add(FfbUserteamPeer::USERTEAM_USER_ID, $item->getUserId());

                $criteria_count->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $_POST['matchround_id']);

                $num_userteams = FfbUserteamPeer::doCount($criteria_count);
                if($num_userteams > 0) {
                    $users[$i]['user_id'] = $item->getUserId();
                    $users[$i]['user_nickname'] = $item->getUserNickname();
                    $i++;
                }
            }
        }

        $this->numResults = $i;
        $this->users = $users;
    }

    public function getUserscoresForRound() {
        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $_POST['matchround_id']);

        $userteams = FfbUserteamPeer::doSelect($criteria);

        $users = array();
        if($userteams) {
            $i=0;
            foreach($userteams as $userteam) {
                $users[$i]['user_id'] = $userteam->getWebUser()->getUserId();
                $users[$i]['user_nickname'] = $userteam->getWebUser()->getUserNickname();
                $users[$i]['user_score'] = $userteam->getUserteamScore();
                $i++;
            }
        }
        foreach($users as $item) {
            $values[] = $item['user_score'];
        }
        array_multisort($values, SORT_DESC, $users);

        $this->numResults = $i;
        $this->users = $users;
    }

    //Userranking
    public function getUserscore() {
        $game_id = $this->session->game_id_player;

        $criteria = new Criteria();
        $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
        $scores = FfbUserscorePeer::doSelect($criteria);

        $users = array();
        if($scores) {
            $i=0;
            foreach($scores as $scoreitem) {
                $users[$i]['user_id'] = $scoreitem->getWebUser()->getUserId();
                $users[$i]['user_nickname'] = $scoreitem->getWebUser()->getUserNickname();
                $users[$i]['user_score'] = $scoreitem->getUserscoreTotal();

                $userteams = $scoreitem->getWebUser()->getFfbUserteams();
                $userteamlist = array();
                $j=0;
                if($userteams) {
                    foreach($userteams as $userteam) {
                        $userteamlist[$j]['userteam_id'] = $userteam->getUserteamId();
                        $userteamlist[$j]['userteam_matchround_id'] = $userteam->getUserteamMatchroundId();
                        $userteamlist[$j]['userteam_matchround_title'] = $userteam->getFfbMatchround()->getMatchroundTitle();
                        $userteamlist[$j]['userteam_score'] = $userteam->getUserteamScore();
                    }
                }
                $i++;
            }
        }

        foreach($users as $item) {
            $values[] = $item['user_score'];
        }
        if($users) {
            array_multisort($values, SORT_DESC, $users);
        }

        $this->numResults = $i;
        $this->users = $users;
    }

    private function getUsersByCriteria($criteria) {
        $list = WebUserPeer::doSelect($criteria);
        $users = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $users[$i]['user_id'] = $item->getUserId();
                $users[$i]['user_nickname'] = $item->getUserNickname();
                $users[$i]['user_date_register'] = $item->getUserDateRegister();
                $i++;
            }
        }

        $this->numResults = $i;
        $this->users = $users;
    }
}
?>