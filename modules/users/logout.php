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
          $this->session->destroy();
          if (isset($_GET['destination'])) {
              $destination = urldecode($_GET['destination']);
          } elseif (isset($_POST['destination'])) {
              $destination = urldecode($_POST['destination']);
          } else {
              $destination = FFB_BASE_PATH;
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
