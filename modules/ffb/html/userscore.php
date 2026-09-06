<?php

/**
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 2010
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="geri">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>userscore.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>playerstats.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>matchdata.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>statistics.css" type="text/css">
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>userscore_v2.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>matchdata.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>playerinfo.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>userprofile.js"></script>
</head>

<body onLoad="javascript:initUserscore(<?= $this->session->user_id?>);">

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

    <div id="Mainleft">
    	<div class="rounddiv_leftteam">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    	<div id="Leftteam">
			    	<div id="lineup_infoarea">
			            <div id="lineup_infoarea_title"></div>
			            <div id="lineup_infoarea_infos"></div>
			        </div>

			        <div id="UserscoreMain">
			        </div>
		        </div>
		        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
    </div>

	<div class="rounddiv_mainright">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright" style="min-height:0px;">
		    	<div class="rounddiv_lineupselectmain">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				    	<div id="lineup_select_main">
				            <div style="padding-left:5px; padding-right:5px; font-size:8pt;">
								<em><b>Tipp:</b> Klick in der Rangliste auf einen Nickname um n&auml;here Infos &uuml;ber den Teilnehmer
								und seine erreichten Auszeichnungen zu bekommen!</em>
							</div>
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
		    	<div class="rounddiv_lineupselectmain">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				    	<div id="lineup_select_main">
				            <div id="lineup_select_round"></div>
				            <div id="lineup_select_user"></div>
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>

		        <div class="rounddiv_matchlist">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				        <div id="matchlist">
				        </div>
				        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
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
