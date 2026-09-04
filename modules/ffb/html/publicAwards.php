<?php

/**
 *
 * @author Musser Gerald
 * @copyright 03/2010
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="geri">
	<link rel="stylesheet" href="<?php echo FFB_BASE_PATH.FFB_INCLUDE_PATH?>standard.css" type="text/css">
    <script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>prototype.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.SCRIPT_PATH?>constants.js"></script>
	<script type="text/javascript" src="<?echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>countdown.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>info_popup.js"></script>
	<script type="text/javascript" src="<?php echo FFB_BASE_PATH.FFB_SCRIPT_PATH?>awards.js"></script>
	
</head>
<body onload="javascript:showUserAwards(<? echo $this->webUserId; ?>);return false;" >
<div id="Mainleft">
<?
//dispUserawardPopup
	if(!$this->webUserId || $this->webUserId==null || $this->webUserId==0){
	?>
	<b>Leider wurde kein Soccer Sportsfan Benutzer zu diesem Facebook Account gefunden.</b>
	<?		
	}
?>
</div>
</body>