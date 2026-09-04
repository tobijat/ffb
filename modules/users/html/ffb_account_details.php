	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
	<link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>account.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>user/account.js"></script>
</head>
<body>
<script>
var RecaptchaOptions = {
   lang : 'de'
};
</script>
<!--** BASE SITE STRUCTURE **//-->
<div id="Container">
	<div class="rounddiv_nav">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="NavbarRound">
				<div id="Navigation">
			        <?include(FFB_VIEWER_PATH.'navigation.php')?>
			    </div>
			    <div style="clear:both;"></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

<div class="rounddiv_main">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="Main">
<!--** **//-->

<?include(INCLUDE_PATH.'country_list.php');?>

<div class="rounddiv_regleftmain">
	<div class="roundcorner_light">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="reg_left_main">
			<div id="reg_title">Profildetails bearbeiten</div>
			<?if(is_array($this->errors)) {?>
			    <div id="reg_formerror">
			        <b>Es sind Fehler aufgetreten:</b><br>
			        <?foreach($this->errors as $error) {
			            echo '* '.$error.'<br>';
			        }?>
		        </div>
			<?}?>
			<?if($this->user_answer) {?>
			    <div id="reg_formanswer">
			        <?echo $this->user_answer;?>
			    </div>
			<?}?>

			<div id="reg_form">
				<form name="registration" action="./accountDetails.html" method="post" enctype="multipart/form-data">
					<div id="reg_formline">
				        <div id="reg_formdescr">Wohnort:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_details_city" size="50" value="<?echo $this->post['user_details_city'];?>" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Postleitzahl:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_details_zip" size="50" value="<?echo $this->post['user_details_zip'];?>" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Stra&szlig;e und Hausnummer:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_details_street" size="50" value="<?echo $this->post['user_details_street'];?>" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Telefonnummer:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_details_phone" size="50" value="<?echo $this->post['user_details_phone'];?>" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Homepage:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_details_website" size="50" value="<?echo $this->post['user_details_website'];?>" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Lieblingsteam:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_details_ffb_favourite_team" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <!--<option class="ffb_select_1" value="">Team...</option>//-->
				                <?
								$i=0;
								foreach($this->team_list as $team) {?>
				                    <option class="ffb_select_<?echo $i%2;?>" <?if($this->post['user_details_ffb_favourite_team'] == $team['team_id']) echo 'selected';?> value="<?echo $team['team_id']?>">
				                        <?echo $team['team_name'];
										$i++;?>
				                    </option>
				                <?}?>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
					<hr>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Profilfoto:</div>
				        <div id="reg_forminput">
				            <input type="file" class="input" name="user_details_photo" size="30" accept="image/*" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				            <br><img src="<?echo $this->post['user_details_photo_old'];?>" width="55px">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Profilfoto zur&uuml;cksetzen:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_details_photo_delete" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option selected class="ffb_select_1" value="0">Nein</option>
				                <option class="ffb_select_0" value="1">Ja</option>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Avatarbild:</div>
				        <div id="reg_forminput">
				            <input type="file" class="input" name="user_details_avatar" size="30" accept="image/*" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
							<br><img src="<?echo $this->post['user_details_avatar_old'];?>" width="55px">
						</div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Avatarbild zur&uuml;cksetzen:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_details_avatar_delete" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option selected class="ffb_select_1" value="0">Nein</option>
				                <option class="ffb_select_0" value="1">Ja</option>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <input type="hidden" name="user_details_photo_old" value="<?echo $this->post['user_details_photo_old'];?>">
				    <input type="hidden" name="user_details_avatar_old" value="<?echo $this->post['user_details_avatar_old'];?>">


					<hr>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Erinnerungen per Mail erhalten:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_permissions_ffb_mailservice_reminder" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option <?if($this->post['user_permissions_ffb_mailservice_reminder'] == 1) echo 'selected';?> class="ffb_select_1" value="1">Ja</option>
								<option <?if($this->post['user_permissions_ffb_mailservice_reminder'] == 0) echo 'selected';?> class="ffb_select_0" value="0">Nein</option>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Infos per Mail erhalten:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_permissions_ffb_mailservice_info" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option <?if($this->post['user_permissions_ffb_mailservice_info'] == 1) echo 'selected';?> class="ffb_select_1" value="1">Ja</option>
								<option <?if($this->post['user_permissions_ffb_mailservice_info'] == 0) echo 'selected';?> class="ffb_select_0" value="0">Nein</option>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Gesamtes Profil anzeigen:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_permissions_ffb_visible_profile" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option <?if($this->post['user_permissions_ffb_visible_profile'] == 1) echo 'selected';?> class="ffb_select_1" value="1">Ja</option>
								<option <?if($this->post['user_permissions_ffb_visible_profile'] == 0) echo 'selected';?> class="ffb_select_0" value="0">Nein</option>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
					<?if($this->post['user_permissions_ffb_facebook'] > -1) {?>
					    <div id="reg_formline">
					        <div id="reg_formdescr">Facebook-Zugriff erlauben:</div>
					        <div id="reg_forminput">
					            <select class="ffb_select" name="user_permissions_ffb_facebook" onFocus="javascript:dispRegHelp(this.name);" onMouseOver="javascript:dispRegHelp(this.name);">
					                <option <?if($this->post['user_permissions_ffb_facebook'] == 1) echo 'selected';?> class="ffb_select_1" value="1">Ja</option>
									<option <?if($this->post['user_permissions_ffb_facebook'] == 0) echo 'selected';?> class="ffb_select_0" value="0">Nein</option>
					            </select>
					        </div>
					        <div id="reg_formclear"></div>
					    </div>
				    <?}?>

				    <div id="reg_formline">
				        <div id="reg_formdescr">&ensp;</div>
				        <div id="reg_forminput">
			                <input type="submit" class="submit" value="&Auml;nderungen abschicken" name="users_profile_update">
				        </div>
				    </div>
				    <div id="reg_formclear"></div>
				</form>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>

<div class="rounddiv_regrightmain">
	<div class="roundcorner_light">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="reg_right_main" style="min-height:576px;">
			<div id="reg_helptext_title"><b><u>Hinweise</u></b></div>
			<div id="reg_helptext">Alle Felder sind optional. Klick auf ein Feld um weitere Hinweise anzuzeigen.</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>

<div style="clear:both"></div>

<!--** BASE SITE STRUCTURE **//-->
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>

<div class="rounddiv_footer">
	<div class="roundcorner_dark">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="Footer">
		    <?include(FFB_VIEWER_PATH.'footer.php')?>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>

</div>
<!--** **//-->

</body>
