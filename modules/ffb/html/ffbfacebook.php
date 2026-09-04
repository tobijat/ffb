<?php

/**
 *
 * @author Gritschacher, Musser
 * @copyright 2009
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"	/>
	<meta name="author" content="Gerald Musser"	/>
	<link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>login.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>facebook.css" type="text/css" />
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>ffbfacebook.js"></script>
	<script>
	var ffbFacebookSubmitButton_	=	'<blink><input class="netbutton1" type="submit" name="action" value="Vernetzen!" /></blink>';
	function doFfbFacebookConnect() {
	var url 	=	server + 'ffb/ffbfacebook/ffbConnectFacebook.xml';
	//alert(Form.serialize($('ffbfacebookconn')));
		new Ajax.Request(url, {
		method: 'get',
 		onSuccess : function(response) {
		alert(response.responseText);
 		var xmlResponse	=	response.responseXML;
		var toDisplay 	=	'<h3>Du hast dich erfolgreich vernetzt, du bekommst jetzt direkt in deinen Facebook Account Auszeichnungen angezeigt sobald du neue Ziele erreicht hast!</h3>';
		var success		=	xmlResponse.getElementsByTagName('userRegistered')[0].firstChild.nodeValue;
		if(success==1) {
			dropLineW3('step1', " ");
			dropLineW3('buttonVernetzen', toDisplay);
		} else {
			alert('Leider ist ein Fehler aufgetreten, es konte keine Vernetzung vorgenommen werden, eventuell hast du ein falsches Passwor / Benutzernamen angegeben.');
		}
		
		},

		 onFailure : function(response) {
    	alert("Oops, there's been an error.");
 		},
 		parameters :	Form.serialize($('ffbfacebookconn'))
	});
	
}
	</script>

</head>

<body style="text-align:center;">


	 		
<div id="Container">

    <div id="Navbar">
        <img style="float:left; margin-top:10px;"  src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH ."symbols/Facebook-64_2.png";  ?>" width="64" alt="Facebook" title="Facebook"/>

		<div id="Navigation">
        </div>
        <div id="Countdown">
            <script>
                loadMe();
            </script>
            <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Mainleft" style="width:100%;">
            <div id="admintitle">Hallo <?
	if($this->facebook_user[0]['pic_small']) {
		echo " <img src=\"" . $this->facebook_user[0]['pic_small'] . "\"> ";
	} elseif ( $this->facebook_user[0]['pic'] ) {
		echo " <img src=\"" . $this->facebook_user[0]['pic'] . "\"> ";
	} elseif ( $friend_info[0]['pic_big'] ) {
		echo " <img src=\"" . $this->facebook_user[0]['pic_big'] . "\"> ";
	}
 	echo $this->facebook_user[0]['first_name'] . " " . $this->facebook_user[0]['last_name'];
 	if(!$this->userRegistered)	{
	?>
	 		</div>
	 		<div id="facebookwelcome">
	 		
	 			<div id="step1">
 					<img src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH ."backgrounds/sa10.png";?>" width="100px" height="100px" align="left" vspace="10" hspace="20" alt="South Africa 2010" />
 					Get in touch with Facebook!<br />
					Verbinde dein Facebook Profil mit deinem Fantasy Football Account und zeige Deinen Freunden deine Erfolge!<br />
 					Vergiss nicht Deine Freunde auch zum Spiel einzuladen!
 				</div>

	 			<div id="step2">
	 				<u><b>Um deinen Account zu vernetzen gib bitte hier deine Fantasy Football Daten ein:</b></u><br /><br /><br />
	 				<p>
	 		

	 		
			 		<form name="login" id="ffbfacebookconn" method="POST" enctype="multipart/form-data">
					<ol>
						
						<li>
                		<div id="formline">
                    		<div id="formdescr">
                        		<input type="hidden" name="destination" value="<? FFB_BASE_PATH ?>ffb/" />
                        		<input type="hidden" name="PHPSSID" value="<? echo session_id();?>" />
                        		<input type="hidden" name="facebook_user_id" value="<?echo $this->facebook_user[0]['uid'];?>" /><script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/de_DE"></script><script type="text/javascript">FB.init("<?echo FFB_FACEBOOK_API_KEY;?>");</script><div id="ffbfbsubmit"><input class="netbutton0"  type="image" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH."symbols/Connect_white_large_long.gif";?>" name="action" alt="Ein paar Gehnehmigungen, bitte!" onclick="FB.Connect.showPermissionDialog('publish_stream,read_stream,offline_access', function(a<?echo FFB_FACEBOOK_APP_ID;?>_perms) { dropLineW3('ffbfbsubmit', 'erledigt');dropLineW3('buttonVernetzen', ffbFacebookSubmitButton_);  return; });return false;"/></div>
                        		

                    		</div>
                    		<div id="formclear"></div>
                		</div>
                		</li>
                		
                		<br />
                		<li>
		                <div id="formline">
        		            <div id="formdescr">* <u>Fantasy-Football</u> Benutzername:</div>
                		    <div id="forminput">
                        		<input type="text" name="user_nickname" value="" />
                    		</div>
                    		<div id="formclear"></div>
                		</div>
                		<div id="formline">
                    		<div id="formdescr">* <u>Fantasy-Football</u> Passwort:</div>
                    		<div id="forminput">
                        		<input type="password" name="user_password" value="" />
                    		</div>
                    		<div id="formclear"></div>
                		</div>
                		</li>
                		
                		<div id="buttonVernetzen"></div>
                		
             		</form>
            		</p>
	 			</div>
			</div>
	 		<?} else {
	 		?>
			 <h3>Du hast dich erfolgreich vernetzt, du bekommst jetzt direkt in deinen Facebook Account Auszeichnungen angezeigt sobald du neue Ziele erreicht hast!</h3>
			 <a href="<? echo FFB_BASE_PATH; ?>" target="_soccer">Weiter gehts auf <? echo FFB_BASE_PATH; ?></a><br />oder direkt auf deiner Facebook Seite.	
			 <br />
			 <hr />
			 <? 
			 	if( strcmp($this->disablePermissionKey,"0") != 0) { ?>
			 		Wenn du keine Nachrichten mehr auf Facebook erhalten willst, nutze diesen <a href="<? echo BASE_PATH."users/mailservice/cancelFb.html?id=".$this->disablePermissionKey ?>" target="_self ">Link zum Deaktivieren</a>.
<?			 	} else { ?> 
					In deinem Profil wurden die Facebook Benachrichtigungen deaktiviert!<br />
					Oder du hast dich zum ersten mal angemeldet, dann wurden die Benachritigungen in diesem Moment eingerichtet.
					
<? 				}
			}
/*
foreach ($this->facebook_friends AS $friend_info) {
	echo "<div>";
	if($friend_info[0]['pic_small']) {
		echo " <img src=\"" . $friend_info[0]['pic_small'] . "\"> ";
	} elseif ( $friend_info[0]['pic'] ) {
		echo " <img src=\"" . $friend_info[0]['pic'] . "\"> ";
	} elseif ( $friend_info[0]['pic_big'] ) {
		echo " <img src=\"" . $friend_info[0]['pic_big'] . "\"> ";
	}
	
	echo $friend_info[0]['first_name'] . " " . $friend_info[0]['last_name'] .   
		 ", <a href=\"" . $friend_info[0]['profile_url'] ."\" target=\"_blank\">Facebook Profil</a></div>";

}
 */
?>
</div>

<script type="text/javascript" src="http://static.ak.connect.facebook.com/connect.php/de_DE"></script><script type="text/javascript">FB.init("<?echo FFB_FACEBOOK_API_KEY;?>");</script><fb:fan profile_id="<?echo FFB_FACEBOOK_APP_ID;?>" stream="1" connections="10" logobar="1" width="300"></fb:fan><div style="font-size:8px; padding-left:10px"><a href="http://soccer.sportsfan.at/ffb/ffbfacebook?">Fantasy Football soccer.sportsfan.at</a> on Facebook</div>

    </div>
 
<center>
<img src="http://feeds2.feedburner.com/ffbat.1.gif" />
</center>
    <div id="Footer">
        <?php include(FFB_VIEWER_PATH . 'footer.php');?>
    </div>

</div>
<div id="debug">

</div>