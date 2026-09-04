<?php
  require_once('XML/Serializer.php');

  class FFB_Presenter_xml extends FFB_Presenter_common
  {
      public function __construct(FFB_Module $module)
      {
          parent::__construct($module);
      }

      public function display()
      {
          $xml = new XML_Serializer();
          $xml->setOption(XML_SERIALIZER_OPTION_CDATA_SECTIONS, true);
          $xml->serialize($this->module->getData());

          header("Content-Type: text/xml; charset=UTF-8");
          echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
          echo $xml->getSerializedData();
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
