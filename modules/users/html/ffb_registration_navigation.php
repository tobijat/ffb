<?php
/*
$nav_array = array(
array('symbol'=>'nav_left.png','name'=>'zur&uuml;ck','link'=>'__BACK__'),
);
*/
if($this->session->user_id > 0) {
    $nav_array = array(
    array('symbol'=>'nav_start.png','name'=>'Start','link'=>'ffb'),
    array('symbol'=>'nav_team.png','name'=>'Aufstellung','link'=>'ffb/teammanagement/lineup.html'),
    array('symbol'=>'nav_player.png','name'=>'Mannschaft','link'=>'ffb/teammanagement/myteam.html'),
    array('symbol'=>'nav_topflop.png','name'=>'Top&Flop','link'=>'ffb/teammanagement/bestteam.html'),
    array('symbol'=>'nav_results.png','name'=>'Rangliste','link'=>'ffb/user/score.html'),
    //array('symbol'=>'nav_forum.png','name'=>'Forum','link'=>'__FORUM__'), //forum disabled for WM 2014
    array('symbol'=>'nav_help.png','name'=>'Regeln','link'=>'users/help','style'=>'small'),
    //array('symbol'=>'nav_invitation.png','name'=>'Einladen','link'=>'ffb/user/invite.html','style'=>'small'),
    array('symbol'=>'nav_user.png','name'=>'Account','link'=>'users/registration','style'=>'small'),
    array('symbol'=>'nav_profile.png','name'=>'Profil','link'=>'users/account/accountDetails.html','style'=>'small'),
    array('symbol'=>'nav_logout.png','name'=>'Ausloggen','link'=>'users/logout','style'=>'small')
    );
} else {
    $nav_array = array(
    array('symbol'=>'nav_start.png','name'=>'Start','link'=>'ffb'),
    array('symbol'=>'nav_user.png','name'=>'Registrieren','link'=>'users/registration'),
    array('symbol'=>'nav_help.png','name'=>'Regeln','link'=>'welcome/help'),
    array('symbol'=>'nav_login.png','name'=>'Anmelden','link'=>'ffb')
    );
}
?>
