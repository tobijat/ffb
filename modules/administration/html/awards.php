	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="author" content="Gritschacher, Musser" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>awards.css" type="text/css" />
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/awards.js"></script>
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

        	<div id="admintitle">Auszeichnungen</div>

        	<div id="Mainleft">
			<div id="awardoutput"></div>
        	</div>

        	<div id="Mainright">
				<b>Bestehende Auszeichnungen verwalten:</b><br />
				<select class="stdSelect" id="awardselect" size="1" onchange="javascript:selectAwardGroup();">
					<option class="stdSelect0" selected="true"  >Auswahl:</option>
					<?
						if($this->awardGroups) {
							$style = 1;
							foreach($this->awardGroups AS $elem) {
								echo '<option value="' . $elem->getUserAwardId() . '" class="stdSelect' . ($style++ % 2) .'">' . $elem->getUserAwardName()	."</optin>\n";						}
						}
					?>
				</select>
			
				<hr />
				<b>Neue Gruppe f&uuml;r Auszeichnungen anlegen:</b><br />
				<form name="newaward" action="./awards" method="POST">
					Name: <input type="text" size="12" name="newgroupawardname" />
					<input type="submit" value="anlegen"/>
				</form>
				<hr />
				<div class="awardEntry">
					<input type="button" class="awardInputOpt" value="alle Auszeichnungen berechnen" onclick="javascript:calcAllAwards();" />
					<input type="button" class="awardInputOpt" value="alle Auszeichnungen anzeigen (not implemented)" onclick="javascript:showAllAwards();" /><br />
				</div>
				<hr />
				
				<div class="awardEntry">
					<input type="button" class="awardInputOpt" value="neue Auszeichnungen an Facebook senden" onclick="javascript:sendNewAwardsToFb();" /><br />
				</div>
				
				<hr />
				
				<div class="formerror" id="formerror">
<?if(is_array($this->errors)) {?>
        	
            	<b>There are errors:</b><br />
            	<?foreach($this->errors as $error) {
	                echo '* '.$error.'<br>';
    	        }?>
        	
<?}?>

				</div>
				<div class="formanswer" id="formanswer">
<?if($this->administration_answer) {?>
        		<?echo $this->administration_answer;?>
<?}?>
				</div>
        	</div>


	</div>



</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
<pre>
<?
if($this->allusers) {
	foreach($this->allusers as $user) {
		if(strlen($user->getUserNickname()) != strlen(trim( $user->getUserNickname() ) )  ) {
			echo "id:"  . $user->getUserId() .  " nick: '" . $user->getuserNickname() . "' <br>";
		}
	}
	
}

?>
</pre>
</div>