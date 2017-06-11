<?php

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
$configurator->setDebugMode(TRUE);

// Override to original wwwDir
$configurator->addParameters([
	'wwwDir' => WWW_DIR,
]);

// Create test container
$container = $configurator->createContainer();

return $container;
