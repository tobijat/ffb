	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
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
<div id="admintitle">Teams</div>
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

<?php include(INCLUDE_PATH.'country_list.php');?>
<div id="form">
<form name="administration" action="./team" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Team Name:</div>
        <div id="forminput">
            <input type="text" class="input" name="team_name" value="<?= $this->post['team_name'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Nationality:</div>
        <div id="forminput">
            <select name="team_nationality">
                <option value="">Country...</option>
                <?php foreach($country_array as $shortname => $name) {?>
                    <option <?php if($this->post['team_nationality'] == $shortname) echo 'selected';?> value="<?= $shortname?>">
                        <?= $name?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Price:</div>
        <div id="forminput">
            <select name="team_price">
                <?php for($i=1;$i<16;$i++) {?>
                    <option <?php if($this->post['team_price'] == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status:</div>
        <div id="forminput">
            <select name="team_status">
                <option <?php if($this->post['team_status'] == 1) echo 'selected ';?> value="1">active</option>
                <option <?php if($this->post['team_status'] == 0) echo 'selected ';?>value="0">inactive</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">TM ID:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_fid_tm" value="<?= $this->post['teamfid_fid_tm'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">TM Name:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_name_tm" value="<?= $this->post['teamfid_name_tm'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">WF Name:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_name_wf" value="<?= $this->post['teamfid_name_wf'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <!--
    <div id="formline">
        <div id="formdescr">TM URL:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_url_tm" value="<?= $this->post['teamfid_url_tm'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">WF URL:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_url_wf" value="<?= $this->post['teamfid_url_wf'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    //-->
    <div id="formline">
        <div id="formdescr">FOE URL:</div>
        <div id="forminput">
            <input type="text" class="input" name="teamfid_url_foe" value="<?= $this->post['teamfid_url_foe'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?= $this->administration_modus;?>">
        <input type="hidden" name="team_id" value="<?= $this->post['team_id'];?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?php if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="team_administration_update">
            <?php } else {?>
                <input type="submit" class="submit" value="Add" name="team_administration_insert">
            <?php }?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?php if($this->teams) {
      foreach($this->teams as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">
                    <?php if($item['team_status'])
                        echo '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'symbols/status_pos.png">';
                      else
                        echo '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'symbols/status_neg.png">';
                    ?>

                    <?= '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'flags/'.strtolower($item['team_nationality']).'.gif" width="20px" height="15px"> '?>
                    <b><?= '('.$item['team_id'].') '.$item['team_name'].' (Price: '.$item['team_price'].')';?></b>
                </div>
            </div>
            <div id="listclear"></div>
            <div id="listline">
                <div id="listsymbol">
                    <form method="POST" action="./team">
                        <input type="hidden" name="team_id" value="<?= $item['team_id']?>">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" name="team_administration_change" value="team_administration_change">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" name="team_administration_delete" value="team_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?php }} else {?>
        <div id="listline">No Teams yet available!</div>
    <?php }?>
</div>

</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>