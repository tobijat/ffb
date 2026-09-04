<?php

/**
 * ADMIN - PLAYERPRICE2014-Klasse
 * dynamische Preisberechnung fuer Spieler v2014
 *
 * @author Gritschacher Tobias
 * @copyright 05/2014
 * @version 0.1
 *
 */

class playerprice2014 extends FFB_Auth_AdminFfb {

    private $options;
	private $eloRating;

	const HISTORY_LENGTH = 10;

    public function __construct() {
        parent::__construct();
		require_once('ELORating.php');
		$this->htmlFile = 'playerprice.php';
        $this->options = new FFB_Options($this->session->game_id_admin);
		$this->eloRating = new ELORating($this->config->ffb_elo_url);
		$this->matchrounds = $this->getMatchrounds();
    }

	public function __default() {
    }

    public function playerprice2014() {
		$this->htmlFile = 'playerprice.php';
    }

	public function calculatePlayerPricesForMatchround() {
		if(!$_POST['matchround_id']) {
            $this->administration_answer = "Please select a Matchround!";
            return;
        }
		if(!$_POST['price_margin']) {
            $this->administration_answer = "Please select Price Margin!";
            return;
        }
		$matchroundId = $_POST['matchround_id'];
		$priceMargin = $_POST['price_margin'];
		$this->post_matchround_id = $matchroundId;
		$this->post_price_margin = $priceMargin;
		$teamList = $this->getTeamListForMatchround($matchroundId);
		$answer = '';
		foreach($teamList as $teamId) {
			$playerPriceMargins = $this->calculatePlayerPriceMarginsForTeam($teamId, $priceMargin);
			$answer .= $this->updatePlayerPrices($playerPriceMargins, $matchroundId);
		}
		$this->administration_answer = $answer;
	}

	public function calculateELOTeamPricesForGame() {
		if(!$_POST['max_price']) {
            $this->administration_answer = "Please select max price!";
            return;
        }
		if(!$_POST['min_price']) {
            $this->administration_answer = "Please select min price!";
            return;
        }

		$maxPrice = intval($_POST['max_price']);
		$minPrice = intval($_POST['min_price']);

		if($maxPrice <= $minPrice) {
			$this->administration_answer = "Max price needs to be greater than min price!";
            return;
		}

		$gameTeams = $this->getTeamListForGame($this->session->game_id_admin);

		$teamPrices = $this->getTeamPrices($gameTeams, $maxPrice, $minPrice);
		$answer = '';
		foreach($teamPrices as $teamId => $teamPrice){
			$answer .= $this->updateBasePriceForTeamAndPlayers($teamId, $teamPrice);
		}

		$this->post_max_price = $maxPrice;
		$this->post_min_price = $minPrice;
		$this->administration_answer = $answer;
	}

	public function calculateELOTeamPricesForMatchround() {
		if(!$_POST['matchround_id']) {
            $this->administration_answer = "Please select a Matchround!";
            return;
        }
		if(!$_POST['max_price']) {
            $this->administration_answer = "Please select max price!";
            return;
        }
		if(!$_POST['min_price']) {
            $this->administration_answer = "Please select min price!";
            return;
        }

		$matchroundId = $_POST['matchround_id'];
		$maxPrice = intval($_POST['max_price']);
		$minPrice = intval($_POST['min_price']);

		if($maxPrice < $minPrice) {
			$this->administration_answer = "Max price needs to be greater than min price!";
            return;
		}

		$matchroundTeams = $this->getTeamListForMatchround($matchroundId);
		$teamPrices = $this->getTeamPrices($matchroundTeams, $maxPrice, $minPrice);
		$answer = '';
		foreach($teamPrices as $teamId => $teamPrice){
			$answer .= $this->updateBasePriceForTeamAndPlayers($teamId, $teamPrice);
		}

		$this->post_matchround_id = $matchroundId;
		$this->post_max_price = $maxPrice;
		$this->post_min_price = $minPrice;
		$this->administration_answer = $answer;
	}

	private function getMatchrounds() {
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
                $i++;
            }
        }
        return $matchrounds;
    }

	private function updatePlayerPrices($playerPriceMargins, $matchroundId) {
		$answer = '';
		foreach($playerPriceMargins as $id => $priceMargin) {
			$pt = FfbPlayerteamPeer::retrieveByPK($id);
			$basePrice = $pt->getPlayerteamPlayerPrice();
			$price = $basePrice+$priceMargin;
			$criteria = new Criteria();
			$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $id);
			$criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $matchroundId);

			$playerprice = FfbPlayerpricePeer::doSelectOne($criteria);

			if(!$playerprice) {
				$playerprice = new FfbPlayerprice();
				$playerprice->setPlayerpricePlayerteamId($id);
				$playerprice->setPlayerpriceMatchroundId($matchroundId);
			}

			$playerprice->setPlayerpricePrice($price);
			$playerprice->setPlayerpriceAvPower(1); //default, not used anymore
			$playerprice->setPlayerpricePlayerPower(1); //default, not used anymore
			$answer .= 'Price updated: ' . "$id: $price" . '<br>';
			$playerprice->save();
		}
		return $answer;
	}

	private function getTeamListForMatchround($matchroundId) {
		$criteria = new Criteria();
		$criteria->add(FfbMatchPeer::MATCH_ROUND, $matchroundId);
		$matches = FfbMatchPeer::doSelect($criteria);
		$teamList = array();
		foreach($matches as $match) {
			$teamList[] = $match->getMatchHometeamId();
			$teamList[] = $match->getMatchGuestteamId();
		}
		return array_unique($teamList);
	}

	private function getTeamListForGame($gameId) {
		$criteria = new Criteria();
		$criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $gameId);
		$matches = FfbMatchPeer::doSelect($criteria);
		$teamList = array();
		foreach($matches as $match) {
			$teamList[] = $match->getMatchHometeamId();
			$teamList[] = $match->getMatchGuestteamId();
		}
		return array_unique($teamList);
	}

	private function calculatePlayerPriceMarginsForTeam($teamId, $margin) {
		$lastMatches = $this->getLastMatches($teamId);
		$opponents = $this->getOpponents($teamId, $lastMatches);
		$teamsIdList = $this->getOpponentsIdList($opponents);
		$teamsIdList[] = $teamId;
		$teamPrices = $this->getTeamPrices($teamsIdList, 13, 3);
		$avgPositionPoints = array();
		$avgPositionPoints['g'] = $this->getAvgPositionPoints($lastMatches, 'g');
		$avgPositionPoints['d'] = $this->getAvgPositionPoints($lastMatches, 'd');
		$avgPositionPoints['m'] = $this->getAvgPositionPoints($lastMatches, 'm');
		$avgPositionPoints['s'] = $this->getAvgPositionPoints($lastMatches, 's');

		$players = $this->getActivePlayersForTeam($teamId);
		$playerPriceMargins = array();
		foreach($players as $player) {
			$mpSum = 0;
			foreach($lastMatches as $match) {
				$opponentId = $this->getOpponentId($match, $teamId);
				$opponentPrice = $teamPrices[$opponentId];
				$teamPrice = $teamPrices[$teamId];
				$priceDiff = $opponentPrice-$teamPrice;
				if($priceDiff < 0) {
					//own team was stronger
					$priceDiff = 1-(($priceDiff*-1)/10);
				} else {
					//opponent team was stronger (or equal)
					$priceDiff = 1+($priceDiff/10);
				}

				$criteria = new Criteria();
				$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match->getMatchId());
				$criteria->add(FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID, $player->getPlayerteamId());
				$playerstats = FfbPlayerstatsPeer::doSelect($criteria);
				$positionAvg = $avgPositionPoints[$player->getPlayerteamPlayerPosition()];
				if($playerstats) {
					//player has played in that match
					$score = $playerstats[0]->getPlayerstatsScore();
					$percentageScore = $score/$positionAvg-1;
					$mp = (10*$percentageScore)*$priceDiff;
					if($mp > 10) {
						$mp = 10;
					}
					if($mp < -10) {
						$mp = -10;
					}
				} else {
					//player has not played in that match
					$mp = 0;
				}
				$mpSum += $mp;
			}
			$playerStrength = $mpSum/count($lastMatches);
			if($playerStrength > $margin) {
				$playerStrength = $margin;
			}
			if($playerStrength < (-1*$margin)) {
				$playerStrength = (-1*$margin);
			}
			$playerPriceMargins[$player->getPlayerteamId()] = round($playerStrength, 1);
		}

		return $playerPriceMargins;
	}

	private function getOpponentId($match, $teamId)  {
		$hometeamId = $match->getMatchHometeamId();
		$guestteamId = $match->getMatchGuestteamId();
		if($teamId == $hometeamId)  {
			return $guestteamId;
		} else {
			return $hometeamId;
		}
	}

	private function getOpponentsIdList($opponents) {
		$list = array();
		foreach($opponents as $opponent) {
			$list[] = $opponent->getTeamId();
		}
		return $list;
	}

	private function getActivePlayersForTeam($teamId) {
		$criteria = new Criteria();
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $teamId);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
		return(FfbPlayerteamPeer::doSelect($criteria));
	}

	private function getAvgPositionPoints($matches, $position) {
		$criteria = new Criteria();
		$criteria->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerstatsPeer::PLAYERSTATS_PLAYERTEAM_ID);
		$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $position);
		$c1 = $criteria->getNewCriterion(0);
		foreach($matches as $match) {
			$c1->addOr($criteria->getNewCriterion(FfbPlayerstatsPeer::PLAYERSTATS_MATCH_ID, $match->getMatchId()));
		}
		$criteria->add($c1);
		$numResults = FfbPlayerstatsPeer::doCount($criteria);
		$criteria->addSelectColumn('SUM('.FfbPlayerstatsPeer::PLAYERSTATS_SCORE.')');
		$playerscore = FfbPlayerstatsPeer::doSelect($criteria);

		return($playerscore[0]->getPlayerstatsId()/$numResults);
	}

	private function getOpponents($teamId, $matches) {
		$opponents = array();
		if(!$matches) {
			return $opponents;
		}
		foreach($matches as $match) {
			if($match->getMatchHometeamId() == $teamId) {
				$opponents[$match->getMatchGuestteamId()] = $match->getFfbTeamRelatedByMatchGuestteamId();
			}
			if($match->getMatchGuestteamId() == $teamId) {
				$opponents[$match->getMatchHometeamId()] = $match->getFfbTeamRelatedByMatchHometeamId();
			}
		}

		return $opponents;
	}

	private function getLastMatches($teamId) {
		$criteria = new Criteria();
		$criteria->add(FfbMatchPeer::MATCH_MINUTES, 0, Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->setLimit(self::HISTORY_LENGTH);
		$c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_GUESTTEAM_ID, $teamId);
		$c1->addOr($criteria->getNewCriterion(FfbMatchPeer::MATCH_HOMETEAM_ID, $teamId));
		$criteria->add($c1);
		$lastMatches = FfbMatchPeer::doSelect($criteria);
		if($lastMatches) {
			return $lastMatches;
		} else {
			return array();
		}
	}

	public function getTeamPrices($teamIdList, $maxPrice, $minPrice) {
		$teams = $this->getEloRatingsForTeamList($teamIdList);
		$numTeams = count($teams);
		$priceDifference = $maxPrice-$minPrice;
		$priceStep = $priceDifference/$numTeams;
		$i = 0;
		$teamPrices = array();
		foreach($teams as $team) {
			$teamPrice = round($minPrice + ($i * $priceStep), 1);
			$teamId = $team['team_id'];
			$teamPrices[$teamId] = $teamPrice;

			$i++;
		}

		return $teamPrices;
	}

	private function getEloRatingsForTeamList($teamIdList) {
		$elo = $this->eloRating;
		$teamEloRatings = array();
		$elos = array();
		$i = 0;
		foreach($teamIdList as $teamId) {
			$teamEloRating = $elo->getELORatingForTeam($teamId);
			if( $teamEloRating !== null) {
				$teamEloRatings[$i]['team_id'] = $teamId;
				$teamEloRatings[$i]['elo_rating'] = $teamEloRating;
				$elos[] = $teamEloRating;
				$i++;
			}
		}
		array_multisort($elos , SORT_ASC, $teamEloRatings);
		return $teamEloRatings;
	}

	private function updateBasePriceForTeamAndPlayers($teamId, $price) {
		$team = FfbTeamPeer::retrieveByPK($teamId);
		$teamName = $team->getTeamName();
		$team->setTeamAvgPrice($price);
		$pts = $team->getFfbPlayerteams();
		$answer = '';
		foreach($pts as $pt) {
			$pt->setPlayerteamPlayerPrice($price);
			$pt->save();
		}
		$team->save();
		$answer .= 'Base price updated: ' . "$teamName: $price<br>";
		return $answer;
	}
}
?>
