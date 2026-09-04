<?php

/**
 * PIC - uploadHandler-Klasse;
 * bietet verschiedene funktionen zum anpassen von images
 * wird zB vom Upload Applet aufgerufen
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 *
 */

class pic_uploadHandler extends FFB_Auth_AdminPictory {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'pic_addAlbum.php';
        $this->navFile = 'pictory_admin_navigation.php';
    }

    public function __default() {
        $this->upload();
    }

    //called by default and by upload applet
    public function upload() {
        //$file_param_name = 'file';
        $file_param_name = 'medium';
        $fid = $_GET['fid'];
        $file_orig = $_FILES[$file_param_name]['name'];
        $file_ext = substr($file_orig, strripos($file_orig, '.'));
        $file_name = md5(uniqid(time()));
        //$exif_xml = $_POST["imageMetadataXml"];

        $source_file_path = $_FILES[$file_param_name]['tmp_name'];
        $target_file_path = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."upload/".$file_name;

        $exif = exif_read_data($source_file_path, 0, true);
        $datetime = $exif['EXIF']['DateTimeOriginal'];

        $im = $this->returnImageHandler($source_file_path, $file_ext);
        //$im_new = $this->changeDimension($im, IMAGE_MAX_DIM); //resize
        $im_new = $im; //no resize

        if($this->copyImageToFolder($im_new, $target_file_path)) {
	        $this->administration_answer = "success";
	        $new_entry = new PicTempimage();
            $new_entry->setTempimageName($file_name);
            $new_entry->setTempimageFid($fid);
            //add EXIF-DateTime if available
            if($datetime) {
                $new_entry->setTempimageDate(strtotime($datetime));
            } else {
                //$new_entry->setTempimageDate(time());
                $new_entry->setTempimageDate(0);
            }
            $new_entry->setTempimageUploaddate(microtime());
            $new_entry->setTempimageUser($this->session->user_id);
            $new_entry->save();
        } else {
	       $this->administration_answer = "error";
        }
    }

    //rezize image
    private function changeDimension($im, $max) {
        $new_dim = $max;
        $width = ImageSX($im);
        $height = ImageSY($im);
        if($width > $height) {
            $new_width = $new_dim;
            $new_height = ($height/$width)*$new_dim;
        } else {
            $new_height = $new_dim;
            $new_width = ($width/$height)*$new_dim;
        }
        $im2 = imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($im2,$im,0,0,0,0,$new_width,$new_height,$width,$height);
        return $im2;
    }

    //get image resource
    private function returnImageHandler($im, $ext) {
        if(strcmp(strtolower($ext),'.jpg')==0 || strcmp(strtolower($ext),'.jpeg')==0) {
            return imagecreatefromjpeg($im);
        } elseif(strcmp(strtolower($ext),'.png')==0) {
            return imagecreatefrompng($im);
        } elseif(strcmp(strtolower($ext),'.gif')==0) {
            return imagecreatefromgif($im);
        } else {
            return false;
        }
    }

    //copy JPEG to upload folder and change quality
    private function copyImageToFolder($im, $target) {
        return(imagejpeg($im, $target.'.jpg', IMAGE_JPG_QUALITY));
    }

    //display users albums
    public function displayTempImages() {
        $this->htmlFile = 'pic_tempImages.php';
        //$fid = $_GET['fid'];
        $criteria = new Criteria();
        $criteria->add(PicTempimagePeer::TEMPIMAGE_USER, $this->session->user_id);
        $criteria->addAscendingOrderByColumn(PicTempimagePeer::TEMPIMAGE_DATE);
        $image_items = PicTempimagePeer::doSelect($criteria);
        $i=0;
        $images = array();
        if($image_items) {
            foreach($image_items as $item) {
                $images[$i]['image_id'] = $item->getTempimageId();
                $images[$i]['image_name'] = $item->getTempimageName();
                $images[$i]['image_fid'] = $item->getTempimageFid();
                if($item->getTempimageDate()) {
                    $images[$i]['image_date'] = date('Y-m-d H:i',$item->getTempimageDate());
                } else {
                    $images[$i]['image_date'] = 0;
                }
                $images[$i]['image_uploaddate'] = date('Y-m-d H:i',$item->getTempimageUploaddate());
                $images[$i]['image_comment'] = 0;
                $images[$i]['image_location'] = 0;
                $i++;
            }
        }
        $this->num_results = $i;
        $this->images = $images;
    }

    public function deleteImage() {
        $this->deleteImageById($_POST['pid']);
    }

    public function deleteImageById($picture_id)  {
        //$picture_id = $_POST['pid'];
        $item = PicTempimagePeer::retrieveByPK($picture_id);
        $image_name = $item->getTempimageName().'.jpg';
        $image_path = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."upload/";
        if(unlink($image_path.$image_name)) {
            PicTempimagePeer::doDelete($item);
            $this->administration_answer = 'Temporary image successfully deleted!';
            $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
        } else {
            $this->administration_answer = 'Error while deleting temporary image!';
            $this->administration_status = STATUS_CODE_ERROR;
        }
    }

    public function addImageToAlbum() {
        $image_id = $_POST['image_id'];
        $image_album = $_POST['image_album'];
        $image_album_name = $_POST['image_album_name'];
        $image_comment = $_POST['comment'];
        $image_location = $_POST['location'];
        $image_date = $_POST['date'];
        $image_dateflag = 1;
        $image_sortflag = $_POST['sortflag'];
        if($_POST['symbol'] != 0)
            $image_symbol = $_POST['symbol'];
        $item = PicTempimagePeer::retrieveByPK($image_id);
        $image_name = $item->getTempimageName().'.jpg';
        $image_source_path = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."upload/";
        $image_dest_path = $_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$image_album_name.'/';

        //unlink($image_dest_path.$item->getTempimageName().'/'.$image_name);
        //rmdir($image_dest_path.$item->getTempimageName());
        $file_ext = '.jpg';
        $im = $this->returnImageHandler($image_source_path.$image_name, $file_ext);
        $im_new = $this->changeDimension($im, 150);

        if($this->copyImageToFolder($im_new, $image_dest_path.'thumbs/'.$image_name) &&
           copy($image_source_path.$image_name, $image_dest_path.'pictures/'.$image_name)) {
            $new_image = new PicImage();
            $new_image->setImageName($image_name);
            $new_image->setImageComment($image_comment);
            $new_image->setImageLocation($image_location);
            $new_image->setImageAlbum($image_album);
            $new_image->setImageDate($image_date);
            $new_image->setImageDateflag($image_dateflag);
            $new_image->setImageSortflag($image_sortflag);
            $new_image->setImageCounter(0);

            $new_image->save();

            if($image_symbol) {
                $album = PicAlbumPeer::retrieveByPK($image_album);
                $album->setAlbumSymbol($new_image->getImageId());
                $album->save();
            }

            //$this->deleteImageById($image_id);

            $this->administration_answer = 'New Image added to Album!';
            $this->administration_status = STATUS_CODE_SUCCESS;
        } else {
            $this->administration_answer = 'Problem while adding new Image to Album!';
            $this->administration_status = STATUS_CODE_ERROR;
        }

    }

    public function createNewAlbum() {
        $this->post = $_POST;
        //exit();
        $album_title = $_POST['album_title'];
        $album_date = $_POST['album_date_year'].'-'.$_POST['album_date_month'].'-'.$_POST['album_date_day'];
        $album_category = $_POST['album_category'];
        $album_owner = $this->session->user_id;
        if($_POST['album_dateflag']) {
            $album_dateflag = 1;
        } else {
            $album_dateflag = 0;
        }
        $album_status = 1;
        $album_counter = 0;
        $album_name = md5(uniqid(time()));
        //$album_symbol = $_POST['album_symbol'];
        $new_album = new PicAlbum();
        $new_album->setAlbumTitle($album_title);
        $new_album->setAlbumDate($album_date);
        $new_album->setAlbumCategory($album_category);
        $new_album->setAlbumOwner($album_owner);
        $new_album->setAlbumDateflag($album_dateflag);
        $new_album->setAlbumStatus($album_status);
        $new_album->setAlbumCounter($album_counter);
        $new_album->setAlbumName($album_name);

        $new_album->save();

        $new_id = $new_album->getAlbumId();
        if($new_id) {
            $this->administration_answer = 'New Album successfully created!';
            $this->administration_status = STATUS_CODE_SUCCESS;
            //create new folders
            mkdir($_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name);
            mkdir($_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name.'/thumbs');
            mkdir($_SERVER['DOCUMENT_ROOT'].PIC_IMAGE_PATH."album/".$album_name.'/pictures');
            //set permission
            $user = WebUserPeer::retrieveByPK($this->session->user_id);
            $email = $user->getUserEmail();
            $criteria = new Criteria();
            $criteria->add(PicPermissionPeer::PERMISSION_EMAIL, $email);
            //$criteria->add(PicPermissionPeer::PERMISSION_ALBUM, $anr);
            $criteria->setLimit(1);
            $exist_items = PicPermissionPeer::doSelect($criteria);
            if($exist_items) {
                $new_key = $exist_items[0]->getPermissionKey();
            } else {
                $new_key = md5(uniqid(time()));
            }
            $new_permission = new PicPermission();
            $new_permission->setPermissionKey($new_key);
            $new_permission->setPermissionAlbum($new_id);
            $new_permission->setPermissionEmail($address);
            $new_permission->setPermissionStatus('active');
            $new_permission->setPermissionOwner('system');
            $new_permission->save();

        } else {
            $this->administration_answer = 'Error while creating new Album. Try again!';
            $this->administration_status = STATUS_CODE_ERROR;
        }
        $this->new_id = $new_id;
        $this->album_name = $album_name;
        //$new_album->setAlbumSymbol($album_symbol);
    }
}
?>
