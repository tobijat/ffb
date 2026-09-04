<?php

/**
 * FFB-API-Module - CRONJOBS-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 03/2010
 * @version 0.1
 *
 */

class cronjobs extends FFB_Auth_Api {
  private $_cj_interval;
  public function __construct() {
    parent::__construct();
  }

  public function __default() {
  }

  public function executeCronjobs() {
	die();
    echo 'starting cronjobs<br>';
    $now = time();
    $date = date('Y-m-d H:i:s', $now);
    $hour = intval(date('H', $now));

    $criteria = new Criteria();
    $criteria->add(FfbCronjobPeer::CRONJOB_STATUS, 1);
    $criteria->add(FfbCronjobPeer::CRONJOB_TIME_START, $date, Criteria::LESS_EQUAL);
    $criteria->add(FfbCronjobPeer::CRONJOB_TIME_END, $date, Criteria::GREATER_EQUAL);
    $cjs = FfbCronjobPeer::doSelect($criteria);
    if($cjs) {
      foreach($cjs as $cj) {
        $this->_cj_interval = $interval_hours = $cj->getCronjobIntervalHours();
        $lastrun = strtotime($cj->getCronjobTimeLastrun());
        $runhour = $cj->getCronjobRunhour();
        $diff_hours = round(($now - $lastrun)/60/60, 0);

        echo 'diff_hours: '.$diff_hours.'<br>';
        echo 'runhour: '.$runhour.'<br>';
        echo 'hour: '.$hour.'<br>';

        if(($runhour > -1 && $interval_hours > 0 && $diff_hours >= $interval_hours && $runhour == $hour) ||
        ($runhour <= -1 && $interval_hours > 0 && $diff_hours >= $interval_hours) ||
        ($runhour > -1 && $interval_hours <= 0 && $runhour == $hour) ||
        ($runhour <= -1 && $interval_hours <= 0)) {
          $function_name = $cj->getCronjobFunction();
          if(call_user_method($function_name, $this) == true) {
            echo 'job '.$cj->getCronjobId().' executed<br>';
          } else {
            echo 'job '.$cj->getCronjobId().' failed<br>';
          }
          if($cj->getCronjobRunonce() == 1) {
            $cj->setCronjobStatus(0);
          }
          $cj->setCronjobTimeLastrun(date('Y-m-d H:i:s', $now));
          $cj->save();
        }

      }
    }
    echo 'cronjobs finished<br>';
    exit();
  }

  private function test() {
    return true;
  }

  private function testmail() {
    $subject = 'Cronjob Test';
    $text = 'cronjobtest - sent: '.date('d.m.Y H:i:s', time());
    $mail = new FFB_Mail($this->config, array(1), $subject, $text, 'force', 'cronjob/'.$_SERVER['REMOTE_ADDR']);
    $mail->send();
    return true;
  }

  private function lineupReminder() {
    $date = date('Y-m-d H:i:s', time());
    $criteria = new Criteria();
    $criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID, Criteria::INNER_JOIN);
    $criteria->add(FfbGamePeer::GAME_STATUS, 1);
    $criteria->add(FfbGamePeer::GAME_ARCHIVE, 0);
    $criteria->add(FfbGamePeer::GAME_VISIBLE, 1);
    $criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
    $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
    $mrs = FfbMatchroundPeer::doSelect($criteria);

    if($mrs) {
      foreach($mrs as $mr) {
        $deadline = strtotime($mr->getMatchroundStartdate());
        $options = $mr->getFfbGame()->getFfbOptionss();
        $time_before = $options[0]->getOptionsGameRemindHoursBefore() * 60*60;
        $interval = $this->_cj_interval*60*60;
        $diff = $deadline-time();
        if($time_before && ($diff-$interval > 0) && (($diff-$interval) < ($time_before))) {
          echo '('.$mr->getMatchroundId().') '.$mr->getMatchroundTitle().'<br>';
          $this->sendReminderMails($mr);
        }
      }
    }
    return true;
  }

  private function sendReminderMails($mr) {
    $game_id = $mr->getFfbGame()->getGameId();
    $userstatus = 'active';
    $mailservice = 'reminder';

    $criteria = new Criteria();
    $criteria->addAscendingOrderByColumn(WebUserPeer::USER_NICKNAME);
    $criteria->add(WebUserPeer::USER_STATUS, $userstatus);
    $criteria->addJoin(WebUserPeer::USER_ID, WebUserPermissionsPeer::USER_ID, Criteria::INNER_JOIN);
    $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER, 0, Criteria::NOT_EQUAL);
    $criteria->addJoin(WebUserPeer::USER_ID, FfbUserscorePeer::USERSCORE_USER_ID, Criteria::INNER_JOIN);
    $criteria->add(FfbUserscorePeer::USERSCORE_GAME_ID, $game_id);

    $items = WebUserPeer::doSelect($criteria);
    echo 'sending '.count($items).' mails<br>';
    $to_array = array();
    if($items) {
      foreach($items as $item) {
        $criteria = new Criteria();
        $criteria->add(FfbUserteamPeer::USERTEAM_USER_ID, $item->getUserId());
        $criteria->add(FfbUserteamPeer::USERTEAM_MATCHROUND_ID, $mr->getMatchroundId());
        if(!FfbUserteamPeer::doCount($criteria)) {
          $to_array[] = $item->getUserId();
          echo $item->getUserNickname().'/'.$item->getUserStatus().'<br>';
        }
      }
    }
    if(count($to_array)) {
      $subject = 'Aufstellungs-Erinnerung';
      $text = "Hallo {*nickname*}!\n";
      $text .= "Du bist auf http://soccer.sportsfan.at zum Fantasy Soccer Spiel angemeldet und hast bereits an der Liga ";
      $text .= $mr->getFfbGame()->getGameTitle()." teilgenommen.\n";
      $text .= "Die Deadline für folgende Spielrunde ist bald erreicht und du hast noch keine Mannschaft aufgestellt:\n\n";
      $text .= $mr->getFfbGame()->getGameTitle().' - '.$mr->getMatchroundTitle()."\n";
      $text .= "Deadline: ".date('d.m.Y H:i', strtotime($mr->getMatchroundStartdate()))."\n\n";
      $text .= "Melde dich auf http://soccer.sportsfan.at an und stell unter \"Aufstellung\" deine Mannschaft zusammen.";

      //TODO: change when it should be activated
      $mail = new FFB_Mail($this->config, $to_array, $subject, $text, $mailservice, 'cronjob/'.$_SERVER['REMOTE_ADDR']);
      //$mail = new FFB_Mail($this->config, array(1), $subject, $text, $mailservice, 'cronjob/'.$_SERVER['REMOTE_ADDR']);
      $mail->send();
    }
    return true;
  }

  public function checkCatrowebAlive() {
    //$hosts = array('http://catroidwebtest.ist.tugraz.at', 'http://www.catroid.org');
    $hosts = array('http://www.catroid.org');
    $addr = "webmaster@catroid.org";
    $repl = "noreply@ffb.tobijat.at";
    $retr = "-f"."webmaster@tobijat.at";
    $head = "From: "."Catroweb AliveChecker <noreply@ffb.tobijat.at>"."\r\n"."X-Mailer: PHP/".phpversion();

    foreach($hosts as $host) {
      $url = $host."/catroid/aliveCheckerHost";
      $host_ok = $this->checkAlive($url);
      $url = $host."/catroid/aliveCheckerDB";
      $db_ok = $this->checkAlive($url);

      if(!$host_ok || !$db_ok) {
        $subj = "CATROWEB: Problem with host $host!";
        $text = "";
        $text .= "A problem with CATROWEB on host $host was detected at ".date('Y-m-d H:i:s').".\n\n";
        if(!$host_ok) {
          $problem = "The host is not reachable!";
        } else if(!$db_ok){
          $problem = "The connection to the database failed!";
        }
        $text .= "Problem:\n$problem\n\n";

        mail($addr, $subj, wordwrap($text), $head, $retr);
      }
    }
  }

  private function checkAlive($url) {
    $data = fopen($url, "r");
    $resp = stream_get_contents($data);
    if(strcmp($resp, '200') == 0) {
      return true;
    } else {
      return false;
    }
  }
}
?>
