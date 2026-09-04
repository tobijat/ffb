<?php

/**
 * FFB-Module - BESTTEAM-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 10/2009
 * @version 0.1
 *
 */

class bestteam extends FFB_Auth_User {

	private $options;

    public function __construct() {
        parent::__construct();
    	$this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    	/*
		$user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    	*/
        $this->bestteam();
    }

	public function bestteam() {
        $this->htmlFile = 'bestteam.php';
		$this->htmlTitle = 'Top / Flop Teams';
        require_once('comments.php');
        comments::loadInto($this, 'bestteam', null, DEFAULT_COMMENT_NUMBER, false);
	}

	public function getBestTeam() {
		$matchround_id = $_REQUEST['matchround_id'];
		$type = $_REQUEST['type'];
		$systems = array();
		$systems[0] = array(1,3,4,3);
		$systems[1] = array(1,3,5,2);
		$systems[2] = array(1,4,3,3);
		$systems[3] = array(1,4,4,2);
		$systems[4] = array(1,4,5,1);
		$systems[5] = array(1,5,3,2);
		$systems[6] = array(1,5,4,1);
		$pm = $this->options->options_game_pointsmode;
		$matchround = FfbMatchroundPeer::retrieveByPK($matchround_id);
		$criteria = new Criteria();
		$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
		$playerprice = FfbPlayerpricePeer::doSelect($criteria);

		$top_of_round = array();
		$team_score_array = array();
		$team_price_array = array();
		$i=0;
		if($type == 'top') {
			$team_score = -100000;
			$team_price = 0;
		} elseif($type == 'flop') {
			$team_score = 100000;
			$team_price = 0;
		}
		foreach($systems as $system) {
			$top_goalie = $this->getTopOfRound($type, $pm, $playerprice, $matchround_id, 'g', $system[0]);
			$top_defence = $this->getTopOfRound($type, $pm, $playerprice, $matchround_id, 'd', $system[1]);
			$top_midfield = $this->getTopOfRound($type, $pm, $playerprice, $matchround_id, 'm', $system[2]);
			$top_striker = $this->getTopOfRound($type, $pm, $playerprice, $matchround_id, 's', $system[3]);
			$team_score_array[$i] = $top_goalie['sum_score']+$top_defence['sum_score']+$top_midfield['sum_score']+$top_striker['sum_score'];
			$team_price_array[$i] = $top_goalie['sum_price']+$top_defence['sum_price']+$top_midfield['sum_price']+$top_striker['sum_price'];
		//	echo 'index: '.$i.' team_score: '.$team_score_array[$i].'<br>';
			if($type == 'top') {
				if($team_score_array[$i] >= $team_score) {
					//echo $team_score_array[$i].'>'.$team_score.'<br>';
					$top_of_round = array_merge($top_goalie, $top_defence, $top_midfield, $top_striker);
					$team_score = $team_score_array[$i];
					$team_price = $team_price_array[$i];
				}
			} elseif($type == 'flop') {
				if($team_score_array[$i] < $team_score) {
					//echo $team_score_array[$i].'<'.$team_score.'<br>';
					$top_of_round = array_merge($top_goalie, $top_defence, $top_midfield, $top_striker);
					$team_score = $team_score_array[$i];
					$team_price = $team_price_array[$i];
				}
			}

			$i++;
		}
		$userteams = array();
		$userteams['userteam_score'] = $team_score;
		$userteams['userteam_price'] = $team_price;
		$this->players = $top_of_round;
		$this->userteams = $userteams;
		//exit();
	}

	private function getTopOfRound($type, $pm, $playerprice, $matchround_id, $position, $limit) {
		$pm = $this->options->options_game_pointsmode;
		//echo $matchround_id.'<br>';
		$criteria = new Criteria();
		$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
		$playerprice = FfbPlayerpricePeer::doSelect($criteria);
		if($pm == 'old' || !$playerprice) {
			//echo 'old<br>';
			$criteria = new Criteria();
			$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
			$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $position);
			if($type == 'top') {
				$criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
				$criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
			} elseif($type == 'flop') {
				$criteria->addAscendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
				$criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
			}

			$criteria->setLimit($limit);
			$playerstats_obj = FfbPlayerstatsPeer::doSelectJoinFfbPlayerteam($criteria);
			//print_r($playerstats_obj);
		} else {
			if($type == 'top') {
			$sql = "SELECT ffb_playerstats.playerstats_id from ffb_playerstats, ffb_playerprice, ffb_playerteam WHERE
                    ffb_playerstats.playerstats_matchround_id='$matchround_id' AND
                    ffb_playerstats.playerstats_playerteam_id=ffb_playerprice.playerprice_playerteam_id AND
                    ffb_playerprice.playerprice_matchround_id='$matchround_id' AND
                    ffb_playerteam.playerteam_id = ffb_playerstats.playerstats_playerteam_id AND
                    ffb_playerteam.playerteam_player_position='$position'
                    order by ffb_playerstats.playerstats_score DESC, ffb_playerprice.playerprice_price ASC
                    LIMIT $limit";
			} elseif($type == 'flop') {
				$sql = "SELECT ffb_playerstats.playerstats_id from ffb_playerstats, ffb_playerprice, ffb_playerteam WHERE
                    ffb_playerstats.playerstats_matchround_id='$matchround_id' AND
                    ffb_playerstats.playerstats_playerteam_id=ffb_playerprice.playerprice_playerteam_id AND
                    ffb_playerprice.playerprice_matchround_id='$matchround_id' AND
                    ffb_playerteam.playerteam_id = ffb_playerstats.playerstats_playerteam_id AND
                    ffb_playerteam.playerteam_player_position='$position'
                    order by ffb_playerstats.playerstats_score ASC, ffb_playerprice.playerprice_price DESC
                    LIMIT $limit";
			}
			$con = Propel::getConnection('d00817fb');
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$playerstats_obj = FfbPlayerteamPeer::populateObjects($stmt);
		}
		$top_of_round = array();
		if($playerstats_obj) {
			$i=0;
			$sum_score = 0;
			$sum_price = 0;
			foreach($playerstats_obj as $item) {
				if($pm == 'old' || !$playerprice) {
					$playerstats_id = $item->getPlayerstatsId();
				} else {
					$playerstats_id = $item->getPlayerteamId();
				}
				$playerstats = FfbPlayerstatsPeer::retrieveByPK($playerstats_id);
				$top_of_round[$i]['player_fname'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerFname();
				$top_of_round[$i]['player_lname'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname();
				$top_of_round[$i]['player_nationality'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerNationality();
				if($playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatus()) {
					$top_of_round[$i]['player_status'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatus();
				} else {
					$top_of_round[$i]['player_status'] = 0;
				}
				if($playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatusDescription()) {
					$top_of_round[$i]['player_status_description'] = $playerstats->getFfbPlayerteam()->getFfbPlayer()->getPlayerStatusDescription();
				} else {
					$top_of_round[$i]['player_status_description'] = 0;
				}
				$top_of_round[$i]['playerteam_id'] = $playerstats->getPlayerstatsPlayerteamId();
				$top_of_round[$i]['playerteam_id'] = $playerstats->getPlayerstatsPlayerteamId();
				$top_of_round[$i]['playerteam_team_id'] = $playerstats->getFfbPlayerteam()->getPlayerteamTeamId();
				$top_of_round[$i]['playerteam_team'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamName();
				$top_of_round[$i]['playerteam_team_nationality'] = $playerstats->getFfbPlayerteam()->getFfbTeam()->getTeamNationality();
				$top_of_round[$i]['playerteam_player_position'] = $playerstats->getFfbPlayerteam()->getPlayerteamPlayerPosition();
				$pp_crit = new Criteria();
				$pp_crit->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
				$pp_crit->setLimit(1);
				$pp_items = $playerstats->getFfbPlayerteam()->getFfbPlayerprices($pp_crit);
				if($pm == 'old' || count($pp_items) < 1) {
					$top_of_round[$i]['playerteam_player_price'] = $playerstats->getFfbPlayerteam()->getPlayerteamPlayerPrice();
				} else {
					$top_of_round[$i]['playerteam_player_price'] = $pp_items[0]->getPlayerpricePrice();
				}
				if($playerstats->getFfbPlayerteam()->getPlayerteamStatus()) {
					$top_of_round[$i]['playerteam_status'] = $playerstats->getFfbPlayerteam()->getPlayerteamStatus();
				} else {
					$top_of_round[$i]['playerteam_status'] = 0;
				}
				$top_of_round[$i]['playerstats_score'] = $playerstats->getPlayerstatsScore();

				$sum_score += $top_of_round[$i]['playerstats_score'];
				$sum_price += $top_of_round[$i]['playerteam_player_price'];
				$i++;
			}
			$top_of_round['sum_score'] = $sum_score;
			$top_of_round['sum_price'] = $sum_price;
		} else {
			$top_of_round = array('sum_score' => 0, 'sum_price' => 0);
		}
		return $top_of_round;
	}

	public function getTopteam() {
		$time = time();
		$matchround_id = $_REQUEST['matchround_id'];
		$teams = $this->getTeamList($matchround_id);
		echo 'time getTeamList: '.(time()-$time).' Sec.<br>';
		$time = time();
		$num = 2;
		$players = array();
		$scores = array();
		$tops = array();
		$tp = array();
		if($teams) {
			foreach($teams as $team) {
				$team_id = $team->getTeamId();
				unset($tp);
				$time = time();
				$tp = $this->getTopplayers_v2($team_id, $matchround_id, $num);
				echo 'time getTopplayers '.$team_id.': '.(time()-$time).' Sec.<br>';
				/*
				foreach($tp as $item) {
					$tops[] = $item;
					$players[] = $item['player'];
					$scores[] = $item['score'];
				}
				*/
				//array_merge($players, $tp);
				//echo 'num: '.count($this->getTopplayers($team->getTeamId(), $matchround_id, $num)).'<br>';
			}
		}
		//echo 'time getTopplayers: '.(time()-$time).' Sec.<br>';

		exit();

		$time = time();
		array_multisort($scores, SORT_DESC, $tops);
		foreach($tops as $top) {
			echo $top['score'].'/'.$top['player']->getFfbPlayer()->getPlayerLname().'<br>';
		}
		echo 'num: '.count($tops).'<br>';
		echo 'time array_sort: '.(time()-$time).' Sec.<br>';

		exit();
	}

	public function testTopplayers() {
		$query = "SELECT ffb_playerteam.playerteam_id as ptid, ffb_player.player_fname as name, ffb_player.player_lname as lname, (ffb_playerstats.playerstats_score/ffb_playerprice.playerprice_price) as power, MAX(ffb_playerstats.playerstats_score) as score, ffb_playerprice.playerprice_price AS price
				  FROM
 				  ffb_player, ffb_playerstats, ffb_playerteam, ffb_matchround, ffb_playerprice
				  WHERE
 				  ffb_playerstats.playerstats_playerteam_id=ffb_playerteam.playerteam_id AND
 				  ffb_matchround.matchround_id=ffb_playerstats.playerstats_matchround_id AND
 				  ffb_playerprice.playerprice_playerteam_id=ffb_playerstats.playerstats_playerteam_id AND
 			  	  ffb_playerprice.playerprice_matchround_id=ffb_matchround.matchround_id AND
 				  ffb_matchround.matchround_id=132 AND
 				  ffb_playerteam.playerteam_player_id=ffb_player.player_id AND
 				  ffb_playerteam.playerteam_team_id=2
				  GROUP BY ffb_playerteam.playerteam_id ORDER BY score DESC, ffb_playerprice.playerprice_price ASC LIMIT 3";
		$result = $this->db->query($query);;
		while($row = $result->fetchrow()) {
			print_r($row);
			echo '<br><br>';
		}

		//echo $this->db;
		exit();
		$time = time();
		$pdata = $this->getTopplayers_v2(132, 2);
		echo 'time getTopplayers: '.(time()-$time).' Sec.<br>';

		foreach($pdata as $item) {
			echo $item['score'].'/'.$item['price'].'/'.$item['player']->getFfbPlayer()->getPlayerLname().'<br>';
		}

		exit();
	}

	public function getTopplayers_v2($matchround_id, $num) {
		$teams = $this->getTeamList($matchround_id);
		$topplayers = array();
		$topprices = array();
		$topscores = array();
		if($teams) {
			foreach($teams as $team) {
				$team_id = $team->getTeamId();

				$criteria = new Criteria();
				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);

				$players = FfbPlayerteamPeer::doSelect($criteria);
				$pdata = array();
				$prices = array();
				$scores = array();
				unset($scores);
				unset($prices);
				unset($pdata);
				$c1 = new Criteria();
				$c1->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
				$c1->setLimit(1);
				$c2 = new Criteria();
				$c2->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchround_id);
				$c2->setLimit(1);
				$i=0;
				foreach($players as $player) {
					$stats = $player->getFfbPlayerstatss($c1);
					$price = $player->getFfbPlayerprices($c2);

					if(count($stats) > 0 && count($price) > 0) {
						$pdata[$i]['player'] = $player;
						$pdata[$i]['score'] = $stats[0]->getPlayerstatsScore();
						$pdata[$i]['price'] = $price[0]->getPlayerpricePrice();
						$scores[] = $stats[0]->getPlayerstatsScore();
						$prices[] = $price[0]->getPlayerpricePrice();
						$i++;
					}
				}
				array_multisort($scores, SORT_DESC, $prices, SORT_ASC, $pdata);
				foreach(array_slice($pdata, 0, $num) as $new_tp) {
					$topplayers[] = $new_tp;
					$topprices[] = $new_tp['price'];
					$topscores[] = $new_tp['score'];
				}
				echo 'count tp: '.count($topplayers).'<br>';
			}
		}

		array_multisort($topscores, SORT_DESC, $topprices, SORT_ASC, $topplayers);

		return $topplayers;
	}

	public function getTopplayers($team_id, $matchround_id, $num) {
		$players = array();
		$criteria = new Criteria();

		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->addJoin(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);
		$criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
		$criteria->addAscendingOrderByColumn(FfbPlayerpricePeer::PLAYERPRICE_PRICE);
		$criteria->setLimit($num);
		$stats = FfbPlayerstatsPeer::doSelect($criteria);

		return $stats;

		/*
		$criteria->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID);
		$criteria->addJoin(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->addJoin(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_ID);
		$criteria->addJoin(FfbTeamPeer::TEAM_ID, FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID);
		$criteria->add(FfbTeamPeer::TEAM_ID, $team_id);
		$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCHROUND_ID, $matchround_id);

		$criteria->addDescendingOrderByColumn(FfbPlayerstatsPeer::PLAYERSTATS_SCORE);
		$criteria->addDescendingOrderByColumn(FfbPlayerpricePeer::PLAYERPRICE_PRICE);
		$criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);

		$criteria->addGroupByColumn(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$criteria->setLimit($num);


		$stats = FfbPlayerstatsPeer::doSelect($criteria);
		return $stats;
		*/

		$i=0;
		foreach($stats as $stat) {
			//echo $stat->getPlayerstatsScore().'/'.$stat->getFfbPlayerteam()->getFfbPlayer()->getPlayerLname().'<br>';
			$players[$i]['player'] = $stat->getFfbPlayerteam();
			$players[$i]['score'] = $stat->getPlayerstatsScore();
		}
/*
		$players = FfbPlayerteamPeer::doSelect($criteria);
		foreach($players as $player) {
			echo $player->getFfbPlayer()->getPlayerLname().'<br>';
		}
*/
		return $players;
	}

	public function getTeamList($matchround_id) {
		//$matchround_id = $_REQUEST['matchround_id'];
		$criteria = new Criteria();
		$criteria->addJoin(FfbMatchroundPeer::MATCHROUND_ID, FfbMatchPeer::MATCH_ROUND);
		//$criteria->addJoin(FfbMatchPeer::MATCH_HOMETEAM_ID, FfbTeamPeer::TEAM_ID);
		//$criteria->addJoin(FfbMatchPeer::MATCH_GUESTTEAM_ID, FfbTeamPeer::TEAM_ID);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $matchround_id);

		$matchs = FfbMatchPeer::doSelect($criteria);
		$teams = array();
		if($matchs) {
			foreach($matchs as $match) {
				$teams[] = $match->getFfbTeamRelatedByMatchHometeamId();
				$teams[] = $match->getFfbTeamRelatedByMatchGuestteamId();
			}
		}
		/*
		foreach($teams as $team) {
			echo $team->getTeamName().'<br>';
		}
		*/
		//$teams = FfbTeamPeer::doSelect($criteria);
		//echo 'num: '.count($teams);
		//exit();
		return $teams;
	}
}
?>