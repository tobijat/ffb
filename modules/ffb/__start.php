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
    }
}
?>
