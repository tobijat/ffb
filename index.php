<?php

/**
 * sorgt daf�r, dass alle Klassen geladen werden k�nnen, l�d die config.php und ruft den Controller auf;
 * ist sonst f�r nix mehr zust�ndig; 
 *  
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.2 
 */

require_once('config.php');

//autoload-function damit die Klassen onDemand geladen werden
spl_autoload_register(function ($class) {
    $classfile = 'classes/'.$class.'.php';
    if (is_file($classfile))
	    include_once $classfile;
});  //damit das propel auch zufrieden is

//Controller aufrufen und ihm jede weitere Verantwortung �berlassen
$controller = new FFB_Controller();

?>
