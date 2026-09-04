	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher, Musser">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
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
<div id="admintitle">Matchrounds</div>
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

<div id="form">
<form name="administration" action="./matchround" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Title:</div>
        <div id="forminput">
            <input type="text" class="input" name="matchround_title" value="<?echo $this->post['matchround_title'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Start Date:</div>
        <div id="forminput">
            <?$months = array('January','February','March','April','May','June','July','August','September','October',
                                  'November','December');
            ?>
            <select name="matchround_startdate_day">
                <option value=""></option>
                <?for($i=1;$i<32;$i++) {?>
                    <option <?if($this->post['matchround_startdate_day'] == $i) echo 'selected';?> value="<?echo $i?>">
                        <?echo $i?>
                    </option>
                <?}?>
            </select>
            <select name="matchround_startdate_month">
                <option value=""></option>
                <?for($i=0;$i<12;$i++) {?>
                    <option <?if($this->post['matchround_startdate_month'] == $i+1) echo 'selected';?> value="<?echo $i+1?>">
                        <?echo $months[$i]?>
                    </option>
                <?}?>
            </select>
            <select name="matchround_startdate_year">
                <option value=""></option>
                <?$now = date('Y',time());
                  for($i=$now+3;$i>$now-5;$i--) {?>
                    <option <?if($this->post['matchround_startdate_year'] == $i) echo 'selected';?> value="<?echo $i?>">
                        <?echo $i?>
                    </option>
                <?}?>
            </select>
            <br>
            <select name="matchround_startdate_hour">
                <?for($i=0;$i<24;$i++) {?>
                    <option <?if($this->post['matchround_startdate_hour'] == $i) echo 'selected';?> value="<?echo $i.':00:00'?>">
                        <?echo $i.':00'?>
                    </option>
                <?}?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* End Date:</div>
        <div id="forminput">
            <select name="matchround_enddate_day">
                <option value=""></option>
                <?for($i=1;$i<32;$i++) {?>
                    <option <?if($this->post['matchround_enddate_day'] == $i) echo 'selected';?> value="<?echo $i?>">
                        <?echo $i?>
                    </option>
                <?}?>
            </select>
            <select name="matchround_enddate_month">
                <option value=""></option>
                <?for($i=0;$i<12;$i++) {?>
                    <option <?if($this->post['matchround_enddate_month'] == $i+1) echo 'selected';?> value="<?echo $i+1?>">
                        <?echo $months[$i]?>
                    </option>
                <?}?>
            </select>
            <select name="matchround_enddate_year">
                <option value=""></option>
                <?$now = date('Y',time());
                  for($i=$now+3;$i>$now-5;$i--) {?>
                    <option <?if($this->post['matchround_enddate_year'] == $i) echo 'selected';?> value="<?echo $i?>">
                        <?echo $i?>
                    </option>
                <?}?>
            </select>
            <br>
            <select name="matchround_enddate_hour">
                <?for($i=0;$i<24;$i++) {?>
                    <option <?if($this->post['matchround_enddate_hour'] == $i) echo 'selected';?> value="<?echo $i.':00:00'?>">
                        <?echo $i.':00'?>
                    </option>
                <?}?>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Status:</div>
        <div id="forminput">
            <select name="matchround_status">
                <option <?if($this->post['matchround_status'] == 1) echo 'selected ';?> value="1">active</option>
                <option <?if($this->post['matchround_status'] == 0) echo 'selected ';?>value="0">inactive</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?echo $this->administration_modus;?>">
        <input type="hidden" name="matchround_id" value="<?echo $this->post['matchround_id'];?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="matchround_administration_update">
            <?} else {?>
                <input type="submit" class="submit" value="Add" name="matchround_administration_insert">
            <?}?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?if($this->matchrounds) {
      foreach($this->matchrounds as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">
                    <div id="matchrounddate">
                        <b>from: </b><?echo $item['matchround_startdate'];?><br>
                        <b>till: </b><?echo $item['matchround_enddate'];?>
                    </div>

                    <?echo $item['matchround_title'];?>
                    <?if($item['matchround_status']) {
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
                        <input type="hidden" name="matchround_id" value="<?echo $item['matchround_id']?>">
                        <input type="image" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" value="matchround_administration_change" name="matchround_administration_change">
                        <input type="image" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" value="matchround_administration_delete" name="matchround_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?}} else {?>
        <div id="listline">No Matchrounds yet available!</div>
    <?}?>
</div>

</div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>