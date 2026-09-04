<?php

declare(strict_types=1);

/**
 * Ensures permanent test user + ephemeral contract-test game world.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use FFB\Tests\Support\FixtureManager;

$fixtures = new FixtureManager();
$state = $fixtures->setup();

echo "Fixture game ready:\n";
foreach ($state as $key => $value) {
    echo "  {$key}={$value}\n";
}
echo "Seed complete.\n";
