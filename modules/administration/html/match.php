	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?= FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?= FFB_BASE_PATH?>script/admin/match.js"></script>
</head>
<body onload="init();">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?php include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Main">

<div id="administration">
<div id="admintitle">Matches</div>
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
<form name="administration" action="./match" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Matchround:</div>
        <div id="forminput">
            <input type="hidden" name="match_round_post" value="<?= ($this->post['match_round'] ?? '');?>">
            <select name="match_round">
                <option value="">Select Matchround..</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Date:</div>
        <div id="forminput">
            <select name="match_date_day">
                <option value=""></option>
                <?php for($i=1;$i<32;$i++) {?>
                    <option <?php if(($this->post['match_date_day'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
            <select name="match_date_month">
                <option value=""></option>
                <?php $months = array('January','February','March','April','May','June','July','August','September','October',
                                  'November','December');
                  for($i=0;$i<12;$i++) {?>
                    <option <?php if(($this->post['match_date_month'] ?? '') == $i+1) echo 'selected';?> value="<?= $i+1?>">
                        <?= $months[$i]?>
                    </option>
                <?php }?>
            </select>
            <select name="match_date_year">
                <option value=""></option>
                <?php $now = date('Y',time());
                  for($i=$now+3;$i>$now-5;$i--) {?>
                    <option <?php if(($this->post['match_date_year'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Hometeam : <br>
            <input type="hidden" name="match_hometeam_id_post" value="<?= ($this->post['match_hometeam_id'] ?? '');?>">
            <select name="match_hometeam_id">
                <option value="">Select Hometeam..</option>
            </select> :
        </div>
        <div id="forminput"> Guestteam *<br>
            <input type="hidden" name="match_guestteam_id_post" value="<?= ($this->post['match_guestteam_id'] ?? '');?>">
            <select name="match_guestteam_id">
                <option value="">Select Guestteam..</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Goals Hometeam : <br>
            <select name="match_homescore">
                <option value="-1">N/A</option>
                <!--
                <?php for($i=0;$i<20;$i++) {?>
                    <option <?php if(strval(($this->post['match_homescore'] ?? '')) == strval($i)) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
                //-->
            </select> :
        </div>
        <div id="forminput"> Goals Guestteam<br>
            <select name="match_guestscore">
                <option value="-1">N/A</option>
                <!--
                <?php for($i=0;$i<20;$i++) {?>
                    <option <?php if(strval(($this->post['match_guestscore'] ?? '')) == strval($i)) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
                //-->
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Penalties Hometeam : <br>
            <select name="match_homescore_penalty">
                <option value="-1">N/A</option>
                <!--
                <?php for($i=0;$i<20;$i++) {?>
                    <option <?php if(strval(($this->post['match_homescore_penalty'] ?? '')) == strval($i)) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
                //-->
            </select> :
        </div>
        <div id="forminput"> Penalties Guestteam<br>
            <select name="match_guestscore_penalty">
                <option value="-1">N/A</option>
                <!--
                <?php for($i=0;$i<20;$i++) {?>
                    <option <?php if(strval(($this->post['match_guestscore_penalty'] ?? '')) == strval($i)) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
                //-->
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status:</div>
        <div id="forminput">
            <input type="text" class="input" name="match_status" value="<?= ($this->post['match_status'] ?? '');?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?= $this->administration_modus;?>">
        <input type="hidden" name="match_id" value="<?= ($this->post['match_id'] ?? '');?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?php if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="match_administration_update">
            <?php } else {?>
                <input type="submit" class="submit" value="Add" name="match_administration_insert">
            <?php }?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?php if($this->matches) {
      foreach($this->matches as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">
                    <div id="matchdate">
                        <?php if(!$item['match_status'])
                            echo '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'symbols/status_pos.png">';
                        else
                            echo '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'symbols/status_neg.png">';
                        ?>
                        <b><?= $item['match_date'];?></b>
                    </div>
                    <div id="matchteams">
                    <?= $item['match_hometeam_name'].' <img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'flags/'.strtolower($item["match_hometeam_nationality"]).'.gif" width="20px" height="15px"> : <img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'flags/'.strtolower($item["match_guestteam_nationality"]).'.gif" width="20px" height="15px"> '.$item['match_guestteam_name'];?>
                    </div>
                    <div id="matchround">
                        (<?= $item['match_round_name'];?>)
                    </div>
                    <div id="listclear"></div>
                </div>
            </div>
            <div id="listclear"></div>
            <div id="listline">
                <div id="listsymbol">
                    <form method="POST" action="./match">
                        <input type="hidden" name="match_id" value="<?= $item['match_id']?>">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" value="match_administration_change" name="match_administration_change">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" value="match_administration_delete" name="match_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?php }} else {?>
        <div id="listline">No Matches yet available!</div>
    <?php }?>
</div>

</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>