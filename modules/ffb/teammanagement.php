<?php

/**
 * FFB-Module - teammanagement-Class
 *
 * @author Gritschacher, Musser
 * @copyright 06/2008
 * @version 0.3
 *
 */

class teammanagement extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'lineup.php';
        $this->options = new FFB_Options($this->session->game_id_player);
        $user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    }

    public function __default() {
        $this->lineup();
    }

    public function lineup() {
        $this->htmlFile = 'lineup.php';
	}

	public function lineup_v2() {
        $this->htmlFile = 'lineup_v2.php';
	}

	public function myteam() {
        $this->htmlFile = 'myteam.php';
	}

	public function bestteam() {
        $this->htmlFile = 'bestteam.php';
	}

	public function countCredits() {
	    //$user_id=$_GET['id'];
	    $userlist = WebUserPeer::doSelect(new Criteria());

	    if($userlist) {
	        foreach($userlist as $user) {
            $userteam = $user->getFfbUserteams();
            $first_ut = $userteam[0];
            if($first_ut) {
            $credits = 0;
            $nations = '';
            $positions = '';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId1()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId1()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId1()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId2()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId2()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId2()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId3()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId3()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId3()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId4()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId4()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId4()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId5()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId5()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId5()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId6()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId6()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId6()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId7()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId7()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId7()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId8()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId8()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId8()->getFfbPlayer()->getPlayerNationality().' ';
            $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId9()->getPlayerteamPlayerPrice();
            $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId9()->getPlayerteamPlayerPosition().' ';
            $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId9()->getFfbPlayer()->getPlayerNationality().' ';
            if($first_ut->getFfbPlayerteamRelatedByUserteamPlayerId10()) {
                $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId10()->getPlayerteamPlayerPrice();
                $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId10()->getPlayerteamPlayerPosition().' ';
                $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId10()->getFfbPlayer()->getPlayerNationality().' ';
            }
            if($first_ut->getFfbPlayerteamRelatedByUserteamPlayerId11()) {
                $credits += $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId11()->getPlayerteamPlayerPrice();
                $positions .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId11()->getPlayerteamPlayerPosition().' ';
                $nations .= $first_ut->getFfbPlayerteamRelatedByUserteamPlayerId11()->getFfbPlayer()->getPlayerNationality().' ';
            }
            echo 'user: '.$user->getUserNickname().'<br>email: '.$user->getUserEmail().'<br>credits: '.$credits.'<br>nations: '.$nations.'<br>position: '.$positions.'<br><br>';
            }
            }
        }
        exit();
    }

    public function saveLineup() {
        $matchround_id = $_POST['matchround_id'];
        $lineup_string = trim($_POST['lineup']);
        $sum_price = $_POST['sum_price'];
        $lineup_ids = explode(',', $lineup_string);
        $user_id = $this->session->user_id;
        $game_id = $this->session->game_id_player;

        if(!$this->checkMatchround($matchround_id)) {
            $this->ffb_error = 'Die Deadline f&uuml;r diese Spielrunde ist bereits vor&uuml;ber! Deine Aufstellung wurde nicht gespeichert!';
            $this->ffb_answer = 0;
            $this->ffb_status = STATUS_CODE_ERROR;
            return;
        }

        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $user_id);
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
        $exist_item = FfbUserteamPeer::doSelect($criteria);
        if($exist_item) {
            $entry = $exist_item[0];
            $this->ffb_answer = 'Deine Aufstellung wurde aktualisiert!';
        } else {
            $entry = new FfbUserteam();
            $entry->setUserteamUserId($user_id);
            $entry->setUserteamMatchroundId($matchround_id);
            $this->ffb_answer = 'Deine Aufstellung wurde gespeichert!';
        }
        $now = time();
        $date = date('Y-m-d H:i:s', $now);
        //$player_price_sum = 0;
        $player_price_sum = $sum_price;
        $pm = $this->options->options_game_pointsmode;

        $entry->setUserteamPlayerId1($lineup_ids[0]);
        $entry->setUserteamPlayerId2($lineup_ids[1]);
        $entry->setUserteamPlayerId3($lineup_ids[2]);
        $entry->setUserteamPlayerId4($lineup_ids[3]);
        $entry->setUserteamPlayerId5($lineup_ids[4]);
        $entry->setUserteamPlayerId6($lineup_ids[5]);
        $entry->setUserteamPlayerId7($lineup_ids[6]);
        $entry->setUserteamPlayerId8($lineup_ids[7]);
        $entry->setUserteamPlayerId9($lineup_ids[8]);
        $entry->setUserteamPlayerId10($lineup_ids[9]);
        $entry->setUserteamPlayerId11($lineup_ids[10]);

        $entry->setUserteamDate($date);
        $entry->setUserteamScore(0);
        $entry->setUserteamPrice($player_price_sum);

        $entry->save();

        $criteria = new Criteria();
        $criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $user_id);
        $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
        if(!FfbUserscorePeer::doCount($criteria)) {
			$new_us = new FfbUserscore();
			$new_us->setUserscoreUserId($user_id);
			$new_us->setUserscoreGameId($game_id);
			$new_us->setUserscoreTotal(0);
			$new_us->setUserscoreWcPoints(0);
			$new_us->save();
		}

        $this->ffb_error = 0;
        $this->ffb_status = STATUS_CODE_SUCCESS;

        return;
    }

	//adds the user lineup to the database
	//used by lineup.js
	public function addUserTeam() {
	    //echo 'csr: '.$_POST['current_selected_round'].'<br>';
        $this->current_selected_round = $_POST['current_selected_round'];
        $string= $_POST['lineupids'];
        $string = str_replace('\\"', '\'', $string);

            //load the xml to the xml-parser
            if($string) {

               if(!$lineupXML = simplexml_load_string($string)) {
                   echo "problem with simplexml_load_string";
                   exit();
               }
            } else {
               return;
            }
            //*****

        //set default values
        $userteam_user_id = $this->session->user_id;
        $userteam_score = 0;
        //*****

        //$userteam_matchround_id = 0;  //TODO: get the right matchround id
        $attr = $lineupXML->matchround->attributes();
        $userteam_matchround_id = $attr['id'];

        if(!$userteam_matchround_id)
        {
            $this->fantasyfootball_error = 'No ID for matchround given!';
            $this->fantasyfootball_answer = STATUS_CODE_ERROR;
            return;
        }

        //check if the matchround has not began
        if($this->checkMatchround($userteam_matchround_id)) {
            //get the playerteam-ids from xml
            $players = array();
            foreach($lineupXML->myplayer as $entry) {
                $attr = $entry->attributes();
                $players[] = $attr['playerteamid'];
            }
            //*****

            $criteria = new Criteria();
            $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $this->session->user_id);
            $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $userteam_matchround_id);
            $exist_item = FfbUserteamPeer::doSelect($criteria);

            if($exist_item) {
                $entry = $exist_item[0];
                $answer = 'Deine Aufstellung wurde aktualisiert!';
            } else {
                $entry = new FfbUserteam();
                $entry->setUserteamUserId($userteam_user_id);
                $entry->setUserteamMatchroundId($userteam_matchround_id);
                $answer = 'Deine Aufstellung wurde gespeichert!';
            }
            $now = time();
            $date = date('Y',$now).'-'.date('m',$now).'-'.date('d',$now).' '.date('H',$now).':'.date('i',$now).':'.date('s',$now);
            $player_price_sum = 0;
            $pm = $this->options->options_game_pointsmode;
            //echo '-'.$pm;
            foreach($players as $playerteam_id) {
                if($pm == 'new') {
                    //echo '-';
                    $criteria = new Criteria();
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_PLAYERTEAM_ID, $playerteam_id);
                    $criteria->add(FfbPlayerpricePeer::PLAYERPRICE_MATCHROUND_ID, $userteam_matchround_id);
                    $criteria->setLimit(1);
                    $items = FfbPlayerpricePeer::doSelect($criteria);
                    if($items) {
                        $item = $items[0];
                        $player_price_sum += $items[0]->getPlayerpricePrice();
                    }
                } else {
                    //echo '--';
                    $item = FfbPlayerteamPeer::retrieveByPK($playerteam_id);
                    $player_price_sum += $item->getPlayerteamPlayerPrice();
                }
            }
            //fill in the userteam-entries
            $entry->setUserteamScore($userteam_score);
            $entry->setUserteamPrice($player_price_sum);
            $entry->setUserteamPlayerId1($players[0]);
            $entry->setUserteamPlayerId2($players[1]);
            $entry->setUserteamPlayerId3($players[2]);
            $entry->setUserteamPlayerId4($players[3]);
            $entry->setUserteamPlayerId5($players[4]);
            $entry->setUserteamPlayerId6($players[5]);
            $entry->setUserteamPlayerId7($players[6]);
            $entry->setUserteamPlayerId8($players[7]);
            $entry->setUserteamPlayerId9($players[8]);
            $entry->setUserteamPlayerId10($players[9]);
            $entry->setUserteamPlayerId11($players[10]);
            $entry->setUserteamDate($date);
            $entry->save();

            $this->fantasyfootball_answer = $answer;
            $this->fantasyfootball_status = STATUS_CODE_SUCCESS;
        } else {
            $this->fantasyfootball_error = 'Die Deadline ist vor&uuml;ber. Deine Aufstellung wurde nicht ber&uuml;cksichtigt!<br>Du kannst aber deine Aufstellung f&uuml;r die n&auml;chste Runde bekanntgeben.';
            $this->fantasyfootball_answer = STATUS_CODE_ERROR;
        }
    }

    //check if the matchround has not began
    public function checkMatchround($id) {
        $now = time();
        $date = date('Y', $now).'-'.date('n', $now).'-'.date('j', $now).' '.date('G', $now).':'.date('i', $now).':'.date('s', $now);
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_ID, $id);
        $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
        $item = FfbMatchroundPeer::doSelect($criteria);

        if($item)
        { return true; }
        else {
            return false;
        }
    }
}
?>
