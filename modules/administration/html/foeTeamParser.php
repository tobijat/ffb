	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>playermanagement.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/foe_team_parser.js"></script>
</head>
<body onload="javascript:initFoeTeamParser()">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">AutoPlayers for Fussballoesterreich.at Source</div>
    <div id="formerror" style="visibility:hidden;">
    </div>
    <div id="formanswer" style="visibility:hidden;">
    </div>

<?include(INCLUDE_PATH.'country_list.php');?>
<div id="form">
<form name="administration_form" id="administration_form" action="./player" method="post" accept-charset="UTF-8">
    <div id="formline">
	  	<div id="formdescr">* Team:</div>
	  	<div id="forminput">
	  		<input type="hidden" name="player_team_post" value="<?echo $this->post['player_team'];?>">
	  		<select name="player_team" id="insertplayer_team" style="width:150px;">
                <option value="">select team</option>
            </select>
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">&ensp;</div>
	  	<div id="forminput">
	  		<input type="button" onclick="javascript:loadSite()" value="Get Data">
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