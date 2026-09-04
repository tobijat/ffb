<?php

/**
 * PIC - addAlbum-Klasse;
 * Album managen
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 *
 */

class pic_addAlbum extends FFB_Auth_No {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'pic_addAlbum.php';
        $this->navFile = 'pictory_admin_navigation.php';
    }

    public function __default() {
        $this->showUploader();
    }

    public function showUploader() {
        $pictory_fid = md5(uniqid(time()));
        $this->pictory_fid = $pictory_fid;
    }
}
?>
