<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
    Multimedia Information Systems 2 - SS 2008 - TU Graz
    Fantasy Football Game
    Web: http://ffb.tobijat.at
    Copyright: Tobias Gritschacher & Gerald Musser
    Mail: ffb@tobijat.at
//-->
<html>
    <head>
    <title><?php
    if ($this->htmlTitle) {
        echo $this->htmlTitle;
    } else {
        echo 'Fantasyfootball - Welcome to the world of Fantasy Football!';
    }
    ?></title>
    <link rel="icon" href="http://www.tobijat.at/test/favicon.ico" type="image/vnd.microsoft.icon">

<?php include($this->viewer); ?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</body>
</html>
