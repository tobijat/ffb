<?php

/**
 * FFB-API-Module - MATCHROUND-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 07/2009
 * @version 0.1
 *
 */

class matchround extends FFB_Auth_Api {

    private $options;

    public function __construct() {
        parent::__construct();

        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }

    //returns list of actual matches
    public function getActualMatches() {
        $game_id = $_REQUEST['game_id'];
        //echo $game_id;

        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->setLimit(1);
        $matchround = $this->getMatchroundByCriteria($criteria);

        $this->matchround = $matchround;
    }

    //returns list of next matches
    public function getNextMatches() {
        $game_id = $_REQUEST['game_id'];
        //echo $game_id;

        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->setLimit(1);
        $matchround = $this->getMatchroundByCriteria($criteria);

        $this->matchround = $matchround;
    }

    //returns list of matches for given matchround id
    public function getMatchesForRound() {
        $game_id = $_REQUEST['game_id'];
        $matchround_id = $_REQUEST['matchround_id'];
        if(!$matchround_id) {
            $now = time();
            $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
            $criteria->setLimit(1);
            $matchround = $this->getMatchroundByCriteria($criteria);

            $matchround_id = $matchround[0]['matchround_id'];
        }
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->setLimit(1);
        $matchround = $this->getMatchroundByCriteria($criteria);

        $this->matchround = $matchround;
    }

    //returns list of matches for given team id
    public function getMatchesForTeam() {
        $game_id = $_REQUEST['game_id'];
        $team_id = $_REQUEST['team_id'];
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $team_id);
        $c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $team_id));
        $criteria->add($c1);
        $matchround = $this->getMatchesByCriteria($criteria);

        $this->matchround = $matchround;
    }

    //returns match for given match id
    public function getMatchById() {
        $match_id = $_REQUEST['match_id'];
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ID, $match_id);
        $matchround = $this->getMatchesByCriteria($criteria);

        $this->matchround = $matchround;
    }

    //returns list of all matchrounds of this game
    public function getAllMatchrounds() {
        $game_id = $_REQUEST['game_id'];
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $matchround = $this->getMatchroundByCriteria($criteria);

        $this->matchround = $matchround;
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
        $this->numResults = $i;
        return $matchrounds;
    }

    private function getMatchesByCriteria($criteria) {
        $items = FfbMatchPeer::doSelectJoinFfbMatchround($criteria);

        $matchrounds = array();


        $i=0;
        foreach($items as $item) {
            if($item) {
                $matchrounds[$i]['matchround_id'] = $item->getFfbMatchround()->getMatchroundId();
                $matchrounds[$i]['matchround_title'] = $item->getFfbMatchround()->getMatchroundTitle();
                $matchrounds[$i]['matchround_status'] = $item->getFfbMatchround()->getMatchroundStatus();
                $matchrounds[$i]['matchround_startdate'] = date('j.n.Y',strtotime($item->getFfbMatchround()->getMatchroundStartdate()));
                $matchrounds[$i]['matchround_enddate'] = date('j.n.Y',strtotime($item->getFfbMatchround()->getMatchroundEnddate()));
                $matchrounds[$i]['matchround_deadline'] = date('j.n.Y G:i',strtotime($item->getFfbMatchround()->getMatchroundStartdate()));

                $matches = array();
                unset($matches);

                $matches['match_id'] = $item->getMatchId();
                $matches['match_date'] = date('j.n.Y',strtotime($item->getMatchDate()));
                $matches['match_hometeam_id'] = $item->getMatchHometeamId();
                $matches['match_guestteam_id'] = $item->getMatchGuestteamId();

                $matches['match_hometeam_name'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamName();
                $matches['match_guestteam_name'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamName();
                $matches['match_hometeam_nationality'] = $item->getFfbTeamRelatedByMatchHometeamId()->getTeamNationality();
                $matches['match_guestteam_nationality'] = $item->getFfbTeamRelatedByMatchGuestteamId()->getTeamNationality();

                $matches['match_homescore'] = $item->getMatchHomescore();
                $matches['match_guestscore'] = $item->getMatchGuestscore();

                $matches['match_homescore_penalty'] = $item->getMatchHomescorePenalty();
                $matches['match_guestscore_penalty'] = $item->getMatchGuestscorePenalty();
                $matches['match_status'] = $item->getMatchStatus();

                $matchrounds[$i]['matches'] = $matches;
                $i++;
            }
        }

        $this->numResults = $i;
        return $matchrounds;
    }
}

?>
