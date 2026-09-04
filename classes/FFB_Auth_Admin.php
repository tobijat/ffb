<?php

/**
 * FFB_Auth_Admin.php
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 * Authentication-Klasse für allgemeine Admin Authentication;
 *
 */

abstract class FFB_Auth_Admin extends FFB_Auth {
    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        return(($this->session->user_id > 0) && ($this->session->admin_flag));
    }

    function __destruct() {
        parent::__destruct();
    }
}

?>