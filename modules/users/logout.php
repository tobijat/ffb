<?php

  /**
 * logout.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 */

  class logout extends FFB_Auth_User
  {
      function __construct()
      {
          parent::__construct();
      }

      function __default()
      {
          $this->logout();
      }

      function logout()
      {
          $sid  = session_id();
          $this->session->destroy();
          if (isset($_GET['destination'])) {
              $go = urldecode($_GET['destination']);
          } elseif (isset($_POST['destination'])) {
              $destination = urldecode($_POST['destination']);
          } else {
              $destination = FFB_BASE_PATH;
          }
          
          $ch = curl_init("http://ffb.gemura.com/forum/ucp.php?mode=logout&sid=$sid");
          curl_setopt($ch,	CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, 	CURLOPT_TIMEOUT,		5);
          $ret = curl_exec($ch);
          curl_close($ch);

          header("Location: $destination");
          exit();
      }

      function __destruct()
      {
          parent::__destruct();
      }
  }

?>
