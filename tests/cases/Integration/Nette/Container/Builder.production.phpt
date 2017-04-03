<?php

/**
 * Test: Container Builder [PRODUCTION]
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Contributte\Bootstrap\Configurator;
use Nette\DI\Container;
use Tester\Assert;

/**
 * DEBUG
 */
test(function () {
	$configurator = new Configurator();
	$configurator->setTempDirectory(TEMP_DIR);

	$configurator->createRobotLoader()
		->addDirectory(APP_DIR)
		->register();

	$configurator->addConfig(APP_DIR . '/config/config.neon');
	$configurator->addConfig(APP_DIR . '/config/config.test.neon');

	try {
		$configurator->setDebugMode(FALSE);
		$container = $configurator->createContainer();
		Assert::type(Container::class, $container);
	} catch (Exception $e) {
		Assert::fail(sprintf('Building production container failed. Exception: %s.', $e->getMessage()));
	}
});
