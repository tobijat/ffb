<?php

/**
 * USER-Start-Klasse;
 * USER-Startseite anzeigen / nicht authentifizierte Seite f�r jedes Modul
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.3
 *
 */

class __start extends FFB_Auth_No {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    	if($this->config->area_load_ads == 1) {
    		$this->adLeft = $this->advert->getAd('start links');
			$this->adRight = $this->advert->getAd('start rechts');
			$this->adBottomRight = $this->advert->getAd('login rechts unten');
		}
		//villacher ads permanent (WM 2014)
		//$this->adVillacherBier = $this->advert->getAd('VillacherBierStartseiteRU');
        //guarana brause ads permanent (EM 2016)
        $this->adGuaranaBrause = $this->advert->getAd('GuaranaBrauseStartseiteRU');

        if($this->session->user_id > 0) {
        	$destination = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->config->area_prefix;
			header("Location: $destination");
			exit();
        } else {
            $this->session->destroy();
            $this->htmlFile = $this->config->area_prefix.'_login.php';
            $this->navFile = $this->config->area_prefix.'_login_navigation.php';
        }

		$this->generalStats();
		$this->loadLeagues();
		$this->loadLastResults();
		//$this->lastForumPosts(); //disabled for WM 2014 -> contains spam only
    }
    
    
    private function generalStats() {
    	$criteria = new Criteria(); 
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('COUNT(' . WebUserPeer::USER_ID . ')'); 
		$this->userCountTotal = WebUserPeer::doSelect($criteria);
		
		$criteria = new Criteria();
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('COUNT(' . WebUserPeer::USER_ID . ')');
		$today = date("Y-m-d 00:00:00"); //nur heute keine 24h
		$criteria->add(WebUserPeer::USER_DATE_LLOGIN, $today, Criteria::GREATER_EQUAL);
		$this->userCountToday = WebUserPeer::doSelect($criteria);
		
		$criteria = new Criteria();
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('COUNT(' . FfbUserteamPeer::USERTEAM_ID . ')');
		$this->userCountUserteams = FfbUserteamPeer::doSelect($criteria);
		
		$criteria = new Criteria();
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('SUM(' . FfbUserteamPeer::USERTEAM_SCORE . ')');
		$this->userCountUserteamScore = FfbUserteamPeer::doSelect($criteria);
        
        
		$criteria = new Criteria();
		$criteria->clearSelectColumns();
		$criteria->add(FfbMatchroundPeer::MATCHROUND_STATUS, 1, Criteria::EQUAL);
		$criteria->addSelectColumn('COUNT(' . FfbMatchroundPeer::MATCHROUND_ID . ')');
		$this->matchrounds = FfbMatchroundPeer::doSelect($criteria);
		
		
		$criteria = new Criteria();
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('SUM(' . FfbMatchPeer::MATCH_HOMESCORE . ')');
		$this->hScore = FfbMatchPeer::doSelect($criteria);
		
		$criteria = new Criteria();
		$criteria->clearSelectColumns(); 
		$criteria->addSelectColumn('SUM(' . FfbMatchPeer::MATCH_GUESTSCORE . ')');
		$this->gScore = FfbMatchPeer::doSelect($criteria); 
    }
    
    
   	private function lastForumPosts() {
		require_once 'modules/ffbapi/forumSQLConnect.php';
		$excludeForums[]	=	2;
		$query	=	"SELECT 
						topic_title, topic_last_post_time, topic_url, topic_id
					FROM ffb_forum_topics
					WHERE ";
		foreach($excludeForums as $elem) {
			$query .= " forum_id!= ". $elem . " AND ";
		}
		$query .=	" forum_id!=1 				 
					ORDER BY topic_last_post_time DESC
					LIMIT 7;";
		$result	=	send_query($query);
		$tmp 	=	array();
		while($row = mysqli_fetch_array($result)) {
			$tmp[]	=	$row;	
		}
		$this->forumPosts	=	$tmp;				
	}
	
	//fuer meta tags Ligaliste
	private function loadLeagues() {
		$criteria	=	new Criteria();
		$criteria->add(FfbGamePeer::GAME_VISIBLE, 1);
		$criteria->add(FfbGamePeer::GAME_ARCHIVE, 0);
		$criteria->add(FfbGamePeer::GAME_COUNTDOWN, 1);
		$criteria->add(FfbGamePeer::GAME_STATUS, 1);
		$this->leagues	= FfbGamePeer::doSelect($criteria);
	}
	
	
	//letzten Soielergebnisse - Newsticker
	private function loadLastResults() {
		$criteria	=	new Criteria();
        $criteria->add(FfbMatchPeer::MATCH_HOMESCORE, -1, Criteria::GREATER_THAN);
		$criteria->add(FfbMatchPeer::MATCH_GUESTSCORE, -1, Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(FfbMatchPeer::MATCH_DATE);
		$criteria->addGroupByColumn(FfbMatchPeer::MATCH_ID);
		$criteria->setLimit(10);
		
		$lres	=	FfbMatchPeer::doSelect($criteria);
		//print_r($lres);
		$results = array();
		$index = 0;
    foreach($lres as $elem) {
        	$guestteam	= FfbTeamPeer::retrieveByPk($elem->getMatchGuestteamId());
        	$hometeam	= FfbTeamPeer::retrieveByPk($elem->getMatchHometeamId());
        	
        	$results[$index]['homeTeam']	=	$hometeam->getTeamName();
          $results[$index]['homeScore'] = $elem->getMatchHomescore();
          //http://soccer.sportsfan.at/images/ffb/flags/aze.gif
          $results[$index]['homeFlag'] = strtolower($hometeam->getTeamNationality());
          $results[$index]['guestTeam'] = $guestteam->getTeamName();
          $results[$index]['guestScore'] = $elem->getMatchGuestscore();
          $results[$index]['guestFlag'] = strtolower($guestteam->getTeamNationality());
          $results[$index]['date'] = date("d.m.Y", strtotime($elem->getMatchDate()));
          $index++;
			$elem		=	null;
    }
		
		$this->lastResults	=	$results;
	}
    
}
?>
