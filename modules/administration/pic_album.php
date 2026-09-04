<?php

/**
 * PICTORY - ADMIN - ALBUM-Klasse;
 * Fotoalben verwalten
 *
 * @author Gritschacher Tobias
 * @copyright 08/2008
 * @version 0.1
 *
 */

class pic_album extends FFB_Auth_AdminPictory {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'pic_album.php';
    }

    public function deleteAlbum() {
        $aid = $_GET['id'];
        $album = PicAlbumPeer::retrieveByPK($aid);
        //$album = 'fa5e04db75993320c0f46a55ea08884d';
        if($album) {
            $album_name = $album->getAlbumName();
            //$album_name = $album;
            $alb_dir = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name;
            $dir = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name.'/thumbs';
            $dir2 = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name.'/pictures';
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if($file != '.' && $file != '..') {
                            unlink($dir.'/'.$file);
                            echo "filename: $file <br>";// : filetype: " . filetype($dir . $file) . "\n";
                        }
                    }
                    closedir($dh);
                }
            }
            if (is_dir($dir2)) {
                if ($dh = opendir($dir2)) {
                    while (($file = readdir($dh)) !== false) {
                        if($file != '.' && $file != '..') {
                            unlink($dir2.'/'.$file);
                            echo "filename: $file <br>";// : filetype: " . filetype($dir . $file) . "\n";
                        }
                    }
                    closedir($dh);
                }
            }
            rmdir($dir);
            rmdir($dir2);
            rmdir($alb_dir);
        }
        exit();
    }

    public function testApplet() {
        //echo 'hallo!';
        //exit();
        $this->htmlFile = 'pic_album.php';
    }

    public function exif() {
        $picturepath = PIC_BASE_PATH.PIC_IMAGE_PATH.'upload/';
        //$dir = dir($picturepath);
        $exif = exif_read_data($picturepath.'DSCN4726.JPG', 0, true);
        //print_r($exif);
        //echo '<br>';
        //echo $exif['DateTime'];
        echo strtotime($exif['EXIF']['DateTimeOriginal']).'<br>';
/*
        foreach ($exif as $key => $section) {
            foreach ($section as $name => $val) {
                echo "$key.$name: $val<br />\n";
            }
        }
*/
        //echo '<br>';
        //echo $exif['DateTimeDigitized'];

        //echo strtotime($exif['DateTimeDigitized']);

        exit();
    }

    public function __default() {
        echo 'funkt!';
        exit();

        $this->post = $_POST;


        $picturepath = PIC_IMAGE_PATH.'album/20080808_strassburg/pictures/';
        $dir = dir($picturepath);
        while($entry = $dir->read()) {
            if($entry != '.' && $entry != '..') {
                $image = new PicImage();
                $image->setImageName($entry);
                $image->setImageComment('');
                $image->setImageAlbum(1);
                //$image->setImageDate(0);
                $image->setImageDateFlag(0);
                $image->setImageCounter(0);
                //$image->save();
                echo $entry.'<br>';
            }
        }

        $dir->close();
        exit();
    }

    public function generateKey() {
        echo md5(uniqid(time()));
        exit();
    }
}
?>
