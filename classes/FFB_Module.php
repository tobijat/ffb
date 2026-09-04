<?php

/**
 * FFB_Module.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 *
 * Basis-Klasse für alle Module; Stellt Basis-Methoden und Interzeptoren zur Verfügung;
 */

abstract class FFB_Module extends FFB_Object_Web {
    protected $data = array();
    public $config;
	protected $advert;
    public $name;
    public $presenter;
    public $tplFile;
    public $htmlFile;
    public $navFile;
    public $moduleName = null;
    public $subdomainName = null;
    public $pageTemplateFile = null;

    public function __construct() {
        parent::__construct();
        $this->subdomainName = isset($_REQUEST['subdomain']) ? $_REQUEST['subdomain'] : null;
        $this->name = $this->me->getName();
    	$this->registry->setConfiguration(new FFB_Configuration($this->subdomainName));
    	$this->registry->setAdvertising(new FFB_Advertising($this->session));
    	$this->config = $this->registry->getConfiguration();
    	$this->advert = $this->registry->getAdvertising();
    }

    //__default-Methode muss es immer geben
    abstract public function __default();

    //zurückgeben des DATA-Arrays
    public function getData() {
        return $this->data;
    }

    //überprüfen ob das Modul korrekt ist
    public static function isValid($module) {
        return (is_object($module) &&
                $module instanceof FFB_Module &&
                $module instanceof FFB_Auth);
    }

    //interceptor: speichert eigenschaften ins data[] array (für mehr Infos: über PHP-Interzeptoren nachlesen)
    public function __set($property, $value) {
        $this->data[$property] = $value;
    }

    // interceptor für unbekannte methoden: ruft die __default-Methode auf
    public function __call($method, $args) {
        //$this->__default($args);

        //6.10.2009: Änderung auf Error-Msg
        echo 'FFB ERROR: function \''.$method.'\' not found!';
        exit();
        //***
    }

    public function __destruct() {
        parent::__destruct();
    }
}

?>