<?php

declare(strict_types=1);

require dirname(__DIR__) . '/tests/vendor/autoload.php';

use FFB\Tests\Support\FixtureManager;
use FFB\Tests\Support\TestServer;

$fixtures = new FixtureManager();
$state = $fixtures->setup();
FixtureManager::applyEnv($state);

register_shutdown_function(static function () use ($fixtures): void {
    try {
        $fixtures->teardown();
    } catch (Throwable $e) {
        fwrite(STDERR, '[FFB fixtures] teardown failed: ' . $e->getMessage() . PHP_EOL);
    }
    TestServer::stop();
});

TestServer::ensureRunning();
