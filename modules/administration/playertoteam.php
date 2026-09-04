<?php

/**
 * ADMIN - PLAYER TO TEAM-Klasse;
 * Player zu einem Team hinzufuegen
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 */

class playertoteam extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        require_once('ffb/FfbPlayerteam.php');
        require_once('ffb/FfbPlayer.php');
        require_once('ffb/FfbTeam.php');
        $this->htmlFile = 'playertoteam.php';
    }

    public function __default() {
    }

    public function managePlayers() {
        $this->post = $_POST;
        if($_POST['action'] == 'insert') {
            $this->addItem();
        } elseif($_POST['action'] == 'update') {
            $this->updateItem($_POST['playerteam_id']);
        } elseif($_POST['action'] == 'delete') {
            if($this->validateDelete($_POST['playerteam_id']))
                $this->deleteItem($_POST['playerteam_id']);
        } else {
            $this->administration_error = 'No valid value given for action! (must be insert, update or delete)';
            $this->administration_status = STATUS_CODE_ERROR;
        }
    }

    public function addItem() {
        if($_POST['playerteam_player_picture'] == 'NULL' || $_POST['playerteam_player_picture'] == 'null')
            { $playerteam_player_picture = ''; }
        else
            { $playerteam_player_picture = $_POST['playerteam_player_picture']; }
        $new_item = new FfbPlayerteam();
        $new_item->setPlayerteamPlayerId($_POST['playerteam_player_id']);
        $new_item->setPlayerteamTeamId($_POST['playerteam_team_id']);
        $new_item->setPlayerteamPlayerPicture($playerteam_player_picture);
        $new_item->setPlayerteamStatus($_POST['playerteam_status']);
        $new_item->setPlayerteamPlayerPrice($_POST['playerteam_player_price']);
        $new_item->setPlayerteamPlayerPosition($_POST['playerteam_player_position']);
        $new_item->save();  //TODO: uncomment when finished
        $this->administration_answer = 'New Player successfully added to team!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;
    }

    public function updateItem($id) {
        $exist_item = FfbPlayerteamPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setPlayerteamPlayerId($_POST['playerteam_player_id']);
            $exist_item->setPlayerteamTeamId($_POST['playerteam_team_id']);
            //$exist_item->setPlayerteamPlayerPicture($_POST['playerteam_player_picture']);
            $exist_item->setPlayerteamStatus($_POST['playerteam_status']);
            $exist_item->setPlayerteamPlayerPrice($_POST['playerteam_player_price']);
            $exist_item->setPlayerteamPlayerPosition($_POST['playerteam_player_position']);
            $exist_item->save();  //TODO: uncomment when finished
            $this->administration_answer = 'Existing Player successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        } else {
            $this->administration_error = 'No entry for this ID was found!';
            $this->administration_status = STATUS_CODE_ERROR;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbPlayerteamPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $this->administration_error = 'Playerteam not found! Wrong ID or site reloaded?';
            $this->administration_status = STATUS_CODE_ERROR;
            return false;
        }
        $return = true;
        if($item->getFfbUserteamsRelatedByUserteamPlayerId1()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId2()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId3()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId4()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId5()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId6()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId7()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId8()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId9()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId10()) {
            $return = false;
        }
        if($item->getFfbUserteamsRelatedByUserteamPlayerId11()) {
            $return = false;
        }

        if(!$return) {
            $this->administration_error = 'This player is used by userteams - you cannot delete this player!';
            $this->administration_status = STATUS_CODE_ERROR;
        }
        return $return;
    }

    //Playerteam löschen
    private function deleteItem($id) {
        $item = FfbPlayerteamPeer::retrieveByPK($id);

        FfbPlayerteamPeer::doDelete($item);
        $this->administration_answer = 'Existing Playerteam successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //returns list of players for selected team
    public function getTeamPlayers() {
        $teamId = $_REQUEST['id'] ?? null;
        $team = $teamId ? FfbTeamPeer::retrieveByPK($teamId) : null;
        if($team) {
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_POSITION);
            $criteria->addDescendingOrderByColumn(FfbPlayerteamPeer::PLAYERTEAM_PLAYER_PRICE);
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_LNAME);
            $criteria->addAscendingOrderByColumn(FfbPlayerPeer::PLAYER_FNAME);
            //make a wonderful JOIN
            $playerRefs = $team->getFfbPlayerteamsJoinFfbPlayer($criteria);
            //in SQL: SELECT * FROM ffb_playerteam LEFT JOIN ffb_player ON ffb_player.player_id = ffb_playerteam
            //.playerteam_player_id WHERE ffb_playerteam.playerteam_team_id = 1

            $players = array();
            $i=0;
            if($playerRefs) {
                foreach($playerRefs as $ref) {
                    $player = $ref->getFfbPlayer();
                    if (!$player) {
                        continue;
                    }

                    //from table ffb_player
                    $players[$i]['player_id'] = $player->getPlayerId();
                    $players[$i]['player_fname'] = $player->getPlayerFname() ?: '';
                    $players[$i]['player_lname'] = $player->getPlayerLname() ?: '';
                    $players[$i]['player_nationality'] = $player->getPlayerNationality() ?: '';
                    $players[$i]['player_status'] = $player->getPlayerStatus() !== null ? $player->getPlayerStatus() : 0;
                    $players[$i]['player_status_description'] = $player->getPlayerStatusDescription() ?: '';
                    //from table ffb_playerteam
                    $players[$i]['playerteam_id'] = $ref->getPlayerteamId();
                    $players[$i]['playerteam_player_price'] = $ref->getPlayerteamPlayerPrice() !== null ? $ref->getPlayerteamPlayerPrice() : 0;
                    if($ref->getPlayerteamStatus()) {
                        $players[$i]['playerteam_status'] = $ref->getPlayerteamStatus();
                    } else {
                        $players[$i]['playerteam_status'] = 0;
                    }
                    $players[$i]['playerteam_player_position'] = $ref->getPlayerteamPlayerPosition() ?: '';
                    if(!$ref->getPlayerteamPlayerPicture() || $ref->getPlayerteamPlayerPicture() == '') {
                        $players[$i]['playerteam_player_picture'] = 0;
                    } else {
                        $players[$i]['playerteam_player_picture'] = $ref->getPlayerteamPlayerPicture();
                    }


                    $i++;
                }
            }
            $this->numResults = $i;
            $this->players = $players;
        } else {
            $this->numResults = 0;
            $this->players = array();
            $this->administration_error = 'No entry for this ID was found!';
            $this->administration_status = STATUS_CODE_ERROR;
        }
    }
}
?>