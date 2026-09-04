<?php

/**
 * mailservice.php
 *
 * @author Gritschacher Tobias
 * @copyright 02/2010
 * @version 0.1
 */

class mailservice extends FFB_Auth_No
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __default() {}

    public function cancel() {
    	$this->session->destroy();

		$cancel_string = explode('-',$_GET['id']);
        $cancel_code = $cancel_string[0];
        $cancel_user = $cancel_string[1];
        $cancel_type = $_GET['t'];
        $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
        $this->htmlFile = $this->config->area_prefix.'_login.php';
        $errors = array();

        if($cancel_code && $cancel_user && $cancel_type) {
            $criteria = new Criteria();
            if($cancel_type == 'r') {
            	$criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_REMINDER, $cancel_code);
            } elseif($cancel_type == 'i') {
				$criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_MAILSERVICE_INFO, $cancel_code);
			}
            $criteria->add(WebUserPermissionsPeer::USER_ID, $cancel_user);
            $criteria->setLimit(1);
            $items = WebUserPermissionsPeer::doSelect($criteria);
            if($items) {
                $user = $items[0];
                if($cancel_type == 'r') {
                	$user->setUserPermissionsFfbMailserviceReminder(0);
                	$user->save();
                } elseif($cancel_type == 'i') {
                	$user->setUserPermissionsFfbMailserviceInfo(0);
                	$user->save();
                }
                if($cancel_type == 'r') {
                	$this->user_answer = 'Du bekommst in Zukunft keine Erinnerungsmails mehr. Erinnerungsmails k&ouml;nnen unter "Profil" wieder aktiviert werden.';
                } elseif($cancel_type == 'i') {
                	$this->user_answer = 'Du bekommst in Zukunft keine Infomails mehr. Infomails k&ouml;nnen unter "Profil" wieder aktiviert werden.';
                }
                $this->user_status = STATUS_CODE_SUCCESS;
            } else {
                $errors[] = 'Der Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.';
                $this->user_status = STATUS_CODE_ERROR;
                $this->errors = $errors;
            }
        } else {
            $errors[] = 'Der Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell hast du das Mailservice bereits deaktiviert.';
            $this->user_status = STATUS_CODE_ERROR;
            $this->errors = $errors;
        }
	}

	public function cancelFb() {
    	$this->session->destroy();

		$cancel_code = $_GET['id'];
        $this->navFile = $this->config->area_prefix.'_registration_navigation.php';
        $this->htmlFile = $this->config->area_prefix.'_login.php';
        $errors = array();

        if($cancel_code) {
            $criteria = new Criteria();
            $criteria->add(WebUserPermissionsPeer::USER_PERMISSIONS_FFB_FACEBOOK, $cancel_code);
            $criteria->setLimit(1);
            $items = WebUserPermissionsPeer::doSelect($criteria);
            if($items) {
                $user = $items[0];
               	$user->setUserPermissionsFfbFacebook(0);
               	$user->save();

                $this->user_answer = 'Du bekommst in Zukunft keine Meldungen von SoccerSportsfan mehr auf deine Facebook-Pinnwand. Du kannst dieses Service unter "Profil" wieder aktivieren.';
                $this->user_status = STATUS_CODE_SUCCESS;
            } else {
                $errors[] = 'Der Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurden die Facebook-Meldungen bereits deaktiviert.';
                $this->user_status = STATUS_CODE_ERROR;
                $this->errors = $errors;
            }
        } else {
            $errors[] = 'Der Link ist ung&uuml;tig oder wurde bereits verwendet. Eventuell wurden die Facebook-Meldungen bereits deaktiviert.';
            $this->user_status = STATUS_CODE_ERROR;
            $this->errors = $errors;
        }
	}

    public function __destruct()
    {
        parent::__destruct();
    }
}
?>