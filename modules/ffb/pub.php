<?php

  /**
 * game.php
 *
 * @author Gerald Musser
 * @copyright 03/2010
 * @version 0.1
 */

class pub extends FFB_Auth_No
{
	public function __construct()
    {
    	parent::__construct();
    }

	public function __default()
    {
    }
      
    public function facebookAwards() {
    	$this->htmlFile	=	'publicAwards.php';
		$fbId	=	trim($_REQUEST['fbid']);
    	$criteria	=	new Criteria();
    	$criteria->add(WebUserPeer::USER_FACEBOOK_ID, $fbId);
    	$webUser 	=	WebUserPeer::doSelect($criteria);
    	if(!$webUser[0]) {
    		$this->webUserId	=	0;
    	} else {
    		$this->webUserId	=	$webUser[0]->getUserId();
    	}    	
    }  
      
      
}