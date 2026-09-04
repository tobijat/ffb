<?php

/**
 * ADMIN - PLAYERMANAGEMENT-Klasse;
 * Playermanagement
 *
 * @author Gritschacher Tobias
 * @copyright 10/2008
 * @version 0.1
 *
 */

class fifa_playermanagement extends FFB_Auth_AdminFfb {
    private $options;

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'fifa_playermanagement.php';
        $this->options = new FFB_Options($this->session->game_id_admin);
    }

    public function __default() {
        if($_POST) {
            $this->test_fifa_pm($_POST['fifa_url']);
        }
    }

    public function test_fifa_pm_xml() {
        $url = 'http://de.fifa.com/worldcup/preliminaries/europe/matches/round=253455/match=300110249/index.html';
        $this->parseFifaProfile($url);
    }

    public function test_foe_pm() {
        $url = 'http://soccer.sportsfan.at/resource/foe_matchdata/ul/2009/19/sta_sac.htm';
        //$url = 'http://www.tobijat.at/foe_html/379402779304830775_100071-489819850739622457.htm';
        $content = $this->parseFoeProfile($url);
        if($content) {
            echo 'OK<br><br>---------------<br>';
            echo $content;
            echo '<br>-----------------------<br>';
        } else {
            echo 'FAILED<br>';
        }
        exit();
    }

    public function getFifaMatchData() {
        $this->parseFifaProfile($_POST['url']);
    }

    public function getFoeMatchData() {
        $this->parseFoeProfile($_POST['url']);
    }

	public function testGetWfMatchData() {
        //$this->parseWFProfile("http://www.weltfussball.at/spielbericht/wm-quali-europa-2012-2013-gruppe-a-kroatien-mazedonien/");
        //$this->parseWFProfile("http://www.weltfussball.at/spielbericht/wm-quali-europa-2012-2013-gruppe-a-wales-belgien/");
        //$this->parseWFProfile("http://www.weltfussball.at/spielbericht/wm-quali-asien-2011-2013-1-runde-laos-kambodscha/");
        $this->parseWFProfile("http://www.weltfussball.at/spielbericht/wm-quali-asien-2011-2013-relegation-usbekistan-jordanien/");
        //$this->parseWFProfile("http://www.weltfussball.at/spielbericht/wm-quali-europa-2012-2013-gruppe-b-bulgarien-italien/");
    }

    public function getWfMatchData() {
        //$this->parseWFProfile($_POST['url'], $_POST['match_minutes']);
        $this->parseWFProfile($_POST['url']);
    }

    public function testFOEProfile() {
        $this->parseFoeProfile('http://soccer.sportsfan.at/resource/foe_matchdata/ul/2009/22/irs_rot.htm');
    }

    public function test_fifa_pm($url) {
        echo $url.'<br>';
        $content = $this->parseFifaProfile($url);
        if($content) {
            echo 'OK<br>---------------<br>';
            echo $content;
            echo '<br>-----------------------<br>';
        } else {
            echo 'FAILED<br>';
        }
    }

    private function parseFoeProfile($dateiname) {
        $datei = fopen( $dateiname , "r" );

        //check if Profile-Page has loaded successfully
        if(!$datei)
            return false;

        $inhalt = stream_get_contents($datei);
        //echo 'string: '.$inhalt.'<br>';

        $tmp_string = substr($inhalt, strpos($inhalt, 'Tore')+4);
        $home_string = substr($tmp_string, 0, strpos($tmp_string, '</table>'));
        $tmp_string = substr($tmp_string, strpos($tmp_string, 'Tore')+4);
        $guest_string = substr($tmp_string, 0, strpos($tmp_string, '</table>'));
        $tmp_string = substr($tmp_string, strpos($tmp_string, "<td valign='top'>")+17);
        $home_string_sub = substr($tmp_string, 0, strpos($tmp_string, "<td valign='top'>"));
        $tmp_string = substr($tmp_string, strpos($tmp_string, "<td valign='top'>")+17);
        $guest_string_sub = substr($tmp_string, 0, strpos($tmp_string, "</table>"));

        $home_players = explode('<tr class=', $home_string);
        $guest_players = explode('<tr class=', $guest_string);
        $home_players_sub = explode('<tr class=', $home_string_sub);
        $guest_players_sub = explode('<tr class=', $guest_string_sub);

        //echo 'hp: '.count($home_players).'<br>';
        //echo 'hp: '.$home_players[0].'<br>';

        $playerlist_home = $this->parseFoePlayer($home_players);
        $playerlist_guest = $this->parseFoePlayer($guest_players);
        $playerlist_home_sub = $this->parseFoePlayer($home_players_sub);
        $playerlist_guest_sub = $this->parseFoePlayer($guest_players_sub);

        //Spieler und Auswechselspieler zusammenfügen
        if($playerlist_home_sub) {
            for($i=0;$i<count($playerlist_home_sub);$i++) {
                if($playerlist_home_sub[$i]['player_change_in']) {
                    array_push($playerlist_home, $playerlist_home_sub[$i]);
                }
            }
        }
        if($playerlist_guest_sub) {
            for($i=0;$i<count($playerlist_guest_sub);$i++) {
                if($playerlist_guest_sub[$i]['player_change_in']) {
                    array_push($playerlist_guest, $playerlist_guest_sub[$i]);
                }
            }
        }

        $this->playerlist_home = $playerlist_home;
        $this->playerlist_guest = $playerlist_guest;
/*
        $this->printPlayers($playerlist_home);
        $this->printPlayers($playerlist_guest);
        exit();
*/
    }

    private function parseFifaProfile($dateiname) {
/*
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dateiname);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $datei = curl_exec($ch);
*/
        $datei = @fopen( $dateiname , "r" );

        //check if Profile-Page has loaded successfully
        if(!$datei)
            return false;

        $inhalt = stream_get_contents($datei);

        $players_code = array();
        $players = array();
        $temp_string = substr($inhalt, strpos($inhalt, '<div class="sep">')+17);
        $home_string = substr($temp_string, 0, strpos($temp_string, '<div class="away">'));
        $temp_string = substr($temp_string, strpos($temp_string, '<div class="away">')+18);
        $guest_string = substr($temp_string, 0, strpos($temp_string, '<div class="sep">'));
        $temp_string = substr($temp_string, strpos($temp_string, '<div class="home">')+18);
        $home_string_sub = substr($temp_string, 0, strpos($temp_string, '<div class="away">'));
        $temp_string = substr($temp_string, strpos($temp_string, '<div class="away">')+18);
        $guest_string_sub = substr($temp_string, 0, strpos($temp_string, '<div class="sep">'));
        $home_players = explode('<li>', $home_string);
        $home_players_sub = explode('<li>', $home_string_sub);
        $guest_players = explode('<li>', $guest_string);
        $guest_players_sub = explode('<li>', $guest_string_sub);
        //Karten-Sektion
        $cards_string = substr($inhalt, strpos($inhalt, '<caption>Karten</caption>')+25);
        //***

        $playerlist_home = $this->parseFifaPlayer($home_players, $cards_string, 'home');
        $playerlist_guest = $this->parseFifaPlayer($guest_players, $cards_string, 'guest');
        $playerlist_home_sub = $this->parseFifaPlayer($home_players_sub, $cards_string, 'home');
        $playerlist_guest_sub = $this->parseFifaPlayer($guest_players_sub, $cards_string, 'guest');

        //Spieler und Auswechselspieler zusammenfügen
        if($playerlist_home_sub) {
            for($i=0;$i<count($playerlist_home_sub);$i++) {
                if($playerlist_home_sub[$i]['player_change_in']) {
                    array_push($playerlist_home, $playerlist_home_sub[$i]);
                }
            }
        }
        if($playerlist_guest_sub) {
            for($i=0;$i<count($playerlist_guest_sub);$i++) {
                if($playerlist_guest_sub[$i]['player_change_in']) {
                    array_push($playerlist_guest, $playerlist_guest_sub[$i]);
                }
            }
        }

        $this->playerlist_home = $playerlist_home;
        $this->playerlist_guest = $playerlist_guest;

        $this->printPlayers($playerlist_home);
        $this->printPlayers($playerlist_guest);
        exit();

    }

    public function testWFProfile() {
		$this->parseWFProfile('http://www.weltfussball.de/spielbericht/em-qualifikation-2010-2011-gruppe-g-england-schweiz/');
		//$this->parseWFProfile('http://www.weltfussball.de/spielbericht/em-qualifikation-2010-2011-gruppe-b-russland-armenien/');
	}

	private function normalizeString($string){
		$string = str_replace("\t", "", trim($string));
		$string = str_replace("\r", "", trim($string));
		$string = str_replace("\n", "", trim($string));

		return $string;
	}

	private function parseWfResult($data) {
		$startPos = strpos($data, '<div class="resultat">')+22;
		$endPos = strpos($data, '</div>', $startPos);
		$resultStr = substr($data, $startPos, $endPos-$startPos);
		return trim($resultStr);
	}

    //parsing from Weltfussball.at
    private function parseWFProfile($dateiname) {
		$datei = fopen( $dateiname , "r" );

        //check if Profile-Page has loaded successfully
        if(!$datei)
            return false;
        //*** ***

        $inhalt = $this->normalizeString(stream_get_contents($datei));

		$resultString = $this->parseWfResult($inhalt);
		if(strpos($resultString, 'n.V.') !== false || strpos($resultString, 'i.E.') !== false) {
			$match_minutes = 120;
		} else {
			$match_minutes = 90;
		}

		//GOALS
        $goals_string = substr($inhalt, strpos($inhalt, 'Tore')+4, strpos($inhalt, '</table>', strpos($inhalt, 'Tore')+4)-strpos($inhalt, 'Tore')+4);
		//echo $goals_string;
		//die();
		$assist_string = '';
        while(strpos($goals_string, '(<a href="/spieler_profil/') > 0) {
          $assist_substring = substr($goals_string, strpos($goals_string, '(<a href="/spieler_profil/'), strpos($goals_string, ')', strpos($goals_string, '(<a href="/spieler_profil/'))-strpos($goals_string, '(<a href="/spieler_profil/')+1);
          $pos = strpos($goals_string, $assist_substring);
		  if($pos !== false) {
			  $goals_string = substr_replace($goals_string, '', $pos, strlen($assist_substring));
		  }
          //$goals_string = str_replace($assist_substring, '', $goals_string);
		  $assist_string .= $assist_substring;
        }
		//echo $assist_string;
		//die();

		//parse goals
        $goals = explode('spieler_profil', trim($goals_string));
        $goal_list = array();
        $owngoal_list = array();
        for($i=1;$i<count($goals);$i++) {
			$name = trim(substr($goals[$i], strpos($goals[$i], '">')+2, strpos($goals[$i], '</a>')-(strpos($goals[$i], '">')+2)));
			$index_name = md5($name);
			$minute = trim(substr($goals[$i], strpos($goals[$i], '</a>')+4, strpos($goals[$i], '.', strpos($goals[$i], '</a>')+4)-(strpos($goals[$i], '</a>')+4)));
			if(strpos($goals[$i], 'Eigentor')) {
				$own = 1;
			} else {
				$own = 0;
			}
			if(!$own) {
				if(!$goal_list[$index_name]) {
					$goal_list[$index_name]['num'] = 1;
					$goal_list[$index_name]['minutes'] = $minute;
				} else {
					$goal_list[$index_name]['num']++;
					$goal_list[$index_name]['minutes'] .= ';'.$minute;
				}
			} else {
				if(!$owngoal_list[$index_name]) {
					$owngoal_list[$index_name]['num'] = 1;
					$owngoal_list[$index_name]['minutes'] = $minute;
				} else {
					$owngoal_list[$index_name]['num']++;
					$owngoal_list[$index_name]['minutes'] .= ';'.$minute;
				}
			}
		}

		//parse assists
        $assists = explode('spieler_profil', trim($assist_string));
        $assist_list = array();
        for($i=1;$i<count($assists);$i++) {
			$name = trim(substr($assists[$i], strpos($assists[$i], '">')+2, strpos($assists[$i], '</a>')-(strpos($assists[$i], '">')+2)));
			$index_name = md5($name);

			if(!$assist_list[$index_name]) {
				$assist_list[$index_name]['num'] = 1;
			} else {
				$assist_list[$index_name]['num']++;
			}
		}
		//print_r($goal_list);
		//*** ***

		//PENALTIES
		$tmp_str = substr($inhalt, strpos($inhalt, '</table>', strpos($inhalt, 'Tore')+4));
		if(strpos($tmp_str, 'Elfmeterschie')) {
			$penalty_string = substr($tmp_str, strpos($tmp_str, 'Elfmeterschie')+13, strpos($tmp_str, '</table>', strpos($tmp_str, 'Elfmeterschie')+13)-(strpos($tmp_str, 'Elfmeterschie')+13));
			$penalties = explode('spieler_profil', trim($penalty_string));

			$penalty_list = array();
	        for($i=1;$i<count($penalties);$i++) {
				$name = trim(substr($penalties[$i], strpos($penalties[$i], '">')+2, strpos($penalties[$i], '</a>')-(strpos($penalties[$i], '">')+2)));
				$index_name = md5($name);
				$hit_string = trim(substr($penalties[$i], strpos($penalties[$i], '</a>')+4, strpos($penalties[$i], '</td>')-(strpos($penalties[$i], '</a>')+4)));

				if(strcmp('trifft', $hit_string) == 0) {
					$hit = 1;
				} else {
					$hit = 0;
				}

				if(!$penalty_list[$index_name]) {
					if($hit) {
						$penalty_list[$index_name]['hits'] = 1;
						$penalty_list[$index_name]['fails'] = 0;
					} else {
						$penalty_list[$index_name]['hits'] = 0;
						$penalty_list[$index_name]['fails'] = 1;
					}
				} else {
					if($hit) {
						$penalty_list[$index_name]['hits']++;
					} else {
						$penalty_list[$index_name]['fails']++;
					}
				}
			}
			$tmp_str = substr($tmp_str, strpos($tmp_str, '</table>', strpos($tmp_str, 'Elfmeterschie')+13));
		}
		//*** ***
		//$home_string = substr($tmp_str, strpos($tmp_str, '<table class="standard_tabelle"')+31, strpos($tmp_str, 'Wechsel')-(strpos($tmp_str, '<table class="standard_tabelle"')+31));
		$home_string = substr($tmp_str, strpos($tmp_str, '<table class="standard_tabelle"')+31, strpos($tmp_str, 'Reservespieler')-(strpos($tmp_str, '<table class="standard_tabelle"')+31));
		//filter out "besondere vorkommnisse"
		if(strpos($home_string, 'Besondere Vorkommnisse')) {
			$home_string = substr($home_string, strpos($home_string, '<table class="standard_tabelle"')+31);
		}
		// ***

        $home_pls = explode('<tr>', trim($home_string));
		$home_string_sub = substr($tmp_str, strpos($tmp_str, 'Reservespieler')+14, strpos($tmp_str, '<table class="standard_tabelle"', strpos($tmp_str, 'Reservespieler')+14)-strpos($tmp_str, 'Reservespieler')+14);
        $home_pls_sub = explode('<tr>', trim($home_string_sub));
        $tmp_str = substr($tmp_str, strpos($tmp_str, '</table>', strpos($tmp_str, 'Reservespieler')+14));
		$guest_string = substr($tmp_str, strpos($tmp_str, '<table class="standard_tabelle"')+31, strpos($tmp_str, 'Reservespieler')-(strpos($tmp_str, '<table class="standard_tabelle"')+31));
		$guest_pls = explode('<tr>', trim($guest_string));
		$guest_string_sub = substr($tmp_str, strpos($tmp_str, 'Reservespieler')+14, strpos($tmp_str, '<table class="standard_tabelle"', strpos($tmp_str, 'Reservespieler')+14)-strpos($tmp_str, 'Reservespieler')+14);
        $guest_pls_sub = explode('<tr>', trim($guest_string_sub));
/*
        echo count($home_pls).'<br>';
        echo count($home_pls_sub).'<br>';
        echo count($guest_pls).'<br>';
        echo count($guest_pls_sub).'<br>';
*/
		$count = count($home_pls)-1;
		$playerlist_home = $this->parseWFPlayer($home_pls, $count, $goal_list, $owngoal_list, $assist_list, $penalty_list, $match_minutes);
//		print_r($playerlist_home);
//		exit();
		$count = count($guest_pls)-1;
		$playerlist_guest = $this->parseWFPlayer($guest_pls, $count, $goal_list, $owngoal_list, $assist_list, $penalty_list, $match_minutes);
		//exit();

		$count = count($home_pls_sub);
		$playerlist_home_sub = $this->parseWFPlayer($home_pls_sub, $count, $goal_list, $owngoal_list, $assist_list, $penalty_list, $match_minutes);
//		echo 'num sub home: '.count($playerlist_home_sub).'<br>';
//		print_r($playerlist_home_sub);
//		exit();
		$count = count($guest_pls_sub);
		$playerlist_guest_sub = $this->parseWFPlayer($guest_pls_sub, $count, $goal_list, $owngoal_list, $assist_list, $penalty_list, $match_minutes);

        //Spieler und Auswechselspieler zusammenfügen
        if($playerlist_home_sub) {
            for($i=0;$i<count($playerlist_home_sub);$i++) {
                if($playerlist_home_sub[$i]['player_change_in']) {
                    array_push($playerlist_home, $playerlist_home_sub[$i]);
                }
            }
        }
        if($playerlist_guest_sub) {
            for($i=0;$i<count($playerlist_guest_sub);$i++) {
                if($playerlist_guest_sub[$i]['player_change_in']) {
                    array_push($playerlist_guest, $playerlist_guest_sub[$i]);
                }
            }
        }

		$this->match_minutes = $match_minutes;
        $this->playerlist_home = $playerlist_home;
        $this->playerlist_guest = $playerlist_guest;

        //$this->printPlayers($playerlist_home);
        //$this->printPlayers($playerlist_guest);
        //exit();
    }

    private function parseWFPlayer($player_array, $count, $goal_list, $owngoal_list, $assist_list, $penalty_list, $match_minutes) {
    	$players = array();
    	for($i=1;$i<$count;$i++) {
    		//PLAYER NAME
    		$player_array[$i] = trim($player_array[$i]);
    		//echo $player_array[$i].'<br>'."\n\n";
			//$tmp_name = substr($player_array[$i], strpos($player_array[$i], 'href="')+6, strpos($player_array[$i], '</span></td>', strpos($player_array[$i], 'href="')+6)-(strpos($player_array[$i], 'href="')+6));
			//$tmp_name = substr($player_array[$i], strpos($player_array[$i], 'href="')+6, strpos($player_array[$i], '</a>', strpos($player_array[$i], 'href="')+6)-(strpos($player_array[$i], 'href="')+6));$tmp_name = substr($player_array[$i], strpos($player_array[$i], 'href="')+6, strpos($player_array[$i], '</span></td>', strpos($player_array[$i], 'href="')+6)-(strpos($player_array[$i], 'href="')+6));
			//echo $i."\n";
			$tmp_name = substr($player_array[$i], strpos($player_array[$i], 'title="')+7, strpos($player_array[$i], '</a>', strpos($player_array[$i], 'title="')+7)-(strpos($player_array[$i], 'title="')+7)+4);
			//echo 'TMP: '.$tmp_name.'<br>'."\n\n";

			$name = trim(substr($tmp_name, strpos($tmp_name, '">')+2, strpos($tmp_name, '</a>')-(strpos($tmp_name, '">')+2)));
			//echo $name.'<br>';
			$index_name = md5($name);
			//*** ***

			//CHANGE TIMES
			$in = 0;
			$out = 0;
			//$change = trim(substr($tmp_name, strpos($tmp_name, 'align="right"><span class="kleine_schrift">')+43));
			//if(strpos($tmp_name, 'rottext') || strpos($tmp_name, 'gruentext')) {
			if(strpos($player_array[$i], 'rottext') || strpos($player_array[$i], 'gruentext')) {
				//$change = trim(substr($tmp_name, strpos($tmp_name, 'class="kleine_schrift"><span class="')+36));
				$change = trim(substr($player_array[$i], strpos($player_array[$i], 'class="kleine_schrift"><span class="')+36));
			} else {
				$change = 0;
			}
			//echo 'change: '.$change.'<br>'."\n\n";
			if($change) {
				if(strpos($change, 'ottext')) {
					$out = trim(substr($change, strpos($change, 'rottext">')+9, strpos($change, '\'', strpos($change, 'rottext">')+9)-(strpos($change, 'rottext">')+9)));
				}
				if(strpos($change, 'ruentext')) {
					$in = trim(substr($change, strpos($change, 'gruentext">')+11, strpos($change, '\'', strpos($change, 'gruentext">')+11)-(strpos($change, 'gruentext">')+11)));
				}
			}
			//*** cards ***
			if(strpos($player_array[$i], 'Gelbe Karte')) {
				$card_y = 1;
			} else {
				$card_y = 0;
			}
			if(strpos($player_array[$i], 'Rote Karte')) {
				$card_r = 1;
				$out = trim(substr($player_array[$i], strpos($player_array[$i], 'in Minute ')+10, strpos($player_array[$i], '"', strpos($player_array[$i], 'in Minute ')+10)-(strpos($player_array[$i], 'in Minute ')+10)));
			} else {
				$card_r = 0;
			}
			if(strpos($player_array[$i], 'Gelbrote Karte')) {
				$card_yr = 1;
				$out = trim(substr($player_array[$i], strpos($player_array[$i], 'in Minute ')+10, strpos($player_array[$i], '"', strpos($player_array[$i], 'in Minute ')+10)-(strpos($player_array[$i], 'in Minute ')+10)));
			} else {
				$card_yr = 0;
			}
			//*** ***

			//PLAYER NAME
			$players[$i-1]['player_name'] = $name;
			//*** ***

			//CHANGE TIMES
			if($in) {
				$players[$i-1]['player_change_in'] = $in;
			} else {
				$players[$i-1]['player_change_in'] = 0;
			}
			if(is_numeric($out)) {
				$players[$i-1]['player_change_out'] = $out;
			} else {
				$players[$i-1]['player_change_out'] = 0;
			}
			//*** ***

			//CARDS
			if($card_r) {
				$players[$i-1]['player_cards'] = 'R';
			} elseif($card_yr) {
				$players[$i-1]['player_cards'] = 'YR';
			} elseif($card_y) {
				$players[$i-1]['player_cards'] = 'Y';
			} else {
				$players[$i-1]['player_cards'] = 0;
			}
			//*** ***

			//GOALS and OWNGOALS
			if($goal_list[$index_name]) {
				$players[$i-1]['player_num_goals'] = $goal_list[$index_name]['num'];
				$players[$i-1]['player_goal'] = $goal_list[$index_name]['minutes'];
				$players[$i-1]['player_goal_minutes'] = $goal_list[$index_name]['minutes'];
			} else {
				$players[$i-1]['player_num_goals'] = 0;
				$players[$i-1]['player_goal'] = 0;
				$players[$i-1]['player_goal_minutes'] = '';
			}
			if($owngoal_list[$index_name]) {
				$players[$i-1]['player_num_owngoals'] = $owngoal_list[$index_name]['num'];
				$players[$i-1]['player_owngoal'] = $owngoal_list[$index_name]['minutes'];
				$players[$i-1]['player_owngoal_minutes'] = $owngoal_list[$index_name]['minutes'];
			} else {
				$players[$i-1]['player_num_owngoals'] = 0;
				$players[$i-1]['player_owngoal'] = 0;
				$players[$i-1]['player_owngoal_minutes'] = '';
			}
			//*** ***

			//ASSISTS
			if($assist_list[$index_name]) {
				$players[$i-1]['player_num_assists'] = $assist_list[$index_name]['num'];
			} else {
				$players[$i-1]['player_num_assists'] = 0;
			}
			//*** ***

			//MINUTES PLAYED
			$minutes = $match_minutes;
			if($players[$i-1]['player_change_out'] && $players[$i-1]['player_change_in']) {
				$minutes = $players[$i-1]['player_change_out'] - $players[$i-1]['player_change_in'];
			} elseif($players[$i-1]['player_change_out']) {
				$minutes = $players[$i-1]['player_change_out'];
			} elseif($players[$i-1]['player_change_in']) {
				$minutes = $match_minutes-$players[$i-1]['player_change_in'];
			}
			if($players[$i-1]['player_change_in'] >= $match_minutes) {
				$minutes = 1;
			}
			if($minutes > $match_minutes) {
				$minutes = $match_minutes;
			}
			$players[$i-1]['player_minutes'] = $minutes;
			//*** ***

			//PENALTY SHOOTOUT
			if(count($penalty_list)) {
				$players[$i-1]['player_penaltyshootout'] = 1;
				if($penalty_list[$index_name]) {
					$players[$i-1]['player_penalties_hit'] = $penalty_list[$index_name]['hits'];
					$players[$i-1]['player_penalties_fail'] = $penalty_list[$index_name]['fails'];
				} else {
					$players[$i-1]['player_penalties_hit'] = 0;
					$players[$i-1]['player_penalties_fail'] = 0;
				}
			} else {
				$players[$i-1]['player_penaltyshootout'] = 0;
				$players[$i-1]['player_penalties_hit'] = 0;
				$players[$i-1]['player_penalties_fail'] = 0;
			}
			//*** ***
		}
    	return $players;
    }

    private function parseFoePlayer($player_array) {
        $players = array();
        for($i=0;$i<count($player_array)-1;$i++) {
            $actions = explode('</td>', $player_array[$i+1]);

            $players[$i]['player_name'] = substr($actions[1], strpos($actions[1], "'>")+2); //Name
            $change_out = substr($actions[5], strpos($actions[5], "'>")+2);
            if(!is_numeric($change_out)) {
                if($change_out == 'HZ') {
                    $players[$i]['player_change_out'] = 45;
                } else {
                    $players[$i]['player_change_out'] = 0;
                }
            } else {
                $players[$i]['player_change_out'] = $change_out;
            }

            $change_in = substr($actions[6], strpos($actions[6], "'>")+2);
            if(!is_numeric($change_in)) {
                if($change_in == 'HZ') {
                    $players[$i]['player_change_in'] = 45;
                } else {
                    $players[$i]['player_change_in'] = 0;
                }
            } else {
                $players[$i]['player_change_in'] = $change_in;
            }

            $yellow = substr($actions[7], strpos($actions[7], "'>")+2);
            $yellow2red = substr($actions[8], strpos($actions[8], "'>")+2);
            $red = substr($actions[9], strpos($actions[9], "'>")+2);
            if(is_numeric($red)) {
                $players[$i]['player_cards'] = 'R';
                $players[$i]['player_change_out'] = $red;
            } elseif(is_numeric($yellow2red)) {
                $players[$i]['player_cards'] = 'YR';
                $players[$i]['player_change_out'] = $yellow2red;
            } elseif(is_numeric($yellow)) {
                $players[$i]['player_cards'] = 'Y';
            } else {
                $players[$i]['player_cards'] = 0;
            }

            $goals = substr($actions[10], strpos($actions[10], "'>")+2);
            $goal_string = '';
            $owngoal_string = '';
            if(!is_numeric($goals)) {
                $goal_tmp = explode(',', $goals);
                foreach($goal_tmp as $goal_min) {
                    $goal_min = trim($goal_min, '+ ');
                    if(is_numeric($goal_min)) {
                        $goal_string .= $goal_min.';';
                    } else {
                        if(substr_count($player_array[$i+1], 'Elf') > 0) {
                            $goal_string .= trim($goal_min, '+ (Elfm.)').';';
                        } elseif(substr_count($player_array[$i+1], 'Fr') > 0) {
                            $goal_string .= trim($goal_min, '+ (Fr.)').';';
                        } elseif(substr_count($player_array[$i+1], 'ET') > 0) {
                            $owngoal_string .= trim($goal_min, '+ (ET)').';';
                        }
                    }
                }
                $penalties = substr_count($player_array[$i+1], 'Elf');
                $owngoals = substr_count($player_array[$i+1], 'ET');
                if(count($goal_tmp)>1) {
                    $players[$i]['player_goal'] = count($goal_tmp) - $owngoals;
                    $players[$i]['player_owngoal'] = $owngoals;
                } else {
                    if($penalties > 0) {
                        $players[$i]['player_goal'] = 1;
                        $players[$i]['player_owngoal'] = 0;
                    } elseif($owngoals > 0) {
                        $players[$i]['player_goal'] = 0;
                        $players[$i]['player_owngoal'] = 1;
                    } else {
                        $players[$i]['player_goal'] = 0;
                        $players[$i]['player_owngoal'] = 0;
                    }
                }
                $players[$i]['player_goal_minutes'] = substr($goal_string, 0, strlen($goal_string)-1);
                $players[$i]['player_owngoal_minutes'] = substr($owngoal_string, 0, strlen($owngoal_string)-1);
            } else {
                $players[$i]['player_goal'] = 1;
                $players[$i]['player_goal_minutes'] = $goals;
                $players[$i]['player_owngoal'] = 0;
            }
            //echo $players[$i]['player_name'].': '.$players[$i]['player_change_out'].'<br>';
            if(!$players[$i]['player_change_out'] && !$players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = 90;
            } elseif($players[$i]['player_change_out'] && !$players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = $players[$i]['player_change_out'];
            } elseif(!$players[$i]['player_change_out'] && $players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = 90-$players[$i]['player_change_in'];
            } elseif($players[$i]['player_change_out'] && $players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = $players[$i]['player_change_out']-$players[$i]['player_change_in'];
            }
            if($players[$i]['player_change_in'] >= 90) {
                $players[$i]['player_minutes'] = 1;
            }

            $pm = $this->options->options_game_pointsmode;
            if($pm == 'new') {
                if($players[$i]['player_goal_minutes']) {
                    $players[$i]['player_goal'] = $players[$i]['player_goal_minutes'];
                }
                if($players[$i]['player_owngoal_minutes']) {
                    $players[$i]['player_owngoal'] = $players[$i]['player_owngoal_minutes'];
                }
            }
            $players[$i]['player_penaltyshootout'] = 0;
			$players[$i]['player_penalties_hit'] = 0;
			$players[$i]['player_penalties_fail'] = 0;
        }
        return $players;
    }

    private function parseFifaPlayer($player_array, $cards_string, $h_or_g) {
        $players = array();
        for($i=0;$i<count($player_array)-1;$i++) {
            $goal = substr_count($player_array[$i+1], '/goal.gif') + substr_count($player_array[$i+1], 'penalty.gif');
            $owngoal = substr_count($player_array[$i+1], 'owngoal.gif');
            $yellow = substr_count($player_array[$i+1], 'yellow.gif');
            $red = substr_count($player_array[$i+1], '/red.gif');
            $yellow2red = substr_count($player_array[$i+1], 'yellow2red.gif');
            $change_out = substr_count($player_array[$i+1], 'outAway.gif') + substr_count($player_array[$i+1], 'outHome.gif');
            $change_in = substr_count($player_array[$i+1], 'inAway.gif') + substr_count($player_array[$i+1], 'inHome.gif');
            $players[$i]['player_minutes'] = 0;

            //echo '***'.$player_array[$i+1].'***<br>'."\n";
            $is_link = substr_count($player_array[$i+1], 'index.html">');
            if($is_link) {
                $players[$i]['player_name'] = substr($player_array[$i+1], (strpos($player_array[$i+1], 'index.html">')+12), strpos($player_array[$i+1], '</a>')-(strpos($player_array[$i+1], 'index.html">')+12));
            } else {
                $player_array[$i+1] = str_replace('(C)', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('(GK)', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Ausgewechselt', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Eingewechselt', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Gelbe Karte', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Rote Karte', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Tor', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Strafstoß', '', $player_array[$i+1]);
                $player_array[$i+1] = str_replace('Eigentor', '', $player_array[$i+1]);

                $players[$i]['player_name'] = substr($player_array[$i+1], (strpos($player_array[$i+1], ' ')+1), strpos($player_array[$i+1], '</li>')-(strpos($player_array[$i+1], ' ')+1));
                //echo '***'.$players[$i]['player_name'].'***<br>'."\n";
                if($h_or_g == 'guest') {
                    while(substr_count($players[$i]['player_name'], '<img')) {
                        $players[$i]['player_name'] = substr($players[$i]['player_name'], (strpos($players[$i]['player_name'], '/>')+2), strlen($players[$i]['player_name'])-(strpos($players[$i]['player_name'], '/>')+2));
                    }
                    $temp_number_1 = substr($players[$i]['player_name'], strlen($players[$i]['player_name'])-1);
                    $temp_number_2 = substr($players[$i]['player_name'], strlen($players[$i]['player_name'])-2);
                    if(is_numeric($temp_number_2)) {
                        $players[$i]['player_name'] = substr($players[$i]['player_name'], 0, strlen($players[$i]['player_name'])-3);
                    } elseif(is_numeric($temp_number_1)) {
                        $players[$i]['player_name'] = substr($players[$i]['player_name'], 0, strlen($players[$i]['player_name'])-2);
                    }
                } elseif($h_or_g == 'home') {
                    if(substr_count($players[$i]['player_name'], '<img')) {
                        $players[$i]['player_name'] = substr($players[$i]['player_name'], 0, strpos($players[$i]['player_name'], '<img'));
                    }
                }
                $players[$i]['player_name'] = trim($players[$i]['player_name']);
            }
            $card_minute = '';
            //echo $players[$i]['player_name'].'<br><br>'."\n";

            if(!$players[$i]['player_name']) {
                $players[$i]['player_name'] = '??';
            }

            if(!$goal) {
                $players[$i]['player_goal'] = 0;
            } else {
                $players[$i]['player_goal'] = $goal;
            }
            if(!$owngoal) {
                $players[$i]['player_owngoal'] = 0;
            } else {
                $players[$i]['player_owngoal'] = $owngoal;
            }
            if($yellow) {
                $players[$i]['player_cards'] = 'Y';
            }
            if($yellow2red) {
                $players[$i]['player_cards'] = 'YR';
                $card_minute = $this->parseFifaRedCard($players[$i]['player_name'], $cards_string, $h_or_g);
            }
            if($red) {
                $players[$i]['player_cards'] = 'R';
                $card_minute = $this->parseFifaRedCard($players[$i]['player_name'], $cards_string, $h_or_g);
            }
            if(!$red && !$yellow && !$yellow2red) {
                $players[$i]['player_cards'] = 0;
            }
            if(!$change_out) {
                $players[$i]['player_change_out'] = 0;
            } else {
                $players[$i]['player_change_out'] = substr($player_array[$i+1], strpos($player_array[$i+1], '(-')+2, strpos($player_array[$i+1], "'")-(strpos($player_array[$i+1], '(-')+2));
            }
            if(is_numeric($card_minute)) {
                $players[$i]['player_change_out'] = $card_minute;
            }
            if(!$change_in) {
                $players[$i]['player_change_in'] = 0;
            } else {
                $players[$i]['player_change_in'] = substr($player_array[$i+1], strpos($player_array[$i+1], '(+')+2, strpos($player_array[$i+1], "'")-(strpos($player_array[$i+1], '(+')+2));
                $players[$i]['player_minutes'] = abs($players[$i]['player_change_in'] - $players[$i]['player_change_out']);
            }
            if(!$players[$i]['player_change_out'] && !$players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = 90;
            } elseif($players[$i]['player_change_out'] && !$players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = $players[$i]['player_change_out'];
            } elseif(!$players[$i]['player_change_out'] && $players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = 90-$players[$i]['player_change_in'];
            } elseif($players[$i]['player_change_out'] && $players[$i]['player_change_in']) {
                $players[$i]['player_minutes'] = $players[$i]['player_change_out']-$players[$i]['player_change_in'];
            }
            if($players[$i]['player_minutes'] <= 0) {
                $players[$i]['player_minutes'] = 1;
            }
        }
        return $players;
    }

    private function parseFifaRedcard($name, $cards_string, $h_or_g) {
        if($h_or_g == 'guest') {
            $temp_string = substr($cards_string, strrpos($cards_string, $name)-9);
            $card_minute = substr($temp_string, strpos($temp_string, '(')+1, strpos($temp_string, '\'')-(strpos($temp_string, '(')+1));
        } elseif($h_or_g == 'home') {
            $temp_string = substr($cards_string, strrpos($cards_string, $name));
            $card_minute = substr($temp_string, strpos($temp_string, '(')+1, strpos($temp_string, '\'')-(strpos($temp_string, '(')+1));
        }
        return $card_minute;
    }

    private function printPlayers($players) {
        for($i=0;$i<count($players);$i++) {
            echo $players[$i]['player_name'].' ('.$players[$i]['player_minutes'].' min)';
            if($players[$i]['player_goal']) {
                echo ' ('.$players[$i]['player_goal'].' GOALS)';
                echo ' ('.$players[$i]['player_goal_minutes'].'.Min.)';
            }
            if($players[$i]['player_owngoal']) {
                echo ' ('.$players[$i]['player_owngoal'].' OWNGOALS)';
                echo ' ('.$players[$i]['player_owngoal_minutes'].'.Min.)';
            }
            if($players[$i]['player_change_out']) {
                echo ' (OUT: '.$players[$i]['player_change_out'].')';
            }
            if($players[$i]['player_change_in']) {
                echo ' (IN: '.$players[$i]['player_change_in'].')';
            }
            if($players[$i]['player_cards']) {
                echo ' ('.$players[$i]['player_cards'].')';
            }
            echo '<br>'."\n";
        }
        echo '<br>'."\n";
    }
}
?>