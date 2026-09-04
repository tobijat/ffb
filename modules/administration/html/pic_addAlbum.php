<?php

/**
 * @author Gritschacher Tobias
 * @copyright 08/2008
 */

?>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="Tobias Gritschacher">
	<link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>administration.css" type="text/css">
    <link rel="stylesheet" href="<?echo FFB_BASE_PATH.ADM_INCLUDE_PATH?>standard.css" type="text/css">
</head>

<body>
<div id="Container">

    <div id="Navbar">
        <div id="Navigation">
            <?include(ADM_VIEWER_PATH.'navigation.php')?>
        </div>

        <div style="clear:both;"></div>
    </div>


    <div id="Main">
        <div id="administration_large">
            <div id="admintitle">Add new Album - Upload images</div><br>

            <div style="font-size:12pt; text-align:center; width:70%; margin:auto; margin-bottom:10px;">


            <applet name="jumpLoaderApplet"
	           code="jmaster.jumploader.app.JumpLoaderApplet.class"
	           archive="<?echo PIC_BASE_PATH?><?echo PIC_SCRIPT_PATH?>jupload/jumploader_z.jar" id="jumpLoaderApplet"
	           width="800"
	           height="450"
	           mayscript>
		       <param name="uc_uploadUrl" value="<?echo PIC_BASE_PATH?>administration/pic_uploadHandler/upload.html?fid=<?echo $this->pictory_fid?>"/>
		       <param name="uc_imageEditorEnabled" value="true"/>
			   <param name="uc_fileParameterName" value="file"/>
		       <param name="uc_addImagesOnly" value="true"/>
		       <param name="uc_directoriesEnabled" value="true"/>
		       <param name="uc_sendImageMetadata" value="true"/>
		       <param name="ac_fireUploaderStatusChanged" value="true"/>

			   <param name="vc_fileListViewUseThumbs" value="true"/>
		       <param name="vc_lookAndFeel" value="system"/>

		       <param name="uc_uploadScaledImagesNoZip" value="true"/>
			   <param name="uc_uploadScaledImages" value="true" />
				<param name="uc_uploadOriginalImages" value="false" />
				<param name="uc_scaledInstanceNames" value="medium,thumb" />
				<param name="uc_scaledInstanceDimensions" value="800x600,70x58" />
				<param name="uc_scaledInstanceQualityFactors" value="700,700" />
            </applet>

            <script type="text/javascript">
                var uploader = document.jumpLoaderApplet.getUploader();
                function uploaderStatusChanged( uploader ) {
                    if (uploader.getStatus() == 0) {
                        location.href = "<?echo PIC_BASE_PATH?>administration/pic_uploadHandler/displayTempImages.html?fid=<?echo $this->pictory_fid?>";
                    }
                }
            </script>
            </div>

        </div>
    </div>
<div id="Footer">
    <?include(ADM_VIEWER_PATH.'footer.php')?>
</div>
</div>


