	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>fifa_playermanagement.css" type="text/css">
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH?>script/admin/fifa_playermanagement.js"></script>
</head>
<body onload="javascript:init()">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?php include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">Playermanagement (FIFA)</div>
<!--
<div id="form">
    Anleitung:<br>
    <ul>
        <li>Matchround auswaehlen</li>
        <li>Match auswaehlen</li>
        <li>-> die vorhandenen Spieler aus der FFB_DB werden angezeigt<br>(gruene Markierung heisst: es wurden schon Daten eingetragen)</li>
        <li>Resultat eintragen</li>
        <li>URL des Match-Ueberblicks der FIFA-Seite reinkopieren</li>
        <li>"Get FIFA Data" (Nationalmannschaften) oder "Get FOE Data" (Unterliga) anklicken</li>
        <li>-> die auf der FIFA-Seite angegebenen Spieler werden rechts daneben angezeigt</li>
        <li>Jetzt die Spieler von der FIFA den Spielern vom FFB zuordnen<br>(dazu einfach auf die Namen klicken)</li>
        <li>-> ganz unten wird eine Uebersicht der bereits getaetigten Aktionen angezeigt</li>
        <li>Zum Abschicken auf "send Updates" klicken und warten bis alles gesendet wurde</li>
        <li>ASSISTS und PENALTYSCHIESSEN muessen weiterhin wie bisher eingetragen werden</li>
        <li>Bei einer roten Karte muss anschliessend haendisch die Minute des Ausschlusses nachgetragen werden</li>
    </ul>
</div>
//-->
    <div id="formerror" style="visibility:hidden;">
    </div>
    <div id="formanswer" style="visibility:hidden;">
    </div>

<?php include(INCLUDE_PATH.'country_list.php');?>
<div id="form">
<div id="select_matchround"></div>
<div id="select_match"></div>
<div id="select_result"></div>
<form name="administration_form" id="administration_form" accept-charset="UTF-8">
    <div id="formline">
	  	<div id="formdescr">* URL:</div>
	  	<div id="forminput">
	  		<input type="text" size="50" id="web_url" name="fifa_url" value="<?= $this->post['fifa_url'];?>">
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">&ensp;</div>
	  	<div id="forminput">
	  		<!--<input type="button" value="Get FIFA Data" onClick="javascript:loadUrlData('fifa')">//-->
	  		<input type="button" value="Get FOE Data" onClick="javascript:loadUrlData('foe')">
	  		<input type="button" value="Get WF Data" onClick="javascript:loadUrlData('wf')">
	  		<input type="button" value="Find matching" onClick="javascript:findMatchingPlayers()">
			<div id="send_updates_button_div"></div>
	  	</div>
	</div>
	<div id="formclear"></div>

</form>
<!--
<form name="upload_form" action="./fifa_playermanagement" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
    <div id="formline">
	  	<div id="formdescr">* FOE-HTML:</div>
	  	<div id="forminput">
	  		<input type="file" id="foe_file" name="foe_file">
	  	</div>
	</div>
	<div id="formclear"></div>
	<div id="formline">
	  	<div id="formdescr">&ensp;</div>
	  	<div id="forminput">
	  		<input type="button" value="Get FOE Data" onClick="javascript:loadFoeData()">
	  	</div>
	</div>
	<div id="formclear"></div>

</form>
//-->
</div>

<br><br>

<div id="playerlist_left">
    <b>HOME</b><br>
    <div id="playerlist_home_db"></div>
    <div id="playerlist_home_fifa"></div>
    <div id="formclear"></div>
</div>

<div id="playerlist_right">
    <b>GUEST</b><br>
    <div id="playerlist_guest_db"></div>
    <div id="playerlist_guest_fifa"></div>
    <div id="formclear"></div>
</div>

<div style="clear:both;"></div>

<div id="playerlist_update"></div>

<!--<div id="send_updates_button_div"></div>-->

</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
