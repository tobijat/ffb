<?php

  /**
 * account.php
 *
 * @author Gritschacher Tobias
 * @copyright 12/2009
 * @version 0.1
 */
define('IN_PHPBB',true);
class account extends FFB_Auth_User
{
    public function __construct()
    {
        parent::__construct();

        require_once(INCLUDE_PATH.'utf/utf_normalizer.php');
        require_once(INCLUDE_PATH.'utf/utf_tools.php');
        $phpEx='php';
        global $phpEx;

        $this->htmlFile = $this->config->area_prefix.'_account.php';
        $this->navFile = $this->config->area_prefix.'_account_navigation.php';
        //for recaptcha:
        require_once(INCLUDE_PATH.'recaptcha/recaptchalib.php');
    }

    public function __default()
    {
        $this->post = $_POST;
        if (!empty($_POST)) {
			if($this->validateRegistration()) {
	            $this->updateRegistration();
	        } else {
	            $this->user_status = STATUS_CODE_ERROR_VALIDATION;
	        }
        } else {
			$this->loadExistingData();
		}
    }

    public function accountDetails() {
    	$this->post = $_POST;
        if (!empty($_POST)) {
			if($this->validateProfile()) {
	            $this->updateProfile();
	            $this->loadExistingData();
	        } else {
	            $this->user_status = STATUS_CODE_ERROR_VALIDATION;
	        }
        } else {
			$this->loadExistingData();
		}
    	$this->team_list = $this->getTeams();
		$this->htmlFile = $this->config->area_prefix."_account_details.php";
	}

    //get existing user data from DB and send to client
    private function loadExistingData() {
    	$user_id = $this->session->user_id;
        $exist_user = WebUserPeer::retrieveByPK($user_id);
        $exist_perm = WebUserPermissionsPeer::retrieveByPK($user_id);
        $exist_details = WebUserDetailsPeer::retrieveByPK($user_id);
        if($exist_user && $exist_perm && $exist_details) {
            $user['user_nickname'] = $exist_user->getUserNickname();
            $user['user_email'] = $exist_user->getUserEmail();
            $user['user_actual_email'] = $exist_user->getUserEmail();
            $user['user_fname'] = $exist_user->getUserFname();
            $user['user_lname'] = $exist_user->getUserLname();
            $user['user_nationality'] = $exist_user->getUserNationality();
            $user['user_mailservice'] = $exist_user->getUserMailservice();
            $user['user_permissions_facebook_connected'] = $exist_perm->getUserPermissionsFacebookConnected();
            $birthdate = strtotime($exist_user->getUserDateBirth());
            if(!$birthdate) {
            	//echo 'birth: '.$birthdate;
            	$user['user_birth_year'] = 0;
            	$user['user_birth_day'] = 0;
            	$user['user_birth_month'] = 0;
            } else {
				$user['user_birth_year'] = date('Y',$birthdate);
            	$user['user_birth_day'] = date('j',$birthdate);
            	$user['user_birth_month'] = date('n',$birthdate);
			}

            $user['user_details_avatar_old'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/avatar/'.$exist_details->getUserDetailsAvatar();
            $user['user_details_photo_old'] = FFB_BASE_PATH.FFB_IMAGE_PATH.'profiles/photo/'.$exist_details->getUserDetailsPhoto();
            $user['user_details_zip'] = $exist_details->getUserDetailsZip();
            $user['user_details_city'] = $exist_details->getUserDetailsCity();
            $user['user_details_street'] = $exist_details->getUserDetailsStreet();
            $user['user_details_phone'] = $exist_details->getUserDetailsPhone();
            $user['user_details_website'] = $exist_details->getUserDetailsWebsite();
            $user['user_details_ffb_favourite_team'] = $exist_details->getUserDetailsFfbFavouriteTeam();
            $user['user_details_ffb_own_team'] = $exist_details->getUserDetailsFfbOwnTeam();
            $user['user_details_ffb_own_player'] = $exist_details->getUserDetailsFfbOwnPlayer();

			if(strcmp($exist_perm->getUserPermissionsFfbMailserviceReminder(), '0') == 0) {
            	$user['user_permissions_ffb_mailservice_reminder'] = 0;
            } else {
				$user['user_permissions_ffb_mailservice_reminder'] = 1;
			}
			if(strcmp($exist_perm->getUserPermissionsFfbMailserviceInfo(), '0') == 0) {
            	$user['user_permissions_ffb_mailservice_info'] = 0;
            } else {
				$user['user_permissions_ffb_mailservice_info'] = 1;
			}
			if($user['user_permissions_facebook_connected']) {
				if(strcmp($exist_perm->getUserPermissionsFfbFacebook(), '0') == 0) {
	            	$user['user_permissions_ffb_facebook'] = 0;
	            } else {
					$user['user_permissions_ffb_facebook'] = 1;
				}
			} else {
				$user['user_permissions_ffb_facebook'] = -1;
			}
            $user['user_permissions_ffb_visible_profile'] = $exist_perm->getUserPermissionsFfbVisibleProfile();
            //$user['user_permissions_pictory_visible_profile'] = $exist_perm->getUserPermissionsPictoryVisibleProfile();
            //$user['user_permissions_pictory_facebook'] = $exist_perm->getUserPermissionsPictoryFacebook();

            $this->post = $user;
        } else {
			$this->user_status = STATUS_CODE_ERROR_VALIDATION;
			$errors[] = 'Update fehlgeschlagen. Deine Daten k&ouml;nnen nicht aktualisiert werden!';
			$this->errors = $errors;
			return false;
		}
    }

	//validate the data
	private function validateRegistration() {
		require_once('Validate.php');
		//check for empty fields
		if (empty($_POST) || !$_POST["recaptcha_response_field"]) {
			$errors[] = 'Du musst alle Felder ausf&uuml;llen, die mit einem * markiert sind!';
			$this->errors = $errors;
			return false;
		}

		//validate changed password-string
		if($_POST["user_password_chg"] && !Validate::string($_POST['user_password_chg'], array('min_length'=>5, 'max_length'=>32))) {
			$errors[] = 'Passwort&auml;nderung: min. L&auml;nge ist 5, max. L&auml;nge ist 32!';
		}
		//validate changed email-string
		if($_POST["user_email_chg"] && !Validate::email($_POST['user_email_chg'])) {
			$errors[] = 'Deine neue E-Mail Adresse ist nicht g&uuml;ltig!';
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
		//check if changed password == changed password_val
		if($_POST["user_password_chg"] && strcmp($_POST['user_password_chg'], $_POST['user_password_val_chg'])!=0) {
			$errors[] = 'Die Passw&ouml;rter bei der Passwort&auml;nderung stimmen nicht &uuml;berein!';
		}
		//check if changed email == changed email_val
		if($_POST["user_email_chg"] && strcmp($_POST['user_email_chg'], $_POST['user_email_val_chg'])!=0) {
			$errors[] = 'Die E-Mail Adressen bei der Adress&auml;nderung stimmen nicht &uuml;berein!';
		}
		//check TOS acceptance
		if($_POST["user_tos"] != 'user_tos_yes') {
			$errors[] = 'Du musst die Bedingungen gelesen und akzeptiert haben!';
		}

		if(count($errors)) {
			$this->errors = $errors;
			return false;
		}

		//check for existing changed email
		$criteria = new Criteria();
		$criteria->add(WebUserPeer::USER_EMAIL, $_POST['user_email_chg']);
		$exist_email = WebUserPeer::doCount($criteria);
		if($exist_email) {
			$errors[] = 'Die von dir gew&auml;hlte neue E-Mail Adresse existiert bereits!';
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

	//validate the profile data
	private function validateProfile() {
		//check images
		if($_FILES['user_details_photo']['tmp_name']) {
			if(!($image_data = getimagesize($_FILES['user_details_photo']['tmp_name']))) {
				$errors[] = 'Problem beim Lesen des Profilfotos. Probier ein anderes.';
				$this->errors = $errors;
				return false;
			}
			if($image_data[0] > 1024 || $image_data[1] > 1024) {
				$errors[] = 'Das Profilfoto darf maximal 1024x1024 Pixel gro&szlig; sein.';
			}
			if($_FILES['user_details_photo']['size'] > 512000) {
				$errors[] = 'Das Profilfoto darf maximal 500 Kilobyte gro&szlig; sein.';
			}
		}

		if($_FILES['user_details_avatar']['tmp_name']) {
			if(!($image_data = getimagesize($_FILES['user_details_avatar']['tmp_name']))) {
				$errors[] = 'Problem beim Lesen des Avatarbildes. Probier ein anderes.';
				$this->errors = $errors;
				return false;
			}
			if($image_data[0] > 90 || $image_data[1] > 90) {
				$errors[] = 'Das Avatarbild darf maximal 90x90 Pixel gro&szlig; sein.';
			}
			if($_FILES['user_details_avatar']['size'] > 102400) {
				$errors[] = 'Das Avatarbild darf maximal 100 Kilobyte gro&szlig; sein.';
			}
		}

		if(count($errors)) {
			$this->errors = $errors;
			return false;
		}

		return true;
	}

/*
	public function testActivationMail() {
		$this->sendActivationMail(md5(uniqid(time())), 1);

		echo 'ok';
		exit();
	}
*/

	//update profile
	private function updateProfile() {
		$user_id = $this->session->user_id;
		$answer = '';
		$http_prefix = 'http://';

		$chg_user = WebUserPeer::retrieveByPk($user_id);
		$chg_user_details = WebUserDetailsPeer::retrieveByPk($user_id);
		$chg_user_perm = WebUserPermissionsPeer::retrieveByPk($user_id);
		$updateForumAvatar = '';//if a forum avatar is changed fill in the full URL, else empty
		if($chg_user && $chg_user_details && $chg_user_perm) {
			$website = '';
			$own_team = 0;
			$own_player = 0;
			$old_photo_file = $chg_user_details->getUserDetailsPhoto();
			$old_avatar_file = $chg_user_details->getUserDetailsAvatar();
			if($_POST['user_details_website']) {
				$pos = stripos($_POST['user_details_website'], $http_prefix);
				if($pos === false || ($pos !== false && $pos > 0)) {
					$website = $http_prefix.$_POST['user_details_website'];
				} else {
					$website = $_POST['user_details_website'];
				}
			}
			if($_POST['user_details_ffb_own_team']) {
				$own_team = $_POST['user_details_ffb_own_team'];
			}
			if($_POST['user_details_ffb_own_player']) {
				$own_player = $_POST['user_details_ffb_own_player'];
			}

			if(!$_FILES['user_details_photo']['tmp_name'] && $_POST['user_details_photo_delete'] == 1 && strcmp($old_photo_file, "profile_na.png") != 0) {
				unlink($_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/photo/'.$old_photo_file);
				$chg_user_details->setUserDetailsPhoto('profile_na.png');
				$this->session->user_photo = 'profile_na.png';
			}
			if(!$_FILES['user_details_avatar']['tmp_name'] && $_POST['user_details_avatar_delete'] == 1 && strcmp($old_avatar_file, "avatar_na.png") != 0) {
				unlink($_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/avatar/'.$old_avatar_file);
				$chg_user_details->setUserDetailsAvatar('avatar_na.png');
				$updateForumAvatar = FFB_IMAGE_PATH.'profiles/avatar/avatar_na.png';
			}

			if($_FILES['user_details_photo']['tmp_name']) {
				$photo_file_orig = $_FILES['user_details_photo']['name'];
				$photo_file_ext = substr($photo_file_orig, strripos($photo_file_orig, '.'));
				$photo_file_name = md5(uniqid(time())).$photo_file_ext;
				$photo_file_src = $_FILES['user_details_photo']['tmp_name'];
				$photo_file_dst = $_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/photo/'.$photo_file_name;
				if(move_uploaded_file($photo_file_src, $photo_file_dst)) {
					$chg_user_details->setUserDetailsPhoto($photo_file_name);
					$this->session->user_photo = $photo_file_name;
				}
				if(strcmp($old_photo_file, "profile_na.png") != 0) {
					//delete old photo
					unlink($_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/photo/'.$old_photo_file);
				}
			}

			if($_FILES['user_details_avatar']['tmp_name']) {
				$old_avatar_file = $chg_user_details->getUserDetailsAvatar();
				$avatar_file_orig = $_FILES['user_details_avatar']['name'];
				$avatar_file_ext = substr($avatar_file_orig, strripos($avatar_file_orig, '.'));
				$avatar_file_name = md5(uniqid(time())).$avatar_file_ext;
				$avatar_file_src = $_FILES['user_details_avatar']['tmp_name'];
				$avatar_file_dst = $_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/avatar/'.$avatar_file_name;
				if(move_uploaded_file($avatar_file_src, $avatar_file_dst)) {
					$chg_user_details->setUserDetailsAvatar($avatar_file_name);
					$updateForumAvatar = FFB_IMAGE_PATH.'profiles/avatar/'.$avatar_file_name;
				}
				if(strcmp($old_avatar_file, "avatar_na.png") != 0) {
					//delete old photo
					unlink($_SERVER['DOCUMENT_ROOT'].FFB_IMAGE_PATH.'profiles/avatar/'.$old_avatar_file);
				}
			}

			$answer .= '<b>Deine Daten wurden aktualisiert!</b>';
        	$this->user_status = STATUS_CODE_SUCCESS_UPDATE;

			$chg_user_details->setUserDetailsZip($_POST['user_details_zip']);
			$chg_user_details->setUserDetailsCity($_POST['user_details_city']);
			$chg_user_details->setUserDetailsStreet($_POST['user_details_street']);
			$chg_user_details->setUserDetailsPhone($_POST['user_details_phone']);
			$chg_user_details->setUserDetailsWebsite($website);
			$chg_user_details->setUserDetailsFfbFavouriteTeam($_POST['user_details_ffb_favourite_team']);
			$chg_user_details->setUserDetailsFfbOwnTeam($own_team);
			$chg_user_details->setUserDetailsFfbOwnPlayer($own_player);

			if($_POST['user_permissions_ffb_mailservice_reminder'] == 1 && strcmp($chg_user_perm->getUserPermissionsFfbMailserviceReminder(), '0') == 0) {
				$chg_user_perm->setUserPermissionsFfbMailserviceReminder(md5(uniqid(time())));
			}
			if($_POST['user_permissions_ffb_mailservice_reminder'] == 0) {
				$chg_user_perm->setUserPermissionsFfbMailserviceReminder(0);
			}
			if($_POST['user_permissions_ffb_mailservice_info'] == 1 && strcmp($chg_user_perm->getUserPermissionsFfbMailserviceInfo(), '0') == 0) {
				$chg_user_perm->setUserPermissionsFfbMailserviceInfo(md5(uniqid(time())));
			}
			if($_POST['user_permissions_ffb_mailservice_info'] == 0) {
				$chg_user_perm->setUserPermissionsFfbMailserviceInfo(0);
			}

			if($chg_user->getUserFacebookId()) {
				if($_POST['user_permissions_ffb_facebook'] == 1 && strcmp($chg_user_perm->getUserPermissionsFfbFacebook(), '0') == 0) {
					$chg_user_perm->setUserPermissionsFfbFacebook(md5(uniqid(time())));
				}
				if($_POST['user_permissions_ffb_facebook'] == 0) {
					$chg_user_perm->setUserPermissionsFfbFacebook(0);
				}
			}

			$chg_user_perm->setUserPermissionsFfbVisibleProfile($_POST['user_permissions_ffb_visible_profile']);

			$chg_user->setUserIp($_SERVER['REMOTE_ADDR']);
			$chg_user_details->setUserDetailsLastUpdate(date('Y-m-d H:i:s', time()));
			$chg_user->save();
			$chg_user_details->save();
			$chg_user_perm->save();
			$this->user_answer = $answer;


			//remote forum avatar update - do at last
			if($updateForumAvatar) {
				require_once ('modules/ffbapi/forumUpdate.php');
				$updateForum = new forumUpdate();
				$fullPath = $http_prefix.$_SERVER["SERVER_NAME"].'/'.$updateForumAvatar;
				$updateForum->updateAvatar($fullPath, $chg_user->getUserNickname());
				$updateForum = null;
			}

		} else {
			$this->user_status = STATUS_CODE_ERROR_VALIDATION;
			$errors[] = 'Update fehlgeschlagen. Deine Daten wurden nicht aktualisiert!';
			$this->errors = $errors;
			return false;
		}
	}

	//update user row
	private function updateRegistration() {
		if($_POST['user_birth_year'] && $_POST['user_birth_month'] && $_POST['user_birth_day']) {
			$user_date_birth = $birthdate = $_POST['user_birth_year'].'-'.$_POST['user_birth_month'].'-'.$_POST['user_birth_day'];
		}
		$user_status = 'na';
		$activation_code = md5(uniqid(time()));
		$user_id = $this->session->user_id;
		$answer = '';

		$chg_user = WebUserPeer::retrieveByPk($user_id);
		if($chg_user) {
			$answer .= '<b>Deine Daten wurden aktualisiert!</b>';
        	$this->user_status = STATUS_CODE_SUCCESS_UPDATE;
			$chg_user->setUserFname($_POST['user_fname']);
			$chg_user->setUserLname($_POST['user_lname']);
			$chg_user->setUserNationality($_POST['user_nationality']);
			$chg_user->setUserDateBirth($user_date_birth);

			if($_POST['user_password_chg'] && $_POST['user_password_val_chg']) {
				$chg_user->setUserPassword(md5($_POST['user_password_chg']));
				$this->doBoardPWUpdate();
			}
			if($_POST['user_email_chg'] && $_POST['user_email_val_chg']) {
				$chg_user->setUserEmail($_POST['user_email_chg']);
				$chg_user->setUserActivationCode($activation_code);
				$chg_user->setUserStatus($user_status);
				$this->sendMailchangeActivationMail($activation_code, $user_id);
				$this->session->user_id = 0;
				$answer .= '<br><b>!!</b> Du hast deine E-Mail Adresse ge&auml;ndert. Ein E-Mail wurde an die neue Adresse geschickt. Um die &Auml;nderung abzuschlie&szlig;en, musst du den Link in dieser E-Mail anklicken. Du wirst jetzt ausgeloggt und kannst dich erst wieder einloggen, wenn der Link geklickt wurde.';
				$this->doBoardEmailUpdate();
			}

			$chg_user->setUserIp($_SERVER['REMOTE_ADDR']);
			$chg_user->save();
			$this->user_answer = $answer;
		} else {
			$this->user_status = STATUS_CODE_ERROR_VALIDATION;
			$errors[] = 'Update fehlgeschlagen. Deine Daten wurden nicht aktualisiert!';
			$this->errors = $errors;
			return false;
		}
	}

	private function doBoardPWUpdate() {
        $usernameUTF8 = utf8_clean_string($_POST['user_nickname']);
        $user_password = md5(md5($_POST['user_password_chg']));

        $db_server = $this->config->board_database_server;
		$db_name = $this->config->board_database_name;
		$db_pw = $this->config->board_database_pw;
		$connection = @mysqli_connect($db_server, $db_name, $db_pw);
		$db = @mysqli_select_db($connection, $db_name);

		$check_request = "SELECT * FROM ffb_forum_users WHERE username_clean='$usernameUTF8'";
        $result = mysqli_query($connection, $check_request);
        $user_exists = mysqli_num_rows($result);
        if($user_exists) {
			$insert_request = "UPDATE ffb_forum_users Set user_password='$user_password' WHERE username_clean='$usernameUTF8'";
	        $ret = @mysqli_query($connection, $insert_request);
	        if(!$ret || !$db || !$connection) {
				$errors[] = 'Dein Passwort wurde g&auml;ndert, das Update f&uuml;r das Forum konnte aber nicht durchgef&uuml;hrt werden. Bitte wende dich den Administrator.';
				$this->errors = $errors;
			}
		}
        mysqli_close($connection);
	}

	private function doBoardEmailUpdate() {
        $usernameUTF8 = utf8_clean_string($_POST['user_nickname']);
        $user_email = strtolower($_POST['user_email_chg']);
        $user_email_hash = crc32(strtolower($_POST['user_email_chg'])) . strlen($_POST['user_email_chg']);

        $db_server = $this->config->board_database_server;
		$db_name = $this->config->board_database_name;
		$db_pw = $this->config->board_database_pw;
		$connection = @mysqli_connect($db_server, $db_name, $db_pw);
		$db = @mysqli_select_db($connection, $db_name);

		$check_request = "SELECT * FROM ffb_forum_users WHERE username_clean='$usernameUTF8'";
        $result = mysqli_query($connection, $check_request);
        $user_exists = mysqli_num_rows($result);
        if($user_exists) {
			$insert_request = "UPDATE ffb_forum_users Set user_email='$user_email',user_email_hash='$user_email_hash' WHERE username_clean='$usernameUTF8'";
	        $ret = @mysqli_query($connection, $insert_request);
	        if(!$ret || !$db || !$connection) {
				$errors[] = 'Deine E-Mail wurde g&auml;ndert, das Update f&uuml;r das Forum konnte aber nicht durchgef&uuml;hrt werden. Bitte wende dich den Administrator.';
				$this->errors = $errors;
			}
		}
        mysqli_close($connection);
	}

	public function sendMailchangeActivationMail($activation_code, $user_id) {
		$add_text = $this->config->mail_activation_add_text;
		$subject = 'E-Mail Änderung';
		$server_name = $_SERVER["SERVER_NAME"];
		$act_link = 'http://'.$server_name.'/users/registration/activateEmail.html?id='.$activation_code.'-'.$user_id;
		$message = '';
		$message .= "Hallo ".$_POST['user_nickname']."!\n\n";
		$message .= "Du hast auf http://".$server_name." deine E-Mail Adresse geändert.\n";
		$message .= "Um die Änderung abzuschließen, musst du nur noch folgenden Link anklicken oder ihn in die Adresszeile deines Browsers kopieren. ";
		$message .= "Anschließend kannst du dich wie gewohnt mit deinem Benutzernamen und Passwort anmelden.\n\n";
		$message .= $act_link."\n";

		$mail = new FFB_Mail($this->config, array($user_id), $subject, $message, 'force', 'system/email change');

		return ($mail->send());
	}

	private function getTeams() {
		$criteria = new Criteria();
		$criteria->add(FfbTeamPeer::TEAM_STATUS, 1);
		$criteria->add(FfbTeamPeer::TEAM_NATIONALITY, '', Criteria::NOT_EQUAL);
		$criteria->addAscendingOrderByColumn(FfbTeamPeer::TEAM_NAME);
		$teams = FfbTeamPeer::doSelect($criteria);
		$team_list = array();
		if($teams) {
			$i = 0;
			foreach($teams as $team) {
				$team_list[$i]['team_id'] = $team->getTeamId();
				$team_list[$i]['team_name'] = $team->getTeamName();
				$team_list[$i]['team_nationality'] = $team->getTeamNationality();
				$i++;
			}
		}
		return $team_list;
	}

    public function __destruct()
    {
        parent::__destruct();
    }
}

?>
