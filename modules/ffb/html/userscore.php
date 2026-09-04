<?php

/**
 * @author Gritschacher Tobias, Musser Gerald
 * @copyright 2010
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="geri">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>userscore.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>playerstats.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>matchdata.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>comments.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>statistics.css" type="text/css">
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>userscore_v2.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>matchdata.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>playerinfo.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>userprofile.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>comments.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
</head>

<body onLoad="javascript:initUserscore(<?echo $this->session->user_id?>);">

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
		
		
	<!-- advertising-->	
	<!--div class="rounddiv_mainrightads"-->
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright_villacher">
		  		<? if($this->adGuaranaBrause)
		  			echo $this->adGuaranaBrause;
	  			?>
	  			
		    </div>
		    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	<!--/div-->

	<!--div class="rounddiv_mainrightads"-->
		<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		    <div id="Mainright_ads">
		  		<?echo $this->adBottomRight;?>
		    </div>
		    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	<!--/div-->		
	<!--advertising end-->	
		
		  <!--comments start -->
			<div class="roundcorner_dark">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div id="Mainright_comments">
        <div class="userComment" id="comment_box">
          <div class="userCommentHead">
            Kommentar verfassen:
          </div>
          <div class="userCommentBody">
            <form name="addCommentForm" id="addCommentForm" >
              <textarea name="comment_text" cols="30" rows="2" id="comment_text"></textarea>
            </form>
          </div>
          <div class="userCommentFooter">
            <input class="commentButton" type="button" onclick="javascript:addComment('userscore', null);" value="Meinung teilen"/>
          </div>
        </div>
        
        <?
          $index  = 0;
          $modulo = 3;
          $mstart = $modulo;
          if($this->comments) {
          echo '<div id="comments_1">';
          
          for(;$index<count($this->comments) && $index<3;$index++) {
            echo  '<div class="userComment' . $index%2 .  '"><div class="userCommentHead">';
            echo  '<img src="' .  FFB_BASE_PATH . FFB_IMAGE_PATH . 'profiles/avatar/' . $this->comments[$index]['user_avatar'] . '" width="25px" /> ';
            echo  $this->comments[$index]['user_nick'] . ':</div>';
            echo  '<div class="userCommentBody">'   . $this->comments[$index]['comment_text'] ."</div>\r\n";
            echo  '<div class="userCommentFooter">' . $this->comments[$index]['comment_date'] . '</div></div>';
          }
          
        echo "</div>\r\n";
          
          if($this->adCommentText[0]) {
            echo  '<div class="userCommentAd"><div class="userCommentHead" style="font-size:50%;"></div>';
            echo  '<div class="userCommentBody" style="margin-left:25px;">';
            echo  $this->adCommentText[0] ."</div>\r\n"; 
            echo  '<div class="userCommentFooter"></div></div>';
          }
          
        echo '<div id="comments_2">';
          
          for(;$index<count($this->comments) && $index<7;$index++) {
            echo  '<div class="userComment' . $index%2 .  '"><div class="userCommentHead">';
            echo  '<img src="' .  FFB_BASE_PATH . FFB_IMAGE_PATH . 'profiles/avatar/' . $this->comments[$index]['user_avatar'] . '" width="25px" /> ';
            echo  $this->comments[$index]['user_nick'] . ':</div>';
            echo  '<div class="userCommentBody">'   . $this->comments[$index]['comment_text'] ."</div>\r\n";
            echo  '<div class="userCommentFooter">' . $this->comments[$index]['comment_date'] . '</div></div>';
          }
         
        echo "</div>\r\n";
           
          if($this->adCommentText[1]) {
            echo  '<div class="userCommentAd"><div class="userCommentHead" style="font-size:50%;"></div>';
            echo  '<div class="userCommentBody" style="margin-left:25px;">';
            echo  $this->adCommentText[1] ."</div>\r\n"; 
            echo  '<div class="userCommentFooter"></div></div>';
          }
          
        echo '<div id="comments_3">';
          
          for(;$index<count($this->comments) && $index<15;$index++) {
            echo  '<div class="userComment' . $index%2 .  '"><div class="userCommentHead">';
            echo  '<img src="' .  FFB_BASE_PATH . FFB_IMAGE_PATH . 'profiles/avatar/' . $this->comments[$index]['user_avatar'] . '" width="25px" /> ';
            echo  $this->comments[$index]['user_nick'] . ':</div>';
            echo  '<div class="userCommentBody">'   . $this->comments[$index]['comment_text'] ."</div>\r\n";
            echo  '<div class="userCommentFooter">' . $this->comments[$index]['comment_date'] . '</div></div>';
          }
          
        echo "</div>\r\n";
            
          if($this->adCommentText[2]) {
            echo  '<div class="userCommentAd"><div class="userCommentHead" style="font-size:50%;"></div>';
            echo  '<div class="userCommentBody" style="margin-left:25px;">';
            echo  $this->adCommentText[2] ."</div>\r\n"; 
            echo  '<div class="userCommentFooter"></div></div>';
          }
         
         echo '<div id="comments_4">';
          
          for(;$index<count($this->comments) ;$index++) {
            echo  '<div class="userComment' . $index%2 .  '"><div class="userCommentHead">';
            echo  '<img src="' .  FFB_BASE_PATH . FFB_IMAGE_PATH . 'profiles/avatar/' . $this->comments[$index]['user_avatar'] . '" width="25px" /> ';
            echo  $this->comments[$index]['user_nick'] . ':</div>';
            echo  '<div class="userCommentBody">'   . $this->comments[$index]['comment_text'] ."</div>\r\n";
            echo  '<div class="userCommentFooter">' . $this->comments[$index]['comment_date'] . '</div></div>';
          }
          
        echo "</div>\r\n";
          
       if($this->numComments>0) { ?>
        <a href="javascript:void(0);" onclick="javascript:getComments('userscore', null);return;" style="font-size:80%;">alle <? echo $this->numTotalComments; ?> Community Meinungen anzeigen</a>
        <?}
        } else {
          echo '<div id="comments_1"></div>';
          echo '<div id="comments_2"></div>';
          echo '<div id="comments_3"></div>';
          echo '<div id="comments_4"></div>';
        }?>

			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<!--comments end-->
		
		
		
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
