<?php
  //require_once('XML/Serializer.php');

  class FFB_Presenter_img extends FFB_Presenter_common
  {
      public function __construct(FFB_Module $module)
      {
          parent::__construct($module);
      }

      public function display()
      {
          $image_array = $this->module->getData();
          header ("Content-type: image/jpeg");
          if(is_array($image_array) && !empty($image_array)) {
              foreach ($image_array as $key => $val) {
      		      if ($val instanceof GdImage || (is_resource($val) && get_resource_type($val) === 'gd')) {
      		          imagejpeg($val, null, 100);
      		          imagedestroy($val);
      		      }
      		  }
  		  }
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
