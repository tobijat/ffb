<?php

/**
 * FFB_Auth_User.php
 *
 * @author Gritschacher, Musser
 * @copyright 07/2010
 * @version 0.2
 *
 * Authentication-Klasse f�r User Authentication;
 * wenn erfolgreich eingeloggt wurde, dann ist die user_id in der Session > 0;
 * Kann leicht adaptiert und ge�ndert werden, wenn n�tig;
 *
 * v0.2 - login timeout added
 *
 */

abstract class FFB_Auth_User extends FFB_Auth {

    private $login_timeout  = 7200;//seconds

    function __construct() {
        parent::__construct();
    }

    function authenticate() {
    	if($this->session->user_id > 0) {
    		$user = WebUserPeer::retrieveByPK($this->session->user_id);
    		//login timeout
    		$user_laction_time  = strtotime($user->getUserDateLaction());
			$timeout = $this->config->area_user_login_timeout;
    		if($timeout > 0 && ($user_laction_time+$timeout)<=time()) {
	        	$this->session->user_id = 0;
	        	$this->session->destroy();
	          	//session_destroy();
	          	return false;
	        }

    		$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    		$user->save();
        	return true;
        } else {
			return false;
		}
    }

    function __destruct() {
        parent::__destruct();
    }
}

?>