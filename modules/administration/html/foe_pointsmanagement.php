	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>playermanagement.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<!--
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/playermanagement.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/transfermarkt_team_array.js"></script>
	//-->
</head>
<body onload="javascript:init()">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">Fußball Österreich - Pointsmanagement</div>
    <div id="formerror" style="visibility:hidden;">
    </div>
    <div id="formanswer" style="visibility:hidden;">
    </div>

<?include(INCLUDE_PATH.'country_list.php');?>
<div id="form">
<form name="administration_form" id="administration_form" action="./foe_pointsmanagement/getFile.html" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
    <!--
    <div id="formline">
	  	<div id="formdescr">* Team:</div>
	  	<div id="forminput">
	  		<input type="hidden" name="player_team_post" value="<?echo $this->post['player_team'];?>">
	  		<select name="player_team" id="insertplayer_team" onchange="javascript:changeTeam();">
                <option value="">select team</option>
            </select>
	  	</div>
	</div>
	//-->
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">* FILE:</div>
	  	<div id="forminput">
	  		<input type="file" name="foe_file" id="pointsmanagement_foe_file">
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">&ensp;</div>
	  	<div id="forminput">
	  		<input type="submit" onclick="javascript:loadSite()" value="Send">
	  	</div>
	</div>
	<div id="formclear"></div>

</form>
</div>

<div id="playerlist_found"></div>

<div id="playerlist_db"></div>

<div id="playerlist_tm"></div>

<div style="clear:both;"></div>

<div id="playerlist_update"></div>

<div id="send_updates_button_div"></div>

</div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
