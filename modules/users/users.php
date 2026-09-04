<?php

  /**
 * users.php
 *
 * @author Gritschacher
 * @copyright 07/2008
 * @version 0.1
 */

  class users extends FFB_Auth_No
  {
      public function __construct()
      {
          parent::__construct();
          require_once('ffb/FfbAdmin.php');
          $this->navFile = 'login_navigation.php';
          $this->htmlFile = 'login.php';
      }

      public function __default()
      {
          $this->session->destroy();
      }

      private function copyUsers() {
          $criteria = new Criteria();
          $users = WebUserPeer::doSelect($criteria);
          if($users) {
            foreach($users as $user) {
                $new_user = new WebUser();
                //$new_user->setUserId($user->getUserId());
                $new_user->setUserNickname($user->getUserNickname());
                $new_user->setUserPassword($user->getUserPassword());
                $new_user->setUserEmail($user->getUserEmail());
                $new_user->setUserFname($user->getUserFname());
                $new_user->setUserLname($user->getUserLname());
                $new_user->setUserGender($user->getUserGender());
                $new_user->setUserStatus($user->getUserStatus());
                $new_user->setUserAdmin($user->getUserAdmin());
                $new_user->setUserNationality($user->getUserNationality());
                $new_user->setUserDateBirth($user->getUserdateBirth());
                $new_user->setUserIp($user->getUserIp());
                $new_user->setUserDateRegister($user->getUserDateRegister());
                $new_user->setUserDateLlogin($user->getUserDateLlogin());
                $new_user->setUserActivationCode($user->getUserActivationCode());
                $new_user->setUserMailservice($user->getUserMailservice());

                //echo 'new_id: '.$new_user->save().'<br>';
                //echo 'old_id: '.$user->getUserNickname().' - '.$user->getUserId().'<br>';
                /*
                $bla = WebUserPeer::retrieveByPK($user->getUserId());
                if($bla->getUserNickname() != $user->getUserNickname()) {
                    echo 'falsch: '.$user->getUserNickname().'<br>';
                }
                */

            }
          }
          echo "fertig!";
          exit();

      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }


?>