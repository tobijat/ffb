<?php

/**
 * FFB_Object_Web.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 *
 * Basis-Klasse für Module die DB, Sessions und User-Management brauchen;
 * Stellt Session-Objekt und User-Objekt zur Verfügung und wird von der FFB_Object_DB abgeleitet
 *
 */

abstract class FFB_Object_Web extends FFB_Object_DB {
    protected $session;

    public function __construct() {
        parent::__construct();
        $this->session = FFB_Session::singleton();
    }

    public function _destruct() {
        parent::_destruct();
    }
}

?>