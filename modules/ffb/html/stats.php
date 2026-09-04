<?php

/**
 *
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 2009
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="geri">
	<link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
	<link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>stats.css" type="text/css">
	<!--link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>playerstats.css" type="text/css"-->

    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>stats.js"></script>
	<!--script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>playerinfo.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>matchdata.js"></script-->


</head>

<body onload="javascript:loadStatsMenu();return;">
<a name="top"></a>
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
		<div>
			<h1>&Uuml;bersicht <span style="font-size:33%; font-style:italic;">alpha</span></h1>

		</div>
		<div id="statsmain"></div>
	
    </div>

	<div class="rounddiv_mainright">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright" style="min-height:667px;">
			<h2>Optionen</h2>
			<form name="statsCorner" id="statsCorner">
				<!--div class="stats_entry" id="playerStatsCriteria" >
					<h3>Spieler Anzeigekriterien</h3>
					<div class="stats_section" >
					
						<div class="checkbox_div">
							<div class="checkbox_div_l">
								<input type="checkbox" name="score"> Punkte
							</div>
							<div class="checkbox_div_m">
							</div>
							<div class="checkbox_div_r">
								<input type="checkbox" name="time"> Einsatzdauer
							</div>
						</div>
						<br/>
						<hr/>
						<div class="checkboxDiv">
							<div class="checkbox_div_l">
								<input type="checkbox" name="cards"> Karten
							</div>
							<div class="checkbox_div_m">
							</div>
							<div class="checkbox_div_r">
								<input type="checkbox" name="lineup"> Eins&auml;tze
							</div>
						</div>
						<br/>
						<hr/>
						<div class="checkboxDiv">
							<div class="checkbox_div_l">
								<input type="checkbox" name="team"> Teamzugeh&ouml;rigkeit
							</div>
							<div class="checkbox_div_m">
							</div>
							<div class="checkbox_div_r">
							</div>
						</div>
						<br/>
		
					</div>
				</div-->

				<div class="stats_entry" id="teamStats">
					<h3>Teamkader:</h3>
					<div class="stats_section" >
						<div calss="stats_select" id="teamStatsSelect">
							<img src="<?php  echo FFB_BASE_PATH.FFB_IMAGE_PATH?>loading/ajax-loader-bar-big.gif" alt="loading">
						</div>
					</div>
					
					<!--div calss="stats_select" id="teamStatsSelectSearch">
						Teamsuche: <input type="text" onfocus="javascript:startSearch('team'); return;" onblur="javascript:stopSearch(); return;" name="searchTeam" size="30" maxlength="255"/> 
					</div>
					Ergebnisliste:
					<div class="stats_select" id="teamStatsSelectResult">
						<br/>
					</div-->
					
				</div>
				
				<div class="stats_entry" id="leagueStats">
					<h3>Ligen:</h3>
					<div class="stats_select" id="leagueStatsSelect">
						<img src="<?php  echo FFB_BASE_PATH.FFB_IMAGE_PATH?>loading/ajax-loader-bar-big.gif" alt="loading">
					</div>
					
					<!--div calss="stats_select" id="leagueStatsSelectSearch">
						Ligasuche: <input type="text" onfocus="javascript:startSearch('league'); return;" onblur="javascript:stopSearch(); return;" name="searchTeam" size="30" maxlength="255"/> 
					</div>
					Ergebnisliste:
					<div class="stats_select" id="leaguetatsSelectResult"> 
						<br/>
					</div-->
				</div>				
				
				<!--div class="stats_entry" id="playerStats">
					<h3>Spieler:</h3>
					<div class="stats_section" >
						<div calss="stats_select" id="playerStatsSelectCountry">
							<img src="<?php  echo FFB_BASE_PATH.FFB_IMAGE_PATH?>loading/ajax-loader-bar-big.gif" alt="loading">
						</div>
						
						<div calss="stats_select" id="playerStatsSelectSearch">
							Spielerdirektsuche: <input type="text" onfocus="javascript:startSearch('player'); return;" onblur="javascript:stopSearch(); return;" name="searchPlayer" size="30" maxlength="255"/>
						</div>
						Ergebnisliste:
						<div class="stats_select" id="playerStatsSelectResult">
						<br/>
						</div>
					</div>
					
					
				</div-->
				
				
			</form>
			<div class="duration" id="durationNW"></div>
			<div class="duration" id="durationPHP"></div>
			<div class="duration" id="durationJS"></div>
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
<div id="pre"></div>
</div>