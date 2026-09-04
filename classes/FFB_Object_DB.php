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

    /** @var \DB_common|null Shared PEAR DB connection */
    private static $sharedConnection = null;

    public function __construct() {
        parent::__construct();
        require_once 'propel/Propel.php';
        Propel::init(PROPEL_CONFIG_FILE);
        require_once('DB.php');

        if (self::$sharedConnection === null) {
            $connection = DB::connect(FFB_DB_LOGIN);
            if (PEAR::isError($connection)) {
                throw new Exception($connection->getMessage());
            }
            $connection->setFetchMode(DB_FETCHMODE_ASSOC);
            self::$sharedConnection = $connection;
        }

        $this->db = self::$sharedConnection;
    }

    public function __destruct() {
        parent::__destruct();
        // Do not disconnect the shared PEAR connection here — other module
        // instances (and subsequent requests in the same process) still need it.
    }
}

?>