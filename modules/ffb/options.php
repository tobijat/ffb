<?php

  /**
 * game.php
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 */

  class options extends FFB_Auth_User
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default()
      {
      }


      public function getLineupOptions()
      {
          $game_id = $this->session->game_id_player;
          $criteria = new Criteria();
          $criteria->add(FfbOptionsPeer::OPTIONS_GAME_ID, $game_id);
          $criteria->setLimit(1);
          $optionsline = FfbOptionsPeer::doSelect($criteria);
          $options = array();
          if($optionsline) {
              $options['lineup_max_players'] = $optionsline[0]->getOptionsLineupMaxPlayers();
              $options['lineup_max_credits'] = $optionsline[0]->getOptionsLineupMaxCredits();
              $options['lineup_max_players_team'] = $optionsline[0]->getOptionsLineupMaxPlayersTeam();
              $options['lineup_min_g'] = $optionsline[0]->getOptionsLineupMinG();
              $options['lineup_min_d'] = $optionsline[0]->getOptionsLineupMinD();
              $options['lineup_min_m'] = $optionsline[0]->getOptionsLineupMinM();
              $options['lineup_min_s'] = $optionsline[0]->getOptionsLineupMinS();
              $options['lineup_max_g'] = $optionsline[0]->getOptionsLineupMaxG();
              $options['lineup_max_d'] = $optionsline[0]->getOptionsLineupMaxD();
              $options['lineup_max_m'] = $optionsline[0]->getOptionsLineupMaxM();
              $options['lineup_max_s'] = $optionsline[0]->getOptionsLineupMaxS();
              $options['game_pricemode'] = $optionsline[0]->getOptionsGamePricemode();
          }
          $this->options = $options;
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
