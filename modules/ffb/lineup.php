<?php

/**
 * FFB-Module - LINEUP-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 09/2009
 * @version 0.1
 *
 */

class lineup extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    	/*
    	$user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    	*/
        $this->lineup();
    }

	public function lineup2() {
        $this->checkGame();
        $this->htmlFile = 'lineup2.php';
        //$this->adBottomRight = $this->advert->getAd('aufstellung rechts');
	}

	public function lineup() {
        $this->checkGame();
        $this->htmlFile = 'lineup.php';
		$this->htmlTitle = 'Lineup';
		if($this->config->area_load_ads == 1) {
        	$this->adBottomRight = $this->advert->getAd('aufstellung rechts');
        	$ads[]  = $this->advert->getAd('CommentsText');
        	$ads[]  = $this->advert->getAd('CommentsText');
        	$ads[]  = $this->advert->getAd('CommentsText');
        	$this->adCommentText  = $ads;
		}
        require_once('comments.php');
        comments::getCommentsParam('lineup', null, DEFAULT_COMMENT_NUMBER, false);
	}

    public function getMatchroundAndTeams() {
        $now = time();
        $date = date('Y-n-j G:i:s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->setLimit(1);
        $matchrounds = $this->getMatchroundByCriteria($criteria);
        $matchround_id = $matchrounds[0]['matchround_id'];

        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $matchround_id);
        $criteria->add(FfbMatchPeer::MATCH_STATUS, '');
        $matches = FfbMatchPeer::doSelect($criteria);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
        if($matches) {
            foreach($matches as $match) {
                $c1 = $criteria->getNewCriterion(FfbTeamPeer::TEAM_ID, $match->getMatchHometeamId());
                $c1->addOr($criteria->getNewCriterion(FfbTeamPeer::TEAM_ID, $match->getMatchGuestteamId()));
                $criteria->addOr($c1);
            }
        } else {
            $criteria->add(FfbTeamPeer::TEAM_ID, 0); //unmögliches criterium, damit keine Teams gefunden werden
        }
        $teams = $this->getTeamsByCriteria($criteria);
        $matchrounds[0]['teams'] = $teams;
        $this->matchrounds = $matchrounds;
    }

    private function getMatchroundByCriteria($criteria) {
        $items = FfbMatchroundPeer::doSelect($criteria);
        $matchrounds = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $matchrounds[$i]['matchround_id'] = $item->getMatchroundId();
                $matchrounds[$i]['matchround_title'] = $item->getMatchroundTitle();
                $matchrounds[$i]['matchround_status'] = $item->getMatchroundStatus();
                $matchrounds[$i]['matchround_startdate'] = date('j.n.Y',strtotime($item->getMatchroundStartdate()));
                $matchrounds[$i]['matchround_enddate'] = date('j.n.Y',strtotime($item->getMatchroundEnddate()));
                $matchrounds[$i]['matchround_deadline'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $matches = array();
                $criteria = new Criteria();
                $criteria->addAscendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
                $matchitems = $item->getFfbMatchs($criteria);
                if($matchitems) {
                    $j=0;
                    foreach($matchitems as $matchitem) {
                        $matches[$j]['match_id'] = $matchitem->getMatchId();
                        $matches[$j]['match_date'] = date('j.n.Y',strtotime($matchitem->getMatchDate()));
                        $matches[$j]['match_hometeam_id'] = $matchitem->getMatchHometeamId();
                        $matches[$j]['match_guestteam_id'] = $matchitem->getMatchGuestteamId();

                        $matches[$j]['match_hometeam_name'] = $matchitem->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                        $matches[$j]['match_guestteam_name'] = $matchitem->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
                        $matches[$j]['match_hometeam_nationality'] = $matchitem->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
                        $matches[$j]['match_guestteam_nationality'] = $matchitem->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();

                        $matches[$j]['match_homescore'] = $matchitem->getMatchHomescore();
                        $matches[$j]['match_guestscore'] = $matchitem->getMatchGuestscore();

                        $matches[$j]['match_homescore_penalty'] = $matchitem->getMatchHomescorePenalty();
                        $matches[$j]['match_guestscore_penalty'] = $matchitem->getMatchGuestscorePenalty();
                        $matches[$j]['match_status'] = $matchitem->getMatchStatus();
                        $j++;
                    }
                }
                $matchrounds[$i]['matches'] = $matches;
                $i++;
            }
        }
        return $matchrounds;
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
                $teams[$i]['team_avg_price'] = round($item->getTeamAvgPrice(), 1);
                $i++;
            }
        }
        return $teams;
    }

    private function checkGame() {
        $game_id = $this->session->game_id_player;
        $game_item = FfbGamePeer::retrieveByPK($game_id);
        if($game_item) {
            if($game_item->getGameArchive() == 0) {
                $this->game_over = 0;
            } else {
                $this->game_over = 1;
            }
        }
        return;
    }
}
?>
