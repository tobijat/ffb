	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH;?>constants.js"></script>
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH;?>admin/awards.js"></script>
</head>
<body>
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?php include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">PlayerPrice Settings</div>

<div id="form">
<form name="administration" action="./calculatePlayerPricesForMatchround.html" method="post" accept-charset="UTF-8">
	<div id="formline" style="text-align:center;">
		<b>Dynamic PlayerPrices v2014 (from WM 2014)</b>
	</div>
    <div id="formline">
        <div id="formdescr">calculate for:</div>
        <div id="forminput">
            <select name="matchround_id">
                <option value="">Select Matchround..</option>
                <?php 
                    foreach($this->matchrounds as $item) {
                        if($this->post_matchround_id == $item["matchround_id"]) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$item["matchround_id"].'">'.$item["matchround_title"].'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
	<div id="formline">
        <div id="formdescr">Price Margin:</div>
        <div id="forminput">
            <select name="price_margin">
                <option value="">price margin..</option>
                <?php 
                    for($i=0.5; $i<=3; $i+=0.5) {
                        if($this->post_price_margin == $i) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$i.'">'.$i.'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <input type="submit" class="submit" value="Set Player Prices" name="set_playerprice_submit">
            <br>(do only click once!)
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
<br />

<div id="form" style="background-color:#FF8000">
<form name="administration" action="./calculateELOTeamPricesForGame.html" method="post" accept-charset="UTF-8">
	<div id="formline" style="text-align:center;">
		<b>ELO BasePrices for Teams participating in selected Game</b>
	</div>
	<div id="formline">
        <div id="formdescr">Max Price:</div>
        <div id="forminput">
            <select name="max_price">
                <option value="">max price..</option>
                <?php 
                    for($i=1; $i<20; $i++) {
                        if($this->post_max_price == $i) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$i.'">'.$i.'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
	<div id="formline">
        <div id="formdescr">Min Price:</div>
        <div id="forminput">
            <select name="min_price">
                <option value="">min price..</option>
                <?php 
                    for($i=1; $i<20; $i++) {
                        if($this->post_min_price == $i) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$i.'">'.$i.'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <input type="submit" class="submit" value="Set Team prices" name="set_teamprice_for_matchround_submit">
            <br>
			<b>(do only click once!)<br>(do not update during tournament!)</b>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
<br />

<div id="form" style="background-color:#FF8000">
<form name="administration" action="./calculateELOTeamPricesForMatchround.html" method="post" accept-charset="UTF-8">
	<div id="formline" style="text-align:center;">
		<b>ELO BasePrices for Teams participating in Matchround</b>
	</div>
    <div id="formline">
        <div id="formdescr">calculate for Teams participating in:</div>
        <div id="forminput">
            <select name="matchround_id">
                <option value="">Select Matchround..</option>
                <?php 
                    foreach($this->matchrounds as $item) {
                        if($this->post_matchround_id == $item["matchround_id"]) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$item["matchround_id"].'">'.$item["matchround_title"].'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
	<div id="formline">
        <div id="formdescr">Max Price:</div>
        <div id="forminput">
            <select name="max_price">
                <option value="">max price..</option>
                <?php 
                    for($i=1; $i<20; $i++) {
                        if($this->post_max_price == $i) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$i.'">'.$i.'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
	<div id="formline">
        <div id="formdescr">Min Price:</div>
        <div id="forminput">
            <select name="min_price">
                <option value="">min price..</option>
                <?php 
                    for($i=1; $i<20; $i++) {
                        if($this->post_min_price == $i) {
                            $selected = 'selected ';
                        } else {
                            $selected = '';
                        }
                        echo '<option '.$selected.'value="'.$i.'">'.$i.'</option>';
                    }
                ?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <input type="submit" class="submit" value="Set Team prices" name="set_teamprice_for_matchround_submit">
            <br>
			<b>(do only click once!)<br>(do not update during tournament!)</b>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>
<br />

<?php if(is_array($this->errors)) {?>
        <div id="formerror">
            <b>There are errors:</b><br>
            <?php foreach($this->errors as $error) {
                echo '* '.$error.'<br>';
            }?>
        </div>
<?php }?>
<?php if($this->administration_answer) {?>
    <div id="formanswer">
        <?= $this->administration_answer;?>
    </div>
<?php }?>
</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
