<?php

/**
 * ADMIN - PLAYERPRICE-Klasse;
 * dynamische Preisberechnung fuer Spieler
 *
 * @author Gritschacher Tobias
 * @copyright 06/2010
 * @version 0.1
 *
 */

class playerprice extends FFB_Auth_AdminFfb {

    private $options;
    private $counts = array('plus'=>0, 'minus'=>0);

    private $dynamic_price_settings = array('margin'=>2);

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'playerprice.php';
        $this->options = new FFB_Options($this->session->game_id_admin);
        $this->getMatchrounds();
    }

    public function __default() {
    }

    public function playerprice() {
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

/*
    //updates the dynamic player-prices for the given matchround
    public function setPlayerDynamicPrice() {
        $matchround_id = $_REQUEST['matchround_id'];
        $this->post_matchround_id = $matchround_id;
        if(!$_POST['matchround_id']) {
            $this->administration_answer = "Please select a Matchround!";
            return;
        }

        //echo $matchround_id.'<br>';
        $actual_matchround = FfbMatchroundPeer::retrieveByPK($matchround_id);
        $actual_matchround_startdate = $actual_matchround->getMatchroundStartdate();
        $game_id = $this->session->game_id_admin;
        //echo 'game_id: '.$game_id.'<br>';

        //count all matches of this game
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $actual_matchround_startdate, Criteria::LESS_THAN);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::NOT_EQUAL);
        $criteria->add(FfbMatchPeer::MATCH_GUESTSCORE, -1, Criteria::NOT_EQUAL);
        $matches = FfbMatchPeer::doSelectJoinFfbMatchround($criteria);
        $num_matches = count($matches);

        if(!$num_matches) {
            $this->administration_answer = "No past matches available! The standard-price will be used!";
            return;
        }
        $answer = '<b>Anzahl der ber&uuml;cksichtigten Matches:</b> '.$num_matches.'<br>';
        //echo 'Anzahl beruecksichtigter Matches: '.$num_matches.'<br>';

        //Effektivität berechnen für G/D/M/S
        $effectivity = array();
        $effectivity['g'] = $this->calculatePositionEffectivity('g', $actual_matchround_startdate);
        $effectivity['d'] = $this->calculatePositionEffectivity('d', $actual_matchround_startdate);
        $effectivity['m'] = $this->calculatePositionEffectivity('m', $actual_matchround_startdate);
        $effectivity['s'] = $this->calculatePositionEffectivity('s', $actual_matchround_startdate);

        $answer .= '<b>Average played Minutes:</b><br>';
        $answer .= 'Goalie: '.round($effectivity['g']['av_minutes'], 2).' min<br>';
        $answer .= 'Defence: '.round($effectivity['d']['av_minutes'], 2).' min<br>';
        $answer .= 'Midfield: '.round($effectivity['m']['av_minutes'], 2).' min<br>';
        $answer .= 'Striker: '.round($effectivity['s']['av_minutes'], 2).' min<br>';
        $answer .= '<b>Average Effectivity:</b><br>';
        $answer .= 'Goalie: '.round($effectivity['g']['av_effectivity'], 2).'<br>';
        $answer .= 'Defence: '.round($effectivity['d']['av_effectivity'], 2).'<br>';
        $answer .= 'Midfield: '.round($effectivity['m']['av_effectivity'], 2).'<br>';
        $answer .= 'Striker: '.round($effectivity['s']['av_effectivity'], 2).'<br>';

        //echo 'G AV Minutes: '.round($effectivity['g']['av_minutes'], 2).'<br>';
        //echo 'D AV Minutes: '.round($effectivity['d']['av_minutes'], 2).'<br>';
        //echo 'M AV Minutes: '.round($effectivity['m']['av_minutes'], 2).'<br>';
        //echo 'S AV Minutes: '.round($effectivity['s']['av_minutes'], 2).'<br>';
        //echo 'G AV: '.round($effectivity['g']['av_effectivity'], 2).'<br>';
        //echo 'D AV: '.round($effectivity['d']['av_effectivity'], 2).'<br>';
        //echo 'M AV: '.round($effectivity['m']['av_effectivity'], 2).'<br>';
        //echo 'S AV: '.round($effectivity['s']['av_effectivity'], 2).'<br>';

        $team_array = array();
        foreach($matches as $item) {
            $home_team_id = $item->getMatchHometeamId();
            $guest_team_id = $item->getMatchGuestteamId();
            $team_array[$home_team_id] = 1;
            $team_array[$guest_team_id] = 1;
        }

        foreach($team_array as $team_id=>$value) {
            if($value == 1) {
                $this->calculateDynamicPriceForTeam($team_id, $effectivity, $actual_matchround_startdate);
            }

        }

        //echo 'Num Teams: '.count($team_array).'<br>';
        //echo '>1: '.$this->counts['plus'].'<br>';
        //echo '<1: '.$this->counts['minus'].'<br>';

        $answer .= '<br><b>Dynamic Player Prices updated!<br>';
        $this->administration_answer = $answer;
        //exit();
    }

    //calculate the average effectivity for the given position
    private function calculatePositionEffectivity($position, $actual_matchround_startdate) {
        $game_id = $this->session->game_id_admin;
        $eff_array = array();
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $actual_matchround_startdate, Criteria::LESS_THAN);
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $position);
        $playerstats = FfbPlayerstatsPeer::doSelectJoinAll($criteria);
        $sum_points = 0;
        $sum_minutes = 0;
        $num_players = count($playerstats);

        if($playerstats) {
            foreach($playerstats as $item) {
                $player = $item->getFfbPlayerteam()->getFfbPlayer();
                $player_name = $player->getPlayerFname().' '.$player->getPlayerLname();
                $sum_points += $item->getPlayerstatsScore();
                $sum_minutes += $item->getPlayerstatsMinutes();
            }
            $av_minutes = $sum_minutes/$num_players;
            $eff_array['av_minutes'] = $av_minutes;

            $eff_array['av_effectivity'] = $sum_points/($sum_minutes)*$av_minutes; //Effektivitäts-Methode
            //$eff_array['av_effectivity'] = ($sum_points/(count($playerstats)*90))*100; //Punkte-Methode
        } else {
            $eff_array['av_effectivity'] = 0;
        }

        return $eff_array;
    }

    private function calculateDynamicPriceForTeam($team_id, $effectivity, $actual_matchround_startdate) {
        $game_id = $this->session->game_id_admin;

        //Anzahl der gespielten Matches des Teams berechnen
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $actual_matchround_startdate, Criteria::LESS_THAN);
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::NOT_EQUAL);
        $criteria->add(FfbMatchPeer::MATCH_GUESTSCORE, -1, Criteria::NOT_EQUAL);
        $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $team_id);
        $c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $team_id));
        $criteria->add($c1);
        $num_matches = FfbMatchPeer::doCountJoinFfbMatchround($criteria);

        //echo 'Team '.$team_id.': '.$num_matches.'<br>';

        //alle AKTIVEN Spieler des Teams aus der DB laden
        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);

        //für vergangene Ligen sollte man diese Zeile auskommentieren, da sonst
        //Spieler die jetzt inaktiv sind nicht berücksichtigt werden
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);

        $playerteam = FfbPlayerteamPeer::doSelect($criteria);

        //für jeden Spieler des Teams:
        foreach($playerteam as $item) {
            //Playerstats für den Spieler für diese Liga aus DB holen
			$criteria = new Criteria();

            //$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $item->getPlayerteamId());
            //$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
            //$criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $actual_matchround_startdate, Criteria::LESS_THAN);
            //$playerstats = FfbPlayerstatsPeer::doSelectJoinFfbMatchround($criteria);

			//new
			$ffb_player = $item->getFfbPlayer();
            $criteria = new Criteria();
			$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_ID);
	        $ffb_playerteams = $ffb_player->getFfbPlayerteams();
	        $pt_ids = array();
	        unset($pt_ids);
	        foreach($ffb_playerteams as $ffbpt) {
	        	$pt_ids[] = $ffbpt->getPlayerteamId();
			}
			$criteria = new Criteria();
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $pt_ids, Criteria::IN);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $actual_matchround_startdate, Criteria::LESS_THAN);
            $playerstats = FfbPlayerstatsPeer::doSelectJoinFfbMatchround($criteria);
			// *****

            $player_position = $item->getPlayerteamPlayerPosition();
            $player_constant_price = $item->getPlayerteamPlayerPrice();
            $playerteam_id = $item->getPlayerteamId();
            $player_name = $item->getFfbPlayer()->getPlayerFname().' '.$item->getFfbPlayer()->getPlayerLname();

            //Score und gespielte Minuten summieren
            $player_score = 0;
            $player_minutes = 0;
            foreach($playerstats as $stat) {
                $player_score += $stat->getPlayerstatsScore();
                $player_minutes += $stat->getPlayerstatsMinutes();
            }

            //Durchschnitt berechnen und Liga-Vergleichswerte aus Array laden
            $av_minutes = $player_minutes/$num_matches; //Spieler-Durchschnitt der Spieldauer (Minuten/Match gespielt)
            $av_minutes_position = $effectivity[$player_position]['av_minutes']; //Liga-Durchschnitt der Spieldauer der Position (Minuten/Match gespielt)
            $perc_minutes = $av_minutes/$av_minutes_position*100; //wieviel Prozent des Liga-Durchschnitts hat Spieler gespielt? (% des Durchschnitts gespielt)
            $av_effectivity_position = $effectivity[$player_position]['av_effectivity']; //Liga-Durchschnitt der Effektivität der Position

            //Effektivität berechnen
            //Effektivitäts-Methode
            if($player_minutes >= 1) {
                //Punkte/Minute * Prozent des Ligadurchschnitts
                $player_effectivity = $player_score/$player_minutes*$perc_minutes;
            } else {
                $player_effectivity = 0;
            }

            //Punkte-Methode
            //if($num_matches > 0) {
            //    $player_effectivity = $player_score/$num_matches;
            //} else {
            //    $player_effectivity = 0;
            //}


            //Wieviel % der Durchschnitts-Effektivität hat der Spieler erreicht?
            if($av_effectivity_position > 0) {
                $perc_effectivity = $player_effectivity/$av_effectivity_position;
            } else {
                $perc_effectivity = -1;
            }
            //maximal Preisspanne aus Konstante holen
            $price_margin = $this->dynamic_price_settings['margin'];

            // count over/under 1
            if($perc_effectivity != 0) {
                if($perc_effectivity > 1) {
                    $this->counts['plus']++;
                } else {
                    $this->counts['minus']++;
                }
            }


            //Preisdifferenz berechnen
            if($perc_effectivity > 2) {
                $price_diff = $price_margin;
            } else if($perc_effectivity < 0) {
                $price_diff = $price_margin * -1;
            } else {
                if($perc_effectivity >= 1) {
                    $price_diff = round(($perc_effectivity - 1) * $price_margin, 1);
                } elseif($perc_effectivity < 1) {
                    $price_diff = round((1 - $perc_effectivity) * $price_margin, 1) * -1;
                }
            }

            //Preis berechnen
            $player_dynamic_price = $player_constant_price + $price_diff;
            if($player_dynamic_price <= 0) {
                $player_dynamic_price = 0.1;
            }

            //Daten in DB schreiben
            $matchround_id = $_REQUEST['matchround_id'];
            $criteria = new Criteria();
            $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
            $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
            $exist_price_item = FfbPlayerpricePeer::doSelect($criteria);
            if(!$exist_price_item) {
                $price_item = new FfbPlayerprice();
            } else {
                $price_item = $exist_price_item[0];
            }
            $price_item->setPlayerpricePlayerteamId($playerteam_id);
            $price_item->setPlayerpriceMatchroundId($matchround_id);
            $price_item->setPlayerpricePrice($player_dynamic_price);
            $price_item->setPlayerpricePlayerPower($player_effectivity);
            $price_item->setPlayerpriceAvPower($av_effectivity_position);
            $price_item->save();

            //echo $player_name.':<br><b>Preis: '.$player_dynamic_price.'</b> ('.$price_diff.' Credits)'.'<br><br>';
        }
    }

    public function setPlayerDynamicPrice_v2() {
    	$answer = '';
    	$matchround_id = $_REQUEST['matchround_id'];
        $this->post_matchround_id = $matchround_id;
        if(!$_REQUEST['matchround_id']) {
            $this->administration_answer = "Please select a Matchround!";
            return;
        }
		//$matchround_id = 65; //WM Gruppenphase 1
		require_once('playerRanking.php');
		$pr = new playerRanking();

		$teams = $this->getTeamListForRound($matchround_id);
		$answer .= 'number of teams: '.count($teams).'<br>';
		//exit();

		foreach($teams as $team) {
			$answer .= '**** TEAM '.$team['team_name'].'/'.$team['team_id'].' ****<br>';
			$team_id = $team['team_id'];
			$criteria = new Criteria();
			$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
			$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
			$pts = FfbPlayerteamPeer::doSelect($criteria);

			foreach($pts as $pt) {
				$playerteam_id = $pt->getPlayerteamId();
				$player_price = $pt->getPlayerteamPlayerPrice();
				$grade = $pr->calculatePlayerGrade_v2($playerteam_id);
				$trend = $grade['player_trend'];
				$power = $grade['player_grade'];
				if($trend<0) {
					$trend_adj = (100-(-1*$trend))/2;
				} elseif($trend == 0) {
					$trend_adj = 50;
				} else {
					$trend_adj = 50+($trend/2);
				}

				$price_base = (3*$power+$trend_adj)/4;

				if($power == 0) {
					$price_perc = -100;
				} elseif($price_base >= 50) {
					$price_perc = ($price_base - 50)/50*100;
				} elseif($price_base < 50) {
					$price_perc = ((50 - $price_base)/50*100) * -1;
				}

				$price_adj = ($price_perc/100)*2;
				$dyn_price = $player_price + round($price_adj, 1);
				if($dyn_price <= 0) {
					$dyn_price = 0.1;
				}

				$criteria = new Criteria();
				$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
				$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
				$criteria->setLimit(1);
				$pps = FfbPlayerpricePeer::doSelect($criteria);
				if($pps) {
					$pp = $pps[0];
				} else {
					$pp = new FfbPlayerprice();
					$pp->setPlayerpricePlayerteamId($playerteam_id);
					$pp->setPlayerpriceMatchroundId($matchround_id);
				}
				$pp->setPlayerpricePrice($dyn_price);
				$pp->setPlayerpricePlayerPower(1);
				$pp->setPlayerpriceAvPower(1);
				$pp->save();

				//$answer .= $pt->getFfbPlayer()->getPlayerFname().' '.$pt->getFfbPlayer()->getPlayerLname().' '.$price_adj.'/'.$dyn_price.'<br>';
			}
		}

		$answer .= '<br><b>Dynamic Player Prices V2 updated!</b><br>';
        $this->administration_answer = $answer;
	}

	public function getTeamListForRound($matchround_id) {
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $matchround_id);
        $matches = FfbMatchPeer::doSelect($criteria);
        $group_array = array();
        $teams = array();
        $i=0;
        foreach($matches as $match) {
			$hometeam = $match->getFfbTeamRelatedByMatchHometeamId();
			$teamfids = $hometeam->getFfbTeamfids();
			if(!$group_array[$hometeam->getTeamId()]) {
				$group_array[$hometeam->getTeamId()] = 1;
				$teams[$i]['team_id'] = $hometeam->getTeamId();
				$teams[$i]['team_name'] = $hometeam->getTeamName();
                $i++;
			}
			$guestteam = $match->getFfbTeamRelatedByMatchGuestteamId();
			$teamfids = $guestteam->getFfbTeamfids();
			if(!$group_array[$guestteam->getTeamId()]) {
				$group_array[$guestteam->getTeamId()] = 1;
				$teams[$i]['team_id'] = $guestteam->getTeamId();
				$teams[$i]['team_name'] = $guestteam->getTeamName();
                $i++;
			}
		}

		return $teams;
    }
*/

}

?>
