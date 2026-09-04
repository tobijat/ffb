<?php

/**
 * FFB - USER-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.2
 *
 * liefert Liste der user zur Abfrage der Userteams
 *
 */

class user extends FFB_Auth_User {

    private $options;

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'userscore.php';
        $this->options = new FFB_Options($this->session->game_id_player);
    }

    public function __default() {
    }

    //returns list of users which have at least one userteam
    //used by myteam.js
    public function getUsersWithTeams() {
    	$criteria = new Criteria();
    	$criteria->addJoin(WebUserPeer::USER_ID, FfbUserteamPeer::USERTEAM_USER_ID, Criteria::INNER_JOIN);
    	$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $_POST['matchround_id'] ?? 0);
    	$criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
    	$list = WebUserPeer::doSelect($criteria);
		$users = array();
		$i = 0;
		if($list) {
            foreach($list as $item) {
                $users[$i]['user_id'] = $item->getUserId();
                $users[$i]['user_nickname'] = $item->getUserNickname();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->users = $users;
    	return;
    }

    public function getUserDetails() {
		$user_id = $_POST['user_id'] ?? null;
		//$user_id = 1;

    	$this->user = $this->returnUserDetailsById($user_id);
    	$this->participations = $this->returnUserParticipations($user_id);
    }

	private function returnUserDetailsById($user_id) {
		$user = WebUserPeer::retrieveByPK($user_id);
		$user_details = WebUserDetailsPeer::retrieveByPK($user_id);
		$user_perms = WebUserPermissionsPeer::retrieveByPK($user_id);
		$user_arr = array();
		if($user && $user_details && $user_perms) {
			$user_arr['user_id'] = $user->getUserId();
			if($user_arr['user_id'] == $this->session->user_id) {
				$user_arr['user_ownprofile'] = 1;
			} else {
				$user_arr['user_ownprofile'] = 0;
			}
			$user_arr['user_nickname'] = $user->getUserNickname();
			$user_arr['user_date_llogin'] = date('d.m.Y', strtotime($user->getUserDateLlogin()));
			$user_arr['user_date_register'] = date('d.m.Y', strtotime($user->getUserDateRegister()));
			$user_arr['user_email'] = $user->getUserEmail();
			$user_arr['user_fname'] = $user->getUserFname();
			$user_arr['user_lname'] = $user->getUserLname();
			$user_arr['user_gender'] = $user->getUserGender();
			$user_arr['user_nationality'] = $user->getUserNationality();
			if($user->getUserDateBirth()) {
				$user_arr['user_date_birth'] = date('d.m.Y', strtotime($user->getUserDateBirth()));
			} else {
				$user_arr['user_date_birth'] = 0;
			}

			$user_arr['user_details_avatar'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/avatar/'.$user_details->getUserDetailsAvatar();
			if(strcmp($user_details->getUserDetailsPhoto(), 'profile_na.png') == 0) {
				if($user->getUserGender()) {
					$user_arr['user_details_photo'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/photo/'.$user->getUserGender().'_'.$user_details->getUserDetailsPhoto();
				} else {
					$user_arr['user_details_photo'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/photo/'.$user_details->getUserDetailsPhoto();
				}
			} else {
				$user_arr['user_details_photo'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/photo/'.$user_details->getUserDetailsPhoto();
			}
			$user_arr['user_details_zip'] = $user_details->getUserDetailsZip();
			$user_arr['user_details_city'] = $user_details->getUserDetailsCity();
			$user_arr['user_details_street'] = $user_details->getUserDetailsStreet();
			$user_arr['user_details_phone'] = $user_details->getUserDetailsPhone();
			$user_arr['user_details_website'] = $user_details->getUserDetailsWebsite();
			if($user_details->getUserDetailsFfbFavouriteTeam()) {
				$user_arr['user_details_favteam_id'] = $user_details->getUserDetailsFfbFavouriteTeam();
				$user_arr['user_details_favteam_name'] = $user_details->getFfbTeamRelatedByUserDetailsFfbFavouriteTeam()->getTeamName();
				$user_arr['user_details_favteam_nationality'] = $user_details->getFfbTeamRelatedByUserDetailsFfbFavouriteTeam()->getTeamNationality();
			} else {
				$user_arr['user_details_favteam_id'] = 0;
				$user_arr['user_details_favteam_name'] = 0;
				$user_arr['user_details_favteam_nationality'] = 0;
			}
			if($user_details->getUserDetailsFfbOwnTeam()) {
				$user_arr['user_details_ownteam_id'] = $user_details->getUserDetailsFfbOwnTeam();
				$user_arr['user_details_ownteam_name'] = $user_details->getFfbTeamRelatedByUserDetailsFfbOwnTeam()->getTeamName();
				$user_arr['user_details_ownteam_nationality'] = $user_details->getFfbTeamRelatedByUserDetailsFfbOwnTeam()->getTeamNationality();
			} else {
				$user_arr['user_details_ownteam_id'] = 0;
				$user_arr['user_details_ownteam_name'] = 0;
				$user_arr['user_details_ownteam_nationality'] = 0;
			}

			$user_arr['user_perm_profile'] = $user_perms->getUserPermissionsFfbVisibleProfile();

			if(!$user_arr['user_fname']) {
				$user_arr['user_fname'] = 0;
			}
			if(!$user_arr['user_lname']) {
				$user_arr['user_lname'] = 0;
			}
			if(!$user_arr['user_gender']) {
				$user_arr['user_gender'] = 0;
			}
			if(!$user_arr['user_nationality']) {
				$user_arr['user_nationality'] = 0;
			}
			if(!$user_arr['user_details_zip']) {
				$user_arr['user_details_zip'] = 0;
			}
			if(!$user_arr['user_details_city']) {
				$user_arr['user_details_city'] = 0;
			}
			if(!$user_arr['user_details_street']) {
				$user_arr['user_details_street'] = 0;
			}
			if(!$user_arr['user_details_phone']) {
				$user_arr['user_details_phone'] = 0;
			}
			if(!$user_arr['user_details_website']) {
				$user_arr['user_details_website'] = 0;
			}
			if(!$user_arr['user_perm_profile']) {
				$user_arr['user_perm_profile'] = 0;
			}
		}
		return $user_arr;
	}

	private function returnUserParticipations($user_id) {
		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbUserscorePeer::USERSCORE_GAME_ID, Criteria::INNER_JOIN);
		$criteria->add(FfbUserscorePeer::USERSCORE_USER_ID, $user_id);
		$criteria->add(FfbGamePeer::GAME_STATUS, 1);
		$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_GAME_ID);

		$userscores = FfbUserscorePeer::doSelect($criteria);
		$participations = array();
		if($userscores) {
			$i = 0;
			foreach($userscores as $item) {
				$options = $item->getFfbGame()->getFfbOptionss();
				$rm = $options[0]->getOptionsGameRankmode();
				$participations[$i]['game_id'] = $item->getFfbGame()->getGameId();
				$participations[$i]['game_title'] = $item->getFfbGame()->getGameTitle();
				$participations[$i]['game_archive'] = $item->getFfbGame()->getGameArchive();
				if($item->getFfbGame()->getGameSymbol()) {
					$participations[$i]['game_symbol'] = $item->getFfbGame()->getGameSymbol();
				} else {
					$participations[$i]['game_symbol'] = 0;
				}
				$participations[$i]['score_rm'] = $rm;
				$participations[$i]['score_wc'] = $item->getUserscoreWcPoints();
				$participations[$i]['score_points'] = $item->getUserscoreTotal();
				if($participations[$i]['game_archive']) {
					$criteria = new Criteria();
					$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $participations[$i]['game_id']);
					$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
					$criteria->setLimit(1);
					$matchround = FfbMatchroundPeer::doSelect($criteria);
					if($matchround) {
						$participations[$i]['score_start'] = date('d.m.y', strtotime($matchround[0]->getMatchroundStartdate()));
					} else {
						$participations[$i]['score_start'] = 0;
					}
					$criteria = new Criteria();
					$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $participations[$i]['game_id']);
					$criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_ENDDATE);
					$criteria->setLimit(1);
					$matchround = FfbMatchroundPeer::doSelect($criteria);
					if($matchround) {
						$participations[$i]['score_end'] = date('d.m.y', strtotime($matchround[0]->getMatchroundEnddate()));
					} else {
						$participations[$i]['score_end'] = 0;
					}
				} else {
					$criteria = new Criteria();
					$criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $participations[$i]['game_id']);
					$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
					$criteria->setLimit(1);
					$matchround = FfbMatchroundPeer::doSelect($criteria);
					if($matchround) {
						$participations[$i]['score_start'] = date('d.m.y', strtotime($matchround[0]->getMatchroundStartdate()));
						$participations[$i]['score_end'] = 'jetzt';
					} else {
						$participations[$i]['score_start'] = 0;
						$participations[$i]['score_end'] = 0;
					}
				}


				$participations[$i]['user_rank'] = $this->calculateUserRank($user_id, $participations[$i]['game_id'], $rm);
				$i++;
			}
		}

		return $participations;
	}

/*
	public function testCalcRanking() {
		echo $this->calculateUserRank(1, 6, 'points');
		exit();
	}
*/

	private function calculateUserRank($user_id, $game_id, $rm) {
		$criteria = new Criteria();
		$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
		if($rm == 'wc') {
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_WC_POINTS);
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_TOTAL);
		} elseif($rm == 'points') {
			$criteria->addDescendingOrderByColumn(FfbUserscorePeer::USERSCORE_TOTAL);
		}
		$uc_items = FfbUserscorePeer::doSelect($criteria);
		if($uc_items) {
			$last_score = 10000;
			$last_points = 10000;
			$rank = 1;
			$i = 0;
			foreach($uc_items as $item) {
				$i++;
				if($rm == 'wc') {
					if($item->getUserscoreWcPoints() < $last_score) {
						$last_score = $item->getUserscoreWcPoints();
						$last_points = $item->getUserscoreTotal();
						$rank = $i;
					} else {
						if($item->getUserscoreTotal() < $last_points) {
							$last_points = $item->getUserscoreTotal();
							$rank = $i;
						}
					}
				} elseif($rm == 'points') {
					if($item->getUserscoreTotal() < $last_score) {
						$last_score = $item->getUserscoreTotal();
						$rank = $i;
					}
				}
				if($item->getUserscoreUserId() == $user_id) {
					return $rank;
				}
			}
		}
		return 0;
	}
}
?>
