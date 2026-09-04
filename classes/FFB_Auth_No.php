<?php

/**
 * FFB_Auth_No.php
 *  
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1 
 * 
 * Klasse für Module die keine Authentication benötigen;
 * die Methode authenticate() returned immer TRUE   
 */

abstract class FFB_Auth_No extends FFB_Auth {
    function __construct() {
        parent::__construct();
    }
    
    function authenticate() {
        return true;
    }
    
    function __destruct() {
        parent::__destruct();
    }
}

?>