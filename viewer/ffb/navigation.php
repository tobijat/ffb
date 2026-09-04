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
<?php 
  $break = 0;
  foreach($this->nav_items as $nav) {

    if(!$break && ($nav['style'] ?? '') == 'small') {
        $break = 1;?>
        <div style="clear:both"></div>
		</div>
		<div class="rounddiv_nav_small">
		<div class="roundcorner_light">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div id="NavbarSmallRound">

    <?php }?>
    <?php if(($nav['style'] ?? '') == 'small') {?>
        <div id="navitem_small">
            <?php if($nav['link'] == '__BACK__') {?>
                <a href="javascript:history.back();" style="text-decoration:none;">
                    <div style="float:left;"><img src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="20px" title="<?= $nav['name']?>" border="0"></div>
                    <div style="float:left;font-size:8pt;padding-top:3px; padding-left:2px;"><?= $nav['name']?></div>
                </a>
            <?php } else {?>
                <a href="<?= FFB_BASE_PATH.$nav['link']?>" style="text-decoration:none;">
                    <div style="float:left;"><img src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="20px" title="<?= $nav['name']?>" border="0"></div>
                    <div style="float:left;font-size:8pt;padding-top:3px; padding-left:2px;"><?= $nav['name']?></div>
                </a>
            <?php }?>
        </div>
    <?php } else {?>
        <div id="navitem_big">
            <?php if($nav['link'] == '__BACK__') {?>
                <a href="javascript:history.back();" style="text-decoration:none;">
                    <img src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="60px" title="<?= $nav['name']?>" border="0"><br>
                    <span style="font-size:8pt;"><?= $nav['name']?></span>
                </a>
            <?php } else {?>
                <a href="<?= FFB_BASE_PATH.$nav['link']?>" style="text-decoration:none;">
                    <img src="<?= FFB_BASE_PATH.FFB_IMAGE_PATH.'navigation/'.$nav['symbol']?>" width="60px" title="<?= $nav['name']?>" border="0"><br>
                    <span style="font-size:8pt;"><?= $nav['name']?></span>
                </a>
            <?php }?>
        </div>
    <?php }?>
<?php }?>
<?php if($break == 1) {?>
<div style="clear:both"></div>
</div>
<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?php }?>

<div style="clear:both"></div>
</div>
