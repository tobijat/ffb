<?php

  /**
 * reference.php
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 */

  class reference extends FFB_Auth_No
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default()
      {
          $this->start();
      }

      public function start()
      {
          $this->htmlFile = 'reference.php';
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
