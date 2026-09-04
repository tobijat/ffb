	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH;?>constants.js"></script>
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH;?>admin/awards.js"></script>
</head>
<body>
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">UserScore Settings</div>

<div id="form">
<form name="administration" action="./setUserteamScore.html" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">
            <input type="submit" class="submit" value="Set Userteam Score" name="set_userteamscores_submit">
            <br>(do only click once!)
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
<br>
<div id="form">
<form name="administration" action="./setUserScore.html" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">
            <input type="submit" class="submit" value="Set User Score" name="set_userscores_submit">
            <br>(do only click once!)
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
<!--
<br>
<div id="form">
<form name="administration" action="./setPlayerDynamicPrice.html" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">berechnen f&uuml;r Matchround:</div>
        <div id="forminput">
            <select name="matchround_id">
                <option value="">Select Matchround..</option>
//-->
                <?
/*
                    foreach($this->matchrounds as $item) {
                        if($this->post_matchround_id == $item["matchround_id"]) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$item["matchround_id"].'">'.$item["matchround_title"].'</option>';
                    }
*/
                ?>
<!--
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">
            <input type="submit" class="submit" value="Set Player Price" name="set_playerprice_submit">
            <br>(do only click once!)
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
//-->
<!--
<br />
<div id="form">
	<div id="formline">
		<div id="formdescr">User Awards berechnen incl. Facebook Feeds:</div>
			<div class="forminput"><input type="button" class="button" value="berechnen" onclick="javascript:calcAllAwards();" /></div>
			<div id="awardoutput"></div>
		<div id="formclear"></div>
	</div>
</div>
//-->
<br />
<?if(is_array($this->errors)) {?>
        <div id="formerror">
            <b>There are errors:</b><br>
            <?foreach($this->errors as $error) {
                echo '* '.$error.'<br>';
            }?>
        </div>
<?}?>
<?if($this->administration_answer) {?>
    <div id="formanswer">
        <?echo $this->administration_answer;?>
    </div>
<?}?>
</div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
