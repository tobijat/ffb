<?php

/**
 * @file users/html/ffb_login.php
 * @author Gritschacher, Musser
 * @copyright 2008
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
	<meta name="file" content="users/html/ffb_login.php">
	<meta name="verify-v1" content="dSsXBlV/GGbH9Vi1UNJkbCxfTMrVM700E2uCSGJg3PA=" />
	<meta name="description" content="Fussball Manager Browserspiel mit den Ligen: <?php  if($this->leagues) foreach($this->leagues AS $league) { echo $league->getGameTitle() ." ";} ?>"/>
	<meta name="keywords" content="Fussball, Fu&szlig;ball, Manager, Tippspiel, Team, Aufstellen,<?php  if($this->leagues) foreach($this->leagues AS $league) { echo $league->getGameTitle() .", ";} ?>"/>

	<link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>login.css" type="text/css">
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
    <script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>user/login.js"></script>


    <link rel="alternate" type="application/rss+xml" href="http://feeds2.feedburner.com/ffbat" title="Fantasy Football News (ffb.tobijat.at)" />

<! -- script for news ticker -- !>
<script language="JavaScript">

<!-- Begin
// news ticker function
var newslist=new Array();
var cnt=0;			// current news item
var letterCnt = 0;
var currLetter = 0;
var innerCnt = 0;
var totalLineCount = 0;
var maxLineCnt = 250;
var newsTimeout = 55.0;
var startMe = true;
var maxDisplay = 3;

var curr = "";

<?php 
  //echo "\r\n<!--\r\n";
  //print_r($this->lastResults);
  //echo "\r\n-->\r\n"; 
	if($this->lastResults[0]) {
	$index=0;
	foreach($this->lastResults AS $result) {
		echo "newslist[$index] = new Object();\r\n";
		echo "newslist[$index]['homeTeam'] = '" .$result['homeTeam'] ."';\r\n";
		echo "newslist[$index]['homeScore'] = '" .$result['homeScore'] ."';\r\n";;
		echo "newslist[$index]['homeFlag'] = '" .$result['homeFlag'] ."';\r\n";;
		echo "newslist[$index]['guestTeam'] = '" .$result['guestTeam'] ."';\r\n";;
		echo "newslist[$index]['guestScore'] = '" .$result['guestScore'] ."';\r\n";;
		echo "newslist[$index]['guestFlag'] = '" .$result['guestFlag'] ."';\r\n";;
		echo "newslist[$index]['date'] = '" .$result['date'] ."';\r\n";;
		$index++;
	}
}

?>
//TEXT - URL
//newslist[0]=new Array("Check out the share check javascript","t_sharecheck.html")

function newsticker()
{ 
  if(startMe == true)
  {
    document.getElementById('mtxt').innerHTML = "";
    letterCnt = 0;
    currLetter = 0;
    innerCnt = 0;
    totalLineCount = 0;

    startMe = false;
  }

  if(cnt < newslist.length)
  {
    switch(innerCnt)
    {
      case 0://date
        curr = ' ' + newslist[cnt]['date'] + ' ';
        break;
      break;
      case 1:// home name
        curr = newslist[cnt]['homeTeam'];
      break;
      case 2:// img home
        curr = ' <img src="' + server + flagImages_ + newslist[cnt]['homeFlag'] + '" alt="' + newslist[cnt]['homeFlag'] + '" width="16"/>';
        document.getElementById('mtxt').innerHTML += curr;
        totalLineCount += curr.length;
        innerCnt++;
        setTimeout('newsticker()',newsTimeout);
        return;       
      break;
      case 3://score home
         curr = ' ' + newslist[cnt]['homeScore'];
      break;
      case 4://sep ':'
        curr = ':';        
      break;
      case 5://guest score
         curr = String(newslist[cnt]['guestScore']) + ' ';
      break;
      case 6://img guest
        curr ='<img src="' + server + flagImages_ + newslist[cnt]['guestFlag'] + '" alt="' + newslist[cnt]['guestFlag'] + '" width="16"/> '; 
        document.getElementById('mtxt').innerHTML += curr;
        totalLineCount += curr.length;
        innerCnt++;
        setTimeout('newsticker()',newsTimeout);
        return;
      break;
      case 7://guest name
         curr = ' ' + newslist[cnt]['guestTeam'];
      break;
      default:
        cnt++;
        innerCnt = 0;
        currLetter = 0;
        document.getElementById('mtxt').innerHTML += "\r\n<br/>\r\n";
        if( (cnt % maxDisplay) == 0)
          startMe = true;
        if(totalLineCount > maxLineCnt)
        {
          totalLineCount = 0;
          //document.getElementById('mtxt').innerHTML += "\r\n<br/>\r\n";          
        }
        if(startMe == false)
          setTimeout('newsticker()',newsTimeout);
        else
          setTimeout('newsticker()',newsTimeout*30);
        return;
      break;    
    }
    
    if(currLetter >= curr.length)
    {
      currLetter = 0;
      innerCnt++;
      setTimeout('newsticker()',1);
      return;
    }
    
    document.getElementById('mtxt').innerHTML += curr[currLetter];
    currLetter++;
    totalLineCount++;
    setTimeout('newsticker()',newsTimeout); 
  }
  else
  {
    cnt=0;
    startMe = true;
    setTimeout('newsticker()',newsTimeout*30);
  }

}
-->
</script>

<!-- Dieses Tag in den Head-Bereich oder direkt vor dem schlieüenden Body-Tag einfügen -->
<!-- google +1 Button Script-->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'de', parsetags: 'explicit'}
</script>
</head>
<!-- newsticker(); exclude snowflakes in footer bevor activating and change div text where ticker is displayed-->
<body onload=" this.document.login.user_nickname.focus(); newsticker();">
<div id="Container">

    <div class="rounddiv_nav">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="NavbarRound">
				<div id="Navigation">
			        <?php include(FFB_VIEWER_PATH.'navigation.php')?>
			    </div>

			    <div class="rounddiv_countdown">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div id="Countdown">
					        <script>
					            loadMe();
					        </script>
					    </div>
					    <div style="clear:both;"></div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
			    <div style="clear:both;"></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

    <div id="Mainleft">
    	<div class="rounddiv_leftinfo">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		        <div id="Leftinfo">
		            <div style="text-align:center; font-size:16pt; padding:3px;">
		                <b>Willkommen bei SoccerSportsfan!</b>
		            </div>
		            <div style="text-align:center; font-size:12pt; padding:3px; font-family:Kristen ITC;">
		                Jetzt bei der <b>Fu&szlig;ball EM 2016 in Frankreich</b> mitmachen!<br>
                        <!-- no Villacher ads for EM 2016 -->
                        <!--
		                <div style="float:left; width:100%; text-align:center;">
							<img src="http://soccer.sportsfan.at/images/ffb/symbols/villacher_kiste_200.png" height="90px" title="Registrieren, mitspielen und Bier von Villacher gewinnen!">
							<img src="http://soccer.sportsfan.at/images/ffb/symbols/villacher_kiste_200.png" height="90px" title="Registrieren, mitspielen und Bier von Villacher gewinnen!">
							<img src="http://soccer.sportsfan.at/images/ffb/symbols/villacher_kiste_200.png" height="90px" title="Registrieren, mitspielen und Bier von Villacher gewinnen!">
						</div>
						//-->
						<!-- disabled for WM 2014 -->
						<!--
						<div style="float:right; width:58%; text-align:left; padding-top:25px;">
			                <a href="<?= FFB_BASE_PATH?>users/registration" title="teilnehmen">
								<img border="0" src="<?= FFB_BASE_PATH?>/images/ffb/backgrounds/beapartofit.gif" width="200px"
									onmouseover="javascript:this.src='<?= FFB_BASE_PATH?>/images/ffb/backgrounds/beapartofit2.gif'"
									onmouseout="javascript:this.src='<?= FFB_BASE_PATH?>/images/ffb/backgrounds/beapartofit.gif'"/>
							</a>
						</div>
						//-->
						<div style="clear:both;"></div>
		                <!--b>Registriere dich jetzt und <a class="nav" href="<?= FFB_BASE_PATH?>users/registration">sei <i>kostenlos</i> dabei</a>!</b-->
		            </div>
		        </div>
		        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>

        <div class="rounddiv_leftteam">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>

				<div id="Leftteam">
					<!-- News Ticker -->
                	<div class="mtxt" Id="mtxt" >Herzlich Willkommen!</div>
          <!-- NewsTicker End-->

					<div style="text-align:center; margin-top:10px;">
						<img id="imgfront" src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>backgrounds/france16_353.png" width="353px" style="border:0" />
			            <br /><br />
			            <div class="ffb_infos">
						<b>
						<?php if($this->userCountTotal[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>navigation/nav_user.png"  /></div>
								<div class="ffb_infos_description">Gesamt registrierte Benutzer:</div>
								<div class="ffb_infos_info"><?php  echo $this->userCountTotal[0]->getUserId(); ?></div>
							</div>
						<?php }?>
						<?php if($this->userCountToday[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>symbols/symbol_user.png"  /></div>
								<div class="ffb_infos_description">Heute eingeloggt:</div>
								<div class="ffb_infos_info"><?php  echo $this->userCountToday[0]->getUserId(); ?></div>
							</div>
						<?php }?>
						<?php if($this->userCountUserteams[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>symbols/stats_lineup.png"  /></div>
								<div class="ffb_infos_description">Aufgestellte Teams:</div>
								<div class="ffb_infos_info"><?php  echo $this->userCountUserteams[0]->getUserteamId(); ?></div>
							</div>
						<?php }?>
						<?php if($this->userCountUserteamScore[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>symbols/stats.png"  /></div>
								<div class="ffb_infos_description">Gesamt Benutzer Score:</div>
								<div class="ffb_infos_info"><?php  echo $this->userCountUserteamScore[0]->getUserteamId(); ?></div>
							</div>
						<?php }?>
						<?php if($this->userCountUserteamScore[0] && $this->userCountUserteams[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>symbols/symbol_score.png"  /></div>
								<div class="ffb_infos_description">Punkteschnitt pro Aufstellung:</div>
								<div class="ffb_infos_info"><?php  echo round( ($this->userCountUserteamScore[0]->getUserteamId() / $this->userCountUserteams[0]->getUserteamId()),2); ?></div>
							</div>
						<?php }?>
						<?php if($this->matchrounds[0]) {?>
							<div class="ffb_infos_single_info">
								<div class="ffb_infos_image"><img class="ffb_infos_img"  src="<?= FFB_BASE_PATH?><?= FFB_IMAGE_PATH?>navigation/nav_player.png"  /></div>
								<div class="ffb_infos_description">Ausgetragene Matchrunden:</div>
								<div class="ffb_infos_info"><?php  echo $this->matchrounds[0]->getMatchroundId(); ?></div>
							</div>
						<?php }?>
						<div class="ffb_infos_single_info"></div>
						<?php if($this->hScore[0] && $this->gScore[0]) {?>
						<div class="ffb_infos_single_info">
						Spielstand:<br />
						Heim <?php  echo $this->hScore[0]->getMatchId() . ":" . $this->gScore[0]->getMatchId(); ?> Gast<br />
						</div>
						<?php }?>

						</b>
						</div>


			            <br />
			            <br />

		            </div>

					<div style="clear:both;"></div>
					<br><br>
		        </div>

		        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>

    </div>

	<div class="rounddiv_mainright">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright">
		        <div id="administration">
					<div class="rounddiv_loginform">
						<div class="roundcorner_light">
							<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					        <div id="login_form">
					        	<div id="formtitle">Anmelden</div>
					        	<div id="answer"></div>
					        	<?php if(is_array($this->errors)) {?>
								    <div id="login_formerror">
								        <b>Es sind Fehler aufgetreten:</b><br>
								        <?php foreach($this->errors as $error) {
								            echo '* '.$error.'<br>';
								        }?>
							        </div>
								<?php }?>
								<?php if($this->user_answer) {?>
								    <div id="login_formanswer">
								        <?= $this->user_answer;?>
								    </div>
								<?php }?>
								<div id="loginforminput">
						            <form name="login" id="login" method="POST" onsubmit="return false" enctype="multipart/form-data">
						                <div id="formline">
						                    <div id="formdescr">* Nickname:</div>
						                    <div id="forminput">
						                        <input type="text" name="user_nickname" value="<?= $this->post['user_nickname'];?>">
						                    </div>
						                    <div id="formclear"></div>
						                </div>
						                <div id="formline">
						                    <div id="formdescr">* Passwort:</div>
						                    <div id="forminput">
						                        <input type="password" name="user_password" value="">
						                    </div>
						                    <div id="formclear"></div>
						                </div>
						                <div id="formline_center">
						                    <input type="hidden" name="destination" value="<?= $_GET['destination']?>">
						                    <input type="submit" value="Anmelden" onclick="javascript:authenticate();">

						                </div>
						                <div id="formline_center">
						                    <a href="javascript:void(0);" onclick="javascript:forgottenPassword();">Passwort vergessen?</a>
						                    <a href="<?php  echo FFB_BASE_PATH."users/registration";?>" title="neuen Benutzer anlegen">Registrieren</a>
						                    <!--dummy login für geris handy browser-->
						                    <!--a href="javascript:authenticate();" style="font-size:50%;" onclick="javascript:authenticate();return;" title="Login" >&nbsp;L&nbsp;</a-->
						                    <!--end-->
						                </div>

						            </form>
					            </div>
					        </div>
					        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
		        </div>



				<div class="media_buttons">
					<!-- Google Plus -->
					<g:plusone size="small" href="http://soccer.sportsfan.at"></g:plusone>
					<script type="text/javascript">gapi.plusone.go();</script>
					<!-- Twitter -->
					<a href="http://www.twitter.com/fsoccer" target="_new" style="border:0px;"><img style="border:0px;" src="http://twitter-badges.s3.amazonaws.com/t_small-a.png" alt="Follow fsoccer on Twitter" width="30px"/></a>&nbsp;&nbsp;
					<!-- RSS Feed -->
					<!-- disabled for WM 2014 - not working anymore -->
					<!--<a href="http://feeds2.feedburner.com/ffbat" target="_new" title="Soccer Sportsfan RSS"><img style="border:0;" src="<?php  echo $FFB_BASE_PATH.FFB_IMAGE_PATH."symbols/rss_256x256.png";?>" width="32px" alt="Soccer Sportsfan RSS"/></a>-->
				</div>

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