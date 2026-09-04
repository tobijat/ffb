<?php

/**
 * @author Gritschacher Tobias
 * @copyright 08/2008
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="Tobias Gritschacher">
	<link rel="stylesheet" href="<?echo PIC_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
	<link rel="stylesheet" href="<?echo PIC_BASE_PATH.ADM_INCLUDE_PATH?>pic_thumbs.css" type="text/css">
    <link rel="stylesheet" href="<?echo PIC_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?echo PIC_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?echo PIC_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo PIC_BASE_PATH?><?echo ADM_SCRIPT_PATH?>pic_addalbum.js"></script>
</head>

<!--<body onload="javascript:setSession();">//-->
<body onload="javascript:init();">

<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Mainall">
        <div id="maininfo">
        </div>
        <div id="administration">
            <div id="admintitle">Create an Album</div>
            <div id="createalbum_answer">
            </div>
            <div id="form">
                <form name="create_album_form" id="create_album_form" method="post" accept-charset="UTF-8" onsubmit="return false;">
                    <div id="formline">
                        <div id="formdescr">* Album Title:</div>
                        <div id="forminput">
                            <input type="text" class="input" name="album_title" value="<?echo $this->post['album_title'];?>">
                        </div>
                        <div id="formclear"></div>
                    </div>
                    <div id="formline">
                        <div id="formdescr">* Album Date:</div>
                        <div id="forminput">
                            <select name="album_date_day">
                                <option value=""></option>
                                <?for($i=1;$i<32;$i++) {?>
                                    <option <?if($this->post['album_date_day'] == $i) echo 'selected';?> value="<?echo $i?>">
                                        <?echo $i?>
                                    </option>
                                <?}?>
                            </select>
                            <select name="album_date_month">
                                <option value=""></option>
                                <?$months = array('January','February','March','April','May','June','July','August','September','October',
                                                  'November','December');
                                  for($i=0;$i<12;$i++) {?>
                                    <option <?if($this->post['album_date_month'] == $i+1) echo 'selected';?> value="<?echo $i+1?>">
                                        <?echo $months[$i]?>
                                    </option>
                                <?}?>
                            </select>
                            <select name="album_date_year">
                                <option value=""></option>
                                <?$now = date('Y',time());
                                  for($i=$now;$i>1989;$i--) {?>
                                    <option <?if($this->post['album_date_year'] == $i) echo 'selected';?> value="<?echo $i?>">
                                        <?echo $i?>
                                    </option>
                                <?}?>
                            </select>
                        </div>
                        <div id="formclear"></div>
                    </div>
                    <div id="formline">
                        <div id="formdescr">* Category:</div>
                        <div id="forminput">
                            <input type="hidden" name="album_category_post" value="<?echo $this->post['album_category'];?>">
                            <select name="album_category">
                                <option value="">Select Category..</option>
                            </select>
                        </div>
                        <div id="formclear"></div>
                    </div>
                    <div id="formline">
                        <div id="formdescr">show Imagedates:</div>
                        <div id="forminput">
                            <input type="checkbox" class="input" name="album_dateflag" checked>
                        </div>
                        <div id="formclear"></div>
                    </div>
                    <div id="formline">
                        <div id="formdescr">&ensp;</div>
                        <div id="forminput">
                            <input type="button" value="Create" name="admin_create_album_submit" onclick="javascript:validateAlbumData();">
                        </div>
                        <div id="formclear"></div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <div style="clear:both;"></div>
        <div id="mainthumbs">
        </div>
        <br>
        <a href="javascript:uncheckAll();">uncheck all</a>
        <br>
        <a href="javascript:checkAll();">check all</a>
        <br>
        <a href="javascript:deleteAllImages();">delete all</a>
    </div>

    <div id="Footer">
        <?include(PIC_VIEWER_PATH.'footer_pictory.php')?>
    </div>

</div>


