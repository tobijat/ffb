<?php

/**
 * FFB_Auth_Admin.php
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 *
 * Authentication-Klasse für Ffb Admin Authentication;
 *
 */

abstract class FFB_Auth_AdminFfb extends FFB_Auth {
    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        return(($this->session->user_id > 0) && ($this->session->admin_flag) && ($this->session->admin_section == FFB_SUBDOMAIN));
    }

    function __destruct() {
        parent::__destruct();
    }
}

?>