<?php

  /**
 * mailservice.php
 *
 * @author Gritschacher Tobias
 * @copyright 03/2010
 * @version 0.2
 */

class mailservice extends FFB_Auth_AdminFfb
{
    public function __construct()
    {
        parent::__construct();
        $this->htmlFile = 'mailservice.php';
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function __default()
    {
        $this->getMailList();
    }

    public function sendMail() {
    	$user_ids = explode(',',trim($_POST['user_ids']));
    	$message = $_POST['text'];
    	$type = $_POST['type'];
    	$subject = $_POST['subject'];

		$mail = new FFB_Mail($this->config, $user_ids, $subject, $message, $type, 'admin/'.$this->session->user_nickname);

		if($num_send = $mail->send()) {
			$this->status = 200;
			$this->answer = 'The email was sent to '.$num_send.' Users.';
		} else {
			$this->status = 500;
			$this->answer = 'The email could not be send to any user.';
		}

    }

    //gesamte Game-Liste holen
  	public function getGameList() {
  		$criteria = new Criteria();
  		$criteria->add(FfbGamePeer::GAME_STATUS, 1);
  		$criteria->addDescendingOrderByColumn(FfbGamePeer::GAME_ID);
  		$this->getGameByCriteria($criteria);
  	}

  	//gesamte Matchround-Liste holen für game_id
    public function getMatchroundList() {
        $criteria = new Criteria();
        $criteria->add(FfbMatchroundPeer::MATCHROUND_GAME_ID, $_POST['game_id']);
        //$criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1);
        $criteria->addDescendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
        $this->getMatchroundByCriteria($criteria);
    }

    //gesamte Mail-Liste holen
    public function getMailList() {
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(WebMailPeer::MAIL_DATE);
        $this->getMailByCriteria($criteria);
    }

    //get mail for ID
    public function getMailById() {
        $item = WebMailPeer::retrieveByPK($_REQUEST['mail_id']);
        if($item) {
            $mail['mail_id'] = $item->getMailId();
            $mail['mail_sender'] = $item->getMailSender();
            $mail['mail_date'] = $item->getMailDate();
            $mail['mail_subject'] = $item->getMailSubject();
            $mail['mail_text'] = $item->getMailText();
            $mail['mail_num_reciepients'] = $item->getMailNumReciepients();
            $mail['mail_criteria'] = $item->getMailCriteria();
            $user_ids = explode(',',$item->getMailTo());
            $criteria = new Criteria();
            $c1 = $criteria->getNewCriterion(WebUserPeer::USER_ID, $user_ids[0]);
			foreach($user_ids as $id) {
				$c1->addOr($criteria->getNewCriterion(WebUserPeer::USER_ID, $id));
			}
			$criteria->add($c1);
			$this->getUserByCriteria($criteria);
        }
        $this->mail = $mail;
    }

    public function getUserList() {
		$game_id = $_REQUEST['game_id'];
		$choosen_game_id = $_REQUEST['choosen_game_id'];
		$matchround_id = $_REQUEST['matchround_id'];
		$mailservice = $_REQUEST['mailservice'];
		$userstatus = $_REQUEST['userstatus'];
		$request_type = $_REQUEST['request_type'];

		$criteria = new Criteria();
		$criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
		if($userstatus) {
			$criteria->add(WebUserPeer::USER_STATUS, $userstatus);
		}
		if($mailservice) {
			$criteria->addJoin(WebUserPeer::USER_ID, WebUserPermissionsPeer::USER_ID, Criteria::INNER_JOIN);
		}
		if($mailservice == 'info' || $mailservice == 'info_reminder') {
			$criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO, 0, Criteria::NOT_EQUAL);
		}
		if($mailservice == 'reminder' || $mailservice == 'info_reminder') {
			$criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER, 0, Criteria::NOT_EQUAL);
		}
		if($choosen_game_id) {
			$criteria->addJoin(WebUserPeer::USER_ID, WebUserDetailsPeer::USER_ID, Criteria::INNER_JOIN);
			$criteria->add(WebUserDetailsPeer::USER_DETAILS_FFB_SELECTED_GAME, $choosen_game_id);
		} else {
			if($matchround_id) {
				$criteria->addJoin(WebUserPeer::USER_ID, FfbUserteamPeer::USERTEAM_USER_ID, Criteria::INNER_JOIN);
				$criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $matchround_id);
			} else {
				if($game_id) {
					$criteria->addJoin(WebUserPeer::USER_ID, FfbUserscorePeer::USERSCORE_USER_ID, Criteria::INNER_JOIN);
					$criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);
				}
			}
		}


		$this->getUserByCriteria($criteria);
	}

	//returns users by given criteria
    private function getUserByCriteria($criteria) {
        $items = WebUserPeer::doSelect($criteria);
        $users = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $users[$i]['user_id'] = $item->getUserId();
                $users[$i]['user_nickname'] = $item->getUserNickname();
                $users[$i]['user_email'] = $item->getUserEmail();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->users = $users;
    }

    //returns game by given criteria
  	private function getGameByCriteria($criteria) {
  		$items = FfbGamePeer::doSelect($criteria);
  		$games = array();
  		$i=0;
  		foreach($items as $item) {
  			if($item) {
  				$games[$i]['game_id'] = $item->getGameId();
  				$games[$i]['game_title'] = $item->getGameTitle();
  				$games[$i]['game_visible'] = $item->getGameVisible();
  				$games[$i]['game_archive'] = $item->getGameArchive();
  				$games[$i]['game_status'] = $item->getGameStatus();
  				$games[$i]['game_countdown'] = $item->getGameCountdown();
  				$games[$i]['game_description'] = $item->getGameDescription();
  				$i++;
  			}
  		}
  		$this->numResults = $i;
  		$this->games = $games;
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

    //returns mails by given criteria
    private function getMailByCriteria($criteria) {
        $items = WebMailPeer::doSelect($criteria);
        $mails = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $mails[$i]['mail_id'] = $item->getMailId();
                $mails[$i]['mail_sender'] = $item->getMailSender();
                $mails[$i]['mail_date'] = $item->getMailDate();
                $mails[$i]['mail_subject'] = $item->getMailSubject();
                $mails[$i]['mail_text'] = $item->getMailText();
                $mails[$i]['mail_num_reciepients'] = $item->getMailNumReciepients();
                if($mails[$i]['mail_num_reciepients'] == 1) {
					$user = WebUserPeer::retrieveByPK(intval($item->getMailTo()));
					if($user) {
						$mails[$i]['mail_to'] = $user->getUserEmail();
					}
				} else {
					$mails[$i]['mail_to'] = $item->getMailTo();
				}
                $mails[$i]['mail_criteria'] = $item->getMailCriteria();

                $i++;
            }
        }
        $this->numResults = $i;
        $this->mails = $mails;
    }
}
?>
