<?php

/**
 * FFB_Auth.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 *
 * Klasse für Module die vorher Authentication benötigen;
 * die Methode authenticate() muss implementiert sein
 *
 */

abstract class FFB_Auth extends FFB_Module {
    function __construct() {
        parent::__construct();
    }

    abstract function authenticate();

    function __destruct() {
        parent::__destruct();
    }
}

?>