<?php

  /**
 * game.php
 *
 * @author Gritschacher Tobias
 * @copyright 07/2009
 * @version 0.1
 */

class gamedata extends FFB_Auth_User {
    public function __construct() {
        parent::__construct();
    }

    public function __default() {
        $this->htmlFile = 'gamedata.php';
    }












    public function __destruct() {
        parent::__destruct();
    }
}

?>
