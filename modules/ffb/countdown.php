<?php

/**
 * @author Musser
 * @copyright 2008
 */


class countdown extends FFB_Auth_No
{
      public function __construct()
      {
          parent::__construct();
          $this->htmlFile = 'countdown.php';
      }

      public function __default() {
      }

	  public function countdown() {
	  	if(strcmp((string)($this->session->countdown ?? ''), 'stop')==0) {
	      $this->stop = "1";
	    } else {

	    $this->stop = "0";
		$now = time();
		$date = date('Y-m-d H:i:s', $now);

		$criteria = new Criteria();
		$criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID, Criteria::INNER_JOIN);
		$criteria->add(FfbMatchroundPeer::MATCHROUND_STARTDATE, $date, Criteria::GREATER_THAN);
		$criteria->add(FfbGamePeer::GAME_COUNTDOWN, 1);
		$criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);

        $rounds = FfbMatchroundPeer::doSelect($criteria);
        $countdown = array();
        foreach($rounds as $round) {
        	$inList = false;
			foreach($countdown as $item) {
				if($item['id']==$round->getMatchroundGameId()) {
					if(strtotime($item['date'])>strtotime($round->getMatchroundStartdate())) {
						$item['date'] = $round->getMatchroundStartdate();
						$item['name'] = $round->getMatchroundTitle();
					}
					$inList = true;
				}
			}
			if(!$inList) {
				$tmp = array();
				$tmp['id'] = $round->getMatchroundGameId();
				$tmp['date'] = strtotime($round->getMatchroundStartdate());
				$tmp['name'] = $round->getMatchroundTitle();
				$countdown[] = $tmp;
			}
			if(count($countdown) == MAX_COUNTDOWN_ENTRIES) {
				break;
			}
			//echo count($countdown).'<br>';
	  }

      $this->roundinfo = $countdown;
      $this->myTime =  time();
      $this->numRounds = count($countdown);
      }
	}

	public function stopCounter() {
		$this->session->countdown = "stop";
	}

	public function startCounter() {
		$this->session->countdown = "start";
	}

	public function __destruct()
    {
		parent::__destruct();
    }
}
?>