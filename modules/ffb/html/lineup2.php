<b><?php

/**
 *
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 02/2010
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="geri">
	<link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>lineup_v2.css" type="text/css">
    <link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>playerstats.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.FFB_INCLUDE_PATH?>matchdata.css" type="text/css">
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>lineup_v2_1.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>matchdata_v2.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>ranking.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>playerinfo.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
</head>

<body
<?php if($this->game_over == 1) {?>
    onLoad="javascript:gameOver();return;"
<?php } else {?>
    onLoad="javascript:initLineup();return;">
<?php }?>
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
    	<div class="rounddiv_leftteam">
			<div class="roundcorner_dark">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div id="Leftteam" style="min-height:1400px;">
			        <div id="lineup_infoarea">
			            <div id="lineup_infoarea_title"></div>
			            <div id="lineup_infoarea_actions"></div>
			            <div id="lineup_infoarea_infos"></div>
			            <div id="lineup_infoarea_credits"></div>
			        </div>

			        <div id="lineup_field_main">
			            <div id="soccer_field" style="background-image:url(<?php echo FFB_BASE_PATH . FFB_IMAGE_PATH?>backgrounds/soccer_field_round.png);">
			                <div id="line_goalie">
			                    <div id="line_elements_g"></div>
			                </div>

			                <div id="line_defence">
			                    <div id="line_elements_d"></div>
			                </div>

			                <div id="line_midfield">
			                    <div id="line_elements_m"><img src="<?php echo FFB_BASE_PATH . FFB_IMAGE_PATH?>/loading/ajax-loader-medium.gif" title="Laden..." alt="Laden..."></img></div>
			                </div>

			                <div id="line_forward">
			                    <div id="line_elements_s"></div>
			                </div>
			            </div>
			        </div>
			    </div>
			    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
    </div>

	<div class="rounddiv_mainright">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright" style="min-height:0px; padding-top:0px;">
		    	<div class="rounddiv_matchlist">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				        <div id="matchlist">
				        </div>
				        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
				<div class="rounddiv_lineupselectmain">
					<div class="roundcorner_light">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				        <div id="lineup_select_main">
				            <div id="lineup_select_team">
				            </div>
				            <div id="lineup_select_selected_team">
				            </div>
						    <div id="lineup_select_player">
						    </div>
						</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
				</div>
		    </div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

	<div class="rounddiv_mainrightads">
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="Mainright_ads">
				<?= $this->adBottomRight;?>
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

<div id="debug"></div>

</b>