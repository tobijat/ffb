<?php

/**
 * ADMIN - MATCH-Klasse;
 * Matches hinzufügen/ändern/löschen
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 */

class admin_match extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        require_once('ffb/FfbMatch.php');
        $this->htmlFile = 'match.php';
    }

    public function __default() {
        $this->administration_modus = $_POST['administration_modus'] ?? null;
        $this->post = $_POST;
        if (!empty($_POST)) {
            if(isset($_POST['match_administration_change_x']) || isset($_POST['match_administration_change']))
                { $this->changeItem($_POST['match_id']); }
            elseif(isset($_POST['match_administration_delete_x']) || isset($_POST['match_administration_delete']))
            {
                if($this->validateDelete($_POST['match_id']))
                    $this->deleteItem($_POST['match_id']);
                else {
                    $errors = array();
                }
            }
            else {
                if($this->validate()) {
                    if(isset($_POST['match_administration_insert']))
                        { $this->addItem(); }
                    elseif(isset($_POST['match_administration_update']))
                        { $this->updateItem($_POST['match_id']); }
                } else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }
        }
        $this->getList();
    }

    //gesamte Match-Liste holen
    public function getList() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $matchrounds = FfbMatchroundPeer::doSelect($criteria);

        $criteria = new Criteria();
        if($matchrounds) {
            foreach($matchrounds as $matchround) {
                $c1 = $criteria->getNewCriterion(FfbMatchPeer::MATCH_ROUND, $matchround->getMatchroundId());
                $criteria->addOr($c1);
            }
        } else {
            //nicht erfüllbare bedingung, damit kein match angezeigt wird
            $criteria->addOr(FfbMatchPeer::MATCH_ID, 0);
        }

        $criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
        //$criteria->add(FfbMatchroundPeer::retrieveByPK(FfbMatchPeer::MATCH_ROUND), $this->session->game_id_admin);
        $this->getMatchByCriteria($criteria);
    }

    //ein Match ändern - bestehende Daten holen
    private function changeItem($id) {
        $team = array();
        if($id) {
            $item = FfbMatchPeer::retrieveByPK($id);
            if($item) {
                $match['match_id'] = $item->getMatchId();
                $match['match_round'] = $item->getMatchRound();
                $match['match_date'] = $item->getMatchDate();
                $date = strtotime($item->getMatchDate());
                $match['match_date_year'] = date('Y',$date);
                $match['match_date_day'] = date('j',$date);
                $match['match_date_month'] = date('n',$date);
                $match['match_hometeam_id'] = $item->getMatchHometeamId();
                $match['match_guestteam_id'] = $item->getMatchGuestteamId();
                $match['match_homescore'] = $item->getMatchHomescore();
                $match['match_guestscore'] = $item->getMatchGuestscore();
                $match['match_homescore_penalty'] = $item->getMatchHomescorePenalty();
                $match['match_guestscore_penalty'] = $item->getMatchGuestscorePenalty();
                $match['match_status'] = $item->getMatchStatus();
            }
        }
        $this->post = $match;
        $this->administration_modus = 'update';
    }

    //neues match hinzufügen
    private function addItem() {
        $new_item = new FfbMatch();
        $new_item->setMatchRound($_POST['match_round']);
        $new_item->setMatchDate($_POST['match_date_year'].'-'.$_POST['match_date_month'].'-'.$_POST['match_date_day']);
        $new_item->setMatchHometeamId($_POST['match_hometeam_id']);
        $new_item->setMatchGuestteamId($_POST['match_guestteam_id']);
        $new_item->setMatchHomescore($_POST['match_homescore']);
        $new_item->setMatchGuestscore($_POST['match_guestscore']);
        $new_item->setMatchHomescorePenalty($_POST['match_homescore_penalty']);
        $new_item->setMatchGuestscorePenalty($_POST['match_guestscore_penalty']);
        $new_item->setMatchStatus($_POST['match_status'] ?? '');
        $new_item->setMatchMinutes(isset($_POST['match_minutes']) ? (int)$_POST['match_minutes'] : 0);
        $new_item->setMatchUrl($_POST['match_url'] ?? '');
        $new_item->save();
        $this->administration_answer = 'New Match successfully added!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;

    }

    //bestehendes Match updaten
    private function updateItem($id) {
        $exist_item = FfbMatchPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setMatchRound($_POST['match_round']);
            $exist_item->setMatchDate($_POST['match_date_year'].'-'.$_POST['match_date_month'].'-'.$_POST['match_date_day']);
            $exist_item->setMatchHometeamId($_POST['match_hometeam_id']);
            $exist_item->setMatchGuestteamId($_POST['match_guestteam_id']);
            //$exist_item->setMatchHomescore($_POST['match_homescore']);
            //$exist_item->setMatchGuestscore($_POST['match_guestscore']);
            //$exist_item->setMatchHomescorePenalty($_POST['match_homescore_penalty']);
            //$exist_item->setMatchGuestscorePenalty($_POST['match_guestscore_penalty']);
            $exist_item->setMatchStatus($_POST['match_status']);
            $exist_item->save();
            $this->administration_answer = 'Existing Match successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbMatchPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $errors[] = 'Match not found! Wrong ID or site reloaded?';
            $this->errors = $errors;
            return false;
        }

        if($item->getFfbPlayerstatss()) {
            $errors[] = 'Deleting Match not possible! There are related playerstats!';
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    //Match löschen
    private function deleteItem($id) {
        $item = FfbMatchPeer::retrieveByPK($id);

        FfbMatchPeer::doDelete($item);
        $this->administration_answer = 'Existing Match successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //Formular validieren
    private function validate() {
        $errors = array();

        //check for empty fields
        if (empty($_POST) || !$_POST['match_round'] || !$_POST['match_date_day'] || !$_POST['match_date_month']
           || !$_POST['match_date_year'] || !$_POST['match_hometeam_id'] || !$_POST['match_guestteam_id'])
        {
            $errors[] = 'You have to fill out all fields marked with a *!';
        }

        //check date
        if($_POST['match_date_day'] && $_POST['match_date_month'] && $_POST['match_date_year']) {
            $usertime = $_POST['match_date_day'].'.'.$_POST['match_date_month'].'.'.$_POST['match_date_year'];
            $servertime =  date('j.n.Y', mktime(0,0,0,$_POST['match_date_month'],$_POST['match_date_day'],$_POST['match_date_year']));
            if($usertime != $servertime)
            {
                $errors[] = 'The Date is not valid!';
            }
        }

        //check score
        if(($_POST['match_homescore'] == -1 && $_POST['match_guestscore'] != -1) || ($_POST['match_guestscore'] == -1 && $_POST['match_homescore'] != -1)) {
            $errors[] = 'Leave both scores N/A or choose valid scores for both teams!';
        }

        //check home and guestteam
        if($_POST['match_hometeam_id'] == $_POST['match_guestteam_id']) {
            $errors[] = 'Hometeam and Guestteam must be different!!';
        }

        //check for existing match (only on insert not on update)
        if(($_POST['match_round'] ?? null) && ($_POST['match_hometeam_id'] ?? null) && ($_POST['match_guestteam_id'] ?? null) && ($_POST['match_date_day'] ?? null) &&
           ($_POST['match_date_month'] ?? null) && ($_POST['match_date_year'] ?? null) && empty($_POST['match_administration_update'])) {
            $criteria = new Criteria();
            $criteria->add(FfbMatchPeer::MATCH_DATE, $_POST['match_date_year'].'-'.$_POST['match_date_month'].'-'.$_POST['match_date_day']);
            $criteria->add(FfbMatchPeer::MATCH_ROUND, $_POST['match_round']);
            $criteria->add(FfbMatchPeer::MATCH_HOMETEAM_ID, $_POST['match_hometeam_id']);
            $criteria->add(FfbMatchPeer::MATCH_GUESTTEAM_ID, $_POST['match_guestteam_id']);
            $exist_item = FfbMatchPeer::doSelect($criteria);
            if($exist_item) {
                $errors[] = 'A Match with this hometeam, guestteam and date is already existing!';
            }
        }

        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }

    //returns matches for the round given in $_POST['matchround_id']
    public function getMatchesForRound() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ROUND, $_POST['matchround_id']);
        $criteria->addAscendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
        $this->getMatchByCriteria($criteria);
    }

    //returns match for the given MatchID
    public function getMatchForId() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_ID, $_POST['match_id']);
        $criteria->setLimit(1);
        $this->getMatchByCriteria($criteria);
    }

    //returns matches by given criteria
    private function getMatchByCriteria($criteria) {
        $list = FfbMatchPeer::doSelect($criteria);
        $match = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $match[$i]['match_id'] = $item->getMatchId();
                $match[$i]['match_round'] = $item->getMatchRound();
                $match[$i]['match_round_name'] = FfbMatchroundPeer::retrieveByPK($match[$i]['match_round'])->getMatchroundTitle();
                $match[$i]['match_date'] = date('j.n.Y',strtotime($item->getMatchDate()));
                $match[$i]['match_minutes'] = $item->getMatchMinutes();
                $match[$i]['match_hometeam_id'] = $item->getMatchHometeamId();
                $match[$i]['match_guestteam_id'] = $item->getMatchGuestteamId();
                $match[$i]['match_hometeam_name'] = FfbTeamPeer::retrieveByPK($match[$i]['match_hometeam_id'])->getTeamName();
                $match[$i]['match_guestteam_name'] = FfbTeamPeer::retrieveByPK($match[$i]['match_guestteam_id'])->getTeamName();
                $match[$i]['match_hometeam_nationality'] = FfbTeamPeer::retrieveByPK($match[$i]['match_hometeam_id'])->getTeamNationality();
                $match[$i]['match_guestteam_nationality'] = FfbTeamPeer::retrieveByPK($match[$i]['match_guestteam_id'])->getTeamNationality();
                $match[$i]['match_homescore'] = $item->getMatchHomescore();
                $match[$i]['match_guestscore'] = $item->getMatchGuestscore();
                $match[$i]['match_homescore_penalty'] = $item->getMatchHomescorePenalty();
                $match[$i]['match_guestscore_penalty'] = $item->getMatchGuestscorePenalty();
                $match[$i]['match_status'] = $item->getMatchStatus();
                if($item->getMatchUrl()) {
					$match[$i]['match_url'] = $item->getMatchUrl();
				} else {
					$match[$i]['match_url'] = 0;
				}
                $i++;
            }
        }
        $this->numResults = $i;
        $this->matches = $match;
    }
}

class_alias('admin_match', 'match');
?>
