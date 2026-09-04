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

class playermanagement extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'playermanagement.php';
    }

    public function __default() {
    }

    public function loadPlayerlistFromUrl($param_tm_url=0, $param_team_id=0, $param_source=0) {
        if($_POST['tm_url'])
            { $tm_url = $_POST['tm_url']; }
        else
            { $tm_url = $param_tm_url; }
        if($_POST['team_id'])
            { $team_id = $_POST['team_id']; }
        else
            { $team_id = $param_team_id; }
		if($_POST['source'])
            { $source = $_POST['source']; }
        else
            { $source = $param_source; }

		if($source == 'tm') {
        	$players_tm = $this->parseTMUrl($tm_url);
        } elseif($source == 'foe') {
        	$players_tm = $this->parseFOEUrl($tm_url);
        } else {
			echo 'wrong source';
			return;
		}

        $players_found = array();
        $players_found_in_db = array();
        $loop=0;
        $f_loop=0;
        $fidb_loop=0;
        foreach($players_tm as $player) {
            $con = Propel::getConnection('d00817fb');
            $querystring = strtolower(trim($player['player_fname']).' '.trim($player['player_lname']));
            $querystring_lnfn = strtolower(trim($player['player_lname']).' '.trim($player['player_fname']));
            $player_foreign_id = $player['player_foreign_id'];
            //$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam WHERE LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' AND ffb_playerteam.playerteam_player_id = ffb_player.player_id";
            //$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR ffb_player.player_foreign_id='$player_foreign_id') AND ffb_playerteam.playerteam_player_id = ffb_player.player_id";
            //$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR ffb_player.player_foreign_id='$player_foreign_id') AND ffb_playerteam.playerteam_team_id='$team_id' AND ffb_playerteam.playerteam_player_id = ffb_player.player_id";
            if($source == 'tm') {
				$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam, ffb_playerfid WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR LOWER(ffb_playerfid.playerfid_name_tm) = '$querystring' OR ffb_player.player_foreign_id='$player_foreign_id') AND ffb_playerteam.playerteam_team_id='$team_id' AND ffb_playerteam.playerteam_player_id = ffb_player.player_id LIMIT 100";
            } elseif($source == 'foe') {
            	$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam, ffb_playerfid WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR LOWER(ffb_playerfid.playerfid_name_foe) = '$querystring_lnfn') AND ffb_playerteam.playerteam_team_id='$team_id' AND ffb_playerteam.playerteam_player_id = ffb_player.player_id LIMIT 100";
            }
			$stmt = $con->prepare($sql);
            $stmt->execute();
            $playerteam = FfbPlayerteamPeer::populateObjects($stmt);

            if($playerteam) {
                $players_found[$f_loop]['player_fname'] = $player['player_fname'];
                $players_found[$f_loop]['player_lname'] = $player['player_lname'];
                $players_found[$f_loop]['player_profile'] = $player['player_profile'];
                $players_found[$f_loop]['playerteam_player_position'] = $player['playerteam_player_position'];
                $players_found[$f_loop]['playerteam_id'] = $playerteam[0]->getPlayerteamId();
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

                if($source == 'foe') {
					$playerfid = $playerteam[0]->getFfbPlayerfids();
					if($playerfid) {
						$playerfid_name_foe = $playerfid[0]->getPlayerfidNameFoe();
					} else {
						$playerfid_name_foe = '';
					}
					$players_found[$f_loop]['playerteam_playerfid_name'] = $playerfid_name_foe;
				}

                if($player['player_foreign_id'] != 0) {
	                if(!$playerteam[0]->getFfbPlayer()->getPlayerForeignId()) {
	                    $players_found[$f_loop]['player_foreign_id'] = $player['player_foreign_id'];
	                    $players_found[$f_loop]['new_foreign_id'] = 1;
	                } else {
	                    $players_found[$f_loop]['player_foreign_id'] = $playerteam[0]->getFfbPlayer()->getPlayerForeignId();
	                    $players_found[$f_loop]['new_foreign_id'] = 0;
	                }
                } else {
					$players_found[$f_loop]['player_foreign_id'] = 0;
					$players_found[$f_loop]['new_foreign_id'] = 0;
				}

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
                //$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR ffb_player.player_foreign_id='$player_foreign_id') AND ffb_playerteam.playerteam_player_id = ffb_player.player_id";
                if($source == 'tm') {
					$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam, ffb_playerfid WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR LOWER(ffb_playerfid.playerfid_name_tm) = '$querystring' OR ffb_player.player_foreign_id='$player_foreign_id') AND ffb_playerteam.playerteam_player_id = ffb_player.player_id LIMIT 100";
                } elseif($source == 'foe') {
                	$sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam, ffb_playerfid WHERE (LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' OR LOWER(ffb_playerfid.playerfid_name_foe) = '$querystring_lnfn') AND ffb_playerteam.playerteam_player_id = ffb_player.player_id LIMIT 100";
                }
				$stmt = $con->prepare($sql);
                $stmt->execute();
                $pt = FfbPlayerteamPeer::populateObjects($stmt);
                if($pt) {
                    $pt_loop = 0;
                    foreach($pt as $playerteam) {
                        $players_found_in_db[$fidb_loop]['player_fname'] = $player['player_fname'];
                        $players_found_in_db[$fidb_loop]['player_lname'] = $player['player_lname'];
                        $players_found_in_db[$fidb_loop]['player_id'] = $playerteam->getFfbPlayer()->getPlayerId();
                        $players_found_in_db[$fidb_loop]['player_profile'] = $player['player_profile'];
                        $players_found_in_db[$fidb_loop]['playerteam_player_position'] = $player['playerteam_player_position'];
                        $players_found_in_db[$fidb_loop]['playerteam_id'] = $playerteam->getPlayerteamId();
                        $players_found_in_db[$fidb_loop]['playerteam_team_id'] = $playerteam->getPlayerteamTeamId();
                        //$team = $playerteam[0]->getFfbTeam();
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
                        if(!$playerteam->getFfbPlayer()->getPlayerForeignId()) {
                            $players_found_in_db[$fidb_loop]['player_foreign_id'] = $player['player_foreign_id'];
                            $players_found_in_db[$fidb_loop]['new_foreign_id'] = 1;
                        } else {
                            $players_found_in_db[$fidb_loop]['player_foreign_id'] = $playerteam->getFfbPlayer()->getPlayerForeignId();
                            $players_found_in_db[$fidb_loop]['new_foreign_id'] = 0;
                        }

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
                }
            }
            $loop++;
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        //$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $teamplayers = FfbPlayerteamPeer::doSelectJoinAll($criteria);
        //echo "num_teamplayers: ".count($teamplayers).'<br>';
        $players_db = array();
        $loop = 0;
        if($teamplayers) {
            foreach($teamplayers as $player) {
                $fname = $player->getFfbPlayer()->getPlayerFname();
                $lname = $player->getFfbPlayer()->getPlayerLname();
                $foreign_id = $player->getFfbPlayer()->getPlayerForeignId();
                $found = false;
				if($source == 'foe') {
					foreach($players_found as $player_f) {
	                    if(strcmp(strtolower(trim($player_f['player_fname']).' '.trim($player_f['player_lname'])), strtolower(trim($fname).' '.trim($lname)))==0 ||
	                       ($player_f['player_foreign_id'] != 0 && $player_f['player_foreign_id'] == $foreign_id) ||
						   (strcmp(strtolower(trim($player_f['playerteam_playerfid_name'])), strtolower(trim($lname).' '.trim($fname)))==0)) {
	                        $found = true;
	                        break;
	                    }
	                }

				} elseif($source == 'tm') {
	                foreach($players_found as $player_f) {
	                    if(strcmp(strtolower(trim($player_f['player_fname']).' '.trim($player_f['player_lname'])), strtolower(trim($fname).' '.trim($lname)))==0 ||
	                       ($player_f['player_foreign_id'] != 0 && $player_f['player_foreign_id'] == $foreign_id)) {
	                        $found = true;
	                        break;
	                    }
	                }
	            }
                //echo 'next<br>';
                if(!$found) {
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
                    if($player->getFfbPlayer()->getPlayerForeignId()) {
                        $players_db[$loop]['player_foreign_id'] = $player->getFfbPlayer()->getPlayerForeignId();
                    } else {
                        $players_db[$loop]['player_foreign_id'] = 0;
                    }
                    $players_db[$loop]['playerteam_player_price'] = $player->getPlayerteamPlayerPrice();
                    $players_db[$loop]['playerteam_id'] = $player->getPlayerteamId();
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

    private function parseTMUrl($tm_url) {
        $players_tm = array();
        $dateiname = $tm_url;

        $datei = fopen( $dateiname , "r" );
        $inhalt = stream_get_contents($datei);
        $player = explode('/profil.html" style="font-weight:bold;" title="', $inhalt );

        $text = '';
        $max = count($player);

        //$this->player_list = $player[1];
        //return;

        $loop = 0;
        for($i=1; $i<=$max-1; $i++) {

            //echo $player[$i]."\r\n<br>------------------".$i."-------------------<br>\r\n";
            //exit();

            //*** read names ***
            $name = substr($player[$i],strpos($player[$i], '">')+2, strpos($player[$i], '</a>')-(strpos($player[$i], '">')+2));
            $names = explode(' ',$name);
            $fname = $names[0];
            if(count($names) == 1) {
                $lname = $fname;
            } else {
                $lname = substr($name, strpos($name, ' ')+1, strlen($name)-strpos($name, ' ')+1);
            }

            $found = false;
            foreach($players_tm as $search) {
                if(strcmp(strtolower(trim($search['player_fname']).' '.trim($search['player_lname'])), strtolower(trim($fname).' '.trim($lname)))==0) {
                    $found = true;
                    break;
                }
            }
            if($found) {
                break;
            }

            //*** ***
            //*** read status ***
            $status = 0;
            if(strpos($player[$i], 'rote_karte.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'rote_karte.gif" width="14" height="12" alt="')+44, strpos($player[$i], '" title="')-(strpos($player[$i], 'rote_karte.gif" width="14" height="12" alt="')+44));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            } elseif(strpos($player[$i], 'sportgericht.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'sportgericht.gif" width="12" height="12" alt="')+46, strpos($player[$i], '" title="')-(strpos($player[$i], 'sportgericht.gif" width="12" height="12" alt="')+46));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            } elseif(strpos($player[$i], 'verletzung2.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'verletzung2.gif" width="10" height="10" alt="')+45, strpos($player[$i], '" title="')-(strpos($player[$i], 'verletzung2.gif" width="10" height="10" alt="')+45));
            } elseif(strpos($player[$i], 'gelbe_karte.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'gelbe_karte.gif" width="13" height="12" alt="')+45, strpos($player[$i], '" title="')-(strpos($player[$i], 'gelbe_karte.gif" width="13" height="12" alt="')+45));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            }
            //echo 'status: '.$status.'<br>';
            //echo 'status: '.$player[5].'<br>';
            //exit;

            //*** ***
            //*** read position ***
            $pos = substr($player[$i],strpos($player[$i], '<td style="white-space:nowrap;vertical-align:top;padding:0;border:0;">')+70, strpos($player[$i], ', ')-(strpos($player[$i], '<td style="white-space:nowrap;vertical-align:top;padding:0;border:0;">')+70));
            if(strpos($pos, ' ')) {
                $pos = substr($pos, 0, strpos($pos, ' '));
            }
            if($pos=='Torwart')
                $position = 'g';
            elseif($pos=='Abwehr')
                $position = 'd';
            elseif($pos=='Mittelfeld')
                $position = 'm';
            elseif($pos=='Sturm')
                $position = 's';
            else
                $position = $this->handlePositionError($pos);
            //*** ***
            //*** read image name ***
            //$image = substr($player[$i-1],strpos($player[$i-1], '<img src="http://www.transfermarkt.de/bilder/minifotos/')+55, strpos($player[$i-1], '" height="25" width="20" alt="')-(strpos($player[$i-1], '<img src="http://www.transfermarkt.de/bilder/minifotos/')+55));
            $profile = substr($player[$i-1],strpos($player[$i-1], '<td style="white-space:nowrap;vertical-align:top;padding:0;border:0;"><a href="/de/spieler/')+91);

            //Profil-Seite der Spieler ist derzeit auf TRansfermarkt nicht zugänglich.. :-(


            $profile_array = $this->parseProfile($profile);

            if(!$profile_array) {
                $players_tm[$loop]['player_profile'] = 'ERROR!';
            } else {
                $players_tm[$loop]['player_profile'] = 'OK';

                $image = $profile_array['image'];
            }
            //echo $image.'<br>';

            if($image == 'nobody.jpg' || $image == 'somebody.jpg' || $image == 'G/K/nobody.jpg' ||!$image) {
                $image = 0;
            }


            /*
            $image = 0;
            $players_tm[$loop]['player_profile'] = 'N/A';
            */

            //*** ***

            if($fname && $lname) {
                $text .= ' ('.$position.') '.$fname.' '.$lname.' '.$image.'<br>';
            }
            //echo $text.'<br>';

            $players_tm[$loop]['player_fname'] = str_replace("'", "´", $fname);
            $players_tm[$loop]['player_lname'] = str_replace("'", "´", $lname);
            $players_tm[$loop]['player_status_description'] = $status;
            $players_tm[$loop]['playerteam_player_position'] = $position;
            $players_tm[$loop]['playerteam_player_picture'] = $image;
            //$players_tm[$loop]['player_foreign_id'] = substr($profile, 0, strpos($profile, '/'));
            $players_tm[$loop]['player_foreign_id'] = $profile;
            //$players_tm[$loop]['player_found'] = 1;

            $loop++;
        }
        foreach($players_tm as $item) {
            $lnames[] = strtolower($item['player_lname']);
            $fnames[] = strtolower($item['player_fname']);
        }
        array_multisort($lnames, SORT_ASC, SORT_STRING, $fnames, SORT_ASC, SORT_STRING, $players_tm);

        return $players_tm;
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
        $positions = explode('<div id="kaderHeader">', $inhalt);

        $text = '';
        $max = count($positions);

		$loop = 0;
        for($i=1; $i<=$max-1; $i++) {
        	$pos = substr($positions[$i], 0, strpos($positions[$i], '</div>'));

        	if(strcmp($pos, 'Tor') == 0) {
				$player = explode('<a class="portalLink"', $positions[$i]);
				$position = 'g';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
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
				$player = explode('<a class="portalLink"', $positions[$i]);
				$position = 'd';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
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
				$player = explode('<a class="portalLink"', $positions[$i]);
				$position = 'm';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
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
				$player = explode('<a class="portalLink"', $positions[$i]);
				$position = 's';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
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
			if(strcmp($pos, 'Noch ohne Spielposition') == 0) {
				$player = explode('<a class="portalLink"', $positions[$i]);
				$position = '?';
				//echo '<br>'.$pos.' '.(count($player)-1).' Players<br>';
				for($j=1; $j<count($player); $j++) {
					$lname = substr($player[$j], strpos($player[$j], '">')+2, strpos($player[$j], '&nbsp;')-(strpos($player[$j], '">')+2));
					$fname = substr($player[$j], strpos($player[$j], '&nbsp;')+6, strpos($player[$j], '</a>')-(strpos($player[$j], '&nbsp;')+6));
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

    private function parseProfile($profile_link) {
        $profile = array();
        $dateiname = 'http://www.transfermarkt.de/de/spieler/'.$profile_link.'/profil.html';
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
        $profile['image'] = substr($inhalt,strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58, strpos($inhalt, '" width="100" height="130" alt="')-(strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58));
        //$profile['nationality'] = substr($inhalt,strpos($inhalt, '.gif" width="16" height="10" alt="-" title="')+44, strpos($inhalt, '" width="100" height="130" alt="')-(strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58));

        return $profile;
    }

    private function handlePositionError($pos) {
        if(strlen($pos)>15) {
            return 'error';
        } else {
            return $pos;
        }
    }

    public function updateName() {
        $playerteam_id = $_POST['playerteam_id'];
        $fname = $_POST['player_fname'];
        $lname = $_POST['player_lname'];
        $position = $_POST['playerteam_player_position'];
        $picture = $_POST['playerteam_player_picture'];
        $status = $_POST['player_status_description'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $answer = '';
        if($playerteam) {
            $team_id = $playerteam->getPlayerteamTeamId();
            //$playerteam->getFfbPlayer()->setPlayerFname($fname);
            //$playerteam->getFfbPlayer()->setPlayerLname($lname);
            $fid_name = $fname.' '.$lname;
            $this->updatePlayerfidName($playerteam_id, $team_id, $fname, $lname);
            //UNCOMMENT FOR POSITION SUPPORT:
            //$playerteam->setPlayerteamPlayerPosition($position);
            if($status) {
                $playerteam->getFfbPlayer()->setPlayerStatus(0);
                $playerteam->getFfbPlayer()->setPlayerStatusDescription($status);
                $answer .= 'Status updated: '.$status.'!<br>';
            }
            if($picture) {
                $picture_file = $this->grabPicture($picture);
                if($picture_file) {
                    if($this->createPicture($picture_file, $team_id, $playerteam_id)) {
                        $playerteam->setPlayerteamPlayerPicture($playerteam_id.'.jpg');
                        $answer .= 'Player Picture successfully added: '.$picture.'!<br>';
                    } else {
                        $answer .= 'Error: Could not create picture '.$picture.'!';
                    }
                } else {
                    $answer .= 'Error: Could not download picture '.$picture.'!';
                }
            }
            $playerteam->save();
            $answer .= 'Name updated: '.$fname.' '.$lname.'!<br>';
            //UNCOMMENT FOT POSITION SUPPORT:
            //$answer .= 'Name & Position updated: '.$fname.' '.$lname.' ('.$position.')!';
            $this->administration_status = STATUS_CODE_SUCCESS;
            $this->administration_answer = $answer;
        } else {
            $this->administration_error = 'Error: Could not find entry for ID '.$playerteam_id.'!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function updateImage() {
        $playerteam_id = $_POST['playerteam_id'];
        $picture = $_POST['playerteam_player_picture'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $answer = '';
        if($playerteam) {
            $team_id = $playerteam->getPlayerteamTeamId();
            $fname = $playerteam->getFfbPlayer()->getPlayerFname();
            $lname = $playerteam->getFfbPlayer()->getPlayerLname();
            $picture_file = $this->grabPicture($picture);
            if($picture_file) {
                if($this->createPicture($picture_file, $team_id, $playerteam_id)) {
                    $playerteam->setPlayerteamPlayerPicture($playerteam_id.'.jpg');
                    $playerteam->save();
                    $this->administration_answer = 'Picture for '.$fname.' '.$lname.' successfully added!<br>';
                    $this->administration_status = STATUS_CODE_SUCCESS;
                } else {
                    $this->administration_error = 'Error: Could not create picture '.$picture.'!';
                    $this->administration_status = STATUS_CODE_ERROR;
                    return;
                }
            } else {
                $this->administration_error = 'Error: Could not download picture '.$picture.'!';
                $this->administration_status = STATUS_CODE_ERROR;
                return;
            }
        } else {
            $this->administration_error = 'Error: Could not find entry for ID '.$playerteam_id.'!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function updateForeignId() {
        $playerteam_id = $_POST['playerteam_id'];
        $foreign_id = $_POST['player_foreign_id'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $answer = '';
        if($playerteam) {
            $fname = $playerteam->getFfbPlayer()->getPlayerFname();
            $lname = $playerteam->getFfbPlayer()->getPlayerLname();
            $player = $playerteam->getFfbPlayer();
            $player->setPlayerForeignId($foreign_id);
            $player->save();
            $this->administration_answer = 'FID for '.$fname.' '.$lname.' successfully updated!<br>';
            $this->administration_status = STATUS_CODE_SUCCESS;
        } else {
            $this->administration_error = 'Error: Could not find entry for ID '.$playerteam_id.'!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function updateStatusDescription() {
        $playerteam_id = $_POST['playerteam_id'];
        $status = $_POST['player_status_description'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        if($playerteam) {
            if($status) {
                $playerteam->getFfbPlayer()->setPlayerStatus(0);
                $playerteam->getFfbPlayer()->setPlayerStatusDescription($status);
            } else {
                $playerteam->getFfbPlayer()->setPlayerStatus(1);
                $playerteam->getFfbPlayer()->setPlayerStatusDescription('');
            }
            $playerteam->save();
            $this->administration_answer = 'Status updated: '.$status.' (ID '.$playerteam_id.')!<br>';
            $this->administration_status = STATUS_CODE_SUCCESS;
        } else {
            $this->administration_error = 'Error: Could not find entry for ID '.$playerteam_id.'!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function updatePlayerteamStatus() {
        $playerteam_id = $_POST['playerteam_id'];
        $status = $_POST['playerteam_status'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        if($playerteam) {
            $playerteam->setPlayerteamStatus($status);
            $playerteam->save();
            $this->administration_answer = 'Status updated: '.$status.' (ID '.$playerteam_id.')!<br>';
            $this->administration_status = STATUS_CODE_SUCCESS;
        } else {
            $this->administration_error = 'Error: Could not find entry for ID '.$playerteam_id.'!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function insertNewPlayer() {
        $answer = '';
        $playerteam_team_id = $_POST['playerteam_team_id'];
        $fname = $_POST['player_fname'];
        $lname = $_POST['player_lname'];
        $fid_name = $fname.' '.$lname;
        $foreign_id = $_POST['player_foreign_id'];
        $position = $_POST['playerteam_player_position'];
        $picture = $_POST['playerteam_player_picture'];

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $playerteam_team_id);
        $criteria->setLimit(1);
        $playerteams = FfbPlayerteamPeer::doSelect($criteria);
        if($playerteams) {
            $price = $playerteams[0]->getPlayerteamPlayerPrice();
            $nationality = $playerteams[0]->getFfbPlayer()->getPlayerNationality();
        } else {
            $this->administration_error = 'Error: There is no player in this team. Add one player manually and try again<br>';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }

        $new_player = new FfbPlayer();
        $new_player->setPlayerFname($fname);
        $new_player->setPlayerLname($lname);
        $new_player->setPlayerForeignId($foreign_id);
        $new_player->setPlayerNationality($nationality);
        $new_player->setPlayerStatus(1);
        $new_player->setPlayerStatusDescription('');
        $new_player->save();
        $player_id = $new_player->getPlayerId();

        $new_playerteam = new FfbPlayerteam();
        $new_playerteam->setPlayerteamPlayerId($player_id);
        $new_playerteam->setPlayerteamTeamId($playerteam_team_id);
        $new_playerteam->setPlayerteamPlayerPosition($position);
        $new_playerteam->setPlayerteamPlayerPrice($price);
        $new_playerteam->save();
        $playerteam_id = $new_playerteam->getPlayerteamId();
        $team_id = $playerteam_team_id;
        $this->updatePlayerfidName($playerteam_id, $team_id, $fname, $lname);
        $answer .= 'Player '.$fname.' '.$lname.' sucessfully added! ID: '.$playerteam_id.'<br>';
        $this->administration_status = STATUS_CODE_SUCCESS;
        if($picture) {
            $picture_file = $this->grabPicture($picture);
            if($picture_file) {
                if($this->createPicture($picture_file, $team_id, $playerteam_id)) {
                    $new_playerteam->setPlayerteamPlayerPicture($playerteam_id.'.jpg');
                    $new_playerteam->save();
                    $answer .= 'Picture added!<br>';
                } else {
                    $answer .= 'Error: Could not create picture '.$picture.'!';
                }
            }
        }
        $this->administration_answer = $answer;
    }

    public function insertPlayerToPlayerteam() {
        $answer = '';
        $playerteam_team_id = $_POST['playerteam_team_id'];
        $fname = $_POST['player_fname'];
        $lname = $_POST['player_lname'];
        $fid_name = $fname.' '.$lname;
        $foreign_id = $_POST['player_foreign_id'];
        $position = $_POST['playerteam_player_position'];
        $player_id = $_POST['player_id'];
        //$picture = $_POST['playerteam_player_picture'];

        $team = FfbTeamPeer::retrieveByPK($playerteam_team_id);
        if($team) {
            $price = $team->getTeamAvgPrice();
        }

        $new_playerteam = new FfbPlayerteam();
        $new_playerteam->setPlayerteamPlayerId($player_id);
        $new_playerteam->setPlayerteamTeamId($playerteam_team_id);
        $new_playerteam->setPlayerteamPlayerPosition($position);
        $new_playerteam->setPlayerteamPlayerPrice($price);
        $new_playerteam->save();
        $playerteam_id = $new_playerteam->getPlayerteamId();
        $team_id = $playerteam_team_id;
        $this->updatePlayerfidName($playerteam_id, $team_id, $fname, $lname);
        $answer .= 'Player '.$fname.' '.$lname.' sucessfully added! ID: '.$playerteam_id.'<br>';
        $this->administration_status = STATUS_CODE_SUCCESS;
        $this->administration_answer = $answer;
    }

    private function grabPicture($picture_name) {
        if($picture_name != 'nobody.jpg' && $picture_name != 'somebody.jpg') {
            $url = 'http://www.transfermarkt.de/bilder/spielerfotos/'.$picture_name;
            return (imagecreatefromjpeg($url));
        } else {
            return false;
        }
    }

    private function createPicture($picture_file, $team_id, $playerteam_id) {
        if(!is_dir('/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$team_id)) {
            mkdir('/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$team_id);
        }
        $filename = '/www/htdocs/w005c0bf/ffb_onlinetest/images/ffb/players/'.$team_id.'/'.$playerteam_id.'.jpg';
        return(imagejpeg($picture_file, $filename));
    }

    private function updatePlayerfidName($playerteam_id, $team_id, $fname, $lname) {
    	$source = $_POST['source'];
        $criteria = new Criteria();
        $criteria->add(FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, $playerteam_id);
        $criteria->setLimit(1);
        $exist_item = FfbPlayerfidPeer::doSelect($criteria);

        if($exist_item) {
            $update_item = $exist_item[0];
        } else {
            $update_item = new FfbPlayerfid();
            $update_item->setPlayerfidPlayerteamId($playerteam_id);
            $update_item->setPlayerfidTeamId($team_id);
        }
        if($source == 'tm') {
        	$fid_name = $fname.' '.$lname;
        	$update_item->setPlayerfidNameTm($fid_name);
        	$this->test = 'tm';
        } elseif($source == 'foe') {
        	$fid_name = $lname.' '.$fname;
			$update_item->setPlayerfidNameFoe($fid_name);
			$this->test = 'foe';
		} else {
			$this->test = 'nix';
		}

        $update_item->save();
    }
}
?>
