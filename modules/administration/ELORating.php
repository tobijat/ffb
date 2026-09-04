<?php

/**
 * ADMIN - ELORAting-Class
 * handling ELO Ratings
 *
 * @author Gritschacher Tobias
 * @copyright 05/2014
 * @version 0.1
 *
 */

class ELORating {

	const TEAM_NAME_MAP_URL = "http://soccer.sportsfan.at/parserfiles/teams/teams.csv";

	private $ratings;
	private $teamNameMap;

    public function __construct($eloUrl) {
		$this->teamNameMap = $this->getTeamNameMap(self::TEAM_NAME_MAP_URL);
		$this->ratings = $this->getELORatingsFromUrl($eloUrl);
    }

	public function getELORatingForTeam($teamId) {
		if(array_key_exists($teamId, $this->ratings)) {
			return $this->ratings[$teamId];
		} else {
			return null;
		}
	}

	public function getELORatings() {
		return $this->ratings;
	}

	private function getELORatingsFromUrl($url) {
		$file = fopen( $url , "r" );
        $content = $this->normalizeString(stream_get_contents($file));

		$eloTableStartPattern = '<table cellspacing="0" border="border" bordercolor="white" rules="groups" frame="void">';
		$eloTableEndPattern = '</table>';
		$eloTableStartPos = strpos($content, $eloTableStartPattern)+strlen($eloTableStartPattern);
		$startPos = strpos($content, '<tr><td>', $eloTableStartPos);
		$endPos = strpos($content, $eloTableEndPattern, $eloTableStartPos);
		$eloTableHeadings = '<tr class="sh"><td rowspan="2" class="sh">rank</td><td rowspan="2" class="sh">team</td><td rowspan="2" class="sh">rating</td><td colspan="2" class="sh">highest</td><td colspan="2" class="th">1 yr change</td><td colspan="7" class="sh">matches</td><td colspan="2" class="sh">goals</td></tr><tr class="lh"><td class="lh">rank</td><td class="lh">rating</td><td class="lh">rank</td><td class="lh">rating</td><td class="lh">total</td><td class="lh">home</td><td class="lh">away</td><td class="lh">neutral</td><td class="lh">wins</td><td class="lh">losses</td><td class="lh">draws</td><td class="lh">for</td><td class="lh">against</td></tr>';

		$eloTable = substr($content, $startPos, $endPos-$startPos);
		$eloTable = str_replace($eloTableHeadings, '', $eloTable);

		$teams = explode('<tr><td>', $eloTable);
		$teams = array_filter($teams);

		$ratings = array();
		$teamNameMap = $this->teamNameMap;

		foreach($teams as $team) {
			//$ratings[md5($this->getTeamNameFromString($team))] = $this->getTeamEloFromString($team);
			$eloName = $this->getTeamNameFromString($team);
			$ratings[$teamNameMap[md5($eloName)]] = $this->getTeamEloFromString($team);
		}

		return $ratings;
	}

	private function getTeamNameMap($url) {
		//$url = "http://soccer.sportsfan.at/parserfiles/teams/teams_wm2014.csv";
		$file = fopen( $url , "r" );
        $content = stream_get_contents($file);
		$teams = array_filter(explode(';;', $content));

		$teamList = array();
		foreach($teams as $team) {
			$parts = explode(';', $team);
			$id = trim($parts[0]);
			$eloName = $parts[3];
			$teamList[md5($eloName)] = $id;
		}

		return $teamList;
	}

	private function getTeamNameFromString($string) {
		$parts = explode('<td>', $string);
		$name = substr($parts[1], strpos($parts[1], '">')+2, strpos($parts[1], '</a>')-(strpos($parts[1], '">')+2));
		return $name;
	}

	private function getTeamEloFromString($string) {
		$parts = explode('<td>', $string);
		$elo = substr($parts[2], 0, strpos($parts[2], '</td>'));
		return $elo;
	}

	private function normalizeString($string){
		$string = str_replace("\t", "", trim($string));
		$string = str_replace("\r", "", trim($string));
		$string = str_replace("\n", "", trim($string));
		return $string;
	}
}
