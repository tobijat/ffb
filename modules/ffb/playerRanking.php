<?php

/**
 * FFB-Module - PLAYER-RANKING-Klasse;
 * berechnet den PlayerRank
 *
 * @author Gritschacher Tobias
 * @copyright 10/2008
 * @version 0.1
 *
 */

class playerRanking extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    public function test() {
        require_once('team.php');
        $team = new team();
        //echo $team->testAvg();
        echo $this->calculatePlayerGrade(565);
        exit();
    }

    public function testMaxLineups() {

    }

    public function testMatchesPlayed() {
        $playerteam_id = 1922;
        $playerteam_item = FfbPlayerteamPeer::retrieveByPK($playerteam_id);

        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::NOT_EQUAL);
        $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $playerteam_item->getPlayerteamTeamId());
        $c2 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $playerteam_item->getPlayerteamTeamId());
        $c1->addOr($c2);
        $criteria->add($c1);
        $num_matches = FfbMatchPeer::doCountJoinFfbMatchround($criteria);

        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, 1922);
        $num_matches_played = FfbPlayerstatsPeer::doCountJoinFfbMatchround($criteria);

        echo $num_matches.'<br>';
        echo $num_matches_played;
        exit();
    }

    public function getPlayerGrade() {
        $grade = $this->calculatePlayerGrade($_POST['id']);
        $this->player_grade = $grade;
        //return $grade;
    }

    public function testPlayerPower() {
        $this->calculatePlayerPower($_REQUEST['playerteam_id'], $_REQUEST['matchround_id']);
    }

    public function calculatePlayerPower($playerteam_id, $playerteam_position) {
    	$player = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
		$ffb_player = $player->getFfbPlayer();
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
        $pt_ids = array();
        foreach($ffb_playerteams as $ffbpt) {
        	$pt_ids[] = $ffbpt->getPlayerteamId();
		}

        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $game_id = $this->session->game_id_player;
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::GREATER_THAN);
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_ENDDATE);
        $criteria->setLimit(1);
        $items = FfbMatchroundPeer::doSelect($criteria);
        if($items) {
            $matchround_id = $items[0]->getMatchroundId();
        } else {
            $matchround_id = 0;
        }

        //get MAX player power for matchround
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $playerteam_position);
        $criteria->addDescendingOrderByColumn(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER);
        $criteria->setLimit(1);
        $pp_item = FfbPlayerpricePeer::doSelectJoinFfbPlayerteam($criteria);
        if($pp_item) {
            $pp_max = $pp_item[0]->getPlayerpricePlayerPower();
        } else {
            $pp_max = 0;
        }

        //get MIN player power for matchround
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $playerteam_position);
        $criteria->addAscendingOrderByColumn(FfbPlayerpricePeer::PLAYERPRICE_PLAYER_POWER);
        $criteria->setLimit(1);
        $pp_item = FfbPlayerpricePeer::doSelectJoinFfbPlayerteam($criteria);
        if($pp_item) {
            $pp_min = $pp_item[0]->getPlayerpricePlayerPower();
        } else {
            $pp_min = 0;
        }

        //get player power for matchround and player
        $criteria = new Criteria();
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
        //$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
        $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
        $criteria->setLimit(1);
        $pp_item = FfbPlayerpricePeer::doSelect($criteria);
        if($pp_item) {
            $ppower = $pp_item[0]->getPlayerpricePlayerPower();
            $ppower_position = $pp_item[0]->getPlayerpriceAvPower();
        } else {
            $ppower = 0;
            $ppower_position = 0;
        }

        //minutes played?
        $criteria = new Criteria();
        //$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $num_playerstats = FfbPlayerstatsPeer::doCountJoinFfbMatchround($criteria);

        //$perc = ($pp_max-$pp_min)/100;
        $perc_min = ($ppower_position-$pp_min)/50;
        $perc_max = ($pp_max - $ppower_position)/50;

        //calculate % playerpower
        if($perc_min && $perc_max && $num_playerstats) {
            //$player_power = round(100-(($pp_max-$ppower)/$perc),0);
            if($ppower == $ppower_position) {
                $player_power = 50;
            } elseif($ppower < $ppower_position) {
                $player_power = round(50-(($ppower_position-$ppower)/$perc_min),0);
            } elseif($ppower > $ppower_position) {
                $player_power = round(50+(($ppower-$ppower_position)/$perc_max),0);
            }
        } else {
            $player_power = 0;
        }

        //echo 'PP: '.$player_power.'<br>';
        //exit();
        return $player_power;
    }

    public function calculatePlayerGrade($playerteam_id) {
        //***** count matches and matches played*****
        $playerteam_item = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::NOT_EQUAL);
        $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $playerteam_item->getPlayerteamTeamId());
        $c2 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $playerteam_item->getPlayerteamTeamId());
        $c1->addOr($c2);
        $criteria->add($c1);
        $num_matches = FfbMatchPeer::doCountJoinFfbMatchround($criteria);
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $num_matches_played = FfbPlayerstatsPeer::doCountJoinFfbMatchround($criteria);

        if($num_matches==0)
          $matches_played_avg = 0;
        else
          $matches_played_avg = $num_matches_played/$num_matches;
        //*****
        if($num_matches <= 0) {
            return 0;
        }
        //***** count userteams *****
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $num_userteams = FfbUserteamPeer::doCountJoinFfbMatchround($criteria);
        $num_userteams = round(($num_userteams/$num_matches)*0.75, 0);
        //*****

        $criteria = new Criteria();
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
        $playerstats_items = FfbPlayerstatsPeer::doSelectJoinFfbMatchround($criteria);
        if(!$playerstats_items) {
            $grade_score_points = 0;
            $grade_score_minutes = 0;
            $grade_score_lineups = $this->calculatePlayerGradeLineups($playerteam_id, $num_matches, $num_userteams);
        } else {
            //***** get highest AVG Points *****
            $criteria = new Criteria();
            $criteria->clearSelectColumns()->addSelectColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
            $criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
            $criteria->addDescendingOrderByColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
            $criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
            $criteria->setLimit(1);
            $result = FfbPlayerstatsPeer::doSelectStmt($criteria);
            $row = $result->fetch(PDO::FETCH_NUM);
            $max_points_avg = $row[0];
            //*****
            //***** get highest AVG Minutes *****
            $criteria = new Criteria();
            $criteria->clearSelectColumns()->addSelectColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_MINUTES.')');
            $criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
            $criteria->addDescendingOrderByColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_MINUTES.')');
            $criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
            $criteria->setLimit(1);
            $result = FfbPlayerstatsPeer::doSelectStmt($criteria);
            $row = $result->fetch(PDO::FETCH_NUM);
            $max_minutes_avg = $row[0];
            //*****
            $grade_score_points = $this->calculatePlayerGradePoints($playerteam_id, $max_points_avg, $matches_played_avg);
            $grade_score_minutes = $this->calculatePlayerGradeMinutes($playerteam_id, $max_minutes_avg, $matches_played_avg);
            $grade_score_lineups = $this->calculatePlayerGradeLineups($playerteam_id, $num_matches, $num_userteams);
        }
        $player_grade = round((0.15*$grade_score_lineups)+(0.425*$grade_score_minutes)+(0.425*$grade_score_points),0);
        //$player_grade = round(((0.15*$grade_score_lineups)+(0.425*$grade_score_minutes)+(0.425*$grade_score_points))*($num_matches_played/$num_matches),0);
        if($player_grade > 100)
            $player_grade = 100;
        if($player_grade < 0)
            $player_grade = 0;
        return $player_grade;
    }

    public function testCalculatePlayerTrend() {
    	$pt_id = $_REQUEST['id'];
		$this->calculatePlayerTrend($pt_id);
		echo 'fertig';
		exit();
	}

    public function calculatePlayerTrend($playerteam_id) {
    	$limit = 5;
		//get all FFB_Playerteams for this player and store playerteam_ids into array
		$ffb_playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
		$ffb_player = $ffb_playerteam->getFfbPlayer();
		$pos = $ffb_playerteam->getPlayerteamPlayerPosition();
		$team_id = $ffb_playerteam->getPlayerteamTeamId();
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
        $pt_ids = array();
        foreach($ffb_playerteams as $ffbpt) {
        	$pt_ids[] = $ffbpt->getPlayerteamId();
		}
		//*** ***

		//get all games in which the player participated and store ids into array
		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->addGroupByColumn(FfbGamePeer::GAME_ID);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$games = FfbGamePeer::doSelect($criteria);
		$g_ids = array();
		foreach($games as $game) {
			$g_ids[] = $game->getGameId();
		}
		//*** ***

		//count stats for all players for position and stats of individual players for position
		$criteria = new Criteria();
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_GAME_ID, FfbGamePeer::GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->addJoin(FfbPLayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $pos);
		$num_players = FfbPlayerstatsPeer::doCount($criteria);
		$criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$num_diff_players = FfbPlayerstatsPeer::doCount($criteria);
		if($num_diff_players > 0) {
			$avg_matches_per_player = $num_players/$num_diff_players;
		} else {
			$avg_matches_per_player = 0;
		}
		/*
		echo 'num players: '.$num_players.'<br>';
		echo 'num diff players: '.$num_diff_players.'<br>';
		echo 'avg matchs per player: '.$avg_matches_per_player.'<br>';
		*/
		//*** ***

		//get sum of points for position
		$criteria = new Criteria();
		$criteria->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $pos);
		$criteria->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
		$playerscore = FfbPlayerstatsPeer::doSelect($criteria);
		$sumPoints_all = $playerscore[0]->getPlayerstatsId();
		if($num_players > 0) {
			$avg_points_per_player_per_match = $sumPoints_all/$num_players;
		} else {
			$avg_points_per_player_per_match = 0;
		}
		/*
		echo 'sum points: '.$pos.': '.$sumPoints_all.'<br>';
		echo 'avg points per player per match: '.$avg_points_per_player_per_match.'<br>';
		*/
		//*** ***

		//get sum of points for player
		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);
		$c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $team_id);
		$c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $team_id));
		$criteria->add($c1);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_ENDDATE);
		$criteria->setLimit($limit);
		$last_matchrounds = FfbMatchroundPeer::doSelect($criteria);
		$mr_ids = array();
		$last_mr = 0;
		if($last_matchrounds) {
			foreach($last_matchrounds as $lmr) {
				$mr_ids[] = $lmr->getMatchroundId();
			}
			$last_mr = $mr_ids[0];
		}

		$criteria = new Criteria();
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$criteria->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
		$playerscore = FfbPlayerstatsPeer::doSelect($criteria);
		$sumPoints_player = $playerscore[0]->getPlayerstatsId();
		$sumStats_player = FfbPlayerstatsPeer::doCount($criteria);
		if($sumStats_player > 0) {
			$avg_player_points_per_match = $sumPoints_player/$sumStats_player;
		} else {
			$avg_player_points_per_match = 0;
		}

		$b1 = $avg_player_points_per_match;
		$b2 = $avg_player_points_per_match * ($limit+1);

		//echo 'all rounds: avg '.$avg_player_points_per_match.' points<br>';
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
		$mult = 2;
		for($i=$limit;$i>0;$i--) {
			$criteria2 = $criteria;
			$criteria2->add(FfbMatchroundPeer::MATCHROUND_ID, $mr_ids, Criteria::IN);
			$playerscore = FfbPlayerstatsPeer::doSelect($criteria2);
			$sumPoints_player_limit = $playerscore[0]->getPlayerstatsId();
			$avg = $sumPoints_player_limit/$i;
			//echo 'last '.$i.' rounds: avg '.$avg.' points<br>';
			array_pop($mr_ids);
			$b1 += $mult*$avg;
			$b2 += $i*$avg;
			$mult++;
		}

		if($b2) {
			$player_trend = abs($b1/$b2);
			$player_trend_perc = (($b1/$b2)-1)*100;
		} else {
			$player_trend = 0;
			$player_trend_perc = 0;
		}
		//echo 'player trend: '.round($player_trend_perc, 2).'%<br>';
		//echo $ffb_player->getPlayerFname().' '.$ffb_player->getPlayerLname().'<br>';
		//*** ***

		return $player_trend;
    }




    public function testCalculatePlayerGrade_v2() {
    	$pt_id = $_REQUEST['id'];
		$values = $this->calculatePlayerGrade_v2($pt_id);
		echo $values['answer'];
		echo '<br><br>fertig';
		exit();
	}

    public function calculatePlayerGrade_v2($playerteam_id) {
    	$limit = 5;
    	$answer = '';
		//get all FFB_Playerteams for this player and store playerteam_ids into array
		$ffb_playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
		$ffb_player = $ffb_playerteam->getFfbPlayer();
		$pos = $ffb_playerteam->getPlayerteamPlayerPosition();
		$team_id = $ffb_playerteam->getPlayerteamTeamId();
		$criteria = new Criteria();
		$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
        $pt_ids = array();
        foreach($ffb_playerteams as $ffbpt) {
        	$pt_ids[] = $ffbpt->getPlayerteamId();
		}
		$answer .= $ffb_player->getPlayerFname().' '.$ffb_player->getPlayerLname().'<br>';
		//*** ***

		//get all games in which the player participated and store ids into array
		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->addGroupByColumn(FfbGamePeer::GAME_ID);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$games = FfbGamePeer::doSelect($criteria);
		$g_ids = array();
		foreach($games as $game) {
			$g_ids[] = $game->getGameId();
		}
		//*** ***

		//count stats for all players for position and stats of individual players for position
		$criteria = new Criteria();
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_GAME_ID, FfbGamePeer::GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->addJoin(FfbPLayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $pos);
		$num_players = FfbPlayerstatsPeer::doCount($criteria);
		$criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$num_diff_players = FfbPlayerstatsPeer::doCount($criteria);
		if($num_diff_players > 0) {
			$avg_matches_per_player = $num_players/$num_diff_players;
		} else {
			$avg_matches_per_player = 0;
		}
		//*** ***

		//get sum of points for position
		$criteria = new Criteria();
		$criteria->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $pos);
		$criteria->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
		$playerscore = FfbPlayerstatsPeer::doSelect($criteria);
		$sumPoints_all = $playerscore[0]->getPlayerstatsId();
		if($num_players > 0) {
			$avg_points_per_player_per_match = $sumPoints_all/$num_players;
		} else {
			$avg_points_per_player_per_match = 0;
		}
		$answer .= 'global avg points/match: '.$avg_points_per_player_per_match.'<br>';
		//*** ***

		//get mr_ids of the last >>LIMIT<< matches in which the user's team played
		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);
		$c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $team_id);
		$c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $team_id));
		$criteria->add($c1);
		$criteria->add(FfbGamePeer::GAME_ID, $g_ids, Criteria::IN);
		//$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		//$criteria->add(FfbMatchPeer::MATCH_DATE, date('Y-m-d H:i:s', time()), Criteria::LESS_THAN);
		$criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->setLimit($limit);
		$last_matchrounds = FfbMatchroundPeer::doSelect($criteria);
		$mr_ids = array();
		$last_mr = 0;
		if($last_matchrounds) {
			$answer .= 'mr ids: ';
			foreach($last_matchrounds as $lmr) {
				$mr_ids[] = $lmr->getMatchroundId();
				$answer .= $lmr->getMatchroundId().' ';
			}
			$answer .= '<br>';
			$last_mr = $mr_ids[0];
		}
		$answer .= 'num matches: '.count($mr_ids).'<br>';
		//*** ***

		//get sum of points for player
		$criteria = new Criteria();
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$criteria->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
		$playerscore = FfbPlayerstatsPeer::doSelect($criteria);
		$sumPoints_player = $playerscore[0]->getPlayerstatsId();
		$sumStats_player = FfbPlayerstatsPeer::doCount($criteria);
		$answer .= 'sumPoints: '.$sumPoints_player.' sumStats: '.$sumStats_player.'<br>';
		if($sumStats_player > 0) {
			$avg_player_points_per_match = $sumPoints_player/$sumStats_player;
		} else {
			$avg_player_points_per_match = 0;
		}
		//*** ***

		//get the trend of the player
		if(count($mr_ids) < $limit) {
			$limit = count($mr_ids);
		}
		$tmpcrit = new Criteria();
		$tmpcrit->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
		$tmpcrit->add(FfbMatchroundPeer::MATCHROUND_ID, $mr_ids, Criteria::IN);
		$tmpcrit->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
		$matches_played = FfbPlayerstatsPeer::doCount($tmpcrit);
		$answer .= 'matches played: '.$matches_played.'<br>';
		if($limit) {
			$perc_played = $matches_played/$limit;
		} else {
			$perc_played = 0;
		}
		//$b1 = $avg_player_points_per_match;
		//$b2 = $avg_player_points_per_match * ($limit+1);
		$b1 = 0;
		$b2 = 0;
		$answer .= 'player avg points/match: '.$avg_player_points_per_match.'<br>';
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
		//$mult = 2;
		$mult = 1;
		for($i=$limit;$i>0;$i--) {
			$answer .= 'mr_ids: ';
			foreach($mr_ids as $id) {
				$answer .= $id.' ';
			}
			$answer .= '<br>';
			$criteria2 = $criteria;
			$criteria2->add(FfbMatchroundPeer::MATCHROUND_ID, $mr_ids, Criteria::IN);
			$playerscore = array();
			unset($playerscore);
			$playerscore = FfbPlayerstatsPeer::doSelect($criteria2);
			$sumPoints_player_limit = $playerscore[0]->getPlayerstatsId();
			$avg = $sumPoints_player_limit/$i;
			$answer .= 'last '.$i.' rounds: avg '.$avg.' points<br>';
			array_pop($mr_ids);
			$b1 += $mult*$avg;
			$b2 += $i*$avg;
			$mult++;
			if(!count($mr_ids)) {
				break;
			}
		}
		if($b2) {
			$answer .= 'b1: '.$b1.' b2: '.$b2.'<br>';
			//$player_trend = abs($b1/$b2);
			$player_trend = abs($b1/$b2) * $perc_played;
			$player_trend_perc = (($b1/$b2)-1)*100 * $perc_played;
			//avoiding positive trend when points of last matches are below zero
			if($b1<0 && $b2<0) {
				$player_trend *= -1;
				$player_trend_perc *= -1;
			}
		} else {
			$player_trend = 0;
			$player_trend_perc = 0;
		}
		//*** ***

		//calc the grade of the player
		if($avg_points_per_player_per_match > 0) {
			$perc_player = (50/$avg_points_per_player_per_match)*$avg_player_points_per_match;
		} else {
			$perc_player = 0;
		}
		//adjust the player average regarding the player trend
		$perc_player = $perc_player * $player_trend;
		//check the boundaries
		if($perc_player > 100) {
			$perc_player = 100;
		} elseif($perc_player < 0) {
			$perc_player = 0;
		}
		if($player_trend_perc > 100) {
			$player_trend_perc = 100;
		} elseif($player_trend_perc < -100) {
			$player_trend_perc = -100;
		}

		$values['player_trend'] = round($player_trend_perc, 0);
		$values['player_grade'] = round($perc_player, 0);

		$answer .= 'perc_played: '.$perc_played.'<br>';
		$answer .= 'player trend: '.round($player_trend_perc, 2).'%<br>';
		$answer .= 'player grade: '.round($perc_player, 2).'%<br>';
		$values['answer'] = $answer;
		//*** ***

		return $values;
    }

    public function testAvg() {
        $criteria = new Criteria();
            $criteria->clearSelectColumns()->addSelectColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
            $criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
            $criteria->addDescendingOrderByColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
            $criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_player);
            $criteria->setLimit(1);
            $result = FfbPlayerstatsPeer::doSelectStmt($criteria);
            $row = $result->fetch(PDO::FETCH_NUM);
            $max_points_avg = $row[0];

            return $max_points_avg;
            exit();
    }

    //private function calculatePlayerGradePoints($playerstats_items) {
    private function calculatePlayerGradePoints($playerteam_id, $max_points_avg, $matches_played_avg) {
        $criteria = new Criteria();
        $criteria->clearSelectColumns()->addSelectColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
        //$criteria->clearSelectColumns()->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
        $criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->setLimit(1);
        $result = FfbPlayerstatsPeer::doSelectStmt($criteria);
        $row = $result->fetch(PDO::FETCH_NUM);
        $player_points_avg = $row[0];
        //$player_points_avg = $player_points_avg * $matches_played_avg;
        $value = ($player_points_avg/$max_points_avg)*100;
        return $value;
    }

    private function calculatePlayerGradeMinutes($playerteam_id, $max_minutes_avg, $matches_played_avg) {
        $criteria = new Criteria();
        $criteria->clearSelectColumns()->addSelectColumn('AVG('.FfbPlayerstatsPeer::PLAYERSTATS_MINUTES.')');
        //$criteria->clearSelectColumns()->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_MINUTES.')');
        $criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
        $criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $playerteam_id);
        $criteria->setLimit(1);
        $result = FfbPlayerstatsPeer::doSelectStmt($criteria);
        $row = $result->fetch(PDO::FETCH_NUM);
        $player_minutes_avg = $row[0];
        $player_minutes_avg = $player_minutes_avg * $matches_played_avg;
        $value = ($player_minutes_avg/$max_minutes_avg)*100;
        return $value;
    }

    private function calculatePlayerGradeLineups($playerteam_id, $num_matches, $num_userteams) {
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
        if($num_lineups == 0) {
            return 0;
        }
        $lineups_per_match = $num_lineups/$num_matches;
        if($lineups_per_match>$num_userteams) {
            $lineups_per_match = $num_userteams;
        }
        $value = ($lineups_per_match/$num_userteams)*100;
        return $value;
    }
}
