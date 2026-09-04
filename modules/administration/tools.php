<?php

/**
 * ADMIN - TOOLS-Klasse
 * testing new fancy stuff
 *
 * @author Gritschacher Tobias
 * @copyright 05/2014
 * @version 0.1
 *
 */

class tools extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

	public function calculateTeamPriceBasedOnELO() {
        //EM 2016
		$teams = array(11,13,37,9,48,44,55,15,10,3,4,43,2,56,16,7,64,17,51,5,1,36,8,71);
        //WM 2014
		//$teams = array(2,3,5,8,9,11,12,14,15,16,51,52,55,73,76,77,78,81,83,93,94,95,96,99,100,101,103,104,105,121,150,151);
		$teamPrices = $this->getTeamPrices($teams, 13, 6);
		foreach($teamPrices as $teamId => $teamPrice){
			print($teamId . ': ' . $teamPrice . "\n");
			//$this->updatePriceForTeamAndPlayers($teamId, $teamPrice);
		}
		die();
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

    //print number of lineups for each team in a given matchround (added for EM2016)
    public function getLineupCountForMatchround() {
        if($_GET['mrid']){
            $matchround_id = $_GET['mrid'];
        } else {
            die("no mrid given");
        }

        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
        $userteams = FfbUserteamPeer::doSelect($criteria);

        $teamscount = array();
        foreach($userteams as $ut) {
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId1())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId2())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId3())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId4())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId5())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId6())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId7())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId8())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId9())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId10())]++;
            $teamscount[$this->getTeamForPlayerteamId($ut->getUserteamPlayerId11())]++;
        }

        arsort($teamscount);

        foreach($teamscount as $team => $count) {
            echo "$team: $count<br>";
        }

        die();
    }

    private function getTeamForPlayerteamId($playerteam_id) {
        $team_name = utf8_decode(FfbPlayerteamPeer::retrieveByPK($playerteam_id)->getFfbTeam()->getTeamName());

        return $team_name;
    }

	private function updatePriceForTeamAndPlayers($teamId, $price) {
		$team = FfbTeamPeer::retrieveByPK($teamId);
		$team->setTeamAvgPrice($price);
		$pts = $team->getFfbPlayerteams();
		foreach($pts as $pt) {
			$pt->setPlayerteamPlayerPrice($price);
			//$pt->save();
		}
		//$team->save();
	}

	private function getEloRatingsForTeamList($teamIdList) {
		require_once 'ELORating.php';
		$elo = new ELORating($this->config->ffb_elo_url);
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
			} else {
				print("not found: $teamId\n");
			}
		}
		array_multisort($elos , SORT_ASC, $teamEloRatings);
		return $teamEloRatings;
	}

}