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
<div id="admintitle">Matchrounds</div>
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
<form name="administration" action="./matchround" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Title:</div>
        <div id="forminput">
            <input type="text" class="input" name="matchround_title" value="<?= ($this->post['matchround_title'] ?? '');?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Start Date:</div>
        <div id="forminput">
            <?php $months = array('January','February','March','April','May','June','July','August','September','October',
                                  'November','December');
            ?>
            <select name="matchround_startdate_day">
                <option value=""></option>
                <?php for($i=1;$i<32;$i++) {?>
                    <option <?php if(($this->post['matchround_startdate_day'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
            <select name="matchround_startdate_month">
                <option value=""></option>
                <?php for($i=0;$i<12;$i++) {?>
                    <option <?php if(($this->post['matchround_startdate_month'] ?? '') == $i+1) echo 'selected';?> value="<?= $i+1?>">
                        <?= $months[$i]?>
                    </option>
                <?php }?>
            </select>
            <select name="matchround_startdate_year">
                <option value=""></option>
                <?php $now = date('Y',time());
                  for($i=$now+3;$i>$now-5;$i--) {?>
                    <option <?php if(($this->post['matchround_startdate_year'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
            <br>
            <select name="matchround_startdate_hour">
                <?php for($i=0;$i<24;$i++) {?>
                    <option <?php if(($this->post['matchround_startdate_hour'] ?? '') == $i) echo 'selected';?> value="<?= $i.':00:00'?>">
                        <?= $i.':00'?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* End Date:</div>
        <div id="forminput">
            <select name="matchround_enddate_day">
                <option value=""></option>
                <?php for($i=1;$i<32;$i++) {?>
                    <option <?php if(($this->post['matchround_enddate_day'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
            <select name="matchround_enddate_month">
                <option value=""></option>
                <?php for($i=0;$i<12;$i++) {?>
                    <option <?php if(($this->post['matchround_enddate_month'] ?? '') == $i+1) echo 'selected';?> value="<?= $i+1?>">
                        <?= $months[$i]?>
                    </option>
                <?php }?>
            </select>
            <select name="matchround_enddate_year">
                <option value=""></option>
                <?php $now = date('Y',time());
                  for($i=$now+3;$i>$now-5;$i--) {?>
                    <option <?php if(($this->post['matchround_enddate_year'] ?? '') == $i) echo 'selected';?> value="<?= $i?>">
                        <?= $i?>
                    </option>
                <?php }?>
            </select>
            <br>
            <select name="matchround_enddate_hour">
                <?php for($i=0;$i<24;$i++) {?>
                    <option <?php if(($this->post['matchround_enddate_hour'] ?? '') == $i) echo 'selected';?> value="<?= $i.':00:00'?>">
                        <?= $i.':00'?>
                    </option>
                <?php }?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status:</div>
        <div id="forminput">
            <select name="matchround_status">
                <option <?php if(($this->post['matchround_status'] ?? '') == 1) echo 'selected ';?> value="1">active</option>
                <option <?php if(($this->post['matchround_status'] ?? '') == 0) echo 'selected ';?>value="0">inactive</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?= $this->administration_modus;?>">
        <input type="hidden" name="matchround_id" value="<?= ($this->post['matchround_id'] ?? '');?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?php if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="matchround_administration_update">
            <?php } else {?>
                <input type="submit" class="submit" value="Add" name="matchround_administration_insert">
            <?php }?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?php if($this->matchrounds) {
      foreach($this->matchrounds as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">
                    <div id="matchrounddate">
                        <b>from: </b><?= $item['matchround_startdate'];?><br>
                        <b>till: </b><?= $item['matchround_enddate'];?>
                    </div>

                    <?= $item['matchround_title'];?>
                    <?php if($item['matchround_status']) {
                        echo ' - active';
                    } else {
                        echo ' - inactive';
                    }?>
                </div>
            </div>
            <div id="listclear"></div>
            <div id="listline">
                <div id="listsymbol">
                    <form method="POST" action="./matchround">
                        <input type="hidden" name="matchround_id" value="<?= $item['matchround_id']?>">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" value="matchround_administration_change" name="matchround_administration_change">
                        <input type="image" src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" value="matchround_administration_delete" name="matchround_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?php }} else {?>
        <div id="listline">No Matchrounds yet available!</div>
    <?php }?>
</div>

</div>
</div>
<div id="Footer">
    <?php include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>