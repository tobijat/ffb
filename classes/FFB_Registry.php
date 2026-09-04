<?php

/**
 * FFB_Registry.php
 *
 * @author Gritschacher Tobias
 * @copyright 11/2009
 * @version 0.1
 */

class FFB_Registry {
    protected static $instance = null;
	protected $values = array();

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new FFB_Registry();
        }
        return self::$instance;
    }

	private function __construct() {
	}

	const KEY_CONFIGURATION = 'config';

    protected function get($var) {
    	if(isset($this->values[$var])) {
    		return $this->values[$var];
    	}
    	return null;
    }

	protected function set($key, $value) {
		$this->values[$key] = $value;
	}

	public function setConfiguration(FFB_Configuration $config) {
		$this->set(self::KEY_CONFIGURATION, $config);
	}

	public function getConfiguration() {
		return $this->get(self::KEY_CONFIGURATION);
	}

	private function __clone() {
	}

    public function __destruct() {

    }
}

?>