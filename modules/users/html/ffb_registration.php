	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
	<link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>registration.css" type="text/css">
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>user/registration.js"></script>
</head>
<body>
<?php include(INCLUDE_PATH.'country_list.php');?>
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
			        <?php include(FFB_VIEWER_PATH.'navigation.php')?>
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
<div class="rounddiv_regleftmain">
	<div class="roundcorner_light">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="reg_left_main">
			<div id="reg_title">Account anlegen</div>
			<?php if(is_array($this->errors)) {?>
			    <div id="reg_formerror">
			        <b>Es sind Fehler aufgetreten:</b><br>
			        <?php foreach($this->errors as $error) {
			            echo '* '.$error.'<br>';
			        }?>
		        </div>
			<?php }?>
			<?php if($this->user_answer) {?>
			    <div id="reg_formanswer">
			        <?= $this->user_answer;?>
			    </div>
			<?php }?>

			<div id="reg_form">
				<form name="registration" action="./registration" method="post">
					<div id="reg_formline">
				        <div id="reg_formdescr">* Benutzername:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_nickname" size="50" value="<?= $this->post['user_nickname'];?>" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">* Passwort:</div>
				        <div id="reg_forminput">
				            <input type="password" class="input" name="user_password" size="50" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">* Passwort wiederholen:</div>
				        <div id="reg_forminput">
				            <input type="password" class="input" name="user_password_val" size="50" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">* E-Mail:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_email" size="50" value="<?= $this->post['user_email'];?>" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">* E-Mail wiederholen:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_email_val" size="50" value="<?= $this->post['user_email_val'];?>" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Vorname:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_fname" size="50" value="<?= $this->post['user_fname'];?>" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Nachname:</div>
				        <div id="reg_forminput">
				            <input type="text" class="input" name="user_lname" size="50" value="<?= $this->post['user_lname'];?>" onMouseOver="javascript:dispRegHelp(this.name);">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>

				    <div id="reg_formline">
				        <div id="reg_formdescr">Geburtsdatum:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_birth_day" onMouseOver="javascript:dispRegHelp('user_birthday');">
				                <option class="ffb_select_0" value=""></option>
				                <?php for($i=1;$i<32;$i++) {?>
				                    <option class="ffb_select_<?= ($i-1)%2;?>" <?php if($this->post['user_birth_day'] == $i) echo 'selected';?> value="<?= $i?>">
				                        <?= $i?>
				                    </option>
				                <?php }?>
				            </select>
				            <select class="ffb_select" name="user_birth_month" onMouseOver="javascript:dispRegHelp('user_birthday');">
				                <option class="ffb_select_0" value=""></option>
				                <?php $months = array('Januar','Februar','M&auml;rz','April','Mai','Juni','Juli','August','September','Oktober',
				                                  'November','Dezember');
				                  for($i=0;$i<12;$i++) {?>
				                    <option class="ffb_select_<?= $i%2;?>" <?php if($this->post['user_birth_month'] == $i+1) echo 'selected';?> value="<?= $i+1?>">
				                        <?= $months[$i]?>
				                    </option>
				                <?php }?>
				            </select>
				            <select class="ffb_select" name="user_birth_year" onMouseOver="javascript:dispRegHelp('user_birthday');">
				                <option class="ffb_select_0" value=""></option>
				                <?php $now = date('Y',time());
				                  $j=0;
				                  for($i=$now-11;$i>$now-101;$i--) {?>
				                    <option class="ffb_select_<?= $j%2;?>" <?php if($this->post['user_birth_year'] == $i) echo 'selected';?> value="<?= $i?>">
				                        <?= $i;
										$j++;?>
				                    </option>
				                <?php }?>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">Nationalit&auml;t:</div>
				        <div id="reg_forminput">
				            <select class="ffb_select" name="user_nationality" onMouseOver="javascript:dispRegHelp(this.name);">
				                <option class="ffb_select_0" value="">Land...</option>
				                <?php $i=0;
								foreach($country_array as $shortname => $name) {?>
				                    <option class="ffb_select_<?= $i%2;?>" <?php if($this->post['user_nationality'] == $shortname) echo 'selected';?> value="<?= $shortname?>">
				                        <?= $name;
										$i++;?>
				                    </option>
				                <?php }?>
				            </select>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">* Ich habe die
							<a href="<?= FFB_BASE_PATH;?>resource/Registrierung.pdf" target="_blank">Bedingungen</a> akzeptiert:</div>
				        <div id="reg_forminput" onMouseOver="javascript:dispRegHelp('user_tos');">
				            <input type="checkbox" name="user_tos" value="user_tos_yes">
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">* Best&auml;tigungs-Code:</div>
				        <div id="reg_forminput" onMouseOver="javascript:dispRegHelp('user_code');">
				            <?= $this->recaptcha_html;?>
				        </div>
				        <div id="reg_formclear"></div>
				    </div>
				    <div id="reg_formline">
				        <div id="reg_formdescr">&ensp;</div>
				        <div id="reg_forminput">
			                <input type="submit" class="submit" value="Registrieren" name="users_registration_insert">
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
		<div id="reg_right_main">
			<div id="reg_helptext_title"><b><u>Hinweise</u></b></div>
			<div id="reg_helptext">Alle Felder die mit einem * markiert sind, m&uuml;ssen ausgef&uuml;llt werden. Klick auf ein Feld um weitere Hinweise anzuzeigen.</div>
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
		    <?php include(FFB_VIEWER_PATH.'footer.php')?>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>

</div>
<!--** **//-->

</body>
