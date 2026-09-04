<?php

/**
 * ADMIN - TEAMPARSERHELPERS-Klasse;
 * Team Parser Update Functions
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.1
 *
 */

class teamParserHelpers extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    public function updateName() {
        $playerteam_id = $_POST['playerteam_id'];
        $fname = $_POST['player_fname'];
        $lname = $_POST['player_lname'];
        $picture = $_POST['playerteam_player_picture'];
        $status = $_POST['player_status_description'];
        $source = $_POST['source'];

        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $answer = '';
        if($playerteam) {
            $team_id = $playerteam->getPlayerteamTeamId();
            //$playerteam->getFfbPlayer()->setPlayerFname($fname);
            //$playerteam->getFfbPlayer()->setPlayerLname($lname);
            if($source == 'foe') {
				$fid_name = $lname.' '.$fname;
			} elseif($source == 'tm' || $source == 'wf') {
				$fid_name = $fname.' '.$lname;
			}
            $this->updatePlayerfidName($playerteam_id, $team_id, $fid_name, $source);

            if($status) {
                $playerteam->getFfbPlayer()->setPlayerStatus(0);
                $playerteam->getFfbPlayer()->setPlayerStatusDescription($status);
                $answer .= 'Status updated: '.$status.'!<br>';
            }
            if($picture) {
                $picture_file = $this->grabPicture($picture, $source);
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
            $this->administration_error = 'Error: updateName: Could not find entry for ID "'.$playerteam_id.'"!';
            $this->administration_status = STATUS_CODE_ERROR;
            return;
        }
    }

    public function updateImage() {
        $playerteam_id = $_POST['playerteam_id'];
        $picture = $_POST['playerteam_player_picture'];
        $source = $_POST['source'];
        $playerteam = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
        $answer = '';
        if($playerteam) {
            $team_id = $playerteam->getPlayerteamTeamId();
            $fname = $playerteam->getFfbPlayer()->getPlayerFname();
            $lname = $playerteam->getFfbPlayer()->getPlayerLname();
            $picture_file = $this->grabPicture($picture, $source);
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
        $source = $_POST['source'];
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
        $source = $_POST['source'];
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
        $source = $_POST['source'];
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
        $fid_name = $lname.' '.$fname;
        if($_POST['player_foreign_id']) {
        	$foreign_id = $_POST['player_foreign_id'];
        } else {
			$foreign_id = '';
		}
        $position = $_POST['playerteam_player_position'];
        $picture = $_POST['playerteam_player_picture'];
        $source = $_POST['source'];

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
        $new_playerteam->setPlayerteamDateTransfer('2008-01-01 00:00:00');
        $new_playerteam->save();
        $playerteam_id = $new_playerteam->getPlayerteamId();
        $team_id = $playerteam_team_id;

		if($source == 'foe') {
			$fid_name = $lname.' '.$fname;
		} elseif($source == 'tm' || $source == 'wf') {
			$fid_name = $fname.' '.$lname;
		}
        $this->updatePlayerfidName($playerteam_id, $team_id, $fid_name, $source);
        $answer .= 'Player '.$fname.' '.$lname.' sucessfully added! ID: '.$playerteam_id.'<br>';
        $this->administration_status = STATUS_CODE_SUCCESS;
        if($picture) {
            $picture_file = $this->grabPicture($picture, $source);
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
        $transfer = $_POST['transfer'];
        $playerteam_id = $_POST['playerteam_id'];
        $source = $_POST['source'];

        $team = FfbTeamPeer::retrieveByPK($playerteam_team_id);
        if($team) {
            $price = $team->getTeamAvgPrice();
        }

        if($transfer) {
			$old_pt = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
			$old_pt->setPlayerteamStatus(0);
			$old_pt->save();
		}

        $new_playerteam = new FfbPlayerteam();
        $new_playerteam->setPlayerteamPlayerId($player_id);
        $new_playerteam->setPlayerteamTeamId($playerteam_team_id);
        $new_playerteam->setPlayerteamPlayerPosition($position);
        $new_playerteam->setPlayerteamPlayerPrice($price);
        if(!$transfer) {
        	$new_playerteam->setPlayerteamDateTransfer('2008-01-01 00:00:00');
        }
        $new_playerteam->save();
        $playerteam_id = $new_playerteam->getPlayerteamId();
        $team_id = $playerteam_team_id;

        if($source == 'foe') {
			$fid_name = $lname.' '.$fname;
		} elseif($source == 'tm' || $source == 'wf') {
			$fid_name = $fname.' '.$lname;
		}
        $this->updatePlayerfidName($playerteam_id, $team_id, $fid_name, $source);
        $answer .= 'Player '.$fname.' '.$lname.' sucessfully added! ID: '.$playerteam_id.'<br>';
        $this->administration_status = STATUS_CODE_SUCCESS;
        $this->administration_answer = $answer;
    }

    private function grabPicture($picture_name, $source) {
        if($picture_name != 'nobody.jpg' && $picture_name != 'somebody.jpg') {
        	if($source == 'tm') {
            	$url = 'http://www.transfermarkt.de/bilder/spielerfotos/'.$picture_name;
            } elseif($source == 'wf') {
            	$url = 'http:' . $picture_name;
            }
            return (imagecreatefromjpeg($url));
        } else {
            return false;
        }
    }

    private function createPicture($picture_file, $team_id, $playerteam_id) {
        if(!is_dir('/www/htdocs/w005c0bf/ffb/images/ffb/players/'.$team_id)) {
            mkdir('/www/htdocs/w005c0bf/ffb/images/ffb/players/'.$team_id);
        }
        $filename = '/www/htdocs/w005c0bf/ffb/images/ffb/players/'.$team_id.'/'.$playerteam_id.'.jpg';
		if(file_exists($filename)) {
			unlink($filename);
		}
        return(imagejpeg($picture_file, $filename));
    }

    private function updatePlayerfidName($playerteam_id, $team_id, $fid_name, $source) {
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
            $update_item->setPlayerfidFidFoe('');
            $update_item->setPlayerfidFidFifa('');
            $update_item->setPlayerfidFidTm('');
            $update_item->setPlayerfidFidUefa('');
            $update_item->setPlayerfidFidWf('');
            $update_item->setPlayerfidNameFoe('');
            $update_item->setPlayerfidNameFifa('');
            $update_item->setPlayerfidNameTm('');
            $update_item->setPlayerfidNameUefa('');
            $update_item->setPlayerfidNameWf('');
        }
		if($source == 'foe') {
			$update_item->setPlayerfidNameFoe($fid_name);
		} elseif($source == 'tm') {
			$update_item->setPlayerfidNameTm($fid_name);
		} elseif($source == 'wf') {
			$update_item->setPlayerfidNameWf($fid_name);
		}
        $update_item->save();
    }
}

?>