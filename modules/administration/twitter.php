<?php

/**
 * ADMIN - Twitter-Klasse;
 * Twitter Nachrichten an fsoccer senden
 * usw.
 *
 * @author Musser
 * @copyright 02/2010
 * @version 0.1
 *
 */

require_once('modules/ffbapi/twitter.lib.php');

class ffbtwitter extends FFB_Auth_AdminFfb {
	
	
	//private static $twitterPW = 'soccer0815';
	//private static $twitterAccount = 'fsoccer';
	//private static $twitterLogin;

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
        $this->twitterMsg();
    }

	public function twitterMsg() {
		$message = $_REQUEST['twittermsg'];
		$this->message1 = $message;
		$len = strlen($message);
		$msgArray = str_split($message, 140);
		$twitter = new Twitter('fsoccer', 'soccer0815', 'ffb');
		for($i=0; ($i<sizeof($msgArray)) && $len>0; $i++) {
			$return =  $twitter->updateStatus($msgArray[i]);
			$this->resultAnswer = $return;
		}
		$twitter->endSession();
	}

}
?>
