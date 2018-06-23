<?php declare(strict_types = 1);

/**
 * Test: Container Builder [DEVELOPMENT]
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use Contributte\Bootstrap\ExtraConfigurator;
use Nette\DI\Container;
use Tester\Assert;

/**
 * DEBUG
 */
test(function (): void {
	$configurator = new ExtraConfigurator();
	$configurator->setTempDirectory(TEMP_DIR);

	$configurator->createRobotLoader()
		->addDirectory(APP_DIR)
		->register();

	$configurator->addConfig(APP_DIR . '/config/config.neon');
	$configurator->addConfig(APP_DIR . '/config/config.test.neon');

	try {
		$configurator->setDebugMode(true);
		$container = $configurator->createContainer();
		Assert::type(Container::class, $container);
	} catch (Throwable $e) {
		Assert::fail(sprintf('Building development container failed. Exception: %s.', $e->getMessage()));
	}
});
