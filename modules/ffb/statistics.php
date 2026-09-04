<?php

/**
 * FFB - STATISTICS-Klasse;
 *
 * @author Gritschacher Tobias, Gerald Musser
 * @copyright 10/2009
 * @version 0.1
 *
 */

class statistics extends FFB_Auth_User {
    private $options;

    public function __construct() {
        parent::__construct();
        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }

    public function getUserStats() {
        $user_id = $_REQUEST['user_id'];
        $matchround_id = $_REQUEST['matchround_id'];
        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_id);
        $criteria->setLimit(1);
        $userteam = FfbUserteamPeer::doSelect($criteria);
        if($userteam) {
            $players = array();
            $players[0] = $userteam[0]->getUserteamPlayerId1();
            $players[1] = $userteam[0]->getUserteamPlayerId2();
            $players[2] = $userteam[0]->getUserteamPlayerId3();
            $players[3] = $userteam[0]->getUserteamPlayerId4();
            $players[4] = $userteam[0]->getUserteamPlayerId5();
            $players[5] = $userteam[0]->getUserteamPlayerId6();
            $players[6] = $userteam[0]->getUserteamPlayerId7();
            $players[7] = $userteam[0]->getUserteamPlayerId8();
            $players[8] = $userteam[0]->getUserteamPlayerId9();
            $players[9] = $userteam[0]->getUserteamPlayerId10();
            $players[10] = $userteam[0]->getUserteamPlayerId11();
            $userteam_stats = $this->getUserteamStats($players, $matchround_id);
            $userteam_stats['price'] = $userteam[0]->getUserteamPrice();
            $userteam_stats['score'] = $userteam[0]->getUserteamScore();
            $userteam_stats['score_per_player'] = round(($userteam_stats['score']/11), 2);
            if($userteam_stats['score']) {
                $userteam_stats['credits_per_point'] = round(($userteam_stats['price']/$userteam_stats['score']), 1);
            } else {
                $userteam_stats['credits_per_point'] = 0;
            }
        }
        if($userteam_stats) {
            $user_stats = $userteam_stats;
        } else {
            $user_stats = 0;
        }
        $this->user_stats = $user_stats;
    }

    private function getUserteamStats($players, $matchround_id) {
        $goals = 0;
        $owngoals = 0;
        $cards_r = 0;
        $cards_yr = 0;
        $cards_y = 0;
        $minutes = 0;
        $num_g = 0;
        $num_d = 0;
        $num_m = 0;
        $num_s = 0;
        $score_g = 0;
        $score_d = 0;
        $score_m = 0;
        $score_s = 0;
        foreach($players as $playerteam_id) {
            $criteria = new Criteria();
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
            $criteria->setLimit(1);
            $playerstats = FfbPlayerstatsPeer::doSelect($criteria);
            $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
            if($playerstats) {
                $goals += $playerstats[0]->getPlayerstatsGoals();
                $owngoals += $playerstats[0]->getPlayerstatsOwngoals();
                if($playerstats[0]->getPlayerstatsCards() == 'r') {
                    $cards_r ++;
                } elseif($playerstats[0]->getPlayerstatsCards() == 'yr') {
                    $cards_yr ++;
                } elseif($playerstats[0]->getPlayerstatsCards() == 'y') {
                    $cards_y ++;
                }
                $minutes += $playerstats[0]->getPlayerstatsMinutes();

                if($playerteam->getPlayerteamPlayerPosition() == 'g') {
                    $score_g += $playerstats[0]->getPlayerstatsScore();
                } elseif($playerteam->getPlayerteamPlayerPosition() == 'd') {
                    $score_d += $playerstats[0]->getPlayerstatsScore();
                } elseif($playerteam->getPlayerteamPlayerPosition() == 'm') {
                    $score_m += $playerstats[0]->getPlayerstatsScore();
                } elseif($playerteam->getPlayerteamPlayerPosition() == 's') {
                    $score_s += $playerstats[0]->getPlayerstatsScore();
                }
            }
            if($playerteam->getPlayerteamPlayerPosition() == 'g') {
                $num_g++;
            } elseif($playerteam->getPlayerteamPlayerPosition() == 'd') {
                $num_d++;
            } elseif($playerteam->getPlayerteamPlayerPosition() == 'm') {
                $num_m++;
            } elseif($playerteam->getPlayerteamPlayerPosition() == 's') {
                $num_s++;
            }

        }
        $userteam_stats = array();
        $userteam_stats['goals'] = $goals;
        $userteam_stats['owngoals'] = $owngoals;
        $userteam_stats['cards_r'] = $cards_r;
        $userteam_stats['cards_yr'] = $cards_yr;
        $userteam_stats['cards_y'] = $cards_y;
        $userteam_stats['minutes'] = $minutes;
        $userteam_stats['system'] = $num_d.'-'.$num_m.'-'.$num_s;
        $userteam_stats['score_g'] = $score_g;
        $userteam_stats['score_d'] = $score_d;
        $userteam_stats['score_m'] = $score_m;
        $userteam_stats['score_s'] = $score_s;

        return $userteam_stats;
    }

    public function getRoundStats() {
        $matchround_id = $_REQUEST['matchround_id'];
        $matchround = FfbMatchroundPeer::retrieveByPK($matchround_id);
        $this->options = new FFB_Options($matchround->getFfbGame()->getGameId());
        $round_stats = array();
        $round_stats = $this->getRoundScores($matchround_id);
        $round_stats['top_of_round'] = $this->getTopOfRound($matchround_id);
        $round_stats['flop_of_round'] = $this->getFlopOfRound($matchround_id);
        $round_stats['num_matches'] = $this->getNumMatches($matchround_id);

        $this->round_stats = $round_stats;
    }

    public function getNumMatches($matchround_id) {
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $matchround_id);
        return(count(FfbMatchPeer::doSelect($criteria)));
    }


    private function getTopOfRound($matchround_id) {
        $pm = $this->options->options_game_pointsmode;
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
        $playerprice = FfbPlayerpricePeer::doSelect($criteria);
        if($pm == 'old' || !$playerprice) {
            $criteria = new Criteria();
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
            $criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
            $criteria->setLimit(1);
            $playerstats_obj = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
        	if($playerstats_obj) {
        		$playerstats = $playerstats_obj[0];
        	}
        } else {
            $sql = "SELECT ffb_playerstats.playerstats_id from ffb_playerstats, ffb_playerprice, ffb_playerteam WHERE
                    ffb_playerstats.playerstats_matchround_id='$matchround_id' AND
                    ffb_playerstats.playerstats_playerteam_id=ffb_playerprice.playerprice_playerteam_id AND
                    ffb_playerprice.playerprice_matchround_id='$matchround_id' AND
                    ffb_playerteam.playerteam_id = ffb_playerstats.playerstats_playerteam_id
                    order by ffb_playerstats.playerstats_score DESC, ffb_playerprice.playerprice_price ASC
                    LIMIT 1";
            $con = Propel::getConnection('d00817fb');
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $playerstats_obj = FfbPlayerteamPeer::populateObjects($stmt);
        	if($playerstats_obj) {
        		$playerstats = FfbPlayerstatsPeer::retrieveByPK($playerstats_obj[0]->getPlayerteamId());
        	}
        }
        $top_of_round = array();
        if($playerstats) {
            $top_of_round['top_player_name'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $top_of_round['top_playerteam_id'] = $playerstats->getPlayerstatsPlayerteamId();
            $top_of_round['top_team_id'] = $playerstats->getFfbPlayerteam()->getPlayerteamTeamId();
            $top_of_round['top_team_name'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $top_of_round['top_team_nationality'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamNationality();
            $top_of_round['top_score'] = $playerstats->getPlayerstatsScore();
        } else {
            $top_of_round = 0;
        }
        return $top_of_round;
    }

    private function getFlopOfRound($matchround_id) {
        $pm = $this->options->options_game_pointsmode;
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
        $playerprice = FfbPlayerpricePeer::doSelect($criteria);
        if($pm == 'old' || !$playerprice) {
            $criteria = new Criteria();
            $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
            $criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
            $criteria->setLimit(1);
            $playerstats_obj = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
        	if($playerstats_obj) {
        		$playerstats = $playerstats_obj[0];
        	}
        } else {
            $sql = "SELECT ffb_playerstats.playerstats_id from ffb_playerstats, ffb_playerprice, ffb_playerteam WHERE
                    ffb_playerstats.playerstats_matchround_id='$matchround_id' AND
                    ffb_playerstats.playerstats_playerteam_id=ffb_playerprice.playerprice_playerteam_id AND
                    ffb_playerprice.playerprice_matchround_id='$matchround_id' AND
                    ffb_playerteam.playerteam_id = ffb_playerstats.playerstats_playerteam_id
                    order by ffb_playerstats.playerstats_score ASC, ffb_playerprice.playerprice_price DESC
                    LIMIT 1";
            $con = Propel::getConnection('d00817fb');
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $playerstats_obj = FfbPlayerteamPeer::populateObjects($stmt);
        	if($playerstats_obj) {
        		$playerstats = FfbPlayerstatsPeer::retrieveByPK($playerstats_obj[0]->getPlayerteamId());
        	}
        }

        $flop_of_round = array();
        if($playerstats) {
            $flop_of_round['flop_player_name'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname().' '.$playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
            $flop_of_round['flop_playerteam_id'] = $playerstats->getPlayerstatsPlayerteamId();
            $flop_of_round['flop_team_id'] = $playerstats->getFfbPlayerteam()->getPlayerteamTeamId();
            $flop_of_round['flop_team_name'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamName();
            $flop_of_round['flop_team_nationality'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamNationality();
            $flop_of_round['flop_score'] = $playerstats->getPlayerstatsScore();
        } else {
            $flop_of_round = 0;
        }
        return $flop_of_round;
    }

    private function getRoundScores($matchround_id) {
        $round_scores = array();
        $goals = 0;
        $owngoals = 0;
        $cards_r = 0;
        $cards_yr = 0;
        $cards_y = 0;
        $minutes = 0;
        $score = 0;
        $credits = 0;

        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
        $userteams = FfbUserteamPeer::doSelect($criteria);
        $round_scores['num_users'] = count($userteams);
        foreach($userteams as $item) {
            $credits += $item->getUserteamPrice();
        }
        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
        $playerstats = FfbPlayerstatsPeer::doSelect($criteria);
        $round_scores['num_players'] = count($playerstats);
        foreach($playerstats as $item) {
            $goals += $item->getPlayerstatsGoals();
            $owngoals += $item->getPlayerstatsOwngoals();
            if($item->getPlayerstatsCards() == 'r') {
                $cards_r ++;
            } elseif($item->getPlayerstatsCards() == 'yr') {
                $cards_yr ++;
            } elseif($item->getPlayerstatsCards() == 'y') {
                $cards_y ++;
            }
            $minutes += $item->getPlayerstatsMinutes();
            $score += $item->getPlayerstatsScore();
        }
        $round_scores['goals'] = $goals;
        $round_scores['owngoals'] = $owngoals;
        $round_scores['cards_r'] = $cards_r;
        $round_scores['cards_yr'] = $cards_yr;
        $round_scores['cards_y'] = $cards_y;
        $round_scores['minutes'] = $minutes;
        $round_scores['score'] = $score;
        $round_scores['credits'] = $credits;
        if($round_scores['num_players']>0) {
            $round_scores['score_per_player'] = round(($score/$round_scores['num_players']), 2);
        } else {
            $round_scores['score_per_player'] = 0;
        }
        if($score>0) {
            $round_scores['credits_per_point'] = round(($credits/$score), 1);
        } else {
            $round_scores['credits_per_point'] = 0;
        }

        return $round_scores;
    }
}
?>