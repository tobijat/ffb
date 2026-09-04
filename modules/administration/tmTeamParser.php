<?php

/**
 * ADMIN - PLAYERMANAGEMENT-Klasse;
 * Transfermarkt Team Parser
 *
 * @author Gritschacher Tobias
 * @copyright 04/2010
 * @version 0.2
 *
 */

class tmTeamParser extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'tmTeamParser.php';
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
        } elseif($source == 'wf') {
        	$players_tm = $this->parseWFUrl($tm_url);
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
        	$querystring_fnln = strtolower(trim($player['player_fname']).' '.trim($player['player_lname']));
            $querystring_lnfn = strtolower(trim($player['player_lname']).' '.trim($player['player_fname']));

            $cr = new Criteria();
			$cr->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
			$cr->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, Criteria::INNER_JOIN);
			$c0 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_FNAME, trim($player['player_fname']));
			$c1 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_LNAME, trim($player['player_lname']));
			$c0->addAnd($c1);
			if(strcmp('tm', $source) == 0) {
				$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_TM, $querystring_fnln);
			} elseif(strcmp('wf', $source) == 0) {
				$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_WF, $querystring_fnln);
			} elseif(strcmp('foe', $source) == 0) {
				$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $querystring_lnfn);
			}
			$c2->addOr($c0);
			$cr->add($c2);
			$cr->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
			$cr->setLimit(1);
			$cr->setIgnoreCase(true);
			$playerteam = FfbPlayerteamPeer::doSelect($cr);

            if($playerteam) {
            //uncomment to see inconsistencies between positions on WF and in the DB
            //if($playerteam && $playerteam[0]->getPlayerteamPlayerPosition() == $player['playerteam_player_position']) {
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

				//uncomment to not update pictures when there is already a picture file
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
					$playerfid_name_tm = $playerfid[0]->getPlayerfidNameTm();
					$playerfid_name_wf = $playerfid[0]->getPlayerfidNameWf();
					$playerfid_name_foe = $playerfid[0]->getPlayerfidNameFoe();
				} else {
					$playerfid_name_tm = '';
					$playerfid_name_wf = '';
					$playerfid_name_foe = '';
				}
				if(strcmp('tm', $source) == 0) {
					$players_found[$f_loop]['playerteam_playerfid_name'] = $playerfid_name_tm;
				} elseif(strcmp('wf', $source) == 0) {
					$players_found[$f_loop]['playerteam_playerfid_name'] = $playerfid_name_wf;
				} elseif(strcmp('foe', $source) == 0) {
					$players_found[$f_loop]['playerteam_playerfid_name'] = $playerfid_name_foe;
				}

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
                if(strcmp('wf', $source) == 0) {
					$players_found[$f_loop]['player_status_update'] = 0;
				}

                unset($players_tm[$loop]);
                $f_loop++;
            } else {
            	//player not found in selected team - perhaps we'll find him in another team

                $cr = new Criteria();
				$cr->addJoin(FfbPlayerPeer::PLAYER_ID, FfbPlayerteamPeer::PLAYERTEAM_PLAYER_ID, Criteria::INNER_JOIN);
				$cr->addJoin(FfbPlayerteamPeer::PLAYERTEAM_ID, FfbPlayerfidPeer::PLAYERFID_PLAYERTEAM_ID, Criteria::INNER_JOIN);
				$c0 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_FNAME, trim($player['player_fname']));
				$c1 = $cr->getNewCriterion(FfbPlayerPeer::PLAYER_LNAME, trim($player['player_lname']));
				$c0->addAnd($c1);
				if(strcmp('tm', $source) == 0) {
					$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_TM, $querystring_fnln);
				} elseif(strcmp('wf', $source) == 0) {
					$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_WF, $querystring_fnln);
				} elseif(strcmp('foe', $source) == 0) {
					$c2 = $cr->getNewCriterion(FfbPlayerfidPeer::PLAYERFID_NAME_FOE, $querystring_lnfn);
				}
				$c2->addOr($c0);
				$cr->add($c2);
				$cr->setLimit(20);
				$cr->setIgnoreCase(true);
				$pt = FfbPlayerteamPeer::doSelect($cr);

                if($pt) {
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
                        if(strcmp('wf', $source) == 0) {
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
    }

    public function testParser() {
        //$this->parseTMUrl('http://www.transfermarkt.at/de/russland/startseite/nationalmannschaft_3448.html');
        //$this->parseUrl('http://www.transfermarkt.at/de/nationalmannschaft/3864/wales/uebersicht/startseite.print');
        $this->parseWFUrl('http://www.weltfussball.at/teams/deutschland-team/wm-2010-in-suedafrika/2/');
        echo 'fertig!';
        exit;
    }

    public function testPlayermanagement() {
        //$this->loadPlayerlistFromUrl('http://www.transfermarkt.at/de/nationalmannschaft/3379/niederlande/uebersicht/startseite.print', 12, 'tm');
        //$this->loadPlayerlistFromUrl('http://ffb.tobijat.at/resource/foe_teamdata/lind.html', 32, 'foe');
        $this->loadPlayerlistFromUrl('http://www.weltfussball.at/teams/katar-team/freundschaft-2010/2/', 130, 'wf');
        echo 'fertig!';
        exit();
    }

    private function parseTMUrl($tm_url) {
        $players_tm = array();
        $dateiname = $tm_url;

        $datei = fopen( $dateiname , "r" );
        $inhalt = stream_get_contents($datei);
        //$player = explode('<td class="al">', $inhalt );
        $player = explode('<tr class="transparent">', $inhalt );

        $text = '';
        $max = count($player);
		//echo 'max: '.$max.'<br>';

        $loop = 0;
        for($i=1; $i<=$max-1; $i++) {
            //*** read profile link ***
            $plink = substr($player[$i], strpos($player[$i], '<td><a href="')+13, strpos($player[$i], '" class="fb s10"')-(strpos($player[$i], '<td><a href="')+13));
            //echo 'plink: '.$plink.'<br>';
            //*** ***

            //*** read names ***
            //$name = substr($player[$i],strpos($player[$i], '">')+2, strpos($player[$i], '</a>')-(strpos($player[$i], '">')+2));
            $name = substr($player[$i],strpos($player[$i], 'width="20" alt="')+16, strpos($player[$i], '" title="')-(strpos($player[$i], 'width="20" alt="')+16));
            //echo 'name: '.$name.'<br>';
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
                $status = substr($player[$i], strpos($player[$i], 'rote_karte.gif" width="13" height="12" alt="')+44, strpos($player[$i], '" title="', strpos($player[$i], 'rote_karte.gif'))-(strpos($player[$i], 'rote_karte.gif" width="13" height="12" alt="')+44));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            } elseif(strpos($player[$i], 'gelbe_karte.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'gelbe_karte.gif" width="13" height="12" alt="')+45, strpos($player[$i], '" title="', strpos($player[$i], 'gelbe_karte.gif'))-(strpos($player[$i], 'gelbe_karte.gif" width="13" height="12" alt="')+45));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            } elseif(strpos($player[$i], 'sportgericht.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'sportgericht.gif" width="12" height="12" alt="')+46, strpos($player[$i], '" title="', strpos($player[$i], 'sportgericht.gif'))-(strpos($player[$i], 'sportgericht.gif" width="12" height="12" alt="')+46));
                if(!strpos($status, 'WM')) {
                    $status = 0;
                }
            } elseif(strpos($player[$i], 'verletzung2.gif')) {
                $status = substr($player[$i], strpos($player[$i], 'verletzung2.gif" alt="')+22, strpos($player[$i], '" title="', strpos($player[$i], 'verletzung2.gif" alt="'))-(strpos($player[$i], 'verletzung2.gif" alt="')+22));
            }
            //echo 'status: '.$status.'<br>';
            //*** ***

            //*** read position ***
            $pos = substr($player[$i],strpos($player[$i], '<td class="s10">')+16, strpos($player[$i], '</td>', strpos($player[$i], '<td class="s10">'))-(strpos($player[$i], '<td class="s10">')+16));
			if(strpos($pos, ' ')) {
                $pos = substr($pos, 0, strpos($pos, ' '));
            }
            //echo 'pos: '.$pos.'<br>';
            if($pos=='Torwart') {
                $position = 'g';
            } elseif($pos=='Abwehr') {
                $position = 'd';
            } elseif($pos=='Mittelfeld') {
                $position = 'm';
            } elseif($pos=='Sturm') {
                $position = 's';
            } else {
				$position = 'ERROR';
			}
            //echo 'pos: '.$position.'<br>';
            //*** ***

            //*** read image name ***
            $image = substr($player[$i],strpos($player[$i], '<img src="http://www.transfermarkt.de/bilder/minifotos/')+55, strpos($player[$i], '" class', strpos($player[$i], '<img src="http://www.transfermarkt.de/bilder/minifotos/'))-(strpos($player[$i], '<img src="http://www.transfermarkt.de/bilder/minifotos/')+55));

			/*
			$profile_link = "http://www.transfermarkt.at".$plink;
            $profile_array = $this->parseProfile($profile_link);
            if(!$profile_array) {
                $players_tm[$loop]['player_profile'] = 'ERROR!';
            } else {
                $players_tm[$loop]['player_profile'] = 'OK';
                $nationality = $profile_array['nationality'];
                echo 'nat: '.$nationality.'<br>';
            }
            */

            if($image == 'nobody.jpg' || $image == 'somebody.jpg' || $image == 'G/K/nobody.jpg' || !$image) {
                $image = 0;
            }
            //echo 'image: '.$image.'<br>';
            //*** ***

            if($fname && $lname) {
                $text = ' ('.$position.') '.$fname.' '.$lname.' '.$image.'<br>';
            }
            //echo 'T: '.$text.'<br>';

            $players_tm[$loop]['player_fname'] = str_replace("'", "´", $fname);
            $players_tm[$loop]['player_lname'] = str_replace("'", "´", $lname);
            $players_tm[$loop]['player_status_description'] = $status;
            $players_tm[$loop]['playerteam_player_position'] = $position;
            $players_tm[$loop]['playerteam_player_picture'] = $image;
            //$players_tm[$loop]['player_foreign_id'] = substr($profile, 0, strpos($profile, '/'));
            //$players_tm[$loop]['player_foreign_id'] = $profile;
            $players_tm[$loop]['player_foreign_id'] = '-';
            $players_tm[$loop]['player_profile'] = 'OK';
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


	private function normalizeString($string){
		$string = str_replace("\t", "", trim($string));
		$string = str_replace("\r", "", trim($string));
		$string = str_replace("\n", "", trim($string));

		return $string;
	}

    private function parseWFPlayers($player, $position) {
        $text = '';
        $max = count($player);

        $loop = 0;
        $players = array();
        for($i=1; $i<=$max-2; $i++) {
			$player[$i] = $this->normalizeString($player[$i]);
			$parts = explode("</td>", $player[$i]);
			//print_r($parts);
			//print $player[$i];
			//die();
            //*** read profile link ***
            //$plink = substr($player[$i], strpos($player[$i], 'href="')+6, strpos($player[$i], '" ', strpos($player[$i], 'href="')+6)-(strpos($player[$i], 'href="')+6));
            $plink = substr($parts[2], strpos($parts[2], 'href="')+6, strpos($parts[2], '" ', strpos($parts[2], 'href="')+6)-(strpos($parts[2], 'href="')+6));
            //echo 'plink: '.$plink.'<br>';
			//die();
            //*** ***

            //*** read fid ***
            $fid = substr($plink, strpos($plink, 'spieler_profil/')+15, strpos($plink, '/', strpos($plink, 'spieler_profil/')+15)-(strpos($plink, 'spieler_profil/')+15));
            //echo 'fid: '.$fid.'<br>';
			//*** ***

            //*** read names ***
            //$name = trim(substr($player[$i], strpos($player[$i], '">', strpos($player[$i], 'href="')+6)+2, strpos($player[$i], '</a>', strpos($player[$i], 'href="')+6)-(strpos($player[$i], '">', strpos($player[$i], 'href="')+6)+2)));
            $name = trim(substr($parts[2], strpos($parts[2], '">', strpos($parts[2], 'href="')+6)+2, strpos($parts[2], '</a>', strpos($parts[2], 'href="')+6)-(strpos($parts[2], '">', strpos($parts[2], 'href="')+6)+2)));
            //echo 'name: '.$name.'<br>';
            $names = explode(' ',$name);
            $fname = $names[0];
            if(count($names) == 1) {
                $lname = $fname;
            } else {
                $lname = substr($name, strpos($name, ' ')+1, strlen($name)-strpos($name, ' ')+1);
            }

            //*** read profile ***
            //if(strpos($player[$i], 'shared/foto.gif')) {
            if(strpos($parts[0], '//s.weltsport.net/bilder')) {
                //print("profile!");
				$profile_array = $this->parseWFProfile($plink);
			} else {
                //print("no profile!");
				$profile_array = 0;
			}

			//*** read image name ***
			if($profile_array) {
				$image = $profile_array['image'];
			} else {
				$image = 0;
			}

            if($fname && $lname) {
                $text = ' ('.$position.') '.$fname.' '.$lname.' '.$image.'<br>';
            }
            //echo 'T: '.$text.'<br>';

            $players[$loop]['player_fname'] = str_replace("'", "´", $fname);
            $players[$loop]['player_lname'] = str_replace("'", "´", $lname);
            $players[$loop]['player_status_description'] = 0;
            $players[$loop]['playerteam_player_position'] = $position;
            $players[$loop]['playerteam_player_picture'] = $image;
            $players[$loop]['player_foreign_id'] = $fid;
            $players[$loop]['player_profile'] = 'OK';

            $loop++;
        }
        return $players;
	}

    private function parseWFUrl($url) {
        $players_tm = array();
        $dateiname = $url;

        $datei = fopen( $dateiname , "r" );
        $inhalt = stream_get_contents($datei);
        $inhalt = substr($inhalt, strpos($inhalt, '</head>')+7);
        $goalie_string = substr($inhalt, strpos($inhalt, '<b>Torh')+7, strpos($inhalt, '<b>Abwehr</b>')-(strpos($inhalt, '<b>Torh')+7));
        $players_g = explode('<tr>', trim($goalie_string));
        $defense_string = substr($inhalt, strpos($inhalt, '<b>Abwehr</b>')+13, strpos($inhalt, '<b>Mittelfeld</b>')-(strpos($inhalt, '<b>Abwehr</b>')+13));
        $players_d = explode('<tr>', trim($defense_string));
        $midfield_string = substr($inhalt, strpos($inhalt, '<b>Mittelfeld</b>')+17, strpos($inhalt, '<b>Sturm</b>')-(strpos($inhalt, '<b>Mittelfeld</b>')+17));
        $players_m = explode('<tr>', trim($midfield_string));
        if(strpos($inhalt, '<b>Trainer</b>')) {
        	$striker_string = substr($inhalt, strpos($inhalt, '<b>Sturm</b>')+12, strpos($inhalt, '<b>Trainer</b>')-(strpos($inhalt, '<b>Sturm</b>')+12));
        	$players_s = explode('<tr>', trim($striker_string));
		} else {
			$striker_string = substr($inhalt, strpos($inhalt, '<b>Sturm</b>')+12, strpos($inhalt, '</table>', strpos($inhalt, '<b>Sturm</b>')+12)-(strpos($inhalt, '<b>Sturm</b>')+12));
			$players_s = explode('<tr>', trim($striker_string));
			$players_s[] = 'dummy';
		}

		/*
        echo count($players_g).'<br>';
        echo count($players_d).'<br>';
        echo count($players_m).'<br>';
        echo count($players_s).'<br>';
        */

        //echo $players_d[3];

		$players_tm = $this->parseWFPlayers($players_g, 'g');

		//print_r($this->parseWFPlayers($players_d, 'd'));
		//die();

		$players_tm = array_merge($players_tm, $this->parseWFPlayers($players_d, 'd'));
        $players_tm = array_merge($players_tm, $this->parseWFPlayers($players_m, 'm'));
        $players_tm = array_merge($players_tm, $this->parseWFPlayers($players_s, 's'));

        foreach($players_tm as $item) {
            $lnames[] = strtolower($item['player_lname']);
            $fnames[] = strtolower($item['player_fname']);
        }
        array_multisort($lnames, SORT_ASC, SORT_STRING, $fnames, SORT_ASC, SORT_STRING, $players_tm);

        //echo count($players_tm).'<br>';
        //print_r($players_tm);

        return $players_tm;
    }

    private function parseTMProfile($profile_link) {
        $profile = array();
        $dateiname = $profile_link;
        $datei = @fopen( $dateiname , "r" );

        //check if Profile-Page has loaded successfully
        if(!$datei)
            return false;

        $inhalt = stream_get_contents($datei);
        //$profile['image'] = substr($inhalt,strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58, strpos($inhalt, '" width="100" height="130" alt="')-(strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58));
        $nationality = substr($inhalt,strpos($inhalt, '<td>Nationalit&auml;t:</td>')+27, strpos($inhalt, '/>', strpos($inhalt, '<td>Nationalit&auml;t:</td>'))-(strpos($inhalt, '<td>Nationalit&auml;t:</td>')+27));
		$profile['nationality'] = substr($nationality, strpos($nationality, 'title="')+7, strpos($nationality, '>', strpos($nationality, 'title="'))-(strpos($nationality, 'title="')+7));
		echo 'nat: '.$profile['nationality'].'<br>';
        return $profile;
    }

    public function testProfile() {
		$this->parseWFProfile('http://www.weltfussball.at/spieler_profil/julio-manzur/');
		exit();
	}

    private function parseWFProfile($profile_link) {
        $profile = array();
        $dateiname = 'http://www.weltfussball.at/' . $profile_link;
        $datei = @fopen( $dateiname , "r" );

        //check if Profile-Page has loaded successfully
        if(!$datei)
            return false;

        $inhalt = $this->normalizeString(stream_get_contents($datei));

		if(strpos($inhalt, '<div class="data" itemprop="image">') !== false) {
        	$tmp_string = substr($inhalt, strpos($inhalt, '<div class="data" itemprop="image">')+35);
        	$profile['image'] = substr($tmp_string, strpos($tmp_string, '<img src="')+10, strpos($tmp_string, '" ', strpos($tmp_string, '<img src="')+10)-(strpos($tmp_string, '<img src="')+10));
		} else {
			//no picture
			$profile['image'] = 0;
		}
		return $profile;
    }
}
?>
