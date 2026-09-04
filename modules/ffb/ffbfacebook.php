<?php

/**
 * FFB-Module - Facebook-Klasse;
 *
 * @author Gerald Musser
 * @copyright 09/2009
 * @version 0.1
 *
 */

class ffbfacebook extends FFB_Auth_No {
	private $appapikey 			= 	FFB_FACEBOOK_API_KEY;
	private $appsecret 			=	FFB_FACEBOOK_APP_SECRET;
	private $appid				= 	FFB_FACEBOOK_APP_ID;
	private $facebookConnect	= 	null;
	private $facebookUID		=	null;

    public function __construct() {
        parent::__construct();
        
        require_once 'modules/ffbapi/facebook-platform/php/facebook.php';
        $this->facebookConnect 	= 	new Facebook($this->appapikey, $this->appsecret);
        $this->facebookUID		=	$this->facebookConnect->require_login();

        //$file = fopen("resource/fb.txt", 'a+');
        //fwrite($file, "\r\nStartup new " . " // " .  $_SERVER['SCRIPT_FILENAME']. " // " . $_SERVER['REQUEST_URI'] . " // " . date(" H:i:s ") ."\r\n" );
        //fclose($file);
        if(strncmp($_REQUEST['action'], "disconnect", strlen("disconnect"))==0) {
        	$this->ffbDisconnectfacebook();
        	return;
        }
        
        if($_POST['action']=="Vernetzen!") {
        	$this->ffbConnectFacebook();
        }
    }

    public function __default() {
    	//$this->ffbFacebook();
    	$this->simpleStartup();    	
    }
    
    private function simpleStartup() {
		$this->htmlFile = 'ffbfacebook.php';
        //print_r($this->facebookConnect);
		if($this->facebookUID) {
        	$criteria				=	new Criteria();
        	$criteria->add(WebUserPeer::USER_FACEBOOK_ID, $this->facebookUID);
        	$hasLogin				=	WebUserPeer::doSelect($criteria);
        	$isAppUser 				=	$this->facebookConnect->api_client->users_isAppUser();//ask facebook direct is more up to date
        	
        	
        	if($hasLogin[0] && $isAppUser) { //Benutzer hat fb_uid und Zugriff erlaubt
        		$hasPermissionsGranted	=	WebUserPermissionsPeer::retrieveByPK($hasLogin[0]->getUserId());
        		if($hasPermissionsGranted->getUserPermissionsFacebookConnected()) {
        			$this->userRegistered	=	1;
        			
					$this->disablePermissionKey =	$hasPermissionsGranted->getUserPermissionsFfbFacebook();
					$this->disconnectedFromFb	=	$hasPermissionsGranted->getUserPermissionsFacebookConnected(); 
				//$this->session->user_id		=	$hasLogin[0]->getUserId();
        		//$this->session->admin_flag	=	$hasLogin[0]->getUserAdmin();
        		//$this->session->user_nickname = $hasLogin[0]->getUserNickname();
          		//$this->session->user_password = $hasLogin[0]->getUserPassword();
        		//header("Location: http://ffb.tobijat.at");
        		}
  			}
       	}
		$this->facebook_user = $this->facebookConnect->api_client->users_getInfo($this->facebookUID, 'last_name, first_name, name, locale, affiliations, pic, pic_small, pic_big, profile_url');    }
    
    
    
    public function ffbConnectFacebook() {
    	//$this->simpleStartup();
    	$criteria	=	new Criteria();
		$criteria->add(WebUserPeer::USER_PASSWORD, md5(trim($_REQUEST['user_password'])));
		$criteria->add(WebUserPeer::USER_NICKNAME, trim($_REQUEST['user_nickname']));
		$criteria->setLimit(1);
		$ffbUser	=	WebUserPeer::doSelect($criteria);
		if(!$ffbUser[0]) {
			$this->userRegistered	=	0;
			return;
		}
		$ffbUser[0]->setUserFacebookId(trim($_REQUEST['facebook_user_id']));
		$ffbUser[0]->save();
		$ffbUserPermissions	=	WebUserPermissionsPeer::retrieveByPK($ffbUser[0]->getUserId());
		$ffbUserPermissions->setUserPermissionsFfbFacebook(md5(time()));
		$ffbUserPermissions->setUserPermissionsFacebookConnected(1);
		$ffbUserPermissions->save();
		$this->userRegistered	=	1;
		$this->facebook_user = $this->facebookConnect->api_client->users_getInfo($this->facebookUID, 'last_name, first_name, name, locale, affiliations, pic, pic_small, pic_big, profile_url');
		//$this->simpleStartup();
		
    }
    
    public function ffbDisconnectFacebook() {    	
 		//$station = 1;
 		//$file = fopen("resource/fb.txt", 'a+');
 		//fwrite($file, $station++ . date(" H:i:s ")) . $this->facebookUID ."\r\n" ;
		$user = $this->facebookConnect->get_loggedin_user();
		if($user!=NULL && $this->facebookConnect->fb_params['uninstall']==1) {
		//	fwrite($file, $station++ . date(" H:i:s\r\n"));
			$criteria			=	new Criteria();
			$criteria->add(WebUserPeer::USER_FACEBOOK_ID, $this->facebookUID);
			$hasFacebookUser	=	WebUserPeer::doSelect($criteria);
			foreach($hasFacebookUser as $aUser) {
				if($aUser->getUserId()) {
			//		fwrite($file, $station++ . date(" H:i:s\r\n"));
					//$aUser->setUserFacebookId(null);
					//$aUser->save();
					$aUserPermission	=	WebUserPermissionsPeer::retrieveByPK($aUser->getUserId());
					$aUserPermission->setUserPermissionsFacebookConnected(0);
					$aUserPermission->setUserPermissionsFfbFacebook("0");
					$aUserPermission->save(); 		
				} 
			}
		
		}
		//fclose($file);
		exit();
    }
    

    
    //private function ffbFacebook() {
    	
// Copyright 2007 Facebook Corp.  All Rights Reserved. 
// 
// Application: Fantasy Football tobijat.at
// File: 'index.php' 
//   This is a sample skeleton for your application. 
// 
		//$facebook = new Facebook($this->appapikey, $this->appsecret);
		//$user_id = $this->facebookConnect->require_login();

		// Greet the currently logged-in user!
		//$this->facebook_user = $this->facebookConnect->api_client->users_getInfo($this->facebookUID, 'last_name, first_name, name, locale, affiliations, pic, pic_small, pic_big, profile_url');

			// Print out at most 25 of the logged-in user's friends,
			// using the friends.get API method
		//echo "<p>Friends:";
		//$facebook_friends_id = $this->facebookConnect->api_client->friends_get();
		//$facebook_friends_id = array_slice($facebook_friends_id,0);
		//$facebook_friends =array();
		//foreach ($facebook_friends_id AS $uid) {
		//	$facebook_friends[] = $this->facebookConnect->api_client->users_getInfo($uid, 'last_name, first_name, name, locale, affiliations, pic, pic_small ,pic_big, profile_url');
		//}
		//$this->facebook_friends = $facebook_friends;
		//foreach ($friends as $friend) {
  		//echo "<br>$friend";
		//}
		//echo "</p>";
    //}
    
    public function __destruct()
    {
    	parent::__destruct();
    }
    
}

?>