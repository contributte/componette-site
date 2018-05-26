<?php declare(strict_types = 1);

use Mangoweb\Tester\Infrastructure\InfrastructureConfigurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new InfrastructureConfigurator(__DIR__ . '/tmp');
$configurator->addConfig(__DIR__ . '/config/infrastructure.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
$configurator->addParameters([
	'appDir' => __DIR__ . '/../app/',
	'consoleMode' => false,
]);
$configurator->setupTester();
$configurator->setTimeZone('Europe/Prague');

return $configurator->getContainerFactory();
