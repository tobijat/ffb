<?php

/**
 * FFB-Module - MYTEAM-Klasse;
 *
 * @author Gritschacher Tobias
 * @copyright 09/2009
 * @version 0.1
 *
 */

class myteam extends FFB_Auth_User {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    	/*
    	$user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    	*/
        $this->myteam();
    }

	public function myteam() {
        $this->htmlFile = 'myteam.php';
		$this->htmlTitle = 'Show My Team';
	}
}
?>
