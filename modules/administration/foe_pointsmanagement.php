<?php

/**
 * ADMIN - POINTSMANAGEMENT-Klasse für Fußballösterreich;
 * Pointsmanagement
 *
 * @author Gritschacher Tobias
 * @copyright 03/2009
 * @version 0.1
 *
 */

class foe_pointsmanagement extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'foe_pointsmanagement.php';
    }

    public function __default() {
        $this->getFiles();
    }

    public function getFiles() {
        $path = './include/ul/';
        $dh = dir($path);
        while (false !== ($entry = $dh->read())) {
           echo $entry."\n";
        }
        $dh->close();
        exit();
        $tmp_name = $_FILES["foe_file"]["tmp_name"];
        //echo $tmp_name."<br>";
        //$datei = fopen( $tmp_name , "r" );
        $inhalt = file_get_contents($tmp_name);
        $this->parseUrl($inhalt);
        echo 'fertig!<br>';
        //echo $inhalt;
        exit();
    }

    public function loadPlayerlistFromUrl($param_tm_url=0, $param_team_id=0) {
        if($_POST['tm_url'])
            { $tm_url = $_POST['tm_url']; }
        else
            { $tm_url = $param_tm_url; }
        if($_POST['team_id'])
            { $team_id = $_POST['team_id']; }
        else
            { $team_id = $param_team_id; }

        $players_tm = $this->parseUrl($tm_url);
        return;

        $players_found = array();
        $loop=0;
        $f_loop=0;
        foreach($players_tm as $player) {
            //$criteria = new Criteria();
            //$criteria->add(FfbPlayerPeer::PLAYER_FNAME, trim($player['player_fname']));
            //$criteria->add(FfbPlayerPeer::PLAYER_LNAME, trim($player['player_lname']));

            //$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $player['playerteam_player_position']);
            //$criteria->setLimit(1);
            //$playerteam = FfbPlayerteamPeer::doSelectJoinAll($criteria);

            $con = Propel::getConnection('d00817fb');
            $querystring = strtolower(trim($player['player_fname']).' '.trim($player['player_lname']));
            //$querystring = str_replace("'", "´", $querystring);


            $sql = "SELECT ffb_playerteam.* FROM ffb_player, ffb_playerteam WHERE LOWER(CONCAT(CONCAT(ffb_player.player_fname,' '),ffb_player.player_lname)) = '$querystring' AND ffb_playerteam.playerteam_player_id = ffb_player.player_id";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $playerteam = FfbPlayerteamPeer::populateObjects($stmt);




            //$playerteam = FfbPlayerteamPeer::doSelectStmt($criteria);
            //print_r($playerteam);
            //exit();
            if($playerteam) {
                $players_found[$f_loop]['player_fname'] = $player['player_fname'];
                $players_found[$f_loop]['player_lname'] = $player['player_lname'];
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

                    /*
                    if($playerteam[0]->getPlayerteamPlayerPicture() == $player['playerteam_player_picture']) {
                        $players_found[$f_loop]['playerteam_player_picture'] = $playerteam[0]->getPlayerteamPlayerPicture();
                        $players_found[$f_loop]['new_image'] = 0;
                    } else {
                        $players_found[$f_loop]['playerteam_player_picture'] = $player['playerteam_player_picture'];
                        $players_found[$f_loop]['new_image'] = 1;
                    }
                    */
                } else {
                    if($player['playerteam_player_picture']) {
                        $players_found[$f_loop]['playerteam_player_picture'] = $player['playerteam_player_picture'];
                        $players_found[$f_loop]['new_image'] = 1;
                    } else {
                        $players_found[$f_loop]['playerteam_player_picture'] = 0;
                        $players_found[$f_loop]['new_image'] = 0;
                    }
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
            }
            $loop++;
            //print_r($players_found);
            //exit();
        }

        $criteria = new Criteria();
        $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $team_id);
        //$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        $teamplayers = FfbPlayerteamPeer::doSelectJoinAll($criteria);
        $players_db = array();
        $loop = 0;
        if($teamplayers) {
            foreach($teamplayers as $player) {
                $fname = $player->getFfbPlayer()->getPlayerFname();
                $lname = $player->getFfbPlayer()->getPlayerLname();
                $found = false;

                foreach($players_found as $player_f) {
                    if(strcmp(strtolower(trim($player_f['player_fname']).' '.trim($player_f['player_lname'])), strtolower(trim($fname).' '.trim($lname)))==0) {
                        $found = true;
                        break;
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

        $this->playerlist_tm = $players_tm;
        $this->playerlist_db = $players_db;
        $this->playerlist_found = $players_found;
    }

    public function testParser() {
        $this->parseUrl('http://www.transfermarkt.at/de/nationalmannschaft/3448/russland/uebersicht/startseite.print');
        //$this->parseUrl('http://www.transfermarkt.at/de/nationalmannschaft/3864/wales/uebersicht/startseite.print');
        echo 'fertig!';
        exit;
    }

    public function testPlayermanagement() {
        $this->loadPlayerlistFromUrl('http://www.fussballoesterreich.at/netzwerk/druck/379402779304830775_100071-489819841075944817.htm?awVerband=K_');
        //$this->loadPlayerlistFromUrl('http://www.fussballoesterreich.at/netzwerk/spieldetails/379402779304830775_100071-489819841075944817.htm?awVerband=K_&show=bericht');
        echo 'fertig!';
        exit();
    }

    private function parseTeam($string) {
        $players = explode("<tr class='kalenderzeile", $string);
        $players_foe = array();
        //echo count($players).'<br>';
        $loop = 0;
        for($i=1;$i<count($players);$i++) {
            //$name = substr($players[$i], strpos($players[$i], "<td align='left'>")+17, strpos($players[$i], "</td>")-strpos($players[$i], "<td align='left'>"));
            $blank = strip_tags($players[$i]);
            $tags = explode("\n", trim($blank));
            if(strpos($tags[6], 'HZ')) {
                $tags[6] = 45;
            }
            if(strpos($tags[7], 'HZ')) {
                $tags[7] = 45;
            }
            $players_foe[$loop]['player_number'] = $tags[1];
            $players_foe[$loop]['player_name'] = $tags[2];
            if(strpos($tags[3], 'nbsp')) {
                $players_foe[$loop]['player_captain'] = 0;
            } else {
                $players_foe[$loop]['player_captain'] = 1;
            }
            if(strpos($tags[4], 'nbsp')) {
                $players_foe[$loop]['player_foreign'] = 0;
            } else {
                $players_foe[$loop]['player_foreign'] = 1;
            }
            if(strpos($tags[5], 'nbsp')) {
                $players_foe[$loop]['player_similar'] = 0;
            } else {
                $players_foe[$loop]['player_similar'] = 1;
            }
            if(strpos($tags[6], 'nbsp')) {
                $players_foe[$loop]['player_out'] = 90;
            } else {
                $players_foe[$loop]['player_out'] = $tags[6];
            }
            if(strpos($tags[7], 'nbsp')) {
                $players_foe[$loop]['player_in'] = 0;
            } else {
                $players_foe[$loop]['player_in'] = $tags[7];
            }
            if(strpos($tags[8], 'nbsp')) {
                $players_foe[$loop]['player_card_y'] = 0;
            } else {
                $players_foe[$loop]['player_card_y'] = $tags[8];
            }
            if(strpos($tags[9], 'nbsp')) {
                $players_foe[$loop]['player_card_yr'] = 0;
            } else {
                $players_foe[$loop]['player_card_yr'] = $tags[9];
            }
            if(strpos($tags[10], 'nbsp')) {
                $players_foe[$loop]['player_card_r'] = 0;
            } else {
                $players_foe[$loop]['player_card_r'] = $tags[10];
            }
            if(strpos($tags[11], 'nbsp')) {
                $players_foe[$loop]['player_goals'] = 0;
            } else {
                $players_foe[$loop]['player_goals'] = count(explode(',', trim($tags[11])));
            }
            $players_foe[$loop]['player_minutes'] = $players_foe[$loop]['player_out']-$players_foe[$loop]['player_in'];

            //echo $tags[3].'<br>';
            //echo '*** start ***<br>'.$players[$i].'*** end ***<br>';
            $loop++;
        }
        return $players_foe;
    }

    private function parseUrl($inhalt) {
        $teams = explode('Tore', $inhalt );
        $home_team = substr($teams[1], 0, strpos($teams[1], '</table>'));
        $guest_team = substr($teams[2], 0, strpos($teams[2], '</table>'));
        $subs = substr($teams[2], strpos($teams[2], "<table width='100%'>"), strpos($teams[2], "<td valign='top'>"));
        //echo '---***---<br>'.$guest_team.'<br>';
        $players_foe_home = $this->parseTeam($home_team);
        $players_foe_guest = $this->parseTeam($guest_team);

        foreach($players_foe_home as $player) {
            echo $player['player_name'].'<br>';
            echo $player['player_minutes'].' Minuten'.'<br>';
            echo $player['player_goals'].' Tore<br>';
        }

        return;


        $max = count($teams);
        echo $max.'<br>';
        //return;

        $loop = 0;
        for($i=1; $i<=$max-1; $i++) {

            echo $player[$i]."\r\n<br>------------------".$i."-------------------<br>\r\n";
            echo $teams[$i];
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
            $image = $this->parseProfile($profile);
            //echo $image.'<br>';

            if($image == 'nobody.jpg' || $image == 'somebody.jpg') {
                $image = 0;
            }
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

    private function parseProfile($profile_link) {
        $dateiname = 'http://www.transfermarkt.de/de/spieler/'.$profile_link.'/profil.html';
        $datei = fopen( $dateiname , "r" );
        $inhalt = stream_get_contents($datei);
        $image = substr($inhalt,strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58, strpos($inhalt, '" width="100" height="130" alt="')-(strpos($inhalt, '<img src="http://www.transfermarkt.de/bilder/spielerfotos/')+58));
        return $image;
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
            $playerteam->getFfbPlayer()->setPlayerFname($fname);
            $playerteam->getFfbPlayer()->setPlayerLname($lname);
            //UNCOMMENT FOT POSITION SUPPORT:
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
        $new_player->setPlayerForeignId('');
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
}
?>
