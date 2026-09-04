<?php

/**
 * FFB_Auth_Key.php
 *
 * @author Gritschacher Tobias
 * @copyright 08/2008
 * @version 0.1
 *
 * Authentication-Klasse für Key Authentication;
 *
 */

abstract class FFB_Auth_Key extends FFB_Auth {
    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        if($this->session->pictorylink_linkkey) {
            $key = $this->session->pictorylink_linkkey;
        } else {
            $key = $_GET['key'];
        }
        if($this->session->pictorylink_anr) {
            $album_id = $this->session->pictorylink_anr;
        } else {
            $album_id = $_GET['anr'];
        }
        $criteria = new Criteria();
        $criteria->add(PicPermissionPeer::PERMISSION_KEY, $key);
        $criteria->add(PicPermissionPeer::PERMISSION_ALBUM, $album_id);
        $items = PicPermissionPeer::doSelect($criteria);

        if($items) {
            $this->session->pictorylink_linkkey = $key;
            $this->session->pictory_album = $album_id;
            return true;
        } else {
            header("Location: ../start");
            exit();
        }
    }

    function __destruct() {
        parent::__destruct();
    }
}
?>