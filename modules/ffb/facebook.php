<?php

  /**
 * forum.php
 *
 * @author Musser
 * @copyright 09/2009
 * @version 0.1
 */

  class facebook extends FFB_Auth_No
  {
	  public function __construct()
      {
          parent::__construct();
      }

	  public function fb() {
		header("Location: https://www.facebook.com/soccersportsfan", null, 301);
		die();
	  }

      public function __default()
      {
			$this->fb();
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }