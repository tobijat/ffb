<?php

/**
 * @author Musser
 * @copyright 2009
 */
?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="author" content="Gritschacher, Musser" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css" />
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
    	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
    <script type="text/javascript">
function resizeIframe(iframeID) {
//if(self==parent) return false; /* Checks that page is in iframe. */
//else if(document.getElementById&&document.all) /* Sniffs for IE5+.*/

var FramePageHeight = document.body.scrollHeight; /* framePage
is the ID of the framed page's BODY tag. The added 10 pixels prevent an
unnecessary scrollbar. */
FramePageHeight *= 2;
FramePageHeight += 20;
parent.document.getElementById(iframeID).height=FramePageHeight;
/* "iframeID" is the ID of the inline frame in the parent page. */
}
	</script>
<script type="text/javascript">
function adjustIFrameSize (iframeWindoww) {
  var iframeWindow = document.getElementById(iframeWindoww);
  if (iframeWindow.document.height) {
    var iframeElement = document.getElementById
(iframeWindow.name);
    iframeElement.style.height = iframeWindow.document.height + 'px';
    iframeElement.style.width = iframeWindow.document.width + 'px';
  }
  else if (document.all) {
    var iframeElement = document.all[iframeWindow.name];
    if (iframeWindow.document.compatMode &&
        iframeWindow.document.compatMode != 'BackCompat')
    {
      iframeElement.style.height =
iframeWindow.document.documentElement.scrollHeight + 5 + 'px';
      iframeElement.style.width =
iframeWindow.document.documentElement.scrollWidth + 5 + 'px';
    }
    else {
      iframeElement.style.height =
iframeWindow.document.body.scrollHeight + 5 + 'px';
      iframeElement.style.width =
iframeWindow.document.body.scrollWidth + 5 + 'px';
    }
  }
}
</script>
</head>

<body id="frame_body" onload="resizeIframe('ffb_managerbereich');">
	<a name="top"></a>
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

		<div id="ffb_managerbereich_div" style="width:100%;height:100%;min-height:768px;">
				<iframe width="100%"  id="ffb_managerbereich" src="http://ffb.gemura.com/forum/ucp.php?mode=login&username=<? echo $this->session->user_nickname; ?>&password=<? echo $this->session->user_password; ?>&login=login&SID=<? echo session_id(); ?>&redirect=http://ffb.gemura.com/forum/index.php&SID=<? echo session_id(); ?>>" height="100%" name="ffb_managerbereich"  marginwidth="0" marginheight="0" frameborder="0">
				<p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:
  				Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden Verweis
		  		aufrufen: <a href="http://ffb.gemura.com/forum/index.php" target="_blank">FFB Managerecke</a></p>
		  		</iframe>
		</div>
		<a href="#top" title="Seitenanfang">zum Seitenanfang</a>

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