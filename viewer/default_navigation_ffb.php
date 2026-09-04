<?php
/* //forum disabled for WM 2014
if($this->session->user_forum_lastvisit < $this->session->user_forum_lastpost) {
    $forum_symbol = 'nav_forum_newpost.png';
} else {
    $forum_symbol = 'nav_forum.png';
}
*/

$nav_array = array(
array('symbol'=>'nav_start.png','name'=>'Start','link'=>'ffb'),
array('symbol'=>'nav_team.png','name'=>'Aufstellung','link'=>'ffb/lineup'),
array('symbol'=>'nav_player.png','name'=>'Mannschaft','link'=>'ffb/myteam'),
array('symbol'=>'nav_topflop.png','name'=>'Top&Flop','link'=>'ffb/bestteam'),
array('symbol'=>'nav_results.png','name'=>'Rangliste','link'=>'ffb/userscore'),
//array('symbol'=>'nav_fav.png','name'=>'Statistiken','link'=>'ffb/stats'), //disabled for WM 2014 -> not very usable
//array('symbol'=>$forum_symbol,'name'=>'Forum','link'=>'ffb/forum/forum.html'), //__FORUM__ //forum disabled for WM 2014
array('symbol'=>'nav_help.png','name'=>'Regeln','link'=>'users/help','style'=>'small'),
//array('symbol'=>'nav_invitation.png','name'=>'Einladen','link'=>'ffb/user/invite.html','style'=>'small'),
array('symbol'=>'nav_user.png','name'=>'Account','link'=>'users/account','style'=>'small'),
array('symbol'=>'nav_profile.png','name'=>'Profil','link'=>'users/account/accountDetails.html','style'=>'small'),
array('symbol'=>'nav_shop.png','name'=>'Sportshop','link'=>'ffb/astore','style'=>'small'),
array('symbol'=>'nav_logout.png','name'=>'Ausloggen','link'=>'users/logout','style'=>'small')
);
?>