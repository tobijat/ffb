<?php

/**
 * @author Gritschacher, Musser
 * @copyright 2008
 */
?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
	<link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
	<link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>matchpoints.css" type="text/css">
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/matchpoints.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/facebookAdmin.js"></script>
	<meta name="author" content="Musser">
	<meta name="author" content="Gritschacher">
</head>
<body onLoad="javascript:loadMatchround();">
<!--Facebook Code for connect-->
 <script src="http://static.ak.connect.facebook.com/connect.php/de_DE" type="text/javascript"></script><script type="text/javascript">FB.init("<?echo FFB_FACEBOOK_API_KEY;?>");</script>
 <!--Facebook ENDE-->
<div id="Container">
    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="administration_matchpoints">

        <div id="admintitle">Player points/round</div>

        <div id="Mainleft">
		<div id="send"><input type="button" value="send all" onclick="javascript:sendAll();"></div>
        <div id="Players">
			<div id="Home">
				<div id="HomeTitle"></div>
				<div id="loadingHome"><img src="<? echo FFB_BASE_PATH?>/images/loading/ajax-loader-medium.gif" alt="loading"></div>
				<ol id="HomePlayers"></ol>
				<!--div id="HomePlayers"></div-->
			</div>
			<div id="Guest">
				<div id="GuestTitle"></div>
				<div id="loadingGuest"><img src="<? echo FFB_BASE_PATH?>/images/loading/ajax-loader-medium.gif" alt="loading"></div>
				<ol id="GuestPlayers" ></ol>
				<!--div id="GuestPlayers"></div-->
			</div>
		</div>
		<div id="send"><input type="button" value="send all" onclick="javascript:sendAll();"></div>
        </div>

        <div id="Mainright">
        <div id="Matchround">Matchround aussuchen</div>
        <div id="Match">Team aussuchen</div>
        <hr />
        <div id="Twitter">Twitter</div>
        <div id="Facebook">Facebook</div>
        <div>
        <script type="text/javascript">
function callPublish(msg, attachment, action_link) {
	//streamPublish(String user_message,  Object attachment,  Object action_links,  String target_id,  String user_message_prompt,  Function callback,  Boolean auto_publish,  String actor_id)
	//var msgg = $(msg).value;

	FB.ensureInit(function () {
    FB.Connect.streamPublish('', attachment, action_link);
  });
}
function callbackPublish(post_id, exception) {
	alert('back');
	alert("Answer:" + post_id + exception);
}

</script>

        </div>
        
        <div id="SystemInfo0"></div>
        <div id="SystemInfo1"></div>
        <div id="Mostwanted"></div>
        
        </div>

    </div>

    <div id="Footer">
        <?include(ADM_VIEWER_PATH.'footer.php')?>
    </div>

</div>
</div>