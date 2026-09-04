<?php

/**
 * ADMIN - TEAM-Klasse;
 * Teams hinzuf�gen/�ndern/l�schen
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 */

class team extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        require_once('ffb/FfbTeam.php');
        $this->htmlFile = 'team.php';
    }

    public function __default() {
        $this->administration_modus = $_POST['administration_modus'];
        $this->post = $_POST;
        if(count($_POST)) {
            if(isset($_POST['team_administration_change_x']) || isset($_POST['team_administration_change']))
                { $this->changeItem($_POST['team_id']); }
            elseif(isset($_POST['team_administration_delete_x']) || isset($_POST['team_administration_delete']))
            {
                if($this->validateDelete($_POST['team_id']))
                    $this->deleteItem($_POST['team_id']);
                else {
                    $errors = array();
                }
            }
            else {
                if($this->validate()) {
                    if(isset($_POST['team_administration_insert']))
                        { $this->addItem(); }
                    elseif(isset($_POST['team_administration_update']))
                        { $this->updateItem($_POST['team_id']); }
                } else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }
        }
        $this->getList();
    }

    //gesamte Team-Liste holen
    public function getList() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
        $list = FfbTeamPeer::doSelect($criteria);
        $teams = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
            	$teamfids = $item->getFfbTeamfids();
            	//echo count($teamfids)."\n";
                $teams[$i]['team_id'] = $item->getTeamId();
                $teams[$i]['team_name'] = $item->getTeamName();
                $teams[$i]['team_nationality'] = $item->getTeamNationality();
                $teams[$i]['team_status'] = $item->getTeamStatus();
                $teams[$i]['team_price'] = $item->getTeamAvgPrice();
                if(count($teamfids)) {
					$teams[$i]['teamfid_fid_foe'] = $teamfids[0]->getTeamfidFidFoe();
					$teams[$i]['teamfid_fid_tm'] = $teamfids[0]->getTeamfidFidTm();
					$teams[$i]['teamfid_fid_wf'] = $teamfids[0]->getTeamfidFidWf();
					$teams[$i]['teamfid_name_foe'] = $teamfids[0]->getTeamfidNameFoe();
					$teams[$i]['teamfid_name_tm'] = $teamfids[0]->getTeamfidNameTm();
					$teams[$i]['teamfid_name_wf'] = $teamfids[0]->getTeamfidNameWf();
					$teams[$i]['teamfid_url_foe'] = $teamfids[0]->getTeamfidUrlFoe();
					$teams[$i]['teamfid_url_tm'] = $teamfids[0]->getTeamfidUrlTm();
					$teams[$i]['teamfid_url_wf'] = $teamfids[0]->getTeamfidUrlWf();
				}

				if(!$teams[$i]['teamfid_fid_foe']) {
					$teams[$i]['teamfid_fid_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_tm']) {
					$teams[$i]['teamfid_fid_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_wf']) {
					$teams[$i]['teamfid_fid_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_name_foe']) {
					$teams[$i]['teamfid_name_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_name_tm']) {
					$teams[$i]['teamfid_name_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_name_wf']) {
					$teams[$i]['teamfid_name_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_url_foe']) {
					$teams[$i]['teamfid_url_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_url_tm']) {
					$teams[$i]['teamfid_url_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_url_wf']) {
					$teams[$i]['teamfid_url_wf'] = 0;
				}
                $i++;
            }
        }
        $this->numResults = $i;
        $this->teams = $teams;
    }

    //Team-Liste für Liga holen
    public function getTeamListForGame() {
        $criteria = new Criteria();
        $game_id = $this->session->game_id_admin;
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $game_id);
        $criteria->addJoin(FfbMatchPeer::MATCH_ROUND, FfbMatchroundPeer::MATCHROUND_ID, Criteria::INNER_JOIN);
        $matches = FfbMatchPeer::doSelect($criteria);
        $group_array = array();
        $teams = array();
        $i=0;
        foreach($matches as $match) {
			$hometeam = $match->getFfbTeamRelatedByMatchHometeamId();
			$teamfids = $hometeam->getFfbTeamfids();
			if(!$group_array[$hometeam->getTeamId()]) {
				$group_array[$hometeam->getTeamId()] = 1;
				$teams[$i]['team_id'] = $hometeam->getTeamId();
                $teams[$i]['team_name'] = $hometeam->getTeamName();
                $teams[$i]['team_nationality'] = $hometeam->getTeamNationality();
                $teams[$i]['team_status'] = $hometeam->getTeamStatus();
                $teams[$i]['team_price'] = $hometeam->getTeamAvgPrice();
                $criteria = new Criteria();
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $hometeam->getTeamId());
                //$players = $hometeam->getFfbPlayerteams($criteria);
                $players = FfbPlayerteamPeer::doSelect($criteria);
                $num_players = count($players);
                $teams[$i]['team_num_players'] = $num_players;
                if($teamfids) {
					$teams[$i]['teamfid_fid_foe'] = $teamfids[0]->getTeamfidFidFoe();
					$teams[$i]['teamfid_fid_tm'] = $teamfids[0]->getTeamfidFidTm();
					$teams[$i]['teamfid_fid_wf'] = $teamfids[0]->getTeamfidFidWf();
					$teams[$i]['teamfid_name_foe'] = $teamfids[0]->getTeamfidNameFoe();
					$teams[$i]['teamfid_name_tm'] = $teamfids[0]->getTeamfidNameTm();
					$teams[$i]['teamfid_name_wf'] = $teamfids[0]->getTeamfidNameWf();
					$teams[$i]['teamfid_url_foe'] = $teamfids[0]->getTeamfidUrlFoe();
					$teams[$i]['teamfid_url_tm'] = $teamfids[0]->getTeamfidUrlTm();
					$teams[$i]['teamfid_url_wf'] = $teamfids[0]->getTeamfidUrlWf();
				}

				if(!$teams[$i]['teamfid_fid_foe']) {
					$teams[$i]['teamfid_fid_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_tm']) {
					$teams[$i]['teamfid_fid_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_wf']) {
					$teams[$i]['teamfid_fid_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_name_foe']) {
					$teams[$i]['teamfid_name_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_name_tm']) {
					$teams[$i]['teamfid_name_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_name_wf']) {
					$teams[$i]['teamfid_name_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_url_foe']) {
					$teams[$i]['teamfid_url_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_url_tm']) {
					$teams[$i]['teamfid_url_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_url_wf']) {
					$teams[$i]['teamfid_url_wf'] = 0;
				}
                $i++;
			}
			$guestteam = $match->getFfbTeamRelatedByMatchGuestteamId();
			$teamfids = $guestteam->getFfbTeamfids();
			if(!$group_array[$guestteam->getTeamId()]) {
				$group_array[$guestteam->getTeamId()] = 1;
				$teams[$i]['team_id'] = $guestteam->getTeamId();
                $teams[$i]['team_name'] = $guestteam->getTeamName();
                $teams[$i]['team_nationality'] = $guestteam->getTeamNationality();
                $teams[$i]['team_status'] = $guestteam->getTeamStatus();
                $teams[$i]['team_price'] = $guestteam->getTeamAvgPrice();
                $criteria = new Criteria();
                $criteria->add(FfbPlayerteamPeer::PLAYERTEAM_STATUS, 1);
				$criteria->add(FfbPlayerteamPeer::PLAYERTEAM_TEAM_ID, $guestteam->getTeamId());
                //$players = $guestteam->getFfbPlayerteams($criteria);
                $players = FfbPlayerteamPeer::doSelect($criteria);
                $num_players = count($players);
                $teams[$i]['team_num_players'] = $num_players;
				if($teamfids) {
					$teams[$i]['teamfid_fid_foe'] = $teamfids[0]->getTeamfidFidFoe();
					$teams[$i]['teamfid_fid_tm'] = $teamfids[0]->getTeamfidFidTm();
					$teams[$i]['teamfid_fid_wf'] = $teamfids[0]->getTeamfidFidWf();
					$teams[$i]['teamfid_name_foe'] = $teamfids[0]->getTeamfidNameFoe();
					$teams[$i]['teamfid_name_tm'] = $teamfids[0]->getTeamfidNameTm();
					$teams[$i]['teamfid_name_wf'] = $teamfids[0]->getTeamfidNameWf();
					$teams[$i]['teamfid_url_foe'] = $teamfids[0]->getTeamfidUrlFoe();
					$teams[$i]['teamfid_url_tm'] = $teamfids[0]->getTeamfidUrlTm();
					$teams[$i]['teamfid_url_wf'] = $teamfids[0]->getTeamfidUrlWf();
				}
				if(!$teams[$i]['teamfid_fid_foe']) {
					$teams[$i]['teamfid_fid_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_tm']) {
					$teams[$i]['teamfid_fid_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_fid_wf']) {
					$teams[$i]['teamfid_fid_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_name_foe']) {
					$teams[$i]['teamfid_name_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_name_tm']) {
					$teams[$i]['teamfid_name_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_name_wf']) {
					$teams[$i]['teamfid_name_wf'] = 0;
				}
				if(!$teams[$i]['teamfid_url_foe']) {
					$teams[$i]['teamfid_url_foe'] = 0;
				}
				if(!$teams[$i]['teamfid_url_tm']) {
					$teams[$i]['teamfid_url_tm'] = 0;
				}
				if(!$teams[$i]['teamfid_url_wf']) {
					$teams[$i]['teamfid_url_wf'] = 0;
				}
                $i++;
			}
		}

        $this->numResults = count($teams);
        $this->teams = $teams;
    }

    //ein Team ändern - bestehende Daten holen
    private function changeItem($id) {
        $team = array();
        if($id) {
            $item = FfbTeamPeer::retrieveByPK($id);
            if($item) {
                $team['team_id'] = $item->getTeamId();
                $team['team_name'] = $item->getTeamName();
                $team['team_nationality'] = $item->getTeamNationality();
                $team['team_status'] = $item->getTeamStatus();
                $team['team_price'] = $item->getTeamAvgPrice();
                $teamfids = $item->getFfbTeamfids();
                if($teamfids) {
					$tfid = $teamfids[0];
					$team['teamfid_fid_tm'] = $tfid->getTeamfidFidTm();
					$team['teamfid_name_tm'] = $tfid->getTeamfidNameTm();
					$team['teamfid_name_wf'] = $tfid->getTeamfidNameWf();
					$team['teamfid_url_tm'] = $tfid->getTeamfidUrlTm();
					$team['teamfid_url_wf'] = $tfid->getTeamfidUrlWf();
					$team['teamfid_url_foe'] = $tfid->getTeamfidUrlFoe();
				}
            }
        }
        $this->post = $team;
        $this->administration_modus = 'update';
    }

    //neues Team hinzuf�gen
    private function addItem() {
        $new_item = new FfbTeam();
        $new_item->setTeamName($_POST['team_name']);
        $new_item->setTeamNationality($_POST['team_nationality']);
        $new_item->setTeamStatus($_POST['team_status']);
        $new_item->setTeamAvgPrice($_POST['team_price']);
        $new_item->save();
        $new_tfid = new FfbTeamfid();
        $new_tfid->setTeamfidTeamId($new_item->getTeamId());
        $new_tfid->setTeamfidFidTm($_POST['teamfid_fid_tm']);
        $new_tfid->setTeamfidNameTm($_POST['teamfid_name_tm']);
        $new_tfid->setTeamfidNameWf($_POST['teamfid_name_wf']);
        if($_POST['teamfid_fid_tm'] && $_POST['teamfid_name_tm']) {
			$new_tfid->setTeamfidUrlTm('http://www.transfermarkt.at/de/'.$_POST['teamfid_name_tm'].'/startseite/nationalmannschaft_'.$_POST['teamfid_fid_tm'].'.html');
		}
	    if($_POST['teamfid_name_wf']) {
	       	$new_tfid->setTeamfidUrlWf('http://www.weltfussball.at/teams/'.$_POST['teamfid_name_wf'].'-team/');
	    }
        $new_tfid->setTeamfidUrlFoe($_POST['teamfid_url_foe']);
        $new_tfid->save();
        $this->administration_answer = 'New Team successfully added!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;

    }

    //bestehendes Team updaten
    private function updateItem($id) {
        $exist_item = FfbTeamPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setTeamName($_POST['team_name']);
            $exist_item->setTeamNationality($_POST['team_nationality']);
            $exist_item->setTeamStatus($_POST['team_status']);
            $exist_item->setTeamAvgPrice($_POST['team_price']);
            $exist_item->save();

            $tfids = $exist_item->getFfbTeamfids();
            if($tfids) {
				$new_tfid = $tfids[0];
			} else {
				$new_tfid = new FfbTeamfid();
				$new_tfid->setTeamfidTeamId($id);
			}
	        $new_tfid->setTeamfidFidTm($_POST['teamfid_fid_tm']);
	        $new_tfid->setTeamfidNameTm($_POST['teamfid_name_tm']);
	        $new_tfid->setTeamfidNameWf($_POST['teamfid_name_wf']);
	        if($_POST['teamfid_fid_tm'] && $_POST['teamfid_name_tm']) {
				$new_tfid->setTeamfidUrlTm('http://www.transfermarkt.at/de/'.$_POST['teamfid_name_tm'].'/startseite/nationalmannschaft_'.$_POST['teamfid_fid_tm'].'.html');
			}
	        if($_POST['teamfid_name_wf']) {
	        	$new_tfid->setTeamfidUrlWf('http://www.weltfussball.at/teams/'.$_POST['teamfid_name_wf'].'-team/');
	        }
	        $new_tfid->setTeamfidUrlFoe($_POST['teamfid_url_foe']);
	        $new_tfid->save();

            $this->administration_answer = 'Existing Team successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbTeamPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $errors[] = 'Team not found! Wrong ID or site reloaded?';
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
            $errors[] = 'Players attached to this team are used by userteams - you cannot delete this team!';
            $this->errors = $errors;
        }
        return $return;
    }

    //Team l�schen
    private function deleteItem($id) {
        $item = FfbTeamPeer::retrieveByPK($id);

        FfbTeamPeer::doDelete($item);
        $this->administration_answer = 'Existing Team successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //Formular validieren
    private function validate() {
        $errors = array();

        //check for empty fields
        if(!count($_POST) || !$_POST['team_name'] || !$_POST['team_nationality'] || !$_POST['team_price'])
        {
            $errors[] = 'You have to fill out all fields marked with a *!';
        }

        //check for existing teamname (only on insert not on update)
        if($_POST['team_name'] && !$_POST['team_administration_update']) {
            $criteria = new Criteria();
            $criteria->add(FfbTeamPeer::TEAM_NAME, $_POST['team_name']);
            $criteria->add(FfbTeamPeer::TEAM_NATIONALITY, $_POST['team_nationality']);
            $exist_item = FfbTeamPeer::doSelect($criteria);
            if($exist_item) {
                $errors[] = 'A Team with this name and nationality is already existing!';
            }
        }

        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }
}
?>
