<?php

/**
 * ADMIN - MATCHROUND-Klasse;
 * Matchrounds hinzufügen/ündern/lüschen
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 */

class matchround extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        require_once('ffb/FfbMatchround.php');
        $this->htmlFile = 'matchround.php';
    }

    public function __default() {
        $this->administration_modus = $_POST['administration_modus'] ?? null;
        $this->post = $_POST;
        if (!empty($_POST)) {
            if(isset($_POST['matchround_administration_change_x']) || isset($_POST['matchround_administration_change']))
                { $this->changeItem($_POST['matchround_id']); }
            elseif(isset($_POST['matchround_administration_delete_x']) || isset($_POST['matchround_administration_delete']))
            {
                if($this->validateDelete($_POST['matchround_id']))
                    $this->deleteItem($_POST['matchround_id']);
                else {
                    $errors = array();
                }
            }
            else {
                if($this->validate()) {
                    if(isset($_POST['matchround_administration_insert']))
                        { $this->addItem(); }
                    elseif(isset($_POST['matchround_administration_update']))
                        { $this->updateItem($_POST['matchround_id']); }
                } else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }
        }
        $this->getList();
    }

    //gesamte Matchround-Liste holen (für aktuell eingestelltes game)
    public function getList() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $this->getMatchroundByCriteria($criteria);
    }

    //eine Matchround ündern - bestehende Daten holen
    private function changeItem($id) {
        $matchround = array();
        if($id) {
            $item = FfbMatchroundPeer::retrieveByPK($id);
            if($item) {
                $matchround['matchround_id'] = $item->getMatchroundId();
                $matchround['matchround_title'] = $item->getMatchroundTitle();
                $matchround['matchround_status'] = $item->getMatchroundStatus();
                $startdate = strtotime($item->getMatchroundStartdate());
                $enddate = strtotime($item->getMatchroundEnddate());
                $matchround['matchround_startdate_year'] = date('Y',$startdate);
                $matchround['matchround_startdate_day'] = date('j',$startdate);
                $matchround['matchround_startdate_month'] = date('n',$startdate);
                $matchround['matchround_startdate_hour'] = date('G',$startdate);
                $matchround['matchround_enddate_year'] = date('Y',$enddate);
                $matchround['matchround_enddate_day'] = date('j',$enddate);
                $matchround['matchround_enddate_month'] = date('n',$enddate);
                $matchround['matchround_enddate_hour'] = date('G',$enddate);
            }
        }
        $this->post = $matchround;
        $this->administration_modus = 'update';
    }

    //neue matchround hinzufügen
    private function addItem() {
        $new_item = new FfbMatchround();
        $new_item->setMatchroundTitle($_POST['matchround_title']);
        $new_item->setMatchroundStatus($_POST['matchround_status']);
        $new_item->setMatchroundGameId($this->session->game_id_admin);
        $new_item->setMatchroundStartdate($_POST['matchround_startdate_year'].'-'.$_POST['matchround_startdate_month'].'-'.$_POST['matchround_startdate_day'].' '.$_POST['matchround_startdate_hour']);
        $new_item->setMatchroundEnddate($_POST['matchround_enddate_year'].'-'.$_POST['matchround_enddate_month'].'-'.$_POST['matchround_enddate_day'].' '.$_POST['matchround_enddate_hour']);
        $new_item->save();
        $this->administration_answer = 'New Matchround successfully added!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;

    }

    //bestehende Matchround updaten
    private function updateItem($id) {
        $exist_item = FfbMatchroundPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setMatchroundTitle($_POST['matchround_title']);
            $exist_item->setMatchroundStatus($_POST['matchround_status']);
            $exist_item->setMatchroundStartdate($_POST['matchround_startdate_year'].'-'.$_POST['matchround_startdate_month'].'-'.$_POST['matchround_startdate_day'].' '.$_POST['matchround_startdate_hour']);
            $exist_item->setMatchroundEnddate($_POST['matchround_enddate_year'].'-'.$_POST['matchround_enddate_month'].'-'.$_POST['matchround_enddate_day'].' '.$_POST['matchround_enddate_hour']);
            $exist_item->save();
            $this->administration_answer = 'Existing Matchround successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbMatchroundPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $errors[] = 'Matchround not found! Wrong ID or site reloaded?';
            $this->errors = $errors;
            return false;
        }

        if($item->getFfbPlayerstatss()) {
            $errors[] = 'Deleting Matchround not possible! There are related playerstats!';
            $this->errors = $errors;
            return false;
        }

        if($item->getFfbUserteams()) {
            $errors[] = 'Deleting Matchround not possible! There are related userteams!';
            $this->errors = $errors;
            return false;
        }

        if($item->getFfbMatchs()) {
            $errors[] = 'Deleting Matchround not possible! There are related matches!';
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    //Matchround lüschen
    private function deleteItem($id) {
        $item = FfbMatchroundPeer::retrieveByPK($id);

        FfbMatchroundPeer::doDelete($item);
        $this->administration_answer = 'Existing Matchround successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //Formular validieren
    private function validate() {
        $errors = array();

        //check for choosen league
        if($this->session->game_id_admin<=0) {
            $errors[] = 'Go to the start page and choose a League first!';
        }
        //check for empty fields
        if (empty($_POST) || !$_POST['matchround_title'] || !$_POST['matchround_startdate_day'] ||
           !$_POST['matchround_startdate_month'] || !$_POST['matchround_startdate_year'] || !$_POST['matchround_enddate_day'] ||
           !$_POST['matchround_enddate_month'] || !$_POST['matchround_enddate_year'])
        {
            $errors[] = 'You have to fill out all fields marked with a *!';
        }

        //check date
        if($_POST['matchround_startdate_day'] && $_POST['matchround_startdate_month'] && $_POST['matchround_startdate_year']) {
            $startusertime = $_POST['matchround_startdate_day'].'.'.$_POST['matchround_startdate_month'].'.'.$_POST['matchround_startdate_year'];
            $startservertime =  date('j.n.Y', mktime(0,0,0,$_POST['matchround_startdate_month'],$_POST['matchround_startdate_day'],$_POST['matchround_startdate_year']));
            if($startusertime != $startservertime)
            {
                $errors[] = 'The Start Date is not valid!';
            }
        }
        if($_POST['matchround_enddate_day'] && $_POST['matchround_enddate_month'] && $_POST['matchround_enddate_year']) {
            $endusertime = $_POST['matchround_enddate_day'].'.'.$_POST['matchround_enddate_month'].'.'.$_POST['matchround_enddate_year'];
            $endservertime =  date('j.n.Y', mktime(0,0,0,$_POST['matchround_enddate_month'],$_POST['matchround_enddate_day'],$_POST['matchround_enddate_year']));
            if($endusertime != $endservertime)
            {
                $errors[] = 'The End Date is not valid!';
            }
        }

        if($_POST['matchround_startdate_day'] && $_POST['matchround_startdate_month'] && $_POST['matchround_startdate_year'] &&
           $_POST['matchround_enddate_day'] && $_POST['matchround_enddate_month'] && $_POST['matchround_enddate_year']) {
            if(mktime(0,0,0,$_POST['matchround_enddate_month'],$_POST['matchround_enddate_day'],$_POST['matchround_enddate_year']) < mktime(0,0,0,$_POST['matchround_startdate_month'],$_POST['matchround_startdate_day'],$_POST['matchround_startdate_year'])) {
                $errors[] = 'The Start Date cannot be after the End Date!';
            }
        }

        //check for existing matchround (only on insert not on update)
        if($_POST['matchround_startdate_day'] && $_POST['matchround_startdate_month'] && $_POST['matchround_startdate_year'] &&
           $_POST['matchround_enddate_day'] && $_POST['matchround_enddate_month'] && $_POST['matchround_enddate_year'] &&
           !$_POST['matchround_administration_update']) {
            $criteria = new Criteria();
            $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $_POST['matchround_startdate_year'].'-'.$_POST['matchround_startdate_month'].'-'.$_POST['matchround_startdate_day'].' '.$_POST['matchround_startdate_hour']);
            $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $_POST['matchround_enddate_year'].'-'.$_POST['matchround_enddate_month'].'-'.$_POST['matchround_enddate_day'].' '.$_POST['matchround_enddate_hour']);
			$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
            $exist_item = FfbMatchroundPeer::doSelect($criteria);
            if($exist_item) {
                $errors[] = 'A Matchround with this start and enddate is already existing!';
            }
        }

        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }

    //returns the next (not yet started) Matchround
    public function getPastMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns the next (not yet started) Matchround
    public function getFutureMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns the next (not yet started) Matchround
    public function getNextMatchround() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $criteria->setLimit(1);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns the current (running) Matchround
    public function getCurrentMatchrounds() {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::LESS_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ENDDATE, $date, Criteria::GREATER_THAN);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $this->session->game_id_admin);
        $this->getMatchroundByCriteria($criteria);
    }

    //returns matchrounds by given criteria
    private function getMatchroundByCriteria($criteria) {
        $items = FfbMatchroundPeer::doSelect($criteria);
        $matchrounds = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $matchrounds[$i]['matchround_id'] = $item->getMatchroundId();
                $matchrounds[$i]['matchround_title'] = $item->getMatchroundTitle();
                $matchrounds[$i]['matchround_status'] = $item->getMatchroundStatus();
                $matchrounds[$i]['matchround_game_id'] = $item->getMatchroundGameId();
                $matchrounds[$i]['matchround_game_title'] = $item->getFfbGame()->getGameTitle();
                $matchrounds[$i]['matchround_startdate'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $matchrounds[$i]['matchround_enddate'] = date('j.n.Y G:i',strtotime($item->getMatchroundEnddate()));
                $matchrounds[$i]['matchround_deadline'] = date('j.n.Y G:i',strtotime($item->getMatchroundStartdate()));
                $i++;
            }
        }
        $this->numResults = $i;
        $this->matchrounds = $matchrounds;
    }

    //return the most wanted players orderd to a team
    public function getMostWanted() {
    	$now = microtime(true);
    	$id = intval($_REQUEST['matchround_id'] ?? 0);

    	$teamsAtStart = array();
    	$i = 0;

    	// Single query — avoid joining ffb_playerteam twice without aliases (MySQL error 1066).
    	$sql = 'SELECT ffb_team.team_id, ffb_team.team_name, COUNT(ffb_playerteam.playerteam_id) AS plnum '
    		. 'FROM ffb_team '
    		. 'INNER JOIN ffb_playerteam ON ffb_team.team_id = ffb_playerteam.playerteam_team_id '
    		. 'INNER JOIN ffb_userteam ON ('
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id1 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id2 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id3 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id4 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id5 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id6 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id7 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id8 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id9 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id10 OR '
    		. 'ffb_playerteam.playerteam_id = ffb_userteam.userteam_player_id11'
    		. ') '
    		. 'WHERE ffb_userteam.userteam_matchround_id = ? '
    		. 'GROUP BY ffb_team.team_id, ffb_team.team_name '
    		. 'ORDER BY plnum DESC';

    	$con = Propel::getConnection(FfbTeamPeer::DATABASE_NAME);
    	$stmt = $con->prepare($sql);
    	$stmt->execute(array($id));
    	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    		$teamsAtStart[] = array(
    			'teamname' => $row['team_name'],
    			'players' => (int) $row['plnum'],
    		);
    		$i++;
    	}

		$this->numTeams = count($teamsAtStart);
		$num = array();
		foreach($teamsAtStart as $key=>$row) {
			$num[$key] = $row['players'];
		}

		if ($num) {
			array_multisort($num, SORT_DESC, SORT_NUMERIC, $teamsAtStart);
		}
		$this->teams=$teamsAtStart;
		$this->sort = $i;
		$this->time = (microtime(true) - $now)*1000;
	}

	private function playerSort(&$playerList, $toSort) {
		$i = 0;
    	foreach($toSort as $team) {
    		$i++;
    		$inList = false;
    		foreach($playerList as &$tm) {
			  if(strcmp($tm['teamname'], $team->getTeamName())==0){
			     $tm['players']++;//= intval($tm['players'])+ 1;
			     $inList=true;
			     break;
			   }
			}
			if(!$inList) {
			  $tmp=array();
			  $tmp['teamname'] = $team->getTeamName();
			  $tmp['players'] = 1;
			  $playerList[] = $tmp;
			}
		}
		return $i;
	}
}
?>
