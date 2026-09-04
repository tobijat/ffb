<?php

/**
 * FFB-Module - Amazon store-Klasse;
 *
 * @author Gerald Musser
 * @copyright 12/2009
 * @version 0.1
 *
 */

class astore extends FFB_Auth_No {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
        $this->aStore();
    }
    
    private function aStore() {
		$this->htmlTitle = 'Amazon Web Store';
    	return;
    }
    
}
?>