<?php

  /**
 * game.php
 *
 * @author Gritschacher
 * @copyright 07/2008
 * @version 0.1
 */

  class game extends FFB_Auth_User
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default()
      {
      }

	  public function setSelectedGame() {
	  	  $game_id = $_POST['game_id'];
	  	  $user_id = $this->session->user_id;
	  	  if($game_id && $user_id) {
	  		  $act_user = WebUserDetailsPeer::retrieveByPk($user_id);
	  		  if($act_user) {
				  $act_user->setUserDetailsFfbSelectedGame($game_id);
				  $act_user->save();
				  $game = FfbGamePeer::retrieveByPK($game_id);
		          if($game) {
		              $this->session->game_id_player = $game_id;
		              $this->session->game_title_player = $game->getGameTitle();
		              $this->administration_answer = 'Game Player ID Session/UserDetails set to '.$game_id;
		              $this->administration_status = STATUS_CODE_SUCCESS;
		          } else {
		              $this->administration_status = STATUS_CODE_ERROR;
		              $this->administration_answer = 'Game not available! '.$game_id;
		          }
			  }	 else {
		          $this->administration_status = STATUS_CODE_ERROR;
		          $this->administration_answer = 'User not available!';
		      }
		  } else {
		  	  $this->administration_status = STATUS_CODE_ERROR;
		  	  $this->administration_answer = 'No GameId given!';
		  }
	  }

	  public function checkSelectedGame() {
	  	  $user_id = $this->session->user_id;
	  	  if($user_id) {
	  		  $act_user = WebUserDetailsPeer::retrieveByPk($user_id);
	  		  if($act_user) {
				  $game_id = $act_user->getUserDetailsFfbSelectedGame();
				  $this->session->game_id_player = $game_id;
				  $this->administration_status = STATUS_CODE_SUCCESS;
				  $this->selected_game_id = $game_id;
			  }	else {
		          $this->administration_status = STATUS_CODE_ERROR;
		      }
		  } else {
		  	  $this->administration_status = STATUS_CODE_ERROR;
		  }
	  }

/*
      public function setSession()
      {
          $value = $_POST['value'];
          $game = FfbGamePeer::retrieveByPK($value);
          if($game) {
              $this->session->game_id_player = $value;
              $this->session->game_title_player = $game->getGameTitle();
              $this->administration_answer = 'Game Player ID Session set to '.$value;
              $this->administration_status = STATUS_CODE_SUCCESS;
          } else {
              $this->administration_error = 'No DB-entry found for Game ID '.$value.'. Please activate cookies and reload!';
              $this->administration_status = STATUS_CODE_ERROR;
          }
      }

      public function getSession()
      {
          echo 'gameID: '.$this->session->game_id_player.'<br>gameTitle: '.$this->session->game_title_player;
          exit;
      }

      public function clearSession()
      {
          $this->session->game_id_player = 0;
          $this->session->game_title_player = 0;
          $this->administration_answer = 'Session cleared!';
          $this->administration_status = STATUS_CODE_SUCCESS;
      }
*/

      //returns list of all visible games
      public function getGameList()
      {
          $criteria = new Criteria();
          $criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
          $criteria->add(FfbGamePeer::GAME_VISIBLE, 1);
          $criteria->add(FfbGamePeer::GAME_ARCHIVE, 0);
          $criteria->add(FfbGamePeer::GAME_STATUS, 1);
          $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
          $criteria->addAscendingOrderByColumn(FfbGamePeer::GAME_TITLE);
          $criteria->addGroupByColumn(FfbGamePeer::GAME_ID);
          $this->getGamesByCriteria($criteria);
      }

      //returns list of all visible archieved games
      public function getPastGames()
      {
          $criteria = new Criteria();
          $criteria->addJoin(FfbGamePeer::GAME_ID, FfbMatchroundPeer::MATCHROUND_GAME_ID);
          $criteria->add(FfbGamePeer::GAME_VISIBLE, 1);
          $criteria->add(FfbGamePeer::GAME_ARCHIVE, 1);
          $criteria->add(FfbGamePeer::GAME_STATUS, 1);
          $criteria->addAscendingOrderByColumn(FfbMatchroundPeer::MATCHROUND_STARTDATE);
          $criteria->addAscendingOrderByColumn(FfbGamePeer::GAME_TITLE);
          $criteria->addGroupByColumn(FfbGamePeer::GAME_ID);
          $this->getGamesByCriteria($criteria);
      }

      private function getGamesByCriteria($criteria) {
          $items = FfbGamePeer::doSelect($criteria);
          $games = array();
          $i=0;
          if ($items) {
              foreach($items as $item) {
                  $games[$i]["game_id"] = $item->getGameId();
                  $games[$i]["game_title"] = $item->getGameTitle();
                  if($item->getGameVisible())
                      $games[$i]["game_visible"] = 1;
                  else
                      $games[$i]["game_visible"] = 0;
                  if($item->getGameArchive())
                      $games[$i]["game_archive"] = 1;
                  else
                      $games[$i]["game_archive"] = 0;
                  if($item->getGameCountdown())
                      $games[$i]["game_countdown"] = 1;
                  else
                      $games[$i]["game_countdown"] = 0;
                  if($item->getGameStatus())
                      $games[$i]["game_status"] = 1;
                  else
                      $games[$i]["game_status"] = 0;
                  if($item->getGameSymbol())
                      $games[$i]["game_symbol"] = $item->getGameSymbol();
                  else
                      $games[$i]["game_symbol"] = 'symbol_game_na.png';
                  $i++;
              }
          }
          $this->num_results = $i;
          $this->games = $games;
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
