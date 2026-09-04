<?php

/**
 * @author Gritschacher, Musser
 * @copyright 2008
 */
include(INCLUDE_PATH.'country_list.php');
?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
	<link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
	<link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>playertoteam.css" type="text/css">
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH?>script/admin/playertoteam.js"></script>
	<meta name="author" content="Musser">
	<meta name="author" content="Gritschacher">
</head>
<body onload="init();">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?php include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="administration_playertoteam">

        <div id="admintitle">Player to Team</div>

        <div id="Mainleft">
            <div id="Teams">
                <b>select a team</b>
        	    <form name="teamselect" id="teamselect" class="teamselect" onsubmit="javascript:return false;" accept-charset="UTF-8">
			       loading teams...
			    </form>
            </div>

            <div id="PlayerToTeams" class="PlayerToTeams">
            </div>
        </div>

        <div id="Mainright">
            <div id="players" style="display:none;">
	  	        <form name="playerselect" id="playerselect" onsubmit="return false;" accept-charset="UTF-8">
		            <b>search players</b>
	  	            <div id="playername">
	  	                <div id="formline">
	  		               <div id="formdescr">Last Name:</div>
	  		               <div id="forminput">
	  		                   <input name="player_search" type="text" size="20" maxlength="200">
	  	                   </div>
	  	                </div>
	  	                <div id="formclear"></div>
	  	            </div>
	  	            <div id="playernationality">
	  	                <div id="formline">
	  		               <div id="formdescr">Player Nationality:</div>
	  		               <div id="forminput">
	  		                   <select name="player_nationality">
	  		                       <option value="" >all nationality's</option>
                                    <?php foreach($country_array as $shortname => $name) {?>
                                        <option value="<?= $shortname?>">
                                            <?= $name?>
                                        </option>
                                    <?php }?>
      		                   </select>
	  	                   </div>
	  	                   <div id="formclear"></div>
	  	                </div>
	  	            </div>

		            <div id="search">
		                <div id="formline">
	  		               <div id="formdescr">&nbsp;</div>
	  		               <div id="forminput">
	  		                   <input type="button" onclick="javascript:searchPlayer();" value="search">
		                   </div>
		                   <div id="formclear"></div>
		                </div>
		            </div>
                    <div id="playerresult">
		            </div>
	  	        </form>
            </div>
        </div>
	    <div id="Confirmlist">
            <b>Changes to confirm:</b>
            <div id="Confirm">
            </div>
        </div>

    </div>

    <div id="Footer">
        <?php include(ADM_VIEWER_PATH.'footer.php')?>
    </div>

</div>