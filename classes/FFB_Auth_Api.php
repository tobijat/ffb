<?php

/**
 * FFB_Auth_Api.php
 *
 * @author Gritschacher Tobias
 * @copyright 03/2010
 * @version 0.2
 *
 * Authentication-Klasse für API Authentication;
 *
 */

abstract class FFB_Auth_Api extends FFB_Auth {

    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        $api_pin = $_REQUEST['pin'];
        $criteria = new Criteria();
        $criteria->add(FfbApikeyPeer::APIKEY_KEY, $api_pin);
        $criteria->add(FfbApikeyPeer::APIKEY_STATUS, 1);
        $criteria->setLimit(1);
        $ak = FfbApikeyPeer::doSelect($criteria);

		if($ak) {
			if(($ak[0]->getApikeyIp() && $ak[0]->getApikeyIp() == $_SERVER['REMOTE_ADDR']) || (!$ak[0]->getApikeyIp())) {
				$ak[0]->setApikeyLastcall(date('Y-m-d H:i:s', time()));
				$ak[0]->save();
				return true;
			}
		}

		return false;
    }

    function __destruct() {
        parent::__destruct();
    }
}

?>