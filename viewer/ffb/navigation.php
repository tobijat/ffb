<?php

/**
 * @author Gritschacher, Musser
 * @copyright 10/2008
 * @version 0.3
 *
 * Zeigt die Navigations-Items an
 */
?>
<div style="">
<?
  $break = 0;
  foreach($this->nav_items as $nav) {

    if(!$break && $nav['style'] == 'small') {
        $break = 1;?>
        <div style="clear:both"></div>
		</div>
		<div class="rounddiv_nav_small">
		<div class="roundcorner_light">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="NavbarSmallRound">

    <?}?>
    <?if($nav['style'] == 'small') {?>
        <div id="navitem_small">
            <?if($nav['link'] == '__BACK__') {?>
                <a href="javascript:history.back();" style="text-decoration:none;">
                    <div style="float:left;"><img src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="20px" title="<?echo $nav['name']?>" border="0"></div>
                    <div style="float:left;font-size:8pt;padding-top:3px; padding-left:2px;"><?echo $nav['name']?></div>
                </a>
            <?} else if($nav['link'] == '__FORUM__') {?>
                <a href="javascript:history.back();" style="text-decoration:none;">
                    <img src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="60px" title="<?echo $nav['name']?>" border="0"><br>
                    <span style="font-size:8pt;"><?echo $nav['name']?></span>
                </a>
            <?} else {?>
                <a href="<?echo  FFB_BASE_PATH.$nav['link']?>" style="text-decoration:none;">
                    <div style="float:left;"><img src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="20px" title="<?echo $nav['name']?>" border="0"></div>
                    <div style="float:left;font-size:8pt;padding-top:3px; padding-left:2px;"><?echo $nav['name']?></div>
                </a>
            <?}?>
        </div>
    <?} else {?>
        <div id="navitem_big">
            <?if($nav['link'] == '__BACK__') {?>
                <a href="javascript:history.back();" style="text-decoration:none;">
                    <img src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="60px" title="<?echo $nav['name']?>" border="0"><br>
                    <span style="font-size:8pt;"><?echo $nav['name']?></span>
                </a>
            <?} else if($nav['link'] == '__FORUM__') {?>
                <form action="http://ffb.gemura.com/forum/ucp.php?mode=login" method="POST" target="_blank">
                    <input type="hidden" name="username" id="username" value="<?echo $this->session->user_nickname;?>">
                    <input type="hidden" name="password" id="password" value="<?echo $this->session->user_password;?>">
                    <input name="redirect" value="http://ffb.gemura.com/forum/index.php" type="hidden">
                    <input type="hidden" name="login" value="login" />
                    <input type="hidden" name="sid" value="<? echo session_id(); ?>" />
                    <input type="image" src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" title="<?echo $nav['name']?>" value="Log in"><br>
                    <span style="font-size:8pt;"><?echo $nav['name']?></span>
                </form>
            <?} else {?>
                <a href="<?echo  FFB_BASE_PATH.$nav['link']?>" style="text-decoration:none;">
                    <img src="<?echo  FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="60px" title="<?echo $nav['name']?>" border="0"><br>
                    <span style="font-size:8pt;"><?echo $nav['name']?></span>
                </a>
            <?}?>
        </div>
    <?}?>
<?}?>
<?if($break == 1) {?>
<div style="clear:both"></div>
</div>
<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?}?>

<div style="clear:both"></div>
</div>
