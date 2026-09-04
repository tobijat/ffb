<?php

/**
 * FFB - MATCHROUND-Klasse;
 * Matchrounds abfragen
 *
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 10/2009
 * @version 0.3
 *
 */

class matchround extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'matchround.php';
    }

    public function __default() {
    }

    //returns List of all available Matchrounds
    public function getList() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns details of given Matchround
    public function getMatchround() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $_POST['matchround_id']);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns List of all Matchrounds for the given game
    public function getMatchroundsForGame() {
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(ffbMatchroundPeer::MATCHROUND_GAME_ID, $_POST['game_id']);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns all past (not running) Matchrounds
    //used by myteam.js
    public function getPastMatchrounds() {
        $now = time();
        $date = date('Y-n-j G:i:s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $this->getMatchroundByCriteria($criteria);
    }

	//returns all past (not running) Matchrounds
	//used by bestteam_v2.js
	public function getPastMatchrounds_v2() {
		$now = time();
		$date = date('Y-n-j G:i:s', $now);
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
		$past_matchrounds = $this->getMatchroundByCriteria($criteria);

		if(count($past_matchrounds) > 0) {
			$past_matchrounds[0]['matchround_actual'] = 1;
		}

		$this->matchrounds = $past_matchrounds;
	}

    //returns all past (not running) Matchrounds
    //used by userscore.js
    public function getPastAndCurrentMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns past and actual Matchrounds
    //used by myteam.js
    public function getPastAndRunningMatchrounds() {
        $now = time();
        $date = date('Y-n-j G:i:s', $now);
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $past_matchrounds = $this->getMatchroundByCriteria($criteria);

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
        $criteria->setLimit(1);
        $running_matchrounds = $this->getMatchroundByCriteria($criteria);

        if(count($past_matchrounds) > 0) {
            $past_matchrounds[0]['matchround_actual'] = 1;
            //if(strtotime($past_matchrounds[0]['matchround_enddate']) > $now) {
            if(strtotime($past_matchrounds[0]['matchround_startdate']) > $now) {
				$past_matchrounds[0]['matchround_running'] = 1;
			}
        } else {
            if(count($running_matchrounds) > 0) {
                $running_matchrounds[0]['matchround_actual'] = 1;
            }
        }

        $matchrounds = array();
        if(count($running_matchrounds) > 0 && $past_matchrounds[0]['matchround_running'] != 1) {
            $running_matchrounds[0]['matchround_running'] = 1;
            $matchrounds = array_merge($running_matchrounds, $past_matchrounds);
        } elseif(count($running_matchrounds) > 0 && $past_matchrounds[0]['matchround_running'] == 1) {
        	$running_matchrounds[0]['matchround_future'] = 1;
            $matchrounds = array_merge($running_matchrounds, $past_matchrounds);
        } else {
            $matchrounds = $past_matchrounds;
        }
        $this->matchrounds = $matchrounds;
    }

    //returns all future (not yet started) Matchrounds
    public function getFutureMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        //$criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns the next (not yet started) Matchround
    //used by lineup.js; myteam.js; playerinfo.js;
    public function getNextMatchround() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->setLimit(1);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns the current (running) Matchround and all Past Matchrounds
    public function getCurrentMatchround() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->setLimit(1);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns matchrounds by given criteria
    private function getMatchroundByCriteria($criteria) {
        $items = FfbMatchroundPeer::doSelect($criteria);

        $matchrounds = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $matchrounds[$i]['matchround_id']		= $item->getMatchroundId();
                $matchrounds[$i]['matchround_title'] 	= $item->getMatchroundTitle();
                $matchrounds[$i]['matchround_actual'] 	= 0;
                $matchrounds[$i]['matchround_running'] 	= 0;
                $matchrounds[$i]['matchround_future'] 	= 0;
                $matchrounds[$i]['matchround_status'] 	= $item->getMatchroundStatus();
                $matchrounds[$i]['matchround_startdate']= date('j.n.Y',strtotime($item->getMatchroundStartdate()));
                $matchrounds[$i]['matchround_enddate'] 	= date('j.n.Y',strtotime($item->getMatchroundEnddate()));
                $matchrounds[$i]['matchround_deadline'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $matches = array();
                $criteria = new Criteria();
                $criteria->addAscendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
                $matchitems = $item->getFfbMatchs($criteria);
                if($matchitems) {
                    $j=0;
                    foreach($matchitems as $matchitem) {
                        $matches[$j]['match_id'] 			= $matchitem->getMatchId();
                        $matches[$j]['match_date']			= date('j.n.Y',strtotime($matchitem->getMatchDate()));
                        $matches[$j]['match_hometeam_id'] 	= $matchitem->getMatchHometeamId();
                        $matches[$j]['match_guestteam_id'] 	= $matchitem->getMatchGuestteamId();

                        $matches[$j]['match_hometeam_name'] 	= $matchitem->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                        $matches[$j]['match_guestteam_name']	= $matchitem->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
                        $matches[$j]['match_hometeam_nationality'] 	= $matchitem->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
                        $matches[$j]['match_guestteam_nationality'] = $matchitem->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();

                        $matches[$j]['match_homescore']	= $matchitem->getMatchHomescore();
                        $matches[$j]['match_guestscore']= $matchitem->getMatchGuestscore();

                        $matches[$j]['match_homescore_penalty']	= $matchitem->getMatchHomescorePenalty();
                        $matches[$j]['match_guestscore_penalty']= $matchitem->getMatchGuestscorePenalty();
                        $matches[$j]['match_status'] 			= $matchitem->getMatchStatus();
                        $j++;
                    }
                }
                $matchrounds[$i]['matches'] = $matches;
                $i++;
            }
        }
        $this->numResults = $i;
        $this->matchrounds = $matchrounds;
        return $matchrounds;
    }

/*
    //returns matches by given criteria
    private function getMatchByCriteria($criteria) {
        $list = FfbMatchPeer::doSelect($criteria);
        $match = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $match[$i]['match_id'] = $item->getMatchId();
                $match[$i]['match_round'] = $item->getMatchRound();

                $match[$i]['match_round_name'] = $item->getFfbMatchround()->getMatchroundTitle();
                $match[$i]['match_date'] = date('j.n.Y',strtotime($item->getMatchDate()));
                $match[$i]['match_hometeam_id'] = $item->getMatchHometeamId();
                $match[$i]['match_guestteam_id'] = $item->getMatchGuestteamId();

                $match[$i]['match_hometeam_name'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                $match[$i]['match_guestteam_name'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
                $match[$i]['match_hometeam_nationality'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
                $match[$i]['match_guestteam_nationality'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();

                $match[$i]['match_homescore'] = $item->getMatchHomescore();
                $match[$i]['match_guestscore'] = $item->getMatchGuestscore();
                $match[$i]['match_status'] = $item->getMatchStatus();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->matches = $match;
    }
*/
}
?>