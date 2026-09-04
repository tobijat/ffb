<?php

/**
 * ADMIN - PLAYER-Klasse;
 * Player hinzufügen/ändern/löschen
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 */

class player extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        require_once('ffb/FfbPlayer.php');
        $this->htmlFile = 'player.php';
    }

    public function __default() {
        $this->administration_modus = $_POST['administration_modus'];
        $this->post = $_POST;
        if (!empty($_POST)) {
            if(isset($_POST['player_administration_change_x']) || isset($_POST['player_administration_change']))
                { $this->changeItem($_POST['player_id']); }
            elseif(isset($_POST['player_administration_delete_x']) || isset($_POST['player_administration_delete']))
            {
                if($this->validateDelete($_POST['player_id']))
                    $this->deleteItem($_POST['player_id']);
                else {
                    $errors = array();
                }
            } elseif(isset($_POST['player_administration_file_p'])) {
                if($this->validateImportP())
                    { $this->importP(); }
                else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }  elseif(isset($_POST['player_administration_file_ptt'])) {
                if($this->validateImportPtt())
                    { $this->importPtt(); }
                else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }

            } else {
                if($this->validate()) {
                    if(isset($_POST['player_administration_insert']))
                        { $this->addItem(); }
                    elseif(isset($_POST['player_administration_update']))
                        { $this->updateItem($_POST['player_id']); }
                } else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }
        }
        $this->getList();
    }

    //gesamte Player-Liste holen
    public function getList() {
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbPlayerPeer::PLAYER_ID);
        $criteria->setLimit(100);
        $list = FfbPlayerPeer::doSelect($criteria);
        $players = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $players[$i]['player_id'] = $item->getPlayerId();
                $players[$i]['player_fname'] = $item->getPlayerFname();
                $players[$i]['player_lname'] = $item->getPlayerLname();
                $players[$i]['player_nationality'] = $item->getPlayerNationality();
                $players[$i]['player_status'] = $item->getPlayerStatus();
                if($item->getPlayerStatusDescription() != 'NULL')
                    { $players[$i]['player_status_description'] = $item->getPlayerStatusDescription(); }
                $playerteam = $item->getFfbPlayerteams();
                if($playerteam) {
                    $players[$i]['player_team_name'] = $playerteam[0]->getFfbTeam()->getTeamName();
                } else {
                    $players[$i]['player_team_name'] = 'no team';
                }
                $i++;
            }
        }
        $this->numResults = $i;
        $this->players = $players;
    }

    //gesamte Player-Liste holen
    public function getPartList() {
        $criteria = new Criteria();
        $criteria->setIgnoreCase(true);

        if($_POST['player_search']) {
            $criteria->add(FfbPlayerPeer::PLAYER_LNAME, '%'.$_POST['player_search'].'%', Criteria::LIKE);
        }
        if($_POST['player_nationality']) {
            $criteria->add(FfbPlayerPeer::PLAYER_NATIONALITY, strtoupper($_POST['player_nationality']));
        }
        if($_POST['player_id']) {
            $criteria->add(FfbPlayerPeer::PLAYER_ID, $_POST['player_id'], Criteria::GREATER_THAN);
        }
        if($_POST['player_team']) {
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $_POST['player_team']);
        }
        if($_POST['player_limit']) {
            $criteria->setLimit($_POST['player_limit']);
        }
        if($_POST['player_sort']) {
            if($_POST['player_sort'] == 'name') {
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
            } elseif($_POST['player_sort'] == 'id_asc') {
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_ID);
            } elseif($_POST['player_sort'] == 'id_desc') {
                $criteria->addDescendingOrderByColumn(FfbPlayerPeer::PLAYER_ID);
            } elseif($_POST['player_sort'] == 'nat') {
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_NATIONALITY);
            } else {
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
                $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
            }
        } else {
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
        }
        //$criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
        //$criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);

        //$list = FfbPlayerPeer::doSelect($criteria);
        $list = FfbPlayerteamPeer::doSelectJoinFfbPlayer($criteria);
        $players = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $players[$i]['player_id'] = $item->getFfbPlayer()->getPlayerId();
                $players[$i]['player_fname'] = $item->getFfbPlayer()->getPlayerFname();
                $players[$i]['player_lname'] = $item->getFfbPlayer()->getPlayerLname();
                $players[$i]['player_nationality'] = $item->getFfbPlayer()->getPlayerNationality();
                $players[$i]['player_status'] = $item->getFfbPlayer()->getPlayerStatus();

                if($item->getFfbPlayer()->getPlayerStatusDescription() && $item->getFfbPlayer()->getPlayerStatusDescription() != 'NULL')
                    { $players[$i]['player_status_description'] = $item->getFfbPlayer()->getPlayerStatusDescription(); }
                else
                    { $players[$i]['player_status_description'] = '-'; }

                $players[$i]['player_team_name'] = $item->getFfbTeam()->getTeamName();
                $players[$i]['playerteam_price'] = $item->getPlayerteamPlayerPrice();
                $players[$i]['playerteam_position'] = $item->getPlayerteamPlayerPosition();
                $players[$i]['playerteam_picture'] = $item->getPlayerteamPlayerPicture();
                $players[$i]['playerteam_status'] = $item->getPlayerteamStatus();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->players = $players;
    }

    //einen Player ändern - bestehende Daten holen
    private function changeItem($id) {
        $player = array();
        if($id) {
            $item = FfbPlayerPeer::retrieveByPK($id);
            if($item) {
                $player['player_id'] = $item->getPlayerId();
                $player['player_fname'] = $item->getPlayerFname();
                $player['player_lname'] = $item->getPlayerLname();
                $player['player_nationality'] = $item->getPlayerNationality();
                $player['player_status'] = $item->getPlayerStatus();
                if($item->getPlayerStatusDescription() != 'NULL')
                    { $player['player_status_description'] = $item->getPlayerStatusDescription(); }
            }
        }
        $this->post = $player;
        $this->administration_modus = 'update';
    }

    //neuen Player hinzufügen
    private function addItem() {
        $new_item = new FfbPlayer();
        $new_item->setPlayerLname($_POST['player_lname']);
        $new_item->setPlayerFname($_POST['player_fname']);
        $new_item->setPlayerNationality($_POST['player_nationality']);
        $new_item->setPlayerStatus($_POST['player_status']);
        $new_item->setPlayerStatusDescription($_POST['player_status_description']);
        $new_item->save();
        $player_id = $new_item->getPlayerId();
        $new_pt_item = new FfbPlayerteam();
        $new_pt_item->setPlayerteamPlayerId($player_id);
        $new_pt_item->setPlayerteamTeamId($_POST['player_team']);
        $new_pt_item->setPlayerteamPlayerPosition($_POST['playerteam_position']);
        $new_pt_item->setPlayerteamPlayerPrice($_POST['playerteam_price']);
        $new_pt_item->setPlayerteamPlayerPicture('');
        $new_pt_item->setPlayerteamStatus($_POST['playerteam_status']);
        $new_pt_item->setPlayerteamDateTransfer('2008-01-01 00:00:00');
        $new_pt_item->save();
        if($_POST['playerteam_picture']) {
            $insert_id = $new_pt_item->getPlayerteamId();
            $new_pt_item->setPlayerteamPlayerPicture($insert_id.'.jpg');
            $new_pt_item->save();
        }
        $this->administration_answer = 'New Player successfully added!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;

    }

    //bestehenden Player updaten
    private function updateItem($id) {
        $exist_item = FfbPlayerPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setPlayerLname($_POST['player_lname']);
            $exist_item->setPlayerFname($_POST['player_fname']);
            $exist_item->setPlayerNationality($_POST['player_nationality']);
            $exist_item->setPlayerStatus($_POST['player_status']);
            $exist_item->setPlayerStatusDescription($_POST['player_status_description']);
            $exist_item->save();
            $this->administration_answer = 'Existing Player successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbPlayerPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $errors[] = 'Player not found! Wrong ID or site reloaded?';
            $this->errors = $errors;
            return false;
        }
        $playerteams = $item->getFfbPlayerteams();
        $return = true;
        foreach($playerteams as $pt_item) {
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId1()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId2()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId3()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId4()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId5()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId6()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId7()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId8()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId9()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId10()) {
                $return = false;
            }
            if($pt_item->getFfbUserteamsRelatedByUserteamPlayerId11()) {
                $return = false;
            }
        }
        if(!$return) {
            $errors[] = 'This player is used by userteams - you cannot delete this player!';
            $this->errors = $errors;
        }
        return $return;
    }

    //Player löschen
    private function deleteItem($id) {
        $item = FfbPlayerPeer::retrieveByPK($id);

        FfbPlayerPeer::doDelete($item);
        $this->administration_answer = 'Existing Player successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //Formular validieren
    private function validate() {
        $errors = array();
        if($_POST['player_administration_update']) {
            //check for empty fields
            if (empty($_POST) || !$_POST['player_lname'] || !$_POST['player_fname'] || !$_POST['player_nationality'])
            {
                $errors[] = 'You have to fill out all fields marked with a *!';
            }
        } elseif($_POST['player_administration_insert']) {
            //echo 'insert<br>';
            //check for empty fields
            if (empty($_POST) || !$_POST['player_lname'] || !$_POST['player_fname'] || !$_POST['player_nationality'] || !$_POST['player_team']
                              || !$_POST['playerteam_price'] || !$_POST['playerteam_position'])
            {
                $errors[] = 'You have to fill out all fields marked with a *!';
            }

            //check for existing playername
            if($_POST['player_lname'] && $_POST['player_fname'] && $_POST['player_nationality'] && $_POST['player_team'] && $_POST['playerteam_price']
                && $_POST['playerteam_position']) {
                //echo $_POST['player_team'].' jaja<br>';
                $criteria = new Criteria();
                $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $_POST['player_fname']);
                $criteria->add(FfbPlayerPeer::PLAYER_LNAME, $_POST['player_lname']);
                $criteria->add(FfbPlayerPeer::PLAYER_NATIONALITY, $_POST['player_nationality']);
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $_POST['player_team']);
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $_POST['playerteam_position']);
                $exist_item = FfbPlayerteamPeer::doSelectJoinAll($criteria);
                if($exist_item) {
                    $errors[] = 'A Player with this name, nationality and position is already existing in this team!';
                }
            }
        }

        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }

    //Import-File für PLayerToTeam validieren
    private function validateImportPtt() {
        $errors = array();

        if($_FILES['player_file_ptt']['name']) {
            $filename = $_FILES['player_file_ptt']['tmp_name'];
            $file = fopen( $filename , "r" );
            $content = utf8_encode(fread($file ,filesize($filename)));

            $playerlines = explode(';', trim($content));
            if(count($playerlines)) {
                $line_counter = 1;
                foreach($playerlines as $line) {
                    $elements = explode(',', trim($line));
                    if(count($elements) == 9) {
                        if(!$elements[0]) {
                            $errors[] = 'You must enter a last name for every player! Line '.$line_counter;
                            break;
                        }
                        if(!$elements[1]) {
                            $errors[] = 'You must enter a first name for every player! Line '.$line_counter;
                            break;
                        }
                        if($elements[2] > 3) {
                            $errors[] = 'Nationality should have MAX 3 characters! Line '.$line_counter;
                            break;
                        }
                        if($elements[6]<1) {
                            $errors[] = 'The price must be greater than 0! Line '.$line_counter;
                            break;
                        }
                        if($elements[7]!='g' && $elements[7]!='d' && $elements[7]!='m' && $elements[7]!='s') {
                            $errors[] = 'The position must be "g", "d", "m" or "s"! Line '.$line_counter;
                            break;
                        }
                        $team = FfbTeamPeer::retrieveByPK($elements[5]);
                        if(!$team) {
                            $errors[] = 'One or more lines contain a team which does not exist! Line '.$line_counter;
                            break;
                        }

                    } else {
                        $errors[] = 'One or more lines have too few/much elements! ('.count($elements).') Line '.$line_counter;
                    }
                    $line_counter++;
                }
            } else {
                $errors[] = 'The textfile contains no valid rows!';
            }
        } else {
            $errors[] = 'There was no player-file found!';
        }
        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }

    private function importPtt() {
        $filename = $_FILES['player_file_ptt']['tmp_name'];
        $file = fopen( $filename , "r" );
        $content = fread($file ,filesize($filename));
        $playerlines = explode(';', trim($content));
        $exist_players = 0;
        $exist_players_string = '';
        $new_players = 0;
        foreach($playerlines as $line) {
            $elements = explode(',',trim($line));
            $full_name = $elements[1].' '.$elements[0];
            $criteria = new Criteria();
            //if($elements[1]) {
            //    $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $elements[1]);
            //$full_name = $elements[1].' '.$elements[0];
            //} else {
            //    $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $elements[0]);
            //    $full_name = $elements[0].' '.$elements[0];
            //}
            $criteria->add(FfbPlayerPeer::PLAYER_LNAME, $elements[0]);
            $criteria->add(FfbPlayerPeer::PLAYER_FNAME, $elements[1]);
            $criteria->add(FfbPlayerPeer::PLAYER_NATIONALITY, $elements[2]);
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $elements[5]);
            $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION, $elements[7]);
            $exist_item = FfbPlayerteamPeer::doSelectJoinAll($criteria);
            if($exist_item) {
                $exist_players++;
                $exist_players_string .= $full_name.'<br>';
            } else {
                $new_players++;
                $new_item = new FfbPlayer();
                $new_item->setPlayerLname($elements[0]);
                if($elements[1])
                    { $new_item->setPlayerFname($elements[1]); }
                else
                    { $new_item->setPlayerFname($elements[0]); }
                $new_item->setPlayerNationality($elements[2]);
                if($elements[3] == '0')
                    $new_item->setPlayerStatus(0);
                else
                    $new_item->setPlayerStatus(1);
                $new_item->setPlayerStatusDescription($elements[4]);
                $new_item->save();

                $player_id = $new_item->getPlayerId();

                $new_item = new FfbPlayerteam();
                $new_item->setPlayerteamPlayerId($player_id);
                $new_item->setPlayerteamTeamId($elements[5]);
                $new_item->setPlayerteamPlayerPrice($elements[6]);
                $new_item->setPlayerteamPlayerPosition($elements[7]);
                $new_item->setPlayerteamPlayerPicture('');
                if($elements[9] == '0')
                    $new_item->setPlayerteamStatus(0);
                else
                    $new_item->setPlayerteamStatus(1);
                $new_item->save();
                if($elements[8] == '1') {
                    $insert_id = $new_item->getPlayerteamId();
                    $new_item->setPlayerteamPlayerPicture($insert_id.'.jpg');
                    $new_item->save();
                }
            }
        }
        $this->administration_answer = $new_players.' new Players successfully added! ';
        if($exist_players)
            $this->administration_answer = $new_players.' new Players successfully added!<br>'.$exist_players.' Players already in the Database!<br>'.$exist_players_string;

        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;
        //exit();
    }

}
?>
