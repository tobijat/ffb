<?php

/**
 * FFB_User.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.2
 */

class FFB_User extends FFB_Object_DB {

    public function __construct($user_id=null) {
        parent::__construct();
        //require_once('ffb/FfbUser.php');
    	require_once('ffb/WebUser.php');
/*
        if($user_id === null) {
            $session = FFB_Session::singleton();
            if(!is_numeric($session->user_id)) {
                $user_id = 0;
            }
            else {
                $user_id = $session->user_id;
            }
        }
*/
/*
    	$curr_user = WebUserPeer::retrieveByPK($user_id);
        if($curr_user) {
            $this->user_id = $curr_user->getUserId();
            $this->user_nickname = $curr_user->getUserNickname();
            $this->user_fname = $curr_user->getUserFname();
            $this->user_lname = $curr_user->getUserLname();
            $this->user_date_register = $curr_user->getUserDateRegister();
            $this->user_date_llogin = $curr_user->getUserDateLlogin();
        }
*/
    }

    public function __destruct() {
        parent::__destruct();
    }
}

?>