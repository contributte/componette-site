<?php declare(strict_types = 1);

use Nette\Configurator;

// Require base bootstrap
require_once __DIR__ . '/bootstrap.php';

// Create container
$configurator = new Configurator();
$configurator->setTempDirectory(TMP_DIR);

$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->register();

$configurator->addConfig(APP_DIR . '/config/config.neon');

// Add test configs and test environments
$configurator->addConfig(APP_DIR . '/config/config.test.neon');

// Setup debugMode of course!
$configurator->setDebugMode(true);

// Override to original wwwDir
$configurator->addParameters([
	'wwwDir' => WWW_DIR,
]);

// Create test container
return $configurator->createContainer();
