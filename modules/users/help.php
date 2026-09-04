<?php
/**
 * help.php
 *
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 02/2010
 * @version 0.2
 */

  class help extends FFB_Auth_No
  {
      public function __construct()
      {
          parent::__construct();
          $this->options = new FFB_Options($this->session->game_id_player);
      }

      public function __default()
      {
          $this->start();
      }

      public function start()
      {
          $this->htmlFile = 'help.php';
          if($this->session->user_id > 0) {
			  $this->navFile = $this->config->area_prefix.'_account_navigation.php';
		  } else {
		  	  $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
		  }
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
