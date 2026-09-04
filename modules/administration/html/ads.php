	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="author" content="Gritschacher, Musser" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css" />
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>ads.css" type="text/css" />
    <script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.SCRIPT_PATH?>admin/ads.js"></script>
</head>
<body onload="javascript:loadAllAds();">
<div id="Container" >

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Main">

        	<h1>Werbemodul</h1>

        	<div id="Mainleft">
			<div id="adsoutput"></div>
        	</div>

        	<div id="Mainright">
				<b>Werbeslots bearbeiten</b><br />
				<select class="stdSelect" id="adsslotselect" size="1" onchange="javascript:selectAdsSlot();">
					<option class="stdSelect0" selected="true"  >Auswahl:</option>
					<?
					if($this->adsSlots) {
						$index=0;
						foreach($this->adsSlots AS $slot) {
							echo '<option class="stdSelect' . ($index++ % 2) . '" value="' . $slot['id'] . '">' . $slot['name'] . "</option>\n";
						}
					}
					?>
				</select> <input class="adInputOpt" type="button" value="aktualisieren" onclick="javascript:selectAdsSlot();" />
			
				<hr />
				<b>Neuen Werbeslot anlegen:</b><br />
				<form name="newslot" action="./ads" method="POST">
					<div class="adInputSmall" >Name:</div><input class="adInput" type="text" size="12" name="newslotname" /><br />
					<div class="adInputSmall" >CSS:</div> <input class="adInput" type="text" size="12" name="newslotcss" /><br />
					<input type="submit" value="Slot anlegen" class="adInputOpt" />
					<input type="hidden" name="action" value="newslot" />
				</form>
				
				<hr />
				
				<b>Werbung bearbeiten</b><br />
				<select class="stdSelect" id="adselect" size="1" onchange="javascript:selectAd();">
					<option class="stdSelect0" selected="true"  >Auswahl:</option>
					<?
					if($this->ads) {
						$index=0;
						foreach($this->ads AS $ad) {
							echo '<option class="stdSelect' . ($index++ % 2) . '" value="' . $ad['id'] . '">' . $ad['name'] . "</option>\n";
						}
					}
					?>
				</select>
			
				<hr />
				<b>Neuen Werbeblock anlegen:</b><br />
				<form name="newad" action="./ads" method="POST">
					<div class="adInputSmall" >Name:</div><input class="adInput" type="text" size="12" name="newadname" /><br />
					<input type="submit" value="Webung anlegen" class="adInputOpt"/>
					<input type="hidden" name="action" value="newad" />
				</form>
				
				
				<hr />
				<input type="button" class="adInputOpt1" value="Usermanagement (blockierte Werbebl&ouml;cke)" onclick="javascript:initBlockAds();"/>
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
</div>