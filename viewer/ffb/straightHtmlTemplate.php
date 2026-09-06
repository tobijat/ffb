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
	if ($this->htmlTitle)
	{ echo $this->htmlTitle; }
	else { echo "Fantasyfootball - Welcome to the world of Fantasy Football!"; }
	?></title>
    <!--<link rel="icon" href="<?php echo FFB_BASE_PATH.FFB_IMAGE_PATH?>symbols/favicon.ico" type="image/vnd.microsoft.icon">//-->
    <link rel="icon" href="http://www.tobijat.at/test/favicon.ico" type="image/vnd.microsoft.icon">

<script type="text/javascript">
window.google_analytics_uacct = "UA-10198363-4";
</script>

<?php  include($this->viewer); ?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php //sollte als letztes vor dem abschließenden </body> tag stehen
include('modules/ffbapi/analyticstracking.php');
?>

</body>
</html>
