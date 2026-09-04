<?php

/**
 * FFB_Configuration.php
 *
 * @author Gritschacher Tobias
 * @copyright 12/2009
 * @version 0.2
 */

class FFB_Configuration {
	private $values = array();
	private $area;

	public function __construct($area) {
		$this->area = $area;
		$this->setConfig();
	}

    public function __destruct() {

    }

	private function setConfig() {
		$file = 'area_config_'.$this->area.'.xml';
		$this->values['config_file'] = $file;
		$xml = @simplexml_load_file($file);
		if ($xml === false) {
			throw new RuntimeException('Unable to load area config: ' . $file);
		}

		foreach($xml->children() as $cfg) {
			$cfg_name = $cfg->getName();
			$cfg_value = $cfg;
			$this->values[$cfg_name] = $cfg_value;
		}
	}

	public function __get($var) {
		return $this->values[$var] ?? null;
	}
}

?>