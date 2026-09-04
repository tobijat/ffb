<?php

  /**
 * forum.php
 *
 * @author Musser
 * @copyright 09/2009
 * @version 0.1
 */

  class forum extends FFB_Auth_User
  {
	  public function __construct()
      {
          parent::__construct();
          $this->htmlFile = 'forum.php';
		  $this->htmlTitle = 'Forum';
          $this->browserHeight = 5120;
          $this->session->user_forum_lastvisit = time();
      }

	  public function forum() {
        $this->htmlFile = 'forum.php';
        if($this->session->game_id_player < 1) {
            $this->navFile = 'plain_navigation.php';
        } else {
            $this->navFile = '';
        }
        /*
        $user = WebUserPeer::retrieveByPK($this->session->user_id);
    	$user->setUserDateLaction(date('Y-m-d H:i:s', time()));
    	$user->save();
    	*/
	  }

      public function __default()
      {
      	$this->forum();
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }