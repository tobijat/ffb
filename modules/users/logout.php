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
              $destination = urldecode($_GET['destination']);
          } elseif (isset($_POST['destination'])) {
              $destination = urldecode($_POST['destination']);
          } else {
              $destination = FFB_BASE_PATH;
          }

          // Notify forum of logout when curl is available (optional side-effect)
          $forumLogout = "http://ffb.gemura.com/forum/ucp.php?mode=logout&sid=$sid";
          if (function_exists('curl_init')) {
              $ch = curl_init($forumLogout);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_TIMEOUT, 5);
              curl_exec($ch);
              curl_close($ch);
          } else {
              $ctx = stream_context_create(array(
                  'http' => array('timeout' => 5, 'ignore_errors' => true)
              ));
              @file_get_contents($forumLogout, false, $ctx);
          }

          header("Location: $destination");
          exit();
      }

      function __destruct()
      {
          parent::__destruct();
      }
  }

?>
