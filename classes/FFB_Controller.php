<?php

/**
 * ist der Controller der Anwendung;
 * Aufgaben:
 * -parsen der URL in Modul/Klasse/Event und Typ des Presenters
 * -aufrufen des Moduls, der Klasse (im Modul), der Methode (in der Klasse)
 * -das ausgeführet Modul wird dann weitergegeben an den Presenter, der dann alle weiteren Aufgaben übenimmt (zB: HTML anzeigen)
 *
 * @author Gritschacher Tobias
 * @copyright 12/2009
 * @version 0.3
 */

class FFB_Controller {
    const DEFAULTMODULE = 'users';  //wird aufgerufen wenn in der URL nichts spezifisches angegeben wird
    const DEFAULTCLASS = '__start';  //wird aufgerufen wenn in der URL keine Klasse angegebn wird
	const DEFAULTEVENT = '__default';  //wird aufgerufen wenn in der URL keine Methode angegebn wird
	const DEFAULTPRESENTER = 'html';  //per default wird der FFB_Presenter_html verwendet um eine HTML-Seite anzuzeigen

	private $module;
	private $class;
	private $event;
	private $presenter;
    private $subdomain;

    public function __construct() {
        $this->parseURL();
        $this->execute();
	}

    /**
     * Parst die URL in Module, Class und Event
     * @access private
     */
	private function parseURL() {
		/*
        echo 'module: '.$_GET['module'].'<br>';
		echo 'class: '.$_GET['class'].'<br>';
		echo 'event: '.$_GET['event'].'<br>';
		echo 'presenter: '.$_GET['presenter'].'<br>';
	    echo 'subdomain: '.$_GET['subdomain'].'<br>';
		exit();
		*/


        //subdomain: if set: ok, else: redirect
        if(!isset($_GET['subdomain'])) {
        	echo header("Location: http://www.tobijat.at"); //wrong URL -> redirect to portal
        } else {
            $this->subdomain = $_GET['subdomain'];
		}

        //event: if set: ok, else: default
        if(isset($_GET['event'])) {
		    $this->event = $_GET['event'];
		} else {
		    $this->event = self::DEFAULTEVENT;
		}

		//presenter: if set: ok, else: default
		if(isset($_GET['presenter'])) {
		    $this->presenter = $_GET['presenter'];
		} else {
		    $this->presenter = self::DEFAULTPRESENTER;
		}

		//module: if set: ok, else: default = subdomain
		if(isset($_GET['module']) && $_GET['module'] != '') {
		    $this->module = $_GET['module'];
		    $module_path = 'modules/'.$this->module;
            if(!is_dir($module_path)) {
                $this->setDefault();
            }
		}
		else {
		    $this->module = self::DEFAULTMODULE;
		}

		//class: if set: ok, else: default
		if(isset($_GET['class'])) {
		    $this->class = $_GET['class'];
		    $classFile = 'modules/'.$this->module.'/'.$this->class.'.php';
		    if(!file_exists($classFile)) {
		        $this->setDefault();
		    }
		} else {
		    $this->class = self::DEFAULTCLASS;
		}

		/*
		echo 'module: '.$this->module.'<br>';
		echo 'class: '.$this->class.'<br>';
		echo 'event: '.$this->event.'<br>';
		echo 'presenter: '.$this->presenter.'<br>';
		echo 'subdomain: '.$this->subdomain.'<br>';
		exit();
		*/

    }

    private function execute() {
        $classFile = 'modules/'.$this->module.'/'.$this->class.'.php';
        if(file_exists($classFile)) {
            require_once($classFile);
            //jedes Modul kann ein zusätzliches, optionales Config-File haben - dieses wird hier eingelesen
            $configFile = 'modules/'.$this->module.'/config.php';
            if(file_exists($configFile))
                require_once($configFile);
            if(class_exists($this->class)) {
                try {
                    $instance = new $this->class();
                    if (!FFB_Module::isValid($instance)) {
                        die("Requested module is not a valid FFB module!");
                    }
                    $instance->moduleName = $this->module;
                    $instance->subdomainName = $this->subdomain;
                    //Authentication überprüfen
                    if($instance->authenticate()) {
                        try {
                            $instance->presenter = 'FFB_Presenter_'.$this->presenter;
                            $event = $this->event;
                            $result = $instance->$event();
                            if(file_exists('classes/'.$instance->presenter.'.php')) {
                                $presenter = FFB_Presenter::factory($instance->presenter, $instance);
                            } else {
                                die("Could not find PresenterClass!");
                            }
                            $presenter->display();
                        } catch (Exception $error) {
                            die($error->getMessage());
                        }
                    } else {
                        //wenn ein Modul aufgerufen wird, das Authentication verlangt, aber der User ist nicht
                        //eingeloggt, dann wird die Startseite aufgerufen
                        $destination = 'http://'.$_SERVER['SERVER_NAME'].'/?destination='.$_SERVER['REQUEST_URI'];
                        header("Location: $destination");
                        exit();
                        //$this->setDefault();
                        //$this->execute();
                        //return;
                    }

                } catch (Exception $error) {
                    die($error->getMessage());
                }
            } else {
                die("An valid module for your request was not found");
            }

        } else {
            die("Could not find: $classFile");
        }
    }

    //setzt module, class, event und presenter auf default-Werte
    private function setDefault() {
		$this->module = self::DEFAULTMODULE;
        $this->class = self::DEFAULTCLASS;
        $this->event = self::DEFAULTEVENT;
        $this->presenter = self::DEFAULTPRESENTER;
    }
}

?>