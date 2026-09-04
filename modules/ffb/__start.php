<?php

/**
 * FFB-Start-Klasse;
 * FFB-Startseite anzeigen
 *
 * @author Gritschacher Tobias
 * @copyright 12/2009
 * @version 0.2
 *
 */

class __start extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    	if($this->config->area_load_ads == 1) {
			$this->adLeft = $this->advert->getAd('start links');
			$this->adRight = $this->advert->getAd('start rechts');
			$this->adBottomRight = $this->advert->getAd('aufstellung rechts');
		}
		//villacher ads permanent -> WM 2014
		//$this->adBottomRight = $this->advert->getAd('VillacherBierStartseiteRU');
        //guarana brause ads permanent (EM 2016)
        $this->adBottomRight = $this->advert->getAd('GuaranaBrauseStartseiteLoggedIn');

        $this->htmlFile = 'start.php';
        if($this->session->admin_flag == 1) {
		}

        //check for profile details
        $ud = WebUserDetailsPeer::retrieveByPK($this->session->user_id);
        $us = WebUserPeer::retrieveByPK($this->session->user_id);
        if(strcmp($ud->getUserDetailsAvatar(), 'avatar_na.png')==0 && strcmp($ud->getUserDetailsPhoto(), 'profile_na.png')==0 &&
		   !$ud->getUserDetailsZip() && !$ud->getUserDetailsCity() && !$ud->getUserDetailsStreet() && !$ud->getUserDetailsPhone() &&
		   !$ud->getUserDetailsWebsite() && !$us->getUserFname() && !$us->getUserLname()) {
		   		$this->updateProfileNag = 1;
		} else {
		   		$this->updateProfileNag = 0;
		}
        // *****

		//check for new forum messages
		$connection = mysql_connect($this->config->board_database_server, $this->config->board_database_name, $this->config->board_database_pw);
        $db = mysql_select_db($this->config->board_database_name, $connection);
        $username = $this->session->user_nickname;
		$search_request = "SELECT user_lastvisit, user_id FROM ffb_forum_users WHERE username='$username'";
        $search_result = mysql_query($search_request, $connection);
		$num_entries = mysql_num_rows($search_result);
		if($num_entries > 0) {
			$row = mysql_fetch_array($search_result);
			if($this->session->user_forum_lastvisit <= 0) {
            	$this->session->user_forum_lastvisit = $row["user_lastvisit"];
            }
        	$user_forum_id = $row["user_id"];
        	$this->session->user_forum_id = $user_forum_id;
        	$search_request = "SELECT post_time FROM ffb_forum_posts WHERE poster_id!='$user_forum_id' AND forum_id>2 ORDER BY post_time DESC LIMIT 1";
            $search_result = mysql_query($search_request, $connection);
            $row = mysql_fetch_array($search_result);
            $this->session->user_forum_lastpost = $row["post_time"];
            mysql_close($connection);
		} else {
			if($this->session->user_forum_lastvisit <= 0) {
				$this->session->user_forum_lastvisit = 0;
			}
			$this->session->user_forum_lastpost = 1;
		}
		// ***

		//echo 'last visit: '.date('Y-m-d H:i:s',$this->session->user_forum_lastvisit).'<br>';
		//echo 'last post: '.date('Y-m-d H:i:s',$this->session->user_forum_lastpost).'<br>';
    }
}
?>
