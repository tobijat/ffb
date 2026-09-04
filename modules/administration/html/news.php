	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher Tobias">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/admin/game.js"></script>
    <script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/prototype.js"></script>
    <script type="text/javascript" src="<?echo FFB_BASE_PATH?>script/constants.js"></script>
</head>
<body onload="init();">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>
    <div id="Main">
<div id="administration">
<div id="admintitle">News</div>
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
<form name="administration" action="./news" method="post" accept-charset="UTF-8">
    <div id="formline">
        <div id="formdescr">* Game:</div>
        <div id="forminput">
            <input type="hidden" name="game_id_post" value="<?echo $this->post['news_game_id'];?>">
            <select name="news_game_id">
                <option value="0">Global</option>
            </select>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Title:</div>
        <div id="forminput">
            <input type="text" name="news_title" value="<?echo $this->post['news_title'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">* Text:</div>
        <div id="forminput">
            <textarea cols="25" rows="6" name="news_text"><?echo $this->post['news_text'];?></textarea>
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Symbol:</div>
        <div id="forminput">
            <input type="text" name="news_symbol" value="<?echo $this->post['news_symbol'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">
        <div id="formdescr">Priority:</div>
        <div id="forminput">
            <input type="text" name="news_priority" value="<?echo $this->post['news_priority'];?>">
        </div>
        <div id="formclear"></div>
    </div>
    <div id="formline">&ensp;</div>
    <div id="formline">
        <input type="hidden" name="administration_modus" value="<?echo $this->administration_modus;?>">
        <input type="hidden" name="news_id" value="<?echo $this->post['news_id'];?>">
        <div id="formdescr">&ensp;</div>
        <div id="forminput">
            <?if($this->administration_modus == 'update') {?>
                <input type="submit" class="submit" value="Update" name="news_administration_update">
            <?} else {?>
                <input type="submit" class="submit" value="Add" name="news_administration_insert">
            <?}?>
        </div>
        <div id="formclear"></div>
    </div>
</form>
</div>

<br>

<div id="list">
    <?if($this->news) {
      foreach($this->news as $item) {?>
        <div id="listitem">
            <div id="listline">
                <div id="listdescr">

                    <?echo '<img src="'.FFB_BASE_PATH.FFB_IMAGE_PATH.'symbols/'.$item['news_symbol'].'" height="18px"> '?>
                    <b><?echo $item['news_title'].' (ID: '.$item['news_id'].')';?></b> <?echo $item['news_date'];?><br>
                    <div style="text-align:justify;"><?echo $item['news_text'];?></div>
                </div>
            </div>
            <div id="listclear"></div>
            <div id="listline">
                <div id="listsymbol">
                    <form method="POST" action="./news">
                        <input type="hidden" name="news_id" value="<?echo $item['news_id']?>">
                        <input type="image" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/edit.png" title="edit the entry" name="news_administration_change" value="news_administration_change">
                        <input type="image" src="<?echo FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/delete.png" title="delete the entry" name="news_administration_delete" value="news_administration_delete">
                    </form>
                </div>
            </div>
            <div id="listclear"></div>
        </div>
    <?}} else {?>
        <div id="listline">No News yet available!</div>
    <?}?>
</div>

</div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
