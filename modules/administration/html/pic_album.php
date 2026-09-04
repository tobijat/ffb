<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="author" content="Gritschacher Tobias">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
	<title><?echo strtoupper($this->module->moduleName).' - '.strtoupper($this->module->name)?> - Welcome to the world of Fantasy Football!</title>
</head>
<body onload="init();">
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>

    <div id="Main">

        <div id="administration">

            <applet archive="<?echo PIC_BASE_PATH.PIC_SCRIPT_PATH;?>jupload-V3.4.1f-src.jar" width="320" height="240">
            </applet>
        </div>
    </div>
</div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>
