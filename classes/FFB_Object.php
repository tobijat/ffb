<?php

/**
 * FFB_Object.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 *
 * Basis-Klasse für so ziemlich alles
 *
 */

abstract class FFB_Object {
    protected $me;
	protected $registry;

    public function __construct() {
        $this->me = new ReflectionClass($this);
    	$this->registry = FFB_Registry::getInstance();
    }

    public function __destruct() {
    }
}


?>