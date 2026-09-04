<?php

/**
 * FFB_Presenter.php
 *  
 * @author Gritschacher, Musser
 * @copyright 04/2008
 * @version 0.1 
 * 
 * Factory für die Presenter;
 *    
 */

class FFB_Presenter {
    static public function factory($presenter_class, FFB_Module $module) {
        $file = 'classes/'.$presenter_class.'.php';
        if(include($file)) {
            $class = $presenter_class;
            if(class_exists($class)) {
                $presenter = new $class($module);
                if($presenter instanceof FFB_Presenter_common) {
                    return $presenter;
                }
                die('Invalid presentation class: '.$presenter_class);
            }
            die('Presentation class not found: '.$presenter_class);
        }
        die('Presenter file not found: '.$presenter_class);
    }
}


?>