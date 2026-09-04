<?php

/**
 * FFB_Auth_Api.php
 *
 * @author Gritschacher Tobias
 * @copyright 03/2010
 * @version 0.3
 *
 * Authentication for ffbapi modules.
 * Accepts X-API-Key / Bearer token, with legacy ?pin= fallback.
 */

abstract class FFB_Auth_Api extends FFB_Auth {

    function __construct() {
        parent::__construct();
    }

    function authenticate() {
        $apiKey = FFB_ApiKey::extractFromRequest();
        if ($apiKey === '') {
            $this->denyUnauthorized('API key missing');
        }

        $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
        $keyRow = $this->findActiveKey($apiKey);
        if ($keyRow === null) {
            $this->denyUnauthorized('API key invalid');
        }

        if (!FFB_ApiKey::ipAllowed($keyRow->getApikeyIp(), $remoteAddr)) {
            $this->denyUnauthorized('API key IP not allowed');
        }

        $stored = $keyRow->getApikeyKey();
        if (!FFB_ApiKey::isDigested($stored)) {
            // Upgrade legacy plaintext token to SHA-256 at rest.
            $keyRow->setApikeyKey(FFB_ApiKey::digest($apiKey));
        }

        $keyRow->setApikeyLastcall(date('Y-m-d H:i:s', time()));
        $keyRow->save();
        return true;
    }

    /**
     * @return FfbApikey|null
     */
    private function findActiveKey(string $apiKey)
    {
        $digest = FFB_ApiKey::digest($apiKey);

        $criteria = new Criteria();
        $criteria->add(FfbApikeyPeer::APIKEY_KEY, $digest);
        $criteria->add(FfbApikeyPeer::APIKEY_STATUS, 1);
        $criteria->setLimit(1);
        $rows = FfbApikeyPeer::doSelect($criteria);
        if (count($rows) > 0) {
            return $rows[0];
        }

        // Legacy plaintext rows (pre-hash migration).
        $criteria = new Criteria();
        $criteria->add(FfbApikeyPeer::APIKEY_KEY, $apiKey);
        $criteria->add(FfbApikeyPeer::APIKEY_STATUS, 1);
        $criteria->setLimit(1);
        $rows = FfbApikeyPeer::doSelect($criteria);
        if (count($rows) > 0) {
            return $rows[0];
        }

        return null;
    }

    /**
     * API callers should not be bounced to the HTML login page.
     */
    private function denyUnauthorized(string $reason): void
    {
        error_log('[FFB API] unauthorized: ' . $reason . ' ip=' . ($_SERVER['REMOTE_ADDR'] ?? ''));

        if (!headers_sent()) {
            http_response_code(401);
            $presenter = isset($_GET['presenter']) ? (string) $_GET['presenter'] : 'html';
            if ($presenter === 'xml') {
                header('Content-Type: text/xml; charset=UTF-8');
                echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
                echo '<response><administration_status>401</administration_status>';
                echo '<ffb_status>401</ffb_status><user_status>401</user_status>';
                echo '<administration_answer>Unauthorized</administration_answer>';
                echo '<ffb_answer>Unauthorized</ffb_answer>';
                echo '<user_answer>Unauthorized</user_answer></response>';
                exit();
            }
            header('Content-Type: text/plain; charset=UTF-8');
        }
        echo 'Unauthorized';
        exit();
    }

    function __destruct() {
        parent::__destruct();
    }
}

?>
