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
          header ("Content-type: image/png");
          if(is_array($image_array) && !empty($image_array)) {
              foreach ($image_array as $key => $val) {
      		      imagejpeg ( $val , "" , 100 );
      		      imagedestroy($val);
      		  }
  		  }
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
