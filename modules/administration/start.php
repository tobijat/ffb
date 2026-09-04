<?php

  /**
 * start.php
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 */

  class start extends FFB_Auth_Admin
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default()
      {
          $navFile = $this->subdomainName.'_admin_navigation.php';
          $this->navFile = $navFile;

          if($this->config->area_prefix == FFB_SUBDOMAIN && $this->session->game_id_admin < 1) {
              $this->navFile = 'plain_navigation.php';
          }

          if($this->config->area_prefix == FFB_SUBDOMAIN) {
              $this->start_ffb();
          } elseif($this->config->area_prefix == PIC_SUBDOMAIN) {
              $this->start_pic();
          }
      }

      private function start_ffb()
      {
          $this->htmlFile = 'start_ffb.php';
          $criteria = new Criteria();
          $criteria->add(FfbAdminPeer::ADMIN_USER_ID, $this->session->user_id);
          $admin_items = FfbAdminPeer::doSelect($criteria);
          $admin_games = array();
          $i=0;
          if($admin_items) {
              foreach($admin_items as $admin_item) {
                  $admin_games[$i]["game_id"] = $admin_item->getAdminGameId();
                  $admin_games[$i]["game_title"] = $admin_item->getFfbGame()->getGameTitle();
                  $i++;
              }
          }
          $this->admin_games = $admin_games;
      }

      private function start_pic()
      {
          $this->htmlFile = 'start_pic.php';
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
