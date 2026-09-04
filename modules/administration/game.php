<?php

  /**
 * game.php
 *
 * @author Gritschacher Tobias
 * @copyright 10/2009
 * @version 0.2
 */

  class game extends FFB_Auth_AdminFfb
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default() {
      	$this->htmlFile = 'game.php';
      	$this->administration_modus = $_POST['administration_modus'] ?? null;
      	$this->post = $_POST;
      	if (!empty($_POST)) {
      		if(isset($_POST['game_administration_change_x']) || isset($_POST['game_administration_change']))
      		{ $this->changeItem($_POST['game_id']); }
      		elseif(isset($_POST['game_administration_delete_x']) || isset($_POST['game_administration_delete']))
      		{
      			if($this->validateDelete($_POST['game_id']))
      				$this->deleteItem($_POST['game_id']);
      			else {
      				$errors = array();
      			}
      		}
      		else {
      			if($this->validate()) {
      				if(isset($_POST['game_administration_insert']))
      				{ $this->addItem(); }
      				elseif(isset($_POST['game_administration_update']))
      				{ $this->updateItem($_POST['game_id']); }
      			} else
      			{ $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
      		}
      	}
      	$this->getList();
      }

  	//gesamte Game-Liste holen
  	public function getList() {
  		$criteria = new Criteria();
  		$criteria->addDescendingOrderByColumn(FfbGamePeer::GAME_ID);
  		$this->getGameByCriteria($criteria);
  	}

  	//returns game by given criteria
  	private function getGameByCriteria($criteria) {
  		$items = FfbGamePeer::doSelect($criteria);
  		$games = array();
  		$i=0;
  		foreach($items as $item) {
  			if($item) {
  				$games[$i]['game_id'] = $item->getGameId();
  				$games[$i]['game_title'] = $item->getGameTitle();
  				$games[$i]['game_visible'] = $item->getGameVisible();
  				$games[$i]['game_archive'] = $item->getGameArchive();
  				//$games[$i]['game_status'] = $item->getGameStatus();
  				//$games[$i]['game_countdown'] = $item->getGameCountdown();
  				//$games[$i]['game_description'] = $item->getGameDescription();
  				$i++;
  			}
  		}
  		$this->numResults = $i;
  		$this->games = $games;
  	}

  	public function setSelectedGame() {
	  	  $game_id = $_POST['game_id'];
	  	  $user_id = $this->session->user_id;
	  	  $game = FfbGamePeer::retrieveByPK($game_id);
		  if($game) {
		      $this->session->game_id_admin = $game_id;
	          $this->session->game_title_admin = $game->getGameTitle();
		      $this->administration_answer = 'Game Player ID Session/UserDetails set to '.$game_id;
		      $this->administration_status = STATUS_CODE_SUCCESS;
		  } else {
		      $this->administration_status = STATUS_CODE_ERROR;
		  	  $this->administration_answer = 'Game not available! '.$game_id;
		  }
	  	  /*
	  	  if($game_id && $user_id) {
	  		  $act_user = WebUserDetailsPeer::retrieveByPk($user_id);
	  		  if($act_user) {
				  $act_user->setUserDetailsFfbSelectedGame($game_id);
				  $act_user->save();
				  $game = FfbGamePeer::retrieveByPK($game_id);
		          if($game) {
		              $this->session->game_id_admin = $game_id;
		              $this->session->game_title_admin = $game->getGameTitle();
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
		  */
	  }

	  public function checkSelectedGame() {
	  	  $game_id = $this->session->game_id_admin;
	  	  if($game_id > 0) {
			  $this->administration_status = STATUS_CODE_SUCCESS;
			  $this->selected_game_id = $game_id;
	  	  } else {
	  	  	  $user_id = $this->session->user_id;
		  	  if($user_id) {
		  		  $act_user = WebUserDetailsPeer::retrieveByPk($user_id);
		  		  if($act_user) {
					  $game_id = $act_user->getUserDetailsFfbSelectedGame();
					  $this->session->game_id_admin = $game_id;
					  $this->administration_status = STATUS_CODE_SUCCESS;
					  $this->selected_game_id = $game_id;
				  }	else {
			          $this->administration_status = STATUS_CODE_ERROR;
			      }
			  } else {
			  	  $this->administration_status = STATUS_CODE_ERROR;
			  }
		  }
	  }

      //returns list of all visible games
      public function getGameList()
      {
          $criteria = new Criteria();
          $criteria->add(FfbGamePeer::GAME_VISIBLE, 1);
          $this->getGamesByCriteria($criteria);
      }

      //returns list of games for given admin
      public function getGamesForAdmin()
      {
          $user_id = $this->session->user_id;
          $criteria = new Criteria();
          $criteria->add(FfbAdminPeer::ADMIN_USER_ID, $user_id);
          $items = FfbAdminPeer::doSelect($criteria);
          $criteria = new Criteria();

          if($items) {
              foreach($items as $item) {
                  $c1 = $criteria->getNewCriterion(FfbGamePeer::GAME_ID, $item->getAdminGameId());
                  $criteria->addOr($c1);
              }
              $criteria->addAscendingOrderByColumn(FfbGamePeer::GAME_ARCHIVE);
              $criteria->addAscendingOrderByColumn(FfbGamePeer::GAME_TITLE);
              $this->getGamesByCriteria($criteria);
          } else {
              // Keep XML/AJAX contract: empty list instead of plain-text exit.
              $this->num_results = 0;
              $this->games = array();
              $this->administration_answer = 'No Games for this Admin available!';
              $this->administration_status = STATUS_CODE_ERROR;
          }
      }

      private function getGamesByCriteria($criteria) {
          $items = FfbGamePeer::doSelect($criteria);
          $games = array();
          $i=0;
          if ($items) {
              foreach($items as $item) {
                  $games[$i]["game_id"] = $item->getGameId();
                  $games[$i]["game_title"] = $item->getGameTitle();
                  $games[$i]["game_visible"] = $item->getGameVisible();
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