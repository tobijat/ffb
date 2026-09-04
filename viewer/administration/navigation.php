<?php

/**
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.1
 *
 * Zeigt die Navigations-Items an
 */
?>
<div style="float:left">
<?
  foreach($this->nav_items as $nav) {?>
    <div style="float:left; margin-left:10px; margin-right:10px; text-align:center;">
        <?if($nav['link'] == '__BACK__') {?>
            <a href="javascript:history.back();" style="text-decoration:none;">
                <img src="<?echo  FFB_BASE_PATH.ADM_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="64px" title="<?echo $nav['name']?>" border="0"><br>
                <span style="font-size:8pt;"><?echo $nav['name']?></span>
            </a>
        <?} else {?>
            <a href="<?echo  FFB_BASE_PATH.$nav['link']?>" style="text-decoration:none;">
                <img src="<?echo  FFB_BASE_PATH.ADM_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="64px" title="<?echo $nav['name']?>" border="0"><br>
                <span style="font-size:8pt;"><?echo $nav['name']?></span>
            </a>
        <?}?>
    </div>
<?}?>
<div style="clear:both;"></div>
</div>
<div id="nav_description" style="float:left; padding-top:40px">
</div>
<div style="clear:both"></div>
