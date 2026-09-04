	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>playermanagement.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/tm_team_parser.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/transfermarkt_team_array.js"></script>
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
<div id="admintitle">AutoPlayers for Weltfussball.at Source</div>
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
	  		<select name="player_team" id="insertplayer_team" onchange="javascript:changeTeam();">
                <option value="">select team</option>
            </select>
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">* URL:</div>
	  	<div id="forminput">
	  		<input type="text" size="35" name="playermanagement_tm_url" id="playermanagement_tm_url" value="<?echo $this->post['playermanagement_tm_url'];?>">
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">* Source:</div>
	  	<div id="forminput">
	  		<select name="source" id="source" onchange="javascript:changeTeam();">
                <!--<option selected value="tm">Transfermarkt</option>//-->
                <option value="wf">Weltfussball</option>
            </select>
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">* Kader:</div>
	  	<div id="forminput">
	  		<select name="kader" id="kader" onchange="javascript:changeTeam();">
                <option value="friend2010">Freundschaft 2010</option>
                <option value="emquali2012">EMQUALI 2012</option>
                <option value="wm2010">WM 2010</option>
                <option value="wmqualieuropa2014">WMQUALI EUROPA 2014</option>
                <option selected value="wm2014">WM 2014</option>
				<option selected value="em2016">EM 2016</option>
				<option selected value="emquali2016">EM-Quali 2016</option>
            </select>
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">&ensp;</div>
	  	<div id="forminput">
	  		<input type="button" onclick="javascript:loadSite()" value="Get Data">
	  	</div>
	  	<!--
	  	<div id="forminput">
	  		<input type="button" onclick="javascript:setIds()" value="Start">
	  	</div>
	  	//-->
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