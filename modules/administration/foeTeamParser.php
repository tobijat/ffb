<?php

/**
 * ADMIN - FOETEAMPARSER-Klasse;
 * Fußball Österreich Team Parser
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.1
 *
 */

class foeTeamParser extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'foeTeamParser.php';
    }

    public function __default() {
    }

    public function testCrit() {
    		$player['player_fname'] = 'Lukas';
    		$player['player_lname'] = 'Gritschacher';
    		$querystring_lnfn = strtolower(trim($player['player_lname']).' '.trim($player['player_fname']));

			$cr = new Criteria();
			$cr->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
			$cr->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, Criteria::INNER_JOIN);
			$c0 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_FNAME, $player['player_fname']);
			$c1 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_LNAME, $player['player_lname']);
			$c0->addAnd($c1);
			$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $querystring_lnfn);
			$c2->addOr($c0);
			$cr->add($c2);
			$cr->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, 33);
			$cr->setLimit(1);
			$pls = FfbPlayerteamPeer::doSelect($cr);

			echo 'pls: '.count($pls).'<br>';
			echo 'found: '.$pls[0]->getFfbPlayer()->getPlayerFname().' '.$pls[0]->getFfbPlayer()->getPlayerLname().'<br>';
            //$playerteam = $pls[0]->getFfbPlayerteams();
	}

    public function loadPlayerlistFromUrl() {
        $team_id = $_POST['team_id'];
        //$team_id = 90;

        $source_url = 'http://ffb.tobijat.at/resource/foe_teamdata/';
        $team = FfbTeamPeer::retrieveByPK($team_id);
        $source_url .= strtolower($team->getTeamNationality()).'.html';

		$players_tm = $this->parseFOEUrl($source_url);
        $players_found = array();
        $players_found_in_db = array();
        $loop=0;
        $f_loop=0;
        $fidb_loop=0;
        foreach($players_tm as $player) {
            $querystring_fnln = strtolower(trim($player['player_fname']).' '.trim($player['player_lname']));
            $querystring_lnfn = strtolower(trim($player['player_lname']).' '.trim($player['player_fname']));

			$cr = new Criteria();
			$cr->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
			$cr->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, Criteria::INNER_JOIN);
			$c0 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_FNAME, trim($player['player_fname']));
			$c1 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_LNAME, trim($player['player_lname']));
			$c0->addAnd($c1);
			$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $querystring_lnfn);
			$c2->addOr($c0);
			$cr->add($c2);
			$cr->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
			$cr->setLimit(1);
			$cr->setIgnoreCase(true);
			$playerteam = FfbPlayerteamPeer::doSelect($cr);

            if($playerteam) {
            	$players_found[$f_loop]['player_fname_tm'] = $player['player_fname'];
                $players_found[$f_loop]['player_lname_tm'] = $player['player_lname'];
                $players_found[$f_loop]['player_fname'] = $playerteam[0]->getFfbPlayer()->getPlayerFname();
                $players_found[$f_loop]['player_lname'] = $playerteam[0]->getFfbPlayer()->getPlayerLname();
                $players_found[$f_loop]['player_profile'] = $player['player_profile'];
                $players_found[$f_loop]['playerteam_player_position'] = $playerteam[0]->getPlayerteamPlayerPosition();
                $players_found[$f_loop]['playerteam_player_position_tm'] = $player['playerteam_player_position'];
                $players_found[$f_loop]['playerteam_id'] = $playerteam[0]->getPlayerteamId();
                $players_found[$f_loop]['player_id'] = $playerteam[0]->getFfbPlayer()->getPlayerId();
                $players_found[$f_loop]['playerteam_team_id'] = $playerteam[0]->getPlayerteamTeamId();
                $players_found[$f_loop]['playerteam_player_price'] = $playerteam[0]->getPlayerteamPlayerPrice();
                if($playerteam[0]->getPlayerteamStatus()) {
                    $players_found[$f_loop]['playerteam_status'] = $playerteam[0]->getPlayerteamStatus();
                } else {
                    $players_found[$f_loop]['playerteam_status'] = 0;
                }

                if($playerteam[0]->getPlayerteamPlayerPicture()) {

                    $players_found[$f_loop]['playerteam_player_picture'] = $playerteam[0]->getPlayerteamPlayerPicture();
                    $players_found[$f_loop]['new_image'] = 0;
                } else {
                    if($player['playerteam_player_picture']) {
                        $players_found[$f_loop]['playerteam_player_picture'] = $player['playerteam_player_picture'];
                        $players_found[$f_loop]['new_image'] = 1;
                    } else {
                        $players_found[$f_loop]['playerteam_player_picture'] = 0;
                        $players_found[$f_loop]['new_image'] = 0;
                    }
                }

				$criteria = new Criteria();
				$criteria->add(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerteam[0]->getPlayerteamId());
				$criteria->setLimit(1);
				$playerfid = FfbPlayerfidPeer::doSelect($criteria);
				if($playerfid) {
					$playerfid_name_foe = $playerfid[0]->getPlayerfidNameFoe();
				} else {
					$playerfid_name_foe = '';
				}
				$players_found[$f_loop]['playerteam_playerfid_name'] = $playerfid_name_foe;

				$players_found[$f_loop]['player_foreign_id'] = 0;
				$players_found[$f_loop]['new_foreign_id'] = 0;

                if(!$playerteam[0]->getFfbPlayer()->getPlayerStatusDescription() && $player['player_status_description']) {
                    $players_found[$f_loop]['player_status_description'] = $player['player_status_description'];
                    $players_found[$f_loop]['player_status_update'] = 1;
                } elseif($playerteam[0]->getFfbPlayer()->getPlayerStatusDescription() && !$player['player_status_description']) {
                    $players_found[$f_loop]['player_status_update'] = 1;
                    $players_found[$f_loop]['player_status_description'] = 0;
                } else {
                    $players_found[$f_loop]['player_status_description'] = 0;
                    $players_found[$f_loop]['player_status_update'] = 0;
                }

                unset($players_tm[$loop]);
                $f_loop++;
            } else {
            	//player not found in selected team - perhaps we'll find him in another team

            	//echo 'not found in given team<br>';
            	//echo $player['player_fname'].' '.$player['player_lname'].'<br>';

                $cr = new Criteria();
				$cr->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
				$cr->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, Criteria::INNER_JOIN);
				$c0 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_FNAME, trim($player['player_fname']));
				$c1 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_LNAME, trim($player['player_lname']));
				$c0->addAnd($c1);
				$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $querystring_lnfn);
				$c2->addOr($c0);
				$cr->add($c2);
				$cr->setLimit(20);
				$cr->setIgnoreCase(true);
				$pt = FfbPlayerteamPeer::doSelect($cr);

                if($pt) {
                	//echo 'FOUND in other team!<br>';
                    $pt_loop = 0;
                    foreach($pt as $playerteam) {
                        $players_found_in_db[$fidb_loop]['player_fname'] = $player['player_fname'];
                        $players_found_in_db[$fidb_loop]['player_lname'] = $player['player_lname'];
                        $players_found_in_db[$fidb_loop]['player_id'] = $playerteam->getFfbPlayer()->getPlayerId();
                        $players_found_in_db[$fidb_loop]['player_profile'] = $player['player_profile'];
                        $players_found_in_db[$fidb_loop]['playerteam_player_position'] = $playerteam->getPlayerteamPlayerPosition();
                        $players_found_in_db[$fidb_loop]['playerteam_player_position_tm'] = $player['playerteam_player_position'];
                        $players_found_in_db[$fidb_loop]['playerteam_id'] = $playerteam->getPlayerteamId();
                        $players_found_in_db[$fidb_loop]['player_id'] = $playerteam->getFfbPlayer()->getPlayerId();
                        $players_found_in_db[$fidb_loop]['playerteam_team_id'] = $playerteam->getPlayerteamTeamId();
                        $players_found_in_db[$fidb_loop]['playerteam_team_name'] = $playerteam->getFfbTeam()->getTeamName();
                        $players_found_in_db[$fidb_loop]['playerteam_player_price'] = $playerteam->getPlayerteamPlayerPrice();
                        if($playerteam->getPlayerteamStatus()) {
                            $players_found_in_db[$fidb_loop]['playerteam_status'] = $playerteam->getPlayerteamStatus();
                        } else {
                            $players_found_in_db[$fidb_loop]['playerteam_status'] = 0;
                        }

                        if($playerteam->getPlayerteamPlayerPicture()) {
                            $players_found_in_db[$fidb_loop]['playerteam_player_picture'] = $playerteam->getPlayerteamPlayerPicture();
                            $players_found_in_db[$fidb_loop]['new_image'] = 0;
                        } else {
                            if($player['playerteam_player_picture']) {
                                $players_found_in_db[$fidb_loop]['playerteam_player_picture'] = $player['playerteam_player_picture'];
                                $players_found_in_db[$fidb_loop]['new_image'] = 1;
                            } else {
                                $players_found_in_db[$fidb_loop]['playerteam_player_picture'] = 0;
                                $players_found_in_db[$fidb_loop]['new_image'] = 0;
                            }
                        }

						$players_found_in_db[$fidb_loop]['player_foreign_id'] = 0;
                        $players_found_in_db[$fidb_loop]['new_foreign_id'] = 0;

                        if(!$playerteam->getFfbPlayer()->getPlayerStatusDescription() && $player['player_status_description']) {
                            $players_found_in_db[$fidb_loop]['player_status_description'] = $player['player_status_description'];
                            $players_found_in_db[$fidb_loop]['player_status_update'] = 1;
                        } elseif($playerteam->getFfbPlayer()->getPlayerStatusDescription() && !$player['player_status_description']) {
                            $players_found_in_db[$fidb_loop]['player_status_update'] = 1;
                            $players_found_in_db[$fidb_loop]['player_status_description'] = 0;
                        } else {
                            $players_found_in_db[$fidb_loop]['player_status_description'] = 0;
                            $players_found_in_db[$fidb_loop]['player_status_update'] = 0;
                        }

                        unset($players_tm[$loop]);
                        $fidb_loop++;
                        $pt_loop++;
                    }
                } else {
					//echo 'NOT found in other team!<br>';
				}
            }
            $loop++;
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $teamplayers = FfbPlayerteamPeer::doSelectJoinAll($criteria);
        $players_db = array();
        $loop = 0;

        $foundPlayersFlags = array();
        $fpf_loop = 0;
        foreach($players_found as $player) {
			$foundPlayersFlags[$player['playerteam_id']] = 1;
			//$foundPlayersFlags[$player['player_id']] = 1;
		}
        if($teamplayers) {
            foreach($teamplayers as $player) {
                $fname = $player->getFfbPlayer()->getPlayerFname();
                $lname = $player->getFfbPlayer()->getPlayerLname();
                $playerteam_id = $player->getPlayerteamId();
                //$player_id = $player->getPlayerteamPlayerId();

                if(!$foundPlayersFlags[$playerteam_id]) {
                    $players_db[$loop]['player_fname'] = $player->getFfbPlayer()->getPlayerFname();
                    $players_db[$loop]['player_lname'] = $player->getFfbPlayer()->getPlayerLname();
                    $players_db[$loop]['playerteam_player_position'] = $player->getPlayerteamPlayerPosition();
                    if($player->getPlayerteamPlayerPicture()) {
                        $players_db[$loop]['playerteam_player_picture'] = $player->getPlayerteamPlayerPicture();
                    } else {
                        $players_db[$loop]['playerteam_player_picture'] = 0;
                    }
                    if($player->getFfbPlayer()->getPlayerStatusDescription()) {
                        $players_db[$loop]['player_status_description'] = $player->getFfbPlayer()->getPlayerStatusDescription();
                    } else {
                        $players_db[$loop]['player_status_description'] = 0;
                    }

                    $players_db[$loop]['player_foreign_id'] = 0;

                    $players_db[$loop]['playerteam_player_price'] = $player->getPlayerteamPlayerPrice();
                    $players_db[$loop]['playerteam_id'] = $player->getPlayerteamId();
                    $players_db[$loop]['player_id'] = $player->getFfbPlayer()->getPlayerId();
                    if($player->getPlayerteamStatus()) {
                        $players_db[$loop]['playerteam_status'] = $player->getPlayerteamStatus();
                    } else {
                        $players_db[$loop]['playerteam_status'] = 0;
                    }
                    $loop++;
                }
            }
        }
		//print_r($players_tm);
		//print_r($players_found);
        $this->playerlist_tm = $players_tm;
        $this->playerlist_db = $players_db;
        $this->playerlist_found = $players_found;
        $this->playerlist_found_in_db = $players_found_in_db;
    //    exit();
    }

    public function testParser() {
        $this->parseUrl('http://www.transfermarkt.at/de/nationalmannschaft/3448/russland/uebersicht/startseite.print');
        //$this->parseUrl('http://www.transfermarkt.at/de/nationalmannschaft/3864/wales/uebersicht/startseite.print');
        echo 'fertig!';
        exit;
    }

    public function testPlayermanagement() {
        //$this->loadPlayerlistFromUrl('http://www.transfermarkt.at/de/nationalmannschaft/3379/niederlande/uebersicht/startseite.print', 12, 'tm');
        $this->loadPlayerlistFromUrl('http://ffb.tobijat.at/resource/foe_teamdata/lind.html', 32, 'foe');
        echo 'fertig!';
        exit();
    }

    public function testFOE() {
		$url = 'http://ffb.tobijat.at/resource/foe_teamdata/rennweg.html';
		$this->parseFOEUrl($url);
	}

    private function parseFOEUrl($tm_url) {
        $players_tm = array();
        $dateiname = $tm_url;

        $datei = fopen( $dateiname , "r" );
        $inhalt = stream_get_contents($datei);
        //$positions = explode('<div id="kaderHeader">', $inhalt);
        $positions = explode('<div class="headline">', $inhalt);

        $text = '';
        $max = count($positions);

		$loop = 0;
        for($i=1; $i<=$max-1; $i++) {
        	$pos = substr($positions[$i], 0, strpos($positions[$i], '</div>'));

        	if(strcmp($pos, 'Tor') == 0) {
				//$player = explode('<a class="portalLink"', $positions[$i]);
				$player = explode('<div class="spielerName">', $positions[$i]);
				$position = 'g';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
				  //echo $player[$j]."\n\n\n";
					$name = substr($player[$j], 0, strpos($player[$j], '</div>'));
				  $lname = substr($name, 0, strpos($name, ' '));
				  $fname = substr($name, strpos($name, ' ')+1);
          //$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					//$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
					//echo $lname.' '.$fname.'<br>';
					$players_tm[$loop]['player_fname'] =$fname;
		            $players_tm[$loop]['player_lname'] = $lname;
		            $players_tm[$loop]['player_status_description'] = 0;
		            $players_tm[$loop]['playerteam_player_position'] = $position;
		            $players_tm[$loop]['playerteam_player_picture'] = 0;
		            $players_tm[$loop]['player_foreign_id'] = 0;
		            $players_tm[$loop]['player_profile'] = 'OK';
		            $loop++;
				}
			}
			if(strcmp($pos, 'Verteidigung') == 0) {
				//$player = explode('<a class="portalLink"', $positions[$i]);
				$player = explode('<div class="spielerName">', $positions[$i]);
				$position = 'd';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$name = substr($player[$j], 0, strpos($player[$j], '</div>'));
				  $lname = substr($name, 0, strpos($name, ' '));
				  $fname = substr($name, strpos($name, ' ')+1);
          //$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					//$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
					//echo $lname.' '.$fname.'<br>';
					$players_tm[$loop]['player_fname'] =$fname;
		            $players_tm[$loop]['player_lname'] = $lname;
		            $players_tm[$loop]['player_status_description'] = 0;
		            $players_tm[$loop]['playerteam_player_position'] = $position;
		            $players_tm[$loop]['playerteam_player_picture'] = 0;
		            $players_tm[$loop]['player_foreign_id'] = 0;
		            $players_tm[$loop]['player_profile'] = 'OK';
		            $loop++;
				}
			}
			if(strcmp($pos, 'Mittelfeld') == 0) {
				//$player = explode('<a class="portalLink"', $positions[$i]);
				$player = explode('<div class="spielerName">', $positions[$i]);
				$position = 'm';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$name = substr($player[$j], 0, strpos($player[$j], '</div>'));
				  $lname = substr($name, 0, strpos($name, ' '));
				  $fname = substr($name, strpos($name, ' ')+1);
          //$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					//$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
					//echo $lname.' '.$fname.'<br>';
					$players_tm[$loop]['player_fname'] =$fname;
		            $players_tm[$loop]['player_lname'] = $lname;
		            $players_tm[$loop]['player_status_description'] = 0;
		            $players_tm[$loop]['playerteam_player_position'] = $position;
		            $players_tm[$loop]['playerteam_player_picture'] = 0;
		            $players_tm[$loop]['player_foreign_id'] = 0;
		            $players_tm[$loop]['player_profile'] = 'OK';
		            $loop++;
				}
			}
			if(strcmp($pos, 'Sturm') == 0) {
				//$player = explode('<a class="portalLink"', $positions[$i]);
				$player = explode('<div class="spielerName">', $positions[$i]);
				$position = 's';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$name = substr($player[$j], 0, strpos($player[$j], '</div>'));
				  $lname = substr($name, 0, strpos($name, ' '));
				  $fname = substr($name, strpos($name, ' ')+1);
          //$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					//$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
					//echo $lname.' '.$fname.'<br>';
					$players_tm[$loop]['player_fname'] = $fname;
		            $players_tm[$loop]['player_lname'] = $lname;
		            $players_tm[$loop]['player_status_description'] = 0;
		            $players_tm[$loop]['playerteam_player_position'] = $position;
		            $players_tm[$loop]['playerteam_player_picture'] = 0;
		            $players_tm[$loop]['player_foreign_id'] = 0;
		            $players_tm[$loop]['player_profile'] = 'OK';
		            $loop++;
				}
			}
			//echo "\n\nposition: $pos\n\n";
			if(strcmp($pos, 'Noch ohne Position') == 0) {
				//$player = explode('<a class="portalLink"', $positions[$i]);
				$player = explode('<div class="spielerName">', $positions[$i]);
				$position = '?';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
				  //echo $player[$j]."\n\n\n";
				  $name = substr($player[$j], 0, strpos($player[$j], '</div>'));
				  $lname = substr($name, 0, strpos($name, ' '));
				  $fname = substr($name, strpos($name, ' ')+1);
					//$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					//$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
					//echo $lname.' '.$fname.'<br>';
					$players_tm[$loop]['player_fname'] = $fname;
		            $players_tm[$loop]['player_lname'] = $lname;
		            $players_tm[$loop]['player_status_description'] = 0;
		            $players_tm[$loop]['playerteam_player_position'] = $position;
		            $players_tm[$loop]['playerteam_player_picture'] = 0;
		            $players_tm[$loop]['player_foreign_id'] = 0;
		            $players_tm[$loop]['player_profile'] = 'OK';
		            $loop++;
				}
			}
        }
		//echo '<br>Sum Players: '.count($players_tm);
        foreach($players_tm as $item) {
            $lnames[] = strtolower($item['player_lname']);
            $fnames[] = strtolower($item['player_fname']);
        }
        array_multisort($lnames, SORT_ASC, SORT_STRING, $fnames, SORT_ASC, SORT_STRING, $players_tm);

		//exit();

        return $players_tm;
    }
}
?>
