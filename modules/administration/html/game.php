	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher Tobias, Musser Gerald">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
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
<div id="admintitle">Games</div>
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

<div id="form">
<form name="administration" action="./game" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Title:</div>
        <div id="forminput">
            <input type="text" class="input" size="30" name="game_title" value="<?= $this->post['game_title'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Status:</div>
        <div id="forminput">
            <select name="game_status">
                <option <?php if($this->post['game_status'] == 1) echo 'selected ';?> value="1">active</option>
                <option <?php if($this->post['game_status'] == '0') echo 'selected ';?>value="0">inactive</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Visible:</div>
        <div id="forminput">
            <select name="game_visible">
                <option <?php if($this->post['game_visible'] == 1) echo 'selected ';?> value="1">yes</option>
                <option <?php if($this->post['game_visible'] == '0') echo 'selected ';?>value="0">no</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Archive:</div>
        <div id="forminput">
            <select name="game_archive">
                <option <?php if($this->post['game_archive'] == 1) echo 'selected ';?> value="1">yes</option>
                <option <?php if($this->post['game_archive'] == '0') echo 'selected ';?>value="0">no</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Countdown:</div>
        <div id="forminput">
            <select name="game_countdown">
                <option <?php if($this->post['game_countdown'] == 1) echo 'selected ';?> value="1">yes</option>
                <option <?php if($this->post['game_countdown'] == '0') echo 'selected ';?>value="0">no</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Rankmode:</div>
        <div id="forminput">
            <select name="options_game_rankmode">
                <option <?php if($this->post['options_game_rankmode'] == 'wc') echo 'selected ';?> value="wc">WC</option>
                <option <?php if($this->post['options_game_rankmode'] == 'points') echo 'selected ';?> value="points">Points</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Pricemode:</div>
        <div id="forminput">
            <select name="options_game_pricemode">
                <option <?php if($this->post['options_game_pricemode'] == 'dynamic') echo 'selected ';?> value="dynamic">dynamic</option>
                <option <?php if($this->post['options_game_pricemode'] == 'static') echo 'selected ';?> value="static">static</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Pointsmode:</div>
        <div id="forminput">
            <select name="options_game_pointsmode">
                <option <?php if($this->post['options_game_pointsmode'] == 'new') echo 'selected ';?> value="new">new</option>
                <option <?php if($this->post['options_game_pointsmode'] == 'old') echo 'selected ';?> value="old">old</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Minutes 2/3 (D:60):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_minutes" value="<?= $this->post['options_score_minutes'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Minutes 1/3 (D:30):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_minutes_treshold" value="<?= $this->post['options_score_minutes_treshold'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score at least 2/3 (D:3):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_minutes_gt" value="<?= $this->post['options_score_minutes_gt'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score at least 1/3 (D:2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_minutes_lt" value="<?= $this->post['options_score_minutes_lt'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score less than 1/3 (D:1):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_minutes_lt30" value="<?= $this->post['options_score_minutes_lt30'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Goals (D:6,5,4,4):</div>
        <div id="forminput">
            &ensp;G=<input type="text" class="input" size="2" name="options_score_goals_g" value="<?= $this->post['options_score_goals_g'];?>">
            D=<input type="text" class="input" size="2" name="options_score_goals_d" value="<?= $this->post['options_score_goals_d'];?>">
            M=<input type="text" class="input" size="2" name="options_score_goals_m" value="<?= $this->post['options_score_goals_m'];?>">
            S=<input type="text" class="input" size="2" name="options_score_goals_s" value="<?= $this->post['options_score_goals_s'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score OwnGoals (D:-2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_owngoals" value="<?= $this->post['options_score_owngoals'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Assists (D:3):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_goals_g" value="<?= $this->post['options_score_assists'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score No OppGoals (D:4,3,1):</div>
        <div id="forminput">
            &ensp;G=<input type="text" class="input" size="2" name="options_score_no_oppgoals_g" value="<?= $this->post['options_score_no_oppgoals_g'];?>">
            D=<input type="text" class="input" size="2" name="options_score_no_oppgoals_d" value="<?= $this->post['options_score_no_oppgoals_d'];?>">
            M=<input type="text" class="input" size="2" name="options_score_no_oppgoals_m" value="<?= $this->post['options_score_no_oppgoals_m'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score 2 OppGoals (D:-1,-1):</div>
        <div id="forminput">
            &ensp;G=<input type="text" class="input" size="2" name="options_score_oppgoals_g" value="<?= $this->post['options_score_oppgoals_g'];?>">
            D=<input type="text" class="input" size="2" name="options_score_oppgoals_d" value="<?= $this->post['options_score_oppgoals_d'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Cards (D:-2,-4,-5):</div>
        <div id="forminput">
            &ensp;Y=<input type="text" class="input" size="2" name="options_score_card_y" value="<?= $this->post['options_score_card_y'];?>">
            YR=<input type="text" class="input" size="2" name="options_score_card_yr" value="<?= $this->post['options_score_card_yr'];?>">
            R=<input type="text" class="input" size="2" name="options_score_card_r" value="<?= $this->post['options_score_card_r'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Penalty saved (D:2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_penalty_saved" value="<?= $this->post['options_score_penalty_saved'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Penalty lost (D:-2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_penalty_lost" value="<?= $this->post['options_score_penalty_lost'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Penaltyshootout save (D:2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_penaltyshootout_save" value="<?= $this->post['options_score_penaltyshootout_save'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Penaltyshootout lost (D:-2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_penaltyshootout_lost" value="<?= $this->post['options_score_penaltyshootout_lost'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Score Penaltyshootout hit (D:2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_score_penaltyshootout_hit" value="<?= $this->post['options_score_penaltyshootout_hit'];?>">
        </div>
        <div id="formclear"></div>
    </div>
	<div id="formline">
        <div id="formdescr">* Lineup MAX Players (D:11):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_lineup_max_players" value="<?= $this->post['options_lineup_max_players'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Lineup MAX Credits (D:100):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_lineup_max_credits" value="<?= $this->post['options_lineup_max_credits'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Lineup MAX Players per Team (D:2):</div>
        <div id="forminput">
            <input type="text" class="input" size="2" name="options_lineup_max_players_team" value="<?= $this->post['options_lineup_max_players_team'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Lineup MAX Positions (D:1,5,5,3):</div>
        <div id="forminput">
            &ensp;G=<input type="text" class="input" size="2" name="options_lineup_max_g" value="<?= $this->post['options_lineup_max_g'];?>">
            D=<input type="text" class="input" size="2" name="options_lineup_max_d" value="<?= $this->post['options_lineup_max_d'];?>">
            M=<input type="text" class="input" size="2" name="options_lineup_max_m" value="<?= $this->post['options_lineup_max_m'];?>">
            S=<input type="text" class="input" size="2" name="options_lineup_max_s" value="<?= $this->post['options_lineup_max_s'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Lineup MIN Positions (D:1,3,3,1):</div>
        <div id="forminput">
            &ensp;G=<input type="text" class="input" size="2" name="options_lineup_min_g" value="<?= $this->post['options_lineup_min_g'];?>">
            D=<input type="text" class="input" size="2" name="options_lineup_min_d" value="<?= $this->post['options_lineup_min_d'];?>">
            M=<input type="text" class="input" size="2" name="options_lineup_min_m" value="<?= $this->post['options_lineup_min_m'];?>">
            S=<input type="text" class="input" size="2" name="options_lineup_min_s" value="<?= $this->post['options_lineup_min_s'];?>">
        </div>
        <div id="formclear"></div>
    </div>

    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?= $this->administration_modus;?>">
        <input type="hidden" name="game_id" value="<?= $this->post['game_id'];?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?php if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="game_administration_update">
            <?php } else {?>
                <input type="submit" class="submit" value="Add" name="game_administration_insert">
            <?php }?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?php if($this->games) {
      foreach($this->games as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">
                    <div id="matchrounddate">
                        <?= '('.$item['game_id'].') <b>'.$item['game_title'].'</b>';?>
                    </div>
                    <?php if($item['game_status']) {
                        echo 'ACTIVE';
                    } else {
                        echo 'INACTIVE';
                    }?>
                    <?php if($item['game_visible']) {
                    	echo '/VISIBLE';
                    } else {
                    	echo '/INVISIBLE';
                    }?>
                    <?php if($item['game_archive']) {
                    	echo '/OVER';
                    } else {
                    	echo '/RUNNING';
                    }?>
                    <?php if($item['game_countdown']) {
                    	echo '/COUNTDOWN';
                    } else {
                    	echo '/NO COUNTDOWN';
                    }?>
                </div>
            </div>
            <div id="listclear"></div>
            <div id="listline">
                <div id="listsymbol">
                    <form method="POST" action="./game">
                        <input type="hidden" name=game_id" value="<?= $item['game_id']?>">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" value="game_administration_change" name="game_administration_change">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" value="game_administration_delete" name="game_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?php }} else {?>
        <div id="listline">No Games yet available!</div>
    <?php }?>
</div>

</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>