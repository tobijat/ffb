<?php

/**
 * FFB_Presenter_common.php
 *  
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1 
 * 
 * Presenter Basis-Klasse: Hat Zugang zu Sessions und User-Objekt;
 * bekommt Modul übergeben; 
 */

abstract class FFB_Presenter_common extends FFB_Object_Web {
    protected $module;
    
    public function __construct(FFB_Module $module) {
        parent::__construct();
        $this->module = $module;
    }
    
    abstract public function display();
    
    public function __destruct() {
        parent::__destruct();
    }
}

?>