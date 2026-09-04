<?php

/**
 * FFB_Presenter_html.php
 *
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1
 *
 * HTML-Presenter: Gibt HTML am Bildschirm aus
 */

class FFB_Presenter_html extends FFB_Presenter_common {
    private $template = null;
    private $path = null;
    private $viewer = null;
    private $data;
    public $recaptcha_html;
    public $nav_items;

    public function __construct(FFB_Module $module) {
        parent::__construct($module);
        //for recaptcha:
        require_once(INCLUDE_PATH.'recaptcha/recaptchalib.php');
        $recaptcha_publickey = FFB_RECAPTCHA_PUBLICKEY;
        $this->recaptcha_html = recaptcha_get_html($recaptcha_publickey);
        $this->data = array();
        $this->nav_items = array();
    }

    public function display() {
        //HTML-Dateinamen holen
        if($this->module->htmlFile == null) {
            //default: HTML-dateiname = <Klassenname>.php
            $htmlFile = $this->module->name.'.php';
        } else {
            $htmlFile = $this->module->htmlFile;
        }
        //$htmlFile = $this->module->htmlFile;
        $this->viewer = 'modules/'.$this->module->moduleName.'/html/'.$htmlFile;
        if(!file_exists($this->viewer)) {
            die('There is no viewer-file named '.$this->viewer.'!');
        }

        //Daten aus dem Modul holen
        $this->data = $this->module->getData();

        //HTML-Page-Template-Dateinamen holen
        if($this->module->pageTemplateFile == null) {
            //default:
            $pageTemplateFile = 'straightHtmlTemplate.php';
        } else {
            $pageTemplateFile = $this->module->pageTemplateFile;
        }

        //NavigationItems holen
        $navFile = VIEWER_PATH.'default_navigation_'.$this->module->moduleName.'.php';

        //echo 'nav: '.$this->module->navFile;
        if($this->module->navFile) {
            $nav_items = FFB_MODULE_PATH.$this->module->moduleName.'/html/'.$this->module->navFile;
            //echo '<br>path: '.$nav_items;
            if(is_file($nav_items))
                $navFile = $nav_items;
        }

        include($navFile);
        $this->addNavItems($nav_array);

        /*
        //echo FFB_BASE_PATH.FFB_MODULE_PATH.$this->module->moduleName.'/html/'.$this->module->name.'_navigation.php';
        $nav_items_class = FFB_MODULE_PATH.$this->module->moduleName.'/html/'.$this->module->name.'_navigation.php';
        $nav_items_module = FFB_MODULE_PATH.$this->module->moduleName.'/html/navigation.php';
        if(is_file($nav_items_module)) {
            include($nav_items_module);
            $this->addNavItems($nav_array);
        } elseif(is_file($nav_items_class)) {
            include($nav_items_class);
            $this->addNavItems($nav_array);
        } else {
            include(FFB_VIEWER_PATH.'default_navigation.php');
            $this->addNavItems($nav_array);
        }
        */
        //PageTemplate anzeigen
        //include(VIEWER_PATH.$pageTemplateFile);
        //include(VIEWER_PATH.$this->module->subdomainName.'/'.$pageTemplateFile);
        include(VIEWER_PATH.$this->module->config->area_prefix.'/'.$pageTemplateFile);
    }

    private function addNavItems($items) {
        foreach($items as $item) {
            array_push($this->nav_items, $item);
        }
    }

    //__get-Interceptor: liefert bei Anfrage die gewünschte Eigenschaft aus den DATA-Array des Moduls zurück;
    public function __get($property) {
        if(isset($this->data[$property])) {
            return $this->data[$property];
        }
        return null;
    }
}

?>