<?php

/**
 * FFB_Object_DB.php
 *
 * @author Gritschacher, Musser
 * @copyright 05/2008
 * @version 0.2
 *
 * Basis-Klasse für alle Module die eine DB benötigen;
 * stellt Zugang zur Datenbank über Propel her
 *
 */

abstract class FFB_Object_DB extends FFB_Object {

	protected $db;

    public function __construct() {
        parent::__construct();
        require_once 'propel/Propel.php';
        Propel::init(PROPEL_CONFIG_FILE);
        require_once('DB.php');

		static $connection = null;
          if ($connection === null) {
              $connection = DB::connect(FFB_DB_LOGIN);
              if (!PEAR::isError($connection)) {
                  $connection->setFetchMode(DB_FETCHMODE_ASSOC);
              } else {
                  throw new Exception($connection->getMessage());
              }
          }

          $this->db = $connection;

    }

    public function __destruct() {
        parent::__destruct();
        $this->db->disconnect();
        //$this->db->mysql_close();
    }
}

?>