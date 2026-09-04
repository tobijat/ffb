	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/player.js"></script>
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
<div id="admintitle">Players</div>
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

<?include(INCLUDE_PATH.'country_list.php');?>
<div id="form">
<form name="administration_form" id="administration_form" action="./player" method="post">
    <div id="formline">
        <div id="formdescr">* First Name:</div>
        <div id="forminput">
            <input type="text" class="input" name="player_fname" value="<?echo $this->post['player_fname'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Last Name:</div>
        <div id="forminput">
            <input type="text" class="input" name="player_lname" value="<?echo $this->post['player_lname'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Nationality:</div>
        <div id="forminput">
            <select name="player_nationality">
                <option value="">Country...</option>
                    <?foreach($country_array as $shortname => $name) {?>
                        <option <?if($this->post['player_nationality'] == $shortname) echo 'selected';?> value="<?echo $shortname?>">
                            <?echo $name?>
                        </option>
                    <?}?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status:</div>
        <div id="forminput">
            <select name="player_status">
                <option <?if($this->post['player_status'] == 1) echo 'selected ';?> value="1">active</option>
                <option <?if($this->post['player_status'] == 0) echo 'selected ';?>value="0">inactive</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status description:</div>
        <div id="forminput">
            <input type="text" class="input" name="player_status_description" value="<?echo $this->post['player_status_description'];?>">
        </div>
        <div id="formclear"></div>
    </div>

    <?if($this->administration_modus != 'update') {?>
        <div id="formline">
	  		<div id="formdescr">* Team:</div>
	  		<div id="forminput">
	  		    <input type="hidden" name="player_team_post" value="<?echo $this->post['player_team'];?>">
	  		    <select name="player_team" id="insertplayer_team">
                    <option value="">select team</option>
                </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>
	  	<div id="formline">
	  		<div id="formdescr">* Price:</div>
	  		<div id="forminput">
	  		    <select name="playerteam_price">
                    <option value="">select price</option>
                    <?for($i=1;$i<13;$i++) {?>
                        <option <?if($this->post['playerteam_price'] == $i) echo 'selected';?> value="<?echo $i?>">
                            <?echo $i?>
                        </option>
                    <?}?>
                </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>
	  	<div id="formline">
	  		<div id="formdescr">* Position:</div>
	  		<div id="forminput">
	  		    <select name="playerteam_position">
                    <option value="">select position</option>
                    <option <?if($this->post['playerteam_position'] == 'g') echo 'selected';?> value="g">
                        Goalie
                    </option>
                    <option <?if($this->post['playerteam_position'] == 'd') echo 'selected';?> value="d">
                        Defence
                    </option>
                    <option <?if($this->post['playerteam_position'] == 'm') echo 'selected';?> value="m">
                        Midfield
                    </option>
                    <option <?if($this->post['playerteam_position'] == 's') echo 'selected';?> value="s">
                        Striker
                    </option>
                </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>
	  	<div id="formline">
	  		<div id="formdescr">Picture:</div>
	  		<div id="forminput">
	  		    <input type="checkbox" name="playerteam_picture" value="playerteam_picture_yes" <?if($this->post['playerteam_picture']) echo 'checked';?>>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>
	  	<div id="formline">
            <div id="formdescr">PT-Status:</div>
            <div id="forminput">
                <select name="playerteam_status">
                    <option <?if($this->post['playerteam_status'] == 1) echo 'selected ';?> value="1">active</option>
                    <option <?if($this->post['playerteam_status'] == 0) echo 'selected ';?>value="0">inactive</option>
                </select>
            </div>
        </div>
        <div id="formclear"></div>
    <?}?>

    <div id="formline">&ensp;</div>

    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?echo $this->administration_modus;?>">
        <input type="hidden" name="player_id" value="<?echo $this->post['player_id'];?>">
        <div id="formdescr">
            <?if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="player_administration_update">
            <?} else {?>
                <input type="submit" class="submit" value="Add" name="player_administration_insert">
            <?}?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
<hr>
<div id="form">
<form name="administration" action="./player" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
    <!--
    <div id="formline">
        <div id="formdescr">Player-File:</div>
        <div id="forminput">
            <input type="file" class="input" name="player_file_p">
            <input type="submit" class="submit" value="Add Players" name="player_administration_file_p">
        </div>
        <div id="formclear"></div>
    </div>
    //-->
    <div id="formline">
        <div id="formdescr">PlayerToTeam-File:</div>
        <div id="forminput">
            <input type="file" class="input" name="player_file_ptt">
            <input type="submit" class="submit" value="Add Players" name="player_administration_file_ptt">
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
</div>

<br>

<div id="form">
	<form name="playerselect" id="playerselect" onsubmit="return false;" accept-charset="UTF-8">
	    <div id="formline">
	  		<div id="formdescr"><b>Search:</b></div>
	  		<div id="forminput">
	  		    &ensp;
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>
	  	<div id="formline">
	  		<div id="formdescr">Last Name:</div>
	  		<div id="forminput">
	  		    <input name="player_search" id="searchplayer_search" type="text" size="20" maxlength="200">
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>

	  	<div id="formline">
	  		<div id="formdescr">Nationality:</div>
	  		<div id="forminput">
	  		    <select name="player_nationality" id="searchplayer_nationality">
	  		        <option value="" >all nationalities</option>
                    <?foreach($country_array as $shortname => $name) {?>
                        <option value="<?echo $shortname?>">
                            <?echo $name?>
                        </option>
                    <?}?>
      		    </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>

	  	<div id="formline">
	  		<div id="formdescr">Team:</div>
	  		<div id="forminput">
	  		    <select name="player_team" id="searchplayer_team">
                    <option value="">all teams</option>
                </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>

	  	<div id="formline">
	  		<div id="formdescr">Sort:</div>
	  		<div id="forminput">
	  		    <select name="player_sort" id="searchplayer_sort">
	  		        <option value="id_desc">ID descending</option>
	  		        <option value="id_asc">ID ascending</option>
                    <option value="name">Name</option>
                    <option value="nat">Nationality</option>
      		    </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>

	  	<div id="formline">
	  		<div id="formdescr">Limit:</div>
	  		<div id="forminput">
	  		    <select name="player_limit" id="searchplayer_limit">
	  		        <!--<option value="20">20</option>//-->
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="100">200</option>
                    <option value="100">500</option>
                    <option value="1000">1000</option>
                    <option value="">no limit</option>
      		    </select>
	  	    </div>
	  	</div>
	  	<div id="formclear"></div>

		<div id="formline">
	  		<div id="formdescr">&nbsp;</div>
	  		<div id="forminput">
	  		    <input type="button" onclick="javascript:searchPlayer();" value="search">
		    </div>
		</div>
		<div id="formclear"></div>
	</form>
</div>

<div id="list">
</div>

</div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
