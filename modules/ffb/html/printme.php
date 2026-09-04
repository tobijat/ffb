<?php

/**
 * @author Gritschacher, Musser
 * @copyright 2008
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-15">
	<meta name="author" content="Gerald Musser">
</head>

<body onload="javascript:window.print(); javascript:window.close();">
http://ffb.tobijat.at<br />
<ol>
<?
//print_r($this->userteams);
	foreach($this->userteams[0]['players'] as $elem) {
		echo 	"<li>" .
			 	html_entity_decode(htmlentities($elem['player_fname'], ENT_QUOTES, "UTF-8"), ENT_QUOTES, "ISO-8859-15") .
			 	" ";
			 	
		if(strcmp($elem['player_fname'], $elem['player_lname'])!=0){
			echo	html_entity_decode(htmlentities($elem['player_lname'], ENT_QUOTES, "UTF-8"), ENT_QUOTES, "ISO-8859-15");
		}
	 	echo	" " . $elem['playerteam_player_position'] . " " . $elem['player_nationality'] ."</li>";
	}

?>
</ol>
<a href="javascript:window.print();"><img src="../../images/ffb/symbols/print.gif" /></a>

