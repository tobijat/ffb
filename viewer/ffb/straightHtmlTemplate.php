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
<script type='text/javascript'> <!--
var title = document.title;
function clock() {
   var date = new Date(); 
   var year = date.getYear();
   var month = date.getMonth();
   var day = date.getDate();
   var hour = date.getHours();
   var minute = date.getMinutes();
   var second = date.getSeconds();
   var months = new Array("JAN", "FEB", "MAR", "APR", "MAI", "JUN", "JUL", "AUG", "SEP", "OKT", "NOV", "DEZ");

   var monthname = months[month];

   if (minute < 10) {
      minute = "0" + minute;
	}

   if (second < 10) {
      second = "0" + second;
	}

	if (year < 1900) {
		year += 1900;
	}

   document.title = title + " " + monthname + " " + day + ", " + year + " - " + hour + ":" + minute + ":" + second;
   setTimeout("clock()", 1000);
}
clock();
//-->
</script>




<script type="text/javascript">
window.google_analytics_uacct = "UA-10198363-4";
</script>

<? include($this->viewer); ?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?//sollte als letztes vor dem abschließenden </body> tag stehen
include('modules/ffbapi/analyticstracking.php');

include('modules/ffbapi/amazonpreview.php');
?>

</body>
</html>
