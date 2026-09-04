<?php

/**
 * FFB_Auth_Key.php
 *
 * @author Gritschacher Tobias
 * @copyright 08/2008
 * @version 0.1
 *
 * Authentication-Klasse für Pictory Admin Authentication;
 *
 */

abstract class FFB_Auth_AdminPictory extends FFB_Auth {
    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        return(($this->session->user_id > 0) && ($this->session->admin_flag) && ($this->session->admin_section == PIC_SUBDOMAIN));
    }

    function __destruct() {
        parent::__destruct();
    }
}
?>