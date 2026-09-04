<?php

/**
 * @file ffb/html/start.php
 * @author Gritschacher, Musser
 * @copyright 2008
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
	<meta name="file" content="ffb/html/start.php">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>news.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>poll.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>gamemgmt.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>playerstats.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>matchdata.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>start.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>news.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>poll.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>gamemgmt.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>userprofile.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>matchdata.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>

	<link rel="alternate" type="application/rss+xml" href="http://feeds2.feedburner.com/ffbat" title="Fantasy Football News (ffb.tobijat.at)" />
</head>

<body onload="javascript:loadStart(false, false);">

<div id="Container">
	<div class="rounddiv_nav">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="NavbarRound">
				<div id="Navigation">
			        <?include(FFB_VIEWER_PATH.'navigation.php')?>
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
    	<div class="rounddiv_leftteam" style="min-height:0px;">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		        <div id="Leftteam">
		            <div id="gm_game_list">
		            </div>
		        </div>
		        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>

		<div class="rounddiv_leftteam" style="min-height:0px;">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		        <div id="Leftteam">

					<div style="text-align:center; float:left; width:25%; margin-top:10px; margin-bottom:10px;">


		                <? echo $this->adLeft; ?>
		            </div>
		            <div style="text-align:center; float:left; width:48%; margin-top:15px;">
		                <div id="news_items_title"></div>
		                <div id="news_items"></div>
		                <br />
		            </div>
					<div style="text-align:center; float:right; width:25%; margin-top:00px;">



							<? echo $this->adRight; ?>
					</div>
		            <div style="clear:both;"></div>
		        </div>
		        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
    </div>

	<div class="rounddiv_mainright">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright" style="min-height:0px;">
		    	<div class="rounddiv_logininfo">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div id="login_info">
							<div style="width:30%;float:left;text-align:right;">
								<a href="<? echo FFB_BASE_PATH; ?>users/account/accountDetails.html" title="Profil bearbeiten">
									<img border="0" src="<? echo FFB_BASE_PATH.FFB_IMAGE_PATH."profiles/photo/".$this->session->user_photo;?>" width="60px" alt="Manager Foto <? echo $this->session->user_name; ?>">
									<!-- <img src="<? echo FFB_BASE_PATH.FFB_IMAGE_PATH."profiles/avatar/".$this->session->user_avatar; ?>" width="48px" alt="Avatar">//-->
								</a>
							</div>
							<div style="width:60%;float:left;text-align:left;margin-left:5%;">
								<br>
					        	<b>Hallo
								<a class="nolink" href="javascript:void(0);" onclick="javascript:dispUserinfoPopup(<?echo $this->session->user_id;?>);" title="Profil anzeigen">
									<em><u><?echo $this->session->user_nickname;?></u></em>!
								</a>
								</b>
								<br/>
					        	Du bist angemeldet.
					        </div>
					        <div style="clear:both;"></div>
					        <?if($this->updateProfileNag) {?>
								<div style="width:100%; text-align:center; color:red; font-size:11pt; padding-top:2px;">
									<b><em>Dein Profil ist noch leer.</em></b>
								</div>
								<div style="width:100%; text-align:center; font-size:8pt;">
									<a href="<? echo FFB_BASE_PATH; ?>users/account/accountDetails.html" title="Profil aktualisieren">&rArr; Profil aktualisieren</a>
								</div>
							<?}?>
		        		</div>
		        		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
				<div class="rounddiv_selectpoll" id="rounddiv_selectpoll">
				</div>
		    </div>
		    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

	<div class="rounddiv_mainrightads">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright_ads">
				<div style="text-align:left">
					<!-- KICKER -->
					<script language="JavaScript" src="http://feed2js.org//feed2js.php?src=http%3A%2F%2Frss.kicker.de%2Fnews%2Fem&num=5&desc=1&date=y&targ=y&utf=y"  charset="UTF-8" type="text/javascript"></script>
					<noscript>
						<a href="http://feed2js.org//feed2js.php?src=http%3A%2F%2Frss.kicker.de%2Fnews%2Fem&chan=y&num=5&desc=1&date=y&utf=y&html=y">View RSS feed</a>
					</noscript>

					<!-- FIFA DE
					<script language="JavaScript" src="http://feed2js.org//feed2js.php?src=http%3A%2F%2Fde.fifa.com%2Fworldcup%2Fnews%2Frss.xml&desc=1&date=y&targ=popup&utf=y"  charset="UTF-8" type="text/javascript"></script>
					<noscript>
						<a href="http://feed2js.org//feed2js.php?src=http%3A%2F%2Fde.fifa.com%2Fworldcup%2Fnews%2Frss.xml&desc=1&date=y&targ=n&utf=y&html=y">View RSS feed</a>
					</noscript>
					-->
				</div>
				<br>
		  		<?echo $this->adBottomRight;?>
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

<!--div id="seltest">
</div>
<div id="selanswer">
</div-->