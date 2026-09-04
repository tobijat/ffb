<?php

/**
 * login.php
 *
 * @author Gritschacher Tobias
 * @copyright 12/2009
 * @version 0.2
 */

  class login extends FFB_Auth_No
  {
      public function __construct()
      {
          parent::__construct();

      }

      public function testLogin() {
      	echo LOGIN_DEFAULT_URL.'<br>';
	  	echo $_SERVER['REQUEST_URI'].'<br>';
	  	exit();
	  }

      public function __default()
      {
		  $this->session->destroy();
          $this->htmlFile = $this->config->area_prefix.'_login.php';
          $this->navFile = $this->config->area_prefix.'_login_navigation.php';
      }

      public function loginAjax()
      {
          $errors = array();
          if (!empty($_POST)) {
              $username = isset($_POST['user_nickname']) ? $_POST['user_nickname'] : '';
              $plainPassword = isset($_POST['user_password']) ? (string) $_POST['user_password'] : '';

              $criteria = new Criteria();
              $criteria->add(WebUserPeer::USER_NICKNAME, $username);
              $curr_user = WebUserPeer::doSelect($criteria);
              if(count($curr_user) > 0) {
                  if($curr_user[0]->getUserStatus() == 'active') {
                      $storedHash = $curr_user[0]->getUserPassword();
                      if(FFB_Password::verify($plainPassword, $storedHash)) {
                    	  if(isset($_POST['destination']) && $_POST['destination'] != '') {
                              $destination = 'http://'.$_SERVER['SERVER_NAME'].$_POST['destination'];
                          } else {
						  	  $destination = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->config->area_prefix;
						  }
                          $this->session->user_id = $curr_user[0]->getUserId();
                          $this->session->user_nickname = $user_name = $curr_user[0]->getUserNickname();
                          $this->session->user_email = $curr_user[0]->getUserEmail();
                      	  $this->session->user_name = $curr_user[0]->getUserFname().' '.$curr_user[0]->getUserLname();

                      	  //user details
                      	  $userDetails = WebUserDetailsPeer::retrieveByPK($this->session->user_id);
                      	  $this->session->user_avatar = $userDetails->getUserDetailsAvatar();
                      	  $this->session->user_photo  = $userDetails->getUserDetailsPhoto();
                      	  $this->session->game_id_player = $userDetails->getUserDetailsFfbSelectedGame();
                      	  $userDetails = null;

                          //*** check for administrator ***
                          $criteria = new Criteria();
                          $criteria->add(WebAdminPeer::ADMIN_USER_ID, $curr_user[0]->getUserId());
                          $criteria->add(WebAdminPeer::ADMIN_SECTION, $this->config->area_prefix);
                          $curr_admin = WebAdminPeer::doSelect($criteria);
                          if(count($curr_admin) > 0) {
                              $this->session->admin_flag = 1;
                              $area_prefix = substr($this->config->area_prefix, 0); //this is because a xml-node cannot be stored into session
							  $this->session->admin_section = $area_prefix;
							  $destination = 'http://'.$_SERVER['SERVER_NAME'].'/administration/start';
                          } else {
						  	  $this->session->admin_flag = 0;
						  }

                          // Upgrade legacy MD5 (and outdated password_hash algos) on successful login.
                          if (FFB_Password::needsRehash($storedHash)) {
                              $curr_user[0]->setUserPassword(FFB_Password::hash($plainPassword));
                          }
                          $curr_user[0]->setUserDateLlogin(date('Y-m-d H:i:s', time()));
                          $curr_user[0]->setUserDateLaction(date('Y-m-d H:i:s', time()));
                          $curr_user[0]->setUserLip($_SERVER['REMOTE_ADDR']);
                          $curr_user[0]->save();

                          $this->administration_status = STATUS_CODE_SUCCESS;
                          $this->administration_destination = $destination;
                      } else {
                          $errors[] = 'Das Passwort ist falsch.';
                      }
                  } elseif($curr_user[0]->getUserStatus() == 'inactive') {
                      $errors[] = 'Dein Account ist inaktiv.<br>Bitte wende dich an einen Administrator!';
                  } elseif($curr_user[0]->getUserStatus() == 'na') {
                      $errors[] = 'Dein Account wurde noch nicht aktiviert.<br>Klick auf den Aktivierungs-Link in der Email, die dir nach der Registrierung zugeschickt wurde!<br>Pr&uuml;f bitte auch deinen Spam-Folder!';
                  }
              } else {
                  $errors[] = 'Benutzername \''.$username.'\' existiert nicht.';
              }
          }
          if(count($errors)) {
              $this->administration_status = STATUS_CODE_ERROR;
              $this->errors = $errors;
          }
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }
?>
