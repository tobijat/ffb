<?php

  /**
 * registration.php
 *
 * @author Gritschacher Tobias
 * @copyright 10/2009
 * @version 0.4
 */
class registration extends FFB_Auth_No
{
    public function __construct()
    {
        parent::__construct();

        //echo "sub: ".$_REQUEST['subdomain']."<br>";
        //echo "sub: ".$_SERVER["argv"][0]."<br>";
        $this->htmlFile = $this->config->area_prefix.'_registration.php';
        $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
        //echo $this->navFile;
        //for recaptcha:
        require_once(INCLUDE_PATH.'recaptcha/recaptchalib.php');
    }

    public function __default()
    {
        $this->post = $_POST;
        if (!empty($_POST)) {
            if($this->validate()) {
                $this->insert();
            } else {
                $this->user_status = STATUS_CODE_ERROR_VALIDATION;
            }
        }
    }

	//validate the data
	private function validate() {
		require_once('Validate.php');
		//check for empty fields
		if (empty($_POST) || !$_POST['user_nickname'] || !$_POST['user_password'] || !$_POST['user_password_val'] ||
		!$_POST['user_email'] || !$_POST["user_email_val"] || !$_POST["recaptcha_response_field"]) {
			$errors[] = 'Du musst alle Felder ausf&uuml;llen, die mit einem * markiert sind!';
			$this->errors = $errors;
			return false;
		}


		if(strlen( $_POST['user_nickname']  )  !=  strlen(trim( $_POST['user_nickname'] ))  ) {
			$errors[] = 'Benutzernamen bitte ohne Leerzeichen \' \' am Beginn und Ende!';
		}

		//validate password-string
		if(!Validate::string($_POST['user_password'], array('min_length'=>5, 'max_length'=>32))) {
			$errors[] = 'Passwort: min. L&auml;nge ist 5, max. L&auml;nge ist 32!';
		}
		//validate username-string
		if(!Validate::string($_POST['user_nickname'], array('min_length'=>3, 'max_length'=>16))) {
			$errors[] = 'Benutzername: min. L&auml;nge ist 3, max. L&auml;nge ist 16!';
		}
		//validate email-string
		if(!Validate::email($_POST['user_email'])) {
			$errors[] = 'Deine Email-Adresse ist nicht g&uuml;ltig!';
		}
		//Validate birthdate
		if($_POST['user_birth_day'] && $_POST['user_birth_month'] && $_POST['user_birth_year']) {
			$usertime = $_POST['user_birth_day'].'.'.$_POST['user_birth_month'].'.'.$_POST['user_birth_year'];
			$servertime =  date('j.n.Y', mktime(0,0,0,$_POST['user_birth_month'],$_POST['user_birth_day'],$_POST['user_birth_year']));
			if($usertime != $servertime)
			{
				$errors[] = 'Das Geburtsdatum ist nicht g&uuml;ltig!';
			}
		}
		//check if password == password_val
		if(strcmp($_POST['user_password'], $_POST['user_password_val'])!=0) {
			$errors[] = 'Die Passw&ouml;rter stimmen nicht &uuml;berein!';
		}
		//check if email == email_val
		if(strcmp($_POST['user_email'], $_POST['user_email_val'])!=0) {
			$errors[] = 'Die Email-Adressen stimmen nicht &uuml;berein!';
		}
		//check TOS acceptance
		if($_POST["user_tos"] != 'user_tos_yes') {
			$errors[] = 'Du musst die Bedingungen gelesen und akzeptiert haben!';
		}

		if(count($errors)) {
			$this->errors = $errors;
			return false;
		}

		//check for existing username
		$criteria = new Criteria();
		$criteria->add(WebUserPeer::USER_NICKNAME, $_POST['user_nickname']);
		$exist_user = WebUserPeer::doCount($criteria);
		if($exist_user) {
			$errors[] = 'Dieser Benutzername existiert bereits!';
		}
		//check for existing email
		$criteria = new Criteria();
		$criteria->add(WebUserPeer::USER_EMAIL, $_POST['user_email']);
		$exist_email = WebUserPeer::doCount($criteria);
		if($exist_email) {
			$errors[] = 'Diese Email-Adresse existiert bereits!';
		}

		//check recaptcha code
		if (!recaptcha_check_answer(FFB_RECAPTCHA_PRIVATEKEY, $_SERVER['REMOTE_ADDR'], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"])->is_valid) {
			$errors[] = 'Der Captcha-Code ist nicht g&uuml;ltig!';
		}

		if(count($errors)) {
			$this->errors = $errors;
			return false;
		}

		return true;
	}

	public function testMail() {
		$subject = 'Mail Test';
		$message = '';
		$message .= "This is a test\n\n";
		$mail = new FFB_Mail($this->config, array(3), $subject, $message, 'info', 'mailtest');

		$mail->send();

		echo 'ok';
		exit();
	}

	//insert new user row
	private function insert() {
		if($_POST['user_birth_year'] && $_POST['user_birth_month'] && $_POST['user_birth_day']) {
			$user_date_birth = $birthdate = $_POST['user_birth_year'].'-'.$_POST['user_birth_month'].'-'.$_POST['user_birth_day'];
		}
		$user_status = 'na';
		$user_mailservice_reminder = md5(uniqid(time()));
		$user_mailservice_info = md5(uniqid(time()));
		$activation_code = md5(uniqid(time()));

		$new_user = new WebUser();
		$new_user->setUserNickname($_POST['user_nickname']);
		$new_user->setUserPassword(md5($_POST['user_password']));
		$new_user->setUserEmail($_POST['user_email']);
		$new_user->setUserFname($_POST['user_fname']);
		$new_user->setUserLname($_POST['user_lname']);
		$new_user->setUserStatus($user_status);
		$new_user->setUserNationality($_POST['user_nationality']);
		$new_user->setUserDateBirth($user_date_birth);
		$new_user->setUserIp($_SERVER['REMOTE_ADDR']);
		$new_user->setUserDateRegister(date('Y-m-d H:i:s', time()));
		$new_user->setUserActivationCode($activation_code);
		$new_user->save();
		$new_user_id = $new_user->getUserId();

		$new_user_details = new WebUserDetails();
		$new_user_permissions = new WebUserPermissions();
		$new_user_details->setUserId($new_user_id);
		$new_user_details->setUserDetailsFfbSelectedGame($this->config->area_userregistration_default_selected_game_id);
		$new_user_details->setUserDetailsLastUpdate(date('Y-m-d H:i:s', time()));
		$new_user_details->setUserDetailsFfbFavouriteTeam(1);
		$new_user_permissions->setUserId($new_user_id);
		$new_user_permissions->setUserPermissionsFfbMailserviceReminder($user_mailservice_reminder);
		$new_user_permissions->setUserPermissionsFfbMailserviceInfo($user_mailservice_info);
		$new_user_details->save();
		$new_user_permissions->save();

		$this->sendActivationMail($activation_code, $new_user_id);

		$this->user_answer = '<b>Dein Account wurde angelegt!</b><br>'.
							 'Ein Aktivierungs-Mail wurde an deine E-Mail Adresse geschickt.<br>'.
							 'Bitte klick den Aktivierungs-Link in dieser E-Mail an, um deinen Account zu aktivieren!<br>'.
							 'Danach kannst du dich auf der Startseite mit deinem Benutzernamen und Passwort anmelden.<br>'.
							 'Nach dem Anmelden kannst du weitere Informationen &uuml;ber dich eintragen indem du auf "Account" klickst.<br>'.
							 'Bitte pr&uuml;f auch den Spam-Ordner deiner Mailbox, es kann vorkommen, dass die Mail dort gelandet ist!';
		$this->user_status = STATUS_CODE_SUCCESS_INSERT;
	}

	public function sendActivationMail($activation_code, $user_id) {
		$add_text = $this->config->mail_activation_add_text;
		$subject = 'Account-Aktivierung';
		$server_name = $_SERVER["SERVER_NAME"];
		$act_link = 'http://'.$server_name.'/users/registration/activate.html?id='.$activation_code.'-'.$user_id;
		$message = '';
		$message .= "Hallo ".$_POST['user_nickname']."!\n\n";
		$message .= "Du hast dich auf http://".$server_name." registriert.\n";
		$message .= "Um deine Registrierung abzuschließen musst du nur noch folgenden Link anklicken oder ihn in die Adresszeile deines Browsers kopieren. ";
		$message .= "Anschließend kannst du dich mit deinem Benutzernamen und Passwort anmelden.\n\n";
		$message .= $act_link."\n\n";
		$message .= "Du kannst deinem Profil weitere Informationen hinzufügen wenn du nach dem Anmelden auf \"Profil\" klickst.\n";

		$mail = new FFB_Mail($this->config, array($user_id), $subject, $message, 'force', 'system/account activation');

		return ($mail->send());
	}

    //activate account (by link from mail)
    public function activate() {
        $activation_string = explode('-',$_GET['id']);
        $activation_code = $activation_string[0];
        $activation_user = $activation_string[1];
        $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
        $this->htmlFile = $this->config->area_prefix.'_login.php';
        //$this->htmlFile = 'login.php';
        $errors = array();

        if($activation_code && $activation_user) {
            $criteria = new Criteria();
            $criteria->add(WebUserPeer::USER_ACTIVATION_CODE, $activation_code);
            $criteria->add(WebUserPeer::USER_ID, $activation_user);
            $criteria->setLimit(1);
            $items = WebUserPeer::doSelect($criteria);
            if($items) {
                $user = $items[0];
                $user->setUserStatus('active');
                $user->setUserActivationCode('done');
                $user->save();
                $this->user_answer = 'Die Registrierung wurde abgeschlossen und dein Account wurde aktiviert. Du kannst dich jetzt mit deinem Benutzernamen und Passwort einloggen!';
                $this->user_status = STATUS_CODE_SUCCESS;
            } else {
                $errors[] = 'Der Aktivierungs-Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.';
                $this->user_status = STATUS_CODE_ERROR;
                $this->errors = $errors;
            }
        } else {
            $errors[] = 'Der Aktivierungs-Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.';
            $this->user_status = STATUS_CODE_ERROR;
            $this->errors = $errors;
        }
    }

    //activate new email address (by link from mail)
    public function activateEmail() {
        $activation_string = explode('-',$_GET['id']);
        $activation_code = $activation_string[0];
        $activation_user = $activation_string[1];
        $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
        $this->htmlFile = $this->config->area_prefix.'_login.php';
        //$this->htmlFile = 'login.php';
        $errors = array();

        if($activation_code && $activation_user) {
            $criteria = new Criteria();
            $criteria->add(WebUserPeer::USER_ACTIVATION_CODE, $activation_code);
            $criteria->add(WebUserPeer::USER_ID, $activation_user);
            $criteria->setLimit(1);
            $items = WebUserPeer::doSelect($criteria);
            if($items) {
                $user = $items[0];
                $user->setUserStatus('active');
                $user->setUserActivationCode('done');
                $user->save();
                $this->user_answer = 'Die E-Mail &Auml;nderung wurde abgeschlossen und dein Account wurde aktiviert. Du kannst dich jetzt mit deinem Benutzernamen und Passwort einloggen!';
                $this->user_status = STATUS_CODE_SUCCESS;
            } else {
                $errors[] = 'Der Aktivierungs-Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.';
                $this->user_status = STATUS_CODE_ERROR;
                $this->errors = $errors;
            }
        } else {
            $errors[] = 'Der Aktivierungs-Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurde dieser Account bereits aktiviert.';
            $this->user_status = STATUS_CODE_ERROR;
            $this->errors = $errors;
        }
    }

    public function getPassword() {
        $errors = array();
        if($_POST['users_registration_getpassword']) {
            if($_POST['user_nickname'] && $_POST['user_email']) {
                $criteria = new Criteria();
                $criteria->add(WebUserPeer::USER_NICKNAME, $_POST['user_nickname']);
                $criteria->add(WebUserPeer::USER_EMAIL, $_POST['user_email']);
                $criteria->setLimit(1);
                $user = WebUserPeer::doSelect($criteria);
                if($user) {
                    $newPassword = $this->generatePassword();

					$subject = 'Hier kommt dein neues Passwort';
					$server_name = $_SERVER["SERVER_NAME"];
					$message = '';
					$message .= "Hallo ".$_POST['user_nickname']."!\n\n";
					$message .= "Du hast für die Seite http://".$server_name." ein neues Passwort angefordert.\n\n";
					$message .= "Dein neues Passwort lautet: ".$newPassword."\n\n";
                    $message .= "Du kannst dich jetzt mit deinem Benutzernamen und dem neuen Passwort einloggen!\n\n";
					$message .= "Nach dem Anmelden kannst du unter \"Account\" das Passwort wieder ändern.\n";

					$mail = new FFB_Mail($this->config, array($user[0]->getUserId()), $subject, $message, 'force', 'system/forgotten password');
					$mail->send();

                    $user[0]->setUserPassword(md5($newPassword));
                    $user[0]->save();
                    $this->user_answer = 'Ein neues Passwort wurde an deine Email-Adresse gesendet!';
                    $this->user_status = STATUS_CODE_SUCCESS;
                } else {
                    $errors[] = 'Benutzer oder Email existieren nicht oder geh&ouml;ren nicht zusammen!';
                }
            } else {
                $errors[] = 'Du musst alle Felder ausf&uuml;llen, die mit einem * markiert sind!';
            }
        } else {
        	$errors[] = 'Ung&uuml;ltige Anfrage!';
        }
        if(count($errors))
        {
        	$this->user_status = STATUS_CODE_ERROR;
        	$this->errors = $errors;
        }
    }

    private function generatePassword($length = 8)
    {
        $password = "";
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";
        $i = 0;
        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }


    public function __destruct()
    {
        parent::__destruct();
    }
}

?>